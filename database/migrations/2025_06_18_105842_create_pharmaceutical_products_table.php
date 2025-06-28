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
        Schema::create('pharmaceutical_products', function (Blueprint $table) {
            $table->id();
            $table->string('product_code')->unique(); // رمز المنتج
            $table->string('product_name'); // اسم المنتج
            $table->string('product_name_en')->nullable(); // اسم المنتج بالإنجليزية
            $table->string('generic_name'); // الاسم العلمي
            $table->string('brand_name')->nullable(); // الاسم التجاري
            $table->foreignId('company_id')->constrained()->onDelete('cascade'); // الشركة المصنعة
            $table->string('registration_number')->unique(); // رقم التسجيل
            $table->date('registration_date'); // تاريخ التسجيل
            $table->date('expiry_date')->nullable(); // تاريخ انتهاء التسجيل
            $table->enum('product_type', ['medicine', 'medical_device', 'supplement', 'cosmetic', 'veterinary']); // نوع المنتج
            $table->enum('dosage_form', ['tablet', 'capsule', 'syrup', 'injection', 'cream', 'ointment', 'drops', 'inhaler', 'other']); // الشكل الصيدلاني
            $table->string('strength')->nullable(); // التركيز
            $table->string('pack_size')->nullable(); // حجم العبوة
            $table->enum('prescription_status', ['prescription', 'otc', 'controlled']); // حالة الوصفة
            $table->enum('status', ['registered', 'pending', 'rejected', 'expired', 'suspended'])->default('pending'); // حالة التسجيل
            $table->string('atc_code')->nullable(); // رمز ATC
            $table->text('composition')->nullable(); // التركيب
            $table->text('indications')->nullable(); // دواعي الاستعمال
            $table->text('contraindications')->nullable(); // موانع الاستعمال
            $table->text('side_effects')->nullable(); // الآثار الجانبية
            $table->text('dosage_instructions')->nullable(); // تعليمات الجرعة
            $table->string('storage_conditions')->nullable(); // شروط التخزين
            $table->decimal('price', 10, 2)->nullable(); // السعر
            $table->string('barcode')->nullable(); // الباركود
            $table->text('notes')->nullable(); // ملاحظات
            $table->json('documents')->nullable(); // المستندات المرفقة
            $table->timestamps();

            $table->index(['status', 'product_type']);
            $table->index('expiry_date');
            $table->index(['company_id', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pharmaceutical_products');
    }
};
