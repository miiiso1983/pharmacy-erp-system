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
        Schema::table('doctors', function (Blueprint $table) {
            // إضافة الأعمدة المفقودة
            $table->string('doctor_code')->nullable()->after('id');
            $table->string('mobile')->nullable()->after('phone');
            $table->string('specialization')->nullable()->after('name'); // بدلاً من specialty
            $table->string('clinic_address')->nullable()->after('clinic_name');
            $table->enum('visit_frequency', ['weekly', 'monthly', 'quarterly'])->default('monthly')->after('medical_representative_id');
            $table->string('preferred_visit_time')->nullable()->after('visit_frequency');

            // تحديث العمود الموجود
            $table->text('address')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('doctors', function (Blueprint $table) {
            $table->dropColumn([
                'doctor_code',
                'mobile',
                'specialization',
                'clinic_address',
                'visit_frequency',
                'preferred_visit_time'
            ]);
        });
    }
};
