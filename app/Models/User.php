<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Spatie\Permission\Traits\HasRoles;
use App\Models\Scopes\LicenseScope;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasApiTokens, HasRoles;

    /**
     * The "booted" method of the model.
     */
    protected static function booted(): void
    {
        // تطبيق License Scope على المستخدمين (عدا Super Admin)
        static::addGlobalScope(new LicenseScope());
    }

    /**
     * Scope للحصول على البيانات بدون عزل (للـ Super Admin فقط)
     */
    public function scopeWithoutLicenseScope($query)
    {
        return $query->withoutGlobalScope(LicenseScope::class);
    }

    /**
     * Scope للحصول على البيانات لترخيص محدد
     */
    public function scopeForLicense($query, $licenseId)
    {
        return $query->withoutGlobalScope(LicenseScope::class)
                    ->where('license_id', $licenseId);
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'address',
        'company_name',
        'tax_number',
        'user_type',
        'status',
        'notes',
        'locale',
        'license_id',
        'user_role',
        'is_account_active',
        'account_expiry_date',
        'warehouse_id',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_account_active' => 'boolean',
            'account_expiry_date' => 'date',
        ];
    }

    // العلاقات
    public function orders()
    {
        return $this->hasMany(Order::class, 'customer_id');
    }

    public function createdOrders()
    {
        return $this->hasMany(Order::class, 'created_by');
    }

    public function invoices()
    {
        return $this->hasMany(Invoice::class, 'customer_id');
    }

    public function collections()
    {
        return $this->hasMany(Collection::class, 'customer_id');
    }

    public function collectedPayments()
    {
        return $this->hasMany(Collection::class, 'collected_by');
    }

    public function returns()
    {
        return $this->hasMany(ReturnOrder::class, 'customer_id');
    }

    public function processedReturns()
    {
        return $this->hasMany(ReturnOrder::class, 'processed_by');
    }

    // دوال مساعدة للصلاحيات
    public function isAdmin(): bool
    {
        return $this->user_type === 'admin' || $this->hasRole('super_admin') || $this->hasRole('admin');
    }

    public function isManager(): bool
    {
        return $this->user_type === 'manager' || $this->hasRole('manager');
    }

    public function isEmployee(): bool
    {
        return $this->user_type === 'employee' || $this->hasRole('employee');
    }

    public function isCustomer(): bool
    {
        return $this->user_type === 'customer' || $this->hasRole('customer');
    }

    // دالة للحصول على جميع الصلاحيات (مباشرة + عبر الأدوار)
    public function getAllPermissionNames(): array
    {
        return $this->getAllPermissions()->pluck('name')->toArray();
    }

    // دالة للتحقق من صلاحية معينة مع fallback للنوع القديم
    public function hasPermissionTo($permission, $guardName = null): bool
    {
        // استخدام Spatie Permission أولاً
        if (method_exists(parent::class, 'hasPermissionTo')) {
            $hasSpatie = parent::hasPermissionTo($permission, $guardName);
            if ($hasSpatie) {
                return true;
            }
        }

        // Fallback للنظام القديم
        return $this->hasLegacyPermission($permission);
    }

    // دالة للتوافق مع النظام القديم
    private function hasLegacyPermission($permission): bool
    {
        return match($this->user_type) {
            'admin' => true, // المدير له جميع الصلاحيات
            'manager' => in_array($permission, [
                'dashboard.view', 'users.view', 'employees.view',
                'orders.view', 'orders.create', 'orders.edit',
                'invoices.view', 'invoices.create', 'reports.view'
            ]),
            'employee' => in_array($permission, [
                'dashboard.view', 'orders.view', 'items.view'
            ]),
            'customer' => in_array($permission, [
                'orders.view', 'orders.create', 'invoices.view'
            ]),
            default => false
        };
    }

    // العلاقة مع الترخيص
    public function license()
    {
        return $this->belongsTo(SystemLicense::class, 'license_id');
    }

    // العلاقة مع منشئ المستخدم
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    // المستخدمين الذين أنشأهم هذا المستخدم
    public function createdUsers()
    {
        return $this->hasMany(User::class, 'created_by');
    }
}
