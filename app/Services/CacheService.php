<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Redis;
use Carbon\Carbon;

class CacheService
{
    // مدد انتهاء الصلاحية (بالثواني)
    const CACHE_DURATIONS = [
        'short' => 300,      // 5 دقائق
        'medium' => 1800,    // 30 دقيقة
        'long' => 3600,      // ساعة واحدة
        'daily' => 86400,    // يوم واحد
        'weekly' => 604800,  // أسبوع واحد
    ];

    // بادئات المفاتيح
    const KEY_PREFIXES = [
        'user' => 'user:',
        'employee' => 'employee:',
        'item' => 'item:',
        'order' => 'order:',
        'invoice' => 'invoice:',
        'warehouse' => 'warehouse:',
        'stats' => 'stats:',
        'report' => 'report:',
        'settings' => 'settings:',
    ];

    /**
     * حفظ البيانات في Cache مع تتبع المفاتيح
     */
    public static function put(string $key, $value, int $duration = null): bool
    {
        $duration = $duration ?? self::CACHE_DURATIONS['medium'];
        
        // حفظ البيانات
        $result = Cache::put($key, $value, $duration);
        
        // تتبع المفتاح
        self::trackKey($key, $duration);
        
        return $result;
    }

    /**
     * الحصول على البيانات من Cache
     */
    public static function get(string $key, $default = null)
    {
        return Cache::get($key, $default);
    }

    /**
     * الحصول على البيانات أو تنفيذ callback
     */
    public static function remember(string $key, int $duration, callable $callback)
    {
        $duration = $duration ?? self::CACHE_DURATIONS['medium'];
        
        $result = Cache::remember($key, $duration, $callback);
        
        // تتبع المفتاح
        self::trackKey($key, $duration);
        
        return $result;
    }

    /**
     * حذف مفتاح من Cache
     */
    public static function forget(string $key): bool
    {
        self::untrackKey($key);
        return Cache::forget($key);
    }

    /**
     * حذف مجموعة من المفاتيح بناءً على البادئة
     */
    public static function forgetByPrefix(string $prefix): int
    {
        $keys = self::getKeysByPrefix($prefix);
        $count = 0;
        
        foreach ($keys as $key) {
            if (self::forget($key)) {
                $count++;
            }
        }
        
        return $count;
    }

    /**
     * حذف Cache للمستخدم
     */
    public static function forgetUserCache(int $userId): int
    {
        return self::forgetByPrefix(self::KEY_PREFIXES['user'] . $userId);
    }

    /**
     * حذف Cache للموظف
     */
    public static function forgetEmployeeCache(int $employeeId): int
    {
        return self::forgetByPrefix(self::KEY_PREFIXES['employee'] . $employeeId);
    }

    /**
     * حذف Cache للمنتج
     */
    public static function forgetItemCache(int $itemId): int
    {
        return self::forgetByPrefix(self::KEY_PREFIXES['item'] . $itemId);
    }

    /**
     * حذف Cache للإحصائيات
     */
    public static function forgetStatsCache(): int
    {
        return self::forgetByPrefix(self::KEY_PREFIXES['stats']);
    }

    /**
     * تنظيف Cache المنتهي الصلاحية
     */
    public static function cleanup(): array
    {
        $trackedKeys = Cache::get('cache_tracked_keys', []);
        $cleaned = 0;
        $errors = 0;
        
        foreach ($trackedKeys as $key => $expiry) {
            if (Carbon::now()->timestamp > $expiry) {
                try {
                    Cache::forget($key);
                    unset($trackedKeys[$key]);
                    $cleaned++;
                } catch (\Exception $e) {
                    $errors++;
                }
            }
        }
        
        // تحديث قائمة المفاتيح المتتبعة
        Cache::put('cache_tracked_keys', $trackedKeys, self::CACHE_DURATIONS['weekly']);
        
        return [
            'cleaned' => $cleaned,
            'errors' => $errors,
            'remaining' => count($trackedKeys),
        ];
    }

    /**
     * الحصول على إحصائيات Cache
     */
    public static function getStats(): array
    {
        $trackedKeys = Cache::get('cache_tracked_keys', []);
        $now = Carbon::now()->timestamp;
        
        $stats = [
            'total_keys' => count($trackedKeys),
            'expired_keys' => 0,
            'valid_keys' => 0,
            'memory_usage' => 0,
            'hit_rate' => 0,
        ];
        
        foreach ($trackedKeys as $key => $expiry) {
            if ($now > $expiry) {
                $stats['expired_keys']++;
            } else {
                $stats['valid_keys']++;
            }
        }
        
        // إحصائيات Redis إذا كان متاحاً
        try {
            if (config('cache.default') === 'redis') {
                $redis = Redis::connection();
                $info = $redis->info('memory');
                $stats['memory_usage'] = $info['used_memory_human'] ?? 'N/A';
                
                $keyspaceInfo = $redis->info('keyspace');
                if (isset($keyspaceInfo['db0'])) {
                    preg_match('/keys=(\d+)/', $keyspaceInfo['db0'], $matches);
                    $stats['redis_keys'] = $matches[1] ?? 0;
                }
            }
        } catch (\Exception $e) {
            // تجاهل أخطاء Redis
        }
        
        return $stats;
    }

