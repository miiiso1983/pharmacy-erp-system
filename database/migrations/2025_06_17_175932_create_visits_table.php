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
        Schema::create('visits', function (Blueprint $table) {
            $table->id();
            $table->foreignId('medical_representative_id')->constrained()->onDelete('cascade');
            $table->foreignId('doctor_id')->constrained()->onDelete('cascade');
            $table->dateTime('visit_date');
            $table->dateTime('next_visit_date')->nullable();
            $table->enum('visit_type', ['planned', 'unplanned', 'follow_up'])->default('planned');
            $table->enum('status', ['scheduled', 'completed', 'cancelled', 'missed'])->default('scheduled');
            $table->text('visit_notes')->nullable();
            $table->text('doctor_feedback')->nullable();
            $table->string('marketing_support_type')->nullable(); // نوع الدعم التسويقي
            $table->text('marketing_support_details')->nullable();
            $table->decimal('latitude', 10, 8)->nullable();
            $table->decimal('longitude', 11, 8)->nullable();
            $table->string('location_address')->nullable();
            $table->json('attachments')->nullable(); // صور، توقيعات، إلخ
            $table->text('voice_notes')->nullable(); // مسار الملف الصوتي
            $table->integer('duration_minutes')->nullable(); // مدة الزيارة بالدقائق
            $table->boolean('order_created')->default(false); // هل تم إنشاء طلبية
            $table->foreignId('order_id')->nullable()->constrained()->onDelete('set null');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('visits');
    }
};
