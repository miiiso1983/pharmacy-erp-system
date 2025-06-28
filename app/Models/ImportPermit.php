<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ImportPermit extends Model
{
    use HasFactory;

    protected $fillable = [
        'permit_number',
        'company_id',
        'product_id',
        'supplier_company',
        'supplier_country',
        'application_date',
        'issue_date',
        'expiry_date',
        'quantity',
        'unit',
        'unit_price',
        'total_value',
        'currency',
        'status',
        'batch_number',
        'manufacturing_date',
        'expiry_date_product',
        'port_of_entry',
        'expected_arrival_date',
        'actual_arrival_date',
        'customs_declaration_number',
        'customs_status',
        'customs_fees',
        'permit_fees',
        'payment_status',
        'rejection_reason',
        'notes',
        'documents',
    ];

    protected $casts = [
        'application_date' => 'date',
        'issue_date' => 'date',
        'expiry_date' => 'date',
        'manufacturing_date' => 'date',
        'expiry_date_product' => 'date',
        'expected_arrival_date' => 'date',
        'actual_arrival_date' => 'date',
        'unit_price' => 'decimal:2',
        'total_value' => 'decimal:2',
        'customs_fees' => 'decimal:2',
        'permit_fees' => 'decimal:2',
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
     * العلاقة مع المنتج
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(PharmaceuticalProduct::class, 'product_id');
    }

    /**
     * التحقق من انتهاء صلاحية الإجازة
     */
    public function isExpired(): bool
    {
        return $this->expiry_date && $this->expiry_date->isPast();
    }

    /**
     * التحقق من تأخر الوصول
     */
    public function isArrivalDelayed(): bool
    {
        return $this->expected_arrival_date &&
               $this->expected_arrival_date->isPast() &&
               !$this->actual_arrival_date;
    }

    /**
     * حساب إجمالي الرسوم
     */
    public function getTotalFees(): float
    {
        return ($this->customs_fees ?? 0) + ($this->permit_fees ?? 0);
    }

    /**
     * حساب القيمة الإجمالية
     */
    public function calculateTotalValue(): float
    {
        return $this->quantity * ($this->unit_price ?? 0);
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
     * نطاقات الاستعلام
     */
    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    public function scopeByCompany($query, $companyId)
    {
        return $query->where('company_id', $companyId);
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    public function scopeExpired($query)
    {
        return $query->where('expiry_date', '<', now());
    }

    public function scopeExpiringWithin($query, $days)
    {
        return $query->where('expiry_date', '<=', now()->addDays($days))
                    ->where('expiry_date', '>=', now());
    }

    public function scopeDelayedArrival($query)
    {
        return $query->where('expected_arrival_date', '<', now())
                    ->whereNull('actual_arrival_date');
    }

    public function scopePaymentPending($query)
    {
        return $query->where('payment_status', 'pending');
    }

    public function scopeBySupplierCountry($query, $country)
    {
        return $query->where('supplier_country', $country);
    }

    public function scopeByPortOfEntry($query, $port)
    {
        return $query->where('port_of_entry', $port);
    }
}
