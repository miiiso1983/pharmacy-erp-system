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
        Schema::create('targets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('medical_representative_id')->constrained()->onDelete('cascade');
            $table->foreignId('doctor_id')->nullable()->constrained()->onDelete('cascade'); // هدف لطبيب محدد
            $table->enum('target_type', ['weekly', 'monthly', 'quarterly'])->default('monthly');
            $table->enum('doctor_class', ['A', 'B', 'C'])->nullable(); // هدف لكلاس معين
            $table->integer('target_visits'); // عدد الزيارات المطلوبة
            $table->integer('achieved_visits')->default(0); // عدد الزيارات المحققة
            $table->date('start_date');
            $table->date('end_date');
            $table->enum('status', ['active', 'completed', 'cancelled'])->default('active');
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('targets');
    }
};
