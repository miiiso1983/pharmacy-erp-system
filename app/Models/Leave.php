<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Leave extends BaseModel
{
    protected $fillable = [
        'employee_id',
        'type',
        'start_date',
        'end_date',
        'days_requested',
        'reason',
        'status',
        'approved_by',
        'approved_at',
        'approval_notes',
        'rejection_reason',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'approved_at' => 'datetime',
    ];

    // العلاقات
    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }

    public function approvedBy(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'approved_by');
    }

    // Accessors
    public function getTypeLabelAttribute(): string
    {
        return match($this->type) {
            'annual' => 'إجازة سنوية',
            'sick' => 'إجازة مرضية',
            'maternity' => 'إجازة أمومة',
            'paternity' => 'إجازة أبوة',
            'emergency' => 'إجازة طارئة',
            'unpaid' => 'إجازة بدون راتب',
            default => 'غير محدد'
        };
    }

    public function getStatusLabelAttribute(): string
    {
        return match($this->status) {
            'pending' => 'معلقة',
            'approved' => 'موافق عليها',
            'rejected' => 'مرفوضة',
            'cancelled' => 'ملغية',
            default => 'غير محدد'
        };
    }

    public function getStatusBadgeAttribute(): string
    {
        return match($this->status) {
            'pending' => 'warning',
            'approved' => 'success',
            'rejected' => 'danger',
            'cancelled' => 'secondary',
            default => 'secondary'
        };
    }
}
