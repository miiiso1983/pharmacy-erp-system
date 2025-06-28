<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Target extends Model
{
    protected $fillable = [
        'medical_representative_id',
        'doctor_id',
        'target_type',
        'doctor_class',
        'target_visits',
        'achieved_visits',
        'start_date',
        'end_date',
        'status',
        'notes',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
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

    // Accessors
    public function getTargetTypeLabelAttribute(): string
    {
        return match($this->target_type) {
            'weekly' => 'أسبوعي',
            'monthly' => 'شهري',
            'quarterly' => 'ربع سنوي',
            default => 'غير محدد'
        };
    }

    public function getStatusLabelAttribute(): string
    {
        return match($this->status) {
            'active' => 'نشط',
            'completed' => 'مكتمل',
            'cancelled' => 'ملغي',
            default => 'غير محدد'
        };
    }

    public function getStatusBadgeAttribute(): string
    {
        return match($this->status) {
            'active' => 'primary',
            'completed' => 'success',
            'cancelled' => 'secondary',
            default => 'secondary'
        };
    }

    public function getAchievementPercentageAttribute(): float
    {
        if ($this->target_visits == 0) {
            return 0;
        }

        return round(($this->achieved_visits / $this->target_visits) * 100, 2);
    }

    public function getAchievementStatusAttribute(): string
    {
        $percentage = $this->achievement_percentage;

        if ($percentage >= 100) {
            return 'مكتمل';
        } elseif ($percentage >= 75) {
            return 'جيد';
        } elseif ($percentage >= 50) {
            return 'متوسط';
        } else {
            return 'ضعيف';
        }
    }

    public function getAchievementBadgeAttribute(): string
    {
        $percentage = $this->achievement_percentage;

        if ($percentage >= 100) {
            return 'success';
        } elseif ($percentage >= 75) {
            return 'info';
        } elseif ($percentage >= 50) {
            return 'warning';
        } else {
            return 'danger';
        }
    }
}
