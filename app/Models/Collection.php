<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Collection extends Model
{
    use HasFactory;

    protected $fillable = [
        'collection_number',
        'invoice_id',
        'customer_id',
        'amount',
        'payment_method',
        'reference_number',
        'collection_date',
        'collected_by',
        'notes',
        'status',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'collection_date' => 'date',
        'payment_method' => 'string',
    ];

    // العلاقات
    public function invoice()
    {
        return $this->belongsTo(Invoice::class);
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }

    public function collectedBy()
    {
        return $this->belongsTo(User::class, 'collected_by');
    }

    // Accessors
    public function getStatusNameAttribute()
    {
        $statuses = [
            'pending' => 'معلق',
            'completed' => 'مكتمل',
            'cancelled' => 'ملغي',
        ];

        return $statuses[$this->status] ?? $this->status;
    }

    public function getPaymentMethodNameAttribute()
    {
        $methods = [
            'cash' => 'نقدي',
            'bank_transfer' => 'تحويل بنكي',
            'check' => 'شيك',
            'credit_card' => 'بطاقة ائتمان',
        ];

        return $methods[$this->payment_method] ?? $this->payment_method;
    }

    // تلقائياً إنشاء رقم التحصيل
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($collection) {
            if (empty($collection->collection_number)) {
                $collection->collection_number = 'COL-' . date('Y') . '-' . str_pad(static::count() + 1, 6, '0', STR_PAD_LEFT);
            }
        });

        static::created(function ($collection) {
            // تحديث المبلغ المدفوع في الفاتورة إذا كانت موجودة
            if ($collection->invoice_id && $collection->invoice) {
                $invoice = $collection->invoice;
                $invoice->paid_amount += $collection->amount;
                $invoice->save();
            }
        });
    }
}