    /**
     * إنشاء مفتاح Cache للمستخدم
     */
    public static function userKey(int $userId, string $suffix = ''): string
    {
        return self::KEY_PREFIXES['user'] . $userId . ($suffix ? ':' . $suffix : '');
    }

    /**
     * إنشاء مفتاح Cache للموظف
     */
    public static function employeeKey(int $employeeId, string $suffix = ''): string
    {
        return self::KEY_PREFIXES['employee'] . $employeeId . ($suffix ? ':' . $suffix : '');
    }

    /**
     * إنشاء مفتاح Cache للمنتج
     */
    public static function itemKey(int $itemId, string $suffix = ''): string
    {
        return self::KEY_PREFIXES['item'] . $itemId . ($suffix ? ':' . $suffix : '');
    }

    /**
     * إنشاء مفتاح Cache للطلب
     */
    public static function orderKey(int $orderId, string $suffix = ''): string
    {
        return self::KEY_PREFIXES['order'] . $orderId . ($suffix ? ':' . $suffix : '');
    }

    /**
     * إنشاء مفتاح Cache للفاتورة
     */
    public static function invoiceKey(int $invoiceId, string $suffix = ''): string
    {
        return self::KEY_PREFIXES['invoice'] . $invoiceId . ($suffix ? ':' . $suffix : '');
    }

    /**
     * إنشاء مفتاح Cache للمخزن
     */
    public static function warehouseKey(int $warehouseId, string $suffix = ''): string
    {
        return self::KEY_PREFIXES['warehouse'] . $warehouseId . ($suffix ? ':' . $suffix : '');
    }

    /**
     * إنشاء مفتاح Cache للإحصائيات
     */
    public static function statsKey(string $type, string $period = 'daily'): string
    {
        return self::KEY_PREFIXES['stats'] . $type . ':' . $period . ':' . date('Y-m-d');
    }

    /**
     * إنشاء مفتاح Cache للتقارير
     */
    public static function reportKey(string $type, array $params = []): string
    {
        $paramString = empty($params) ? '' : ':' . md5(serialize($params));
        return self::KEY_PREFIXES['report'] . $type . $paramString . ':' . date('Y-m-d');
    }

    /**
     * إنشاء مفتاح Cache للإعدادات
     */
    public static function settingsKey(string $key): string
    {
        return self::KEY_PREFIXES['settings'] . $key;
    }

    /**
     * تتبع مفتاح Cache
     */
    private static function trackKey(string $key, int $duration): void
    {
        $trackedKeys = Cache::get('cache_tracked_keys', []);
        $trackedKeys[$key] = Carbon::now()->addSeconds($duration)->timestamp;
        Cache::put('cache_tracked_keys', $trackedKeys, self::CACHE_DURATIONS['weekly']);
    }

    /**
     * إلغاء تتبع مفتاح Cache
     */
    private static function untrackKey(string $key): void
    {
        $trackedKeys = Cache::get('cache_tracked_keys', []);
        unset($trackedKeys[$key]);
        Cache::put('cache_tracked_keys', $trackedKeys, self::CACHE_DURATIONS['weekly']);
    }

    /**
     * الحصول على المفاتيح بناءً على البادئة
     */
    private static function getKeysByPrefix(string $prefix): array
    {
        $trackedKeys = Cache::get('cache_tracked_keys', []);
        $matchingKeys = [];
        
        foreach (array_keys($trackedKeys) as $key) {
            if (str_starts_with($key, $prefix)) {
                $matchingKeys[] = $key;
            }
        }
        
        return $matchingKeys;
    }

    /**
     * تحديث Cache بشكل ذكي
     */
    public static function smartUpdate(string $key, callable $callback, int $duration = null): mixed
    {
        $duration = $duration ?? self::CACHE_DURATIONS['medium'];
        
        // محاولة الحصول على البيانات من Cache
        $cached = Cache::get($key);
        
        if ($cached !== null) {
            // تحديث البيانات في الخلفية إذا كانت قريبة من الانتهاء
            $trackedKeys = Cache::get('cache_tracked_keys', []);
            if (isset($trackedKeys[$key])) {
                $expiry = $trackedKeys[$key];
                $timeLeft = $expiry - Carbon::now()->timestamp;
                
                // إذا بقي أقل من 10% من الوقت، حدث البيانات
                if ($timeLeft < ($duration * 0.1)) {
                    try {
                        $newData = $callback();
                        self::put($key, $newData, $duration);
                    } catch (\Exception $e) {
                        // في حالة الخطأ، استخدم البيانات المحفوظة
                        LoggingService::logSystemError($e, 'Cache smart update failed');
                    }
                }
            }
            
            return $cached;
        }
        
        // إذا لم توجد بيانات محفوظة، احصل عليها وحفظها
        $data = $callback();
        self::put($key, $data, $duration);
        
        return $data;
    }
}
