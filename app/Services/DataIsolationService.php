<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use App\Models\SystemLicense;
use App\Models\User;
use App\Models\Customer;
use App\Models\Item;
use App\Models\Warehouse;

class DataIsolationService
{
    /**
     * التحقق من عزل البيانات بين التراخيص
     */
    public function validateDataIsolation(): array
    {
        $results = [];
        
        // فحص جميع التراخيص
        $licenses = SystemLicense::all();
        
        foreach ($licenses as $license) {
            $results[$license->license_key] = $this->checkLicenseDataIsolation($license);
        }
        
        return $results;
    }

    /**
     * فحص عزل البيانات لترخيص محدد
     */
    private function checkLicenseDataIsolation($license): array
    {
        $isolation = [
            'license_id' => $license->id,
            'license_key' => $license->license_key,
            'client_name' => $license->client_name,
            'data_counts' => [],
            'isolation_status' => 'good',
            'issues' => []
        ];

        // فحص البيانات لكل جدول
        $tables = [
            'users' => User::class,
            'customers' => Customer::class,
            'items' => Item::class,
            'warehouses' => Warehouse::class
        ];

        foreach ($tables as $table => $model) {
            try {
                // عد البيانات المرتبطة بهذا الترخيص
                $count = $model::withoutLicenseScope()
                    ->where('license_id', $license->id)
                    ->count();
                
                $isolation['data_counts'][$table] = $count;

                // فحص وجود بيانات غير مرتبطة بأي ترخيص
                $orphanedCount = $model::withoutLicenseScope()
                    ->whereNull('license_id')
                    ->count();

                if ($orphanedCount > 0) {
                    $isolation['issues'][] = "يوجد {$orphanedCount} سجل غير مرتبط بترخيص في جدول {$table}";
                    $isolation['isolation_status'] = 'warning';
                }

                // فحص تسرب البيانات بين التراخيص
                $crossLicenseCount = $model::withoutLicenseScope()
                    ->where('license_id', '!=', $license->id)
                    ->whereNotNull('license_id')
                    ->count();

                if ($crossLicenseCount > 0) {
                    $isolation['data_counts'][$table . '_other_licenses'] = $crossLicenseCount;
                }

            } catch (\Exception $e) {
                $isolation['issues'][] = "خطأ في فحص جدول {$table}: " . $e->getMessage();
                $isolation['isolation_status'] = 'error';
            }
        }

        return $isolation;
    }

    /**
     * إصلاح مشاكل عزل البيانات
     */
    public function fixDataIsolationIssues($licenseId = null): array
    {
        $results = [];
        
        if ($licenseId) {
            $license = SystemLicense::find($licenseId);
            if ($license) {
                $results[$license->license_key] = $this->fixLicenseDataIssues($license);
            }
        } else {
            $licenses = SystemLicense::all();
            foreach ($licenses as $license) {
                $results[$license->license_key] = $this->fixLicenseDataIssues($license);
            }
        }
        
        return $results;
    }

    /**
     * إصلاح مشاكل عزل البيانات لترخيص محدد
     */
    private function fixLicenseDataIssues($license): array
    {
        $fixes = [
            'license_key' => $license->license_key,
            'fixed_issues' => [],
            'remaining_issues' => []
        ];

        $tables = [
            'users' => User::class,
            'customers' => Customer::class,
            'items' => Item::class,
            'warehouses' => Warehouse::class
        ];

        foreach ($tables as $table => $model) {
            try {
                // إصلاح البيانات غير المرتبطة بترخيص
                $orphanedRecords = $model::withoutLicenseScope()
                    ->whereNull('license_id')
                    ->get();

                if ($orphanedRecords->count() > 0) {
                    // ربط البيانات غير المرتبطة بالترخيص الأول (أو حذفها)
                    $firstLicense = SystemLicense::first();
                    if ($firstLicense) {
                        $model::withoutLicenseScope()
                            ->whereNull('license_id')
                            ->update(['license_id' => $firstLicense->id]);
                        
                        $fixes['fixed_issues'][] = "تم ربط {$orphanedRecords->count()} سجل بالترخيص الافتراضي في جدول {$table}";
                    }
                }

            } catch (\Exception $e) {
                $fixes['remaining_issues'][] = "فشل إصلاح جدول {$table}: " . $e->getMessage();
            }
        }

        return $fixes;
    }

