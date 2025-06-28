<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CustomReport extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'report_type',
        'data_sources',
        'filters',
        'columns',
        'grouping',
        'sorting',
        'calculations',
        'chart_config',
        'layout_config',
        'created_by',
        'is_public',
        'is_scheduled',
        'schedule_config',
        'last_generated_at',
        'status',
        'category'
    ];

    protected $casts = [
        'data_sources' => 'array',
        'filters' => 'array',
        'columns' => 'array',
        'grouping' => 'array',
        'sorting' => 'array',
        'calculations' => 'array',
        'chart_config' => 'array',
        'layout_config' => 'array',
        'schedule_config' => 'array',
        'is_public' => 'boolean',
        'is_scheduled' => 'boolean',
        'last_generated_at' => 'datetime'
    ];

    // العلاقات
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    // مصادر البيانات المتاحة
    public static function getAvailableDataSources(): array
    {
        return [
            'orders' => [
                'name' => 'الطلبات',
                'model' => Order::class,
                'table' => 'orders',
                'relations' => ['customer', 'orderItems', 'invoice', 'returns'],
                'fields' => [
                    'id' => 'رقم الطلب',
                    'order_number' => 'رقم الطلب',
                    'customer_id' => 'العميل',
                    'status' => 'الحالة',
                    'subtotal' => 'المجموع الفرعي',
                    'tax_amount' => 'الضريبة',
                    'discount_amount' => 'الخصم',
                    'total_amount' => 'المجموع الكلي',
                    'delivery_date' => 'تاريخ التسليم',
                    'created_at' => 'تاريخ الإنشاء'
                ]
            ],
            'invoices' => [
                'name' => 'الفواتير',
                'model' => Invoice::class,
                'table' => 'invoices',
                'relations' => ['order', 'customer', 'collections'],
                'fields' => [
                    'id' => 'رقم الفاتورة',
                    'invoice_number' => 'رقم الفاتورة',
                    'order_id' => 'الطلب',
                    'customer_id' => 'العميل',
                    'total_amount' => 'المبلغ الكلي',
                    'paid_amount' => 'المبلغ المدفوع',
                    'remaining_amount' => 'المبلغ المتبقي',
                    'status' => 'الحالة',
                    'due_date' => 'تاريخ الاستحقاق',
                    'created_at' => 'تاريخ الإنشاء'
                ]
            ],
            'collections' => [
                'name' => 'التحصيلات',
                'model' => Collection::class,
                'table' => 'collections',
                'relations' => ['invoice', 'customer', 'collector'],
                'fields' => [
                    'id' => 'رقم التحصيل',
                    'collection_number' => 'رقم التحصيل',
                    'invoice_id' => 'الفاتورة',
                    'customer_id' => 'العميل',
                    'amount' => 'المبلغ',
                    'payment_method' => 'طريقة الدفع',
                    'status' => 'الحالة',
                    'collected_by' => 'المحصل',
                    'collection_date' => 'تاريخ التحصيل',
                    'created_at' => 'تاريخ الإنشاء'
                ]
            ]
        ];
    }

    // إضافة المزيد من مصادر البيانات
    public static function getExtendedDataSources(): array
    {
        return array_merge(self::getAvailableDataSources(), [
            'customers' => [
                'name' => 'العملاء',
                'model' => User::class,
                'table' => 'users',
                'relations' => ['orders', 'invoices', 'collections', 'transactions'],
                'fields' => [
                    'id' => 'رقم العميل',
                    'name' => 'الاسم',
                    'email' => 'البريد الإلكتروني',
                    'phone' => 'الهاتف',
                    'address' => 'العنوان',
                    'city' => 'المدينة',
                    'customer_type' => 'نوع العميل',
                    'credit_limit' => 'حد الائتمان',
                    'current_balance' => 'الرصيد الحالي',
                    'created_at' => 'تاريخ التسجيل'
                ]
            ],
            'items' => [
                'name' => 'العناصر',
                'model' => Item::class,
                'table' => 'items',
                'relations' => ['supplier', 'orderItems', 'warehouseItems'],
                'fields' => [
                    'id' => 'رقم العنصر',
                    'item_code' => 'كود العنصر',
                    'name' => 'اسم العنصر',
                    'description' => 'الوصف',
                    'category' => 'الفئة',
                    'unit' => 'الوحدة',
                    'cost_price' => 'سعر التكلفة',
                    'selling_price' => 'سعر البيع',
                    'stock_quantity' => 'الكمية المتوفرة',
                    'min_stock_level' => 'الحد الأدنى للمخزون',
                    'supplier_id' => 'المورد',
                    'created_at' => 'تاريخ الإضافة'
                ]
            ],
            'suppliers' => [
                'name' => 'الموردين',
                'model' => Supplier::class,
                'table' => 'suppliers',
                'relations' => ['items', 'orders', 'invoices'],
                'fields' => [
                    'id' => 'رقم المورد',
                    'supplier_code' => 'كود المورد',
                    'name' => 'اسم المورد',
                    'contact_person' => 'الشخص المسؤول',
                    'phone' => 'الهاتف',
                    'email' => 'البريد الإلكتروني',
                    'address' => 'العنوان',
                    'city' => 'المدينة',
                    'country' => 'البلد',
                    'payment_terms' => 'شروط الدفع',
                    'credit_limit' => 'حد الائتمان',
                    'status' => 'الحالة',
                    'created_at' => 'تاريخ التسجيل'
                ]
            ],
            'warehouses' => [
                'name' => 'المخازن',
                'model' => Warehouse::class,
                'table' => 'warehouses',
                'relations' => ['warehouseItems', 'orders'],
                'fields' => [
                    'id' => 'رقم المخزن',
                    'warehouse_code' => 'كود المخزن',
                    'name' => 'اسم المخزن',
                    'location' => 'الموقع',
                    'manager_id' => 'المدير',
                    'capacity' => 'السعة',
                    'current_stock' => 'المخزون الحالي',
                    'status' => 'الحالة',
                    'created_at' => 'تاريخ الإنشاء'
                ]
            ],
            'employees' => [
                'name' => 'الموظفين',
                'model' => Employee::class,
                'table' => 'employees',
                'relations' => ['department', 'attendance', 'payroll', 'leaves'],
                'fields' => [
                    'id' => 'رقم الموظف',
                    'employee_code' => 'كود الموظف',
                    'name' => 'الاسم',
                    'position' => 'المنصب',
                    'department_id' => 'القسم',
                    'salary' => 'الراتب',
                    'hire_date' => 'تاريخ التوظيف',
                    'phone' => 'الهاتف',
                    'email' => 'البريد الإلكتروني',
                    'status' => 'الحالة',
                    'created_at' => 'تاريخ التسجيل'
                ]
            ],
            'doctors' => [
                'name' => 'الأطباء',
                'model' => Doctor::class,
                'table' => 'doctors',
                'relations' => ['visits', 'medicalRepresentative'],
                'fields' => [
                    'id' => 'رقم الطبيب',
                    'doctor_code' => 'كود الطبيب',
                    'name' => 'الاسم',
                    'specialty' => 'التخصص',
                    'phone' => 'الهاتف',
                    'email' => 'البريد الإلكتروني',
                    'clinic_name' => 'اسم العيادة',
                    'hospital_name' => 'اسم المستشفى',
                    'city' => 'المدينة',
                    'area' => 'المنطقة',
                    'classification' => 'التصنيف',
                    'status' => 'الحالة',
                    'created_at' => 'تاريخ التسجيل'
                ]
            ]
        ]);
    }

    // أنواع التقارير المتاحة
    public static function getReportTypes(): array
    {
        return [
            'table' => 'تقرير جدولي',
            'chart' => 'تقرير بياني',
            'dashboard' => 'لوحة تحكم',
            'financial' => 'تقرير مالي',
            'analytical' => 'تقرير تحليلي',
            'comparative' => 'تقرير مقارن',
            'summary' => 'تقرير ملخص',
            'detailed' => 'تقرير مفصل'
        ];
    }

    // أنواع الحسابات المتاحة
    public static function getCalculationTypes(): array
    {
        return [
            'sum' => 'المجموع',
            'avg' => 'المتوسط',
            'count' => 'العدد',
            'min' => 'الحد الأدنى',
            'max' => 'الحد الأقصى',
            'percentage' => 'النسبة المئوية',
            'growth_rate' => 'معدل النمو',
            'variance' => 'التباين',
            'ratio' => 'النسبة'
        ];
    }
}
