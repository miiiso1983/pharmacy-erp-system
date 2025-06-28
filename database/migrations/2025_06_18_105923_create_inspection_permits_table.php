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
        Schema::create('inspection_permits', function (Blueprint $table) {
            $table->id();
            $table->string('permit_number')->unique(); // رقم الإجازة
            $table->foreignId('company_id')->constrained()->onDelete('cascade'); // الشركة
            $table->foreignId('product_id')->nullable()->constrained('pharmaceutical_products')->onDelete('cascade'); // المنتج (اختياري)
            $table->enum('permit_type', ['facility_inspection', 'product_inspection', 'gmp_inspection', 'import_inspection', 'export_inspection']); // نوع الإجازة
            $table->date('application_date'); // تاريخ التقديم
            $table->date('inspection_date')->nullable(); // تاريخ الفحص
            $table->date('issue_date')->nullable(); // تاريخ الإصدار
            $table->date('expiry_date')->nullable(); // تاريخ الانتهاء
            $table->enum('status', ['pending', 'scheduled', 'in_progress', 'completed', 'approved', 'rejected', 'expired'])->default('pending'); // الحالة
            $table->string('inspector_name')->nullable(); // اسم المفتش
            $table->text('inspection_notes')->nullable(); // ملاحظات الفحص
            $table->enum('result', ['passed', 'failed', 'conditional', 'pending'])->nullable(); // نتيجة الفحص
            $table->text('deficiencies')->nullable(); // أوجه القصور
            $table->text('corrective_actions')->nullable(); // الإجراءات التصحيحية
            $table->date('follow_up_date')->nullable(); // تاريخ المتابعة
            $table->decimal('fees', 10, 2)->nullable(); // الرسوم
            $table->enum('payment_status', ['pending', 'paid', 'overdue'])->default('pending'); // حالة الدفع
            $table->text('remarks')->nullable(); // ملاحظات
            $table->json('documents')->nullable(); // المستندات المرفقة
            $table->timestamps();

            $table->index(['status', 'permit_type']);
            $table->index('inspection_date');
            $table->index(['company_id', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inspection_permits');
    }
};
