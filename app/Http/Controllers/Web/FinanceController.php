<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Account;
use App\Models\JournalEntry;
use App\Models\JournalEntryDetail;
use App\Models\FiscalPeriod;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class FinanceController extends Controller
{
    /**
     * لوحة تحكم النظام المالي
     */
    public function dashboard()
    {
        try {
            // إحصائيات عامة
            $stats = [
                'total_accounts' => Account::active()->count(),
                'total_journal_entries' => JournalEntry::count(),
                'posted_entries' => JournalEntry::posted()->count(),
                'draft_entries' => JournalEntry::draft()->count(),
                'current_period' => FiscalPeriod::getCurrentPeriod(),
                'total_assets' => $this->getTotalByAccountType('asset'),
                'total_liabilities' => $this->getTotalByAccountType('liability'),
                'total_equity' => $this->getTotalByAccountType('equity'),
                'total_revenue' => $this->getTotalByAccountType('revenue'),
                'total_expenses' => $this->getTotalByAccountType('expense'),
            ];

            // القيود الأخيرة
            $recentEntries = JournalEntry::with(['creator', 'details.account'])
                ->latest()
                ->take(10)
                ->get();

            // الحسابات الأكثر نشاطاً
            $activeAccounts = Account::withCount('journalEntryDetails')
                ->orderByDesc('journal_entry_details_count')
                ->take(10)
                ->get()
                ->filter(function($account) {
                    return $account->journal_entry_details_count > 0;
                });

            return view('finance.dashboard', compact('stats', 'recentEntries', 'activeAccounts'));

        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'حدث خطأ أثناء تحميل لوحة التحكم المالية: ' . $e->getMessage()]);
        }
    }

    /**
     * إدارة دليل الحسابات
     */
    public function accounts(Request $request)
    {
        try {
            $query = Account::with('parentAccount');

            // البحث
            if ($request->has('search') && $request->search) {
                $search = $request->search;
                $query->where(function($q) use ($search) {
                    $q->where('account_name', 'like', "%{$search}%")
                      ->orWhere('account_code', 'like', "%{$search}%")
                      ->orWhere('account_name_en', 'like', "%{$search}%");
                });
            }

            // فلترة حسب النوع
            if ($request->has('account_type') && $request->account_type) {
                $query->where('account_type', $request->account_type);
            }

            // فلترة حسب التصنيف
            if ($request->has('account_category') && $request->account_category) {
                $query->where('account_category', $request->account_category);
            }

            // فلترة حسب الحالة
            if ($request->has('is_active') && $request->is_active !== '') {
                $query->where('is_active', $request->is_active);
            }

            $accounts = $query->orderBy('account_code')->paginate(20);

            // إحصائيات
            $accountStats = [
                'total_accounts' => Account::count(),
                'active_accounts' => Account::active()->count(),
                'parent_accounts' => Account::parentAccounts()->count(),
                'child_accounts' => Account::childAccountsOnly()->count(),
                'assets' => Account::byType('asset')->count(),
                'liabilities' => Account::byType('liability')->count(),
                'equity' => Account::byType('equity')->count(),
                'revenue' => Account::byType('revenue')->count(),
                'expenses' => Account::byType('expense')->count(),
            ];

            return view('finance.accounts.index', compact('accounts', 'accountStats'));

        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'حدث خطأ أثناء تحميل دليل الحسابات: ' . $e->getMessage()]);
        }
    }

    /**
     * إدارة القيود المحاسبية
     */
    public function journalEntries(Request $request)
    {
        try {
            $query = JournalEntry::with(['creator', 'poster', 'details.account']);

            // البحث
            if ($request->has('search') && $request->search) {
                $search = $request->search;
                $query->where(function($q) use ($search) {
                    $q->where('entry_number', 'like', "%{$search}%")
                      ->orWhere('description', 'like', "%{$search}%");
                });
            }

            // فلترة حسب الحالة
            if ($request->has('status') && $request->status) {
                $query->where('status', $request->status);
            }

            // فلترة حسب التاريخ
            if ($request->has('date_from') && $request->date_from) {
                $query->where('entry_date', '>=', $request->date_from);
            }

            if ($request->has('date_to') && $request->date_to) {
                $query->where('entry_date', '<=', $request->date_to);
            }

            $entries = $query->latest('entry_date')->paginate(20);

            // إحصائيات
            $entryStats = [
                'total_entries' => JournalEntry::count(),
                'posted_entries' => JournalEntry::posted()->count(),
                'draft_entries' => JournalEntry::draft()->count(),
                'cancelled_entries' => JournalEntry::cancelled()->count(),
                'total_amount_posted' => JournalEntry::posted()->sum('total_amount'),
                'entries_this_month' => JournalEntry::whereMonth('entry_date', now()->month)
                    ->whereYear('entry_date', now()->year)->count(),
            ];

            return view('finance.journal-entries.index', compact('entries', 'entryStats'));

        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'حدث خطأ أثناء تحميل القيود المحاسبية: ' . $e->getMessage()]);
        }
    }

    /**
     * التقارير المالية
     */
    public function reports()
    {
        try {
            $currentPeriod = FiscalPeriod::getCurrentPeriod();

            return view('finance.reports.index', compact('currentPeriod'));

        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'حدث خطأ أثناء تحميل التقارير المالية: ' . $e->getMessage()]);
        }
    }

    /**
     * ميزان المراجعة
     */
    public function trialBalance(Request $request)
    {
        try {
            $startDate = $request->get('start_date', now()->startOfYear());
            $endDate = $request->get('end_date', now());

            $accounts = Account::where('is_active', true)->get();

            $trialBalance = [];
            $totalDebits = 0;
            $totalCredits = 0;

            foreach ($accounts as $account) {
                // حساب المدين والدائن للفترة المحددة
                $debits = 0;
                $credits = 0;

                // في حالة عدم وجود قيود، نعرض الرصيد الافتتاحي فقط
                $balance = $account->balance_type === 'debit'
                    ? ($account->opening_balance + $debits - $credits)
                    : ($account->opening_balance + $credits - $debits);

                $trialBalance[] = [
                    'account' => $account,
                    'opening_balance' => $account->opening_balance ?? 0,
                    'debits' => $debits,
                    'credits' => $credits,
                    'balance' => $balance,
                    'balance_type' => $balance >= 0 ? $account->balance_type :
                        ($account->balance_type === 'debit' ? 'credit' : 'debit'),
                ];

                $totalDebits += $debits;
                $totalCredits += $credits;
            }

            return view('finance.reports.trial-balance', compact(
                'trialBalance', 'totalDebits', 'totalCredits', 'startDate', 'endDate'
            ));

        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'حدث خطأ أثناء إنشاء ميزان المراجعة: ' . $e->getMessage()]);
        }
    }

    /**
     * حساب إجمالي نوع الحسابات
     */
    private function getTotalByAccountType(string $type): float
    {
        $accounts = Account::where('account_type', $type)
            ->where('is_active', true)
            ->get();

        $total = 0;
        foreach ($accounts as $account) {
            $total += $account->current_balance ?? 0;
        }

        return $total;
    }

    /**
     * إنشاء قيد محاسبي جديد
     */
    public function createJournalEntry()
    {
        try {
            $accounts = Account::active()->orderBy('account_code')->get();
            return view('finance.journal-entries.create', compact('accounts'));
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'حدث خطأ أثناء تحميل صفحة إنشاء القيد: ' . $e->getMessage()]);
        }
    }

    /**
     * حفظ قيد محاسبي جديد
     */
    public function storeJournalEntry(Request $request)
    {
        try {
            $request->validate([
                'entry_date' => 'required|date',
                'description' => 'required|string|max:1000',
                'details' => 'required|array|min:2',
                'details.*.account_id' => 'required|exists:accounts,id',
                'details.*.debit_amount' => 'nullable|numeric|min:0',
                'details.*.credit_amount' => 'nullable|numeric|min:0',
            ]);

            // التحقق من التوازن
            $totalDebits = 0;
            $totalCredits = 0;

            foreach ($request->details as $detail) {
                $totalDebits += $detail['debit_amount'] ?? 0;
                $totalCredits += $detail['credit_amount'] ?? 0;
            }

            if (abs($totalDebits - $totalCredits) > 0.01) {
                return back()->withErrors(['error' => 'القيد غير متوازن. إجمالي المدين يجب أن يساوي إجمالي الدائن.'])
                    ->withInput();
            }

            // إنشاء رقم القيد
            $entryNumber = 'JE-' . date('Y') . '-' . str_pad(JournalEntry::count() + 1, 6, '0', STR_PAD_LEFT);

            // إنشاء القيد
            $entry = JournalEntry::create([
                'entry_number' => $entryNumber,
                'entry_date' => $request->entry_date,
                'reference_type' => $request->reference_type,
                'reference_id' => $request->reference_id,
                'description' => $request->description,
                'total_amount' => $totalDebits,
                'status' => $request->action === 'save_and_post' ? 'posted' : 'draft',
                'created_by' => auth()->id(),
                'posted_by' => $request->action === 'save_and_post' ? auth()->id() : null,
                'posted_at' => $request->action === 'save_and_post' ? now() : null,
            ]);

            // إنشاء تفاصيل القيد
            foreach ($request->details as $detail) {
                if (($detail['debit_amount'] ?? 0) > 0 || ($detail['credit_amount'] ?? 0) > 0) {
                    JournalEntryDetail::create([
                        'journal_entry_id' => $entry->id,
                        'account_id' => $detail['account_id'],
                        'debit_amount' => $detail['debit_amount'] ?? 0,
                        'credit_amount' => $detail['credit_amount'] ?? 0,
                        'description' => $detail['description'] ?? null,
                    ]);
                }
            }

            // تحديث أرصدة الحسابات إذا تم الترحيل
            if ($request->action === 'save_and_post') {
                foreach ($entry->details as $detail) {
                    $detail->account->updateCurrentBalance();
                }
            }

            $message = $request->action === 'save_and_post'
                ? 'تم إنشاء القيد وترحيله بنجاح'
                : 'تم حفظ القيد كمسودة بنجاح';

            return redirect()->route('finance.journal-entries.index')
                ->with('success', $message);

        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'حدث خطأ أثناء حفظ القيد: ' . $e->getMessage()])
                ->withInput();
        }
    }

    /**
     * عرض قيد محاسبي
     */
    public function showJournalEntry(JournalEntry $entry)
    {
        try {
            $entry->load(['details.account', 'creator', 'poster']);
            return view('finance.journal-entries.show', compact('entry'));
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'حدث خطأ أثناء تحميل القيد: ' . $e->getMessage()]);
        }
    }

    /**
     * ترحيل قيد محاسبي
     */
    public function postJournalEntry(JournalEntry $entry)
    {
        try {
            if ($entry->post(auth()->id())) {
                return back()->with('success', 'تم ترحيل القيد بنجاح');
            } else {
                return back()->withErrors(['error' => 'لا يمكن ترحيل هذا القيد']);
            }
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'حدث خطأ أثناء ترحيل القيد: ' . $e->getMessage()]);
        }
    }

    /**
     * إلغاء ترحيل قيد محاسبي
     */
    public function unpostJournalEntry(JournalEntry $entry)
    {
        try {
            if ($entry->unpost()) {
                return back()->with('success', 'تم إلغاء ترحيل القيد بنجاح');
            } else {
                return back()->withErrors(['error' => 'لا يمكن إلغاء ترحيل هذا القيد']);
            }
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'حدث خطأ أثناء إلغاء ترحيل القيد: ' . $e->getMessage()]);
        }
    }

    /**
     * حذف قيد محاسبي
     */
    public function destroyJournalEntry(JournalEntry $entry)
    {
        try {
            if ($entry->status === 'posted') {
                return back()->withErrors(['error' => 'لا يمكن حذف قيد مرحل']);
            }

            $entry->delete();
            return redirect()->route('finance.journal-entries.index')
                ->with('success', 'تم حذف القيد بنجاح');

        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'حدث خطأ أثناء حذف القيد: ' . $e->getMessage()]);
        }
    }

    /**
     * عرض تفاصيل حساب محدد
     */
    public function showAccount(Account $account)
    {
        try {
            $account->load(['parentAccount', 'childAccounts']);

            // حساب الرصيد الحالي
            $account->updateCurrentBalance();

            // الحصول على آخر المعاملات
            $recentTransactions = $account->journalEntryDetails()
                ->with(['journalEntry'])
                ->whereHas('journalEntry', function($query) {
                    $query->where('status', 'posted');
                })
                ->latest()
                ->take(20)
                ->get();

            // إحصائيات الحساب
            $accountStats = [
                'total_debits' => $account->journalEntryDetails()
                    ->whereHas('journalEntry', function($query) {
                        $query->where('status', 'posted');
                    })
                    ->sum('debit_amount'),
                'total_credits' => $account->journalEntryDetails()
                    ->whereHas('journalEntry', function($query) {
                        $query->where('status', 'posted');
                    })
                    ->sum('credit_amount'),
                'transactions_count' => $account->journalEntryDetails()
                    ->whereHas('journalEntry', function($query) {
                        $query->where('status', 'posted');
                    })
                    ->count(),
                'opening_balance' => $account->opening_balance,
                'current_balance' => $account->current_balance,
            ];

            return view('finance.accounts.show', compact('account', 'recentTransactions', 'accountStats'));

        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'حدث خطأ أثناء تحميل تفاصيل الحساب: ' . $e->getMessage()]);
        }
    }

    /**
     * إنشاء حساب جديد
     */
    public function createAccount()
    {
        try {
            $parentAccounts = Account::parentAccounts()->active()->orderBy('account_code')->get();
            return view('finance.accounts.create', compact('parentAccounts'));
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'حدث خطأ أثناء تحميل صفحة إنشاء الحساب: ' . $e->getMessage()]);
        }
    }

    /**
     * حفظ حساب جديد
     */
    public function storeAccount(Request $request)
    {
        try {
            $request->validate([
                'account_code' => 'required|string|unique:accounts,account_code',
                'account_name' => 'required|string|max:255',
                'account_name_en' => 'nullable|string|max:255',
                'account_type' => 'required|in:asset,liability,equity,revenue,expense',
                'account_category' => 'required|string',
                'parent_account_id' => 'nullable|exists:accounts,id',
                'opening_balance' => 'nullable|numeric',
                'balance_type' => 'required|in:debit,credit',
                'description' => 'nullable|string',
            ]);

            $account = Account::create([
                'account_code' => $request->account_code,
                'account_name' => $request->account_name,
                'account_name_en' => $request->account_name_en,
                'account_type' => $request->account_type,
                'account_category' => $request->account_category,
                'parent_account_id' => $request->parent_account_id,
                'account_level' => $request->parent_account_id ?
                    Account::find($request->parent_account_id)->account_level + 1 : 1,
                'opening_balance' => $request->opening_balance ?? 0,
                'current_balance' => $request->opening_balance ?? 0,
                'balance_type' => $request->balance_type,
                'is_active' => true,
                'is_system_account' => false,
                'description' => $request->description,
            ]);

            return redirect()->route('finance.accounts.index')
                ->with('success', 'تم إنشاء الحساب بنجاح');

        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'حدث خطأ أثناء إنشاء الحساب: ' . $e->getMessage()])
                ->withInput();
        }
    }

    /**
     * تعديل حساب
     */
    public function editAccount(Account $account)
    {
        try {
            $parentAccounts = Account::parentAccounts()
                ->active()
                ->where('id', '!=', $account->id)
                ->orderBy('account_code')
                ->get();

            return view('finance.accounts.edit', compact('account', 'parentAccounts'));
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'حدث خطأ أثناء تحميل صفحة تعديل الحساب: ' . $e->getMessage()]);
        }
    }

    /**
     * تحديث حساب
     */
    public function updateAccount(Request $request, Account $account)
    {
        try {
            $request->validate([
                'account_code' => 'required|string|unique:accounts,account_code,' . $account->id,
                'account_name' => 'required|string|max:255',
                'account_name_en' => 'nullable|string|max:255',
                'account_type' => 'required|in:asset,liability,equity,revenue,expense',
                'account_category' => 'required|string',
                'parent_account_id' => 'nullable|exists:accounts,id',
                'opening_balance' => 'nullable|numeric',
                'balance_type' => 'required|in:debit,credit',
                'is_active' => 'boolean',
                'description' => 'nullable|string',
            ]);

            $account->update([
                'account_code' => $request->account_code,
                'account_name' => $request->account_name,
                'account_name_en' => $request->account_name_en,
                'account_type' => $request->account_type,
                'account_category' => $request->account_category,
                'parent_account_id' => $request->parent_account_id,
                'account_level' => $request->parent_account_id ?
                    Account::find($request->parent_account_id)->account_level + 1 : 1,
                'opening_balance' => $request->opening_balance ?? 0,
                'balance_type' => $request->balance_type,
                'is_active' => $request->has('is_active'),
                'description' => $request->description,
            ]);

            // إعادة حساب الرصيد الحالي
            $account->updateCurrentBalance();

            return redirect()->route('finance.accounts.index')
                ->with('success', 'تم تحديث الحساب بنجاح');

        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'حدث خطأ أثناء تحديث الحساب: ' . $e->getMessage()])
                ->withInput();
        }
    }

    /**
     * حذف حساب
     */
    public function destroyAccount(Account $account)
    {
        try {
            // التحقق من وجود معاملات على الحساب
            if ($account->journalEntryDetails()->count() > 0) {
                return back()->withErrors(['error' => 'لا يمكن حذف الحساب لوجود معاملات عليه']);
            }

            // التحقق من وجود حسابات فرعية
            if ($account->childAccounts()->count() > 0) {
                return back()->withErrors(['error' => 'لا يمكن حذف الحساب لوجود حسابات فرعية تابعة له']);
            }

            // التحقق من كونه حساب نظام
            if ($account->is_system_account) {
                return back()->withErrors(['error' => 'لا يمكن حذف حسابات النظام']);
            }

            $account->delete();

            return redirect()->route('finance.accounts.index')
                ->with('success', 'تم حذف الحساب بنجاح');

        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'حدث خطأ أثناء حذف الحساب: ' . $e->getMessage()]);
        }
    }

    /**
     * API للحصول على قائمة الحسابات
     */
    public function getAccounts(Request $request)
    {
        try {
            $query = Account::active();

            if ($request->has('search') && $request->search) {
                $search = $request->search;
                $query->where(function($q) use ($search) {
                    $q->where('account_name', 'like', "%{$search}%")
                      ->orWhere('account_code', 'like', "%{$search}%");
                });
            }

            $accounts = $query->orderBy('account_code')->get();

            return response()->json([
                'success' => true,
                'data' => $accounts->map(function($account) {
                    return [
                        'id' => $account->id,
                        'code' => $account->account_code,
                        'name' => $account->account_name,
                        'name_en' => $account->account_name_en,
                        'type' => $account->account_type,
                        'category' => $account->account_category,
                        'balance' => $account->current_balance,
                        'balance_type' => $account->balance_type,
                        'is_active' => $account->is_active,
                        'level' => $account->account_level,
                    ];
                })
            ], 200, [], JSON_UNESCAPED_UNICODE);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء تحميل الحسابات: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * تعديل قيد محاسبي
     */
    public function editJournalEntry(JournalEntry $entry)
    {
        try {
            if ($entry->status === 'posted') {
                return back()->withErrors(['error' => 'لا يمكن تعديل قيد مرحل']);
            }

            $entry->load(['details.account']);
            $accounts = Account::active()->orderBy('account_code')->get();

            return view('finance.journal-entries.edit', compact('entry', 'accounts'));
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'حدث خطأ أثناء تحميل صفحة تعديل القيد: ' . $e->getMessage()]);
        }
    }

    /**
     * تحديث قيد محاسبي
     */
    public function updateJournalEntry(Request $request, JournalEntry $entry)
    {
        try {
            if ($entry->status === 'posted') {
                return back()->withErrors(['error' => 'لا يمكن تعديل قيد مرحل']);
            }

            $request->validate([
                'entry_date' => 'required|date',
                'description' => 'required|string|max:500',
                'accounts' => 'required|array|min:2',
                'accounts.*.account_id' => 'required|exists:accounts,id',
                'accounts.*.debit_amount' => 'nullable|numeric|min:0',
                'accounts.*.credit_amount' => 'nullable|numeric|min:0',
                'accounts.*.description' => 'nullable|string|max:255',
            ]);

            // التحقق من توازن القيد
            $totalDebits = collect($request->accounts)->sum('debit_amount');
            $totalCredits = collect($request->accounts)->sum('credit_amount');

            if (abs($totalDebits - $totalCredits) > 0.01) {
                return back()->withErrors(['error' => 'القيد غير متوازن. مجموع المدين يجب أن يساوي مجموع الدائن'])
                    ->withInput();
            }

            DB::beginTransaction();

            // تحديث القيد
            $entry->update([
                'entry_date' => $request->entry_date,
                'description' => $request->description,
                'total_amount' => $totalDebits,
                'updated_by' => auth()->id(),
            ]);

            // حذف التفاصيل القديمة
            $entry->details()->delete();

            // إضافة التفاصيل الجديدة
            foreach ($request->accounts as $accountData) {
                if (($accountData['debit_amount'] ?? 0) > 0 || ($accountData['credit_amount'] ?? 0) > 0) {
                    JournalEntryDetail::create([
                        'journal_entry_id' => $entry->id,
                        'account_id' => $accountData['account_id'],
                        'debit_amount' => $accountData['debit_amount'] ?? 0,
                        'credit_amount' => $accountData['credit_amount'] ?? 0,
                        'description' => $accountData['description'] ?? '',
                    ]);
                }
            }

            DB::commit();

            return redirect()->route('finance.journal-entries.index')
                ->with('success', 'تم تحديث القيد بنجاح');

        } catch (\Exception $e) {
            DB::rollback();
            return back()->withErrors(['error' => 'حدث خطأ أثناء تحديث القيد: ' . $e->getMessage()])
                ->withInput();
        }
    }





    /**
     * الميزانية العمومية
     */
    public function balanceSheet(Request $request)
    {
        try {
            $asOfDate = $request->get('as_of_date', now());

            // الأصول
            $assets = Account::where('account_type', 'asset')
                ->where('is_active', true)
                ->get()
                ->map(function($account) use ($asOfDate) {
                    $account->balance_as_of_date = $this->getAccountBalanceAsOfDate($account, $asOfDate);
                    return $account;
                });

            // الخصوم
            $liabilities = Account::where('account_type', 'liability')
                ->where('is_active', true)
                ->get()
                ->map(function($account) use ($asOfDate) {
                    $account->balance_as_of_date = $this->getAccountBalanceAsOfDate($account, $asOfDate);
                    return $account;
                });

            // حقوق الملكية
            $equity = Account::where('account_type', 'equity')
                ->where('is_active', true)
                ->get()
                ->map(function($account) use ($asOfDate) {
                    $account->balance_as_of_date = $this->getAccountBalanceAsOfDate($account, $asOfDate);
                    return $account;
                });

            $totalAssets = $assets->sum('balance_as_of_date');
            $totalLiabilities = $liabilities->sum('balance_as_of_date');
            $totalEquity = $equity->sum('balance_as_of_date');

            return view('finance.reports.balance-sheet', compact(
                'assets', 'liabilities', 'equity',
                'totalAssets', 'totalLiabilities', 'totalEquity',
                'asOfDate'
            ));

        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'حدث خطأ أثناء تحميل الميزانية العمومية: ' . $e->getMessage()]);
        }
    }

    /**
     * قائمة الدخل
     */
    public function incomeStatement(Request $request)
    {
        try {
            $startDate = $request->get('start_date', now()->startOfYear());
            $endDate = $request->get('end_date', now());

            // الإيرادات
            $revenues = Account::where('account_type', 'revenue')
                ->where('is_active', true)
                ->get()
                ->map(function($account) use ($startDate, $endDate) {
                    $account->period_balance = $this->getAccountBalanceForPeriod($account, $startDate, $endDate);
                    return $account;
                });

            // المصروفات
            $expenses = Account::where('account_type', 'expense')
                ->where('is_active', true)
                ->get()
                ->map(function($account) use ($startDate, $endDate) {
                    $account->period_balance = $this->getAccountBalanceForPeriod($account, $startDate, $endDate);
                    return $account;
                });

            $totalRevenues = $revenues->sum('period_balance');
            $totalExpenses = $expenses->sum('period_balance');
            $netIncome = $totalRevenues - $totalExpenses;

            return view('finance.reports.income-statement', compact(
                'revenues', 'expenses',
                'totalRevenues', 'totalExpenses', 'netIncome',
                'startDate', 'endDate'
            ));

        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'حدث خطأ أثناء تحميل قائمة الدخل: ' . $e->getMessage()]);
        }
    }

    /**
     * قائمة التدفقات النقدية
     */
    public function cashFlow(Request $request)
    {
        try {
            $startDate = $request->get('start_date', now()->startOfYear());
            $endDate = $request->get('end_date', now());

            // الحصول على حسابات النقدية
            $cashAccounts = Account::where('account_category', 'cash')
                ->where('is_active', true)
                ->get();

            $cashFlowData = [];
            foreach ($cashAccounts as $account) {
                $transactions = $account->journalEntryDetails()
                    ->whereHas('journalEntry', function($query) use ($startDate, $endDate) {
                        $query->where('status', 'posted')
                              ->whereBetween('entry_date', [$startDate, $endDate]);
                    })
                    ->with('journalEntry')
                    ->get();

                $cashFlowData[] = [
                    'account' => $account,
                    'transactions' => $transactions,
                    'total_inflow' => $transactions->sum('debit_amount'),
                    'total_outflow' => $transactions->sum('credit_amount'),
                    'net_flow' => $transactions->sum('debit_amount') - $transactions->sum('credit_amount'),
                ];
            }

            return view('finance.reports.cash-flow', compact('cashFlowData', 'startDate', 'endDate'));

        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'حدث خطأ أثناء تحميل قائمة التدفقات النقدية: ' . $e->getMessage()]);
        }
    }

    /**
     * دفتر الأستاذ للحساب
     */
    public function accountLedger(Request $request)
    {
        try {
            $accountId = $request->get('account_id');
            $startDate = $request->get('start_date', now()->startOfYear());
            $endDate = $request->get('end_date', now());

            $account = null;
            $transactions = collect();
            $runningBalance = 0;

            if ($accountId) {
                $account = Account::findOrFail($accountId);

                // الرصيد الافتتاحي
                $openingBalance = $this->getAccountBalanceAsOfDate($account, $startDate->copy()->subDay());
                $runningBalance = $openingBalance;

                // المعاملات خلال الفترة
                $transactions = $account->journalEntryDetails()
                    ->whereHas('journalEntry', function($query) use ($startDate, $endDate) {
                        $query->where('status', 'posted')
                              ->whereBetween('entry_date', [$startDate, $endDate]);
                    })
                    ->with('journalEntry')
                    ->orderBy('created_at')
                    ->get()
                    ->map(function($detail) use (&$runningBalance, $account) {
                        if (in_array($account->account_type, ['asset', 'expense'])) {
                            $runningBalance += $detail->debit_amount - $detail->credit_amount;
                        } else {
                            $runningBalance += $detail->credit_amount - $detail->debit_amount;
                        }
                        $detail->running_balance = $runningBalance;
                        return $detail;
                    });
            }

            $accounts = Account::active()->orderBy('account_code')->get();

            return view('finance.reports.account-ledger', compact(
                'account', 'transactions', 'accounts',
                'startDate', 'endDate', 'runningBalance'
            ));

        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'حدث خطأ أثناء تحميل دفتر الأستاذ: ' . $e->getMessage()]);
        }
    }

    /**
     * إدارة الفترات المالية
     */
    public function fiscalPeriods()
    {
        try {
            $periods = FiscalPeriod::orderBy('start_date', 'desc')->get();
            $currentPeriod = FiscalPeriod::getCurrentPeriod();

            return view('finance.periods.index', compact('periods', 'currentPeriod'));

        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'حدث خطأ أثناء تحميل الفترات المالية: ' . $e->getMessage()]);
        }
    }

    /**
     * إنشاء فترة مالية جديدة
     */
    public function createFiscalPeriod()
    {
        try {
            return view('finance.periods.create');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'حدث خطأ أثناء تحميل صفحة إنشاء الفترة المالية: ' . $e->getMessage()]);
        }
    }

    /**
     * حفظ فترة مالية جديدة
     */
    public function storeFiscalPeriod(Request $request)
    {
        try {
            $request->validate([
                'period_name' => 'required|string|max:255',
                'start_date' => 'required|date',
                'end_date' => 'required|date|after:start_date',
                'description' => 'nullable|string',
            ]);

            // التحقق من عدم تداخل الفترات
            $overlapping = FiscalPeriod::where(function($query) use ($request) {
                $query->whereBetween('start_date', [$request->start_date, $request->end_date])
                      ->orWhereBetween('end_date', [$request->start_date, $request->end_date])
                      ->orWhere(function($q) use ($request) {
                          $q->where('start_date', '<=', $request->start_date)
                            ->where('end_date', '>=', $request->end_date);
                      });
            })->exists();

            if ($overlapping) {
                return back()->withErrors(['error' => 'تتداخل هذه الفترة مع فترة مالية موجودة'])
                    ->withInput();
            }

            FiscalPeriod::create([
                'period_name' => $request->period_name,
                'start_date' => $request->start_date,
                'end_date' => $request->end_date,
                'status' => 'open',
                'is_current' => false,
                'description' => $request->description,
                'created_by' => auth()->id(),
            ]);

            return redirect()->route('finance.periods.index')
                ->with('success', 'تم إنشاء الفترة المالية بنجاح');

        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'حدث خطأ أثناء إنشاء الفترة المالية: ' . $e->getMessage()])
                ->withInput();
        }
    }

    /**
     * إغلاق فترة مالية
     */
    public function closeFiscalPeriod(FiscalPeriod $period)
    {
        try {
            if ($period->status === 'closed') {
                return back()->withErrors(['error' => 'الفترة مغلقة مسبقاً']);
            }

            $period->update([
                'status' => 'closed',
                'closed_at' => now(),
                'closed_by' => auth()->id(),
            ]);

            return redirect()->route('finance.periods.index')
                ->with('success', 'تم إغلاق الفترة المالية بنجاح');

        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'حدث خطأ أثناء إغلاق الفترة المالية: ' . $e->getMessage()]);
        }
    }

    /**
     * إعادة فتح فترة مالية
     */
    public function reopenFiscalPeriod(FiscalPeriod $period)
    {
        try {
            if ($period->status === 'open') {
                return back()->withErrors(['error' => 'الفترة مفتوحة مسبقاً']);
            }

            $period->update([
                'status' => 'open',
                'closed_at' => null,
                'closed_by' => null,
            ]);

            return redirect()->route('finance.periods.index')
                ->with('success', 'تم إعادة فتح الفترة المالية بنجاح');

        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'حدث خطأ أثناء إعادة فتح الفترة المالية: ' . $e->getMessage()]);
        }
    }

    /**
     * تعيين فترة مالية كحالية
     */
    public function setCurrentPeriod(FiscalPeriod $period)
    {
        try {
            if ($period->status === 'closed') {
                return back()->withErrors(['error' => 'لا يمكن تعيين فترة مغلقة كفترة حالية']);
            }

            DB::beginTransaction();

            // إلغاء تعيين الفترة الحالية
            FiscalPeriod::where('is_current', true)->update(['is_current' => false]);

            // تعيين الفترة الجديدة كحالية
            $period->update(['is_current' => true]);

            DB::commit();

            return redirect()->route('finance.periods.index')
                ->with('success', 'تم تعيين الفترة المالية كفترة حالية بنجاح');

        } catch (\Exception $e) {
            DB::rollback();
            return back()->withErrors(['error' => 'حدث خطأ أثناء تعيين الفترة المالية: ' . $e->getMessage()]);
        }
    }

    /**
     * Helper methods
     */
    private function getAccountBalanceAsOfDate($account, $date)
    {
        $balance = $account->opening_balance;

        $transactions = $account->journalEntryDetails()
            ->whereHas('journalEntry', function($query) use ($date) {
                $query->where('status', 'posted')
                      ->where('entry_date', '<=', $date);
            })
            ->get();

        foreach ($transactions as $transaction) {
            if (in_array($account->account_type, ['asset', 'expense'])) {
                $balance += $transaction->debit_amount - $transaction->credit_amount;
            } else {
                $balance += $transaction->credit_amount - $transaction->debit_amount;
            }
        }

        return $balance;
    }

    private function getAccountBalanceForPeriod($account, $startDate, $endDate)
    {
        $transactions = $account->journalEntryDetails()
            ->whereHas('journalEntry', function($query) use ($startDate, $endDate) {
                $query->where('status', 'posted')
                      ->whereBetween('entry_date', [$startDate, $endDate]);
            })
            ->get();

        $balance = 0;
        foreach ($transactions as $transaction) {
            if (in_array($account->account_type, ['asset', 'expense'])) {
                $balance += $transaction->debit_amount - $transaction->credit_amount;
            } else {
                $balance += $transaction->credit_amount - $transaction->debit_amount;
            }
        }

        return $balance;
    }
}
