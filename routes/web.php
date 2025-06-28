<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Web\AuthController;
use App\Http\Controllers\Web\DashboardController;
use App\Http\Controllers\Web\OrderController;
use App\Http\Controllers\Web\ItemController;
use App\Http\Controllers\Web\InvoiceController;
use App\Http\Controllers\Web\CollectionController;
use App\Http\Controllers\Web\SupplierController;
use App\Http\Controllers\Web\ReturnController;
use App\Http\Controllers\Web\ReportController;
use App\Http\Controllers\Web\UserController;
use App\Http\Controllers\Web\WarehouseController;
use App\Http\Controllers\Web\HRController;
use App\Http\Controllers\Web\MedicalRepManagementController;
use App\Http\Controllers\Web\CustomerController;
use App\Http\Controllers\Web\FinanceController;
use App\Http\Controllers\LanguageController;
use App\Http\Controllers\Web\PermissionController;
use App\Http\Controllers\Web\RegulatoryAffairsController;

// مسارات تغيير اللغة
Route::get('/language/{locale}', [LanguageController::class, 'switch'])->name('language.switch');
Route::get('/api/languages', [LanguageController::class, 'getLanguages'])->name('language.list');
Route::get('/api/current-language', [LanguageController::class, 'getCurrentLanguage'])->name('language.current');
Route::get('/api/translations/{locale?}', [LanguageController::class, 'getTranslations'])->name('api.translations');
Route::get('/language-test', function () {
    return view('language-test');
})->name('language.test');

Route::get('/test-permissions', function () {
    return view('test-permissions');
})->name('test.permissions')->middleware('auth');



Route::get('/test-invoices', function () {
    $invoices = \App\Models\Invoice::with(['customer', 'order'])->paginate(15);
    return view('invoices.index', compact('invoices'));
})->name('test.invoices')->middleware('auth');

Route::get('/debug-invoices', function () {
    return response()->json([
        'invoices_count' => \App\Models\Invoice::count(),
        'customers_count' => \App\Models\Customer::count(),
        'orders_count' => \App\Models\Order::count(),
        'route_exists' => \Route::has('invoices.index'),
        'controller_exists' => class_exists(\App\Http\Controllers\Web\InvoiceController::class),
    ]);
})->name('debug.invoices');

Route::get('/test-warehouse-transfers', function () {
    $warehouses = \App\Models\Warehouse::where('status', 'active')->get();
    return view('warehouses.transfers', compact('warehouses'));
})->name('test.warehouse.transfers');

Route::get('/test-collection-document/{id}', function ($id) {
    try {
        $collection = \App\Models\Collection::with(['customer', 'invoice', 'collectedBy'])->findOrFail($id);

        $documentService = new \App\Services\SimpleCollectionService();
        $result = $documentService->generateCollectionDocument($collection);

        if (!$result['success']) {
            return response()->json(['error' => $result['error']], 500);
        }

        return response()->json([
            'success' => true,
            'filename' => $result['filename'],
            'url' => $result['full_url']
        ]);

    } catch (\Exception $e) {
        return response()->json(['error' => $e->getMessage()], 500);
    }
})->name('test.collection.document');

// مسار التحقق من الفواتير (متاح للجميع)
Route::get('/invoices/{id}/verify', [\App\Http\Controllers\Web\InvoiceController::class, 'verify'])->name('invoices.verify');

// إعادة توجيه الصفحة الرئيسية إلى تسجيل الدخول
Route::get('/', function () {
    return redirect()->route('login');
});

// مسارات المصادقة
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

