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
        Schema::create('license_usage', function (Blueprint $table) {
            $table->id();
            $table->foreignId('license_id')->constrained('system_licenses')->onDelete('cascade');

            // إحصائيات الاستخدام الحالي
            $table->integer('current_users')->default(0);
            $table->integer('current_warehouses')->default(0);
            $table->integer('current_branches')->default(0);

            // إحصائيات الاستخدام الأقصى
            $table->integer('peak_users')->default(0);
            $table->integer('peak_warehouses')->default(0);
            $table->integer('peak_branches')->default(0);

            // تواريخ آخر تحديث
            $table->timestamp('last_user_check')->nullable();
            $table->timestamp('last_warehouse_check')->nullable();
            $table->timestamp('last_branch_check')->nullable();

            // معلومات إضافية
            $table->json('usage_history')->nullable(); // تاريخ الاستخدام الشهري
            $table->json('alerts_sent')->nullable(); // التنبيهات المرسلة

            $table->timestamps();

            // فهارس
            $table->index('license_id');
            $table->unique('license_id'); // ترخيص واحد لكل سجل استخدام
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('license_usage');
    }
};
