<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use App\Models\Customer;
use Carbon\Carbon;

class CustomerImportController extends Controller
{
    /**
     * عرض صفحة استيراد العملاء
     */
    public function index()
    {
        try {
            // إحصائيات العملاء
            $stats = [
                'total_customers' => Customer::count(),
                'active_customers' => Customer::where('status', 'active')->count(),
                'inactive_customers' => Customer::where('status', 'inactive')->count(),
                'recent_imports' => Customer::where('created_at', '>=', Carbon::now()->subDays(7))->count(),
            ];

            return view('customers.import', compact('stats'));

        } catch (\Exception $e) {
            \Log::error('Customer import page error: ' . $e->getMessage());
            return back()->withErrors(['error' => 'حدث خطأ أثناء تحميل الصفحة: ' . $e->getMessage()]);
        }
    }

    /**
     * تحميل قالب Excel للعملاء
     */
    public function downloadTemplate()
    {
        try {
            $spreadsheet = new Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();

            // إعداد العناوين
            $headers = [
                'A1' => 'رمز العميل*',
                'B1' => 'اسم العميل*',
                'C1' => 'نوع العميل*',
                'D1' => 'رقم الهاتف',
                'E1' => 'البريد الإلكتروني',
                'F1' => 'العنوان',
                'G1' => 'المدينة',
                'H1' => 'المنطقة',
                'I1' => 'حد الائتمان',
                'J1' => 'الحالة*',
                'K1' => 'ملاحظات',
            ];

            // كتابة العناوين
            foreach ($headers as $cell => $header) {
                $sheet->setCellValue($cell, $header);
                $sheet->getStyle($cell)->getFont()->setBold(true);
                $sheet->getStyle($cell)->getFill()
                    ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                    ->getStartColor()->setRGB('E3F2FD');
            }

            // إضافة بيانات تجريبية
            $sampleData = [
                ['CUST001', 'صيدلية الشفاء', 'pharmacy', '07701234567', 'shifa@example.com', 'شارع الجامعة', 'بغداد', 'الكرخ', '50000', 'active', 'عميل مميز'],
                ['CUST002', 'مستشفى النور', 'hospital', '07709876543', 'noor@example.com', 'منطقة الكرادة', 'بغداد', 'الرصافة', '100000', 'active', ''],
                ['CUST003', 'عيادة الأمل', 'clinic', '07501234567', 'amal@example.com', 'شارع فلسطين', 'أربيل', 'المركز', '25000', 'active', 'عيادة متخصصة'],
            ];

            $row = 2;
            foreach ($sampleData as $data) {
                $col = 'A';
                foreach ($data as $value) {
                    $sheet->setCellValue($col . $row, $value);
                    $col++;
                }
                $row++;
            }

            // تنسيق الأعمدة
            foreach (range('A', 'K') as $col) {
                $sheet->getColumnDimension($col)->setAutoSize(true);
            }

            // إضافة تعليقات للخلايا المهمة
            $sheet->getComment('C1')->getText()->createTextRun('أنواع العملاء المسموحة: pharmacy, hospital, clinic, distributor, wholesaler, individual');
            $sheet->getComment('J1')->getText()->createTextRun('الحالات المسموحة: active, inactive');

            // إعداد الاستجابة
            $writer = new Xlsx($spreadsheet);
            $filename = 'قالب_استيراد_العملاء_' . date('Y-m-d') . '.xlsx';

            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment;filename="' . $filename . '"');
            header('Cache-Control: max-age=0');

            $writer->save('php://output');
            exit;

        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'حدث خطأ أثناء إنشاء القالب: ' . $e->getMessage()]);
        }
    }

    /**
     * استيراد العملاء من ملف Excel
     */
    public function import(Request $request)
    {
        try {
            // التحقق من الملف
            $request->validate([
                'excel_file' => 'required|file|mimes:xlsx,xls|max:10240', // 10MB max
            ], [
                'excel_file.required' => 'يرجى اختيار ملف Excel',
                'excel_file.mimes' => 'يجب أن يكون الملف من نوع Excel (xlsx, xls)',
                'excel_file.max' => 'حجم الملف يجب أن يكون أقل من 10 ميجابايت',
            ]);

            $file = $request->file('excel_file');
            
            // قراءة ملف Excel
            $spreadsheet = IOFactory::load($file->getPathname());
            $sheet = $spreadsheet->getActiveSheet();
            $rows = $sheet->toArray();

            // إزالة العنوان
            array_shift($rows);

            $successCount = 0;
            $errorCount = 0;
            $errors = [];
            $duplicates = [];

            DB::beginTransaction();

            foreach ($rows as $index => $row) {
                $rowNumber = $index + 2; // +2 لأن المصفوفة تبدأ من 0 والعنوان محذوف

                // تخطي الصفوف الفارغة
                if (empty(array_filter($row))) {
                    continue;
                }

                try {
                    // تنظيف البيانات
                    $customerData = [
                        'customer_code' => trim($row[0] ?? ''),
                        'name' => trim($row[1] ?? ''),
                        'customer_type' => trim($row[2] ?? ''),
                        'phone' => trim($row[3] ?? ''),
                        'email' => trim($row[4] ?? ''),
                        'address' => trim($row[5] ?? ''),
                        'city' => trim($row[6] ?? ''),
                        'area' => trim($row[7] ?? ''),
                        'credit_limit' => is_numeric($row[8] ?? 0) ? floatval($row[8]) : 0,
                        'status' => trim($row[9] ?? 'active'),
                        'notes' => trim($row[10] ?? ''),
                    ];

                    // التحقق من البيانات المطلوبة
                    $validator = Validator::make($customerData, [
                        'customer_code' => 'required|string|max:50|unique:customers,customer_code',
                        'name' => 'required|string|max:255',
                        'customer_type' => 'required|in:pharmacy,hospital,clinic,distributor,wholesaler,individual',
                        'phone' => 'nullable|string|max:20',
                        'email' => 'nullable|email|max:255',
                        'address' => 'nullable|string',
                        'city' => 'nullable|string|max:100',
                        'area' => 'nullable|string|max:100',
                        'credit_limit' => 'nullable|numeric|min:0',
                        'status' => 'required|in:active,inactive',
                        'notes' => 'nullable|string',
                    ]);

                    if ($validator->fails()) {
                        $errorCount++;
                        $errors[] = "الصف {$rowNumber}: " . implode(', ', $validator->errors()->all());
                        continue;
                    }

                    // التحقق من التكرار
                    if (Customer::where('customer_code', $customerData['customer_code'])->exists()) {
                        $duplicates[] = "الصف {$rowNumber}: رمز العميل {$customerData['customer_code']} موجود مسبقاً";
                        $errorCount++;
                        continue;
                    }

                    // إنشاء العميل
                    Customer::create($customerData);
                    $successCount++;

                } catch (\Exception $e) {
                    $errorCount++;
                    $errors[] = "الصف {$rowNumber}: خطأ في المعالجة - " . $e->getMessage();
                }
            }

            DB::commit();

            // إعداد رسالة النتيجة
            $message = "تم استيراد {$successCount} عميل بنجاح";
            if ($errorCount > 0) {
                $message .= " مع {$errorCount} خطأ";
            }

            $result = [
                'success' => $successCount,
                'errors' => $errorCount,
                'error_details' => array_merge($errors, $duplicates),
                'message' => $message
            ];

            return back()->with('import_result', $result);

        } catch (\Exception $e) {
            DB::rollback();
            return back()->withErrors(['error' => 'حدث خطأ أثناء استيراد الملف: ' . $e->getMessage()]);
        }
    }

    /**
     * تصدير العملاء إلى Excel
     */
    public function export(Request $request)
    {
        try {
            $query = Customer::query();

            // تطبيق الفلاتر
            if ($request->has('customer_type') && $request->customer_type) {
                $query->where('customer_type', $request->customer_type);
            }

            if ($request->has('status') && $request->status) {
                $query->where('status', $request->status);
            }

            if ($request->has('city') && $request->city) {
                $query->where('city', 'like', '%' . $request->city . '%');
            }

            $customers = $query->orderBy('customer_name')->get();

            $spreadsheet = new Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();

            // العناوين
            $headers = [
                'A1' => 'رمز العميل',
                'B1' => 'اسم العميل',
                'C1' => 'نوع العميل',
                'D1' => 'رقم الهاتف',
                'E1' => 'البريد الإلكتروني',
                'F1' => 'العنوان',
                'G1' => 'المدينة',
                'H1' => 'البلد',
                'I1' => 'حد الائتمان',
                'J1' => 'الرصيد المستحق',
                'K1' => 'الحالة',
                'L1' => 'تاريخ الإنشاء',
                'M1' => 'ملاحظات',
            ];

            foreach ($headers as $cell => $header) {
                $sheet->setCellValue($cell, $header);
                $sheet->getStyle($cell)->getFont()->setBold(true);
                $sheet->getStyle($cell)->getFill()
                    ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                    ->getStartColor()->setRGB('E3F2FD');
            }

            // البيانات
            $row = 2;
            foreach ($customers as $customer) {
                $sheet->setCellValue('A' . $row, $customer->customer_code);
                $sheet->setCellValue('B' . $row, $customer->customer_name);
                $sheet->setCellValue('C' . $row, $customer->customer_type);
                $sheet->setCellValue('D' . $row, $customer->phone);
                $sheet->setCellValue('E' . $row, $customer->email);
                $sheet->setCellValue('F' . $row, $customer->address);
                $sheet->setCellValue('G' . $row, $customer->city);
                $sheet->setCellValue('H' . $row, $customer->country);
                $sheet->setCellValue('I' . $row, $customer->credit_limit);
                $sheet->setCellValue('J' . $row, $customer->outstanding_balance ?? 0);
                $sheet->setCellValue('K' . $row, $customer->status);
                $sheet->setCellValue('L' . $row, $customer->created_at->format('Y-m-d'));
                $sheet->setCellValue('M' . $row, $customer->notes);
                $row++;
            }

            // تنسيق الأعمدة
            foreach (range('A', 'M') as $col) {
                $sheet->getColumnDimension($col)->setAutoSize(true);
            }

            $writer = new Xlsx($spreadsheet);
            $filename = 'العملاء_' . date('Y-m-d_H-i-s') . '.xlsx';

            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment;filename="' . $filename . '"');
            header('Cache-Control: max-age=0');

            $writer->save('php://output');
            exit;

        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'حدث خطأ أثناء تصدير البيانات: ' . $e->getMessage()]);
        }
    }

    /**
     * معاينة ملف Excel قبل الاستيراد
     */
    public function preview(Request $request)
    {
        try {
            $request->validate([
                'excel_file' => 'required|file|mimes:xlsx,xls|max:10240',
            ]);

            $file = $request->file('excel_file');
            $spreadsheet = IOFactory::load($file->getPathname());
            $sheet = $spreadsheet->getActiveSheet();
            $rows = $sheet->toArray();

            // أخذ أول 10 صفوف للمعاينة
            $headers = array_shift($rows);
            $previewRows = array_slice($rows, 0, 10);

            return response()->json([
                'success' => true,
                'headers' => $headers,
                'rows' => $previewRows,
                'total_rows' => count($rows)
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => 'حدث خطأ أثناء معاينة الملف: ' . $e->getMessage()
            ]);
        }
    }
}
