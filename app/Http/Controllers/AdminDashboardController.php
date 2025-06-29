<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\SystemLicense;
use App\Models\Warehouse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Schema;

class AdminDashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('admin.access');
    }

    /**
     * لوحة التحكم الرئيسية
     */
    public function index()
    {
        try {
            $stats = $this->getSystemStats();
            $recentUsers = User::latest()->take(10)->get();

            // التحقق من وجود جدول system_licenses
            $expiredLicenses = collect();
            $nearExpiryLicenses = collect();

            if (\Schema::hasTable('system_licenses')) {
                $expiredLicenses = SystemLicense::expired()->get();
                $nearExpiryLicenses = SystemLicense::nearExpiry()->get();
            }

            return view('admin.dashboard-simple', compact('stats', 'recentUsers', 'expiredLicenses', 'nearExpiryLicenses'));
        } catch (\Exception $e) {
            // في حالة وجود خطأ، عرض صفحة بسيطة
            $stats = [
                'total_users' => User::count(),
                'active_users' => User::where('is_account_active', true)->count(),
                'expired_users' => 0,
                'total_licenses' => 0,
                'active_licenses' => 0,
                'expired_licenses' => 0,
                'near_expiry_licenses' => 0,
                'total_warehouses' => 0,
                'active_warehouses' => 0,
                'users_by_role' => collect(),
            ];
            $recentUsers = User::latest()->take(10)->get();
            $expiredLicenses = collect();
            $nearExpiryLicenses = collect();

            return view('admin.dashboard-simple', compact('stats', 'recentUsers', 'expiredLicenses', 'nearExpiryLicenses'));
        }
    }

    /**
     * إدارة المستخدمين
     */
    public function users(Request $request)
    {
        $query = User::query();

        // فلترة حسب الدور
        if ($request->filled('role')) {
            $query->where('user_role', $request->role);
        }

        // فلترة حسب الحالة
        if ($request->filled('status')) {
            if ($request->status === 'active') {
                $query->where('is_account_active', true);
            } elseif ($request->status === 'inactive') {
                $query->where('is_account_active', false);
            } elseif ($request->status === 'expired') {
                $query->where('account_expiry_date', '<', now());
            }
        }

        // البحث
        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('email', 'like', '%' . $request->search . '%');
            });
        }

        $users = $query->with(['warehouse'])->paginate(20);
        $userRoles = $this->getUserRoles();

        return view('admin.users.index-simple', compact('users', 'userRoles'));
    }

    /**
     * إنشاء مستخدم جديد
     */
    public function createUser()
    {
        $userRoles = $this->getUserRoles();
        $warehouses = Warehouse::where('status', 'active')->get();
        $licenses = SystemLicense::active()->get();

        return view('admin.users.create', compact('userRoles', 'warehouses', 'licenses'));
    }

    /**
     * حفظ مستخدم جديد
     */
    public function storeUser(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6',
            'user_role' => 'required|in:super_admin,admin,manager,employee,pharmacy,warehouse,sales_rep',
            'account_expiry_date' => 'nullable|date|after:today',
            'warehouse_id' => 'nullable|exists:warehouses,id',
            'license_id' => 'nullable|exists:system_licenses,id',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'department' => 'nullable|string|max:100',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'user_role' => $request->user_role,
            'account_expiry_date' => $request->account_expiry_date,
            'is_account_active' => true,
            'warehouse_id' => $request->warehouse_id,
            'license_id' => $request->license_id,
            'phone' => $request->phone,
            'address' => $request->address,
            'department' => $request->department,
            'permissions' => $request->permissions ?? [],
            'created_by_admin' => auth()->user()->name,
        ]);

        return redirect()->route('admin.users')
            ->with('success', 'تم إنشاء المستخدم بنجاح');
    }

    /**
     * إحصائيات النظام
     */
    private function getSystemStats()
    {
        try {
            $stats = [
                'total_users' => User::count(),
                'active_users' => User::where('is_account_active', true)->count(),
                'expired_users' => User::where('account_expiry_date', '<', now())->count(),
                'total_licenses' => 0,
                'active_licenses' => 0,
                'expired_licenses' => 0,
                'near_expiry_licenses' => 0,
                'total_warehouses' => 0,
                'active_warehouses' => 0,
                'users_by_role' => User::selectRaw('COALESCE(user_role, user_type, "employee") as role, count(*) as count')
                                      ->groupBy('role')
                                      ->pluck('count', 'role'),
            ];

            // إضافة إحصائيات التراخيص إذا كان الجدول موجود
            if (\Schema::hasTable('system_licenses')) {
                $stats['total_licenses'] = SystemLicense::count();
                $stats['active_licenses'] = SystemLicense::active()->count();
                $stats['expired_licenses'] = SystemLicense::expired()->count();
                $stats['near_expiry_licenses'] = SystemLicense::nearExpiry()->count();
            }

            // إضافة إحصائيات المخازن إذا كان الجدول موجود
            if (\Schema::hasTable('warehouses')) {
                $stats['total_warehouses'] = Warehouse::count();
                $stats['active_warehouses'] = Warehouse::where('status', 'active')->count();
            }

            return $stats;
        } catch (\Exception $e) {
            // إرجاع إحصائيات أساسية في حالة الخطأ
            return [
                'total_users' => User::count(),
                'active_users' => User::where('is_account_active', true)->count(),
                'expired_users' => 0,
                'total_licenses' => 0,
                'active_licenses' => 0,
                'expired_licenses' => 0,
                'near_expiry_licenses' => 0,
                'total_warehouses' => 0,
                'active_warehouses' => 0,
                'users_by_role' => collect(),
            ];
        }
    }

    /**
     * أدوار المستخدمين
     */
    private function getUserRoles()
    {
        return [
            'super_admin' => 'مدير عام',
            'admin' => 'مدير نظام',
            'manager' => 'مدير',
            'employee' => 'موظف',
            'pharmacy' => 'صيدلية',
            'warehouse' => 'مخزن',
            'sales_rep' => 'مندوب مبيعات'
        ];
    }

    /**
     * تفعيل/إلغاء تفعيل مستخدم
     */
    public function toggleUserStatus(User $user)
    {
        $user->update(['is_account_active' => !$user->is_account_active]);

        $status = $user->is_account_active ? 'تم تفعيل' : 'تم إلغاء تفعيل';
        return back()->with('success', $status . ' المستخدم بنجاح');
    }

    /**
     * إدارة التراخيص
     */
    public function licenses()
    {
        $licenses = SystemLicense::with('creator')->orderBy('created_at', 'desc')->paginate(15);
        return view('admin.licenses.index', compact('licenses'));
    }

    /**
     * إنشاء ترخيص جديد
     */
    public function createLicense()
    {
        return view('admin.licenses.create');
    }

    /**
     * إدارة المخازن
     */
    public function warehouses()
    {
        $warehouses = Warehouse::with('users')->paginate(15);
        return view('admin.warehouses.index', compact('warehouses'));
    }

    /**
     * تقارير النظام
     */
    public function reports()
    {
        $stats = $this->getSystemStats();
        return view('admin.reports.index', compact('stats'));
    }
}
