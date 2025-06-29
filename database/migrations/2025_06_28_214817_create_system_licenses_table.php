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
        Schema::create('system_licenses', function (Blueprint $table) {
            $table->id();
            $table->string('license_key')->unique(); // مفتاح الترخيص الفريد
            $table->string('license_type')->default('basic'); // basic, full, premium

            // معلومات العميل
            $table->string('client_name');
            $table->string('client_email');
            $table->string('client_phone')->nullable();
            $table->text('client_address')->nullable();
            $table->string('client_company')->nullable();

            // تواريخ الترخيص
            $table->date('start_date');
            $table->date('end_date');
            $table->boolean('is_active')->default(true);

            // حدود الاستخدام
            $table->integer('max_users')->default(10); // عدد المستخدمين المسموح
            $table->integer('max_warehouses')->default(1); // عدد المخازن المسموح
            $table->integer('max_branches')->default(1); // عدد الفروع المسموح

            // المميزات والوحدات المتاحة
            $table->json('features')->nullable(); // قائمة المميزات المتاحة
            $table->json('modules')->nullable(); // قائمة الوحدات المتاحة

            // معلومات مالية
            $table->decimal('license_cost', 10, 2)->nullable();
            $table->enum('payment_status', ['pending', 'paid', 'overdue', 'cancelled'])->default('pending');
            $table->date('payment_due_date')->nullable();

            // معلومات إضافية
            $table->text('notes')->nullable();
            $table->foreignId('created_by')->constrained('master_admins'); // من أنشأ الترخيص
            $table->timestamp('last_check')->nullable(); // آخر فحص للترخيص

            $table->timestamps();

            // فهارس
            $table->index(['license_key', 'is_active']);
            $table->index('end_date');
            $table->index('client_email');
            $table->index(['is_active', 'end_date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('system_licenses');
    }
};
