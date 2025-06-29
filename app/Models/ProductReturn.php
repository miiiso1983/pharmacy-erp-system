<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductReturn extends BaseModel
{
    protected $table = 'returns';

    protected $fillable = [
        'return_number',
        'order_id',
        'customer_id',
        'item_id',
        'quantity',
        'unit_price',
        'total_amount',
        'reason',
        'reason_description',
        'status',
        'return_date',
        'processed_by',
        'notes'
    ];

    protected $casts = [
        'return_date' => 'date',
        'unit_price' => 'decimal:2',
        'total_amount' => 'decimal:2',
    ];

    // العلاقات
    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'customer_id');
    }

    public function item(): BelongsTo
    {
        return $this->belongsTo(Item::class);
    }

    public function processedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'processed_by');
    }

    // Scopes
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    public function scopeProcessed($query)
    {
        return $query->where('status', 'processed');
    }

    // Accessors
    public function getStatusLabelAttribute()
    {
        $labels = [
            'pending' => 'في الانتظار',
            'approved' => 'موافق عليه',
            'rejected' => 'مرفوض',
            'processed' => 'تم المعالجة'
        ];

        return $labels[$this->status] ?? $this->status;
    }

    public function getReasonLabelAttribute()
    {
        $reasons = [
            'damaged' => 'تالف',
            'expired' => 'منتهي الصلاحية',
            'wrong_item' => 'صنف خاطئ',
            'customer_request' => 'طلب العميل',
            'other' => 'أخرى'
        ];

        return $reasons[$this->reason] ?? $this->reason;
    }

    // Methods
    public function generateReturnNumber()
    {
        $lastReturn = static::latest('id')->first();
        $number = $lastReturn ? $lastReturn->id + 1 : 1;
        return 'RET-' . date('Y') . '-' . str_pad($number, 6, '0', STR_PAD_LEFT);
    }
}
