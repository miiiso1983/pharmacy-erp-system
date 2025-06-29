<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Payroll extends BaseModel
{
    protected $fillable = [
        'employee_id',
        'payroll_period',
        'pay_date',
        'basic_salary',
        'housing_allowance',
        'transport_allowance',
        'meal_allowance',
        'other_allowances',
        'overtime_amount',
        'bonus',
        'tax_deduction',
        'insurance_deduction',
        'loan_deduction',
        'absence_deduction',
        'other_deductions',
        'gross_salary',
        'total_deductions',
        'net_salary',
        'status',
        'notes',
    ];

    protected $casts = [
        'pay_date' => 'date',
        'basic_salary' => 'decimal:2',
        'housing_allowance' => 'decimal:2',
        'transport_allowance' => 'decimal:2',
        'meal_allowance' => 'decimal:2',
        'other_allowances' => 'decimal:2',
        'overtime_amount' => 'decimal:2',
        'bonus' => 'decimal:2',
        'tax_deduction' => 'decimal:2',
        'insurance_deduction' => 'decimal:2',
        'loan_deduction' => 'decimal:2',
        'absence_deduction' => 'decimal:2',
        'other_deductions' => 'decimal:2',
        'gross_salary' => 'decimal:2',
        'total_deductions' => 'decimal:2',
        'net_salary' => 'decimal:2',
    ];

    // العلاقات
    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }

    // Accessors
    public function getStatusLabelAttribute(): string
    {
        return match($this->status) {
            'draft' => 'مسودة',
            'approved' => 'موافق عليه',
            'paid' => 'مدفوع',
            default => 'غير محدد'
        };
    }

    public function getStatusBadgeAttribute(): string
    {
        return match($this->status) {
            'draft' => 'secondary',
            'approved' => 'warning',
            'paid' => 'success',
            default => 'secondary'
        };
    }

    public function getTotalAllowancesAttribute(): float
    {
        return $this->housing_allowance + $this->transport_allowance +
               $this->meal_allowance + $this->other_allowances +
               $this->overtime_amount + $this->bonus;
    }

    public function getTotalDeductionsCalculatedAttribute(): float
    {
        return $this->tax_deduction + $this->insurance_deduction +
               $this->loan_deduction + $this->absence_deduction +
               $this->other_deductions;
    }
}