    /**
     * إنشاء تقرير شامل عن عزل البيانات
     */
    public function generateIsolationReport(): array
    {
        $report = [
            'timestamp' => now()->toDateTimeString(),
            'total_licenses' => SystemLicense::count(),
            'licenses_data' => [],
            'system_health' => 'good',
            'recommendations' => []
        ];

        $licenses = SystemLicense::all();
        
        foreach ($licenses as $license) {
            $licenseData = $this->checkLicenseDataIsolation($license);
            $report['licenses_data'][] = $licenseData;
            
            if ($licenseData['isolation_status'] !== 'good') {
                $report['system_health'] = 'warning';
            }
        }

        // إضافة توصيات
        if ($report['system_health'] === 'warning') {
            $report['recommendations'][] = 'يُنصح بتشغيل أداة إصلاح عزل البيانات';
            $report['recommendations'][] = 'مراجعة البيانات غير المرتبطة بتراخيص';
        }

        $report['recommendations'][] = 'تفعيل مراقبة عزل البيانات التلقائية';
        $report['recommendations'][] = 'إجراء نسخة احتياطية قبل أي تعديلات';

        return $report;
    }

    /**
     * مراقبة عزل البيانات في الوقت الفعلي
     */
    public function monitorDataIsolation(): void
    {
        // تسجيل مستمع للاستعلامات
        DB::listen(function ($query) {
            $this->validateQueryIsolation($query);
        });
    }

    /**
     * التحقق من عزل الاستعلام
     */
    private function validateQueryIsolation($query): void
    {
        $sql = $query->sql;
        $bindings = $query->bindings;

        // قائمة الجداول التي تحتاج عزل
        $isolatedTables = ['users', 'customers', 'items', 'warehouses', 'orders', 'invoices'];
        
        foreach ($isolatedTables as $table) {
            if (strpos($sql, $table) !== false) {
                // التحقق من وجود فلتر license_id
                if (strpos($sql, 'license_id') === false && 
                    !strpos($sql, 'system_licenses') &&
                    auth()->check() && 
                    auth()->user()->user_role !== 'super_admin') {
                    
                    \Log::warning('Potential data isolation breach', [
                        'table' => $table,
                        'sql' => $sql,
                        'bindings' => $bindings,
                        'user_id' => auth()->id(),
                        'license_id' => session('current_license_id')
                    ]);
                }
            }
        }
    }

    /**
     * تنظيف البيانات المتسربة
     */
    public function cleanupLeakedData(): array
    {
        $results = [
            'cleaned_records' => 0,
            'affected_tables' => [],
            'errors' => []
        ];

        $tables = [
            'users' => User::class,
            'customers' => Customer::class,
            'items' => Item::class,
            'warehouses' => Warehouse::class
        ];

        foreach ($tables as $table => $model) {
            try {
                // حذف البيانات غير المرتبطة بأي ترخيص نشط
                $activelicenses = SystemLicense::where('is_active', true)->pluck('id');
                
                $deletedCount = $model::withoutLicenseScope()
                    ->whereNotIn('license_id', $activelicenses)
                    ->orWhereNull('license_id')
                    ->delete();

                if ($deletedCount > 0) {
                    $results['cleaned_records'] += $deletedCount;
                    $results['affected_tables'][] = $table;
                }

            } catch (\Exception $e) {
                $results['errors'][] = "خطأ في تنظيف جدول {$table}: " . $e->getMessage();
            }
        }

        return $results;
    }

    /**
     * اختبار عزل البيانات
     */
    public function testDataIsolation($licenseId1, $licenseId2): array
    {
        $test = [
            'license_1' => $licenseId1,
            'license_2' => $licenseId2,
            'isolation_test' => 'passed',
            'details' => []
        ];

        $tables = [
            'users' => User::class,
            'customers' => Customer::class,
            'items' => Item::class,
            'warehouses' => Warehouse::class
        ];

        foreach ($tables as $table => $model) {
            $count1 = $model::withoutLicenseScope()->where('license_id', $licenseId1)->count();
            $count2 = $model::withoutLicenseScope()->where('license_id', $licenseId2)->count();
            
            // محاولة الوصول للبيانات من ترخيص آخر
            session(['current_license_id' => $licenseId1]);
            $accessibleFromLicense1 = $model::count();
            
            session(['current_license_id' => $licenseId2]);
            $accessibleFromLicense2 = $model::count();
            
            $test['details'][$table] = [
                'license_1_data' => $count1,
                'license_2_data' => $count2,
                'accessible_from_license_1' => $accessibleFromLicense1,
                'accessible_from_license_2' => $accessibleFromLicense2,
                'isolation_working' => ($accessibleFromLicense1 == $count1 && $accessibleFromLicense2 == $count2)
            ];

            if (!$test['details'][$table]['isolation_working']) {
                $test['isolation_test'] = 'failed';
            }
        }

        return $test;
    }
}
