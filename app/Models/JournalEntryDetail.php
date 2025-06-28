<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class JournalEntryDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'journal_entry_id',
        'account_id',
        'debit_amount',
        'credit_amount',
        'description',
    ];

    protected $casts = [
        'debit_amount' => 'decimal:2',
        'credit_amount' => 'decimal:2',
    ];

    /**
     * العلاقة مع القيد المحاسبي
     */
    public function journalEntry(): BelongsTo
    {
        return $this->belongsTo(JournalEntry::class);
    }

    /**
     * العلاقة مع الحساب
     */
    public function account(): BelongsTo
    {
        return $this->belongsTo(Account::class);
    }

    /**
     * الحصول على المبلغ (مدين أو دائن)
     */
    public function getAmount(): float
    {
        return $this->debit_amount > 0 ? $this->debit_amount : $this->credit_amount;
    }

    /**
     * الحصول على نوع المبلغ
     */
    public function getAmountType(): string
    {
        return $this->debit_amount > 0 ? 'debit' : 'credit';
    }

    /**
     * التحقق من كون السطر مدين
     */
    public function isDebit(): bool
    {
        return $this->debit_amount > 0;
    }

    /**
     * التحقق من كون السطر دائن
     */
    public function isCredit(): bool
    {
        return $this->credit_amount > 0;
    }
}
