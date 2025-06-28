<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InspectionPermit extends Model
{
    use HasFactory;

    protected $fillable = [
        'permit_number',
        'company_id',
        'product_id',
        'permit_type',
        'application_date',
        'inspection_date',
        'issue_date',
        'expiry_date',
        'status',
        'inspector_name',
        'inspection_notes',
        'result',
        'deficiencies',
        'corrective_actions',
        'follow_up_date',
        'fees',
        'payment_status',
        'remarks',
        'documents',
    ];

    protected $casts = [
        'application_date' => 'date',
        'inspection_date' => 'date',
        'issue_date' => 'date',
        'expiry_date' => 'date',
        'follow_up_date' => 'date',
        'fees' => 'decimal:2',
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
     * التحقق من استحقاق الدفع
     */
    public function isPaymentOverdue(): bool
    {
        return $this->payment_status === 'pending' &&
               $this->application_date &&
               $this->application_date->addDays(30)->isPast();
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

    public function scopeByType($query, $type)
    {
        return $query->where('permit_type', $type);
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

    public function scopePaymentPending($query)
    {
        return $query->where('payment_status', 'pending');
    }

    public function scopeOverduePayment($query)
    {
        return $query->where('payment_status', 'pending')
                    ->where('application_date', '<=', now()->subDays(30));
    }
}
