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
        // قائمة الجداول التي تحتاج إضافة license_id
        $tables = [
            'orders',
            'order_items',
            'invoices',
            'collections',
            'returns',
            'suppliers',
            'employees',
            'departments',
            'attendances',
            'leaves',
            'payrolls',
            'medical_representatives',
            'doctors',
            'visits',
            'samples',
            'targets',
            'customer_transactions',
            'customer_payments',
            'accounts',
            'journal_entries',
            'journal_entry_details',
            'fiscal_periods',
            'companies',
            'pharmaceutical_products',
            'inspection_permits',
            'import_permits',
            'whatsapp_logs',
            'custom_reports'
        ];

        foreach ($tables as $table) {
            if (Schema::hasTable($table) && !Schema::hasColumn($table, 'license_id')) {
                Schema::table($table, function (Blueprint $table) {
                    $table->unsignedBigInteger('license_id')->nullable()->after('id');
                    $table->foreign('license_id')->references('id')->on('system_licenses')->onDelete('cascade');
                    $table->index('license_id');
                });
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $tables = [
            'orders',
            'order_items',
            'invoices',
            'collections',
            'returns',
            'suppliers',
            'employees',
            'departments',
            'attendances',
            'leaves',
            'payrolls',
            'medical_representatives',
            'doctors',
            'visits',
            'samples',
            'targets',
            'customer_transactions',
            'customer_payments',
            'accounts',
            'journal_entries',
            'journal_entry_details',
            'fiscal_periods',
            'companies',
            'pharmaceutical_products',
            'inspection_permits',
            'import_permits',
            'whatsapp_logs',
            'custom_reports'
        ];

        foreach ($tables as $table) {
            if (Schema::hasTable($table) && Schema::hasColumn($table, 'license_id')) {
                Schema::table($table, function (Blueprint $table) {
                    $table->dropForeign(['license_id']);
                    $table->dropIndex(['license_id']);
                    $table->dropColumn('license_id');
                });
            }
        }
    }
};