// مسارات محمية
Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // مسارات الطلبات
    Route::prefix('orders')->name('orders.')->group(function () {
        Route::get('/', [OrderController::class, 'index'])->name('index');
        Route::get('/create', [OrderController::class, 'create'])->name('create');
        Route::post('/', [OrderController::class, 'store'])->name('store');
        Route::get('/{id}', [OrderController::class, 'show'])->name('show');
        Route::put('/{id}/status', [OrderController::class, 'updateStatus'])->name('updateStatus');
        Route::get('/{id}/repeat', [OrderController::class, 'repeat'])->name('repeat');
    });

    // مسارات العناصر
    Route::prefix('items')->name('items.')->group(function () {
        Route::get('/', [ItemController::class, 'index'])->name('index');
        Route::get('/create', [ItemController::class, 'create'])->name('create');
        Route::post('/', [ItemController::class, 'store'])->name('store');
        Route::get('/low-stock', [ItemController::class, 'lowStock'])->name('low-stock');
        Route::get('/search', [ItemController::class, 'search'])->name('search');
        Route::get('/import/form', [ItemController::class, 'importForm'])->name('import.form');
        Route::post('/import', [ItemController::class, 'import'])->name('import');
        Route::get('/sample/download', [ItemController::class, 'downloadSample'])->name('sample');
        Route::get('/{id}', [ItemController::class, 'show'])->name('show');
        Route::get('/{id}/edit', [ItemController::class, 'edit'])->name('edit');
        Route::put('/{id}', [ItemController::class, 'update'])->name('update');
        Route::delete('/{id}', [ItemController::class, 'destroy'])->name('destroy');
        Route::get('/{id}/details', [ItemController::class, 'details'])->name('details');
        Route::post('/{id}/add-stock', [ItemController::class, 'addStock'])->name('add-stock');
        Route::get('/export', [ItemController::class, 'export'])->name('export');
    });

    // مسارات الفواتير
    Route::prefix('invoices')->name('invoices.')->group(function () {
        Route::get('/', [InvoiceController::class, 'index'])->name('index');
        Route::get('/create', [InvoiceController::class, 'create'])->name('create');
        Route::post('/', [InvoiceController::class, 'store'])->name('store');
        Route::get('/pending', [InvoiceController::class, 'pending'])->name('pending');
        Route::get('/paid', [InvoiceController::class, 'paid'])->name('paid');
        Route::get('/overdue', [InvoiceController::class, 'overdue'])->name('overdue');
        Route::get('/export', [InvoiceController::class, 'export'])->name('export');
        Route::get('/{id}', [InvoiceController::class, 'show'])->name('show');
        Route::get('/{id}/edit', [InvoiceController::class, 'edit'])->name('edit');
        Route::put('/{id}', [InvoiceController::class, 'update'])->name('update');
        Route::delete('/{id}', [InvoiceController::class, 'destroy'])->name('destroy');
        Route::post('/{id}/mark-paid', [InvoiceController::class, 'markAsPaid'])->name('markAsPaid');
        Route::get('/{id}/print', [InvoiceController::class, 'print'])->name('print');

        Route::post('/{id}/send-reminder', [InvoiceController::class, 'sendReminder'])->name('sendReminder');
        Route::post('/send-bulk-reminders', [InvoiceController::class, 'sendBulkReminders'])->name('sendBulkReminders');
    });

    // مسارات التحصيلات
    Route::prefix('collections')->name('collections.')->group(function () {
        Route::get('/', [CollectionController::class, 'index'])->name('index');
        Route::get('/create', [CollectionController::class, 'create'])->name('create');
        Route::post('/', [CollectionController::class, 'store'])->name('store');
        Route::get('/customer-invoices', [CollectionController::class, 'getCustomerInvoices'])->name('customer-invoices');
        Route::get('/{id}/document', [CollectionController::class, 'downloadDocument'])->name('document');
        Route::get('/{id}/document-simple', function($id) {
            try {
                $collection = \App\Models\Collection::with(['customer', 'invoice', 'collectedBy'])->findOrFail($id);
                $service = new \App\Services\SimpleCollectionService();
                $result = $service->generateCollectionDocument($collection);

                if ($result['success']) {
                    return redirect($result['full_url']);
                } else {
                    return response()->json(['error' => $result['error']], 500);
                }
            } catch (\Exception $e) {
                return response()->json(['error' => $e->getMessage()], 500);
            }
        })->name('document-simple');
        Route::get('/{id}', [CollectionController::class, 'show'])->name('show');
        Route::get('/{id}/edit', [CollectionController::class, 'edit'])->name('edit');
        Route::put('/{id}', [CollectionController::class, 'update'])->name('update');
        Route::delete('/{id}', [CollectionController::class, 'destroy'])->name('destroy');
        Route::get('/invoice/{invoiceId}/details', [CollectionController::class, 'getInvoiceDetails'])->name('invoiceDetails');
    });

    // مسارات الموردين
    Route::prefix('suppliers')->name('suppliers.')->group(function () {
        Route::get('/', [SupplierController::class, 'index'])->name('index');
        Route::get('/create', [SupplierController::class, 'create'])->name('create');
        Route::post('/', [SupplierController::class, 'store'])->name('store');
        Route::get('/search', [SupplierController::class, 'search'])->name('search');
        Route::get('/import/form', [SupplierController::class, 'importForm'])->name('import.form');
        Route::post('/import', [SupplierController::class, 'import'])->name('import');
        Route::get('/sample/download', [SupplierController::class, 'downloadSample'])->name('sample');
        Route::get('/{id}', [SupplierController::class, 'show'])->name('show');
        Route::get('/{id}/edit', [SupplierController::class, 'edit'])->name('edit');
        Route::put('/{id}', [SupplierController::class, 'update'])->name('update');
        Route::delete('/{id}', [SupplierController::class, 'destroy'])->name('destroy');
    });

    // مسارات المستخدمين
    Route::prefix('users')->name('users.')->group(function () {
        Route::get('/', [UserController::class, 'index'])->name('index');
        Route::get('/create', [UserController::class, 'create'])->name('create');
        Route::post('/', [UserController::class, 'store'])->name('store');
        Route::get('/search', [UserController::class, 'search'])->name('search');
        Route::get('/{id}', [UserController::class, 'show'])->name('show');
        Route::get('/{id}/edit', [UserController::class, 'edit'])->name('edit');
        Route::put('/{id}', [UserController::class, 'update'])->name('update');
        Route::delete('/{id}', [UserController::class, 'destroy'])->name('destroy');

        // مسارات الاستيراد والتصدير
        Route::get('/template/download', [UserController::class, 'downloadTemplate'])->name('template');
        Route::post('/import', [UserController::class, 'import'])->name('import');
        Route::get('/export', [UserController::class, 'export'])->name('export');
    });

    // مسارات الصلاحيات والأدوار
    Route::prefix('permissions')->name('permissions.')->group(function () {
        Route::get('/', [PermissionController::class, 'index'])->name('index');
        Route::post('/roles', [PermissionController::class, 'createRole'])->name('roles.create');
        Route::put('/roles/{id}', [PermissionController::class, 'updateRole'])->name('roles.update');
        Route::delete('/roles/{id}', [PermissionController::class, 'deleteRole'])->name('roles.delete');
        Route::get('/roles/{id}/permissions', [PermissionController::class, 'getRolePermissions'])->name('roles.permissions');
        Route::post('/users/{userId}/assign', [PermissionController::class, 'assignUserPermissions'])->name('users.assign');
        Route::get('/users/{userId}', [PermissionController::class, 'getUserPermissions'])->name('users.permissions');
        Route::get('/suggestions', [PermissionController::class, 'getSuggestedPermissions'])->name('suggestions');
        Route::get('/users/{userId}/check', [PermissionController::class, 'checkUserPermission'])->name('users.check');
        Route::get('/matrix/export', [PermissionController::class, 'exportPermissionMatrix'])->name('matrix.export');
        Route::get('/stats', [PermissionController::class, 'getPermissionStats'])->name('stats');
        Route::get('/search', [PermissionController::class, 'searchPermissions'])->name('search');
        Route::post('/roles/copy', [PermissionController::class, 'copyRolePermissions'])->name('roles.copy');
    });

    // مسارات المخازن
    Route::prefix('warehouses')->name('warehouses.')->group(function () {
        Route::get('/', [WarehouseController::class, 'index'])->name('index');
        Route::get('/create', [WarehouseController::class, 'create'])->name('create');
        Route::post('/', [WarehouseController::class, 'store'])->name('store');
        Route::get('/{id}', [WarehouseController::class, 'show'])->name('show');
        Route::get('/{id}/edit', [WarehouseController::class, 'edit'])->name('edit');
        Route::put('/{id}', [WarehouseController::class, 'update'])->name('update');
        Route::delete('/{id}', [WarehouseController::class, 'destroy'])->name('destroy');
        Route::get('/{id}/items', [WarehouseController::class, 'items'])->name('items');
        Route::get('/{id}/reports', [WarehouseController::class, 'reports'])->name('reports');

        // مسارات نقل البضائع
        Route::get('/transfers', [WarehouseController::class, 'transfers'])->name('transfers');
        Route::post('/transfers', [WarehouseController::class, 'processTransfer'])->name('process-transfer');
        Route::get('/{id}/items-api', [WarehouseController::class, 'getWarehouseItems'])->name('items-api');

        // التقارير الشاملة
        Route::get('/reports/all', [WarehouseController::class, 'allReports'])->name('all-reports');
    });

    // مسارات التقارير
    Route::prefix('reports')->name('reports.')->group(function () {
        Route::get('/', [ReportController::class, 'index'])->name('index');
        Route::get('/sales', [ReportController::class, 'sales'])->name('sales');
        Route::get('/financial', [ReportController::class, 'financial'])->name('financial');
        Route::get('/inventory', [ReportController::class, 'inventory'])->name('inventory');
        Route::get('/customers', [ReportController::class, 'customers'])->name('customers');
        Route::get('/top-items', [ReportController::class, 'topItems'])->name('topItems');

        // التقارير المخصصة
        Route::get('/custom', [ReportController::class, 'custom'])->name('custom');
        Route::post('/custom/generate', [ReportController::class, 'generateCustom'])->name('custom.generate');
        Route::get('/custom/builder', [ReportController::class, 'customBuilder'])->name('custom.builder');
        Route::post('/custom/save', [ReportController::class, 'saveCustom'])->name('custom.save');
        Route::get('/custom/{id}', [ReportController::class, 'showCustom'])->name('custom.show');
        Route::delete('/custom/{id}', [ReportController::class, 'deleteCustom'])->name('custom.delete');

        Route::post('/sales/export', [ReportController::class, 'exportSales'])->name('exportSales');
        Route::post('/financial/export', [ReportController::class, 'exportFinancial'])->name('exportFinancial');
        Route::post('/inventory/export', [ReportController::class, 'exportInventory'])->name('exportInventory');
        Route::post('/custom/export', [ReportController::class, 'exportCustom'])->name('custom.export');
    });

    // مسارات الموارد البشرية (HR)
    Route::prefix('hr')->name('hr.')->group(function () {
        Route::get('/', [HRController::class, 'index'])->name('index');
        Route::get('/dashboard', [HRController::class, 'dashboard'])->name('dashboard');

        // إدارة الموظفين
        Route::get('/employees', [HRController::class, 'employees'])->name('employees');
        Route::get('/employees/create', [HRController::class, 'createEmployee'])->name('employees.create');
        Route::post('/employees', [HRController::class, 'storeEmployee'])->name('employees.store');

        // إدارة الأقسام
        Route::get('/departments', [HRController::class, 'departments'])->name('departments');
        Route::get('/departments/create', function() { return view('hr.departments.create'); })->name('departments.create');

        // الحضور والانصراف
        Route::get('/attendance', [HRController::class, 'attendance'])->name('attendance');
        Route::get('/attendance/mark', function() { return view('hr.attendance.mark'); })->name('attendance.mark');
        Route::post('/attendance/mark', [HRController::class, 'markAttendance'])->name('attendance.store');

        // إدارة الإجازات
        Route::get('/leaves', [HRController::class, 'leaves'])->name('leaves');
        Route::get('/leaves/create', function() { return view('hr.leaves.create'); })->name('leaves.create');
        Route::post('/leaves/{id}/approve', [HRController::class, 'approveLeave'])->name('leaves.approve');
        Route::post('/leaves/{id}/reject', [HRController::class, 'rejectLeave'])->name('leaves.reject');

        // إدارة الرواتب
        Route::get('/payroll', [HRController::class, 'payroll'])->name('payroll');
        Route::get('/payroll/create', function() { return view('hr.payroll.create'); })->name('payroll.create');

        // تقارير HR
        Route::get('/reports', [HRController::class, 'reports'])->name('reports');
        Route::get('/reports/generate', function() { return view('hr.reports.generate'); })->name('reports.generate');
    });

    // مسارات الزبائن
    Route::prefix('customers')->name('customers.')->group(function () {
        Route::get('/', [CustomerController::class, 'index'])->name('index');
        Route::get('/create', [CustomerController::class, 'create'])->name('create');
        Route::post('/', [CustomerController::class, 'store'])->name('store');
        Route::get('/{customer}', [CustomerController::class, 'show'])->name('show');
        Route::get('/{customer}/edit', [CustomerController::class, 'edit'])->name('edit');
        Route::put('/{customer}', [CustomerController::class, 'update'])->name('update');
        Route::delete('/{customer}', [CustomerController::class, 'destroy'])->name('destroy');

        // استيراد وتصدير الزبائن
        Route::get('/import', [App\Http\Controllers\Web\CustomerImportController::class, 'index'])->name('import');
        Route::get('/import/form', [App\Http\Controllers\Web\CustomerImportController::class, 'index'])->name('import.form');
        Route::get('/import/template', [App\Http\Controllers\Web\CustomerImportController::class, 'downloadTemplate'])->name('import.template');
        Route::get('/template', [App\Http\Controllers\Web\CustomerImportController::class, 'downloadTemplate'])->name('template');
        Route::post('/import/upload', [App\Http\Controllers\Web\CustomerImportController::class, 'import'])->name('import.upload');
        Route::post('/import/preview', [App\Http\Controllers\Web\CustomerImportController::class, 'preview'])->name('import.preview');
        Route::get('/export', [App\Http\Controllers\Web\CustomerImportController::class, 'export'])->name('export');

        // حركات الزبون
        Route::get('/{customer}/transactions', [CustomerController::class, 'transactions'])->name('transactions');
        Route::get('/{customer}/transactions/create', [CustomerController::class, 'createTransaction'])->name('transactions.create');
        Route::post('/{customer}/transactions', [CustomerController::class, 'storeTransaction'])->name('transactions.store');

        // مدفوعات الزبون
        Route::get('/{customer}/payments', [CustomerController::class, 'payments'])->name('payments');
        Route::get('/{customer}/payments/create', [CustomerController::class, 'createPayment'])->name('payments.create');
        Route::post('/{customer}/payments', [CustomerController::class, 'storePayment'])->name('payments.store');

        // تقارير الزبون
        Route::get('/{customer}/reports', [CustomerController::class, 'reports'])->name('reports');
        Route::get('/{customer}/statement', [CustomerController::class, 'statement'])->name('statement');
    });

    // مسارات المندوبين العلميين
    Route::prefix('medical-rep')->name('medical-rep.')->group(function () {
        Route::get('/', [MedicalRepManagementController::class, 'index'])->name('dashboard');

        // إدارة المندوبين
        Route::prefix('representatives')->name('representatives.')->group(function () {
            Route::get('/', [MedicalRepManagementController::class, 'representatives'])->name('index');
            Route::get('/{id}', [MedicalRepManagementController::class, 'representativeDetails'])->name('show');
        });

        // إدارة الأطباء
        Route::prefix('doctors')->name('doctors.')->group(function () {
            Route::get('/', [MedicalRepManagementController::class, 'doctors'])->name('index');

            // استيراد وتصدير الأطباء
            Route::get('/import/form', [MedicalRepManagementController::class, 'doctorsImportForm'])->name('import.form');
            Route::post('/import', [MedicalRepManagementController::class, 'importDoctors'])->name('import');
            Route::get('/template/download', [MedicalRepManagementController::class, 'downloadDoctorsTemplate'])->name('template');
            Route::get('/export', [MedicalRepManagementController::class, 'exportDoctors'])->name('export');

            // الرفع القديم (للتوافق مع النظام السابق)
            Route::post('/upload', [MedicalRepManagementController::class, 'uploadDoctors'])->name('upload');
        });

        // إدارة الزيارات
        Route::prefix('visits')->name('visits.')->group(function () {
            Route::get('/', [MedicalRepManagementController::class, 'visits'])->name('index');
            Route::get('/{id}', [MedicalRepManagementController::class, 'visitDetails'])->name('show');
        });

        // التقارير
        Route::get('/reports', [MedicalRepManagementController::class, 'reports'])->name('reports.index');
    });

    // مسارات النظام المالي
    Route::prefix('finance')->name('finance.')->group(function () {
        Route::get('/', [FinanceController::class, 'dashboard'])->name('index');
        Route::get('/dashboard', [FinanceController::class, 'dashboard'])->name('dashboard');

        // إدارة دليل الحسابات
        Route::prefix('accounts')->name('accounts.')->group(function () {
            Route::get('/', [FinanceController::class, 'accounts'])->name('index');
            Route::get('/create', [FinanceController::class, 'createAccount'])->name('create');
            Route::post('/', [FinanceController::class, 'storeAccount'])->name('store');
            Route::get('/{account}', [FinanceController::class, 'showAccount'])->name('show');
            Route::get('/{account}/edit', [FinanceController::class, 'editAccount'])->name('edit');
            Route::put('/{account}', [FinanceController::class, 'updateAccount'])->name('update');
            Route::delete('/{account}', [FinanceController::class, 'destroyAccount'])->name('destroy');
        });

        // إدارة القيود المحاسبية
        Route::prefix('journal-entries')->name('journal-entries.')->group(function () {
            Route::get('/', [FinanceController::class, 'journalEntries'])->name('index');
            Route::get('/create', [FinanceController::class, 'createJournalEntry'])->name('create');
            Route::post('/', [FinanceController::class, 'storeJournalEntry'])->name('store');
            Route::get('/{entry}', [FinanceController::class, 'showJournalEntry'])->name('show');
            Route::get('/{entry}/edit', [FinanceController::class, 'editJournalEntry'])->name('edit');
            Route::put('/{entry}', [FinanceController::class, 'updateJournalEntry'])->name('update');
            Route::post('/{entry}/post', [FinanceController::class, 'postJournalEntry'])->name('post');
            Route::post('/{entry}/unpost', [FinanceController::class, 'unpostJournalEntry'])->name('unpost');
            Route::delete('/{entry}', [FinanceController::class, 'destroyJournalEntry'])->name('destroy');
        });

        // التقارير المالية
        Route::prefix('reports')->name('reports.')->group(function () {
            Route::get('/', [FinanceController::class, 'reports'])->name('index');
            Route::get('/trial-balance', [FinanceController::class, 'trialBalance'])->name('trial-balance');
            Route::get('/balance-sheet', [FinanceController::class, 'balanceSheet'])->name('balance-sheet');
            Route::get('/income-statement', [FinanceController::class, 'incomeStatement'])->name('income-statement');
            Route::get('/cash-flow', [FinanceController::class, 'cashFlow'])->name('cash-flow');
            Route::get('/account-ledger', [FinanceController::class, 'accountLedger'])->name('account-ledger');
        });

        // إدارة الفترات المالية
        Route::prefix('periods')->name('periods.')->group(function () {
            Route::get('/', [FinanceController::class, 'fiscalPeriods'])->name('index');
            Route::get('/create', [FinanceController::class, 'createFiscalPeriod'])->name('create');
            Route::post('/', [FinanceController::class, 'storeFiscalPeriod'])->name('store');
            Route::post('/{period}/close', [FinanceController::class, 'closeFiscalPeriod'])->name('close');
            Route::post('/{period}/reopen', [FinanceController::class, 'reopenFiscalPeriod'])->name('reopen');
            Route::post('/{period}/set-current', [FinanceController::class, 'setCurrentPeriod'])->name('set-current');
        });

        // API endpoints
        Route::get('/api/accounts', [FinanceController::class, 'getAccounts'])->name('api.accounts');
    });

    // الشؤون التنظيمية (Regulatory Affairs)
    Route::prefix('regulatory-affairs')->name('regulatory-affairs.')->group(function () {
        Route::get('/', [RegulatoryAffairsController::class, 'dashboard'])->name('dashboard');

        // إدارة الشركات
        Route::get('/companies', [RegulatoryAffairsController::class, 'companies'])->name('companies');
        Route::get('/companies/create', [RegulatoryAffairsController::class, 'createCompany'])->name('companies.create');
        Route::post('/companies', [RegulatoryAffairsController::class, 'storeCompany'])->name('companies.store');
        Route::get('/companies/{company}', [RegulatoryAffairsController::class, 'showCompany'])->name('companies.show');

        // إدارة المنتجات الدوائية
        Route::get('/products', [RegulatoryAffairsController::class, 'products'])->name('products');
        Route::get('/products/create', [RegulatoryAffairsController::class, 'createProduct'])->name('products.create');
        Route::post('/products', [RegulatoryAffairsController::class, 'storeProduct'])->name('products.store');
        Route::get('/products/{product}', [RegulatoryAffairsController::class, 'showProduct'])->name('products.show');

        // إدارة إجازات الفحص
        Route::get('/inspection-permits', [RegulatoryAffairsController::class, 'inspectionPermits'])->name('inspection-permits');

        // إدارة إجازات الاستيراد
        Route::get('/import-permits', [RegulatoryAffairsController::class, 'importPermits'])->name('import-permits');
    });

    // الذكاء الاصطناعي (AI)
    Route::prefix('ai')->name('ai.')->group(function () {
        Route::get('/', [App\Http\Controllers\Web\AIController::class, 'dashboard'])->name('dashboard');
        Route::get('/sales-forecasting', [App\Http\Controllers\Web\AIController::class, 'salesForecasting'])->name('sales-forecasting');
        Route::get('/team-development', [App\Http\Controllers\Web\AIController::class, 'teamDevelopment'])->name('team-development');
        Route::get('/sales-development', [App\Http\Controllers\Web\AIController::class, 'salesDevelopment'])->name('sales-development');
        Route::get('/chat', [App\Http\Controllers\Web\AIController::class, 'chat'])->name('chat');
        Route::post('/chat', [App\Http\Controllers\Web\AIController::class, 'chat'])->name('chat.send');
    });

    // مسارات المرتجعات
    Route::prefix('returns')->name('returns.')->group(function () {
        Route::get('/', [ReturnController::class, 'index'])->name('index');
        Route::get('/create', [ReturnController::class, 'create'])->name('create');
        Route::post('/', [ReturnController::class, 'store'])->name('store');
        Route::get('/{return}', [ReturnController::class, 'show'])->name('show');
        Route::get('/{return}/edit', [ReturnController::class, 'edit'])->name('edit');
        Route::put('/{return}', [ReturnController::class, 'update'])->name('update');
        Route::delete('/{return}', [ReturnController::class, 'destroy'])->name('destroy');
        Route::post('/{return}/approve', [ReturnController::class, 'approve'])->name('approve');
        Route::post('/{return}/reject', [ReturnController::class, 'reject'])->name('reject');
    });

    // مسارات النسخ الاحتياطية
    Route::prefix('backup')->name('backup.')->group(function () {
        Route::get('/', [App\Http\Controllers\Web\BackupController::class, 'index'])->name('index');
        Route::get('/restore-guide', function() {
            return view('backup.restore-guide');
        })->name('restore-guide');
        Route::post('/create', [App\Http\Controllers\Web\BackupController::class, 'create'])->name('create');
        Route::get('/download/{filename}', [App\Http\Controllers\Web\BackupController::class, 'download'])->name('download');
        Route::delete('/delete/{filename}', [App\Http\Controllers\Web\BackupController::class, 'delete'])->name('delete');
        Route::post('/restore/{filename}', [App\Http\Controllers\Web\BackupController::class, 'restore'])->name('restore');
        Route::post('/upload', [App\Http\Controllers\Web\BackupController::class, 'upload'])->name('upload');
    });

});

