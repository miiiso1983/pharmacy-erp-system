<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Customer extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_code',
        'name',
        'business_name',
        'phone',
        'mobile',
        'email',
        'address',
        'city',
        'area',
        'customer_type',
        'credit_limit',
        'payment_terms_days',
        'current_balance',
        'total_purchases',
        'total_payments',
        'last_purchase_date',
        'last_payment_date',
        'status',
        'notes',
    ];

    protected $casts = [
        'credit_limit' => 'decimal:2',
        'current_balance' => 'decimal:2',
        'total_purchases' => 'decimal:2',
        'total_payments' => 'decimal:2',
        'last_purchase_date' => 'date',
        'last_payment_date' => 'date',
    ];

    /**
     * العلاقة مع المعاملات
     */
    public function transactions(): HasMany
    {
        return $this->hasMany(CustomerTransaction::class);
    }

    /**
     * العلاقة مع المدفوعات
     */
    public function payments(): HasMany
    {
        return $this->hasMany(CustomerPayment::class);
    }

    /**
     * العلاقة مع الطلبات
     */
    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    /**
     * العلاقة مع الفواتير
     */
    public function invoices(): HasMany
    {
        return $this->hasMany(Invoice::class);
    }

    /**
     * المعاملات غير المدفوعة
     */
    public function unpaidTransactions(): HasMany
    {
        return $this->hasMany(CustomerTransaction::class)
            ->where('payment_status', '!=', 'paid');
    }

    /**
     * حساب الرصيد الحالي
     */
    public function calculateCurrentBalance(): float
    {
        $totalSales = $this->transactions()
            ->where('transaction_type', 'sale')
            ->sum('total_amount');

        $totalReturns = $this->transactions()
            ->where('transaction_type', 'return')
            ->sum('total_amount');

        $totalPayments = $this->payments()->sum('amount');

        return $totalSales - $totalReturns - $totalPayments;
    }

    /**
     * حساب معدل المشتريات الشهري
     */
    public function getMonthlyPurchaseAverage(): float
    {
        $monthsCount = $this->transactions()
            ->selectRaw('COUNT(DISTINCT DATE_FORMAT(transaction_date, "%Y-%m")) as months')
            ->value('months') ?: 1;

        return $this->total_purchases / $monthsCount;
    }

    /**
     * حساب معدل التحصيلات الشهري
     */
    public function getMonthlyCollectionAverage(): float
    {
        $monthsCount = $this->payments()
            ->selectRaw('COUNT(DISTINCT DATE_FORMAT(payment_date, "%Y-%m")) as months')
            ->value('months') ?: 1;

        return $this->total_payments / $monthsCount;
    }

    /**
     * التحقق من تجاوز سقف الدين
     */
    public function isOverCreditLimit(): bool
    {
        return $this->current_balance > $this->credit_limit;
    }

    /**
     * نسبة استخدام سقف الدين
     */
    public function getCreditUtilizationPercentage(): float
    {
        if ($this->credit_limit == 0) {
            return 0;
        }

        return ($this->current_balance / $this->credit_limit) * 100;
    }
}
