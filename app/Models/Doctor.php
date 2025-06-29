<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Doctor extends BaseModel
{
    protected $fillable = [
        'doctor_code',
        'name',
        'specialty',
        'specialization', // الحقل الجديد
        'phone',
        'mobile', // الحقل الجديد
        'email',
        'address',
        'city',
        'area',
        'classification',
        'clinic_name',
        'clinic_address', // الحقل الجديد
        'hospital_name',
        'latitude',
        'longitude',
        'medical_representative_id',
        'visit_frequency', // الحقل الجديد
        'preferred_visit_time', // الحقل الجديد
        'status',
        'notes',
    ];

    protected $casts = [
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8',
    ];

    // العلاقات
    public function medicalRepresentative(): BelongsTo
    {
        return $this->belongsTo(MedicalRepresentative::class);
    }

    public function visits(): HasMany
    {
        return $this->hasMany(Visit::class);
    }

    public function targets(): HasMany
    {
        return $this->hasMany(Target::class);
    }

    // Accessors
    public function getClassificationLabelAttribute(): string
    {
        return match($this->classification) {
            'A' => 'الفئة أ - عالي الأهمية',
            'B' => 'الفئة ب - متوسط الأهمية',
            'C' => 'الفئة ج - منخفض الأهمية',
            default => 'غير محدد'
        };
    }

    public function getClassificationBadgeAttribute(): string
    {
        return match($this->classification) {
            'A' => 'danger',
            'B' => 'warning',
            'C' => 'info',
            default => 'secondary'
        };
    }

    public function getStatusLabelAttribute(): string
    {
        return match($this->status) {
            'active' => 'نشط',
            'inactive' => 'غير نشط',
            default => 'غير محدد'
        };
    }

    // الحصول على عدد الزيارات المطلوبة شهرياً حسب التصنيف
    public function getMonthlyTargetVisits(): int
    {
        return match($this->classification) {
            'A' => 8,
            'B' => 4,
            'C' => 2,
            default => 2
        };
    }

    // الحصول على عدد الزيارات المطلوبة أسبوعياً حسب التصنيف
    public function getWeeklyTargetVisits(): int
    {
        return match($this->classification) {
            'A' => 2,
            'B' => 1,
            'C' => 1,
            default => 1
        };
    }

    // إحصائيات الزيارات
    public function getMonthlyVisitsCount(): int
    {
        return $this->visits()
            ->whereMonth('visit_date', now()->month)
            ->whereYear('visit_date', now()->year)
            ->where('status', 'completed')
            ->count();
    }

    public function getLastVisitDate(): ?string
    {
        $lastVisit = $this->visits()
            ->where('status', 'completed')
            ->latest('visit_date')
            ->first();

        return $lastVisit ? $lastVisit->visit_date->format('Y-m-d') : null;
    }

    public function getNextVisitDate(): ?string
    {
        $nextVisit = $this->visits()
            ->where('status', 'scheduled')
            ->where('visit_date', '>', now())
            ->oldest('visit_date')
            ->first();

        return $nextVisit ? $nextVisit->visit_date->format('Y-m-d') : null;
    }
}
