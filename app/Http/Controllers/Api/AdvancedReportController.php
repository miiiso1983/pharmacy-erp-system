<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\AdvancedReportBuilder;
use App\Models\CustomReport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Exports\AdvancedReportExport;

class AdvancedReportController extends Controller
{
    protected $reportBuilder;

    public function __construct(AdvancedReportBuilder $reportBuilder)
    {
        $this->reportBuilder = $reportBuilder;
    }

    /**
     * الحصول على مصادر البيانات المتاحة
     */
    public function getDataSources()
    {
        return response()->json([
            'success' => true,
            'data' => CustomReport::getExtendedDataSources()
        ]);
    }

    /**
     * الحصول على أنواع التقارير
     */
    public function getReportTypes()
    {
        return response()->json([
            'success' => true,
            'data' => CustomReport::getReportTypes()
        ]);
    }

    /**
     * الحصول على أنواع الحسابات
     */
    public function getCalculationTypes()
    {
        return response()->json([
            'success' => true,
            'data' => CustomReport::getCalculationTypes()
        ]);
    }

    /**
     * إنشاء تقرير متداخل مخصص
     */
    public function createIntegratedReport(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'data_sources' => 'required|array|min:1',
            'data_sources.*' => 'required|string',
            'columns' => 'required|array|min:1',
            'filters' => 'nullable|array',
            'grouping' => 'nullable|array',
            'sorting' => 'nullable|array',
            'calculations' => 'nullable|array',
            'format' => 'required|in:json,excel,pdf'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation errors',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            // بناء التقرير
            $builder = new AdvancedReportBuilder();
            
            // إضافة مصادر البيانات
            foreach ($request->data_sources as $source => $config) {
                $builder->addDataSource($source, is_array($config) ? $config : []);
            }
            
            // إضافة الأعمدة
            foreach ($request->columns as $column) {
                $builder->addColumn(
                    $column['field'],
                    $column['alias'] ?? null,
                    $column['source'] ?? null,
                    $column['calculation'] ?? null
                );
            }
            
            // إضافة الفلاتر
            if ($request->filters) {
                foreach ($request->filters as $filter) {
                    $builder->addFilter(
                        $filter['field'],
                        $filter['operator'],
                        $filter['value'],
                        $filter['source'] ?? null
                    );
                }
            }
            
            // إضافة التجميع
            if ($request->grouping) {
                foreach ($request->grouping as $group) {
                    $builder->addGrouping($group['field'], $group['source'] ?? null);
                }
            }
            
            // إضافة الترتيب
            if ($request->sorting) {
                foreach ($request->sorting as $sort) {
                    $builder->addSorting(
                        $sort['field'],
                        $sort['direction'] ?? 'asc',
                        $sort['source'] ?? null
                    );
                }
            }
            
            // إضافة الحسابات
            if ($request->calculations) {
                foreach ($request->calculations as $calc) {
                    $builder->addCalculation(
                        $calc['type'],
                        $calc['field'],
                        $calc['alias'],
                        $calc['source'] ?? null
                    );
                }
            }
            
            // بناء التقرير
            $reportData = $builder->buildIntegratedReport();
            
            // حفظ التقرير إذا كان مطلوباً
            if ($request->save_report) {
                $customReport = CustomReport::create([
                    'name' => $request->name,
                    'description' => $request->description,
                    'report_type' => 'integrated',
                    'data_sources' => $request->data_sources,
                    'filters' => $request->filters,
                    'columns' => $request->columns,
                    'grouping' => $request->grouping,
                    'sorting' => $request->sorting,
                    'calculations' => $request->calculations,
                    'created_by' => auth()->id(),
                    'is_public' => $request->is_public ?? false,
                    'status' => 'active',
                    'last_generated_at' => now()
                ]);
            }
            
            // تصدير التقرير حسب التنسيق المطلوب
            switch ($request->format) {
                case 'excel':
                    return Excel::download(
                        new AdvancedReportExport($reportData),
                        $request->name . '_' . date('Y-m-d') . '.xlsx'
                    );
                    
                case 'pdf':
                    $pdf = Pdf::loadView('reports.advanced-report', [
                        'reportData' => $reportData,
                        'reportName' => $request->name,
                        'reportDescription' => $request->description
                    ]);
                    return $pdf->download($request->name . '_' . date('Y-m-d') . '.pdf');
                    
                default:
                    return response()->json([
                        'success' => true,
                        'message' => 'تم إنشاء التقرير بنجاح',
                        'data' => $reportData
                    ]);
            }
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء إنشاء التقرير',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * تقرير المبيعات المتداخل
     */
    public function salesIntegratedReport(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'include_customers' => 'boolean',
            'include_items' => 'boolean',
            'include_collections' => 'boolean',
            'format' => 'required|in:json,excel,pdf'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $builder = new AdvancedReportBuilder();
            
            // مصدر البيانات الأساسي: الطلبات
            $builder->addDataSource('orders');
            
            // إضافة مصادر البيانات الإضافية
            if ($request->include_customers) {
                $builder->addDataSource('customers');
            }
            if ($request->include_items) {
                $builder->addDataSource('items');
            }
            if ($request->include_collections) {
                $builder->addDataSource('collections');
            }
            
            // الأعمدة الأساسية
            $builder->addColumn('order_number', 'رقم الطلب', 'orders')
                   ->addColumn('created_at', 'تاريخ الطلب', 'orders')
                   ->addColumn('status', 'حالة الطلب', 'orders')
                   ->addColumn('total_amount', 'المبلغ الكلي', 'orders');
            
            // أعمدة العملاء
            if ($request->include_customers) {
                $builder->addColumn('name', 'اسم العميل', 'customers')
                       ->addColumn('phone', 'هاتف العميل', 'customers')
                       ->addColumn('city', 'مدينة العميل', 'customers');
            }
            
            // أعمدة التحصيلات
            if ($request->include_collections) {
                $builder->addColumn('amount', 'مبلغ التحصيل', 'collections')
                       ->addColumn('payment_method', 'طريقة الدفع', 'collections')
                       ->addColumn('collection_date', 'تاريخ التحصيل', 'collections');
            }
            
            // الفلاتر
            $builder->addFilter('created_at', 'date_range', [
                'start' => $request->start_date,
                'end' => $request->end_date
            ], 'orders');
            
            // الحسابات
            $builder->addCalculation('sum', 'total_amount', 'إجمالي المبيعات', 'orders')
                   ->addCalculation('avg', 'total_amount', 'متوسط قيمة الطلب', 'orders')
                   ->addCalculation('count', 'id', 'عدد الطلبات', 'orders');
            
            if ($request->include_collections) {
                $builder->addCalculation('sum', 'amount', 'إجمالي التحصيلات', 'collections');
            }
            
            // الترتيب
            $builder->addSorting('created_at', 'desc', 'orders');
            
            $reportData = $builder->buildIntegratedReport();
            
            switch ($request->format) {
                case 'excel':
                    return Excel::download(
                        new AdvancedReportExport($reportData),
                        'sales_integrated_report_' . date('Y-m-d') . '.xlsx'
                    );
                    
                case 'pdf':
                    $pdf = Pdf::loadView('reports.sales-integrated', [
                        'reportData' => $reportData,
                        'startDate' => $request->start_date,
                        'endDate' => $request->end_date
                    ]);
                    return $pdf->download('sales_integrated_report_' . date('Y-m-d') . '.pdf');
                    
                default:
                    return response()->json([
                        'success' => true,
                        'data' => $reportData
                    ]);
            }
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء إنشاء تقرير المبيعات',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * تقرير الأداء المالي المتداخل
     */
    public function financialPerformanceReport(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'period' => 'required|in:daily,weekly,monthly,quarterly,yearly',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'format' => 'required|in:json,excel,pdf'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $builder = new AdvancedReportBuilder();
            
            // مصادر البيانات المتعددة
            $builder->addDataSource('orders')
                   ->addDataSource('invoices')
                   ->addDataSource('collections')
                   ->addDataSource('customers');
            
            // الأعمدة والحسابات المالية
            $builder->addColumn('created_at', 'التاريخ', 'orders')
                   ->addCalculation('sum', 'total_amount', 'إجمالي المبيعات', 'orders')
                   ->addCalculation('sum', 'total_amount', 'إجمالي الفواتير', 'invoices')
                   ->addCalculation('sum', 'amount', 'إجمالي التحصيلات', 'collections')
                   ->addCalculation('count', 'id', 'عدد العملاء الجدد', 'customers');
            
            // فلترة حسب الفترة
            $builder->addFilter('created_at', 'date_range', [
                'start' => $request->start_date,
                'end' => $request->end_date
            ], 'orders');
            
            // تجميع حسب الفترة
            switch ($request->period) {
                case 'daily':
                    $builder->addGrouping('DATE(created_at)', 'orders');
                    break;
                case 'weekly':
                    $builder->addGrouping('YEARWEEK(created_at)', 'orders');
                    break;
                case 'monthly':
                    $builder->addGrouping('YEAR(created_at), MONTH(created_at)', 'orders');
                    break;
                case 'quarterly':
                    $builder->addGrouping('YEAR(created_at), QUARTER(created_at)', 'orders');
                    break;
                case 'yearly':
                    $builder->addGrouping('YEAR(created_at)', 'orders');
                    break;
            }
            
            $reportData = $builder->buildIntegratedReport();
            
            // إضافة مؤشرات الأداء
            $reportData['kpis'] = $this->calculateFinancialKPIs($reportData['data']);
            
            switch ($request->format) {
                case 'excel':
                    return Excel::download(
                        new AdvancedReportExport($reportData),
                        'financial_performance_' . date('Y-m-d') . '.xlsx'
                    );
                    
                case 'pdf':
                    $pdf = Pdf::loadView('reports.financial-performance', [
                        'reportData' => $reportData,
                        'period' => $request->period,
                        'startDate' => $request->start_date,
                        'endDate' => $request->end_date
                    ]);
                    return $pdf->download('financial_performance_' . date('Y-m-d') . '.pdf');
                    
                default:
                    return response()->json([
                        'success' => true,
                        'data' => $reportData
                    ]);
            }
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء إنشاء تقرير الأداء المالي',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * حساب مؤشرات الأداء المالي
     */
    protected function calculateFinancialKPIs($data): array
    {
        $totalSales = $data->sum('إجمالي المبيعات');
        $totalCollections = $data->sum('إجمالي التحصيلات');
        $totalInvoices = $data->sum('إجمالي الفواتير');
        
        return [
            'collection_rate' => $totalInvoices > 0 ? ($totalCollections / $totalInvoices) * 100 : 0,
            'average_order_value' => $data->count() > 0 ? $totalSales / $data->count() : 0,
            'growth_rate' => $this->calculateGrowthRate($data),
            'outstanding_amount' => $totalInvoices - $totalCollections
        ];
    }

    /**
     * حساب معدل النمو
     */
    protected function calculateGrowthRate($data): float
    {
        if ($data->count() < 2) return 0;
        
        $firstPeriod = $data->first()['إجمالي المبيعات'] ?? 0;
        $lastPeriod = $data->last()['إجمالي المبيعات'] ?? 0;
        
        if ($firstPeriod == 0) return 0;
        
        return (($lastPeriod - $firstPeriod) / $firstPeriod) * 100;
    }
}
