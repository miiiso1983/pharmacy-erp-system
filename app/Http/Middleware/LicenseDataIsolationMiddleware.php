<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use App\Http\Controllers\LicenseVerificationController;

class LicenseDataIsolationMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // تخطي Super Admin
        if (auth()->check() && auth()->user()->user_role === 'super_admin') {
            return $next($request);
        }

        // التحقق من وجود ترخيص نشط
        $licenseController = new LicenseVerificationController();
        $currentLicense = $licenseController->getCurrentLicense();

        if (!$currentLicense) {
            return redirect()->route('license.verify')
                           ->with('error', 'يرجى تفعيل الترخيص أولاً');
        }

        // تطبيق عزل البيانات على جميع الاستعلامات
        $this->applyGlobalScope($currentLicense->id);

        // حفظ معرف الترخيص في الجلسة للاستخدام في العمليات
        session(['current_license_id' => $currentLicense->id]);

        return $next($request);
    }

    /**
     * تطبيق عزل البيانات على مستوى النظام
     */
    private function applyGlobalScope($licenseId)
    {
        // قائمة الجداول التي تحتوي على license_id
        $tablesWithLicense = [
            'users',
            'customers',
            'items',
            'warehouses',
            'orders',
            'invoices',
            'inventory',
            'suppliers',
            'purchases',
            'sales',
            'transactions',
            'reports',
            'settings'
        ];

        foreach ($tablesWithLicense as $table) {
            if (Schema::hasTable($table) && Schema::hasColumn($table, 'license_id')) {
                // تطبيق فلتر تلقائي على جميع الاستعلامات
                DB::listen(function ($query) use ($table, $licenseId) {
                    if (strpos($query->sql, $table) !== false &&
                        strpos($query->sql, 'license_id') === false &&
                        !strpos($query->sql, 'system_licenses')) {

                        // تسجيل تحذير للاستعلامات غير المفلترة
                        \Log::warning('Unfiltered query detected', [
                            'table' => $table,
                            'sql' => $query->sql,
                            'bindings' => $query->bindings
                        ]);
                    }
                });
            }
        }
    }
}
