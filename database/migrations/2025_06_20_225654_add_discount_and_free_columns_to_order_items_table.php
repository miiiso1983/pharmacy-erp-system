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
        Schema::table('order_items', function (Blueprint $table) {
            $table->decimal('discount_percentage', 5, 2)->default(0)->after('unit_price');
            $table->decimal('discount_amount', 10, 2)->default(0)->after('discount_percentage');
            $table->integer('free_quantity')->default(0)->after('quantity');
            $table->decimal('net_price', 10, 2)->default(0)->after('discount_amount');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('order_items', function (Blueprint $table) {
            $table->dropColumn(['discount_percentage', 'discount_amount', 'free_quantity', 'net_price']);
        });
    }
};
