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
        Schema::table('invoices', function (Blueprint $table) {
            // حذف Foreign Key الخاطئ
            $table->dropForeign(['customer_id']);

            // إضافة Foreign Key الصحيح
            $table->foreign('customer_id')->references('id')->on('customers')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('invoices', function (Blueprint $table) {
            // حذف Foreign Key الصحيح
            $table->dropForeign(['customer_id']);

            // إعادة Foreign Key الخاطئ (للتراجع)
            $table->foreign('customer_id')->references('id')->on('users')->onDelete('cascade');
        });
    }
};
