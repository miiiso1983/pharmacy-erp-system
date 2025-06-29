<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class SystemLicense extends Model
{
    use HasFactory;

    protected $fillable = [
        'license_key',
        'license_type',
        'client_name',
        'client_email',
        'client_phone',
        'client_address',
        'start_date',
        'end_date',
        'max_users',
        'max_warehouses',
        'is_active',
        'features',
        'modules',
        'license_cost',
        'payment_status',
        'notes',
        'created_by',
        'last_check'
    ];

    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime',
        'is_active' => 'boolean',
        'features' => 'array',
        'modules' => 'array',
        'license_cost' => 'decimal:2',
        'last_check' => 'datetime'
    ];

    // العلاقات
    public function creator()
    {
        return $this->belongsTo(MasterAdmin::class, 'created_by');
    }

    public function usage()
    {
        return $this->hasOne(LicenseUsage::class, 'license_id');
    }

    public function users()
    {
        return $this->hasMany(User::class, 'license_id');
    }

    public function warehouses()
    {
        return $this->hasMany(WarehouseManagement::class, 'license_id');
    }

    // Accessors
    public function getIsExpiredAttribute()
    {
        return $this->end_date < now();
    }

    public function getIsNearExpiryAttribute()
    {
        return $this->end_date > now() && $this->end_date->diffInDays(now()) <= 30;
    }

    public function getDaysRemainingAttribute()
    {
        return $this->end_date->diffInDays(now(), false);
    }

    public function getCurrentUsersCountAttribute()
    {
        return $this->users()->where('is_account_active', true)->count();
    }

    public function getCurrentWarehousesCountAttribute()
    {
        return $this->warehouses()->where('status', 'active')->count();
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeExpired($query)
    {
        return $query->where('end_date', '<', now());
    }

    public function scopeNearExpiry($query)
    {
        return $query->where('end_date', '<=', now()->addDays(30))
                    ->where('end_date', '>', now());
    }

    // Methods
    public static function generateLicenseKey()
    {
        do {
            $key = 'PH-' . strtoupper(Str::random(4)) . '-' . strtoupper(Str::random(4)) . '-' . strtoupper(Str::random(4));
        } while (self::where('license_key', $key)->exists());

        return $key;
    }

    public function extendLicense($months = 12)
    {
        $this->update([
            'end_date' => $this->end_date->addMonths($months),
            'is_active' => true
        ]);
    }

    public function suspend()
    {
        $this->update(['is_active' => false]);
    }

    public function activate()
    {
        $this->update(['is_active' => true]);
    }

    public function updateLastCheck()
    {
        $this->update(['last_check' => now()]);
    }

    public function hasFeature($feature)
    {
        return in_array($feature, $this->features ?? []);
    }

    public function hasModule($module)
    {
        return in_array($module, $this->modules ?? []);
    }
}
