<?php

namespace App\Services;

use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use App\Models\User;

class PermissionService
{
    /**
     * الحصول على جميع الصلاحيات مجمعة حسب الفئة
     */
    public static function getGroupedPermissions(): array
    {
        $permissions = Permission::all();
        $grouped = [];

        foreach ($permissions as $permission) {
            $parts = explode('.', $permission->name);
            $category = $parts[0];
            $action = $parts[1] ?? 'general';

            if (!isset($grouped[$category])) {
                $grouped[$category] = [
                    'name' => self::getCategoryName($category),
                    'permissions' => []
                ];
            }

            $grouped[$category]['permissions'][] = [
                'id' => $permission->id,
                'name' => $permission->name,
                'description' => $permission->description ?? self::getPermissionDescription($permission->name),
                'action' => $action
            ];
        }

        return $grouped;
    }

    /**
     * الحصول على اسم الفئة بالعربية
     */
    public static function getCategoryName(string $category): string
    {
        $categories = [
            'dashboard' => 'لوحة التحكم',
            'users' => 'المستخدمون',
            'employees' => 'الموظفون',
            'hr' => 'الموارد البشرية',
            'warehouses' => 'المخازن',
            'items' => 'المنتجات',
            'orders' => 'الطلبات',
            'invoices' => 'الفواتير',
            'collections' => 'التحصيلات',
            'suppliers' => 'الموردون',
            'returns' => 'المرتجعات',
            'reports' => 'التقارير',
            'medical_reps' => 'المندوبون العلميون',
            'settings' => 'الإعدادات',
            'system' => 'النظام'
        ];

        return $categories[$category] ?? $category;
    }

    /**
     * الحصول على وصف الصلاحية
     */
    public static function getPermissionDescription(string $permission): string
    {
        $descriptions = [
            'view' => 'عرض',
            'create' => 'إضافة',
            'edit' => 'تعديل',
            'delete' => 'حذف',
            'export' => 'تصدير',
            'import' => 'استيراد',
            'approve' => 'الموافقة',
            'reject' => 'الرفض',
            'activate' => 'التفعيل',
            'permissions' => 'إدارة الصلاحيات',
            'reports' => 'التقارير',
            'stats' => 'الإحصائيات',
            'charts' => 'الرسوم البيانية'
        ];

        $parts = explode('.', $permission);
        $action = end($parts);

        return $descriptions[$action] ?? $action;
    }

    /**
     * الحصول على جميع الأدوار
     */
    public static function getAllRoles(): array
    {
        return Role::all()->map(function ($role) {
            return [
                'id' => $role->id,
                'name' => $role->name,
                'description' => $role->description ?? self::getRoleDescription($role->name),
                'permissions_count' => $role->permissions()->count(),
                'users_count' => $role->users()->count()
            ];
        })->toArray();
    }

    /**
     * الحصول على وصف الدور
     */
    public static function getRoleDescription(string $role): string
    {
        $descriptions = [
            'super_admin' => 'مدير عام - جميع الصلاحيات',
            'admin' => 'مدير - معظم الصلاحيات',
            'manager' => 'مدير مساعد',
            'hr_manager' => 'مدير الموارد البشرية',
            'warehouse_manager' => 'مدير المخزن',
            'sales_manager' => 'مدير المبيعات',
            'accountant' => 'محاسب',
            'employee' => 'موظف',
            'medical_rep' => 'مندوب علمي',
            'customer' => 'عميل'
        ];

        return $descriptions[$role] ?? $role;
    }

    /**
     * تعيين صلاحيات للمستخدم
     */
    public static function assignPermissionsToUser(User $user, array $permissions, array $roles = []): void
    {
        // إزالة جميع الصلاحيات والأدوار الحالية
        $user->syncPermissions([]);
        $user->syncRoles([]);

        // تعيين الأدوار الجديدة
        if (!empty($roles)) {
            $user->assignRole($roles);
        }

        // تعيين الصلاحيات الإضافية
        if (!empty($permissions)) {
            $user->givePermissionTo($permissions);
        }
    }

    /**
     * التحقق من صلاحية المستخدم
     */
    public static function userHasPermission(User $user, string $permission): bool
    {
        return $user->can($permission);
    }

    /**
     * الحصول على صلاحيات المستخدم
     */
    public static function getUserPermissions(User $user): array
    {
        try {
            $directPermissions = $user->getDirectPermissions();
            $rolePermissions = $user->getPermissionsViaRoles();
            $allPermissions = $user->getAllPermissions();

            return [
                'direct' => $directPermissions->pluck('name')->toArray(),
                'via_roles' => $rolePermissions->pluck('name')->toArray(),
                'all' => $allPermissions->pluck('name')->toArray(),
                'roles' => $user->getRoleNames()->toArray()
            ];
        } catch (\Exception $e) {
            // في حالة عدم وجود صلاحيات، إرجاع مصفوفة فارغة
            return [
                'direct' => [],
                'via_roles' => [],
                'all' => [],
                'roles' => []
            ];
        }
    }

