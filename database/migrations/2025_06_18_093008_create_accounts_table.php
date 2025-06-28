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
        Schema::create('accounts', function (Blueprint $table) {
            $table->id();
            $table->string('account_code')->unique(); // رمز الحساب
            $table->string('account_name'); // اسم الحساب
            $table->string('account_name_en')->nullable(); // اسم الحساب بالإنجليزية
            $table->enum('account_type', ['asset', 'liability', 'equity', 'revenue', 'expense']); // نوع الحساب
            $table->enum('account_category', [
                'current_assets', 'fixed_assets', 'current_liabilities', 'long_term_liabilities',
                'capital', 'retained_earnings', 'sales_revenue', 'other_revenue',
                'cost_of_goods_sold', 'operating_expenses', 'financial_expenses'
            ]); // تصنيف الحساب
            $table->unsignedBigInteger('parent_account_id')->nullable(); // الحساب الأب
            $table->integer('account_level')->default(1); // مستوى الحساب في الشجرة
            $table->decimal('opening_balance', 15, 2)->default(0); // الرصيد الافتتاحي
            $table->decimal('current_balance', 15, 2)->default(0); // الرصيد الحالي
            $table->enum('balance_type', ['debit', 'credit']); // طبيعة الرصيد
            $table->boolean('is_active')->default(true); // حالة الحساب
            $table->boolean('is_system_account')->default(false); // حساب نظام
            $table->text('description')->nullable(); // وصف الحساب
            $table->timestamps();

            $table->foreign('parent_account_id')->references('id')->on('accounts')->onDelete('set null');
            $table->index(['account_type', 'account_category']);
            $table->index('parent_account_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('accounts');
    }
};
