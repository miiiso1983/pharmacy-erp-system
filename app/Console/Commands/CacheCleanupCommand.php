<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\CacheService;
use App\Services\LoggingService;
use App\Services\PerformanceMonitoringService;

class CacheCleanupCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cache:cleanup {--force : Force cleanup without confirmation}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'تنظيف Cache المنتهي الصلاحية وتحسين الأداء';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $this->info('🧹 بدء عملية تنظيف Cache...');

        PerformanceMonitoringService::startTimer('cache_cleanup');

        try {
            // الحصول على إحصائيات قبل التنظيف
            $statsBefore = CacheService::getStats();
            $this->info("📊 إحصائيات قبل التنظيف:");
            $this->line("   - إجمالي المفاتيح: {$statsBefore['total_keys']}");
            $this->line("   - المفاتيح المنتهية: {$statsBefore['expired_keys']}");
            $this->line("   - المفاتيح الصالحة: {$statsBefore['valid_keys']}");

            if ($statsBefore['expired_keys'] === 0) {
                $this->info('✅ لا توجد مفاتيح منتهية الصلاحية للتنظيف');
                return self::SUCCESS;
            }

            // طلب التأكيد إذا لم يتم استخدام --force
            if (!$this->option('force')) {
                if (!$this->confirm("هل تريد تنظيف {$statsBefore['expired_keys']} مفتاح منتهي الصلاحية؟")) {
                    $this->info('❌ تم إلغاء عملية التنظيف');
                    return self::SUCCESS;
                }
            }

            // تنفيذ التنظيف
            $this->info('🔄 جاري تنظيف Cache...');
            $cleanupResults = CacheService::cleanup();

            // عرض النتائج
            $this->info('✅ تم تنظيف Cache بنجاح!');
            $this->line("   - تم تنظيف: {$cleanupResults['cleaned']} مفتاح");
            $this->line("   - أخطاء: {$cleanupResults['errors']} مفتاح");
            $this->line("   - متبقي: {$cleanupResults['remaining']} مفتاح");

            // الحصول على إحصائيات بعد التنظيف
            $statsAfter = CacheService::getStats();
            $this->info("📊 إحصائيات بعد التنظيف:");
            $this->line("   - إجمالي المفاتيح: {$statsAfter['total_keys']}");
            $this->line("   - المفاتيح الصالحة: {$statsAfter['valid_keys']}");

            // حساب المساحة المحررة
            $spaceSaved = $statsBefore['total_keys'] - $statsAfter['total_keys'];
            if ($spaceSaved > 0) {
                $this->info("💾 تم توفير مساحة: {$spaceSaved} مفتاح");
            }

            // تسجيل العملية
            LoggingService::logSystemEvent('Cache cleanup completed', [
                'cleaned' => $cleanupResults['cleaned'],
                'errors' => $cleanupResults['errors'],
                'remaining' => $cleanupResults['remaining'],
                'space_saved' => $spaceSaved,
            ]);

            // تنظيف إضافي للأداء
            $this->performAdditionalCleanup();

        } catch (\Exception $e) {
            $this->error('❌ حدث خطأ أثناء تنظيف Cache: ' . $e->getMessage());
            LoggingService::logSystemError($e, 'Cache cleanup failed');
            return self::FAILURE;
        } finally {
            $metrics = PerformanceMonitoringService::endTimer('cache_cleanup');
            $this->info("⏱️  وقت التنفيذ: {$metrics['execution_time']} ثانية");
        }

        return self::SUCCESS;
    }

    /**
     * تنظيف إضافي للأداء
     */
    private function performAdditionalCleanup(): void
    {
        $this->info('🔧 تنفيذ تنظيف إضافي...');

        try {
            // تنظيف logs القديمة
            $this->cleanupOldLogs();

            // تنظيف ملفات مؤقتة
            $this->cleanupTempFiles();

            // تحسين قاعدة البيانات
            $this->optimizeDatabase();

            $this->info('✅ تم التنظيف الإضافي بنجاح');

        } catch (\Exception $e) {
            $this->warn('⚠️  تحذير: فشل في بعض عمليات التنظيف الإضافي: ' . $e->getMessage());
        }
    }

    /**
     * تنظيف logs القديمة
     */
    private function cleanupOldLogs(): void
    {
        $logPath = storage_path('logs');
        $cutoffDate = now()->subDays(30);

        $files = glob($logPath . '/*.log');
        $cleaned = 0;

        foreach ($files as $file) {
            if (filemtime($file) < $cutoffDate->timestamp) {
                if (unlink($file)) {
                    $cleaned++;
                }
            }
        }

        if ($cleaned > 0) {
            $this->line("   - تم حذف {$cleaned} ملف log قديم");
        }
    }

    /**
     * تنظيف ملفات مؤقتة
     */
    private function cleanupTempFiles(): void
    {
        $tempPaths = [
            storage_path('framework/cache'),
            storage_path('framework/sessions'),
            storage_path('framework/views'),
        ];

        $cleaned = 0;

        foreach ($tempPaths as $path) {
            if (is_dir($path)) {
                $files = glob($path . '/*');
                foreach ($files as $file) {
                    if (is_file($file) && filemtime($file) < now()->subHours(24)->timestamp) {
                        if (unlink($file)) {
                            $cleaned++;
                        }
                    }
                }
            }
        }

        if ($cleaned > 0) {
            $this->line("   - تم حذف {$cleaned} ملف مؤقت");
        }
    }

    /**
     * تحسين قاعدة البيانات
     */
    private function optimizeDatabase(): void
    {
        try {
            // تحسين SQLite
            if (config('database.default') === 'sqlite') {
                \DB::statement('VACUUM');
                \DB::statement('ANALYZE');
                $this->line('   - تم تحسين قاعدة البيانات SQLite');
            }

            // تحسين MySQL
            if (config('database.default') === 'mysql') {
                $tables = \DB::select('SHOW TABLES');
                foreach ($tables as $table) {
                    $tableName = array_values((array) $table)[0];
                    \DB::statement("OPTIMIZE TABLE {$tableName}");
                }
                $this->line('   - تم تحسين جداول MySQL');
            }

        } catch (\Exception $e) {
            $this->warn('   - تحذير: فشل في تحسين قاعدة البيانات: ' . $e->getMessage());
        }
    }
}
