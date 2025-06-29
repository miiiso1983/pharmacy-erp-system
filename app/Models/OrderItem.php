<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class OrderItem extends BaseModel
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'item_id',
        'quantity',
        'free_quantity',
        'unit_price',
        'discount_percentage',
        'discount_amount',
        'net_price',
        'total_price',
        'notes',
    ];

    protected $casts = [
        'unit_price' => 'decimal:2',
        'discount_percentage' => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'net_price' => 'decimal:2',
        'total_price' => 'decimal:2',
    ];

    // العلاقات
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function item()
    {
        return $this->belongsTo(Item::class);
    }

    // تلقائياً حساب السعر الإجمالي والخصم
    protected static function boot()
    {
        parent::boot();

        static::saving(function ($orderItem) {
            // حساب مبلغ الخصم
            if ($orderItem->discount_percentage > 0) {
                $orderItem->discount_amount = ($orderItem->unit_price * $orderItem->discount_percentage) / 100;
            }

            // حساب السعر الصافي بعد الخصم
            $orderItem->net_price = $orderItem->unit_price - $orderItem->discount_amount;

            // حساب السعر الإجمالي (الكمية المدفوعة فقط × السعر الصافي)
            $orderItem->total_price = $orderItem->quantity * $orderItem->net_price;
        });
    }

    // Accessors
    public function getTotalQuantityAttribute()
    {
        return $this->quantity + $this->free_quantity;
    }

    public function getDiscountValueAttribute()
    {
        return $this->quantity * $this->discount_amount;
    }

    public function getFreeValueAttribute()
    {
        return $this->free_quantity * $this->unit_price;
    }
}
