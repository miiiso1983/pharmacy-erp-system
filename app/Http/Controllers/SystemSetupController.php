<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Session;
use App\Models\User;
use App\Models\Warehouse;
use App\Models\SystemLicense;
use App\Models\LicenseUsage;

class SystemSetupController extends Controller
{
    /**
     * عرض صفحة الإعداد الأولي
     */
    public function showSetup()
    {
        // التحقق من وجود ترخيص مفعل
        $licenseController = new \App\Http\Controllers\LicenseVerificationController();
        $currentLicense = $licenseController->getCurrentLicense();

        if (!$currentLicense) {
            return redirect()->route('license.verify')
                           ->with('error', 'يرجى تفعيل الترخيص أولاً');
        }

        // التحقق من وجود إعداد سابق
        $hasExistingSetup = $this->checkExistingSetup($currentLicense);

        return view('setup.initial', compact('currentLicense', 'hasExistingSetup'));
    }

    /**
     * معالجة الإعداد الأولي
     */
    public function processSetup(Request $request)
    {
        $licenseController = new \App\Http\Controllers\LicenseVerificationController();
        $currentLicense = $licenseController->getCurrentLicense();

        if (!$currentLicense) {
            return redirect()->route('license.verify')
                           ->with('error', 'يرجى تفعيل الترخيص أولاً');
        }

        $validator = Validator::make($request->all(), [
            'setup_type' => 'required|in:fresh,reset',
            'admin_name' => 'required|string|max:255',
            'admin_email' => 'required|email|unique:users,email',
            'admin_password' => 'required|string|min:8|confirmed',
            'admin_phone' => 'nullable|string|max:20',
            'admin_address' => 'nullable|string',
            'company_name' => 'required|string|max:255',
            'company_address' => 'nullable|string',
            'company_phone' => 'nullable|string|max:20',
            'warehouse_name' => 'required|string|max:255',
            'warehouse_address' => 'nullable|string',
        ], [
            'setup_type.required' => 'نوع الإعداد مطلوب',
            'admin_name.required' => 'اسم المدير مطلوب',
            'admin_email.required' => 'البريد الإلكتروني مطلوب',
            'admin_email.unique' => 'البريد الإلكتروني مستخدم مسبقاً',
            'admin_password.required' => 'كلمة المرور مطلوبة',
            'admin_password.min' => 'كلمة المرور يجب أن تكون 8 أحرف على الأقل',
            'admin_password.confirmed' => 'تأكيد كلمة المرور غير متطابق',
            'admin_phone.max' => 'رقم الهاتف طويل جداً',
            'company_name.required' => 'اسم الشركة مطلوب',
            'company_phone.max' => 'رقم هاتف الشركة طويل جداً',
            'warehouse_name.required' => 'اسم المخزن مطلوب',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        try {
            DB::beginTransaction();

            // إذا كان إعادة تعيين، مسح البيانات الموجودة
            if ($request->setup_type === 'reset') {
                $this->resetSystemData($currentLicense);
            }

            // إنشاء المدير الرئيسي
            $admin = $this->createSystemAdmin($request, $currentLicense);

            // إنشاء المخزن الرئيسي
            $warehouse = $this->createMainWarehouse($request, $currentLicense, $admin);

            // إنشاء البيانات الأساسية
            $this->createBasicData($currentLicense, $admin, $warehouse);

            // تحديث معلومات الترخيص
            $this->updateLicenseUsage($currentLicense);

            // حفظ معلومات الإعداد
            $this->saveSetupInfo($request, $currentLicense, $admin);

            DB::commit();

            // تسجيل دخول المدير تلقائياً
            auth()->login($admin);

            return redirect()->route('dashboard')
                           ->with('success', 'تم إعداد النظام بنجاح! مرحباً بك في نظام إدارة الصيدليات');

        } catch (\Exception $e) {
            DB::rollBack();

            return back()->withErrors(['error' => 'حدث خطأ أثناء الإعداد: ' . $e->getMessage()])
                        ->withInput();
        }
    }

    /**
     * التحقق من وجود إعداد سابق
     */
    private function checkExistingSetup($license)
    {
        return User::where('license_id', $license->id)->exists() ||
               Warehouse::where('license_id', $license->id)->exists();
    }

    /**
     * إعادة تعيين بيانات النظام
     */
    private function resetSystemData($license)
    {
        // حذف المستخدمين المرتبطين بالترخيص
        User::where('license_id', $license->id)->delete();

        // حذف المخازن المرتبطة بالترخيص
        Warehouse::where('license_id', $license->id)->delete();

        // يمكن إضافة حذف جداول أخرى حسب الحاجة
        // مثل: Orders, Items, Invoices, etc.

        // إعادة تعيين إحصائيات الاستخدام
        $usage = LicenseUsage::where('license_id', $license->id)->first();
        if ($usage) {
            $usage->update([
                'current_users' => 0,
                'current_warehouses' => 0,
                'current_branches' => 0,
                'peak_users' => 0,
                'peak_warehouses' => 0,
                'peak_branches' => 0,
                'last_updated_at' => now()
            ]);
        }
    }

    /**
     * إنشاء المدير الرئيسي للنظام
     */
    private function createSystemAdmin($request, $license)
    {
        return User::create([
            'name' => $request->admin_name,
            'email' => $request->admin_email,
            'password' => Hash::make($request->admin_password),
            'user_role' => 'admin',
            'user_type' => 'admin',
            'status' => 'active',
            'is_account_active' => true,
            'license_id' => $license->id,
            'phone' => $request->admin_phone ?? null,
            'address' => $request->admin_address ?? null,
            'company_name' => $request->company_name,
            'notes' => 'مدير النظام الرئيسي - تم إنشاؤه أثناء الإعداد الأولي'
        ]);
    }

    /**
     * إنشاء المخزن الرئيسي
     */
    private function createMainWarehouse($request, $license, $admin)
    {
        // توليد كود فريد للمخزن
        $warehouseCode = $this->generateWarehouseCode($license);

        return Warehouse::create([
            'name' => $request->warehouse_name,
            'code' => $warehouseCode,
            'description' => 'المخزن الرئيسي - تم إنشاؤه أثناء الإعداد الأولي',
            'city' => $this->extractCity($request->warehouse_address ?? $request->company_address),
            'area' => $this->extractArea($request->warehouse_address ?? $request->company_address),
            'address' => $request->warehouse_address ?? $request->company_address ?? 'المقر الرئيسي',
            'phone' => $request->company_phone,
            'manager' => $admin->name,
            'type' => 'main',
            'status' => true,
            'total_value' => 0.00,
            'total_items' => 0,
            'notes' => 'المخزن الرئيسي - تم إنشاؤه أثناء الإعداد الأولي',
            // الحقول الجديدة
            'license_id' => $license->id,
            'location' => $request->warehouse_address ?? 'المقر الرئيسي',
            'manager_id' => $admin->id,
            'is_active' => true,
            'warehouse_type' => 'main',
            'capacity' => 10000,
            'contact_phone' => $request->company_phone,
            'contact_email' => $request->admin_email,
            'created_by' => $admin->id
        ]);
    }

    /**
     * توليد كود فريد للمخزن
     */
    private function generateWarehouseCode($license)
    {
        // استخدام جزء من مفتاح الترخيص + رقم تسلسلي
        $licensePrefix = substr(str_replace('-', '', $license->license_key), -4);
        $warehouseCount = Warehouse::where('license_id', $license->id)->count() + 1;

        return 'WH-' . $licensePrefix . '-' . str_pad($warehouseCount, 3, '0', STR_PAD_LEFT);
    }

    /**
     * استخراج المدينة من العنوان
     */
    private function extractCity($address)
    {
        if (empty($address)) {
            return 'بغداد';
        }

        // البحث عن أسماء المدن العراقية الشائعة
        $cities = ['بغداد', 'البصرة', 'الموصل', 'أربيل', 'النجف', 'كربلاء', 'الحلة', 'الرمادي', 'تكريت', 'السماوة'];

        foreach ($cities as $city) {
            if (strpos($address, $city) !== false) {
                return $city;
            }
        }

        return 'بغداد'; // افتراضي
    }

    /**
     * استخراج المنطقة من العنوان
     */
    private function extractArea($address)
    {
        if (empty($address)) {
            return 'المركز';
        }

        // استخراج أول كلمة بعد اسم المدينة أو أول منطقة مذكورة
        $areas = ['الكرخ', 'الرصافة', 'الكرادة', 'الجادرية', 'المنصور', 'الحرية', 'الشعلة', 'الدورة'];

        foreach ($areas as $area) {
            if (strpos($address, $area) !== false) {
                return $area;
            }
        }

        return 'المركز'; // افتراضي
    }

    /**
     * إنشاء البيانات الأساسية للنظام
     */
    private function createBasicData($license, $admin, $warehouse)
    {
        // يمكن إضافة إنشاء بيانات أساسية أخرى هنا
        // مثل: فئات المنتجات، وحدات القياس، إعدادات النظام، إلخ

        // مثال: إنشاء فئات أساسية للأدوية
        $this->createBasicCategories($license, $admin);

        // مثال: إنشاء وحدات قياس أساسية
        $this->createBasicUnits($license, $admin);

        // مثال: إنشاء إعدادات النظام الأساسية
        $this->createSystemSettings($license, $admin);
    }

    /**
     * إنشاء فئات أساسية
     */
    private function createBasicCategories($license, $admin)
    {
        // يمكن إضافة إنشاء فئات أساسية هنا
        // إذا كان لديك جدول categories
    }

    /**
     * إنشاء وحدات قياس أساسية
     */
    private function createBasicUnits($license, $admin)
    {
        // يمكن إضافة إنشاء وحدات قياس أساسية هنا
        // إذا كان لديك جدول units
    }

    /**
     * إنشاء إعدادات النظام الأساسية
     */
    private function createSystemSettings($license, $admin)
    {
        // يمكن إضافة إنشاء إعدادات النظام هنا
        // إذا كان لديك جدول settings
    }

    /**
     * تحديث إحصائيات استخدام الترخيص
     */
    private function updateLicenseUsage($license)
    {
        $usage = LicenseUsage::where('license_id', $license->id)->first();

        if ($usage) {
            $usage->update([
                'current_users' => 1, // المدير الجديد
                'current_warehouses' => 1, // المخزن الجديد
                'current_branches' => 1, // الفرع الرئيسي
                'peak_users' => 1,
                'peak_warehouses' => 1,
                'peak_branches' => 1,
                'last_updated_at' => now()
            ]);
        }
    }

    /**
     * حفظ معلومات الإعداد
     */
    private function saveSetupInfo($request, $license, $admin)
    {
        Session::put('system_setup', [
            'completed' => true,
            'setup_date' => now(),
            'license_id' => $license->id,
            'admin_id' => $admin->id,
            'company_name' => $request->company_name,
            'setup_type' => $request->setup_type
        ]);
    }

    /**
     * عرض صفحة تأكيد إعادة التعيين
     */
    public function showResetConfirmation()
    {
        $licenseController = new \App\Http\Controllers\LicenseVerificationController();
        $currentLicense = $licenseController->getCurrentLicense();

        if (!$currentLicense) {
            return redirect()->route('license.verify');
        }

        $hasExistingSetup = $this->checkExistingSetup($currentLicense);

        return view('setup.reset-confirmation', compact('currentLicense', 'hasExistingSetup'));
    }
}