// مسارات المساعدة
Route::prefix('help')->name('help.')->middleware('auth')->group(function () {
    Route::get('/', [App\Http\Controllers\Web\HelpController::class, 'index'])->name('index');
    Route::get('/quick-start', [App\Http\Controllers\Web\HelpController::class, 'quickStart'])->name('quick-start');
    Route::get('/video-tutorial', function() {
        return view('help.video-tutorial');
    })->name('video-tutorial');
    Route::get('/customers', [App\Http\Controllers\Web\HelpController::class, 'customers'])->name('customers');
    Route::get('/inventory', [App\Http\Controllers\Web\HelpController::class, 'inventory'])->name('inventory');
    Route::get('/invoices', [App\Http\Controllers\Web\HelpController::class, 'invoices'])->name('invoices');
    Route::get('/collections', [App\Http\Controllers\Web\HelpController::class, 'collections'])->name('collections');
    Route::get('/warehouses', [App\Http\Controllers\Web\HelpController::class, 'warehouses'])->name('warehouses');
    Route::get('/backups', [App\Http\Controllers\Web\HelpController::class, 'backups'])->name('backups');
    Route::get('/users', [App\Http\Controllers\Web\HelpController::class, 'users'])->name('users');
    Route::get('/faq', [App\Http\Controllers\Web\HelpController::class, 'faq'])->name('faq');
    Route::get('/troubleshooting', [App\Http\Controllers\Web\HelpController::class, 'troubleshooting'])->name('troubleshooting');
    Route::get('/contact', [App\Http\Controllers\Web\HelpController::class, 'contact'])->name('contact');
});

