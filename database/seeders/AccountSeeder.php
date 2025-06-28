<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AccountSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // الحسابات الرئيسية للأصول
        $assets = \App\Models\Account::create([
            'account_code' => '1000',
            'account_name' => 'الأصول',
            'account_name_en' => 'Assets',
            'account_type' => 'asset',
            'account_category' => 'current_assets',
            'account_level' => 1,
            'balance_type' => 'debit',
            'is_system_account' => true,
            'description' => 'الحساب الرئيسي للأصول'
        ]);

        // الأصول المتداولة
        $currentAssets = \App\Models\Account::create([
            'account_code' => '1100',
            'account_name' => 'الأصول المتداولة',
            'account_name_en' => 'Current Assets',
            'account_type' => 'asset',
            'account_category' => 'current_assets',
            'parent_account_id' => $assets->id,
            'account_level' => 2,
            'balance_type' => 'debit',
            'is_system_account' => true,
            'description' => 'الأصول التي يمكن تحويلها إلى نقد خلال سنة'
        ]);

        // النقدية والبنوك
        \App\Models\Account::create([
            'account_code' => '1110',
            'account_name' => 'النقدية في الصندوق',
            'account_name_en' => 'Cash in Hand',
            'account_type' => 'asset',
            'account_category' => 'current_assets',
            'parent_account_id' => $currentAssets->id,
            'account_level' => 3,
            'balance_type' => 'debit',
            'is_system_account' => true,
            'description' => 'النقدية المتوفرة في الصندوق'
        ]);

        \App\Models\Account::create([
            'account_code' => '1120',
            'account_name' => 'البنوك',
            'account_name_en' => 'Banks',
            'account_type' => 'asset',
            'account_category' => 'current_assets',
            'parent_account_id' => $currentAssets->id,
            'account_level' => 3,
            'balance_type' => 'debit',
            'is_system_account' => true,
            'description' => 'الأرصدة في البنوك'
        ]);

        // المخزون
        \App\Models\Account::create([
            'account_code' => '1130',
            'account_name' => 'المخزون',
            'account_name_en' => 'Inventory',
            'account_type' => 'asset',
            'account_category' => 'current_assets',
            'parent_account_id' => $currentAssets->id,
            'account_level' => 3,
            'balance_type' => 'debit',
            'is_system_account' => true,
            'description' => 'قيمة البضائع المتوفرة للبيع'
        ]);

        // العملاء
        \App\Models\Account::create([
            'account_code' => '1140',
            'account_name' => 'العملاء',
            'account_name_en' => 'Accounts Receivable',
            'account_type' => 'asset',
            'account_category' => 'current_assets',
            'parent_account_id' => $currentAssets->id,
            'account_level' => 3,
            'balance_type' => 'debit',
            'is_system_account' => true,
            'description' => 'المبالغ المستحقة من العملاء'
        ]);

        // الأصول الثابتة
        $fixedAssets = \App\Models\Account::create([
            'account_code' => '1200',
            'account_name' => 'الأصول الثابتة',
            'account_name_en' => 'Fixed Assets',
            'account_type' => 'asset',
            'account_category' => 'fixed_assets',
            'parent_account_id' => $assets->id,
            'account_level' => 2,
            'balance_type' => 'debit',
            'is_system_account' => true,
            'description' => 'الأصول طويلة الأجل'
        ]);

        \App\Models\Account::create([
            'account_code' => '1210',
            'account_name' => 'الأثاث والمعدات',
            'account_name_en' => 'Furniture & Equipment',
            'account_type' => 'asset',
            'account_category' => 'fixed_assets',
            'parent_account_id' => $fixedAssets->id,
            'account_level' => 3,
            'balance_type' => 'debit',
            'is_system_account' => true,
            'description' => 'الأثاث والمعدات المكتبية'
        ]);

        // الحسابات الرئيسية للخصوم
        $liabilities = \App\Models\Account::create([
            'account_code' => '2000',
            'account_name' => 'الخصوم',
            'account_name_en' => 'Liabilities',
            'account_type' => 'liability',
            'account_category' => 'current_liabilities',
            'account_level' => 1,
            'balance_type' => 'credit',
            'is_system_account' => true,
            'description' => 'الحساب الرئيسي للخصوم'
        ]);

        // الخصوم المتداولة
        $currentLiabilities = \App\Models\Account::create([
            'account_code' => '2100',
            'account_name' => 'الخصوم المتداولة',
            'account_name_en' => 'Current Liabilities',
            'account_type' => 'liability',
            'account_category' => 'current_liabilities',
            'parent_account_id' => $liabilities->id,
            'account_level' => 2,
            'balance_type' => 'credit',
            'is_system_account' => true,
            'description' => 'الالتزامات المستحقة خلال سنة'
        ]);

        // الموردين
        \App\Models\Account::create([
            'account_code' => '2110',
            'account_name' => 'الموردين',
            'account_name_en' => 'Accounts Payable',
            'account_type' => 'liability',
            'account_category' => 'current_liabilities',
            'parent_account_id' => $currentLiabilities->id,
            'account_level' => 3,
            'balance_type' => 'credit',
            'is_system_account' => true,
            'description' => 'المبالغ المستحقة للموردين'
        ]);

        // حقوق الملكية
        $equity = \App\Models\Account::create([
            'account_code' => '3000',
            'account_name' => 'حقوق الملكية',
            'account_name_en' => 'Equity',
            'account_type' => 'equity',
            'account_category' => 'capital',
            'account_level' => 1,
            'balance_type' => 'credit',
            'is_system_account' => true,
            'description' => 'الحساب الرئيسي لحقوق الملكية'
        ]);

        \App\Models\Account::create([
            'account_code' => '3100',
            'account_name' => 'رأس المال',
            'account_name_en' => 'Capital',
            'account_type' => 'equity',
            'account_category' => 'capital',
            'parent_account_id' => $equity->id,
            'account_level' => 2,
            'balance_type' => 'credit',
            'is_system_account' => true,
            'description' => 'رأس المال المدفوع'
        ]);

        // الإيرادات
        $revenue = \App\Models\Account::create([
            'account_code' => '4000',
            'account_name' => 'الإيرادات',
            'account_name_en' => 'Revenue',
            'account_type' => 'revenue',
            'account_category' => 'sales_revenue',
            'account_level' => 1,
            'balance_type' => 'credit',
            'is_system_account' => true,
            'description' => 'الحساب الرئيسي للإيرادات'
        ]);

        \App\Models\Account::create([
            'account_code' => '4100',
            'account_name' => 'إيرادات المبيعات',
            'account_name_en' => 'Sales Revenue',
            'account_type' => 'revenue',
            'account_category' => 'sales_revenue',
            'parent_account_id' => $revenue->id,
            'account_level' => 2,
            'balance_type' => 'credit',
            'is_system_account' => true,
            'description' => 'إيرادات بيع البضائع'
        ]);

        // المصروفات
        $expenses = \App\Models\Account::create([
            'account_code' => '5000',
            'account_name' => 'المصروفات',
            'account_name_en' => 'Expenses',
            'account_type' => 'expense',
            'account_category' => 'operating_expenses',
            'account_level' => 1,
            'balance_type' => 'debit',
            'is_system_account' => true,
            'description' => 'الحساب الرئيسي للمصروفات'
        ]);

        \App\Models\Account::create([
            'account_code' => '5100',
            'account_name' => 'تكلفة البضاعة المباعة',
            'account_name_en' => 'Cost of Goods Sold',
            'account_type' => 'expense',
            'account_category' => 'cost_of_goods_sold',
            'parent_account_id' => $expenses->id,
            'account_level' => 2,
            'balance_type' => 'debit',
            'is_system_account' => true,
            'description' => 'تكلفة البضائع المباعة'
        ]);

        \App\Models\Account::create([
            'account_code' => '5200',
            'account_name' => 'مصروفات التشغيل',
            'account_name_en' => 'Operating Expenses',
            'account_type' => 'expense',
            'account_category' => 'operating_expenses',
            'parent_account_id' => $expenses->id,
            'account_level' => 2,
            'balance_type' => 'debit',
            'is_system_account' => true,
            'description' => 'المصروفات التشغيلية'
        ]);

        // التأكد من وجود الحسابات المطلوبة للقيود التجريبية
        \App\Models\Account::firstOrCreate(
            ['account_code' => '3100'],
            [
                'account_name' => 'رأس المال',
                'account_name_en' => 'Capital',
                'account_type' => 'equity',
                'account_category' => 'capital',
                'parent_account_id' => $equity->id,
                'account_level' => 2,
                'balance_type' => 'credit',
                'is_system_account' => true,
                'description' => 'رأس المال المدفوع'
            ]
        );

        \App\Models\Account::firstOrCreate(
            ['account_code' => '4100'],
            [
                'account_name' => 'إيرادات المبيعات',
                'account_name_en' => 'Sales Revenue',
                'account_type' => 'revenue',
                'account_category' => 'sales_revenue',
                'parent_account_id' => $revenue->id,
                'account_level' => 2,
                'balance_type' => 'credit',
                'is_system_account' => true,
                'description' => 'إيرادات بيع البضائع'
            ]
        );

        \App\Models\Account::firstOrCreate(
            ['account_code' => '5100'],
            [
                'account_name' => 'تكلفة البضاعة المباعة',
                'account_name_en' => 'Cost of Goods Sold',
                'account_type' => 'expense',
                'account_category' => 'cost_of_goods_sold',
                'parent_account_id' => $expenses->id,
                'account_level' => 2,
                'balance_type' => 'debit',
                'is_system_account' => true,
                'description' => 'تكلفة البضائع المباعة'
            ]
        );

        $this->command->info('تم إنشاء دليل الحسابات الأساسي بنجاح!');
        $this->command->info('إجمالي الحسابات: ' . \App\Models\Account::count());
    }
}
