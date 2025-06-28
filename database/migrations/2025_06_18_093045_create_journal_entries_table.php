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
        Schema::create('journal_entries', function (Blueprint $table) {
            $table->id();
            $table->string('entry_number')->unique(); // رقم القيد
            $table->date('entry_date'); // تاريخ القيد
            $table->string('reference_type')->nullable(); // نوع المرجع (invoice, payment, etc.)
            $table->unsignedBigInteger('reference_id')->nullable(); // معرف المرجع
            $table->text('description'); // وصف القيد
            $table->decimal('total_amount', 15, 2); // إجمالي المبلغ
            $table->enum('status', ['draft', 'posted', 'cancelled'])->default('draft'); // حالة القيد
            $table->unsignedBigInteger('created_by'); // المستخدم الذي أنشأ القيد
            $table->unsignedBigInteger('posted_by')->nullable(); // المستخدم الذي ترحيل القيد
            $table->timestamp('posted_at')->nullable(); // تاريخ الترحيل
            $table->timestamps();

            $table->foreign('created_by')->references('id')->on('users');
            $table->foreign('posted_by')->references('id')->on('users');
            $table->index(['entry_date', 'status']);
            $table->index(['reference_type', 'reference_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('journal_entries');
    }
};
