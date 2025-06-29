<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Carbon\Carbon;

class Company extends BaseModel
{
    use HasFactory;

    protected $fillable = [
        'company_code',
        'company_name',
        'company_name_en',
        'registration_number',
        'registration_date',
        'expiry_date',
        'company_type',
        'status',
        'country',
        'city',
        'address',
        'phone',
        'email',
        'website',
        'contact_person',
        'contact_phone',
        'license_number',
        'license_issue_date',
        'license_expiry_date',
        'gmp_status',
        'gmp_expiry_date',
        'notes',
        'documents',
    ];

    protected $casts = [
        'registration_date' => 'date',
        'expiry_date' => 'date',
        'license_issue_date' => 'date',
        'license_expiry_date' => 'date',
        'gmp_expiry_date' => 'date',
        'documents' => 'array',
    ];

    /**
     * العلاقة مع المنتجات الدوائية
     */
    public function pharmaceuticalProducts(): HasMany
    {
        return $this->hasMany(PharmaceuticalProduct::class);
    }

    /**
     * العلاقة مع إجازات الفحص
     */
    public function inspectionPermits(): HasMany
    {
        return $this->hasMany(InspectionPermit::class);
    }

    /**
     * العلاقة مع إجازات الاستيراد
     */
    public function importPermits(): HasMany
    {
        return $this->hasMany(ImportPermit::class);
    }

    /**
     * التحقق من انتهاء صلاحية التسجيل
     */
    public function isExpired(): bool
    {
        return $this->expiry_date && $this->expiry_date->isPast();
    }

    /**
     * التحقق من انتهاء صلاحية الترخيص
     */
    public function isLicenseExpired(): bool
    {
        return $this->license_expiry_date && $this->license_expiry_date->isPast();
    }

    /**
     * التحقق من انتهاء صلاحية GMP
     */
    public function isGmpExpired(): bool
    {
        return $this->gmp_expiry_date && $this->gmp_expiry_date->isPast();
    }

    /**
     * الحصول على الأيام المتبقية للانتهاء
     */
    public function getDaysUntilExpiry(): ?int
    {
        if (!$this->expiry_date) {
            return null;
        }

        return now()->diffInDays($this->expiry_date, false);
    }

    /**
     * الحصول على حالة التنبيه
     */
    public function getAlertStatus(): string
    {
        $daysUntilExpiry = $this->getDaysUntilExpiry();

        if ($daysUntilExpiry === null) {
            return 'no_expiry';
        }

        if ($daysUntilExpiry < 0) {
            return 'expired';
        }

        if ($daysUntilExpiry <= 30) {
            return 'critical';
        }

        if ($daysUntilExpiry <= 90) {
            return 'warning';
        }

        return 'normal';
    }

    /**
     * نطاقات الاستعلام
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeByType($query, $type)
    {
        return $query->where('company_type', $type);
    }

    public function scopeExpiringWithin($query, $days)
    {
        return $query->where('expiry_date', '<=', now()->addDays($days))
                    ->where('expiry_date', '>=', now());
    }

    public function scopeExpired($query)
    {
        return $query->where('expiry_date', '<', now());
    }

    public function scopeByCountry($query, $country)
    {
        return $query->where('country', $country);
    }
}
