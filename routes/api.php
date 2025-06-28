<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\ItemController;
use App\Http\Controllers\Api\InvoiceController;
use App\Http\Controllers\Api\CollectionController;
use App\Http\Controllers\Api\ReturnController;
use App\Http\Controllers\Api\SupplierController;
use App\Http\Controllers\Api\ReportController;
use App\Http\Controllers\Api\MedicalRepresentativeController;
use App\Http\Controllers\Api\DoctorController;
use App\Http\Controllers\Api\VisitController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// مسارات المصادقة (بدون middleware)
Route::prefix('auth')->group(function () {
    Route::post('register', [AuthController::class, 'register']);
    Route::post('login', [AuthController::class, 'login']);
});

// مسارات محمية بـ Sanctum
Route::middleware('auth:sanctum')->group(function () {
    
    // مسارات المصادقة المحمية
    Route::prefix('auth')->group(function () {
        Route::post('logout', [AuthController::class, 'logout']);
        Route::get('profile', [AuthController::class, 'profile']);
        Route::put('profile', [AuthController::class, 'updateProfile']);
    });

    // مسارات الطلبات
    Route::prefix('orders')->group(function () {
        Route::get('/', [OrderController::class, 'index']);
        Route::post('/', [OrderController::class, 'store']);
        Route::get('/{id}', [OrderController::class, 'show']);
        Route::put('/{id}/status', [OrderController::class, 'updateStatus']);
        Route::post('/{id}/repeat', [OrderController::class, 'repeatOrder']);
    });

    // مسارات العناصر (الأدوية)
    Route::prefix('items')->group(function () {
        Route::get('/', [ItemController::class, 'index']);
        Route::post('/', [ItemController::class, 'store']);
        Route::get('/{id}', [ItemController::class, 'show']);
        Route::put('/{id}', [ItemController::class, 'update']);
        Route::delete('/{id}', [ItemController::class, 'destroy']);
        Route::get('/search/{query}', [ItemController::class, 'search']);
    });

    // مسارات الفواتير
    Route::prefix('invoices')->group(function () {
        Route::get('/', [InvoiceController::class, 'index']);
        Route::get('/{id}', [InvoiceController::class, 'show']);
        Route::put('/{id}', [InvoiceController::class, 'update']);
    });

    // مسارات التحصيلات
    Route::prefix('collections')->group(function () {
        Route::get('/', [CollectionController::class, 'index']);
        Route::post('/', [CollectionController::class, 'store']);
        Route::get('/{id}', [CollectionController::class, 'show']);
        Route::put('/{id}', [CollectionController::class, 'update']);
        Route::delete('/{id}', [CollectionController::class, 'destroy']);
    });

    // مسارات المرتجعات
    Route::prefix('returns')->group(function () {
        Route::get('/', [ReturnController::class, 'index']);
        Route::post('/', [ReturnController::class, 'store']);
        Route::get('/{id}', [ReturnController::class, 'show']);
        Route::put('/{id}/status', [ReturnController::class, 'updateStatus']);
    });

    // مسارات الموردين
    Route::prefix('suppliers')->group(function () {
        Route::get('/', [SupplierController::class, 'index']);
        Route::post('/', [SupplierController::class, 'store']);
        Route::get('/{id}', [SupplierController::class, 'show']);
        Route::put('/{id}', [SupplierController::class, 'update']);
        Route::delete('/{id}', [SupplierController::class, 'destroy']);
    });

    // مسارات التقارير
    Route::prefix('reports')->group(function () {
        Route::get('dashboard-stats', [ReportController::class, 'dashboardStats']);
        Route::get('orders', [ReportController::class, 'ordersReport']);
        Route::get('invoices', [ReportController::class, 'invoicesReport']);
        Route::get('collections', [ReportController::class, 'collectionsReport']);
        Route::get('financial', [ReportController::class, 'financialReport']);
    });

    // التقارير المتقدمة
    Route::prefix('advanced-reports')->group(function () {
        Route::get('data-sources', [App\Http\Controllers\Api\AdvancedReportController::class, 'getDataSources']);
        Route::get('report-types', [App\Http\Controllers\Api\AdvancedReportController::class, 'getReportTypes']);
        Route::get('calculation-types', [App\Http\Controllers\Api\AdvancedReportController::class, 'getCalculationTypes']);
        Route::post('create-integrated', [App\Http\Controllers\Api\AdvancedReportController::class, 'createIntegratedReport']);
        Route::post('sales-integrated', [App\Http\Controllers\Api\AdvancedReportController::class, 'salesIntegratedReport']);
        Route::post('financial-performance', [App\Http\Controllers\Api\AdvancedReportController::class, 'financialPerformanceReport']);
    });

    // مسارات المندوبين العلميين
    Route::prefix('medical-rep')->group(function () {
        // Profile and Dashboard
        Route::get('/profile', [MedicalRepresentativeController::class, 'profile']);
        Route::get('/dashboard', [MedicalRepresentativeController::class, 'dashboard']);
        Route::get('/my-doctors', [MedicalRepresentativeController::class, 'myDoctors']);
        Route::get('/my-targets', [MedicalRepresentativeController::class, 'myTargets']);

        // Doctors Management
        Route::apiResource('doctors', DoctorController::class);

        // Visits Management
        Route::apiResource('visits', VisitController::class);
        Route::post('/visits/{visit}/create-order', [VisitController::class, 'createOrder']);
        Route::post('/visits/{visit}/upload-attachment', [VisitController::class, 'uploadAttachment']);

        // Additional endpoints
        Route::get('/doctors/{doctor}/visits', [DoctorController::class, 'doctorVisits']);
        Route::get('/visits/upcoming', [VisitController::class, 'upcomingVisits']);
        Route::get('/visits/today', [VisitController::class, 'todayVisits']);
    });
});

