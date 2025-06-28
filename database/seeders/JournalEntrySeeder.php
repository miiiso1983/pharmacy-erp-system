<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\JournalEntry;
use App\Models\JournalEntryDetail;
use App\Models\Account;

class JournalEntrySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // التأكد من وجود الحسابات
        $cashAccount = Account::where('account_code', '1110')->first();
        $bankAccount = Account::where('account_code', '1120')->first();
        $inventoryAccount = Account::where('account_code', '1130')->first();
        $capitalAccount = Account::where('account_code', '3100')->first();
        $salesAccount = Account::where('account_code', '4100')->first();
        $cogsAccount = Account::where('account_code', '5100')->first();

        if (!$cashAccount || !$bankAccount || !$capitalAccount) {
            $this->command->warn('بعض الحسابات الأساسية غير موجودة. يرجى تشغيل AccountSeeder أولاً.');
            return;
        }

        // القيد الأول: إيداع رأس المال
        $entry1 = JournalEntry::create([
            'entry_number' => 'JE-2025-000001',
            'entry_date' => '2025-01-01',
            'reference_type' => 'manual',
            'description' => 'إيداع رأس المال الافتتاحي في الصندوق',
            'total_amount' => 1000000,
            'status' => 'posted',
            'created_by' => 1,
            'posted_by' => 1,
            'posted_at' => now(),
        ]);

        // تفاصيل القيد الأول
        JournalEntryDetail::create([
            'journal_entry_id' => $entry1->id,
            'account_id' => $cashAccount->id,
            'debit_amount' => 1000000,
            'credit_amount' => 0,
            'description' => 'إيداع نقدي في الصندوق',
        ]);

        JournalEntryDetail::create([
            'journal_entry_id' => $entry1->id,
            'account_id' => $capitalAccount->id,
            'debit_amount' => 0,
            'credit_amount' => 1000000,
            'description' => 'رأس المال الافتتاحي',
        ]);

        // القيد الثاني: تحويل من الصندوق إلى البنك
        $entry2 = JournalEntry::create([
            'entry_number' => 'JE-2025-000002',
            'entry_date' => '2025-01-02',
            'reference_type' => 'manual',
            'description' => 'تحويل نقدية من الصندوق إلى البنك',
            'total_amount' => 500000,
            'status' => 'posted',
            'created_by' => 1,
            'posted_by' => 1,
            'posted_at' => now(),
        ]);

        // تفاصيل القيد الثاني
        JournalEntryDetail::create([
            'journal_entry_id' => $entry2->id,
            'account_id' => $bankAccount->id,
            'debit_amount' => 500000,
            'credit_amount' => 0,
            'description' => 'إيداع في البنك',
        ]);

        JournalEntryDetail::create([
            'journal_entry_id' => $entry2->id,
            'account_id' => $cashAccount->id,
            'debit_amount' => 0,
            'credit_amount' => 500000,
            'description' => 'سحب من الصندوق',
        ]);

        // القيد الثالث: شراء مخزون (إذا كان الحساب موجود)
        if ($inventoryAccount) {
            $entry3 = JournalEntry::create([
                'entry_number' => 'JE-2025-000003',
                'entry_date' => '2025-01-03',
                'reference_type' => 'invoice',
                'reference_id' => 'PO-001',
                'description' => 'شراء مخزون أدوية',
                'total_amount' => 200000,
                'status' => 'posted',
                'created_by' => 1,
                'posted_by' => 1,
                'posted_at' => now(),
            ]);

            // تفاصيل القيد الثالث
            JournalEntryDetail::create([
                'journal_entry_id' => $entry3->id,
                'account_id' => $inventoryAccount->id,
                'debit_amount' => 200000,
                'credit_amount' => 0,
                'description' => 'شراء أدوية ومستلزمات طبية',
            ]);

            JournalEntryDetail::create([
                'journal_entry_id' => $entry3->id,
                'account_id' => $cashAccount->id,
                'debit_amount' => 0,
                'credit_amount' => 200000,
                'description' => 'دفع نقدي للموردين',
            ]);
        }

        // القيد الرابع: مبيعات (إذا كانت الحسابات موجودة)
        if ($salesAccount && $cogsAccount && $inventoryAccount) {
            $entry4 = JournalEntry::create([
                'entry_number' => 'JE-2025-000004',
                'entry_date' => '2025-01-04',
                'reference_type' => 'invoice',
                'reference_id' => 'INV-001',
                'description' => 'مبيعات أدوية نقدية',
                'total_amount' => 150000,
                'status' => 'posted',
                'created_by' => 1,
                'posted_by' => 1,
                'posted_at' => now(),
            ]);

            // تفاصيل القيد الرابع - المبيعات
            JournalEntryDetail::create([
                'journal_entry_id' => $entry4->id,
                'account_id' => $cashAccount->id,
                'debit_amount' => 150000,
                'credit_amount' => 0,
                'description' => 'تحصيل نقدي من المبيعات',
            ]);

            JournalEntryDetail::create([
                'journal_entry_id' => $entry4->id,
                'account_id' => $salesAccount->id,
                'debit_amount' => 0,
                'credit_amount' => 150000,
                'description' => 'إيرادات مبيعات الأدوية',
            ]);
        }

        // تحديث أرصدة الحسابات
        $this->updateAccountBalances();

        $this->command->info('تم إنشاء القيود التجريبية بنجاح!');
        $this->command->info('إجمالي القيود: ' . JournalEntry::count());
        $this->command->info('القيود المرحلة: ' . JournalEntry::where('status', 'posted')->count());
    }

    private function updateAccountBalances()
    {
        $accounts = Account::all();
        foreach ($accounts as $account) {
            $account->updateCurrentBalance();
        }
    }
}
