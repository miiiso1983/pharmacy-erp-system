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
        Schema::table('warehouses', function (Blueprint $table) {
            // إضافة حقول الترخيص والإدارة
            $table->unsignedBigInteger('license_id')->nullable()->after('id');
            $table->string('location')->nullable()->after('address');
            $table->unsignedBigInteger('manager_id')->nullable()->after('manager');
            $table->boolean('is_active')->default(true)->after('status');
            $table->string('warehouse_type')->default('main')->after('type');
            $table->integer('capacity')->nullable()->after('total_items');
            $table->string('contact_phone')->nullable()->after('phone');
            $table->string('contact_email')->nullable()->after('contact_phone');
            $table->unsignedBigInteger('created_by')->nullable()->after('contact_email');

            // إضافة المفاتيح الخارجية
            $table->foreign('license_id')->references('id')->on('system_licenses')->onDelete('cascade');
            $table->foreign('manager_id')->references('id')->on('users')->onDelete('set null');
            $table->foreign('created_by')->references('id')->on('users')->onDelete('set null');

            // إضافة فهارس
            $table->index('license_id');
            $table->index('manager_id');
            $table->index('is_active');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('warehouses', function (Blueprint $table) {
            // حذف المفاتيح الخارجية
            $table->dropForeign(['license_id']);
            $table->dropForeign(['manager_id']);
            $table->dropForeign(['created_by']);

            // حذف الحقول
            $table->dropColumn([
                'license_id',
                'location',
                'manager_id',
                'is_active',
                'warehouse_type',
                'capacity',
                'contact_phone',
                'contact_email',
                'created_by'
            ]);
        });
    }
};
