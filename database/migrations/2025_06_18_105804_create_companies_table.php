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
        Schema::create('companies', function (Blueprint $table) {
            $table->id();
            $table->string('company_code')->unique(); // رمز الشركة
            $table->string('company_name'); // اسم الشركة
            $table->string('company_name_en')->nullable(); // اسم الشركة بالإنجليزية
            $table->string('registration_number')->unique(); // رقم التسجيل
            $table->date('registration_date'); // تاريخ التسجيل
            $table->date('expiry_date')->nullable(); // تاريخ انتهاء التسجيل
            $table->enum('company_type', ['manufacturer', 'distributor', 'importer', 'exporter', 'wholesaler', 'retailer']); // نوع الشركة
            $table->enum('status', ['active', 'suspended', 'expired', 'cancelled'])->default('active'); // حالة التسجيل
            $table->string('country'); // البلد
            $table->string('city'); // المدينة
            $table->text('address'); // العنوان
            $table->string('phone')->nullable(); // الهاتف
            $table->string('email')->nullable(); // البريد الإلكتروني
            $table->string('website')->nullable(); // الموقع الإلكتروني
            $table->string('contact_person')->nullable(); // الشخص المسؤول
            $table->string('contact_phone')->nullable(); // هاتف الشخص المسؤول
            $table->string('license_number')->nullable(); // رقم الترخيص
            $table->date('license_issue_date')->nullable(); // تاريخ إصدار الترخيص
            $table->date('license_expiry_date')->nullable(); // تاريخ انتهاء الترخيص
            $table->enum('gmp_status', ['certified', 'not_certified', 'pending', 'expired'])->default('not_certified'); // حالة GMP
            $table->date('gmp_expiry_date')->nullable(); // تاريخ انتهاء GMP
            $table->text('notes')->nullable(); // ملاحظات
            $table->json('documents')->nullable(); // المستندات المرفقة
            $table->timestamps();

            $table->index(['status', 'company_type']);
            $table->index('expiry_date');
            $table->index('license_expiry_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('companies');
    }
};
