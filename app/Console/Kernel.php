<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        // النسخ الاحتياطية اليومية في الساعة 7:00 مساءً
        $schedule->command('backup:database')
                 ->dailyAt('19:00')
                 ->timezone('Asia/Baghdad')
                 ->description('Daily database backup at 7:00 PM')
                 ->emailOutputOnFailure(config('mail.admin_email'));

        // تنظيف الملفات المؤقتة يومياً في الساعة 2:00 صباحاً
        $schedule->command('cache:cleanup')
                 ->dailyAt('02:00')
                 ->timezone('Asia/Baghdad')
                 ->description('Daily cache cleanup at 2:00 AM');

        // تقرير الأداء أسبوعياً يوم الأحد في الساعة 8:00 صباحاً
        $schedule->command('performance:report')
                 ->weeklyOn(0, '08:00')
                 ->timezone('Asia/Baghdad')
                 ->description('Weekly performance report on Sunday at 8:00 AM');

        // حذف النسخ الاحتياطية القديمة (أكثر من 30 يوم) شهرياً
        $schedule->call(function () {
            $backupPath = storage_path('app/backups');
            if (file_exists($backupPath)) {
                $files = glob($backupPath . '/*.zip');
                $thirtyDaysAgo = time() - (30 * 24 * 60 * 60);
                
                foreach ($files as $file) {
                    if (filemtime($file) < $thirtyDaysAgo) {
                        unlink($file);
                        \Log::info('Old backup deleted: ' . basename($file));
                    }
                }
            }
        })->monthly()
          ->description('Delete old backups (older than 30 days)');

        // تنظيف سجلات النظام أسبوعياً
        $schedule->call(function () {
            // حذف سجلات أقدم من 90 يوم
            $logPath = storage_path('logs');
            if (file_exists($logPath)) {
                $files = glob($logPath . '/laravel-*.log');
                $ninetyDaysAgo = time() - (90 * 24 * 60 * 60);
                
                foreach ($files as $file) {
                    if (filemtime($file) < $ninetyDaysAgo) {
                        unlink($file);
                        \Log::info('Old log file deleted: ' . basename($file));
                    }
                }
            }
        })->weekly()
          ->description('Clean old log files (older than 90 days)');
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
