<?php

return [
    'account_types' => [
        'asset' => 'أصول',
        'liability' => 'خصوم',
        'equity' => 'حقوق الملكية',
        'revenue' => 'إيرادات',
        'expense' => 'مصروفات',
    ],
    
    'account_categories' => [
        'cash' => 'نقدية',
        'bank' => 'بنوك',
        'receivables' => 'ذمم مدينة',
        'inventory' => 'مخزون',
        'fixed_assets' => 'أصول ثابتة',
        'payables' => 'ذمم دائنة',
        'loans' => 'قروض',
        'capital' => 'رأس المال',
        'retained_earnings' => 'أرباح محتجزة',
        'sales' => 'مبيعات',
        'other_income' => 'إيرادات أخرى',
        'cost_of_goods' => 'تكلفة البضاعة المباعة',
        'operating_expenses' => 'مصروفات تشغيلية',
        'administrative_expenses' => 'مصروفات إدارية',
        'financial_expenses' => 'مصروفات مالية',
    ],
    
    'balance_types' => [
        'debit' => 'مدين',
        'credit' => 'دائن',
    ],
    
    'entry_status' => [
        'draft' => 'مسودة',
        'posted' => 'مرحل',
        'cancelled' => 'ملغي',
    ],
    
    'period_status' => [
        'open' => 'مفتوحة',
        'closed' => 'مغلقة',
    ],
    
    'reports' => [
        'balance_sheet' => 'الميزانية العمومية',
        'income_statement' => 'قائمة الدخل',
        'cash_flow' => 'قائمة التدفقات النقدية',
        'trial_balance' => 'ميزان المراجعة',
        'account_ledger' => 'دفتر الأستاذ',
        'general_ledger' => 'دفتر الأستاذ العام',
    ],
    
    'messages' => [
        'account_created' => 'تم إنشاء الحساب بنجاح',
        'account_updated' => 'تم تحديث الحساب بنجاح',
        'account_deleted' => 'تم حذف الحساب بنجاح',
        'entry_created' => 'تم إنشاء القيد بنجاح',
        'entry_updated' => 'تم تحديث القيد بنجاح',
        'entry_posted' => 'تم ترحيل القيد بنجاح',
        'entry_unposted' => 'تم إلغاء ترحيل القيد بنجاح',
        'entry_deleted' => 'تم حذف القيد بنجاح',
        'period_created' => 'تم إنشاء الفترة المالية بنجاح',
        'period_closed' => 'تم إغلاق الفترة المالية بنجاح',
        'period_reopened' => 'تم إعادة فتح الفترة المالية بنجاح',
    ],
    
    'errors' => [
        'account_has_transactions' => 'لا يمكن حذف الحساب لوجود معاملات عليه',
        'account_has_children' => 'لا يمكن حذف الحساب لوجود حسابات فرعية تابعة له',
        'system_account' => 'لا يمكن حذف حسابات النظام',
        'entry_already_posted' => 'القيد مرحل مسبقاً',
        'entry_not_posted' => 'القيد غير مرحل',
        'entry_not_balanced' => 'القيد غير متوازن. مجموع المدين يجب أن يساوي مجموع الدائن',
        'period_overlapping' => 'تتداخل هذه الفترة مع فترة مالية موجودة',
        'period_already_closed' => 'الفترة مغلقة مسبقاً',
        'period_already_open' => 'الفترة مفتوحة مسبقاً',
        'cannot_set_closed_period_current' => 'لا يمكن تعيين فترة مغلقة كفترة حالية',
    ],
];
