<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // إضافة فهارس بطريقة آمنة (تجاهل الأخطاء إذا كانت موجودة)
        $this->addIndexSafely('users', 'email');
        $this->addIndexSafely('users', 'status');
        $this->addIndexSafely('users', 'user_type');
        $this->addIndexSafely('users', 'created_at');
        
        if (Schema::hasTable('employees')) {
            $this->addIndexSafely('employees', 'employee_id');
            $this->addIndexSafely('employees', 'email');
            $this->addIndexSafely('employees', 'status');
            $this->addIndexSafely('employees', 'department_id');
            $this->addIndexSafely('employees', 'hire_date');
        }
        
        if (Schema::hasTable('items')) {
            $this->addIndexSafely('items', 'code');
            $this->addIndexSafely('items', 'barcode');
            $this->addIndexSafely('items', 'status');
            $this->addIndexSafely('items', 'category');
            $this->addIndexSafely('items', 'supplier_id');
            $this->addIndexSafely('items', 'expiry_date');
        }
        
        if (Schema::hasTable('orders')) {
            $this->addIndexSafely('orders', 'order_number');
            $this->addIndexSafely('orders', 'status');
            $this->addIndexSafely('orders', 'customer_id');
            $this->addIndexSafely('orders', 'order_date');
        }
        
        if (Schema::hasTable('invoices')) {
            $this->addIndexSafely('invoices', 'invoice_number');
            $this->addIndexSafely('invoices', 'status');
            $this->addIndexSafely('invoices', 'customer_id');
            $this->addIndexSafely('invoices', 'invoice_date');
        }
        
        if (Schema::hasTable('warehouses')) {
            $this->addIndexSafely('warehouses', 'code');
            $this->addIndexSafely('warehouses', 'status');
        }
        
        if (Schema::hasTable('attendances')) {
            $this->addIndexSafely('attendances', 'employee_id');
            $this->addIndexSafely('attendances', 'date');
            $this->addIndexSafely('attendances', 'status');
        }
        
        if (Schema::hasTable('leaves')) {
            $this->addIndexSafely('leaves', 'employee_id');
            $this->addIndexSafely('leaves', 'status');
            $this->addIndexSafely('leaves', 'start_date');
        }
    }

    /**
     * إضافة فهرس بطريقة آمنة
     */
    private function addIndexSafely(string $table, string $column): void
    {
        try {
            $indexName = "{$table}_{$column}_index";
            
            // التحقق من وجود الفهرس
            $exists = DB::select("
                SELECT name FROM sqlite_master 
                WHERE type='index' AND name=? AND tbl_name=?
            ", [$indexName, $table]);
            
            if (empty($exists)) {
                DB::statement("CREATE INDEX IF NOT EXISTS {$indexName} ON {$table} ({$column})");
            }
        } catch (\Exception $e) {
            // تجاهل الأخطاء وتسجيلها فقط
            \Log::info("Index creation skipped for {$table}.{$column}: " . $e->getMessage());
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // لا نحتاج لحذف الفهارس في rollback
    }
};
