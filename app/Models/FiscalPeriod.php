<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Carbon\Carbon;

class FiscalPeriod extends BaseModel
{
    use HasFactory;

    protected $fillable = [
        'period_name',
        'start_date',
        'end_date',
        'period_type',
        'is_closed',
        'is_current',
        'notes',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'is_closed' => 'boolean',
        'is_current' => 'boolean',
    ];

    /**
     * الحصول على الفترة الحالية
     */
    public static function getCurrentPeriod(): ?FiscalPeriod
    {
        return static::where('is_current', true)->first();
    }

    /**
     * الحصول على الفترة التي تحتوي على تاريخ معين
     */
    public static function getPeriodByDate(Carbon $date): ?FiscalPeriod
    {
        return static::where('start_date', '<=', $date)
            ->where('end_date', '>=', $date)
            ->first();
    }

    /**
     * تعيين الفترة كحالية
     */
    public function setAsCurrent(): void
    {
        // إلغاء تعيين جميع الفترات الأخرى كحالية
        static::where('is_current', true)->update(['is_current' => false]);

        // تعيين هذه الفترة كحالية
        $this->is_current = true;
        $this->save();
    }

    /**
     * إغلاق الفترة
     */
    public function close(): bool
    {
        if ($this->is_closed) {
            return false;
        }

        $this->is_closed = true;
        $this->save();

        return true;
    }

    /**
     * إعادة فتح الفترة
     */
    public function reopen(): bool
    {
        if (!$this->is_closed) {
            return false;
        }

        $this->is_closed = false;
        $this->save();

        return true;
    }

    /**
     * التحقق من كون التاريخ ضمن الفترة
     */
    public function containsDate(Carbon $date): bool
    {
        return $date->between($this->start_date, $this->end_date);
    }

    /**
     * الحصول على عدد الأيام في الفترة
     */
    public function getDaysCount(): int
    {
        return $this->start_date->diffInDays($this->end_date) + 1;
    }

    /**
     * نطاقات الاستعلام
     */
    public function scopeOpen($query)
    {
        return $query->where('is_closed', false);
    }

    public function scopeClosed($query)
    {
        return $query->where('is_closed', true);
    }

    public function scopeCurrent($query)
    {
        return $query->where('is_current', true);
    }

    public function scopeByType($query, $type)
    {
        return $query->where('period_type', $type);
    }
}
