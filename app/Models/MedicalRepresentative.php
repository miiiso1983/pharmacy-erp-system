<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MedicalRepresentative extends Model
{
    protected $fillable = [
        'employee_id',
        'name',
        'email',
        'phone',
        'address',
        'territory',
        'supervisor_id',
        'status',
        'hire_date',
        'base_salary',
        'permissions',
        'notes',
    ];

    protected $casts = [
        'hire_date' => 'date',
        'permissions' => 'array',
        'base_salary' => 'decimal:2',
    ];

    // العلاقات
    public function supervisor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'supervisor_id');
    }

    public function doctors(): HasMany
    {
        return $this->hasMany(Doctor::class);
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
    public function getStatusLabelAttribute(): string
    {
        return match($this->status) {
            'active' => 'نشط',
            'inactive' => 'غير نشط',
            'suspended' => 'موقوف',
            default => 'غير محدد'
        };
    }

    public function getStatusBadgeAttribute(): string
    {
        return match($this->status) {
            'active' => 'success',
            'inactive' => 'secondary',
            'suspended' => 'danger',
            default => 'secondary'
        };
    }

    // إحصائيات
    public function getMonthlyVisitsCount(): int
    {
        return $this->visits()
            ->whereMonth('visit_date', now()->month)
            ->whereYear('visit_date', now()->year)
            ->where('status', 'completed')
            ->count();
    }

    public function getWeeklyVisitsCount(): int
    {
        return $this->visits()
            ->whereBetween('visit_date', [now()->startOfWeek(), now()->endOfWeek()])
            ->where('status', 'completed')
            ->count();
    }

}
