<?php

namespace App\Http\Controllers;

use App\Models\SystemLicense;
use App\Models\LicenseUsage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Session;

class LicenseVerificationController extends Controller
{
    /**
     * عرض صفحة إدخال مفتاح الترخيص
     */
    public function showLicenseForm()
    {
        // التحقق من وجود ترخيص مفعل بالفعل
        $currentLicense = $this->getCurrentLicense();

        return view('license.verify', compact('currentLicense'));
    }

    /**
     * التحقق من مفتاح الترخيص
     */
    public function verifyLicense(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'license_key' => [
                'required',
                'string',
                'regex:/^PH-[A-Z0-9]{4}-[A-Z0-9]{4}-[A-Z0-9]{4}$/'
            ],
        ], [
            'license_key.required' => 'مفتاح الترخيص مطلوب',
            'license_key.regex' => 'تنسيق مفتاح الترخيص غير صحيح. يجب أن يكون بالشكل: PH-XXXX-XXXX-XXXX',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $licenseKey = strtoupper(trim($request->license_key));

        // البحث عن الترخيص
        $license = SystemLicense::where('license_key', $licenseKey)->first();

        if (!$license) {
            // محاولة البحث الذكي
            $suggestions = $this->findSimilarLicenses($licenseKey);
            $errorMessage = 'مفتاح الترخيص غير صحيح أو غير موجود.';

            if (!empty($suggestions)) {
                $errorMessage .= ' هل تقصد أحد هذه المفاتيح: ' . implode(', ', $suggestions);
            }

            return back()->withErrors(['license_key' => $errorMessage])->withInput();
        }

        // التحقق من صحة الترخيص
        $validation = $this->validateLicense($license);

        if (!$validation['valid']) {
            return back()->withErrors(['license_key' => $validation['message']])
                        ->withInput();
        }

        // حفظ الترخيص في الجلسة والكاش
        $this->activateLicense($license);

        // تحديث آخر فحص للترخيص
        $license->update(['last_check' => now()]);

        // التحقق من وجود إعداد سابق للنظام
        $hasSystemSetup = $this->checkSystemSetup($license);

        if (!$hasSystemSetup) {
            // توجيه لصفحة الإعداد الأولي
            return redirect()->route('setup.initial')
                           ->with('success', 'تم تفعيل الترخيص بنجاح! الآن قم بإعداد النظام.');
        }

        // رسالة ترحيب مخصصة حسب نوع الترخيص
        $welcomeMessage = $this->getWelcomeMessage($license);

        return redirect()->route('dashboard')
                       ->with('success', $welcomeMessage);
    }

    /**
     * رسالة ترحيب مخصصة
     */
    private function getWelcomeMessage($license)
    {
        $messages = [
            'basic' => 'تم تفعيل الترخيص الأساسي بنجاح! مرحباً بك في نظام إدارة الصيدليات.',
            'full' => 'تم تفعيل الترخيص الكامل بنجاح! استمتع بجميع المميزات المتقدمة.',
            'premium' => 'تم تفعيل الترخيص المميز بنجاح! لديك الآن وصول كامل لجميع الوحدات والمميزات.',
        ];

        $baseMessage = $messages[$license->license_type] ?? 'تم تفعيل الترخيص بنجاح!';

        return $baseMessage . ' مرحباً بك ' . $license->client_name;
    }

    /**
     * عرض معلومات الترخيص الحالي
     */
    public function showLicenseInfo()
    {
        $license = $this->getCurrentLicense();

        if (!$license) {
            return redirect()->route('license.verify')
                           ->with('error', 'لا يوجد ترخيص مفعل. يرجى إدخال مفتاح الترخيص.');
        }

        $usage = $license->usage;
        $limits = $this->getLicenseLimits($license);

        return view('license.info', compact('license', 'usage', 'limits'));
    }

    /**
     * إلغاء تفعيل الترخيص
     */
    public function deactivateLicense()
    {
        Session::forget('active_license');
        Cache::forget('license_' . request()->ip());

        return redirect()->route('license.verify')
                       ->with('success', 'تم إلغاء تفعيل الترخيص بنجاح');
    }

    /**
     * التحقق من صحة الترخيص
     */
    private function validateLicense($license)
    {
        // التحقق من تفعيل الترخيص
        if (!$license->is_active) {
            return [
                'valid' => false,
                'message' => 'هذا الترخيص معلق. يرجى التواصل مع الإدارة.'
            ];
        }

        // التحقق من انتهاء صلاحية الترخيص
        if ($license->end_date < now()) {
            return [
                'valid' => false,
                'message' => 'انتهت صلاحية هذا الترخيص في ' . $license->end_date->format('Y-m-d') . '. يرجى تجديد الترخيص.'
            ];
        }

        // التحقق من حالة الدفع
        if ($license->payment_status === 'overdue') {
            return [
                'valid' => false,
                'message' => 'هناك مستحقات مالية متأخرة على هذا الترخيص. يرجى التواصل مع الإدارة.'
            ];
        }

        // تحذير إذا كان الترخيص قريب الانتهاء
        if ($license->end_date <= now()->addDays(30)) {
            $daysRemaining = $license->end_date->diffInDays(now());
            Session::flash('warning', 'تنبيه: سينتهي الترخيص خلال ' . $daysRemaining . ' يوم. يرجى التواصل مع الإدارة للتجديد.');
        }

        return ['valid' => true, 'message' => 'الترخيص صالح'];
    }

