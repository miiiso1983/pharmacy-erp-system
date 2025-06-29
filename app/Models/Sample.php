<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Sample extends BaseModel
{
    protected $fillable = [
        'visit_id',
        'item_id',
        'item_name',
        'quantity_distributed',
        'batch_number',
        'expiry_date',
        'notes',
        'sample_image',
        'doctor_signature',
    ];

    protected $casts = [
        'expiry_date' => 'date',
        'doctor_signature' => 'boolean',
    ];

    // العلاقات
    public function visit(): BelongsTo
    {
        return $this->belongsTo(Visit::class);
    }

    public function item(): BelongsTo
    {
        return $this->belongsTo(Item::class);
    }

    // Accessors
    public function getExpiryStatusAttribute(): string
    {
        if (!$this->expiry_date) {
            return 'غير محدد';
        }

        $daysToExpiry = now()->diffInDays($this->expiry_date, false);

        if ($daysToExpiry < 0) {
            return 'منتهي الصلاحية';
        } elseif ($daysToExpiry <= 30) {
            return 'قريب الانتهاء';
        } else {
            return 'صالح';
        }
    }

    public function getExpiryStatusBadgeAttribute(): string
    {
        if (!$this->expiry_date) {
            return 'secondary';
        }

        $daysToExpiry = now()->diffInDays($this->expiry_date, false);

        if ($daysToExpiry < 0) {
            return 'danger';
        } elseif ($daysToExpiry <= 30) {
            return 'warning';
        } else {
            return 'success';
        }
    }
}
