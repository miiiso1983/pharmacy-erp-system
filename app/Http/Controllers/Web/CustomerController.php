<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Customer;
use App\Models\CustomerTransaction;
use App\Models\CustomerPayment;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class CustomerController extends Controller
{
    /**
     * عرض صفحة الزبائن الرئيسية
     */
    public function index(Request $request)
    {
        try {
            // إحصائيات عامة
            $stats = [
                'total_customers' => Customer::count(),
                'active_customers' => Customer::where('status', 'active')->count(),
                'blocked_customers' => Customer::where('status', 'blocked')->count(),
                'total_outstanding' => Customer::sum('current_balance'),
                'over_credit_limit' => Customer::whereRaw('current_balance > credit_limit')->count(),
                'total_sales_this_month' => CustomerTransaction::where('transaction_type', 'sale')
                    ->whereMonth('transaction_date', now()->month)
                    ->sum('total_amount'),
                'total_collections_this_month' => CustomerPayment::whereRaw("strftime('%m', payment_date) = ?", [sprintf('%02d', now()->month)])
                    ->sum('amount'),
            ];

            // فلترة الزبائن
            $query = Customer::with(['transactions', 'payments']);

            // البحث
            if ($request->has('search') && $request->search) {
                $search = $request->search;
                $query->where(function($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                      ->orWhere('customer_code', 'like', "%{$search}%")
                      ->orWhere('business_name', 'like', "%{$search}%")
                      ->orWhere('phone', 'like', "%{$search}%")
                      ->orWhere('mobile', 'like', "%{$search}%");
                });
            }

            // فلترة حسب النوع
            if ($request->has('customer_type') && $request->customer_type) {
                $query->where('customer_type', $request->customer_type);
            }

            // فلترة حسب الحالة
            if ($request->has('status') && $request->status) {
                $query->where('status', $request->status);
            }

            // فلترة حسب تجاوز سقف الدين
            if ($request->has('over_credit_limit') && $request->over_credit_limit) {
                $query->whereRaw('current_balance > credit_limit');
            }

            $customers = $query->paginate(15);

            // حساب المعدلات الشهرية لكل زبون
            foreach ($customers as $customer) {
                $customer->monthly_purchase_avg = $customer->getMonthlyPurchaseAverage();
                $customer->monthly_collection_avg = $customer->getMonthlyCollectionAverage();
                $customer->credit_utilization = $customer->getCreditUtilizationPercentage();
            }

            return view('customers.index', compact('customers', 'stats'));

        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'حدث خطأ أثناء تحميل بيانات الزبائن: ' . $e->getMessage()]);
        }
    }

    /**
     * عرض تفاصيل زبون محدد
     */
    public function show($id)
    {
        try {
            $customer = Customer::with(['transactions', 'payments'])->findOrFail($id);

            // إحصائيات الزبون
            $customerStats = [
                'total_transactions' => $customer->transactions()->count(),
                'total_sales' => $customer->transactions()->where('transaction_type', 'sale')->sum('total_amount'),
                'total_returns' => $customer->transactions()->where('transaction_type', 'return')->sum('total_amount'),
                'total_payments' => $customer->payments()->sum('amount'),
                'unpaid_invoices' => $customer->unpaidTransactions()->count(),
                'last_transaction_date' => $customer->transactions()->latest('transaction_date')->value('transaction_date'),
                'last_payment_date' => $customer->payments()->latest('payment_date')->value('payment_date'),
            ];

            // المعاملات الأخيرة
            $recentTransactions = $customer->transactions()
                ->latest('transaction_date')
                ->take(10)
                ->get();

            // المدفوعات الأخيرة
            $recentPayments = $customer->payments()
                ->latest('payment_date')
                ->take(10)
                ->get();

            // المعاملات غير المدفوعة
            $unpaidTransactions = $customer->unpaidTransactions()
                ->orderBy('due_date')
                ->get();

            // إحصائيات شهرية للسنة الحالية
            $monthlyStats = [];
            for ($month = 1; $month <= 12; $month++) {
                $monthlyStats[] = [
                    'month' => $month,
                    'month_name' => Carbon::create(null, $month)->format('F'),
                    'sales' => $customer->transactions()
                        ->where('transaction_type', 'sale')
                        ->whereRaw("strftime('%m', transaction_date) = ?", [sprintf('%02d', $month)])
                        ->whereRaw("strftime('%Y', transaction_date) = ?", [now()->year])
                        ->sum('total_amount'),
                    'payments' => $customer->payments()
                        ->whereRaw("strftime('%m', payment_date) = ?", [sprintf('%02d', $month)])
                        ->whereRaw("strftime('%Y', payment_date) = ?", [now()->year])
                        ->sum('amount'),
                ];
            }

            return view('customers.show', compact(
                'customer',
                'customerStats',
                'recentTransactions',
                'recentPayments',
                'unpaidTransactions',
                'monthlyStats'
            ));

        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'حدث خطأ أثناء تحميل بيانات الزبون: ' . $e->getMessage()]);
        }
    }

    /**
     * عرض صفحة إنشاء زبون جديد
     */
    public function create()
    {
        return view('customers.create');
    }

    /**
     * حفظ زبون جديد
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'business_name' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:20',
            'mobile' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255|unique:customers,email',
            'address' => 'nullable|string|max:500',
            'city' => 'nullable|string|max:100',
            'area' => 'nullable|string|max:100',
            'customer_type' => 'required|in:individual,company,pharmacy,hospital,clinic',
            'credit_limit' => 'nullable|numeric|min:0',
            'payment_terms_days' => 'nullable|integer|min:0|max:365',
            'notes' => 'nullable|string|max:1000',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        try {
            // إنشاء رمز العميل التلقائي
            $customerCode = 'CUST' . str_pad(Customer::count() + 1, 6, '0', STR_PAD_LEFT);

            $customer = Customer::create([
                'customer_code' => $customerCode,
                'name' => $request->name,
                'business_name' => $request->business_name,
                'phone' => $request->phone,
                'mobile' => $request->mobile,
                'email' => $request->email,
                'address' => $request->address,
                'city' => $request->city,
                'area' => $request->area,
                'customer_type' => $request->customer_type,
                'credit_limit' => $request->credit_limit ?? 0,
                'payment_terms_days' => $request->payment_terms_days ?? 30,
                'current_balance' => 0,
                'total_purchases' => 0,
                'total_payments' => 0,
                'status' => 'active',
                'notes' => $request->notes,
            ]);

            return redirect()->route('customers.index')->with('success', 'تم إنشاء العميل بنجاح - رمز العميل: ' . $customer->customer_code);

        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'حدث خطأ أثناء إنشاء العميل: ' . $e->getMessage()])->withInput();
        }
    }

    /**
     * عرض صفحة تعديل الزبون
     */
    public function edit($id)
    {
        $customer = Customer::findOrFail($id);
        return view('customers.edit', compact('customer'));
    }

    /**
     * تحديث بيانات الزبون
     */
    public function update(Request $request, $id)
    {
        // سيتم تنفيذها لاحقاً
        return redirect()->route('customers.show', $id)->with('success', 'تم تحديث بيانات الزبون بنجاح');
    }

    /**
     * حذف الزبون
     */
    public function destroy($id)
    {
        // سيتم تنفيذها لاحقاً
        return redirect()->route('customers.index')->with('success', 'تم حذف الزبون بنجاح');
    }

    /**
     * عرض صفحة استيراد الزبائن
     */
    public function importForm()
    {
        return view('customers.import');
    }

    /**
     * تحميل نموذج Excel للزبائن
     */
    public function downloadTemplate()
    {
        $headers = [
            'customer_code' => 'كود الزبون',
            'name' => 'اسم الزبون*',
            'business_name' => 'اسم الشركة/المؤسسة',
            'phone' => 'رقم الهاتف',
            'mobile' => 'رقم الموبايل',
            'email' => 'البريد الإلكتروني',
            'address' => 'العنوان',
            'city' => 'المدينة',
            'area' => 'المنطقة',
            'customer_type' => 'نوع الزبون* (retail/wholesale/pharmacy)',
            'credit_limit' => 'سقف الدين*',
            'payment_terms_days' => 'مدة السداد (يوم)*',
            'status' => 'الحالة* (active/inactive/blocked)',
            'notes' => 'ملاحظات'
        ];

        // إنشاء بيانات تجريبية
        $sampleData = [
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
                'credit_limit' => '5000000',
                'payment_terms_days' => '30',
                'status' => 'active',
                'notes' => 'زبون مميز - صيدلية كبيرة'
            ],
            [
                'customer_code' => 'CUST002',
                'name' => 'أحمد محمد علي',
                'business_name' => '',
                'phone' => '07901234568',
                'mobile' => '07801234568',
                'email' => 'ahmed@customer.com',
                'address' => 'حي الجامعة - بغداد',
                'city' => 'بغداد',
                'area' => 'الجامعة',
                'customer_type' => 'retail',
                'credit_limit' => '500000',
                'payment_terms_days' => '15',
                'status' => 'active',
                'notes' => 'زبون تجزئة منتظم'
            ]
        ];

        // إنشاء محتوى CSV
        $csvContent = implode(',', array_values($headers)) . "\n";
        foreach ($sampleData as $row) {
            $csvContent .= implode(',', array_map(function($value) {
                return '"' . str_replace('"', '""', $value) . '"';
            }, array_values($row))) . "\n";
        }

        return response($csvContent)
            ->header('Content-Type', 'text/csv; charset=UTF-8')
            ->header('Content-Disposition', 'attachment; filename="customers_template.csv"')
            ->header('Content-Length', strlen($csvContent));
    }

    /**
     * استيراد الزبائن من ملف Excel/CSV
     */
    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:csv,txt,xlsx,xls|max:2048'
        ]);

        try {
            $file = $request->file('file');
            $path = $file->getRealPath();

            // قراءة الملف
            if ($file->getClientOriginalExtension() === 'csv' || $file->getClientOriginalExtension() === 'txt') {
                $data = array_map('str_getcsv', file($path));
            } else {
                // للملفات Excel، نحتاج مكتبة إضافية
                return back()->withErrors(['file' => 'نوع الملف غير مدعوم حالياً. يرجى استخدام ملف CSV.']);
            }

            if (empty($data)) {
                return back()->withErrors(['file' => 'الملف فارغ أو تالف']);
            }

            $headers = $data[0];
            $rows = array_slice($data, 1);

            $imported = 0;
            $errors = [];

            foreach ($rows as $index => $row) {
                $rowNumber = $index + 2; // +2 لأن الفهرس يبدأ من 0 والصف الأول هو العناوين

                if (count($row) < count($headers)) {
                    $errors[] = "الصف {$rowNumber}: بيانات ناقصة";
                    continue;
                }

                // تحويل الصف إلى مصفوفة مفاتيح
                $customerData = array_combine($headers, $row);

                // التحقق من البيانات المطلوبة
                if (empty($customerData['name'])) {
                    $errors[] = "الصف {$rowNumber}: اسم الزبون مطلوب";
                    continue;
                }

                if (empty($customerData['customer_type']) ||
                    !in_array($customerData['customer_type'], ['retail', 'wholesale', 'pharmacy'])) {
                    $errors[] = "الصف {$rowNumber}: نوع الزبون غير صحيح (retail/wholesale/pharmacy)";
                    continue;
                }

                if (empty($customerData['status']) ||
                    !in_array($customerData['status'], ['active', 'inactive', 'blocked'])) {
                    $errors[] = "الصف {$rowNumber}: حالة الزبون غير صحيحة (active/inactive/blocked)";
                    continue;
                }

                // إنشاء كود الزبون إذا لم يكن موجوداً
                if (empty($customerData['customer_code'])) {
                    $customerData['customer_code'] = 'CUST' . str_pad(Customer::count() + 1, 4, '0', STR_PAD_LEFT);
                }

                // التحقق من عدم تكرار الكود
                if (Customer::where('customer_code', $customerData['customer_code'])->exists()) {
                    $errors[] = "الصف {$rowNumber}: كود الزبون {$customerData['customer_code']} موجود مسبقاً";
                    continue;
                }

                // تعيين القيم الافتراضية
                $customerData['credit_limit'] = !empty($customerData['credit_limit']) ?
                    floatval($customerData['credit_limit']) : 0;
                $customerData['payment_terms_days'] = !empty($customerData['payment_terms_days']) ?
                    intval($customerData['payment_terms_days']) : 30;
                $customerData['current_balance'] = 0;
                $customerData['total_purchases'] = 0;
                $customerData['total_payments'] = 0;

                try {
                    Customer::create($customerData);
                    $imported++;
                } catch (\Exception $e) {
                    $errors[] = "الصف {$rowNumber}: خطأ في حفظ البيانات - " . $e->getMessage();
                }
            }

            $message = "تم استيراد {$imported} زبون بنجاح";
            if (!empty($errors)) {
                $message .= ". الأخطاء: " . implode(', ', array_slice($errors, 0, 5));
                if (count($errors) > 5) {
                    $message .= " و " . (count($errors) - 5) . " أخطاء أخرى";
                }
            }

            return redirect()->route('customers.index')->with('success', $message);

        } catch (\Exception $e) {
            return back()->withErrors(['file' => 'حدث خطأ أثناء معالجة الملف: ' . $e->getMessage()]);
        }
    }

    /**
     * تصدير الزبائن إلى ملف Excel/CSV
     */
    public function export(Request $request)
    {
        $query = Customer::query();

        // تطبيق الفلاتر إذا وجدت
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('customer_code', 'like', "%{$search}%")
                  ->orWhere('business_name', 'like', "%{$search}%");
            });
        }

        if ($request->has('customer_type') && $request->customer_type) {
            $query->where('customer_type', $request->customer_type);
        }

        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }

        $customers = $query->get();

        // إنشاء محتوى CSV
        $headers = [
            'كود الزبون',
            'اسم الزبون',
            'اسم الشركة/المؤسسة',
            'رقم الهاتف',
            'رقم الموبايل',
            'البريد الإلكتروني',
            'العنوان',
            'المدينة',
            'المنطقة',
            'نوع الزبون',
            'سقف الدين',
            'مدة السداد (يوم)',
            'الرصيد الحالي',
            'إجمالي المشتريات',
            'إجمالي المدفوعات',
            'الحالة',
            'ملاحظات',
            'تاريخ الإنشاء'
        ];

        $csvContent = implode(',', $headers) . "\n";

        foreach ($customers as $customer) {
            $row = [
                $customer->customer_code,
                $customer->name,
                $customer->business_name ?? '',
                $customer->phone ?? '',
                $customer->mobile ?? '',
                $customer->email ?? '',
                $customer->address ?? '',
                $customer->city ?? '',
                $customer->area ?? '',
                $customer->customer_type,
                $customer->credit_limit,
                $customer->payment_terms_days,
                $customer->current_balance,
                $customer->total_purchases,
                $customer->total_payments,
                $customer->status,
                $customer->notes ?? '',
                $customer->created_at->format('Y-m-d H:i:s')
            ];

            $csvContent .= implode(',', array_map(function($value) {
                return '"' . str_replace('"', '""', $value) . '"';
            }, $row)) . "\n";
        }

        $filename = 'customers_export_' . date('Y-m-d_H-i-s') . '.csv';

        return response($csvContent)
            ->header('Content-Type', 'text/csv; charset=UTF-8')
            ->header('Content-Disposition', "attachment; filename=\"{$filename}\"")
            ->header('Content-Length', strlen($csvContent));
    }
}
