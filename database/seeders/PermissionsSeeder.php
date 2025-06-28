<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermissionsSeeder extends Seeder
{
    /**
     * Run the database seeder.
     */
    public function run(): void
    {
        // إنشاء الصلاحيات المفصلة
        $permissions = [
            // صلاحيات لوحة التحكم
            'dashboard.view' => 'عرض لوحة التحكم',
            'dashboard.stats' => 'عرض الإحصائيات',
            'dashboard.charts' => 'عرض الرسوم البيانية',
            
            // صلاحيات المستخدمين
            'users.view' => 'عرض المستخدمين',
            'users.create' => 'إضافة مستخدم',
            'users.edit' => 'تعديل المستخدم',
            'users.delete' => 'حذف المستخدم',
            'users.activate' => 'تفعيل/إلغاء تفعيل المستخدم',
            'users.export' => 'تصدير المستخدمين',
            'users.import' => 'استيراد المستخدمين',
            'users.permissions' => 'إدارة صلاحيات المستخدمين',
            'users.profile' => 'عرض الملف الشخصي',
            
            // صلاحيات الموظفين
            'employees.view' => 'عرض الموظفين',
            'employees.create' => 'إضافة موظف',
            'employees.edit' => 'تعديل الموظف',
            'employees.delete' => 'حذف الموظف',
            'employees.export' => 'تصدير الموظفين',
            'employees.import' => 'استيراد الموظفين',
            'employees.salary' => 'إدارة الرواتب',
            
            // صلاحيات الموارد البشرية
            'hr.view' => 'عرض لوحة تحكم HR',
            'hr.departments.view' => 'عرض الأقسام',
            'hr.departments.create' => 'إضافة قسم',
            'hr.departments.edit' => 'تعديل القسم',
            'hr.departments.delete' => 'حذف القسم',
            'hr.attendance.view' => 'عرض الحضور والغياب',
            'hr.attendance.create' => 'تسجيل الحضور',
            'hr.attendance.edit' => 'تعديل الحضور',
            'hr.attendance.delete' => 'حذف سجل الحضور',
            'hr.leaves.view' => 'عرض الإجازات',
            'hr.leaves.create' => 'طلب إجازة',
            'hr.leaves.approve' => 'الموافقة على الإجازات',
            'hr.leaves.reject' => 'رفض الإجازات',
            'hr.payroll.view' => 'عرض كشوف المرتبات',
            'hr.payroll.create' => 'إنشاء كشف مرتبات',
            'hr.payroll.edit' => 'تعديل كشف المرتبات',
            'hr.payroll.delete' => 'حذف كشف المرتبات',
            'hr.reports.view' => 'عرض تقارير HR',
            'hr.reports.export' => 'تصدير تقارير HR',
            
            // صلاحيات المخازن
            'warehouses.view' => 'عرض المخازن',
            'warehouses.create' => 'إضافة مخزن',
            'warehouses.edit' => 'تعديل المخزن',
            'warehouses.delete' => 'حذف المخزن',
            'warehouses.transfer' => 'تحويل بين المخازن',
            'warehouses.reports' => 'تقارير المخازن',
            'warehouses.inventory' => 'إدارة المخزون',
            
            // صلاحيات المنتجات
            'items.view' => 'عرض المنتجات',
            'items.create' => 'إضافة منتج',
            'items.edit' => 'تعديل المنتج',
            'items.delete' => 'حذف المنتج',
            'items.export' => 'تصدير المنتجات',
            'items.import' => 'استيراد المنتجات',
            'items.prices' => 'إدارة الأسعار',
            'items.stock' => 'إدارة المخزون',
            
            // صلاحيات الطلبات
            'orders.view' => 'عرض الطلبات',
            'orders.create' => 'إنشاء طلب',
            'orders.edit' => 'تعديل الطلب',
            'orders.delete' => 'حذف الطلب',
            'orders.approve' => 'الموافقة على الطلبات',
            'orders.reject' => 'رفض الطلبات',
            'orders.ship' => 'شحن الطلبات',
            'orders.cancel' => 'إلغاء الطلبات',
            'orders.reports' => 'تقارير الطلبات',
            
            // صلاحيات الفواتير
            'invoices.view' => 'عرض الفواتير',
            'invoices.create' => 'إنشاء فاتورة',
            'invoices.edit' => 'تعديل الفاتورة',
            'invoices.delete' => 'حذف الفاتورة',
            'invoices.print' => 'طباعة الفاتورة',
            'invoices.email' => 'إرسال الفاتورة بالبريد',
            'invoices.payment' => 'تسجيل الدفعات',
            'invoices.reports' => 'تقارير الفواتير',
            
            // صلاحيات التحصيلات
            'collections.view' => 'عرض التحصيلات',
            'collections.create' => 'إضافة تحصيل',
            'collections.edit' => 'تعديل التحصيل',
            'collections.delete' => 'حذف التحصيل',
            'collections.approve' => 'الموافقة على التحصيلات',
            'collections.reports' => 'تقارير التحصيلات',
            
            // صلاحيات الموردين
            'suppliers.view' => 'عرض الموردين',
            'suppliers.create' => 'إضافة مورد',
            'suppliers.edit' => 'تعديل المورد',
            'suppliers.delete' => 'حذف المورد',
            'suppliers.export' => 'تصدير الموردين',
            'suppliers.import' => 'استيراد الموردين',
            'suppliers.payments' => 'إدارة مدفوعات الموردين',
            
            // صلاحيات المرتجعات
            'returns.view' => 'عرض المرتجعات',
            'returns.create' => 'إنشاء مرتجع',
            'returns.edit' => 'تعديل المرتجع',
            'returns.delete' => 'حذف المرتجع',
            'returns.approve' => 'الموافقة على المرتجعات',
            'returns.reports' => 'تقارير المرتجعات',
            
            // صلاحيات التقارير
            'reports.view' => 'عرض التقارير',
            'reports.sales' => 'تقارير المبيعات',
            'reports.purchases' => 'تقارير المشتريات',
            'reports.inventory' => 'تقارير المخزون',
            'reports.financial' => 'التقارير المالية',
            'reports.export' => 'تصدير التقارير',
            'reports.schedule' => 'جدولة التقارير',
            
            // صلاحيات المندوبين العلميين
            'medical_reps.view' => 'عرض المندوبين العلميين',
            'medical_reps.create' => 'إضافة مندوب علمي',
            'medical_reps.edit' => 'تعديل المندوب العلمي',
            'medical_reps.delete' => 'حذف المندوب العلمي',
            'medical_reps.doctors.view' => 'عرض الأطباء',
            'medical_reps.doctors.create' => 'إضافة طبيب',
            'medical_reps.doctors.edit' => 'تعديل الطبيب',
            'medical_reps.visits.view' => 'عرض الزيارات',
            'medical_reps.visits.create' => 'إضافة زيارة',
            'medical_reps.visits.edit' => 'تعديل الزيارة',
            'medical_reps.reports' => 'تقارير المندوبين',
            
            // صلاحيات الإعدادات
            'settings.view' => 'عرض الإعدادات',
            'settings.edit' => 'تعديل الإعدادات',
            'settings.backup' => 'النسخ الاحتياطي',
            'settings.restore' => 'استعادة النسخ الاحتياطية',
            'settings.system' => 'إعدادات النظام',
            'settings.security' => 'إعدادات الأمان',
            
            // صلاحيات النظام
            'system.logs' => 'عرض سجلات النظام',
            'system.maintenance' => 'وضع الصيانة',
            'system.cache' => 'إدارة الذاكرة المؤقتة',
            'system.performance' => 'مراقبة الأداء',
            'system.notifications' => 'إدارة الإشعارات',
        ];

        // إنشاء الصلاحيات
        foreach ($permissions as $name => $description) {
            Permission::firstOrCreate([
                'name' => $name,
                'guard_name' => 'web'
            ], [
                'description' => $description
            ]);
        }

        // إنشاء الأدوار
        $roles = [
            'super_admin' => 'مدير عام',
            'admin' => 'مدير',
            'manager' => 'مدير مساعد',
            'hr_manager' => 'مدير الموارد البشرية',
            'warehouse_manager' => 'مدير المخزن',
            'sales_manager' => 'مدير المبيعات',
            'accountant' => 'محاسب',
            'employee' => 'موظف',
            'medical_rep' => 'مندوب علمي',
            'customer' => 'عميل'
        ];

        foreach ($roles as $name => $description) {
            Role::firstOrCreate([
                'name' => $name,
                'guard_name' => 'web'
            ], [
                'description' => $description
            ]);
        }

        // تعيين الصلاحيات للأدوار
        $this->assignPermissionsToRoles();
    }

    /**
     * تعيين الصلاحيات للأدوار
     */
    private function assignPermissionsToRoles(): void
    {
        // المدير العام - جميع الصلاحيات
        $superAdmin = Role::where('name', 'super_admin')->first();
        $superAdmin->givePermissionTo(Permission::all());

        // المدير - معظم الصلاحيات
        $admin = Role::where('name', 'admin')->first();
        $adminPermissions = Permission::where('name', 'not like', 'system.%')
            ->where('name', 'not like', 'settings.backup')
            ->where('name', 'not like', 'settings.restore')
            ->get();
        $admin->givePermissionTo($adminPermissions);

        // مدير الموارد البشرية
        $hrManager = Role::where('name', 'hr_manager')->first();
        $hrPermissions = Permission::where('name', 'like', 'hr.%')
            ->orWhere('name', 'like', 'employees.%')
            ->orWhere('name', 'dashboard.view')
            ->get();
        $hrManager->givePermissionTo($hrPermissions);

        // مدير المخزن
        $warehouseManager = Role::where('name', 'warehouse_manager')->first();
        $warehousePermissions = Permission::where('name', 'like', 'warehouses.%')
            ->orWhere('name', 'like', 'items.%')
            ->orWhere('name', 'dashboard.view')
            ->get();
        $warehouseManager->givePermissionTo($warehousePermissions);

        // مدير المبيعات
        $salesManager = Role::where('name', 'sales_manager')->first();
        $salesPermissions = Permission::where('name', 'like', 'orders.%')
            ->orWhere('name', 'like', 'invoices.%')
            ->orWhere('name', 'like', 'customers.%')
            ->orWhere('name', 'dashboard.view')
            ->get();
        $salesManager->givePermissionTo($salesPermissions);

        // المحاسب
        $accountant = Role::where('name', 'accountant')->first();
        $accountantPermissions = Permission::where('name', 'like', 'invoices.%')
            ->orWhere('name', 'like', 'collections.%')
            ->orWhere('name', 'like', 'reports.financial')
            ->orWhere('name', 'dashboard.view')
            ->get();
        $accountant->givePermissionTo($accountantPermissions);

        // الموظف
        $employee = Role::where('name', 'employee')->first();
        $employeePermissions = Permission::whereIn('name', [
            'dashboard.view',
            'orders.view',
            'items.view',
            'customers.view'
        ])->get();
        $employee->givePermissionTo($employeePermissions);

        // المندوب العلمي
        $medicalRep = Role::where('name', 'medical_rep')->first();
        $medicalRepPermissions = Permission::where('name', 'like', 'medical_reps.%')
            ->orWhere('name', 'dashboard.view')
            ->get();
        $medicalRep->givePermissionTo($medicalRepPermissions);

        // العميل
        $customer = Role::where('name', 'customer')->first();
        $customerPermissions = Permission::whereIn('name', [
            'orders.view',
            'orders.create',
            'invoices.view'
        ])->get();
        $customer->givePermissionTo($customerPermissions);
    }
}
