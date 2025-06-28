<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Order;
use App\Models\Invoice;
use App\Models\Collection;
use App\Models\Item;
use App\Models\Supplier;
use App\Models\OrderItem;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class AdvancedReportTestDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // تنظيف البيانات الموجودة (اختياري)
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        // إنشاء موردين إضافيين
        $suppliers = [];
        for ($i = 1; $i <= 5; $i++) {
            $suppliers[] = Supplier::create([
                'name' => 'مورد الأدوية رقم ' . $i,
                'contact_person' => 'مدير المبيعات ' . $i,
                'phone' => '+964770' . rand(1000000, 9999999),
                'email' => "supplier{$i}@pharmacy.com",
                'address' => 'شارع الطب رقم ' . $i . ', بغداد, العراق',
                'tax_number' => 'TAX' . rand(100000, 999999),
                'status' => 'active',
                'notes' => 'مورد موثوق للأدوية والمستلزمات الطبية'
            ]);
        }

        // إنشاء عناصر إضافية
        $items = [];
        $categories = ['مضادات حيوية', 'مسكنات', 'فيتامينات', 'أدوية القلب', 'أدوية السكري'];

        for ($i = 1; $i <= 50; $i++) {
            $costPrice = rand(1000, 50000);
            $sellingPrice = $costPrice * (1 + rand(20, 100) / 100);

            $items[] = Item::create([
                'code' => 'ITEM' . str_pad($i, 4, '0', STR_PAD_LEFT),
                'name' => 'دواء رقم ' . $i,
                'description' => 'وصف تفصيلي للدواء رقم ' . $i,
                'category' => $categories[rand(0, 4)],
                'unit' => ['علبة', 'شريط', 'قرص', 'زجاجة'][rand(0, 3)],
                'cost' => $costPrice,
                'price' => $sellingPrice,
                'stock_quantity' => rand(10, 1000),
                'min_stock_level' => rand(5, 50),
                'supplier_id' => $suppliers[rand(0, 4)]->id,
                'barcode' => 'BAR' . rand(100000000000, 999999999999),
                'expiry_date' => now()->addMonths(rand(6, 36)),
                'batch_number' => 'BATCH' . rand(1000, 9999),
                'status' => 'active',
                'notes' => 'دواء عالي الجودة'
            ]);
        }

        // إنشاء عملاء إضافيين
        $customers = [];
        $cities = ['بغداد', 'البصرة', 'أربيل', 'النجف', 'كربلاء'];
        $companyTypes = ['صيدلية', 'مستشفى', 'عيادة'];

        for ($i = 1; $i <= 20; $i++) {
            $customers[] = User::create([
                'name' => 'عميل رقم ' . $i,
                'email' => "customer{$i}@pharmacy.com",
                'phone' => '+964770' . rand(1000000, 9999999),
                'password' => bcrypt('password123'),
                'address' => 'عنوان العميل رقم ' . $i . ', ' . $cities[rand(0, 4)],
                'company_name' => $companyTypes[rand(0, 2)] . ' رقم ' . $i,
                'tax_number' => 'TAX' . rand(100000, 999999),
                'user_type' => 'customer',
                'status' => 'active',
                'notes' => 'عميل موثوق'
            ]);
        }

        // تعيين دور العميل للمستخدمين الجدد
        foreach ($customers as $customer) {
            $customer->assignRole('customer');
        }

        // إنشاء طلبات متنوعة للأشهر الثلاثة الماضية
        $orders = [];
        $statuses = ['pending', 'confirmed', 'delivered', 'cancelled'];
        $adminUser = User::role('admin')->first() ?? User::first();

        for ($i = 1; $i <= 100; $i++) {
            $orderDate = Carbon::now()->subDays(rand(1, 90));
            $customer = $customers[rand(0, 19)];
            $status = $statuses[rand(0, 3)];

            $order = Order::create([
                'order_number' => 'ORD' . str_pad($i, 6, '0', STR_PAD_LEFT),
                'customer_id' => $customer->id,
                'status' => $status,
                'subtotal' => 0, // سيتم حسابه لاحقاً
                'tax_amount' => 0,
                'discount_amount' => rand(0, 10000),
                'total_amount' => 0,
                'delivery_date' => $orderDate->copy()->addDays(rand(1, 7)),
                'delivery_address' => $customer->address,
                'notes' => 'ملاحظات الطلب رقم ' . $i,
                'created_by' => $adminUser->id,
                'created_at' => $orderDate,
                'updated_at' => $orderDate
            ]);

            // إضافة عناصر للطلب
            $orderSubtotal = 0;
            $itemCount = rand(1, 8);
            
            for ($j = 0; $j < $itemCount; $j++) {
                $item = $items[rand(0, 49)];
                $quantity = rand(1, 20);
                $unitPrice = $item->price;
                $totalPrice = $quantity * $unitPrice;
                
                OrderItem::create([
                    'order_id' => $order->id,
                    'item_id' => $item->id,
                    'quantity' => $quantity,
                    'unit_price' => $unitPrice,
                    'total_price' => $totalPrice,
                    'discount_amount' => rand(0, $totalPrice * 0.1),
                    'free_quantity' => rand(0, 2)
                ]);
                
                $orderSubtotal += $totalPrice;
            }

            // تحديث إجماليات الطلب
            $taxAmount = $orderSubtotal * 0.05; // ضريبة 5%
            $totalAmount = $orderSubtotal + $taxAmount - $order->discount_amount;
            
            $order->update([
                'subtotal' => $orderSubtotal,
                'tax_amount' => $taxAmount,
                'total_amount' => $totalAmount
            ]);

            $orders[] = $order;
        }

        // إنشاء فواتير للطلبات المؤكدة والمسلمة
        $invoices = [];
        foreach ($orders as $order) {
            if (in_array($order->status, ['confirmed', 'delivered'])) {
                $dueDate = $order->created_at->copy()->addDays(rand(15, 45));
                
                $invoice = Invoice::create([
                    'invoice_number' => 'INV' . str_pad($order->id, 6, '0', STR_PAD_LEFT),
                    'order_id' => $order->id,
                    'customer_id' => $order->customer_id,
                    'total_amount' => $order->total_amount,
                    'paid_amount' => 0, // سيتم تحديثه مع التحصيلات
                    'remaining_amount' => $order->total_amount,
                    'status' => 'pending',
                    'due_date' => $dueDate,
                    'created_at' => $order->created_at,
                    'updated_at' => $order->created_at
                ]);

                $invoices[] = $invoice;
            }
        }

        // إنشاء تحصيلات متنوعة
        $paymentMethods = ['نقد', 'شيك', 'تحويل بنكي', 'بطاقة ائتمان'];
        $collectors = User::role('employee')->get();
        
        foreach ($invoices as $invoice) {
            // احتمال 70% لوجود تحصيل جزئي أو كامل
            if (rand(1, 100) <= 70) {
                $collectionsCount = rand(1, 3); // من 1 إلى 3 تحصيلات
                $remainingAmount = $invoice->total_amount;
                $totalCollected = 0;
                
                for ($k = 0; $k < $collectionsCount && $remainingAmount > 0; $k++) {
                    $collectionAmount = $k == $collectionsCount - 1 
                        ? $remainingAmount // آخر تحصيل يأخذ المبلغ المتبقي
                        : rand(1000, min($remainingAmount, $remainingAmount * 0.8));
                    
                    $collectionDate = $invoice->created_at->copy()->addDays(rand(1, 30));
                    
                    Collection::create([
                        'collection_number' => 'COL' . str_pad($invoice->id * 10 + $k, 6, '0', STR_PAD_LEFT),
                        'invoice_id' => $invoice->id,
                        'customer_id' => $invoice->customer_id,
                        'amount' => $collectionAmount,
                        'payment_method' => $paymentMethods[rand(0, 3)],
                        'status' => 'completed',
                        'collected_by' => $collectors->isNotEmpty() ? $collectors->random()->id : 1,
                        'collection_date' => $collectionDate,
                        'notes' => 'تحصيل رقم ' . ($k + 1) . ' للفاتورة ' . $invoice->invoice_number,
                        'created_at' => $collectionDate,
                        'updated_at' => $collectionDate
                    ]);
                    
                    $totalCollected += $collectionAmount;
                    $remainingAmount -= $collectionAmount;
                }
                
                // تحديث الفاتورة
                $invoice->update([
                    'paid_amount' => $totalCollected,
                    'remaining_amount' => $invoice->total_amount - $totalCollected,
                    'status' => $totalCollected >= $invoice->total_amount ? 'paid' : 'partial'
                ]);
            }
        }

        // إنشاء بعض التقارير المخصصة كأمثلة
        $adminUser = User::role('admin')->first();
        
        if ($adminUser) {
            // تقرير المبيعات الشهرية
            \App\Models\CustomReport::create([
                'name' => 'تقرير المبيعات الشهرية المتداخل',
                'description' => 'تقرير شامل يجمع بين الطلبات والفواتير والتحصيلات والعملاء',
                'report_type' => 'integrated',
                'data_sources' => ['orders', 'invoices', 'collections', 'customers'],
                'columns' => [
                    ['field' => 'order_number', 'alias' => 'رقم الطلب', 'source' => 'orders'],
                    ['field' => 'name', 'alias' => 'اسم العميل', 'source' => 'customers'],
                    ['field' => 'total_amount', 'alias' => 'مبلغ الطلب', 'source' => 'orders'],
                    ['field' => 'paid_amount', 'alias' => 'المبلغ المدفوع', 'source' => 'invoices'],
                    ['field' => 'payment_method', 'alias' => 'طريقة الدفع', 'source' => 'collections']
                ],
                'calculations' => [
                    ['type' => 'sum', 'field' => 'total_amount', 'alias' => 'إجمالي المبيعات', 'source' => 'orders'],
                    ['type' => 'sum', 'field' => 'paid_amount', 'alias' => 'إجمالي التحصيلات', 'source' => 'invoices'],
                    ['type' => 'count', 'field' => 'id', 'alias' => 'عدد الطلبات', 'source' => 'orders']
                ],
                'created_by' => $adminUser->id,
                'is_public' => true,
                'status' => 'active',
                'category' => 'مبيعات'
            ]);

            // تقرير أداء العملاء
            \App\Models\CustomReport::create([
                'name' => 'تقرير أداء العملاء المتقدم',
                'description' => 'تحليل شامل لأداء العملاء مع بيانات الطلبات والمدفوعات',
                'report_type' => 'analytical',
                'data_sources' => ['customers', 'orders', 'invoices', 'collections'],
                'columns' => [
                    ['field' => 'name', 'alias' => 'اسم العميل', 'source' => 'customers'],
                    ['field' => 'city', 'alias' => 'المدينة', 'source' => 'customers'],
                    ['field' => 'customer_type', 'alias' => 'نوع العميل', 'source' => 'customers']
                ],
                'calculations' => [
                    ['type' => 'count', 'field' => 'id', 'alias' => 'عدد الطلبات', 'source' => 'orders'],
                    ['type' => 'sum', 'field' => 'total_amount', 'alias' => 'إجمالي المشتريات', 'source' => 'orders'],
                    ['type' => 'avg', 'field' => 'total_amount', 'alias' => 'متوسط قيمة الطلب', 'source' => 'orders'],
                    ['type' => 'sum', 'field' => 'amount', 'alias' => 'إجمالي المدفوعات', 'source' => 'collections']
                ],
                'grouping' => [
                    ['field' => 'city', 'source' => 'customers'],
                    ['field' => 'customer_type', 'source' => 'customers']
                ],
                'created_by' => $adminUser->id,
                'is_public' => true,
                'status' => 'active',
                'category' => 'عملاء'
            ]);
        }

        $this->command->info('تم إنشاء بيانات اختبار التقارير المتقدمة بنجاح!');
        $this->command->info('- ' . count($suppliers) . ' موردين');
        $this->command->info('- ' . count($items) . ' عنصر');
        $this->command->info('- ' . count($customers) . ' عميل');
        $this->command->info('- ' . count($orders) . ' طلب');
        $this->command->info('- ' . count($invoices) . ' فاتورة');
        $this->command->info('- تحصيلات متنوعة');
        $this->command->info('- تقارير مخصصة كأمثلة');
    }
}
