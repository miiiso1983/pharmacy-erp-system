<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Invoice;
use App\Models\Collection;
use App\Models\Item;
use App\Models\User;
use App\Models\CustomReport;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ReportController extends Controller
{
    public function index()
    {
        return view('reports.index');
    }

    public function sales(Request $request)
    {
        $fromDate = $request->from_date ? Carbon::parse($request->from_date) : Carbon::now()->startOfMonth();
        $toDate = $request->to_date ? Carbon::parse($request->to_date) : Carbon::now()->endOfMonth();

        $orders = Order::with(['customer', 'orderItems'])
            ->whereBetween('created_at', [$fromDate, $toDate])
            ->orderBy('created_at', 'desc')
            ->get();

        $totalSales = $orders->sum('total_amount');
        $totalOrders = $orders->count();
        $avgOrderValue = $totalOrders > 0 ? $totalSales / $totalOrders : 0;

        return view('reports.sales', compact('orders', 'totalSales', 'totalOrders', 'avgOrderValue', 'fromDate', 'toDate'));
    }

    public function financial(Request $request)
    {
        $fromDate = $request->from_date ? Carbon::parse($request->from_date) : Carbon::now()->startOfMonth();
        $toDate = $request->to_date ? Carbon::parse($request->to_date) : Carbon::now()->endOfMonth();

        $invoices = Invoice::with(['customer'])
            ->whereBetween('created_at', [$fromDate, $toDate])
            ->get();

        $collections = collect(); // مؤقتاً حتى يتم إنشاء جدول التحصيلات

        $totalInvoiced = $invoices->sum('total_amount');
        $totalCollected = 0; // مؤقتاً
        $totalOutstanding = $invoices->sum('remaining_amount');

        return view('reports.financial', compact(
            'invoices', 'collections', 'totalInvoiced', 'totalCollected',
            'totalOutstanding', 'fromDate', 'toDate'
        ));
    }

    public function inventory(Request $request)
    {
        $items = Item::with('supplier')
            ->when($request->category, function($query, $category) {
                return $query->where('category', $category);
            })
            ->when($request->low_stock, function($query) {
                return $query->whereColumn('stock_quantity', '<=', 'min_stock_level');
            })
            ->orderBy('name')
            ->get();

        $categories = Item::distinct()->pluck('category')->filter();
        $totalItems = $items->count();
        $lowStockItems = $items->filter(function($item) {
            return $item->stock_quantity <= $item->min_stock_level;
        })->count();
        $totalValue = $items->sum(function($item) {
            return $item->stock_quantity * ($item->cost ?? 0);
        });

        return view('reports.inventory', compact('items', 'categories', 'totalItems', 'lowStockItems', 'totalValue'));
    }

    public function customers(Request $request)
    {
        $fromDate = $request->from_date ? Carbon::parse($request->from_date) : Carbon::now()->startOfYear();
        $toDate = $request->to_date ? Carbon::parse($request->to_date) : Carbon::now()->endOfYear();

        $customers = User::where('user_type', 'customer')
            ->with(['orders' => function($query) use ($fromDate, $toDate) {
                $query->whereBetween('created_at', [$fromDate, $toDate]);
            }])
            ->get()
            ->map(function($customer) {
                $customer->orders_count = $customer->orders->count();
                $customer->total_invoiced = $customer->orders->sum('total_amount');
                $customer->total_paid = 0; // سيتم حسابها لاحقاً
                return $customer;
            })
            ->sortByDesc('total_invoiced');

        return view('reports.customers', compact('customers', 'fromDate', 'toDate'));
    }

    public function topItems(Request $request)
    {
        $fromDate = $request->from_date ? Carbon::parse($request->from_date) : Carbon::now()->startOfMonth();
        $toDate = $request->to_date ? Carbon::parse($request->to_date) : Carbon::now()->endOfMonth();

        $topItems = Item::with(['supplier', 'orderItems' => function($query) use ($fromDate, $toDate) {
                $query->whereHas('order', function($q) use ($fromDate, $toDate) {
                    $q->whereBetween('created_at', [$fromDate, $toDate]);
                });
            }])
            ->get()
            ->map(function($item) {
                $item->total_quantity = $item->orderItems->sum('quantity');
                $item->total_revenue = $item->orderItems->sum('total_price');
                return $item;
            })
            ->filter(function($item) {
                return $item->total_quantity > 0;
            })
            ->sortByDesc('total_revenue')
            ->take(20);

        return view('reports.top-items', compact('topItems', 'fromDate', 'toDate'));
    }

    public function exportSales(Request $request)
    {
        // سيتم تطوير وظيفة التصدير لاحقاً
        return back()->with('info', 'وظيفة التصدير قيد التطوير');
    }

    public function exportFinancial(Request $request)
    {
        // سيتم تطوير وظيفة التصدير لاحقاً
        return back()->with('info', 'وظيفة التصدير قيد التطوير');
    }

    public function exportInventory(Request $request)
    {
        // سيتم تطوير وظيفة التصدير لاحقاً
        return back()->with('info', 'وظيفة التصدير قيد التطوير');
    }

    public function custom()
    {
        // جلب التقارير المحفوظة
        $savedReports = \App\Models\CustomReport::where('user_id', auth()->id())
            ->orderBy('created_at', 'desc')
            ->get();

        return view('reports.custom.index', compact('savedReports'));
    }

    public function customBuilder()
    {
        // البيانات المتاحة للتقارير
        $tables = [
            'orders' => [
                'name' => 'الطلبات',
                'fields' => [
                    'id' => 'رقم الطلب',
                    'customer_name' => 'اسم العميل',
                    'total_amount' => 'إجمالي المبلغ',
                    'status' => 'الحالة',
                    'created_at' => 'تاريخ الإنشاء',
                    'delivery_date' => 'تاريخ التسليم'
                ]
            ],
            'invoices' => [
                'name' => 'الفواتير',
                'fields' => [
                    'id' => 'رقم الفاتورة',
                    'customer_name' => 'اسم العميل',
                    'total_amount' => 'إجمالي المبلغ',
                    'paid_amount' => 'المبلغ المدفوع',
                    'remaining_amount' => 'المبلغ المتبقي',
                    'status' => 'الحالة',
                    'created_at' => 'تاريخ الإنشاء',
                    'due_date' => 'تاريخ الاستحقاق'
                ]
            ],
            'items' => [
                'name' => 'المنتجات',
                'fields' => [
                    'id' => 'رقم المنتج',
                    'name' => 'اسم المنتج',
                    'code' => 'رمز المنتج',
                    'category' => 'الفئة',
                    'stock_quantity' => 'كمية المخزون',
                    'min_stock_level' => 'الحد الأدنى',
                    'price' => 'السعر',
                    'cost' => 'التكلفة',
                    'supplier_name' => 'اسم المورد',
                    'created_at' => 'تاريخ الإنشاء'
                ]
            ],
            'customers' => [
                'name' => 'العملاء',
                'fields' => [
                    'id' => 'رقم العميل',
                    'name' => 'اسم العميل',
                    'email' => 'البريد الإلكتروني',
                    'phone' => 'رقم الهاتف',
                    'address' => 'العنوان',
                    'total_orders' => 'إجمالي الطلبات',
                    'total_amount' => 'إجمالي المبلغ',
                    'created_at' => 'تاريخ التسجيل'
                ]
            ]
        ];

        $operators = [
            '=' => 'يساوي',
            '!=' => 'لا يساوي',
            '>' => 'أكبر من',
            '>=' => 'أكبر من أو يساوي',
            '<' => 'أصغر من',
            '<=' => 'أصغر من أو يساوي',
            'LIKE' => 'يحتوي على',
            'NOT LIKE' => 'لا يحتوي على',
            'IN' => 'ضمن القائمة',
            'NOT IN' => 'ليس ضمن القائمة',
            'BETWEEN' => 'بين',
            'IS NULL' => 'فارغ',
            'IS NOT NULL' => 'غير فارغ'
        ];

        $aggregations = [
            'COUNT' => 'العدد',
            'SUM' => 'المجموع',
            'AVG' => 'المتوسط',
            'MAX' => 'الحد الأقصى',
            'MIN' => 'الحد الأدنى'
        ];

        return view('reports.custom.builder', compact('tables', 'operators', 'aggregations'));
    }

    public function generateCustom(Request $request)
    {
        $request->validate([
            'table' => 'required|string',
            'fields' => 'required|array',
            'fields.*' => 'required|string',
        ]);

        try {
            $query = $this->buildCustomQuery($request);
            $results = $query->get();

            $reportData = [
                'table' => $request->table,
                'fields' => $request->fields,
                'filters' => $request->filters ?? [],
                'groupBy' => $request->group_by,
                'orderBy' => $request->order_by,
                'orderDirection' => $request->order_direction ?? 'asc',
                'results' => $results,
                'total_records' => $results->count()
            ];

            return view('reports.custom.results', compact('reportData'));

        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'حدث خطأ في إنشاء التقرير: ' . $e->getMessage()]);
        }
    }

    public function saveCustom(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'config' => 'required|array'
        ]);

        $customReport = \App\Models\CustomReport::create([
            'name' => $request->name,
            'description' => $request->description,
            'config' => json_encode($request->config),
            'user_id' => auth()->id()
        ]);

        return response()->json([
            'success' => true,
            'message' => 'تم حفظ التقرير بنجاح',
            'report_id' => $customReport->id
        ]);
    }

    public function showCustom($id)
    {
        $customReport = \App\Models\CustomReport::where('user_id', auth()->id())
            ->findOrFail($id);

        $config = json_decode($customReport->config, true);

        try {
            $query = $this->buildCustomQueryFromConfig($config);
            $results = $query->get();

            $reportData = array_merge($config, [
                'results' => $results,
                'total_records' => $results->count(),
                'report_name' => $customReport->name,
                'report_description' => $customReport->description
            ]);

            return view('reports.custom.results', compact('reportData', 'customReport'));

        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'حدث خطأ في تحميل التقرير: ' . $e->getMessage()]);
        }
    }

    public function deleteCustom($id)
    {
        $customReport = \App\Models\CustomReport::where('user_id', auth()->id())
            ->findOrFail($id);

        $customReport->delete();

        return redirect()->route('reports.custom')
            ->with('success', 'تم حذف التقرير بنجاح');
    }

    public function exportCustom(Request $request)
    {
        // سيتم تطوير وظيفة التصدير لاحقاً
        return back()->with('info', 'وظيفة التصدير قيد التطوير');
    }

    private function buildCustomQuery(Request $request)
    {
        $table = $request->table;
        $fields = $request->fields;

        switch ($table) {
            case 'orders':
                $query = Order::with('customer');
                break;
            case 'invoices':
                $query = Invoice::with('customer');
                break;
            case 'items':
                $query = Item::with('supplier');
                break;
            case 'customers':
                $query = User::where('user_type', 'customer');
                break;
            default:
                throw new \Exception('جدول غير مدعوم');
        }

        // تطبيق الفلاتر
        if ($request->filters) {
            foreach ($request->filters as $filter) {
                if (isset($filter['field']) && isset($filter['operator']) && isset($filter['value'])) {
                    $this->applyFilter($query, $filter);
                }
            }
        }

        // تطبيق التجميع
        if ($request->group_by) {
            $query->groupBy($request->group_by);
        }

        // تطبيق الترتيب
        if ($request->order_by) {
            $direction = $request->order_direction ?? 'asc';
            $query->orderBy($request->order_by, $direction);
        }

        return $query;
    }

    private function buildCustomQueryFromConfig($config)
    {
        $request = new Request($config);
        return $this->buildCustomQuery($request);
    }

    private function applyFilter($query, $filter)
    {
        $field = $filter['field'];
        $operator = $filter['operator'];
        $value = $filter['value'];

        switch ($operator) {
            case 'LIKE':
                $query->where($field, 'LIKE', "%{$value}%");
                break;
            case 'NOT LIKE':
                $query->where($field, 'NOT LIKE', "%{$value}%");
                break;
            case 'IN':
                $values = is_array($value) ? $value : explode(',', $value);
                $query->whereIn($field, $values);
                break;
            case 'NOT IN':
                $values = is_array($value) ? $value : explode(',', $value);
                $query->whereNotIn($field, $values);
                break;
            case 'BETWEEN':
                if (is_array($value) && count($value) == 2) {
                    $query->whereBetween($field, $value);
                }
                break;
            case 'IS NULL':
                $query->whereNull($field);
                break;
            case 'IS NOT NULL':
                $query->whereNotNull($field);
                break;
            default:
                $query->where($field, $operator, $value);
        }
    }
}
