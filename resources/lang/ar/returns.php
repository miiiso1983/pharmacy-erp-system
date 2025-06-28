<?php

return [
    // Main
    'title' => 'المرتجعات',
    'returns' => 'المرتجعات',
    'return' => 'مرتجع',
    'new_return' => 'مرتجع جديد',
    'add_return' => 'إضافة مرتجع',
    'create_return' => 'إنشاء مرتجع',
    'edit_return' => 'تعديل مرتجع',
    'view_return' => 'عرض مرتجع',
    'delete_return' => 'حذف مرتجع',
    'return_details' => 'تفاصيل المرتجع',
    'return_information' => 'معلومات المرتجع',
    'all_returns' => 'جميع المرتجعات',

    // Return Fields
    'return_number' => 'رقم المرتجع',
    'order_id' => 'رقم الطلب',
    'customer' => 'العميل',
    'item' => 'الصنف',
    'quantity' => 'الكمية',
    'unit_price' => 'سعر الوحدة',
    'total_amount' => 'المبلغ الإجمالي',
    'reason' => 'السبب',
    'reason_description' => 'وصف السبب',
    'status' => 'الحالة',
    'return_date' => 'تاريخ الإرجاع',
    'processed_by' => 'تمت المعالجة بواسطة',
    'notes' => 'ملاحظات',

    // Return Reasons
    'damaged' => 'تالف',
    'expired' => 'منتهي الصلاحية',
    'wrong_item' => 'صنف خاطئ',
    'customer_request' => 'طلب العميل',
    'other' => 'أخرى',

    // Return Status
    'pending' => 'في الانتظار',
    'approved' => 'موافق عليه',
    'rejected' => 'مرفوض',
    'processed' => 'تم المعالجة',

    // Actions
    'approve_return' => 'الموافقة على المرتجع',
    'reject_return' => 'رفض المرتجع',
    'process_return' => 'معالجة المرتجع',
    'view_details' => 'عرض التفاصيل',

    // Messages
    'return_created' => 'تم إنشاء المرتجع بنجاح',
    'return_updated' => 'تم تحديث المرتجع بنجاح',
    'return_deleted' => 'تم حذف المرتجع بنجاح',
    'return_approved' => 'تم الموافقة على المرتجع وإضافة الكمية للمخزون',
    'return_rejected' => 'تم رفض المرتجع',
    'return_processed' => 'تم معالجة المرتجع بنجاح',
    'no_returns_found' => 'لا توجد مرتجعات',
    'return_not_found' => 'المرتجع غير موجود',
    'cannot_edit_processed' => 'لا يمكن تعديل مرتجع تم معالجته',
    'cannot_delete_processed' => 'لا يمكن حذف مرتجع تم معالجته',
    'already_processed' => 'هذا المرتجع تم معالجته مسبقاً',

    // Statistics
    'total_returns' => 'إجمالي المرتجعات',
    'pending_returns' => 'المرتجعات المعلقة',
    'approved_returns' => 'المرتجعات الموافق عليها',
    'rejected_returns' => 'المرتجعات المرفوضة',
    'processed_returns' => 'المرتجعات المعالجة',
    'returns_value' => 'قيمة المرتجعات',
    'returns_today' => 'مرتجعات اليوم',
    'returns_this_month' => 'مرتجعات هذا الشهر',

    // Filters
    'filter_by_status' => 'تصفية حسب الحالة',
    'filter_by_reason' => 'تصفية حسب السبب',
    'filter_by_customer' => 'تصفية حسب العميل',
    'filter_by_date' => 'تصفية حسب التاريخ',
    'from_date' => 'من تاريخ',
    'to_date' => 'إلى تاريخ',

    // Reports
    'returns_report' => 'تقرير المرتجعات',
    'returns_summary' => 'ملخص المرتجعات',
    'returns_by_reason' => 'المرتجعات حسب السبب',
    'returns_by_customer' => 'المرتجعات حسب العميل',
    'returns_by_item' => 'المرتجعات حسب الصنف',
    'export_returns' => 'تصدير المرتجعات',
    'print_return' => 'طباعة المرتجع',

    // Validation
    'validation' => [
        'order_required' => 'الطلب مطلوب',
        'customer_required' => 'العميل مطلوب',
        'item_required' => 'الصنف مطلوب',
        'quantity_required' => 'الكمية مطلوبة',
        'quantity_positive' => 'الكمية يجب أن تكون أكبر من صفر',
        'unit_price_required' => 'سعر الوحدة مطلوب',
        'unit_price_positive' => 'سعر الوحدة يجب أن يكون أكبر من صفر',
        'reason_required' => 'السبب مطلوب',
        'return_date_required' => 'تاريخ الإرجاع مطلوب',
        'return_date_valid' => 'تاريخ الإرجاع يجب أن يكون صحيحاً',
        'order_exists' => 'الطلب يجب أن يكون موجوداً',
        'customer_exists' => 'العميل يجب أن يكون موجوداً',
        'item_exists' => 'الصنف يجب أن يكون موجوداً',
        'reason_valid' => 'السبب يجب أن يكون صحيحاً',
    ],

    // Confirmations
    'confirm_approve' => 'هل أنت متأكد من الموافقة على هذا المرتجع؟',
    'confirm_reject' => 'هل أنت متأكد من رفض هذا المرتجع؟',
    'confirm_delete' => 'هل أنت متأكد من حذف هذا المرتجع؟',
    'confirm_process' => 'هل أنت متأكد من معالجة هذا المرتجع؟',

    // Form Labels
    'select_order' => 'اختر الطلب',
    'select_customer' => 'اختر العميل',
    'select_item' => 'اختر الصنف',
    'select_reason' => 'اختر السبب',
    'enter_quantity' => 'أدخل الكمية',
    'enter_unit_price' => 'أدخل سعر الوحدة',
    'enter_reason_description' => 'أدخل وصف السبب',
    'enter_notes' => 'أدخل الملاحظات',
    'select_return_date' => 'اختر تاريخ الإرجاع',

    // Placeholders
    'quantity_placeholder' => 'مثال: 10',
    'unit_price_placeholder' => 'مثال: 25.50',
    'reason_description_placeholder' => 'اكتب وصف مفصل للسبب...',
    'notes_placeholder' => 'ملاحظات إضافية...',

    // Help Text
    'quantity_help' => 'الكمية المرتجعة من الصنف',
    'unit_price_help' => 'سعر الوحدة الواحدة عند البيع',
    'reason_help' => 'اختر السبب الرئيسي للإرجاع',
    'reason_description_help' => 'وصف مفصل لسبب الإرجاع',
    'return_date_help' => 'التاريخ الذي تم فيه إرجاع الصنف',

    // Buttons
    'save_return' => 'حفظ المرتجع',
    'update_return' => 'تحديث المرتجع',
    'approve' => 'موافقة',
    'reject' => 'رفض',
    'process' => 'معالجة',
    'back_to_returns' => 'العودة للمرتجعات',
    'add_new_return' => 'إضافة مرتجع جديد',

    // Table Headers
    'return_no' => 'رقم المرتجع',
    'order_no' => 'رقم الطلب',
    'customer_name' => 'اسم العميل',
    'item_name' => 'اسم الصنف',
    'return_qty' => 'الكمية المرتجعة',
    'return_value' => 'قيمة المرتجع',
    'return_reason' => 'سبب الإرجاع',
    'return_status' => 'حالة المرتجع',
    'return_date_col' => 'تاريخ الإرجاع',
    'processed_by_col' => 'معالج بواسطة',
    'actions' => 'الإجراءات',
];
