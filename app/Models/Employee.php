<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Carbon\Carbon;

class Employee extends Model
{
    protected $fillable = [
        'employee_id',
        'first_name',
        'last_name',
        'email',
        'phone',
        'birth_date',
        'gender',
        'marital_status',
        'address',
        'national_id',
        'passport_number',
        'department_id',
        'position',
        'hire_date',
        'contract_end_date',
        'employment_type',
        'status',
        'basic_salary',
        'allowances',
        'deductions',
        'bank_account',
        'bank_name',
        'emergency_contact_name',
        'emergency_contact_phone',
        'emergency_contact_relation',
        'photo',
        'documents',
        'notes',
    ];

    protected $casts = [
        'birth_date' => 'date',
        'hire_date' => 'date',
        'contract_end_date' => 'date',
        'basic_salary' => 'decimal:2',
        'allowances' => 'decimal:2',
        'deductions' => 'decimal:2',
        'documents' => 'array',
        'is_active' => 'boolean',
    ];

    protected $hidden = [
        'national_id',
        'passport_number',
        'bank_account',
    ];

    // العلاقات
    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }

    public function attendances(): HasMany
    {
        return $this->hasMany(Attendance::class);
    }

    public function leaves(): HasMany
    {
        return $this->hasMany(Leave::class);
    }

    public function payrolls(): HasMany
    {
        return $this->hasMany(Payroll::class);
    }

    // Accessors
    public function getFullNameAttribute(): string
    {
        return $this->first_name . ' ' . $this->last_name;
    }

    public function getAgeAttribute(): int
    {
        return Carbon::parse($this->birth_date)->age;
    }

    public function getYearsOfServiceAttribute(): int
    {
        return Carbon::parse($this->hire_date)->diffInYears(now());
    }

    public function getGenderLabelAttribute(): string
    {
        return match($this->gender) {
            'male' => 'ذكر',
            'female' => 'أنثى',
            default => 'غير محدد'
        };
    }

    public function getMaritalStatusLabelAttribute(): string
    {
        return match($this->marital_status) {
            'single' => 'أعزب',
            'married' => 'متزوج',
            'divorced' => 'مطلق',
            'widowed' => 'أرمل',
            default => 'غير محدد'
        };
    }

    public function getEmploymentTypeLabelAttribute(): string
    {
        return match($this->employment_type) {
            'full_time' => 'دوام كامل',
            'part_time' => 'دوام جزئي',
            'contract' => 'عقد',
            'intern' => 'متدرب',
            default => 'غير محدد'
        };
    }

    public function getStatusLabelAttribute(): string
    {
        return match($this->status) {
            'active' => 'نشط',
            'inactive' => 'غير نشط',
            'terminated' => 'منتهي الخدمة',
            'on_leave' => 'في إجازة',
            default => 'غير محدد'
        };
    }

    public function getStatusBadgeAttribute(): string
    {
        return match($this->status) {
            'active' => 'success',
            'inactive' => 'secondary',
            'terminated' => 'danger',
            'on_leave' => 'warning',
            default => 'secondary'
        };
    }

    public function getTotalSalaryAttribute(): float
    {
        return $this->basic_salary + $this->allowances - $this->deductions;
    }
}