// مسار للحصول على معلومات المستخدم المصادق عليه
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// اختبار API
Route::get('test', function () {
    return response()->json(['message' => 'API يعمل بشكل صحيح']);
});

// التقارير المتقدمة (بدون مصادقة للاختبار)
Route::prefix('advanced-reports')->group(function () {
    Route::get('test', function () {
        return response()->json(['message' => 'Advanced Reports API يعمل']);
    });

    Route::get('data-sources', function () {
        return response()->json([
            'success' => true,
            'data' => [
                'orders' => [
                    'name' => 'الطلبات',
                    'table' => 'orders',
                    'fields' => [
                        'id' => 'رقم الطلب',
                        'order_number' => 'رقم الطلب',
                        'customer_id' => 'العميل',
                        'status' => 'الحالة',
                        'total_amount' => 'المجموع الكلي',
                        'created_at' => 'تاريخ الإنشاء'
                    ]
                ],
                'customers' => [
                    'name' => 'العملاء',
                    'table' => 'users',
                    'fields' => [
                        'id' => 'رقم العميل',
                        'name' => 'الاسم',
                        'email' => 'البريد الإلكتروني',
                        'phone' => 'الهاتف',
                        'company_name' => 'اسم الشركة'
                    ]
                ],
                'invoices' => [
                    'name' => 'الفواتير',
                    'table' => 'invoices',
                    'fields' => [
                        'id' => 'رقم الفاتورة',
                        'invoice_number' => 'رقم الفاتورة',
                        'total_amount' => 'المبلغ الإجمالي',
                        'paid_amount' => 'المبلغ المدفوع',
                        'status' => 'الحالة'
                    ]
                ],
                'collections' => [
                    'name' => 'التحصيلات',
                    'table' => 'collections',
                    'fields' => [
                        'id' => 'رقم التحصيل',
                        'collection_number' => 'رقم التحصيل',
                        'amount' => 'المبلغ',
                        'payment_method' => 'طريقة الدفع',
                        'collection_date' => 'تاريخ التحصيل'
                    ]
                ]
            ]
        ]);
    });

    Route::get('report-types', function () {
        return response()->json([
            'success' => true,
            'data' => [
                'simple' => 'تقرير بسيط',
                'integrated' => 'تقرير متداخل',
                'analytical' => 'تقرير تحليلي'
            ]
        ]);
    });

    Route::get('calculation-types', function () {
        return response()->json([
            'success' => true,
            'data' => [
                'sum' => 'المجموع',
                'avg' => 'المتوسط',
                'count' => 'العدد',
                'min' => 'الحد الأدنى',
                'max' => 'الحد الأقصى'
            ]
        ]);
    });

    Route::post('create-integrated', function (Illuminate\Http\Request $request) {
        try {
            // محاكاة إنشاء تقرير متداخل
            $dataSources = $request->input('data_sources', []);
            $columns = $request->input('columns', []);
            $filters = $request->input('filters', []);
            $calculations = $request->input('calculations', []);

            // بناء استعلام تجريبي
            $query = \App\Models\Order::query();

            if (in_array('customers', $dataSources)) {
                $query->join('users', 'orders.customer_id', '=', 'users.id');
            }

            if (in_array('invoices', $dataSources)) {
                $query->leftJoin('invoices', 'orders.id', '=', 'invoices.order_id');
            }

            if (in_array('collections', $dataSources)) {
                $query->leftJoin('collections', 'invoices.id', '=', 'collections.invoice_id');
            }

            // تطبيق الفلاتر
            foreach ($filters as $filter) {
                if ($filter['operator'] === 'date_range' && isset($filter['value']['start'])) {
                    $query->whereBetween('orders.created_at', [
                        $filter['value']['start'],
                        $filter['value']['end'] ?? now()
                    ]);
                }
            }

            // تحديد الأعمدة
            $selectColumns = ['orders.id'];
            foreach ($columns as $column) {
                $table = $column['source'] ?? 'orders';
                $field = $column['field'];
                $selectColumns[] = "{$table}.{$field} as {$column['alias']}";
            }

            $results = $query->select($selectColumns)->limit(100)->get();

            // حساب الإحصائيات
            $statistics = [];
            foreach ($calculations as $calc) {
                $table = $calc['source'] ?? 'orders';
                $field = $calc['field'];

                switch ($calc['type']) {
                    case 'sum':
                        $value = \App\Models\Order::sum($field);
                        break;
                    case 'avg':
                        $value = \App\Models\Order::avg($field);
                        break;
                    case 'count':
                        $value = \App\Models\Order::count();
                        break;
                    default:
                        $value = 0;
                }

                $statistics[] = [
                    'label' => $calc['alias'],
                    'value' => $value,
                    'type' => $calc['type']
                ];
            }

            return response()->json([
                'success' => true,
                'data' => [
                    'results' => $results,
                    'statistics' => $statistics,
                    'total_records' => $results->count(),
                    'query_info' => [
                        'data_sources' => $dataSources,
                        'columns_count' => count($columns),
                        'filters_count' => count($filters)
                    ]
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    });

    Route::post('export-excel', function (Illuminate\Http\Request $request) {
        try {
            // الحصول على بيانات التقرير
            $dataSources = $request->input('data_sources', []);
            $columns = $request->input('columns', []);
            $filters = $request->input('filters', []);
            $calculations = $request->input('calculations', []);
            $results = $request->input('results', []);
            $statistics = $request->input('statistics', []);

            // إنشاء ملف Excel
            $filename = 'تقرير_متداخل_' . date('Y-m-d_H-i-s') . '.xlsx';
            $filepath = storage_path('app/public/exports/' . $filename);

            // التأكد من وجود المجلد
            if (!file_exists(dirname($filepath))) {
                mkdir(dirname($filepath), 0755, true);
            }

            // إنشاء محتوى CSV بسيط (يمكن تحسينه لاحقاً)
            $csvContent = "\xEF\xBB\xBF"; // UTF-8 BOM

            // إضافة عنوان التقرير
            $csvContent .= "تقرير متداخل - " . date('Y-m-d H:i:s') . "\n\n";

            // إضافة معلومات التقرير
            $csvContent .= "مصادر البيانات: " . implode(', ', $dataSources) . "\n";
            $csvContent .= "عدد الأعمدة: " . count($columns) . "\n";
            $csvContent .= "عدد الفلاتر: " . count($filters) . "\n\n";

            // إضافة رؤوس الأعمدة
            $headers = [];
            foreach ($columns as $column) {
                $headers[] = $column['alias'] ?? $column['field'];
            }
            $csvContent .= implode(',', $headers) . "\n";

            // إضافة البيانات
            foreach ($results as $row) {
                $rowData = [];
                foreach ($columns as $column) {
                    $alias = $column['alias'] ?? $column['field'];
                    $value = $row[$alias] ?? '';
                    // تنظيف القيم للـ CSV
                    $value = str_replace(['"', ',', "\n", "\r"], ['""', '،', ' ', ' '], $value);
                    $rowData[] = '"' . $value . '"';
                }
                $csvContent .= implode(',', $rowData) . "\n";
            }

            // إضافة الإحصائيات
            if (!empty($statistics)) {
                $csvContent .= "\n\nالإحصائيات:\n";
                foreach ($statistics as $stat) {
                    $csvContent .= $stat['label'] . ',' . $stat['value'] . "\n";
                }
            }

            // حفظ الملف
            file_put_contents($filepath, $csvContent);

            // إرجاع رابط التحميل
            $downloadUrl = url('storage/exports/' . $filename);

            return response()->json([
                'success' => true,
                'download_url' => $downloadUrl,
                'filename' => $filename,
                'message' => 'تم إنشاء ملف Excel بنجاح'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    });
});
