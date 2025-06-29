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
        Schema::table('warehouses', function (Blueprint $table) {
            // جعل الحقول المطلوبة قابلة للقيم الفارغة أو إضافة قيم افتراضية
            $table->string('code')->nullable()->change();
            $table->string('city')->nullable()->change();
            $table->string('area')->nullable()->change();
            $table->text('address')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('warehouses', function (Blueprint $table) {
            // إعادة الحقول لحالتها الأصلية
            $table->string('code')->nullable(false)->change();
            $table->string('city')->nullable(false)->change();
            $table->string('area')->nullable(false)->change();
            $table->text('address')->nullable(false)->change();
        });
    }
};
