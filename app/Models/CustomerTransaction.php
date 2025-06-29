<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CustomerTransaction extends BaseModel
{
    use HasFactory;

    protected $fillable = [
        'customer_id',
        'transaction_type',
        'reference_number',
        'transaction_date',
        'amount',
        'discount',
        'tax',
        'total_amount',
        'payment_status',
        'due_date',
        'description',
        'items',
        'created_by',
    ];

    protected $casts = [
        'transaction_date' => 'date',
        'due_date' => 'date',
        'amount' => 'decimal:2',
        'discount' => 'decimal:2',
        'tax' => 'decimal:2',
        'total_amount' => 'decimal:2',
        'items' => 'array',
    ];

    /**
     * العلاقة مع الزبون
     */
    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    /**
     * العلاقة مع المدفوعات
     */
    public function payments(): HasMany
    {
        return $this->hasMany(CustomerPayment::class, 'transaction_id');
    }

    /**
     * العلاقة مع المستخدم الذي أنشأ المعاملة
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * التحقق من كون المعاملة مدفوعة بالكامل
     */
    public function isFullyPaid(): bool
    {
        return $this->payment_status === 'paid';
    }

    /**
     * التحقق من كون المعاملة مدفوعة جزئياً
     */
    public function isPartiallyPaid(): bool
    {
        return $this->payment_status === 'partially_paid';
    }

    /**
     * التحقق من كون المعاملة غير مدفوعة
     */
    public function isUnpaid(): bool
    {
        return $this->payment_status === 'unpaid';
    }
}
