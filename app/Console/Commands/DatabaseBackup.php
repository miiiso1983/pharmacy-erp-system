<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use ZipArchive;

class DatabaseBackup extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'backup:database {--manual : Manual backup trigger}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create database backup and send to admin via email';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        try {
            $this->info('Starting database backup...');

            // إنشاء النسخة الاحتياطية
            $backupResult = $this->createBackup();

            if (!$backupResult['success']) {
                $this->error('Backup failed: ' . $backupResult['error']);
                return 1;
            }

            // إرسال البريد الإلكتروني
            $emailResult = $this->sendBackupEmail($backupResult);

            if ($emailResult['success']) {
                $this->info('Backup created and email sent successfully!');
                Log::info('Database backup completed successfully', [
                    'file' => $backupResult['filename'],
                    'size' => $backupResult['size'],
                    'email_sent' => true
                ]);
            } else {
                $this->warn('Backup created but email failed: ' . $emailResult['error']);
                Log::warning('Database backup created but email failed', [
                    'file' => $backupResult['filename'],
                    'email_error' => $emailResult['error']
                ]);
            }

            return 0;

        } catch (\Exception $e) {
            $this->error('Backup process failed: ' . $e->getMessage());
            Log::error('Database backup failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return 1;
        }
    }

    /**
     * إنشاء النسخة الاحتياطية
     */
    private function createBackup()
    {
        try {
            $timestamp = Carbon::now()->format('Y-m-d_H-i-s');
            $filename = "pharmacy_backup_{$timestamp}.sql";
            $zipFilename = "pharmacy_backup_{$timestamp}.zip";

            // إنشاء مجلد النسخ الاحتياطية إذا لم يكن موجوداً
            $backupPath = storage_path('app/backups');
            if (!file_exists($backupPath)) {
                mkdir($backupPath, 0755, true);
            }

            $sqlFile = $backupPath . '/' . $filename;
            $zipFile = $backupPath . '/' . $zipFilename;

            // التحقق من نوع قاعدة البيانات
            $driver = config('database.default');
            $connection = config("database.connections.{$driver}");

            if ($driver === 'sqlite') {
                // نسخ ملف SQLite مباشرة
                $dbPath = $connection['database'];
                if (file_exists($dbPath)) {
                    copy($dbPath, $sqlFile);
                } else {
                    return [
                        'success' => false,
                        'error' => 'SQLite database file not found: ' . $dbPath
                    ];
                }
            } else {
                // إنشاء النسخة الاحتياطية باستخدام Laravel DB
                $this->createDatabaseBackupWithLaravel($sqlFile, $driver);
            }

            // التحقق من وجود الملف
            if (!file_exists($sqlFile)) {
                return [
                    'success' => false,
                    'error' => 'Backup file was not created'
                ];
            }

            // ضغط الملف
            $zip = new ZipArchive();
            if ($zip->open($zipFile, ZipArchive::CREATE) === TRUE) {
                $zip->addFile($sqlFile, $filename);
                $zip->close();

                // حذف ملف SQL الأصلي
                unlink($sqlFile);
            } else {
                return [
                    'success' => false,
                    'error' => 'Failed to create zip file'
                ];
            }

            $fileSize = filesize($zipFile);

            return [
                'success' => true,
                'filename' => $zipFilename,
                'filepath' => $zipFile,
                'size' => $this->formatBytes($fileSize),
                'timestamp' => $timestamp
            ];

        } catch (\Exception $e) {
            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * إرسال النسخة الاحتياطية بالبريد الإلكتروني
     */
    private function sendBackupEmail($backupResult)
    {
        try {
            $adminEmail = config('mail.admin_email', 'admin@pharmacy.com');

            // جمع إحصائيات النظام
            $stats = $this->getSystemStats();

            $data = [
                'backup' => $backupResult,
                'stats' => $stats,
                'timestamp' => Carbon::now(),
                'is_manual' => $this->option('manual')
            ];

            Mail::send('emails.backup', $data, function ($message) use ($adminEmail, $backupResult) {
                $message->to($adminEmail)
                        ->subject('نسخة احتياطية يومية - نظام إدارة الصيدلية - ' . Carbon::now()->format('Y-m-d'))
                        ->attach($backupResult['filepath']);
            });

            return ['success' => true];

        } catch (\Exception $e) {
            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * جمع إحصائيات النظام
     */
    private function getSystemStats()
    {
        try {
            return [
                'customers' => DB::table('customers')->count(),
                'suppliers' => DB::table('suppliers')->count(),
                'items' => DB::table('items')->count(),
                'orders' => DB::table('orders')->count(),
                'invoices' => DB::table('invoices')->count(),
                'collections' => DB::table('collections')->count(),
                'users' => DB::table('users')->count(),
                'warehouses' => DB::table('warehouses')->count(),
                'total_sales' => DB::table('invoices')->where('status', 'paid')->sum('total_amount'),
                'total_collections' => DB::table('collections')->where('status', 'completed')->sum('amount'),
                'pending_invoices' => DB::table('invoices')->where('status', 'pending')->count(),
                'low_stock_items' => DB::table('items')->where('current_stock', '<=', DB::raw('min_stock_level'))->count(),
            ];
        } catch (\Exception $e) {
            Log::error('Failed to get system stats', ['error' => $e->getMessage()]);
            return [];
        }
    }

    /**
     * تنسيق حجم الملف
     */
    private function formatBytes($size, $precision = 2)
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];

        for ($i = 0; $size > 1024 && $i < count($units) - 1; $i++) {
            $size /= 1024;
        }

        return round($size, $precision) . ' ' . $units[$i];
    }

    /**
     * إنشاء نسخة احتياطية باستخدام Laravel DB
     */
    private function createDatabaseBackupWithLaravel($sqlFile, $driver = 'mysql')
    {
        try {
            if ($driver === 'mysql') {
                $this->createMySQLBackup($sqlFile);
            } else {
                // للقواعد الأخرى، إنشاء نسخة بسيطة
                $this->createGenericBackup($sqlFile);
            }
        } catch (\Exception $e) {
            throw new \Exception('Failed to create database backup: ' . $e->getMessage());
        }
    }

    /**
     * إنشاء نسخة احتياطية لـ MySQL
     */
    private function createMySQLBackup($sqlFile)
    {
        $database = config('database.connections.mysql.database');

        // الحصول على جميع الجداول
        $tables = DB::select('SHOW TABLES');
        $tableKey = 'Tables_in_' . $database;

        $sql = "-- Database Backup Created: " . Carbon::now() . "\n";
        $sql .= "-- Database: {$database}\n\n";
        $sql .= "SET FOREIGN_KEY_CHECKS=0;\n\n";

        foreach ($tables as $table) {
            $tableName = $table->$tableKey;

            // إنشاء بنية الجدول
            $createTable = DB::select("SHOW CREATE TABLE `{$tableName}`");
            $sql .= "-- Table structure for table `{$tableName}`\n";
            $sql .= "DROP TABLE IF EXISTS `{$tableName}`;\n";
            $sql .= $createTable[0]->{'Create Table'} . ";\n\n";

            // إدراج البيانات
            $rows = DB::table($tableName)->get();
            if ($rows->count() > 0) {
                $sql .= "-- Dumping data for table `{$tableName}`\n";
                $sql .= "INSERT INTO `{$tableName}` VALUES\n";

                $values = [];
                foreach ($rows as $row) {
                    $rowData = [];
                    foreach ($row as $value) {
                        if (is_null($value)) {
                            $rowData[] = 'NULL';
                        } else {
                            $rowData[] = "'" . addslashes($value) . "'";
                        }
                    }
                    $values[] = '(' . implode(',', $rowData) . ')';
                }

                $sql .= implode(",\n", $values) . ";\n\n";
            }
        }

        $sql .= "SET FOREIGN_KEY_CHECKS=1;\n";

        // حفظ الملف
        file_put_contents($sqlFile, $sql);
    }

    /**
     * إنشاء نسخة احتياطية عامة
     */
    private function createGenericBackup($sqlFile)
    {
        $sql = "-- Database Backup Created: " . Carbon::now() . "\n";
        $sql .= "-- Connection: " . config('database.default') . "\n\n";

        // الحصول على أسماء الجداول
        $tables = $this->getAllTableNames();

        foreach ($tables as $tableName) {
            $sql .= "-- Table: {$tableName}\n";

            // الحصول على البيانات
            $rows = DB::table($tableName)->get();

            if ($rows->count() > 0) {
                $sql .= "-- Data for table {$tableName}\n";
                foreach ($rows as $row) {
                    $sql .= "-- " . json_encode($row) . "\n";
                }
            }

            $sql .= "\n";
        }

        // حفظ الملف
        file_put_contents($sqlFile, $sql);
    }

    /**
     * الحصول على أسماء جميع الجداول
     */
    private function getAllTableNames()
    {
        $driver = config('database.default');

        if ($driver === 'sqlite') {
            $tables = DB::select("SELECT name FROM sqlite_master WHERE type='table' AND name NOT LIKE 'sqlite_%'");
            return collect($tables)->pluck('name')->toArray();
        } elseif ($driver === 'mysql') {
            $database = config('database.connections.mysql.database');
            $tables = DB::select('SHOW TABLES');
            $tableKey = 'Tables_in_' . $database;
            return collect($tables)->pluck($tableKey)->toArray();
        }

        return [];
    }
}
