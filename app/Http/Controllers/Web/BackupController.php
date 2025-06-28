<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use ZipArchive;

class BackupController extends Controller
{
    /**
     * عرض صفحة النسخ الاحتياطية
     */
    public function index()
    {
        try {
            // جلب قائمة النسخ الاحتياطية الموجودة
            $backups = $this->getBackupsList();

            // إحصائيات النظام
            $stats = $this->getSystemStats();

            return view('backup.index', compact('backups', 'stats'));

        } catch (\Exception $e) {
            Log::error('Backup page error', ['error' => $e->getMessage()]);
            return back()->withErrors(['error' => 'حدث خطأ أثناء تحميل الصفحة: ' . $e->getMessage()]);
        }
    }

    /**
     * إنشاء نسخة احتياطية يدوية
     */
    public function create(Request $request)
    {
        try {
            // تشغيل أمر النسخ الاحتياطي مع العلامة اليدوية
            Artisan::call('backup:database', ['--manual' => true]);

            $output = Artisan::output();

            return response()->json([
                'success' => true,
                'message' => 'تم إنشاء النسخة الاحتياطية بنجاح',
                'output' => $output
            ]);

        } catch (\Exception $e) {
            Log::error('Manual backup failed', ['error' => $e->getMessage()]);

            return response()->json([
                'success' => false,
                'message' => 'فشل في إنشاء النسخة الاحتياطية: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * تحميل نسخة احتياطية
     */
    public function download($filename)
    {
        try {
            $backupPath = storage_path('app/backups/' . $filename);

            if (!file_exists($backupPath)) {
                return back()->withErrors(['error' => 'الملف غير موجود']);
            }

            return response()->download($backupPath);

        } catch (\Exception $e) {
            Log::error('Backup download failed', [
                'filename' => $filename,
                'error' => $e->getMessage()
            ]);

            return back()->withErrors(['error' => 'فشل في تحميل الملف: ' . $e->getMessage()]);
        }
    }

    /**
     * حذف نسخة احتياطية
     */
    public function delete($filename)
    {
        try {
            $backupPath = storage_path('app/backups/' . $filename);

            if (!file_exists($backupPath)) {
                return response()->json([
                    'success' => false,
                    'message' => 'الملف غير موجود'
                ], 404);
            }

            unlink($backupPath);

            return response()->json([
                'success' => true,
                'message' => 'تم حذف النسخة الاحتياطية بنجاح'
            ]);

        } catch (\Exception $e) {
            Log::error('Backup deletion failed', [
                'filename' => $filename,
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'فشل في حذف الملف: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * استعادة نسخة احتياطية
     */
    public function restore($filename)
    {
        try {
            $backupPath = storage_path('app/backups/' . $filename);

            if (!file_exists($backupPath)) {
                return response()->json([
                    'success' => false,
                    'message' => 'الملف غير موجود'
                ], 404);
            }

            // التحقق من نوع الملف
            $fileExtension = pathinfo($filename, PATHINFO_EXTENSION);

            if ($fileExtension === 'zip') {
                $result = $this->restoreFromZip($backupPath);
            } elseif ($fileExtension === 'sql') {
                $result = $this->restoreFromSql($backupPath);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'نوع الملف غير مدعوم'
                ], 400);
            }

            if ($result['success']) {
                Log::info('Database restored successfully', [
                    'filename' => $filename,
                    'restored_by' => auth()->id()
                ]);

                return response()->json([
                    'success' => true,
                    'message' => 'تم استعادة النسخة الاحتياطية بنجاح'
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => $result['error']
                ], 500);
            }

        } catch (\Exception $e) {
            Log::error('Database restore failed', [
                'filename' => $filename,
                'error' => $e->getMessage(),
                'user_id' => auth()->id()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'فشل في استعادة النسخة الاحتياطية: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * رفع وتثبيت نسخة احتياطية جديدة
     */
    public function upload(Request $request)
    {
        try {
            $request->validate([
                'backup_file' => 'required|file|mimes:zip,sql|max:102400' // 100MB max
            ], [
                'backup_file.required' => 'يرجى اختيار ملف النسخة الاحتياطية',
                'backup_file.file' => 'الملف المرفوع غير صحيح',
                'backup_file.mimes' => 'يجب أن يكون الملف من نوع ZIP أو SQL',
                'backup_file.max' => 'حجم الملف يجب أن يكون أقل من 100 ميجابايت'
            ]);

            $file = $request->file('backup_file');
            $timestamp = Carbon::now()->format('Y-m-d_H-i-s');
            $originalName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
            $extension = $file->getClientOriginalExtension();
            $filename = "uploaded_{$originalName}_{$timestamp}.{$extension}";

            // حفظ الملف
            $backupPath = storage_path('app/backups');
            if (!file_exists($backupPath)) {
                mkdir($backupPath, 0755, true);
            }

            $file->move($backupPath, $filename);

            return response()->json([
                'success' => true,
                'message' => 'تم رفع الملف بنجاح',
                'filename' => $filename
            ]);

        } catch (\Exception $e) {
            Log::error('Backup upload failed', [
                'error' => $e->getMessage(),
                'user_id' => auth()->id()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'فشل في رفع الملف: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * جلب قائمة النسخ الاحتياطية
     */
    private function getBackupsList()
    {
        $backupPath = storage_path('app/backups');
        $backups = [];

        if (!file_exists($backupPath)) {
            return $backups;
        }

        $files = glob($backupPath . '/*.zip');

        foreach ($files as $file) {
            $filename = basename($file);
            $size = filesize($file);
            $created = filemtime($file);

            $backups[] = [
                'filename' => $filename,
                'size' => $this->formatBytes($size),
                'size_bytes' => $size,
                'created_at' => Carbon::createFromTimestamp($created),
                'age' => Carbon::createFromTimestamp($created)->diffForHumans(),
            ];
        }

        // ترتيب حسب تاريخ الإنشاء (الأحدث أولاً)
        usort($backups, function($a, $b) {
            return $b['created_at']->timestamp - $a['created_at']->timestamp;
        });

        return $backups;
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
                'database_size' => $this->getDatabaseSize(),
                'last_backup' => $this->getLastBackupInfo(),
            ];
        } catch (\Exception $e) {
            Log::error('Failed to get system stats', ['error' => $e->getMessage()]);
            return [];
        }
    }

    /**
     * الحصول على حجم قاعدة البيانات
     */
    private function getDatabaseSize()
    {
        try {
            $database = config('database.connections.mysql.database');
            $result = DB::select("
                SELECT
                    ROUND(SUM(data_length + index_length) / 1024 / 1024, 2) AS size_mb
                FROM information_schema.tables
                WHERE table_schema = ?
            ", [$database]);

            return $result[0]->size_mb . ' MB';
        } catch (\Exception $e) {
            return 'غير متاح';
        }
    }

    /**
     * معلومات آخر نسخة احتياطية
     */
    private function getLastBackupInfo()
    {
        $backups = $this->getBackupsList();

        if (empty($backups)) {
            return null;
        }

        return $backups[0];
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
     * استعادة من ملف ZIP
     */
    private function restoreFromZip($zipPath)
    {
        try {
            $extractPath = storage_path('app/temp_restore');

            // إنشاء مجلد مؤقت
            if (!file_exists($extractPath)) {
                mkdir($extractPath, 0755, true);
            }

            // استخراج الملف
            $zip = new ZipArchive;
            if ($zip->open($zipPath) === TRUE) {
                $zip->extractTo($extractPath);
                $zip->close();
            } else {
                return ['success' => false, 'error' => 'فشل في استخراج ملف ZIP'];
            }

            // البحث عن ملف SQL
            $sqlFiles = glob($extractPath . '/*.sql');
            if (empty($sqlFiles)) {
                return ['success' => false, 'error' => 'لم يتم العثور على ملف SQL في الأرشيف'];
            }

            $sqlFile = $sqlFiles[0];
            $result = $this->restoreFromSql($sqlFile);

            // تنظيف الملفات المؤقتة
            $this->cleanupTempFiles($extractPath);

            return $result;

        } catch (\Exception $e) {
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }

    /**
     * استعادة من ملف SQL
     */
    private function restoreFromSql($sqlPath)
    {
        try {
            $driver = config('database.default');

            if ($driver === 'sqlite') {
                return $this->restoreSQLiteDatabase($sqlPath);
            } elseif ($driver === 'mysql') {
                return $this->restoreMySQLDatabase($sqlPath);
            } else {
                return ['success' => false, 'error' => 'نوع قاعدة البيانات غير مدعوم'];
            }

        } catch (\Exception $e) {
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }

    /**
     * استعادة قاعدة بيانات SQLite
     */
    private function restoreSQLiteDatabase($sqlPath)
    {
        try {
            $connection = config('database.connections.sqlite');
            $dbPath = $connection['database'];

            // إنشاء نسخة احتياطية من قاعدة البيانات الحالية
            $backupDbPath = $dbPath . '.backup_' . time();
            if (file_exists($dbPath)) {
                copy($dbPath, $backupDbPath);
            }

            // استبدال قاعدة البيانات
            if (pathinfo($sqlPath, PATHINFO_EXTENSION) === 'sql') {
                // إذا كان ملف SQL، نحتاج لتنفيذ الاستعلامات
                return ['success' => false, 'error' => 'استعادة ملفات SQL لـ SQLite غير مدعومة حالياً. يرجى استخدام ملف قاعدة البيانات مباشرة.'];
            } else {
                // إذا كان ملف قاعدة بيانات SQLite
                copy($sqlPath, $dbPath);
            }

            return ['success' => true];

        } catch (\Exception $e) {
            // استعادة النسخة الاحتياطية في حالة الفشل
            if (isset($backupDbPath) && file_exists($backupDbPath)) {
                copy($backupDbPath, $dbPath);
                unlink($backupDbPath);
            }

            return ['success' => false, 'error' => $e->getMessage()];
        }
    }

    /**
     * استعادة قاعدة بيانات MySQL
     */
    private function restoreMySQLDatabase($sqlPath)
    {
        try {
            $sql = file_get_contents($sqlPath);

            if (empty($sql)) {
                return ['success' => false, 'error' => 'ملف SQL فارغ'];
            }

            // تقسيم الاستعلامات
            $statements = $this->splitSqlStatements($sql);

            DB::beginTransaction();

            foreach ($statements as $statement) {
                $statement = trim($statement);
                if (!empty($statement) && !str_starts_with($statement, '--')) {
                    DB::unprepared($statement);
                }
            }

            DB::commit();

            return ['success' => true];

        } catch (\Exception $e) {
            DB::rollback();
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }

    /**
     * تقسيم استعلامات SQL
     */
    private function splitSqlStatements($sql)
    {
        // إزالة التعليقات
        $sql = preg_replace('/--.*$/m', '', $sql);
        $sql = preg_replace('/\/\*.*?\*\//s', '', $sql);

        // تقسيم حسب الفاصلة المنقوطة
        $statements = explode(';', $sql);

        return array_filter($statements, function($statement) {
            return !empty(trim($statement));
        });
    }

    /**
     * تنظيف الملفات المؤقتة
     */
    private function cleanupTempFiles($path)
    {
        if (file_exists($path)) {
            $files = glob($path . '/*');
            foreach ($files as $file) {
                if (is_file($file)) {
                    unlink($file);
                }
            }
            rmdir($path);
        }
    }
}