    /**
     * تفعيل الترخيص
     */
    private function activateLicense($license)
    {
        $licenseData = [
            'id' => $license->id,
            'key' => $license->license_key,
            'client_name' => $license->client_name,
            'license_type' => $license->license_type,
            'end_date' => $license->end_date->toDateString(),
            'max_users' => $license->max_users,
            'max_warehouses' => $license->max_warehouses,
            'max_branches' => $license->max_branches,
            'features' => $license->features,
            'modules' => $license->modules,
            'activated_at' => now()->toDateTimeString()
        ];

        // حفظ في الجلسة
        Session::put('active_license', $licenseData);

        // حفظ في الكاش لمدة 24 ساعة
        Cache::put('license_' . request()->ip(), $licenseData, now()->addHours(24));
    }

    /**
     * الحصول على الترخيص الحالي
     */
    public function getCurrentLicense()
    {
        // محاولة الحصول من الجلسة أولاً
        $licenseData = Session::get('active_license');

        // إذا لم يوجد، محاولة الحصول من الكاش
        if (!$licenseData) {
            $licenseData = Cache::get('license_' . request()->ip());
        }

        if (!$licenseData) {
            return null;
        }

        // الحصول على الترخيص من قاعدة البيانات للتأكد من صحته
        $license = SystemLicense::find($licenseData['id']);

        if (!$license || !$license->is_active || $license->end_date < now()) {
            // إزالة الترخيص غير الصالح
            Session::forget('active_license');
            Cache::forget('license_' . request()->ip());
            return null;
        }

        return $license;
    }

    /**
     * الحصول على حدود الترخيص
     */
    private function getLicenseLimits($license)
    {
        $usage = $license->usage;

        return [
            'users' => [
                'current' => $usage->current_users ?? 0,
                'max' => $license->max_users,
                'percentage' => $usage ? $usage->users_usage_percentage : 0
            ],
            'warehouses' => [
                'current' => $usage->current_warehouses ?? 0,
                'max' => $license->max_warehouses,
                'percentage' => $usage ? $usage->warehouses_usage_percentage : 0
            ],
            'branches' => [
                'current' => $usage->current_branches ?? 0,
                'max' => $license->max_branches,
                'percentage' => $usage ? $usage->branches_usage_percentage : 0
            ]
        ];
    }

    /**
     * التحقق من صلاحية للوصول لميزة معينة
     */
    public function hasFeature($feature)
    {
        $license = $this->getCurrentLicense();

        if (!$license) {
            return false;
        }

        return in_array($feature, $license->features ?? []);
    }

    /**
     * التحقق من صلاحية للوصول لوحدة معينة
     */
    public function hasModule($module)
    {
        $license = $this->getCurrentLicense();

        if (!$license) {
            return false;
        }

        return in_array($module, $license->modules ?? []);
    }

    /**
     * التحقق من وجود إعداد للنظام
     */
    private function checkSystemSetup($license)
    {
        // التحقق من وجود مستخدمين مرتبطين بالترخيص
        $hasUsers = \App\Models\User::where('license_id', $license->id)->exists();

        // التحقق من وجود مخازن مرتبطة بالترخيص
        $hasWarehouses = \App\Models\Warehouse::where('license_id', $license->id)->exists();

        return $hasUsers || $hasWarehouses;
    }

    /**
     * البحث عن تراخيص مشابهة
     */
    private function findSimilarLicenses($inputKey)
    {
        // إزالة الشرطات والمسافات
        $cleanInput = str_replace(['-', ' '], '', $inputKey);

        // البحث عن تراخيص تحتوي على جزء من المفتاح
        $licenses = SystemLicense::where('is_active', true)
                                ->where('end_date', '>', now())
                                ->get(['license_key']);

        $suggestions = [];

        foreach ($licenses as $license) {
            $cleanLicense = str_replace(['-', ' '], '', $license->license_key);

            // حساب التشابه
            $similarity = 0;
            similar_text($cleanInput, $cleanLicense, $similarity);

            // إذا كان التشابه أكثر من 70%
            if ($similarity > 70) {
                $suggestions[] = $license->license_key;
            }
        }

        return array_slice($suggestions, 0, 3); // أقصى 3 اقتراحات
    }

    /**
     * API للبحث السريع عن التراخيص
     */
    public function searchLicenses(Request $request)
    {
        $query = $request->get('q', '');

        if (strlen($query) < 2) {
            return response()->json([]);
        }

        $licenses = SystemLicense::where('license_key', 'LIKE', '%' . $query . '%')
                                ->where('is_active', true)
                                ->where('end_date', '>', now())
                                ->limit(5)
                                ->get(['license_key', 'client_name', 'license_type']);

        return response()->json($licenses->map(function($license) {
            return [
                'key' => $license->license_key,
                'label' => $license->license_key . ' - ' . $license->client_name,
                'type' => $license->license_type
            ];
        }));
    }
}
