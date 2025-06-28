<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Services\PermissionService;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\UsersExport;
use App\Imports\UsersImport;

class UserController extends Controller
{
    public function index()
    {
        $users = User::orderBy('created_at', 'desc')->paginate(15);
        return view('users.index', compact('users'));
    }

    public function show($id)
    {
        $user = User::findOrFail($id);
        return view('users.show', compact('user'));
    }

    public function create()
    {
        try {
            $roles = Role::all();
            $groupedPermissions = PermissionService::getGroupedPermissions();
            return view('users.create', compact('roles', 'groupedPermissions'));
        } catch (\Exception $e) {
            return redirect()->route('users.index')->withErrors(['error' => 'حدث خطأ أثناء تحميل صفحة إنشاء المستخدم: ' . $e->getMessage()]);
        }
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'user_type' => 'required|in:admin,employee,customer',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'company_name' => 'nullable|string|max:255',
            'tax_number' => 'nullable|string|max:50',
            // 'roles' => 'nullable|array',
            // 'roles.*' => 'exists:roles,name',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'user_type' => $request->user_type,
            'phone' => $request->phone,
            'address' => $request->address,
            'company_name' => $request->company_name,
            'tax_number' => $request->tax_number,
            'status' => 'active',
        ]);

        // تعيين الأدوار والصلاحيات
        try {
            if ($request->roles || $request->permissions) {
                PermissionService::assignPermissionsToUser(
                    $user,
                    $request->permissions ?? [],
                    $request->roles ?? []
                );
            }
        } catch (\Exception $e) {
            // في حالة فشل تعيين الصلاحيات، نسجل الخطأ ولكن لا نمنع إنشاء المستخدم
            \Log::warning('فشل في تعيين الصلاحيات للمستخدم: ' . $e->getMessage());
        }

        return redirect()->route('users.index')
            ->with('success', 'تم إنشاء المستخدم بنجاح');
    }

    public function edit($id)
    {
        try {
            $user = User::findOrFail($id);
            $roles = Role::all();
            $groupedPermissions = PermissionService::getGroupedPermissions();
            $userPermissions = PermissionService::getUserPermissions($user);

            return view('users.edit', compact('user', 'roles', 'groupedPermissions', 'userPermissions'));
        } catch (\Exception $e) {
            return redirect()->route('users.index')->withErrors(['error' => 'حدث خطأ أثناء تحميل بيانات المستخدم: ' . $e->getMessage()]);
        }
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $id,
            'password' => 'nullable|string|min:8|confirmed',
            'user_type' => 'required|in:admin,employee,customer',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'company_name' => 'nullable|string|max:255',
            'tax_number' => 'nullable|string|max:50',
            'status' => 'required|in:active,inactive',
            // 'roles' => 'nullable|array',
            // 'roles.*' => 'exists:roles,name',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $updateData = [
            'name' => $request->name,
            'email' => $request->email,
            'user_type' => $request->user_type,
            'phone' => $request->phone,
            'address' => $request->address,
            'company_name' => $request->company_name,
            'tax_number' => $request->tax_number,
            'status' => $request->status,
        ];

        // تحديث كلمة المرور إذا تم إدخالها
        if ($request->password) {
            $updateData['password'] = Hash::make($request->password);
        }

        $user->update($updateData);

        // تحديث الأدوار والصلاحيات
        try {
            if ($request->has('roles') || $request->has('permissions')) {
                PermissionService::assignPermissionsToUser(
                    $user,
                    $request->permissions ?? [],
                    $request->roles ?? []
                );
            }
        } catch (\Exception $e) {
            // في حالة فشل تحديث الصلاحيات، نسجل الخطأ ولكن لا نمنع تحديث المستخدم
            \Log::warning('فشل في تحديث الصلاحيات للمستخدم: ' . $e->getMessage());
        }

        return redirect()->route('users.show', $user->id)
            ->with('success', 'تم تحديث المستخدم بنجاح');
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);

        // منع حذف المستخدم الحالي
        if ($user->id === auth()->id()) {
            return back()->withErrors(['error' => 'لا يمكنك حذف حسابك الخاص']);
        }

        // التحقق من عدم وجود طلبات مرتبطة
        if ($user->orders()->count() > 0 || $user->invoices()->count() > 0) {
            return back()->withErrors(['error' => 'لا يمكن حذف المستخدم لوجود بيانات مرتبطة به']);
        }

        $user->delete();

        return redirect()->route('users.index')
            ->with('success', 'تم حذف المستخدم بنجاح');
    }

    public function search(Request $request)
    {
        $query = $request->get('q');

        $users = User::where('name', 'like', "%{$query}%")
            ->orWhere('email', 'like', "%{$query}%")
            ->orWhere('phone', 'like', "%{$query}%")
            ->orWhere('company_name', 'like', "%{$query}%")
            ->limit(10)
            ->get();

        return response()->json($users);
    }

    /**
     * تحميل نموذج Excel للمستخدمين
     */
    public function downloadTemplate()
    {
        $headers = [
            'الاسم الكامل',
            'البريد الإلكتروني',
            'كلمة المرور',
            'نوع المستخدم',
            'رقم الهاتف',
            'العنوان',
            'اسم الشركة',
            'الرقم الضريبي',
            'الحالة'
        ];

        $sampleData = [
            [
                'أحمد محمد علي',
                'ahmed@example.com',
                'password123',
                'employee',
                '07901234567',
                'بغداد - الكرادة',
                'شركة الأدوية المتحدة',
                'TAX123456',
                'active'
            ],
            [
                'فاطمة حسن',
                'fatima@example.com',
                'password456',
                'customer',
                '07907654321',
                'البصرة - المعقل',
                'صيدلية النور',
                'TAX789012',
                'active'
            ]
        ];

        // إنشاء ملف Excel
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // إضافة Headers
        $column = 'A';
        foreach ($headers as $header) {
            $sheet->setCellValue($column . '1', $header);
            $sheet->getStyle($column . '1')->getFont()->setBold(true);
            $sheet->getColumnDimension($column)->setAutoSize(true);
            $column++;
        }

        // إضافة البيانات النموذجية
        $row = 2;
        foreach ($sampleData as $data) {
            $column = 'A';
            foreach ($data as $value) {
                $sheet->setCellValue($column . $row, $value);
                $column++;
            }
            $row++;
        }

        // إضافة تعليقات
        $sheet->setCellValue('A' . ($row + 1), 'ملاحظات:');
        $sheet->setCellValue('A' . ($row + 2), '- نوع المستخدم: admin, manager, employee, customer');
        $sheet->setCellValue('A' . ($row + 3), '- الحالة: active, inactive');
        $sheet->setCellValue('A' . ($row + 4), '- كلمة المرور يجب أن تكون 8 أحرف على الأقل');

        $writer = new Xlsx($spreadsheet);

        $filename = 'نموذج_المستخدمين_' . date('Y-m-d') . '.xlsx';
        $tempFile = tempnam(sys_get_temp_dir(), 'users_template');
        $writer->save($tempFile);

        return response()->download($tempFile, $filename)->deleteFileAfterSend(true);
    }

    /**
     * استيراد المستخدمين من ملف Excel
     */
    public function import(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'excel_file' => 'required|file|mimes:xlsx,xls|max:5120',
            'skip_duplicates' => 'boolean',
            'send_notifications' => 'boolean'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'ملف غير صحيح. يجب أن يكون Excel ولا يتجاوز 5MB'
            ]);
        }

        try {
            $file = $request->file('excel_file');
            $spreadsheet = IOFactory::load($file->getPathname());
            $sheet = $spreadsheet->getActiveSheet();
            $data = $sheet->toArray();

            if (count($data) < 2) {
                return response()->json([
                    'success' => false,
                    'message' => 'الملف فارغ أو لا يحتوي على بيانات'
                ]);
            }

            $headers = $data[0];
            $rows = array_slice($data, 1);

            $imported = 0;
            $skipped = 0;
            $errors = [];

            foreach ($rows as $index => $row) {
                $rowNumber = $index + 2;

                // تجاهل الصفوف الفارغة
                if (empty(array_filter($row))) {
                    continue;
                }

                try {
                    $userData = [
                        'name' => $row[0] ?? '',
                        'email' => $row[1] ?? '',
                        'password' => $row[2] ?? '',
                        'user_type' => $row[3] ?? 'customer',
                        'phone' => $row[4] ?? null,
                        'address' => $row[5] ?? null,
                        'company_name' => $row[6] ?? null,
                        'tax_number' => $row[7] ?? null,
                        'status' => $row[8] ?? 'active'
                    ];

                    // التحقق من البيانات المطلوبة
                    if (empty($userData['name']) || empty($userData['email']) || empty($userData['password'])) {
                        $errors[] = "الصف {$rowNumber}: بيانات مطلوبة مفقودة (الاسم، البريد، كلمة المرور)";
                        continue;
                    }

                    // التحقق من وجود المستخدم
                    if (User::where('email', $userData['email'])->exists()) {
                        if ($request->skip_duplicates) {
                            $skipped++;
                            continue;
                        } else {
                            $errors[] = "الصف {$rowNumber}: البريد الإلكتروني موجود مسبقاً";
                            continue;
                        }
                    }

                    // إنشاء المستخدم
                    User::create([
                        'name' => $userData['name'],
                        'email' => $userData['email'],
                        'password' => Hash::make($userData['password']),
                        'user_type' => in_array($userData['user_type'], ['admin', 'manager', 'employee', 'customer'])
                                     ? $userData['user_type'] : 'customer',
                        'phone' => $userData['phone'],
                        'address' => $userData['address'],
                        'company_name' => $userData['company_name'],
                        'tax_number' => $userData['tax_number'],
                        'status' => in_array($userData['status'], ['active', 'inactive'])
                                  ? $userData['status'] : 'active'
                    ]);

                    $imported++;

                } catch (\Exception $e) {
                    $errors[] = "الصف {$rowNumber}: " . $e->getMessage();
                }
            }

            $message = "تم استيراد {$imported} مستخدم بنجاح";
            if ($skipped > 0) {
                $message .= "، تم تجاهل {$skipped} مستخدم مكرر";
            }
            if (!empty($errors)) {
                $message .= "، " . count($errors) . " خطأ";
            }

            return response()->json([
                'success' => true,
                'message' => $message,
                'details' => [
                    'imported' => $imported,
                    'skipped' => $skipped,
                    'errors' => $errors
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء معالجة الملف: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * تصدير المستخدمين
     */
    public function export(Request $request)
    {
        $format = $request->get('format', 'excel');

        $users = User::with('roles')->get();

        if ($format === 'excel') {
            return $this->exportToExcel($users);
        } elseif ($format === 'pdf') {
            return $this->exportToPdf($users);
        } elseif ($format === 'csv') {
            return $this->exportToCsv($users);
        }

        return back()->withErrors(['error' => 'صيغة التصدير غير مدعومة']);
    }

    /**
     * تصدير إلى Excel
     */
    private function exportToExcel($users)
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Headers
        $headers = [
            'الرقم', 'الاسم الكامل', 'البريد الإلكتروني', 'نوع المستخدم',
            'رقم الهاتف', 'اسم الشركة', 'الرقم الضريبي', 'الحالة',
            'تاريخ التسجيل', 'آخر تحديث'
        ];

        $column = 'A';
        foreach ($headers as $header) {
            $sheet->setCellValue($column . '1', $header);
            $sheet->getStyle($column . '1')->getFont()->setBold(true);
            $sheet->getColumnDimension($column)->setAutoSize(true);
            $column++;
        }

        // البيانات
        $row = 2;
        foreach ($users as $user) {
            $sheet->setCellValue('A' . $row, $user->id);
            $sheet->setCellValue('B' . $row, $user->name);
            $sheet->setCellValue('C' . $row, $user->email);
            $sheet->setCellValue('D' . $row, $user->user_type);
            $sheet->setCellValue('E' . $row, $user->phone);
            $sheet->setCellValue('F' . $row, $user->company_name);
            $sheet->setCellValue('G' . $row, $user->tax_number);
            $sheet->setCellValue('H' . $row, $user->status);
            $sheet->setCellValue('I' . $row, $user->created_at->format('Y-m-d H:i'));
            $sheet->setCellValue('J' . $row, $user->updated_at->format('Y-m-d H:i'));
            $row++;
        }

        $writer = new Xlsx($spreadsheet);
        $filename = 'المستخدمين_' . date('Y-m-d_H-i-s') . '.xlsx';
        $tempFile = tempnam(sys_get_temp_dir(), 'users_export');
        $writer->save($tempFile);

        return response()->download($tempFile, $filename)->deleteFileAfterSend(true);
    }

    /**
     * تصدير إلى CSV
     */
    private function exportToCsv($users)
    {
        $filename = 'المستخدمين_' . date('Y-m-d_H-i-s') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() use ($users) {
            $file = fopen('php://output', 'w');

            // إضافة BOM للدعم العربي
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));

            // Headers
            fputcsv($file, [
                'الرقم', 'الاسم الكامل', 'البريد الإلكتروني', 'نوع المستخدم',
                'رقم الهاتف', 'اسم الشركة', 'الرقم الضريبي', 'الحالة',
                'تاريخ التسجيل', 'آخر تحديث'
            ]);

            // البيانات
            foreach ($users as $user) {
                fputcsv($file, [
                    $user->id,
                    $user->name,
                    $user->email,
                    $user->user_type,
                    $user->phone,
                    $user->company_name,
                    $user->tax_number,
                    $user->status,
                    $user->created_at->format('Y-m-d H:i'),
                    $user->updated_at->format('Y-m-d H:i')
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * تصدير إلى PDF
     */
    private function exportToPdf($users)
    {
        // سيتم تنفيذها لاحقاً باستخدام مكتبة PDF
        return back()->withErrors(['error' => 'تصدير PDF غير متاح حالياً']);
    }
}
