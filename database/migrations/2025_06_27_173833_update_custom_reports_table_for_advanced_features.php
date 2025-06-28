<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('custom_reports', function (Blueprint $table) {
            // تحقق من وجود الحقول قبل إضافتها
            if (!Schema::hasColumn('custom_reports', 'report_type')) {
                $table->string('report_type')->default('table')->after('description');
            }
            if (!Schema::hasColumn('custom_reports', 'data_sources')) {
                $table->json('data_sources')->nullable()->after('config');
            }
            if (!Schema::hasColumn('custom_reports', 'filters')) {
                $table->json('filters')->nullable()->after('data_sources');
            }
            if (!Schema::hasColumn('custom_reports', 'columns')) {
                $table->json('columns')->nullable()->after('filters');
            }
            if (!Schema::hasColumn('custom_reports', 'grouping')) {
                $table->json('grouping')->nullable()->after('columns');
            }
            if (!Schema::hasColumn('custom_reports', 'sorting')) {
                $table->json('sorting')->nullable()->after('grouping');
            }
            if (!Schema::hasColumn('custom_reports', 'calculations')) {
                $table->json('calculations')->nullable()->after('sorting');
            }
            if (!Schema::hasColumn('custom_reports', 'chart_config')) {
                $table->json('chart_config')->nullable()->after('calculations');
            }
            if (!Schema::hasColumn('custom_reports', 'layout_config')) {
                $table->json('layout_config')->nullable()->after('chart_config');
            }
            if (!Schema::hasColumn('custom_reports', 'created_by')) {
                $table->unsignedBigInteger('created_by')->nullable()->after('layout_config');
            }
            if (!Schema::hasColumn('custom_reports', 'is_scheduled')) {
                $table->boolean('is_scheduled')->default(false)->after('is_public');
            }
            if (!Schema::hasColumn('custom_reports', 'schedule_config')) {
                $table->json('schedule_config')->nullable()->after('is_scheduled');
            }
            if (!Schema::hasColumn('custom_reports', 'last_generated_at')) {
                $table->timestamp('last_generated_at')->nullable()->after('schedule_config');
            }
            if (!Schema::hasColumn('custom_reports', 'status')) {
                $table->string('status')->default('active')->after('last_generated_at');
            }
        });

        // إضافة الفهارس والمفاتيح الخارجية في استعلام منفصل
        Schema::table('custom_reports', function (Blueprint $table) {
            // إضافة فهارس (تحقق من عدم وجودها مسبقاً)
            if (!Schema::hasIndex('custom_reports', 'custom_reports_created_by_index')) {
                $table->index('created_by');
            }
            if (!Schema::hasIndex('custom_reports', 'custom_reports_report_type_index')) {
                $table->index('report_type');
            }
            if (!Schema::hasIndex('custom_reports', 'custom_reports_status_index')) {
                $table->index('status');
            }
            if (!Schema::hasIndex('custom_reports', 'custom_reports_is_scheduled_index')) {
                $table->index('is_scheduled');
            }

            // إضافة مفتاح خارجي (تجاهل الخطأ إذا كان موجوداً)
            try {
                if (Schema::hasColumn('custom_reports', 'created_by')) {
                    $table->foreign('created_by')->references('id')->on('users')->onDelete('set null');
                }
            } catch (\Exception $e) {
                // تجاهل الخطأ إذا كان المفتاح الخارجي موجوداً مسبقاً
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('custom_reports', function (Blueprint $table) {
            // إزالة المفتاح الخارجي أولاً
            $table->dropForeign(['created_by']);

            // إزالة الفهارس (تحقق من وجودها)
            if (Schema::hasIndex('custom_reports', 'custom_reports_created_by_index')) {
                $table->dropIndex(['created_by']);
            }
            if (Schema::hasIndex('custom_reports', 'custom_reports_report_type_index')) {
                $table->dropIndex(['report_type']);
            }
            if (Schema::hasIndex('custom_reports', 'custom_reports_status_index')) {
                $table->dropIndex(['status']);
            }
            if (Schema::hasIndex('custom_reports', 'custom_reports_is_scheduled_index')) {
                $table->dropIndex(['is_scheduled']);
            }

            // إزالة الحقول
            $table->dropColumn([
                'report_type',
                'data_sources',
                'filters',
                'columns',
                'grouping',
                'sorting',
                'calculations',
                'chart_config',
                'layout_config',
                'created_by',
                'is_scheduled',
                'schedule_config',
                'last_generated_at',
                'status'
            ]);
        });
    }
};