// منشئ التقارير المتقدم
Route::get('/report-builder', function () {
    return view('reports.report-builder');
})->name('report-builder');

// مسارات API للتقارير المتقدمة (بدون CSRF)
Route::prefix('api/advanced-reports')->withoutMiddleware('csrf')->group(function () {
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

    Route::get('create-integrated-test', function (Illuminate\Http\Request $request) {
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

    // مسار لتحميل محتوى منشئ التقارير
    Route::get('builder-content', function () {
        return view('reports.partials.advanced-builder');
    });
});

// مسارات API للبحث في القوائم المنسدلة
Route::prefix('api/search')->group(function () {
    Route::get('customers', function (Illuminate\Http\Request $request) {
        $search = $request->get('search', '');
        $customers = \App\Models\Customer::where('status', 'active')
            ->where(function($query) use ($search) {
                $query->where('name', 'like', "%{$search}%")
                      ->orWhere('customer_code', 'like', "%{$search}%")
                      ->orWhere('phone', 'like', "%{$search}%")
                      ->orWhere('email', 'like', "%{$search}%");
            })
            ->orderBy('name')
            ->limit(20)
            ->get();

        return response()->json([
            'results' => $customers->map(function($customer) {
                return [
                    'id' => $customer->id,
                    'text' => $customer->name . ($customer->customer_code ? " ({$customer->customer_code})" : ''),
                    'customer_code' => $customer->customer_code,
                    'phone' => $customer->phone,
                    'email' => $customer->email
                ];
            })
        ]);
    });

    Route::get('orders', function (Illuminate\Http\Request $request) {
        $search = $request->get('search', '');
        $orders = \App\Models\Order::where('status', 'delivered')
            ->whereDoesntHave('invoice')
            ->where(function($query) use ($search) {
                $query->where('order_number', 'like', "%{$search}%")
                      ->orWhereHas('customer', function($q) use ($search) {
                          $q->where('name', 'like', "%{$search}%");
                      });
            })
            ->with('customer')
            ->orderBy('created_at', 'desc')
            ->limit(20)
            ->get();

        return response()->json([
            'results' => $orders->map(function($order) {
                return [
                    'id' => $order->id,
                    'text' => $order->order_number . ' - ' . ($order->customer->name ?? 'غير محدد') . ' (' . number_format($order->total_amount, 0) . ' د.ع)',
                    'order_number' => $order->order_number,
                    'customer_name' => $order->customer->name ?? 'غير محدد',
                    'total_amount' => $order->total_amount
                ];
            })
        ]);
    });

    Route::get('items', function (Illuminate\Http\Request $request) {
        $search = $request->get('search', '');
        $items = \App\Models\Item::where('status', 'active')
            ->where(function($query) use ($search) {
                $query->where('name', 'like', "%{$search}%")
                      ->orWhere('code', 'like', "%{$search}%")
                      ->orWhere('barcode', 'like', "%{$search}%");
            })
            ->orderBy('name')
            ->limit(20)
            ->get();

        return response()->json([
            'results' => $items->map(function($item) {
                return [
                    'id' => $item->id,
                    'text' => $item->name . ($item->code ? " ({$item->code})" : ''),
                    'code' => $item->code,
                    'price' => $item->price,
                    'stock' => $item->stock_quantity,
                    'name' => $item->name
                ];
            })
        ]);
    });
});

// مسار تصدير Excel للتقارير المتقدمة
Route::get('/api/advanced-reports/export-excel-test', function (Illuminate\Http\Request $request) {
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


