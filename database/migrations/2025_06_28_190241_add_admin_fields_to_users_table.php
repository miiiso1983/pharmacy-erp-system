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
        Schema::table('users', function (Blueprint $table) {
            // إضافة حقول إدارية
            $table->string('user_role')->default('employee')->after('email'); // super_admin, admin, manager, employee, pharmacy, warehouse, sales_rep
            $table->date('account_expiry_date')->nullable()->after('user_role');
            $table->boolean('is_account_active')->default(true)->after('account_expiry_date');
            $table->foreignId('warehouse_id')->nullable()->constrained('warehouses')->after('is_account_active');
            $table->foreignId('license_id')->nullable()->constrained('system_licenses')->after('warehouse_id');

            // معلومات إضافية
            $table->string('phone')->nullable()->after('license_id');
            $table->text('address')->nullable()->after('phone');
            $table->string('department')->nullable()->after('address'); // قسم العمل
            $table->json('permissions')->nullable()->after('department'); // صلاحيات مخصصة
            $table->timestamp('last_login_at')->nullable()->after('permissions');
            $table->string('created_by_admin')->nullable()->after('last_login_at'); // من أنشأ الحساب

            // فهارس
            $table->index(['user_role', 'is_account_active']);
            $table->index('account_expiry_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['warehouse_id']);
            $table->dropForeign(['license_id']);
            $table->dropColumn([
                'user_role',
                'account_expiry_date',
                'is_account_active',
                'warehouse_id',
                'license_id',
                'phone',
                'address',
                'department',
                'permissions',
                'last_login_at',
                'created_by_admin'
            ]);
        });
    }
};
