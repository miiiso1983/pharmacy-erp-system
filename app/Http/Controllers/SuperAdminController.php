<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SystemLicense;
use App\Models\LicenseUsage;
use App\Models\User;
use App\Models\MasterAdmin;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class SuperAdminController extends Controller
{
    /**
     * لوحة تحكم Super Admin
     */
    public function dashboard()
    {
        $stats = [
            'total_licenses' => SystemLicense::count(),
            'active_licenses' => SystemLicense::where('is_active', true)->count(),
            'expired_licenses' => SystemLicense::where('end_date', '<', now())->count(),
            'total_users' => User::count(),
            'active_users' => User::where('is_account_active', true)->count(),
            'total_admins' => MasterAdmin::count(),
        ];

        $recentLicenses = SystemLicense::with('usage')
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        $systemHealth = $this->getSystemHealth();

        return view('super-admin.dashboard', compact('stats', 'recentLicenses', 'systemHealth'));
    }

    /**
     * إدارة التراخيص
     */
    public function licenses()
    {
        $licenses = SystemLicense::with('usage')
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('super-admin.licenses', compact('licenses'));
    }

    /**
     * إدارة المستخدمين
     */
    public function users()
    {
        $users = User::with('license')
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('super-admin.users', compact('users'));
    }

    /**
     * إدارة المديرين
     */
    public function admins()
    {
        $admins = MasterAdmin::orderBy('created_at', 'desc')->get();

        return view('super-admin.admins', compact('admins'));
    }

    /**
     * إعدادات النظام
     */
    public function settings()
    {
        return view('super-admin.settings');
    }

    /**
     * تقارير النظام
     */
    public function reports()
    {
        $licenseStats = [
            'by_type' => SystemLicense::select('license_type', DB::raw('count(*) as count'))
                ->groupBy('license_type')
                ->get(),
            'by_status' => SystemLicense::select('is_active', DB::raw('count(*) as count'))
                ->groupBy('is_active')
                ->get(),
            'monthly_revenue' => SystemLicense::select(
                DB::raw('MONTH(created_at) as month'),
                DB::raw('YEAR(created_at) as year'),
                DB::raw('SUM(license_cost) as revenue')
            )
                ->groupBy('year', 'month')
                ->orderBy('year', 'desc')
                ->orderBy('month', 'desc')
                ->limit(12)
                ->get(),
        ];

        return view('super-admin.reports', compact('licenseStats'));
    }

    /**
     * فحص صحة النظام
     */
    private function getSystemHealth()
    {
        $health = [
            'database' => 'healthy',
            'storage' => 'healthy',
            'cache' => 'healthy',
            'queue' => 'healthy',
        ];

        try {
            // فحص قاعدة البيانات
            DB::connection()->getPdo();
        } catch (\Exception $e) {
            $health['database'] = 'error';
        }

        try {
            // فحص التخزين
            $diskSpace = disk_free_space(storage_path());
            if ($diskSpace < 1024 * 1024 * 100) { // أقل من 100 ميجا
                $health['storage'] = 'warning';
            }
        } catch (\Exception $e) {
            $health['storage'] = 'error';
        }

        return $health;
    }

    /**
     * تسجيل خروج Super Admin
     */
    public function logout()
    {
        auth()->logout();
        return redirect()->route('login')->with('success', 'تم تسجيل الخروج بنجاح');
    }
}
