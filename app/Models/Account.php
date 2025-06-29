<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Account extends BaseModel
{
    use HasFactory;

    protected $fillable = [
        'account_code',
        'account_name',
        'account_name_en',
        'account_type',
        'account_category',
        'parent_account_id',
        'account_level',
        'opening_balance',
        'current_balance',
        'balance_type',
        'is_active',
        'is_system_account',
        'description',
    ];

    protected $casts = [
        'opening_balance' => 'decimal:2',
        'current_balance' => 'decimal:2',
        'is_active' => 'boolean',
        'is_system_account' => 'boolean',
    ];

    /**
     * العلاقة مع الحساب الأب
     */
    public function parentAccount(): BelongsTo
    {
        return $this->belongsTo(Account::class, 'parent_account_id');
    }

    /**
     * العلاقة مع الحسابات الفرعية
     */
    public function childAccounts(): HasMany
    {
        return $this->hasMany(Account::class, 'parent_account_id');
    }

    /**
     * العلاقة مع تفاصيل القيود
     */
    public function journalEntryDetails(): HasMany
    {
        return $this->hasMany(JournalEntryDetail::class);
    }

    /**
     * حساب الرصيد الحالي
     */
    public function calculateCurrentBalance(): float
    {
        $totalDebits = $this->journalEntryDetails()
            ->whereHas('journalEntry', function($query) {
                $query->where('status', 'posted');
            })
            ->sum('debit_amount');

        $totalCredits = $this->journalEntryDetails()
            ->whereHas('journalEntry', function($query) {
                $query->where('status', 'posted');
            })
            ->sum('credit_amount');

        if ($this->balance_type === 'debit') {
            return $this->opening_balance + $totalDebits - $totalCredits;
        } else {
            return $this->opening_balance + $totalCredits - $totalDebits;
        }
    }

    /**
     * تحديث الرصيد الحالي
     */
    public function updateCurrentBalance(): void
    {
        $this->current_balance = $this->calculateCurrentBalance();
        $this->save();
    }

    /**
     * الحصول على المسار الكامل للحساب
     */
    public function getFullPath(): string
    {
        $path = $this->account_name;
        $parent = $this->parentAccount;

        while ($parent) {
            $path = $parent->account_name . ' > ' . $path;
            $parent = $parent->parentAccount;
        }

        return $path;
    }

    /**
     * التحقق من كون الحساب حساب أب
     */
    public function isParentAccount(): bool
    {
        return $this->childAccounts()->count() > 0;
    }

    /**
     * الحصول على جميع الحسابات الفرعية (بما في ذلك الفرعية من الفرعية)
     */
    public function getAllChildAccounts(): \Illuminate\Support\Collection
    {
        $children = collect();

        foreach ($this->childAccounts as $child) {
            $children->push($child);
            $children = $children->merge($child->getAllChildAccounts());
        }

        return $children;
    }

    /**
     * نطاقات الاستعلام
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeByType($query, $type)
    {
        return $query->where('account_type', $type);
    }

    public function scopeByCategory($query, $category)
    {
        return $query->where('account_category', $category);
    }

    public function scopeParentAccounts($query)
    {
        return $query->whereNull('parent_account_id');
    }

    public function scopeChildAccountsOnly($query)
    {
        return $query->whereNotNull('parent_account_id');
    }
}