    /**
     * إنشاء دور جديد مع صلاحيات
     */
    public static function createRoleWithPermissions(string $name, string $description, array $permissions): Role
    {
        $role = Role::create([
            'name' => $name,
            'description' => $description,
            'guard_name' => 'web'
        ]);

        if (!empty($permissions)) {
            $role->givePermissionTo($permissions);
        }

        return $role;
    }

    /**
     * تحديث صلاحيات الدور
     */
    public static function updateRolePermissions(Role $role, array $permissions): void
    {
        $role->syncPermissions($permissions);
    }

    /**
     * الحصول على الصلاحيات المقترحة حسب نوع المستخدم
     */
    public static function getSuggestedPermissions(string $userType): array
    {
        $suggestions = [
            'admin' => [
                'dashboard.view', 'dashboard.stats', 'dashboard.charts',
                'users.view', 'users.create', 'users.edit', 'users.delete',
                'employees.view', 'employees.create', 'employees.edit',
                'warehouses.view', 'warehouses.create', 'warehouses.edit',
                'items.view', 'items.create', 'items.edit',
                'orders.view', 'orders.create', 'orders.edit', 'orders.approve',
                'invoices.view', 'invoices.create', 'invoices.edit',
                'reports.view', 'reports.export'
            ],
            'manager' => [
                'dashboard.view', 'dashboard.stats',
                'users.view', 'users.create', 'users.edit',
                'employees.view', 'employees.create', 'employees.edit',
                'orders.view', 'orders.create', 'orders.edit',
                'invoices.view', 'invoices.create',
                'reports.view'
            ],
            'employee' => [
                'dashboard.view',
                'orders.view', 'orders.create',
                'items.view',
                'customers.view'
            ],
            'customer' => [
                'orders.view', 'orders.create',
                'invoices.view'
            ]
        ];

        return $suggestions[$userType] ?? [];
    }

    /**
     * التحقق من تضارب الصلاحيات
     */
    public static function checkPermissionConflicts(array $permissions): array
    {
        $conflicts = [];
        
        // قواعد التضارب
        $conflictRules = [
            'delete' => ['create', 'edit'], // إذا كان يمكن الحذف، يجب أن يكون يمكن الإنشاء والتعديل
            'edit' => ['view'], // إذا كان يمكن التعديل، يجب أن يكون يمكن العرض
            'create' => ['view'], // إذا كان يمكن الإنشاء، يجب أن يكون يمكن العرض
            'approve' => ['view'], // إذا كان يمكن الموافقة، يجب أن يكون يمكن العرض
        ];

        foreach ($permissions as $permission) {
            $parts = explode('.', $permission);
            if (count($parts) >= 2) {
                $module = $parts[0];
                $action = $parts[1];

                if (isset($conflictRules[$action])) {
                    foreach ($conflictRules[$action] as $requiredAction) {
                        $requiredPermission = $module . '.' . $requiredAction;
                        if (!in_array($requiredPermission, $permissions)) {
                            $conflicts[] = [
                                'permission' => $permission,
                                'requires' => $requiredPermission,
                                'message' => "الصلاحية {$permission} تتطلب {$requiredPermission}"
                            ];
                        }
                    }
                }
            }
        }

        return $conflicts;
    }

    /**
     * إصلاح تضارب الصلاحيات تلقائياً
     */
    public static function autoFixPermissionConflicts(array $permissions): array
    {
        $fixed = $permissions;
        $conflicts = self::checkPermissionConflicts($permissions);

        foreach ($conflicts as $conflict) {
            if (!in_array($conflict['requires'], $fixed)) {
                $fixed[] = $conflict['requires'];
            }
        }

        return array_unique($fixed);
    }

    /**
     * الحصول على إحصائيات الصلاحيات
     */
    public static function getPermissionStats(): array
    {
        return [
            'total_permissions' => Permission::count(),
            'total_roles' => Role::count(),
            'users_with_roles' => User::whereHas('roles')->count(),
            'users_with_direct_permissions' => User::whereHas('permissions')->count(),
            'most_used_role' => Role::withCount('users')->orderBy('users_count', 'desc')->first()?->name,
            'least_used_permissions' => Permission::whereDoesntHave('users')
                ->whereDoesntHave('roles')
                ->count()
        ];
    }
}
