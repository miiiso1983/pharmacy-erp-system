<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use App\Services\PermissionService;
use App\Models\User;
use Illuminate\Support\Facades\Validator;

class PermissionController extends Controller
{
    /**
     * عرض صفحة إدارة الصلاحيات
     */
    public function index()
    {
        $groupedPermissions = PermissionService::getGroupedPermissions();
        $roles = PermissionService::getAllRoles();
        $stats = PermissionService::getPermissionStats();

        // إضافة صلاحيات كل دور
        foreach ($roles as &$role) {
            $roleModel = Role::find($role['id']);
            if ($roleModel) {
                $role['permissions'] = $roleModel->permissions->pluck('name')->toArray();
            } else {
                $role['permissions'] = [];
            }
        }

        return view('permissions.index', compact('groupedPermissions', 'roles', 'stats'));
    }

    /**
     * إنشاء دور جديد
     */
    public function createRole(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:roles,name',
            'description' => 'nullable|string|max:500',
            'permissions' => 'array',
            'permissions.*' => 'exists:permissions,name'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $role = PermissionService::createRoleWithPermissions(
                $request->name,
                $request->description,
                $request->permissions ?? []
            );

            return response()->json([
                'success' => true,
                'message' => 'تم إنشاء الدور بنجاح',
                'role' => [
                    'id' => $role->id,
                    'name' => $role->name,
                    'description' => $role->description,
                    'permissions_count' => $role->permissions()->count()
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء إنشاء الدور: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * تحديث دور
     */
    public function updateRole(Request $request, $id)
    {
        $role = Role::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:roles,name,' . $id,
            'description' => 'nullable|string|max:500',
            'permissions' => 'array',
            'permissions.*' => 'exists:permissions,name'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $role->update([
                'name' => $request->name,
                'description' => $request->description
            ]);

            PermissionService::updateRolePermissions($role, $request->permissions ?? []);

            return response()->json([
                'success' => true,
                'message' => 'تم تحديث الدور بنجاح'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء تحديث الدور: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * حذف دور
     */
    public function deleteRole($id)
    {
        $role = Role::findOrFail($id);

        // منع حذف دور المدير العام
        if ($role->name === 'super_admin') {
            return response()->json([
                'success' => false,
                'message' => 'لا يمكن حذف دور المدير العام'
            ], 403);
        }

        // التحقق من وجود مستخدمين مرتبطين بالدور
        if ($role->users()->count() > 0) {
            return response()->json([
                'success' => false,
                'message' => 'لا يمكن حذف الدور لأنه مرتبط بمستخدمين'
            ], 400);
        }

        try {
            $role->delete();

            return response()->json([
                'success' => true,
                'message' => 'تم حذف الدور بنجاح'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء حذف الدور: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * الحصول على صلاحيات دور معين
     */
    public function getRolePermissions($id)
    {
        try {
            $role = Role::with('permissions')->findOrFail($id);
            $allPermissions = Permission::all();

            return response()->json([
                'success' => true,
                'role' => [
                    'id' => $role->id,
                    'name' => $role->name,
                    'description' => $role->description,
                    'permissions' => $role->permissions->pluck('name')->toArray()
                ],
                'permissions' => $allPermissions->map(function ($permission) {
                    return [
                        'id' => $permission->id,
                        'name' => $permission->name,
                        'description' => $permission->description ?? PermissionService::getPermissionDescription($permission->name)
                    ];
                })
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء تحميل بيانات الدور: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * تعيين صلاحيات لمستخدم
     */
    public function assignUserPermissions(Request $request, $userId)
    {
        $user = User::findOrFail($userId);

        $validator = Validator::make($request->all(), [
            'roles' => 'array',
            'roles.*' => 'exists:roles,name',
            'permissions' => 'array',
            'permissions.*' => 'exists:permissions,name'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            // التحقق من تضارب الصلاحيات
            $permissions = $request->permissions ?? [];
            $conflicts = PermissionService::checkPermissionConflicts($permissions);

            if (!empty($conflicts)) {
                // إصلاح التضارب تلقائياً
                $permissions = PermissionService::autoFixPermissionConflicts($permissions);
            }

            PermissionService::assignPermissionsToUser(
                $user,
                $permissions,
                $request->roles ?? []
            );

            return response()->json([
                'success' => true,
                'message' => 'تم تحديث صلاحيات المستخدم بنجاح',
                'conflicts_fixed' => !empty($conflicts),
                'conflicts' => $conflicts
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء تحديث الصلاحيات: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * الحصول على صلاحيات مستخدم
     */
    public function getUserPermissions($userId)
    {
        $user = User::with(['roles', 'permissions'])->findOrFail($userId);
        $userPermissions = PermissionService::getUserPermissions($user);

        return response()->json([
            'success' => true,
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'permissions' => $userPermissions
            ]
        ]);
    }

    /**
     * الحصول على الصلاحيات المقترحة حسب نوع المستخدم
     */
    public function getSuggestedPermissions(Request $request)
    {
        $userType = $request->get('user_type');
        $suggestions = PermissionService::getSuggestedPermissions($userType);

        return response()->json([
            'success' => true,
            'user_type' => $userType,
            'suggested_permissions' => $suggestions
        ]);
    }

    /**
     * التحقق من صلاحية مستخدم
     */
    public function checkUserPermission(Request $request, $userId)
    {
        $user = User::findOrFail($userId);
        $permission = $request->get('permission');

        $hasPermission = PermissionService::userHasPermission($user, $permission);

        return response()->json([
            'success' => true,
            'user_id' => $userId,
            'permission' => $permission,
            'has_permission' => $hasPermission
        ]);
    }

    /**
     * تصدير مصفوفة الصلاحيات
     */
    public function exportPermissionMatrix()
    {
        $roles = Role::with('permissions')->get();
        $permissions = Permission::all();

        $matrix = [];
        foreach ($permissions as $permission) {
            $row = [
                'permission' => $permission->name,
                'description' => $permission->description ?? PermissionService::getPermissionDescription($permission->name)
            ];

            foreach ($roles as $role) {
                $row[$role->name] = $role->hasPermissionTo($permission->name) ? 'نعم' : 'لا';
            }

            $matrix[] = $row;
        }

        return response()->json([
            'success' => true,
            'matrix' => $matrix,
            'roles' => $roles->pluck('name')->toArray()
        ]);
    }

    /**
     * إحصائيات الصلاحيات
     */
    public function getPermissionStats()
    {
        $stats = PermissionService::getPermissionStats();

        return response()->json([
            'success' => true,
            'stats' => $stats
        ]);
    }

    /**
     * البحث في الصلاحيات
     */
    public function searchPermissions(Request $request)
    {
        $query = $request->get('q');
        
        $permissions = Permission::where('name', 'like', "%{$query}%")
            ->orWhere('description', 'like', "%{$query}%")
            ->get();

        return response()->json([
            'success' => true,
            'query' => $query,
            'permissions' => $permissions->map(function ($permission) {
                return [
                    'id' => $permission->id,
                    'name' => $permission->name,
                    'description' => $permission->description ?? PermissionService::getPermissionDescription($permission->name)
                ];
            })
        ]);
    }

    /**
     * نسخ صلاحيات من دور إلى آخر أو إنشاء دور جديد
     */
    public function copyRolePermissions(Request $request)
    {
        // إذا كان هناك new_role_name، إنشاء دور جديد
        if ($request->has('new_role_name')) {
            $validator = Validator::make($request->all(), [
                'source_role_id' => 'required|exists:roles,id',
                'new_role_name' => 'required|string|unique:roles,name'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'errors' => $validator->errors()
                ], 422);
            }

            try {
                $sourceRole = Role::findOrFail($request->source_role_id);

                // إنشاء الدور الجديد
                $newRole = Role::create([
                    'name' => $request->new_role_name,
                    'description' => 'نسخة من ' . ($sourceRole->description ?? $sourceRole->name),
                    'guard_name' => 'web'
                ]);

                // نسخ الصلاحيات
                $permissions = $sourceRole->permissions;
                $newRole->syncPermissions($permissions);

                return response()->json([
                    'success' => true,
                    'message' => 'تم إنشاء الدور ونسخ الصلاحيات بنجاح',
                    'role' => [
                        'id' => $newRole->id,
                        'name' => $newRole->name,
                        'description' => $newRole->description,
                        'permissions_count' => $permissions->count()
                    ]
                ]);

            } catch (\Exception $e) {
                return response()->json([
                    'success' => false,
                    'message' => 'حدث خطأ أثناء إنشاء الدور: ' . $e->getMessage()
                ], 500);
            }
        }

        // نسخ إلى دور موجود
        $validator = Validator::make($request->all(), [
            'source_role_id' => 'required|exists:roles,id',
            'target_role_id' => 'required|exists:roles,id|different:source_role_id'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $sourceRole = Role::findOrFail($request->source_role_id);
            $targetRole = Role::findOrFail($request->target_role_id);

            $sourcePermissions = $sourceRole->permissions->pluck('name')->toArray();
            PermissionService::updateRolePermissions($targetRole, $sourcePermissions);

            return response()->json([
                'success' => true,
                'message' => "تم نسخ " . count($sourcePermissions) . " صلاحية من {$sourceRole->name} إلى {$targetRole->name}"
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء نسخ الصلاحيات: ' . $e->getMessage()
            ], 500);
        }
    }
}
