<?php

namespace App\Http\Controllers\MasterAdmin;

use App\Http\Controllers\Controller;
use App\Models\MasterAdmin;
use App\Models\SystemLicense;
use App\Models\LicenseUsage;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Carbon\Carbon;

class MasterDashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('master.admin');
    }

    /**
     * لوحة التحكم الرئيسية للماستر أدمن
     */
    public function index()
    {
        $stats = $this->getSystemStats();
        $recentLicenses = SystemLicense::with('creator')->latest()->take(10)->get();
        $expiredLicenses = SystemLicense::where('end_date', '<', now())->get();
        $nearExpiryLicenses = SystemLicense::where('end_date', '<=', now()->addDays(30))
                                          ->where('end_date', '>', now())->get();
        $overLimitLicenses = $this->getOverLimitLicenses();

        return view('master-admin.dashboard', compact(
            'stats',
            'recentLicenses',
            'expiredLicenses',
            'nearExpiryLicenses',
            'overLimitLicenses'
        ));
    }

    /**
     * إدارة التراخيص
     */
    public function licenses(Request $request)
    {
        $query = SystemLicense::with(['creator', 'usage']);

        // فلترة حسب الحالة
        if ($request->filled('status')) {
            switch ($request->status) {
                case 'active':
                    $query->where('is_active', true)->where('end_date', '>', now());
                    break;
                case 'expired':
                    $query->where('end_date', '<', now());
                    break;
                case 'near_expiry':
                    $query->where('end_date', '<=', now()->addDays(30))
                          ->where('end_date', '>', now());
                    break;
                case 'inactive':
                    $query->where('is_active', false);
                    break;
            }
        }

        // فلترة حسب النوع
        if ($request->filled('type')) {
            $query->where('license_type', $request->type);
        }

        // البحث
        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('client_name', 'like', '%' . $request->search . '%')
                  ->orWhere('client_email', 'like', '%' . $request->search . '%')
                  ->orWhere('license_key', 'like', '%' . $request->search . '%');
            });
        }

        $licenses = $query->orderBy('created_at', 'desc')->paginate(20);

        return view('master-admin.licenses.index', compact('licenses'));
    }

    /**
     * إحصائيات النظام
     */
    private function getSystemStats()
    {
        return [
            'total_licenses' => SystemLicense::count(),
            'active_licenses' => SystemLicense::where('is_active', true)
                                              ->where('end_date', '>', now())->count(),
            'expired_licenses' => SystemLicense::where('end_date', '<', now())->count(),
            'near_expiry_licenses' => SystemLicense::where('end_date', '<=', now()->addDays(30))
                                                   ->where('end_date', '>', now())->count(),
            'total_revenue' => SystemLicense::where('payment_status', 'paid')->sum('license_cost'),
            'pending_payments' => SystemLicense::where('payment_status', 'pending')->sum('license_cost'),
            'total_users_allowed' => SystemLicense::where('is_active', true)->sum('max_users'),
            'total_warehouses_allowed' => SystemLicense::where('is_active', true)->sum('max_warehouses'),
            'over_limit_licenses' => $this->getOverLimitLicenses()->count(),
            'licenses_by_type' => SystemLicense::selectRaw('license_type, count(*) as count')
                                               ->groupBy('license_type')
                                               ->pluck('count', 'license_type'),
        ];
    }

    /**
     * إنشاء ترخيص جديد
     */
    public function createLicense()
    {
        $licenseTypes = $this->getLicenseTypes();
        $features = $this->getAvailableFeatures();
        $modules = $this->getAvailableModules();

        return view('master-admin.licenses.create', compact('licenseTypes', 'features', 'modules'));
    }

    /**
     * حفظ ترخيص جديد
     */
    public function storeLicense(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'license_type' => 'required|in:basic,full,premium',
            'client_name' => 'required|string|max:255',
            'client_email' => 'required|email',
            'client_phone' => 'nullable|string|max:20',
            'client_address' => 'nullable|string',
            'client_company' => 'nullable|string|max:255',
            'duration_months' => 'required|integer|min:1|max:60',
            'max_users' => 'required|integer|min:1|max:1000',
            'max_warehouses' => 'required|integer|min:1|max:100',
            'max_branches' => 'required|integer|min:1|max:50',
            'license_cost' => 'nullable|numeric|min:0',
            'features' => 'array',
            'modules' => 'array',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $license = SystemLicense::create([
            'license_key' => $this->generateLicenseKey(),
            'license_type' => $request->license_type,
            'client_name' => $request->client_name,
            'client_email' => $request->client_email,
            'client_phone' => $request->client_phone,
            'client_address' => $request->client_address,
            'client_company' => $request->client_company,
            'start_date' => now(),
            'end_date' => now()->addMonths((int) $request->duration_months),
            'max_users' => $request->max_users,
            'max_warehouses' => $request->max_warehouses,
            'max_branches' => $request->max_branches,
            'is_active' => true,
            'features' => $request->features ?? [],
            'modules' => $request->modules ?? [],
            'license_cost' => $request->license_cost,
            'payment_status' => 'pending',
            'notes' => $request->notes,
            'created_by' => Auth::guard('master_admin')->id(),
        ]);

        // إنشاء سجل استخدام للترخيص
        LicenseUsage::create(['license_id' => $license->id]);

        return redirect()->route('master-admin.licenses.index')
            ->with('success', 'تم إنشاء الترخيص بنجاح. مفتاح الترخيص: ' . $license->license_key);
    }

    /**
     * عرض تفاصيل ترخيص
     */
    public function showLicense(SystemLicense $license)
    {
        $license->load(['creator', 'usage']);
        return view('master-admin.licenses.show', compact('license'));
    }

    /**
     * تمديد ترخيص
     */
    public function extendLicense(Request $request, SystemLicense $license)
    {
        $validator = Validator::make($request->all(), [
            'months' => 'required|integer|min:1|max:60'
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator);
        }

        // تحويل القيمة إلى integer صراحة
        $months = (int) $request->months;

        // إنشاء تاريخ جديد باستخدام Carbon
        $currentEndDate = Carbon::parse($license->end_date);
        $newEndDate = $currentEndDate->addMonths($months);

        $license->update([
            'end_date' => $newEndDate,
            'is_active' => true
        ]);

        return back()->with('success', 'تم تمديد الترخيص لمدة ' . $months . ' شهر');
    }

    /**
     * تعليق/تفعيل ترخيص
     */
    public function toggleLicense(SystemLicense $license)
    {
        $license->update(['is_active' => !$license->is_active]);

        $status = $license->is_active ? 'تم تفعيل' : 'تم تعليق';
        return back()->with('success', $status . ' الترخيص بنجاح');
    }

    /**
     * التراخيص التي تجاوزت الحد المسموح
     */
    private function getOverLimitLicenses()
    {
        return collect(); // مؤقتاً حتى ننشئ العلاقات
    }

    /**
     * توليد مفتاح ترخيص فريد
     */
    private function generateLicenseKey()
    {
        do {
            $key = 'PH-' . strtoupper(Str::random(4)) . '-' . strtoupper(Str::random(4)) . '-' . strtoupper(Str::random(4));
        } while (SystemLicense::where('license_key', $key)->exists());

        return $key;
    }

    /**
     * أنواع التراخيص
     */
    private function getLicenseTypes()
    {
        return [
            'basic' => 'أساسي',
            'full' => 'كامل',
            'premium' => 'مميز'
        ];
    }

    /**
     * المميزات المتاحة
     */
    private function getAvailableFeatures()
    {
        return [
            'inventory_management' => 'إدارة المخزون',
            'sales_management' => 'إدارة المبيعات',
            'purchase_management' => 'إدارة المشتريات',
            'financial_reports' => 'التقارير المالية',
            'user_management' => 'إدارة المستخدمين',
            'backup_restore' => 'النسخ الاحتياطي',
            'api_access' => 'الوصول للـ API',
            'mobile_app' => 'تطبيق الهاتف',
            'multi_branch' => 'متعدد الفروع',
            'advanced_reports' => 'تقارير متقدمة'
        ];
    }

    /**
     * الوحدات المتاحة
     */
    private function getAvailableModules()
    {
        return [
            'pharmacy' => 'وحدة الصيدلية',
            'warehouse' => 'وحدة المخزن',
            'accounting' => 'وحدة المحاسبة',
            'hr' => 'وحدة الموارد البشرية',
            'crm' => 'إدارة العملاء',
            'pos' => 'نقطة البيع',
            'manufacturing' => 'وحدة التصنيع',
            'quality_control' => 'مراقبة الجودة'
        ];
    }
}
