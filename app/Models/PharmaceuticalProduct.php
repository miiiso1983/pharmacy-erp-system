<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PharmaceuticalProduct extends BaseModel
{
    use HasFactory;

    protected $fillable = [
        'product_code',
        'product_name',
        'product_name_en',
        'generic_name',
        'brand_name',
        'company_id',
        'registration_number',
        'registration_date',
        'expiry_date',
        'product_type',
        'dosage_form',
        'strength',
        'pack_size',
        'prescription_status',
        'status',
        'atc_code',
        'composition',
        'indications',
        'contraindications',
        'side_effects',
        'dosage_instructions',
        'storage_conditions',
        'price',
        'barcode',
        'notes',
        'documents',
    ];

    protected $casts = [
        'registration_date' => 'date',
        'expiry_date' => 'date',
        'price' => 'decimal:2',
        'documents' => 'array',
    ];

    /**
     * العلاقة مع الشركة
     */
    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    /**
     * العلاقة مع إجازات الفحص
     */
    public function inspectionPermits(): HasMany
    {
        return $this->hasMany(InspectionPermit::class, 'product_id');
    }

    /**
     * العلاقة مع إجازات الاستيراد
     */
    public function importPermits(): HasMany
    {
        return $this->hasMany(ImportPermit::class, 'product_id');
    }

    /**
     * التحقق من انتهاء صلاحية التسجيل
     */
    public function isExpired(): bool
    {
        return $this->expiry_date && $this->expiry_date->isPast();
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
    public function scopeRegistered($query)
    {
        return $query->where('status', 'registered');
    }

    public function scopeByType($query, $type)
    {
        return $query->where('product_type', $type);
    }

    public function scopeByCompany($query, $companyId)
    {
        return $query->where('company_id', $companyId);
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

    public function scopePrescription($query)
    {
        return $query->where('prescription_status', 'prescription');
    }

    public function scopeOtc($query)
    {
        return $query->where('prescription_status', 'otc');
    }
}
