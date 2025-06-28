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
        Schema::create('whatsapp_logs', function (Blueprint $table) {
            $table->id();
            $table->string('model_type'); // نوع النموذج (Collection, Invoice, etc.)
            $table->unsignedBigInteger('model_id'); // معرف النموذج
            $table->string('phone_number'); // رقم الهاتف المرسل إليه
            $table->enum('message_type', ['text', 'document', 'template', 'image']); // نوع الرسالة
            $table->string('message_id')->nullable(); // معرف الرسالة من واتساب
            $table->enum('status', ['sent', 'delivered', 'read', 'failed'])->default('sent'); // حالة الرسالة
            $table->json('response_data')->nullable(); // بيانات الاستجابة من واتساب
            $table->text('message_content')->nullable(); // محتوى الرسالة
            $table->string('document_url')->nullable(); // رابط المستند إن وجد
            $table->string('error_message')->nullable(); // رسالة الخطأ إن وجدت
            $table->timestamp('sent_at')->nullable(); // وقت الإرسال
            $table->timestamp('delivered_at')->nullable(); // وقت التسليم
            $table->timestamp('read_at')->nullable(); // وقت القراءة
            $table->timestamps();

            $table->index(['model_type', 'model_id']);
            $table->index(['phone_number']);
            $table->index(['status']);
            $table->index(['sent_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('whatsapp_logs');
    }
};
