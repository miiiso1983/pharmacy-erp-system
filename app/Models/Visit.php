<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Visit extends BaseModel
{
    protected $fillable = [
        'medical_representative_id',
        'doctor_id',
        'visit_date',
        'next_visit_date',
        'visit_type',
        'status',
        'visit_notes',
        'doctor_feedback',
        'marketing_support_type',
        'marketing_support_details',
        'latitude',
        'longitude',
        'location_address',
        'attachments',
        'voice_notes',
        'duration_minutes',
        'order_created',
        'order_id',
    ];

    protected $casts = [
        'visit_date' => 'datetime',
        'next_visit_date' => 'datetime',
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8',
        'attachments' => 'array',
        'order_created' => 'boolean',
    ];

    // العلاقات
    public function medicalRepresentative(): BelongsTo
    {
        return $this->belongsTo(MedicalRepresentative::class);
    }

    public function doctor(): BelongsTo
    {
        return $this->belongsTo(Doctor::class);
    }

    public function samples(): HasMany
    {
        return $this->hasMany(Sample::class);
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    // Accessors
    public function getVisitTypeLabelAttribute(): string
    {
        return match($this->visit_type) {
            'planned' => 'مخططة',
            'unplanned' => 'غير مخططة',
            'follow_up' => 'متابعة',
            default => 'غير محدد'
        };
    }

    public function getStatusLabelAttribute(): string
    {
        return match($this->status) {
            'scheduled' => 'مجدولة',
            'completed' => 'مكتملة',
            'cancelled' => 'ملغية',
            'missed' => 'فائتة',
            default => 'غير محدد'
        };
    }

    public function getStatusBadgeAttribute(): string
    {
        return match($this->status) {
            'scheduled' => 'primary',
            'completed' => 'success',
            'cancelled' => 'secondary',
            'missed' => 'danger',
            default => 'secondary'
        };
    }

    public function getDurationFormattedAttribute(): string
    {
        if (!$this->duration_minutes) {
            return '--';
        }

        $hours = floor($this->duration_minutes / 60);
        $minutes = $this->duration_minutes % 60;

        if ($hours > 0) {
            return sprintf('%d ساعة %d دقيقة', $hours, $minutes);
        }

        return sprintf('%d دقيقة', $minutes);
    }

    // إحصائيات العينات
    public function getTotalSamplesDistributed(): int
    {
        return $this->samples()->sum('quantity_distributed');
    }

    public function getUniqueMedicinesCount(): int
    {
        return $this->samples()->distinct('item_id')->count();
    }
}
