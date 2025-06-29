<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Http\Controllers\LicenseVerificationController;
use Illuminate\Support\Facades\Session;

class CheckLicense
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // تجاهل فحص الترخيص لصفحات معينة
        $excludedRoutes = [
            'license.verify',
            'license.verify.submit',
            'license.deactivate',
            'master-admin.*',
            'language.*',
            'login',
            'logout',
            'register'
        ];

        // التحقق من أن المسار الحالي ليس مستثنى
        foreach ($excludedRoutes as $route) {
            if ($request->routeIs($route)) {
                return $next($request);
            }
        }

        // إنشاء instance من LicenseVerificationController
        $licenseController = new LicenseVerificationController();

        // التحقق من وجود ترخيص مفعل
        $currentLicense = $licenseController->getCurrentLicense();

        if (!$currentLicense) {
            // إذا كان الطلب AJAX، إرجاع JSON response
            if ($request->expectsJson()) {
                return response()->json([
                    'error' => 'لا يوجد ترخيص مفعل',
                    'message' => 'يرجى تفعيل الترخيص للوصول إلى النظام',
                    'redirect' => route('license.verify')
                ], 403);
            }

            // إعادة توجيه إلى صفحة الترخيص مع رسالة
            return redirect()->route('license.verify')
                           ->with('error', 'يرجى إدخال مفتاح الترخيص للوصول إلى النظام');
        }

        // التحقق من صحة الترخيص
        if (!$currentLicense->is_active) {
            Session::forget('active_license');

            if ($request->expectsJson()) {
                return response()->json([
                    'error' => 'الترخيص معلق',
                    'message' => 'هذا الترخيص معلق. يرجى التواصل مع الإدارة',
                    'redirect' => route('license.verify')
                ], 403);
            }

            return redirect()->route('license.verify')
                           ->with('error', 'هذا الترخيص معلق. يرجى التواصل مع الإدارة');
        }

        if ($currentLicense->end_date < now()) {
            Session::forget('active_license');

            if ($request->expectsJson()) {
                return response()->json([
                    'error' => 'الترخيص منتهي',
                    'message' => 'انتهت صلاحية الترخيص. يرجى تجديد الترخيص',
                    'redirect' => route('license.verify')
                ], 403);
            }

            return redirect()->route('license.verify')
                           ->with('error', 'انتهت صلاحية الترخيص. يرجى تجديد الترخيص');
        }

        // إضافة معلومات الترخيص إلى الطلب
        $request->merge(['current_license' => $currentLicense]);

        // تحديث آخر فحص للترخيص كل ساعة
        $lastCheck = Session::get('license_last_check');
        if (!$lastCheck || now()->diffInHours($lastCheck) >= 1) {
            $currentLicense->update(['last_check' => now()]);
            Session::put('license_last_check', now());
        }

        return $next($request);
    }
}
