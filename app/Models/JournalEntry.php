<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class JournalEntry extends Model
{
    use HasFactory;

    protected $fillable = [
        'entry_number',
        'entry_date',
        'reference_type',
        'reference_id',
        'description',
        'total_amount',
        'status',
        'created_by',
        'posted_by',
        'posted_at',
    ];

    protected $casts = [
        'entry_date' => 'date',
        'total_amount' => 'decimal:2',
        'posted_at' => 'datetime',
    ];

    /**
     * العلاقة مع تفاصيل القيد
     */
    public function details(): HasMany
    {
        return $this->hasMany(JournalEntryDetail::class);
    }

    /**
     * العلاقة مع المستخدم الذي أنشأ القيد
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * العلاقة مع المستخدم الذي رحل القيد
     */
    public function poster(): BelongsTo
    {
        return $this->belongsTo(User::class, 'posted_by');
    }

    /**
     * التحقق من توازن القيد
     */
    public function isBalanced(): bool
    {
        $totalDebits = $this->details()->sum('debit_amount');
        $totalCredits = $this->details()->sum('credit_amount');

        return abs($totalDebits - $totalCredits) < 0.01; // للتعامل مع أخطاء الفاصلة العشرية
    }

    /**
     * ترحيل القيد
     */
    public function post($userId): bool
    {
        if ($this->status !== 'draft') {
            return false;
        }

        if (!$this->isBalanced()) {
            return false;
        }

        $this->status = 'posted';
        $this->posted_by = $userId;
        $this->posted_at = now();
        $this->save();

        // تحديث أرصدة الحسابات
        foreach ($this->details as $detail) {
            $detail->account->updateCurrentBalance();
        }

        return true;
    }

    /**
     * إلغاء ترحيل القيد
     */
    public function unpost(): bool
    {
        if ($this->status !== 'posted') {
            return false;
        }

        $this->status = 'draft';
        $this->posted_by = null;
        $this->posted_at = null;
        $this->save();

        // تحديث أرصدة الحسابات
        foreach ($this->details as $detail) {
            $detail->account->updateCurrentBalance();
        }

        return true;
    }

    /**
     * إلغاء القيد
     */
    public function cancel(): bool
    {
        if ($this->status === 'cancelled') {
            return false;
        }

        $this->status = 'cancelled';
        $this->save();

        // تحديث أرصدة الحسابات إذا كان القيد مرحل
        if ($this->posted_at) {
            foreach ($this->details as $detail) {
                $detail->account->updateCurrentBalance();
            }
        }

        return true;
    }

    /**
     * حساب إجمالي المبلغ
     */
    public function calculateTotalAmount(): float
    {
        return $this->details()->sum('debit_amount');
    }

    /**
     * تحديث إجمالي المبلغ
     */
    public function updateTotalAmount(): void
    {
        $this->total_amount = $this->calculateTotalAmount();
        $this->save();
    }

    /**
     * نطاقات الاستعلام
     */
    public function scopePosted($query)
    {
        return $query->where('status', 'posted');
    }

    public function scopeDraft($query)
    {
        return $query->where('status', 'draft');
    }

    public function scopeCancelled($query)
    {
        return $query->where('status', 'cancelled');
    }

    public function scopeByDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('entry_date', [$startDate, $endDate]);
    }

    public function scopeByReferenceType($query, $type)
    {
        return $query->where('reference_type', $type);
    }
}
