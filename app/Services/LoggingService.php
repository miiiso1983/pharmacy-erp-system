<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;
use Carbon\Carbon;

class LoggingService
{
    /**
     * تسجيل نشاط المستخدم
     */
    public static function logUserActivity(string $action, array $data = [], string $level = 'info'): void
    {
        $user = Auth::user();
        
        $logData = [
            'action' => $action,
            'user_id' => $user?->id,
            'user_email' => $user?->email,
            'user_type' => $user?->user_type,
            'ip_address' => Request::ip(),
            'user_agent' => Request::userAgent(),
            'url' => Request::fullUrl(),
            'method' => Request::method(),
            'timestamp' => Carbon::now()->toISOString(),
            'data' => $data,
        ];

        Log::channel('user_activity')->log($level, $action, $logData);
    }

    /**
     * تسجيل أخطاء النظام
     */
    public static function logSystemError(\Exception $exception, string $context = '', array $data = []): void
    {
        $logData = [
            'context' => $context,
            'message' => $exception->getMessage(),
            'file' => $exception->getFile(),
            'line' => $exception->getLine(),
            'trace' => $exception->getTraceAsString(),
            'user_id' => Auth::id(),
            'ip_address' => Request::ip(),
            'url' => Request::fullUrl(),
            'method' => Request::method(),
            'timestamp' => Carbon::now()->toISOString(),
            'additional_data' => $data,
        ];

        Log::channel('system_errors')->error('System Error: ' . $context, $logData);
    }

    /**
     * تسجيل العمليات الأمنية
     */
    public static function logSecurityEvent(string $event, array $data = [], string $level = 'warning'): void
    {
        $logData = [
            'event' => $event,
            'user_id' => Auth::id(),
            'user_email' => Auth::user()?->email,
            'ip_address' => Request::ip(),
            'user_agent' => Request::userAgent(),
            'url' => Request::fullUrl(),
            'method' => Request::method(),
            'timestamp' => Carbon::now()->toISOString(),
            'data' => $data,
        ];

        Log::channel('security')->log($level, $event, $logData);
    }

    /**
     * تسجيل عمليات قاعدة البيانات
     */
    public static function logDatabaseOperation(string $operation, string $table, array $data = []): void
    {
        $logData = [
            'operation' => $operation,
            'table' => $table,
            'user_id' => Auth::id(),
            'ip_address' => Request::ip(),
            'timestamp' => Carbon::now()->toISOString(),
            'data' => $data,
        ];

        Log::channel('database')->info("Database {$operation} on {$table}", $logData);
    }

    /**
     * تسجيل الأداء
     */
    public static function logPerformance(string $operation, float $executionTime, array $data = []): void
    {
        $logData = [
            'operation' => $operation,
            'execution_time' => $executionTime,
            'memory_usage' => memory_get_usage(true),
            'peak_memory' => memory_get_peak_usage(true),
            'user_id' => Auth::id(),
            'url' => Request::fullUrl(),
            'method' => Request::method(),
            'timestamp' => Carbon::now()->toISOString(),
            'data' => $data,
        ];

        $level = $executionTime > 5 ? 'warning' : 'info'; // تحذير إذا كان الوقت أكثر من 5 ثواني

        Log::channel('performance')->log($level, "Performance: {$operation}", $logData);
    }

    /**
     * تسجيل عمليات تسجيل الدخول
     */
    public static function logLoginAttempt(string $email, bool $successful, string $reason = ''): void
    {
        $logData = [
            'email' => $email,
            'successful' => $successful,
            'reason' => $reason,
            'ip_address' => Request::ip(),
            'user_agent' => Request::userAgent(),
            'timestamp' => Carbon::now()->toISOString(),
        ];

        $level = $successful ? 'info' : 'warning';
        $message = $successful ? 'Successful login' : 'Failed login attempt';

        Log::channel('auth')->log($level, $message, $logData);
    }

    /**
     * تسجيل عمليات الملفات
     */
    public static function logFileOperation(string $operation, string $filename, array $data = []): void
    {
        $logData = [
            'operation' => $operation,
            'filename' => $filename,
            'user_id' => Auth::id(),
            'ip_address' => Request::ip(),
            'timestamp' => Carbon::now()->toISOString(),
            'data' => $data,
        ];

        Log::channel('files')->info("File {$operation}: {$filename}", $logData);
    }

    /**
     * تسجيل عمليات API
     */
    public static function logApiRequest(string $endpoint, array $data = []): void
    {
        $logData = [
            'endpoint' => $endpoint,
            'method' => Request::method(),
            'user_id' => Auth::id(),
            'ip_address' => Request::ip(),
            'user_agent' => Request::userAgent(),
            'timestamp' => Carbon::now()->toISOString(),
            'request_data' => $data,
        ];

        Log::channel('api')->info("API Request: {$endpoint}", $logData);
    }

    /**
     * تسجيل التغييرات في البيانات
     */
    public static function logDataChange(string $model, int $modelId, array $oldData, array $newData): void
    {
        $changes = [];
        
        foreach ($newData as $key => $value) {
            if (isset($oldData[$key]) && $oldData[$key] !== $value) {
                $changes[$key] = [
                    'old' => $oldData[$key],
                    'new' => $value,
                ];
            }
        }

        if (!empty($changes)) {
            $logData = [
                'model' => $model,
                'model_id' => $modelId,
                'changes' => $changes,
                'user_id' => Auth::id(),
                'ip_address' => Request::ip(),
                'timestamp' => Carbon::now()->toISOString(),
            ];

            Log::channel('data_changes')->info("Data changed in {$model} #{$modelId}", $logData);
        }
    }

    /**
     * تسجيل العمليات المالية
     */
    public static function logFinancialTransaction(string $type, float $amount, array $data = []): void
    {
        $logData = [
            'transaction_type' => $type,
            'amount' => $amount,
            'currency' => 'IQD',
            'user_id' => Auth::id(),
            'ip_address' => Request::ip(),
            'timestamp' => Carbon::now()->toISOString(),
            'data' => $data,
        ];

        Log::channel('financial')->info("Financial Transaction: {$type}", $logData);
    }

    /**
     * تسجيل أحداث النظام
     */
    public static function logSystemEvent(string $event, array $data = [], string $level = 'info'): void
    {
        $logData = [
            'event' => $event,
            'timestamp' => Carbon::now()->toISOString(),
            'memory_usage' => memory_get_usage(true),
            'data' => $data,
        ];

        Log::channel('system')->log($level, "System Event: {$event}", $logData);
    }

    /**
     * تسجيل عمليات النسخ الاحتياطي
     */
    public static function logBackupOperation(string $operation, string $status, array $data = []): void
    {
        $logData = [
            'operation' => $operation,
            'status' => $status,
            'timestamp' => Carbon::now()->toISOString(),
            'data' => $data,
        ];

        $level = $status === 'failed' ? 'error' : 'info';

        Log::channel('backup')->log($level, "Backup {$operation}: {$status}", $logData);
    }
}
