<?php

use App\Http\Controllers\MasterAdmin\MasterDashboardController;
use App\Http\Controllers\MasterAdmin\MasterAuthController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Master Admin Routes
|--------------------------------------------------------------------------
|
| هذه المسارات مخصصة لنظام Master Admin المعزول تماماً عن المشروع الأساسي
| يتحكم في التراخيص وحدود المستخدمين لجميع العملاء
|
*/

// مسارات تسجيل الدخول للـ Master Admin (بدون middleware)
Route::prefix('master-admin')->name('master-admin.')->group(function () {
    
    // صفحة تسجيل الدخول
    Route::get('/login', [MasterAuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [MasterAuthController::class, 'login'])->name('login.submit');
    
    // تسجيل الخروج
    Route::post('/logout', [MasterAuthController::class, 'logout'])->name('logout');
    
    // صفحة اختبار النظام
    Route::get('/test', function () {
        return view('master-admin.test');
    })->name('test');
});

// مسارات محمية بـ middleware للـ Master Admin
Route::prefix('master-admin')->name('master-admin.')->middleware('master.admin')->group(function () {
    
    // لوحة التحكم الرئيسية
    Route::get('/dashboard', [MasterDashboardController::class, 'index'])->name('dashboard');
    
    // إدارة التراخيص
    Route::prefix('licenses')->name('licenses.')->group(function () {
        Route::get('/', [MasterDashboardController::class, 'licenses'])->name('index');
        Route::get('/create', [MasterDashboardController::class, 'createLicense'])->name('create');
        Route::post('/', [MasterDashboardController::class, 'storeLicense'])->name('store');
        Route::get('/{license}', [MasterDashboardController::class, 'showLicense'])->name('show');
        Route::put('/{license}/extend', [MasterDashboardController::class, 'extendLicense'])->name('extend');
        Route::put('/{license}/toggle', [MasterDashboardController::class, 'toggleLicense'])->name('toggle');
        Route::delete('/{license}', [MasterDashboardController::class, 'deleteLicense'])->name('delete');
    });
    
    // إدارة الاستخدام والإحصائيات
    Route::prefix('usage')->name('usage.')->group(function () {
        Route::get('/', [MasterDashboardController::class, 'usageOverview'])->name('overview');
        Route::get('/license/{license}', [MasterDashboardController::class, 'licenseUsage'])->name('license');
        Route::post('/update/{license}', [MasterDashboardController::class, 'updateUsage'])->name('update');
        Route::get('/alerts', [MasterDashboardController::class, 'usageAlerts'])->name('alerts');
    });
    
    // إدارة Master Admins
    Route::prefix('admins')->name('admins.')->group(function () {
        Route::get('/', [MasterDashboardController::class, 'masterAdmins'])->name('index');
        Route::get('/create', [MasterDashboardController::class, 'createMasterAdmin'])->name('create');
        Route::post('/', [MasterDashboardController::class, 'storeMasterAdmin'])->name('store');
        Route::get('/{admin}', [MasterDashboardController::class, 'showMasterAdmin'])->name('show');
        Route::put('/{admin}', [MasterDashboardController::class, 'updateMasterAdmin'])->name('update');
        Route::put('/{admin}/toggle', [MasterDashboardController::class, 'toggleMasterAdmin'])->name('toggle');
    });
    
    // التقارير والإحصائيات المتقدمة
    Route::prefix('reports')->name('reports.')->group(function () {
        Route::get('/', [MasterDashboardController::class, 'reports'])->name('index');
        Route::get('/revenue', [MasterDashboardController::class, 'revenueReport'])->name('revenue');
        Route::get('/usage-trends', [MasterDashboardController::class, 'usageTrends'])->name('usage-trends');
        Route::get('/client-analysis', [MasterDashboardController::class, 'clientAnalysis'])->name('client-analysis');
        Route::get('/export/{type}', [MasterDashboardController::class, 'exportReport'])->name('export');
    });
    
    // إعدادات النظام
    Route::prefix('settings')->name('settings.')->group(function () {
        Route::get('/', [MasterDashboardController::class, 'systemSettings'])->name('index');
        Route::put('/update', [MasterDashboardController::class, 'updateSettings'])->name('update');
        Route::get('/backup', [MasterDashboardController::class, 'createBackup'])->name('backup');
        Route::get('/maintenance', [MasterDashboardController::class, 'maintenanceMode'])->name('maintenance');
    });
    
    // API للتحقق من التراخيص (للاستخدام من المشروع الأساسي)
    Route::prefix('api')->name('api.')->group(function () {
        Route::get('/license/{key}/verify', [MasterDashboardController::class, 'verifyLicense'])->name('verify-license');
        Route::post('/license/{key}/usage', [MasterDashboardController::class, 'reportUsage'])->name('report-usage');
        Route::get('/license/{key}/limits', [MasterDashboardController::class, 'getLimits'])->name('get-limits');
    });
});

// مسار إعادة التوجيه الافتراضي
Route::get('/master-admin', function () {
    return redirect()->route('master-admin.dashboard');
});
