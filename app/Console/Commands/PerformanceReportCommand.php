<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\PerformanceMonitoringService;
use App\Services\CacheService;
use App\Services\LoggingService;

class PerformanceReportCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'performance:report {--export : Export report to file}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'إنشاء تقرير شامل عن أداء النظام';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $this->info('📊 إنشاء تقرير الأداء...');

        try {
            // الحصول على تقرير الأداء
            $report = PerformanceMonitoringService::getPerformanceReport();
            
            // عرض التقرير
            $this->displayReport($report);
            
            // تصدير التقرير إذا طُلب ذلك
            if ($this->option('export')) {
                $this->exportReport($report);
            }
            
            // تسجيل إنشاء التقرير
            LoggingService::logSystemEvent('Performance report generated', [
                'report_size' => count($report),
                'exported' => $this->option('export'),
            ]);

        } catch (\Exception $e) {
            $this->error('❌ حدث خطأ أثناء إنشاء التقرير: ' . $e->getMessage());
            LoggingService::logSystemError($e, 'Performance report generation failed');
            return self::FAILURE;
        }

        return self::SUCCESS;
    }

    /**
     * عرض التقرير في الكونسول
     */
    private function displayReport(array $report): void
    {
        $this->info('🏥 تقرير أداء نظام ERP الصيدلية');
        $this->line('═══════════════════════════════════════════════');
        $this->line('تاريخ التقرير: ' . now()->format('Y-m-d H:i:s'));
        $this->newLine();

        // صحة النظام
        $this->displaySystemHealth($report['system_health']);
        
        // إحصائيات الذاكرة
        $this->displayMemoryReport($report['memory_usage']);
        
        // الاستعلامات البطيئة
        $this->displaySlowQueries($report['slow_queries']);
        
        // مقاييس العمليات
        $this->displayOperationMetrics($report['operation_metrics']);
        
        // إحصائيات Cache
        $this->displayCacheStats();
    }

    /**
     * عرض صحة النظام
     */
    private function displaySystemHealth(array $health): void
    {
        $this->info('🏥 صحة النظام');
        $this->line('─────────────────');
        
        $statusIcon = match($health['status']) {
            'healthy' => '✅',
            'warning' => '⚠️',
            'critical' => '❌',
            default => '❓'
        };
        
        $this->line("الحالة: {$statusIcon} {$health['status']}");
        
        if (!empty($health['issues'])) {
            $this->warn('المشاكل المكتشفة:');
            foreach ($health['issues'] as $issue) {
                $this->line("  • {$issue}");
            }
        }
        
        if (!empty($health['metrics'])) {
            $this->info('المقاييس:');
            foreach ($health['metrics'] as $metric => $value) {
                $this->line("  • {$metric}: {$value}");
            }
        }
        
        $this->newLine();
    }

    /**
     * عرض تقرير الذاكرة
     */
    private function displayMemoryReport(array $memory): void
    {
        $this->info('💾 استخدام الذاكرة');
        $this->line('─────────────────');
        
        $this->line('الاستخدام الحالي: ' . $this->formatBytes($memory['current_usage']));
        $this->line('أقصى استخدام: ' . $this->formatBytes($memory['peak_usage']));
        $this->line('الحد الأقصى: ' . $memory['limit']);
        
        if (!empty($memory['checkpoints'])) {
            $this->line('آخر نقاط المراقبة:');
            foreach (array_slice($memory['checkpoints'], -5) as $checkpoint) {
                $usage = $this->formatBytes($checkpoint['current_usage']);
                $this->line("  • {$checkpoint['checkpoint']}: {$usage}");
            }
        }
        
        $this->newLine();
    }

    /**
     * عرض الاستعلامات البطيئة
     */
    private function displaySlowQueries(array $queries): void
    {
        $this->info('🐌 الاستعلامات البطيئة');
        $this->line('─────────────────────');
        
        if (empty($queries)) {
            $this->line('✅ لا توجد استعلامات بطيئة');
        } else {
            foreach ($queries as $query) {
                $time = round($query['time'], 2);
                $sql = substr($query['sql'], 0, 80) . '...';
                $this->line("⏱️  {$time}ms: {$sql}");
            }
        }
        
        $this->newLine();
    }

    /**
     * عرض مقاييس العمليات
     */
    private function displayOperationMetrics(array $metrics): void
    {
        $this->info('⚡ مقاييس العمليات');
        $this->line('─────────────────');
        
        if (empty($metrics)) {
            $this->line('لا توجد مقاييس متاحة');
        } else {
            $slowOperations = array_filter($metrics, fn($m) => $m['execution_time'] > 1);
            
            if (!empty($slowOperations)) {
                $this->warn('العمليات البطيئة:');
                foreach (array_slice($slowOperations, -5) as $metric) {
                    $time = round($metric['execution_time'], 2);
                    $memory = $this->formatBytes($metric['memory_used']);
                    $this->line("  • {$metric['operation']}: {$time}s, {$memory}");
                }
            } else {
                $this->line('✅ جميع العمليات تعمل بكفاءة');
            }
        }
        
        $this->newLine();
    }

    /**
     * عرض إحصائيات Cache
     */
    private function displayCacheStats(): void
    {
        $this->info('🗄️  إحصائيات Cache');
        $this->line('─────────────────');
        
        $stats = CacheService::getStats();
        
        $this->line("إجمالي المفاتيح: {$stats['total_keys']}");
        $this->line("المفاتيح الصالحة: {$stats['valid_keys']}");
        $this->line("المفاتيح المنتهية: {$stats['expired_keys']}");
        
        if (isset($stats['memory_usage'])) {
            $this->line("استخدام الذاكرة: {$stats['memory_usage']}");
        }
        
        if (isset($stats['redis_keys'])) {
            $this->line("مفاتيح Redis: {$stats['redis_keys']}");
        }
        
        $this->newLine();
    }

    /**
     * تصدير التقرير إلى ملف
     */
    private function exportReport(array $report): void
    {
        $filename = 'performance_report_' . now()->format('Y-m-d_H-i-s') . '.json';
        $filepath = storage_path('reports/' . $filename);
        
        // إنشاء مجلد التقارير إذا لم يكن موجوداً
        if (!is_dir(dirname($filepath))) {
            mkdir(dirname($filepath), 0755, true);
        }
        
        // حفظ التقرير
        file_put_contents($filepath, json_encode($report, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
        
        $this->info("📄 تم تصدير التقرير إلى: {$filepath}");
    }

    /**
     * تنسيق حجم البايتات
     */
    private function formatBytes(int $bytes): string
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        
        for ($i = 0; $bytes > 1024 && $i < count($units) - 1; $i++) {
            $bytes /= 1024;
        }
        
        return round($bytes, 2) . ' ' . $units[$i];
    }
}
