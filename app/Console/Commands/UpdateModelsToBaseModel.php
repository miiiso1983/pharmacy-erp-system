<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class UpdateModelsToBaseModel extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'models:update-to-base {--dry-run : عرض التغييرات بدون تطبيقها}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'تحديث جميع Models لتستخدم BaseModel بدلاً من Model';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🔄 بدء تحديث Models لاستخدام BaseModel...');
        $this->newLine();

        $modelsPath = app_path('Models');
        $modelFiles = File::glob($modelsPath . '/*.php');

        $excludedModels = [
            'BaseModel.php',
            'User.php', // تم تحديثه بالفعل
            'SystemLicense.php',
            'LicenseUsage.php',
            'MasterAdmin.php'
        ];

        $updatedCount = 0;
        $skippedCount = 0;

        foreach ($modelFiles as $file) {
            $fileName = basename($file);

            if (in_array($fileName, $excludedModels)) {
                $this->line("⏭️  تخطي: {$fileName} (مستثنى)");
                $skippedCount++;
                continue;
            }

            $content = File::get($file);

            // التحقق من أن الملف يحتوي على Model class
            if (!preg_match('/class\s+\w+\s+extends\s+Model/', $content)) {
                $this->line("⏭️  تخطي: {$fileName} (لا يحتوي على Model class)");
                $skippedCount++;
                continue;
            }

            // التحقق من أنه لا يستخدم BaseModel بالفعل
            if (strpos($content, 'extends BaseModel') !== false) {
                $this->line("✅ {$fileName} يستخدم BaseModel بالفعل");
                $skippedCount++;
                continue;
            }

            $originalContent = $content;

            // استبدال extends Model بـ extends BaseModel
            $content = preg_replace(
                '/class\s+(\w+)\s+extends\s+Model/',
                'class $1 extends BaseModel',
                $content
            );

            // إزالة use Illuminate\Database\Eloquent\Model;
            $content = preg_replace(
                '/use\s+Illuminate\\\\Database\\\\Eloquent\\\\Model;\s*\n/',
                '',
                $content
            );

            if ($content !== $originalContent) {
                if ($this->option('dry-run')) {
                    $this->warn("🔍 سيتم تحديث: {$fileName}");
                } else {
                    File::put($file, $content);
                    $this->info("✅ تم تحديث: {$fileName}");
                }
                $updatedCount++;
            } else {
                $this->line("⏭️  لا يحتاج تحديث: {$fileName}");
                $skippedCount++;
            }
        }

        $this->newLine();

        if ($this->option('dry-run')) {
            $this->info("🔍 وضع المعاينة - لم يتم تطبيق أي تغييرات");
        }

        $this->info("📊 ملخص العملية:");
        $this->table(
            ['النوع', 'العدد'],
            [
                ['Models المحدثة', $updatedCount],
                ['Models المتخطاة', $skippedCount],
                ['إجمالي الملفات', count($modelFiles)]
            ]
        );

        if ($updatedCount > 0 && !$this->option('dry-run')) {
            $this->warn('⚠️  تأكد من مراجعة التغييرات والتأكد من عمل النظام بشكل صحيح');
            $this->info('💡 يُنصح بتشغيل الاختبارات للتأكد من عدم وجود مشاكل');
        }

        return 0;
    }
}
