<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Attendance extends BaseModel
{
    protected $fillable = [
        'employee_id',
        'date',
        'check_in',
        'check_out',
        'break_start',
        'break_end',
        'total_hours',
        'overtime_hours',
        'status',
        'notes',
    ];

    protected $casts = [
        'date' => 'date',
        'check_in' => 'datetime:H:i',
        'check_out' => 'datetime:H:i',
        'break_start' => 'datetime:H:i',
        'break_end' => 'datetime:H:i',
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
            'present' => 'حاضر',
            'absent' => 'غائب',
            'late' => 'متأخر',
            'half_day' => 'نصف يوم',
            'holiday' => 'عطلة',
            default => 'غير محدد'
        };
    }

    public function getStatusBadgeAttribute(): string
    {
        return match($this->status) {
            'present' => 'success',
            'absent' => 'danger',
            'late' => 'warning',
            'half_day' => 'info',
            'holiday' => 'secondary',
            default => 'secondary'
        };
    }

    public function getTotalHoursFormattedAttribute(): string
    {
        $hours = floor($this->total_hours / 60);
        $minutes = $this->total_hours % 60;
        return sprintf('%02d:%02d', $hours, $minutes);
    }
}
