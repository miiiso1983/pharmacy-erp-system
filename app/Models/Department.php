<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Department extends BaseModel
{
    protected $fillable = [
        'name',
        'code',
        'description',
        'manager_id',
        'location',
        'phone',
        'email',
        'budget',
        'status',
    ];

    protected $casts = [
        'budget' => 'decimal:2',
    ];

    // العلاقات
    public function employees(): HasMany
    {
        return $this->hasMany(Employee::class);
    }

    public function manager(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'manager_id');
    }

    // Accessors
    public function getStatusLabelAttribute(): string
    {
        return match($this->status) {
            'active' => 'نشط',
            'inactive' => 'غير نشط',
            default => 'غير محدد'
        };
    }

    public function getStatusBadgeAttribute(): string
    {
        return match($this->status) {
            'active' => 'success',
            'inactive' => 'secondary',
            default => 'secondary'
        };
    }
}
