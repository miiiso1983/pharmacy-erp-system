<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class PerformanceMonitoringService
{
    private static array $timers = [];
    private static array $queryCount = [];
    private static array $memoryUsage = [];

    /**
     * بدء مراقبة العملية
     */
    public static function startTimer(string $operation): void
    {
        self::$timers[$operation] = [
            'start_time' => microtime(true),
            'start_memory' => memory_get_usage(true),
            'start_peak_memory' => memory_get_peak_usage(true),
            'query_count_start' => self::getQueryCount(),
        ];
    }

    /**
     * إنهاء مراقبة العملية
     */
    public static function endTimer(string $operation): array
    {
        if (!isset(self::$timers[$operation])) {
            return [];
        }

        $timer = self::$timers[$operation];
        $endTime = microtime(true);
        $endMemory = memory_get_usage(true);
        $endPeakMemory = memory_get_peak_usage(true);
        $queryCountEnd = self::getQueryCount();

        $metrics = [
            'operation' => $operation,
            'execution_time' => round($endTime - $timer['start_time'], 4),
            'memory_used' => $endMemory - $timer['start_memory'],
            'peak_memory_used' => $endPeakMemory - $timer['start_peak_memory'],
            'query_count' => $queryCountEnd - $timer['query_count_start'],
            'timestamp' => Carbon::now()->toISOString(),
        ];

        // تسجيل الأداء إذا كان بطيئاً
        if ($metrics['execution_time'] > 2) {
            LoggingService::logPerformance($operation, $metrics['execution_time'], $metrics);
        }

        // حفظ المقاييس في Cache للمراجعة
        self::storeMetrics($operation, $metrics);

        unset(self::$timers[$operation]);

        return $metrics;
    }

    /**
     * مراقبة استعلام قاعدة البيانات
     */
    public static function monitorQuery(string $sql, array $bindings = [], float $time = 0): void
    {
        $queryInfo = [
            'sql' => $sql,
            'bindings' => $bindings,
            'time' => $time,
            'timestamp' => Carbon::now()->toISOString(),
        ];

        // تسجيل الاستعلامات البطيئة
        if ($time > 1000) { // أكثر من ثانية واحدة
            Log::channel('performance')->warning('Slow Query Detected', $queryInfo);
        }

        // حفظ إحصائيات الاستعلامات
        self::updateQueryStats($queryInfo);
    }

    /**
     * مراقبة استخدام الذاكرة
     */
    public static function monitorMemory(string $checkpoint): array
    {
        $memoryInfo = [
            'checkpoint' => $checkpoint,
            'current_usage' => memory_get_usage(true),
            'peak_usage' => memory_get_peak_usage(true),
            'limit' => ini_get('memory_limit'),
            'timestamp' => Carbon::now()->toISOString(),
        ];

        self::$memoryUsage[] = $memoryInfo;

        // تحذير إذا كان استخدام الذاكرة مرتفع
        $memoryLimitBytes = self::convertToBytes(ini_get('memory_limit'));
        $usagePercentage = ($memoryInfo['current_usage'] / $memoryLimitBytes) * 100;

        if ($usagePercentage > 80) {
            Log::channel('performance')->warning('High Memory Usage', [
                'usage_percentage' => round($usagePercentage, 2),
                'memory_info' => $memoryInfo,
            ]);
        }

        return $memoryInfo;
    }

    /**
     * الحصول على تقرير الأداء
     */
    public static function getPerformanceReport(): array
    {
        $cacheKey = 'performance_report_' . date('Y-m-d-H');
        
        return Cache::remember($cacheKey, 3600, function () {
            return [
                'slow_queries' => self::getSlowQueries(),
                'memory_usage' => self::getMemoryReport(),
                'operation_metrics' => self::getOperationMetrics(),
                'system_health' => self::getSystemHealth(),
                'generated_at' => Carbon::now()->toISOString(),
            ];
        });
    }

    /**
     * فحص صحة النظام
     */
    public static function getSystemHealth(): array
    {
        $health = [
            'status' => 'healthy',
            'issues' => [],
            'metrics' => [],
        ];

        // فحص قاعدة البيانات
        try {
            $dbStart = microtime(true);
            DB::select('SELECT 1');
            $dbTime = (microtime(true) - $dbStart) * 1000;
            
            $health['metrics']['database_response_time'] = round($dbTime, 2);
            
            if ($dbTime > 100) {
                $health['issues'][] = 'Database response time is slow';
                $health['status'] = 'warning';
            }
        } catch (\Exception $e) {
            $health['issues'][] = 'Database connection failed';
            $health['status'] = 'critical';
        }

        // فحص مساحة القرص
        $diskSpace = disk_free_space(storage_path());
        $totalSpace = disk_total_space(storage_path());
        $usedPercentage = (($totalSpace - $diskSpace) / $totalSpace) * 100;
        
        $health['metrics']['disk_usage_percentage'] = round($usedPercentage, 2);
        
        if ($usedPercentage > 90) {
            $health['issues'][] = 'Disk space is critically low';
            $health['status'] = 'critical';
        } elseif ($usedPercentage > 80) {
            $health['issues'][] = 'Disk space is running low';
            if ($health['status'] === 'healthy') {
                $health['status'] = 'warning';
            }
        }

        // فحص استخدام الذاكرة
        $memoryUsage = memory_get_usage(true);
        $memoryLimit = self::convertToBytes(ini_get('memory_limit'));
        $memoryPercentage = ($memoryUsage / $memoryLimit) * 100;
        
        $health['metrics']['memory_usage_percentage'] = round($memoryPercentage, 2);
        
        if ($memoryPercentage > 90) {
            $health['issues'][] = 'Memory usage is critically high';
            $health['status'] = 'critical';
        } elseif ($memoryPercentage > 80) {
            $health['issues'][] = 'Memory usage is high';
            if ($health['status'] === 'healthy') {
                $health['status'] = 'warning';
            }
        }

        return $health;
    }

    /**
     * تنظيف البيانات القديمة
     */
    public static function cleanup(): void
    {
        // تنظيف المقاييس القديمة من Cache
        $keys = Cache::get('performance_metrics_keys', []);
        $cutoff = Carbon::now()->subDays(7);
        
        foreach ($keys as $key => $timestamp) {
            if (Carbon::parse($timestamp)->lt($cutoff)) {
                Cache::forget($key);
                unset($keys[$key]);
            }
        }
        
        Cache::put('performance_metrics_keys', $keys, 86400);
    }

    /**
     * الحصول على عدد الاستعلامات
     */
    private static function getQueryCount(): int
    {
        return count(DB::getQueryLog());
    }

    /**
     * حفظ المقاييس
     */
    private static function storeMetrics(string $operation, array $metrics): void
    {
        $key = 'performance_metrics_' . date('Y-m-d-H') . '_' . $operation;
        $existing = Cache::get($key, []);
        $existing[] = $metrics;
        
        Cache::put($key, $existing, 86400);
        
        // تحديث قائمة المفاتيح
        $keys = Cache::get('performance_metrics_keys', []);
        $keys[$key] = Carbon::now()->toISOString();
        Cache::put('performance_metrics_keys', $keys, 86400);
    }

    /**
     * تحديث إحصائيات الاستعلامات
     */
    private static function updateQueryStats(array $queryInfo): void
    {
        $key = 'query_stats_' . date('Y-m-d-H');
        $stats = Cache::get($key, ['total' => 0, 'slow' => 0, 'queries' => []]);
        
        $stats['total']++;
        if ($queryInfo['time'] > 1000) {
            $stats['slow']++;
            $stats['queries'][] = $queryInfo;
        }
        
        Cache::put($key, $stats, 3600);
    }

    /**
     * تحويل حجم الذاكرة إلى bytes
     */
    private static function convertToBytes(string $size): int
    {
        $unit = strtolower(substr($size, -1));
        $value = (int) $size;
        
        return match($unit) {
            'g' => $value * 1024 * 1024 * 1024,
            'm' => $value * 1024 * 1024,
            'k' => $value * 1024,
            default => $value
        };
    }

    /**
     * الحصول على الاستعلامات البطيئة
     */
    private static function getSlowQueries(): array
    {
        $key = 'query_stats_' . date('Y-m-d-H');
        $stats = Cache::get($key, ['queries' => []]);
        
        return array_slice($stats['queries'], -10); // آخر 10 استعلامات بطيئة
    }

    /**
     * الحصول على تقرير الذاكرة
     */
    private static function getMemoryReport(): array
    {
        return [
            'current_usage' => memory_get_usage(true),
            'peak_usage' => memory_get_peak_usage(true),
            'limit' => ini_get('memory_limit'),
            'checkpoints' => array_slice(self::$memoryUsage, -10),
        ];
    }

    /**
     * الحصول على مقاييس العمليات
     */
    private static function getOperationMetrics(): array
    {
        $keys = Cache::get('performance_metrics_keys', []);
        $metrics = [];
        
        foreach (array_keys($keys) as $key) {
            $data = Cache::get($key, []);
            $metrics = array_merge($metrics, $data);
        }
        
        return array_slice($metrics, -20); // آخر 20 عملية
    }
}
