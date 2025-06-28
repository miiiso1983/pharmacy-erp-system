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
        Schema::create('import_permits', function (Blueprint $table) {
            $table->id();
            $table->string('permit_number')->unique(); // رقم إجازة الاستيراد
            $table->foreignId('company_id')->constrained()->onDelete('cascade'); // الشركة المستوردة
            $table->foreignId('product_id')->constrained('pharmaceutical_products')->onDelete('cascade'); // المنتج
            $table->string('supplier_company'); // الشركة المورّدة
            $table->string('supplier_country'); // بلد المورّد
            $table->date('application_date'); // تاريخ التقديم
            $table->date('issue_date')->nullable(); // تاريخ الإصدار
            $table->date('expiry_date')->nullable(); // تاريخ الانتهاء
            $table->integer('quantity'); // الكمية
            $table->string('unit'); // الوحدة
            $table->decimal('unit_price', 10, 2)->nullable(); // سعر الوحدة
            $table->decimal('total_value', 15, 2)->nullable(); // القيمة الإجمالية
            $table->string('currency', 3)->default('IQD'); // العملة
            $table->enum('status', ['pending', 'under_review', 'approved', 'rejected', 'expired', 'used', 'cancelled'])->default('pending'); // الحالة
            $table->string('batch_number')->nullable(); // رقم الدفعة
            $table->date('manufacturing_date')->nullable(); // تاريخ التصنيع
            $table->date('expiry_date_product')->nullable(); // تاريخ انتهاء المنتج
            $table->string('port_of_entry')->nullable(); // منفذ الدخول
            $table->date('expected_arrival_date')->nullable(); // تاريخ الوصول المتوقع
            $table->date('actual_arrival_date')->nullable(); // تاريخ الوصول الفعلي
            $table->string('customs_declaration_number')->nullable(); // رقم البيان الجمركي
            $table->enum('customs_status', ['pending', 'cleared', 'held', 'rejected'])->nullable(); // حالة الجمارك
            $table->decimal('customs_fees', 10, 2)->nullable(); // الرسوم الجمركية
            $table->decimal('permit_fees', 10, 2)->nullable(); // رسوم الإجازة
            $table->enum('payment_status', ['pending', 'paid', 'overdue'])->default('pending'); // حالة الدفع
            $table->text('rejection_reason')->nullable(); // سبب الرفض
            $table->text('notes')->nullable(); // ملاحظات
            $table->json('documents')->nullable(); // المستندات المرفقة
            $table->timestamps();

            $table->index(['status', 'company_id']);
            $table->index('expiry_date');
            $table->index('expected_arrival_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('import_permits');
    }
};
