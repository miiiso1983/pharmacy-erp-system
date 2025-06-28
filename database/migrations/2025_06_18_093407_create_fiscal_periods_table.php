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
        Schema::create('fiscal_periods', function (Blueprint $table) {
            $table->id();
            $table->string('period_name'); // اسم الفترة
            $table->date('start_date'); // تاريخ البداية
            $table->date('end_date'); // تاريخ النهاية
            $table->enum('period_type', ['monthly', 'quarterly', 'yearly']); // نوع الفترة
            $table->boolean('is_closed')->default(false); // هل الفترة مغلقة
            $table->boolean('is_current')->default(false); // هل هي الفترة الحالية
            $table->text('notes')->nullable(); // ملاحظات
            $table->timestamps();

            $table->index(['start_date', 'end_date']);
            $table->index('is_current');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fiscal_periods');
    }
};
