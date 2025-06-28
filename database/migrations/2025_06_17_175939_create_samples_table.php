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
        Schema::create('samples', function (Blueprint $table) {
            $table->id();
            $table->foreignId('visit_id')->constrained()->onDelete('cascade');
            $table->foreignId('item_id')->nullable()->constrained()->onDelete('cascade'); // ربط بجدول العناصر
            $table->string('item_name'); // اسم العنصر/الدواء
            $table->integer('quantity_distributed'); // عدد العينات الموزعة
            $table->string('batch_number')->nullable(); // رقم الدفعة
            $table->date('expiry_date')->nullable(); // تاريخ الانتهاء
            $table->text('notes')->nullable();
            $table->string('sample_image')->nullable(); // صورة العينة
            $table->boolean('doctor_signature')->default(false); // توقيع الطبيب
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('samples');
    }
};
