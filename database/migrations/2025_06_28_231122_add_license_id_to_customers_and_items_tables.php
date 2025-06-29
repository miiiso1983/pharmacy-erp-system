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
        // إضافة license_id لجدول customers
        Schema::table('customers', function (Blueprint $table) {
            $table->unsignedBigInteger('license_id')->nullable()->after('id');
            $table->foreign('license_id')->references('id')->on('system_licenses')->onDelete('cascade');
            $table->index('license_id');
        });

        // إضافة license_id لجدول items
        Schema::table('items', function (Blueprint $table) {
            $table->unsignedBigInteger('license_id')->nullable()->after('id');
            $table->foreign('license_id')->references('id')->on('system_licenses')->onDelete('cascade');
            $table->index('license_id');
        });

        // إضافة license_id لجدول orders إذا كان موجود
        if (Schema::hasTable('orders')) {
            Schema::table('orders', function (Blueprint $table) {
                if (!Schema::hasColumn('orders', 'license_id')) {
                    $table->unsignedBigInteger('license_id')->nullable()->after('id');
                    $table->foreign('license_id')->references('id')->on('system_licenses')->onDelete('cascade');
                    $table->index('license_id');
                }
            });
        }

        // إضافة license_id لجدول invoices إذا كان موجود
        if (Schema::hasTable('invoices')) {
            Schema::table('invoices', function (Blueprint $table) {
                if (!Schema::hasColumn('invoices', 'license_id')) {
                    $table->unsignedBigInteger('license_id')->nullable()->after('id');
                    $table->foreign('license_id')->references('id')->on('system_licenses')->onDelete('cascade');
                    $table->index('license_id');
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // حذف license_id من جدول customers
        Schema::table('customers', function (Blueprint $table) {
            $table->dropForeign(['license_id']);
            $table->dropColumn('license_id');
        });

        // حذف license_id من جدول items
        Schema::table('items', function (Blueprint $table) {
            $table->dropForeign(['license_id']);
            $table->dropColumn('license_id');
        });

        // حذف license_id من جدول orders إذا كان موجود
        if (Schema::hasTable('orders') && Schema::hasColumn('orders', 'license_id')) {
            Schema::table('orders', function (Blueprint $table) {
                $table->dropForeign(['license_id']);
                $table->dropColumn('license_id');
            });
        }

        // حذف license_id من جدول invoices إذا كان موجود
        if (Schema::hasTable('invoices') && Schema::hasColumn('invoices', 'license_id')) {
            Schema::table('invoices', function (Blueprint $table) {
                $table->dropForeign(['license_id']);
                $table->dropColumn('license_id');
            });
        }
    }
};
