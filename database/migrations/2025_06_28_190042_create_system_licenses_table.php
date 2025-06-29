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
            $table->string('license_key')->unique();
            $table->string('license_type')->default('full'); // full, basic, premium
            $table->string('client_name');
            $table->string('client_email');
            $table->string('client_phone')->nullable();
            $table->text('client_address')->nullable();

            // إعدادات الترخيص
            $table->date('start_date');
            $table->date('end_date');
            $table->integer('max_users')->default(10);
            $table->integer('max_warehouses')->default(1);
            $table->boolean('is_active')->default(true);

            // المميزات المتاحة
            $table->json('features')->nullable(); // قائمة المميزات المفعلة
            $table->json('modules')->nullable(); // الوحدات المتاحة

            // معلومات إضافية
            $table->decimal('license_cost', 10, 2)->nullable();
            $table->string('payment_status')->default('pending'); // pending, paid, overdue
            $table->text('notes')->nullable();
            $table->foreignId('created_by')->constrained('users');
            $table->timestamp('last_check')->nullable();

            $table->timestamps();

            // فهارس
            $table->index(['is_active', 'end_date']);
            $table->index('license_type');
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
