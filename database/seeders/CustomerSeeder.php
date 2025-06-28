<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Customer;
use App\Models\CustomerTransaction;
use App\Models\CustomerPayment;
use Carbon\Carbon;

class CustomerSeeder extends Seeder
{
    /**
     * Run the database seeder.
     */
    public function run(): void
    {
        // إنشاء الزبائن
        $customers = [
            [
                'customer_code' => 'CUST001',
                'name' => 'صيدلية الشفاء',
                'business_name' => 'صيدلية الشفاء للأدوية',
                'phone' => '07901234567',
                'mobile' => '07801234567',
                'email' => 'shifa@pharmacy.com',
                'address' => 'شارع الجامعة - بغداد',
                'city' => 'بغداد',
                'area' => 'الجادرية',
                'customer_type' => 'pharmacy',
                'credit_limit' => 5000000, // 5 مليون دينار
                'payment_terms_days' => 30,
                'status' => 'active',
                'notes' => 'زبون مميز - صيدلية كبيرة',
            ],
            [
                'customer_code' => 'CUST002',
                'name' => 'مستشفى النور',
                'business_name' => 'مستشفى النور الطبي',
                'phone' => '07901234568',
                'mobile' => '07801234568',
                'email' => 'noor@hospital.com',
                'address' => 'شارع الكندي - بغداد',
                'city' => 'بغداد',
                'area' => 'الكرادة',
                'customer_type' => 'wholesale',
                'credit_limit' => 10000000, // 10 مليون دينار
                'payment_terms_days' => 45,
                'status' => 'active',
                'notes' => 'مستشفى كبير - طلبات منتظمة',
            ],
            [
                'customer_code' => 'CUST003',
                'name' => 'أحمد محمد علي',
                'business_name' => null,
                'phone' => '07901234569',
                'mobile' => '07801234569',
                'email' => 'ahmed@customer.com',
                'address' => 'حي الجامعة - بغداد',
                'city' => 'بغداد',
                'area' => 'الجامعة',
                'customer_type' => 'retail',
                'credit_limit' => 500000, // 500 ألف دينار
                'payment_terms_days' => 15,
                'status' => 'active',
                'notes' => 'زبون تجزئة منتظم',
            ],
            [
                'customer_code' => 'CUST004',
                'name' => 'صيدلية الحياة',
                'business_name' => 'صيدلية الحياة الطبية',
                'phone' => '07901234570',
                'mobile' => '07801234570',
                'email' => 'hayat@pharmacy.com',
                'address' => 'شارع فلسطين - بغداد',
                'city' => 'بغداد',
                'area' => 'الحارثية',
                'customer_type' => 'pharmacy',
                'credit_limit' => 3000000, // 3 مليون دينار
                'payment_terms_days' => 30,
                'status' => 'active',
                'notes' => 'صيدلية متوسطة الحجم',
            ],
            [
                'customer_code' => 'CUST005',
                'name' => 'مركز الأمل الطبي',
                'business_name' => 'مركز الأمل للخدمات الطبية',
                'phone' => '07901234571',
                'mobile' => '07801234571',
                'email' => 'amal@medical.com',
                'address' => 'شارع الأطباء - بغداد',
                'city' => 'بغداد',
                'area' => 'المنصور',
                'customer_type' => 'wholesale',
                'credit_limit' => 7000000, // 7 مليون دينار
                'payment_terms_days' => 30,
                'status' => 'active',
                'notes' => 'مركز طبي متخصص',
            ],
            [
                'customer_code' => 'CUST006',
                'name' => 'فاطمة حسن محمود',
                'business_name' => null,
                'phone' => '07901234572',
                'mobile' => '07801234572',
                'email' => 'fatima@customer.com',
                'address' => 'حي الأطباء - بغداد',
                'city' => 'بغداد',
                'area' => 'الأطباء',
                'customer_type' => 'retail',
                'credit_limit' => 300000, // 300 ألف دينار
                'payment_terms_days' => 7,
                'status' => 'active',
                'notes' => 'زبونة تجزئة',
            ],
            [
                'customer_code' => 'CUST007',
                'name' => 'صيدلية المستقبل',
                'business_name' => 'صيدلية المستقبل الحديثة',
                'phone' => '07901234573',
                'mobile' => '07801234573',
                'email' => 'future@pharmacy.com',
                'address' => 'شارع الرشيد - بغداد',
                'city' => 'بغداد',
                'area' => 'الرشيد',
                'customer_type' => 'pharmacy',
                'credit_limit' => 4000000, // 4 مليون دينار
                'payment_terms_days' => 30,
                'status' => 'blocked', // زبون محظور لتجاوز سقف الدين
                'notes' => 'محظور لتجاوز سقف الدين',
            ],
        ];

        foreach ($customers as $customerData) {
            Customer::firstOrCreate(['customer_code' => $customerData['customer_code']], $customerData);
        }

        // إنشاء المعاملات والمدفوعات
        $customers = Customer::all();
        $transactionCounter = 1;

        foreach ($customers as $customer) {
            // إنشاء معاملات للشهور الماضية
            for ($month = 6; $month >= 1; $month--) {
                $transactionDate = Carbon::now()->subMonths($month)->addDays(rand(1, 28));

                // إنشاء 2-5 معاملات شهرياً
                $transactionsCount = rand(2, 5);

                for ($i = 0; $i < $transactionsCount; $i++) {
                    $amount = rand(100000, 2000000); // من 100 ألف إلى 2 مليون
                    $discount = $amount * (rand(0, 10) / 100); // خصم 0-10%
                    $tax = ($amount - $discount) * 0.05; // ضريبة 5%
                    $totalAmount = $amount - $discount + $tax;

                    $transaction = CustomerTransaction::create([
                        'customer_id' => $customer->id,
                        'transaction_type' => 'sale',
                        'reference_number' => 'INV-' . str_pad($transactionCounter, 6, '0', STR_PAD_LEFT),
                        'transaction_date' => $transactionDate->copy()->addDays($i),
                        'amount' => $amount,
                        'discount' => $discount,
                        'tax' => $tax,
                        'total_amount' => $totalAmount,
                        'payment_status' => ['paid', 'partial', 'unpaid'][rand(0, 2)],
                        'due_date' => $transactionDate->copy()->addDays($customer->payment_terms_days),
                        'description' => 'فاتورة مبيعات أدوية',
                        'items' => [
                            ['name' => 'باراسيتامول', 'quantity' => rand(10, 100), 'price' => rand(500, 2000)],
                            ['name' => 'أموكسيسيلين', 'quantity' => rand(5, 50), 'price' => rand(1000, 5000)],
                            ['name' => 'أوميبرازول', 'quantity' => rand(10, 80), 'price' => rand(800, 3000)],
                        ],
                        'created_by' => 1,
                    ]);

                    // إنشاء مدفوعات للمعاملات
                    if ($transaction->payment_status !== 'unpaid') {
                        $paymentAmount = $transaction->payment_status === 'paid' 
                            ? $totalAmount 
                            : $totalAmount * (rand(30, 80) / 100);

                        $paymentMethod = ['cash', 'bank_transfer', 'check'][rand(0, 2)];

                        CustomerPayment::create([
                            'customer_id' => $customer->id,
                            'transaction_id' => $transaction->id,
                            'payment_number' => 'PAY-' . str_pad($transactionCounter, 6, '0', STR_PAD_LEFT),
                            'payment_date' => $transactionDate->copy()->addDays(rand(1, $customer->payment_terms_days)),
                            'amount' => $paymentAmount,
                            'payment_method' => $paymentMethod,
                            'reference_number' => $paymentMethod === 'check' ? 'CHK-' . rand(100000, 999999) : null,
                            'notes' => 'دفعة على حساب الفاتورة',
                            'received_by' => 1,
                        ]);

                        $transaction->paid_amount = $paymentAmount;
                        $transaction->remaining_amount = $totalAmount - $paymentAmount;
                        $transaction->save();
                    } else {
                        $transaction->remaining_amount = $totalAmount;
                        $transaction->save();
                    }

                    $transactionCounter++;
                }
            }

            // تحديث إجماليات الزبون
            $totalSales = $customer->transactions()->where('transaction_type', 'sale')->sum('total_amount');
            $totalPayments = $customer->payments()->sum('amount');
            $currentBalance = $totalSales - $totalPayments;

            $customer->update([
                'total_purchases' => $totalSales,
                'total_payments' => $totalPayments,
                'current_balance' => $currentBalance,
                'last_purchase_date' => $customer->transactions()->latest('transaction_date')->value('transaction_date'),
                'last_payment_date' => $customer->payments()->latest('payment_date')->value('payment_date'),
            ]);
        }

        $this->command->info('تم إنشاء البيانات التجريبية للزبائن بنجاح!');
        $this->command->info('الزبائن: ' . Customer::count());
        $this->command->info('المعاملات: ' . CustomerTransaction::count());
        $this->command->info('المدفوعات: ' . CustomerPayment::count());
        $this->command->info('إجمالي المبيعات: ' . number_format(CustomerTransaction::sum('total_amount')) . ' د.ع');
        $this->command->info('إجمالي المدفوعات: ' . number_format(CustomerPayment::sum('amount')) . ' د.ع');
    }
}
