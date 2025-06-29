<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\DataIsolationService;

class MonitorDataIsolation extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'isolation:monitor
                            {--fix : إصلاح مشاكل العزل تلقائياً}
                            {--report : إنشاء تقرير مفصل}
                            {--clean : تنظيف البيانات المتسربة}
                            {--test=* : اختبار العزل بين تراخيص محددة}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'مراقبة وإدارة عزل البيانات بين التراخيص';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $isolationService = new DataIsolationService();

        $this->info('🔍 بدء مراقبة عزل البيانات...');
        $this->newLine();

        // إنشاء تقرير
        if ($this->option('report')) {
            $this->generateReport($isolationService);
        }

        // إصلاح المشاكل
        if ($this->option('fix')) {
            $this->fixIssues($isolationService);
        }

        // تنظيف البيانات
        if ($this->option('clean')) {
            $this->cleanData($isolationService);
        }

        // اختبار العزل
        if ($this->option('test')) {
            $this->testIsolation($isolationService);
        }

        // إذا لم يتم تحديد أي خيار، قم بالفحص العام
        if (!$this->option('report') && !$this->option('fix') &&
            !$this->option('clean') && !$this->option('test')) {
            $this->performGeneralCheck($isolationService);
        }

        $this->info('✅ تم الانتهاء من مراقبة عزل البيانات');
    }

    /**
     * إنشاء تقرير مفصل
     */
    private function generateReport($isolationService)
    {
        $this->info('📊 إنشاء تقرير عزل البيانات...');

        $report = $isolationService->generateIsolationReport();

        $this->table(
            ['المعلومة', 'القيمة'],
            [
                ['الوقت', $report['timestamp']],
                ['عدد التراخيص', $report['total_licenses']],
                ['حالة النظام', $report['system_health']]
            ]
        );

        if (!empty($report['licenses_data'])) {
            $this->info('📋 تفاصيل التراخيص:');

            $licenseData = [];
            foreach ($report['licenses_data'] as $license) {
                $licenseData[] = [
                    $license['license_key'],
                    $license['client_name'],
                    $license['isolation_status'],
                    count($license['issues'])
                ];
            }

            $this->table(
                ['مفتاح الترخيص', 'العميل', 'حالة العزل', 'عدد المشاكل'],
                $licenseData
            );
        }

        if (!empty($report['recommendations'])) {
            $this->warn('💡 التوصيات:');
            foreach ($report['recommendations'] as $recommendation) {
                $this->line("  • {$recommendation}");
            }
        }
    }

    /**
     * إصلاح مشاكل العزل
     */
    private function fixIssues($isolationService)
    {
        $this->warn('🔧 إصلاح مشاكل عزل البيانات...');

        if (!$this->confirm('هل أنت متأكد من المتابعة؟ قد يؤثر هذا على البيانات الموجودة.')) {
            $this->info('تم إلغاء العملية');
            return;
        }

        $results = $isolationService->fixDataIsolationIssues();

        foreach ($results as $licenseKey => $result) {
            $this->info("📝 نتائج إصلاح الترخيص: {$licenseKey}");

            if (!empty($result['fixed_issues'])) {
                $this->info('✅ المشاكل المُصلحة:');
                foreach ($result['fixed_issues'] as $fix) {
                    $this->line("  • {$fix}");
                }
            }

            if (!empty($result['remaining_issues'])) {
                $this->error('❌ المشاكل المتبقية:');
                foreach ($result['remaining_issues'] as $issue) {
                    $this->line("  • {$issue}");
                }
            }

            $this->newLine();
        }
    }

    /**
     * تنظيف البيانات المتسربة
     */
    private function cleanData($isolationService)
    {
        $this->warn('🧹 تنظيف البيانات المتسربة...');

        if (!$this->confirm('هل أنت متأكد؟ سيتم حذف البيانات غير المرتبطة بتراخيص نشطة نهائياً.')) {
            $this->info('تم إلغاء العملية');
            return;
        }

        $results = $isolationService->cleanupLeakedData();

        $this->info("🗑️ تم تنظيف {$results['cleaned_records']} سجل");

        if (!empty($results['affected_tables'])) {
            $this->info('📋 الجداول المتأثرة:');
            foreach ($results['affected_tables'] as $table) {
                $this->line("  • {$table}");
            }
        }

        if (!empty($results['errors'])) {
            $this->error('❌ أخطاء حدثت أثناء التنظيف:');
            foreach ($results['errors'] as $error) {
                $this->line("  • {$error}");
            }
        }
    }

    /**
     * اختبار العزل بين التراخيص
     */
    private function testIsolation($isolationService)
    {
        $testLicenses = $this->option('test');

        if (count($testLicenses) < 2) {
            $this->error('يجب تحديد ترخيصين على الأقل للاختبار');
            return;
        }

        $this->info('🧪 اختبار عزل البيانات...');

        $results = $isolationService->testDataIsolation($testLicenses[0], $testLicenses[1]);

        $this->table(
            ['المعلومة', 'القيمة'],
            [
                ['الترخيص الأول', $results['license_1']],
                ['الترخيص الثاني', $results['license_2']],
                ['نتيجة الاختبار', $results['isolation_test']]
            ]
        );

        foreach ($results['details'] as $table => $details) {
            $status = $details['isolation_working'] ? '✅ يعمل' : '❌ لا يعمل';
            $this->line("📋 {$table}: {$status}");
        }
    }

    /**
     * فحص عام لعزل البيانات
     */
    private function performGeneralCheck($isolationService)
    {
        $this->info('🔍 فحص عام لعزل البيانات...');

        $validation = $isolationService->validateDataIsolation();

        $overallStatus = 'good';
        $totalIssues = 0;

        foreach ($validation as $licenseKey => $result) {
            if ($result['isolation_status'] !== 'good') {
                $overallStatus = 'warning';
            }
            $totalIssues += count($result['issues']);
        }

        $statusIcon = $overallStatus === 'good' ? '✅' : '⚠️';
        $this->info("{$statusIcon} حالة عزل البيانات: {$overallStatus}");
        $this->info("📊 إجمالي المشاكل المكتشفة: {$totalIssues}");

        if ($totalIssues > 0) {
            $this->warn('💡 استخدم --fix لإصلاح المشاكل أو --report للحصول على تقرير مفصل');
        }
    }
}
