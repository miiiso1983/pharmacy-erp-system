<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Item;
use App\Models\Supplier;
use App\Imports\ItemsImport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;

class ItemController extends Controller
{
    public function index()
    {
        $items = Item::with('supplier')
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('items.index', compact('items'));
    }

    public function show($id)
    {
        $item = Item::with('supplier')->findOrFail($id);
        return view('items.show', compact('item'));
    }

    public function create()
    {
        $suppliers = Supplier::where('status', 'active')->get();
        return view('items.create', compact('suppliers'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:items',
            'description' => 'nullable|string',
            'category' => 'nullable|string|max:100',
            'unit' => 'required|string|max:50',
            'price' => 'required|numeric|min:0',
            'cost' => 'nullable|numeric|min:0',
            'stock_quantity' => 'required|integer|min:0',
            'min_stock_level' => 'required|integer|min:0',
            'supplier_id' => 'nullable|exists:suppliers,id',
            'barcode' => 'nullable|string|max:100',
            'expiry_date' => 'nullable|date|after:today',
            'batch_number' => 'nullable|string|max:100',
            'notes' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        Item::create($request->all());

        return redirect()->route('items.index')
            ->with('success', 'تم إنشاء المنتج بنجاح');
    }

    public function edit($id)
    {
        $item = Item::findOrFail($id);
        $suppliers = Supplier::where('status', 'active')->get();
        return view('items.edit', compact('item', 'suppliers'));
    }

    public function update(Request $request, $id)
    {
        $item = Item::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:items,code,' . $id,
            'description' => 'nullable|string',
            'category' => 'nullable|string|max:100',
            'unit' => 'required|string|max:50',
            'price' => 'required|numeric|min:0',
            'cost' => 'nullable|numeric|min:0',
            'stock_quantity' => 'required|integer|min:0',
            'min_stock_level' => 'required|integer|min:0',
            'supplier_id' => 'nullable|exists:suppliers,id',
            'barcode' => 'nullable|string|max:100',
            'expiry_date' => 'nullable|date|after:today',
            'batch_number' => 'nullable|string|max:100',
            'status' => 'required|in:active,inactive',
            'notes' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $item->update($request->all());

        return redirect()->route('items.show', $item->id)
            ->with('success', 'تم تحديث المنتج بنجاح');
    }

    public function destroy($id)
    {
        $item = Item::findOrFail($id);

        // التحقق من عدم وجود طلبات مرتبطة
        if ($item->orderItems()->count() > 0) {
            return back()->withErrors(['error' => 'لا يمكن حذف المنتج لوجود طلبات مرتبطة به']);
        }

        $item->delete();

        return redirect()->route('items.index')
            ->with('success', 'تم حذف المنتج بنجاح');
    }

    public function search(Request $request)
    {
        $query = $request->get('q');

        $items = Item::where('name', 'like', "%{$query}%")
            ->orWhere('code', 'like', "%{$query}%")
            ->orWhere('barcode', 'like', "%{$query}%")
            ->with('supplier')
            ->limit(10)
            ->get();

        return response()->json($items);
    }

    public function lowStock(Request $request)
    {
        $query = Item::with(['supplier'])
            ->whereColumn('stock_quantity', '<=', 'min_stock_level');

        // تطبيق الفلاتر
        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        if ($request->filled('supplier_id')) {
            $query->where('supplier_id', $request->supplier_id);
        }

        if ($request->filled('stock_level')) {
            switch ($request->stock_level) {
                case 'out_of_stock':
                    $query->where('stock_quantity', '<=', 0);
                    break;
                case 'critical':
                    $query->whereRaw('stock_quantity <= (min_stock_level * 0.25)');
                    break;
                case 'low':
                    $query->whereRaw('stock_quantity <= (min_stock_level * 0.5)')
                          ->whereRaw('stock_quantity > (min_stock_level * 0.25)');
                    break;
            }
        }

        // ترتيب النتائج
        $sortBy = $request->get('sort_by', 'stock_quantity');
        switch ($sortBy) {
            case 'name':
                $query->orderBy('name');
                break;
            case 'category':
                $query->join('categories', 'items.category_id', '=', 'categories.id')
                      ->orderBy('categories.name');
                break;
            case 'price':
                $query->orderBy('unit_price');
                break;
            case 'last_updated':
                $query->orderBy('updated_at', 'desc');
                break;
            default:
                $query->orderBy('stock_quantity', 'asc');
        }

        $items = $query->paginate(15);

        // إحصائيات
        $lowStockCount = Item::whereColumn('stock_quantity', '<=', 'min_stock_level')->count();
        $outOfStockCount = Item::where('stock_quantity', '<=', 0)->count();
        $criticalCount = Item::whereRaw('stock_quantity <= (min_stock_level * 0.25)')->count();
        $totalValue = Item::whereColumn('stock_quantity', '<=', 'min_stock_level')
                          ->selectRaw('SUM(stock_quantity * price) as total')
                          ->value('total') ?? 0;

        // البيانات للفلاتر
        $categories = collect(); // سيتم إضافة نموذج Category لاحقاً
        $suppliers = Supplier::orderBy('name')->get();

        return view('items.low-stock', compact(
            'items', 'lowStockCount', 'outOfStockCount', 'criticalCount',
            'totalValue', 'categories', 'suppliers'
        ));
    }

    public function details($id)
    {
        $item = Item::with(['supplier'])->findOrFail($id);

        return response()->json([
            'id' => $item->id,
            'name' => $item->name,
            'item_code' => $item->code,
            'stock_quantity' => $item->stock_quantity,
            'minimum_stock' => $item->min_stock_level,
            'unit' => $item->unit,
            'unit_price' => $item->price,
            'category' => $item->category,
            'supplier' => $item->supplier?->name,
        ]);
    }

    public function addStock(Request $request, $id)
    {
        $request->validate([
            'quantity' => 'required|numeric|min:1',
            'notes' => 'nullable|string|max:500'
        ]);

        $item = Item::findOrFail($id);
        $oldQuantity = $item->stock_quantity;
        $newQuantity = $oldQuantity + $request->quantity;

        $item->update([
            'stock_quantity' => $newQuantity
        ]);

        // تسجيل حركة المخزون - سيتم إضافة نموذج StockMovement لاحقاً
        // StockMovement::create([
        //     'item_id' => $item->id,
        //     'type' => 'in',
        //     'quantity' => $request->quantity,
        //     'old_quantity' => $oldQuantity,
        //     'new_quantity' => $newQuantity,
        //     'notes' => $request->notes ?? 'إضافة مخزون يدوية',
        //     'user_id' => auth()->id(),
        // ]);

        return response()->json([
            'success' => true,
            'message' => 'تم إضافة المخزون بنجاح',
            'new_quantity' => $newQuantity
        ]);
    }

    public function export(Request $request)
    {
        $query = Item::with(['category', 'supplier']);

        if ($request->has('low_stock')) {
            $query->whereColumn('stock_quantity', '<=', 'min_stock_level');
        }

        // تطبيق نفس الفلاتر
        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        if ($request->filled('supplier_id')) {
            $query->where('supplier_id', $request->supplier_id);
        }

        $items = $query->get();

        if ($request->get('format') === 'pdf') {
            // تصدير PDF
            $pdf = Pdf::loadView('items.export-pdf', compact('items'));
            return $pdf->download('low-stock-items-' . date('Y-m-d') . '.pdf');
        } else {
            // تصدير Excel - سيتم إضافة ItemsExport لاحقاً
            return back()->with('info', 'تصدير Excel قيد التطوير');
        }
    }

    /**
     * Show import form
     */
    public function importForm()
    {
        return view('items.import');
    }

    /**
     * Import items from Excel
     */
    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv|max:2048'
        ], [
            'file.required' => 'يرجى اختيار ملف',
            'file.mimes' => 'يجب أن يكون الملف من نوع Excel أو CSV',
            'file.max' => 'حجم الملف يجب أن يكون أقل من 2 ميجابايت'
        ]);

        try {
            $import = new ItemsImport;
            Excel::import($import, $request->file('file'));

            $message = "تم استيراد العناصر بنجاح";

            if (count($import->errors()) > 0 || count($import->failures()) > 0) {
                $message .= ". تم تجاهل بعض الصفوف بسبب أخطاء";
            }

            return redirect()->route('items.index')
                ->with('success', $message);

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'حدث خطأ أثناء استيراد الملف: ' . $e->getMessage());
        }
    }

    /**
     * Download sample Excel file
     */
    public function downloadSample()
    {
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="items_sample.csv"',
        ];

        // إنشاء ملف نموذجي
        $sampleData = [
            ['الكود', 'اسم_المنتج', 'الوصف', 'الفئة', 'الباركود', 'الوحدة', 'السعر', 'التكلفة', 'الكمية', 'الحد_الادنى', 'الحد_الاقصى', 'اسم_المورد', 'تاريخ_الانتهاء', 'رقم_الدفعة', 'الموقع', 'الحالة', 'ملاحظات'],
            ['MED001', 'باراسيتامول 500 مجم', 'مسكن للألم وخافض للحرارة', 'مسكنات', '1234567890123', 'قرص', '500', '300', '100', '10', '500', 'شركة الأدوية المتحدة', '2025-12-31', 'BATCH001', 'رف A1', 'نشط', 'دواء آمن'],
            ['MED002', 'أموكسيسيلين 250 مجم', 'مضاد حيوي واسع المجال', 'مضادات حيوية', '1234567890124', 'كبسولة', '1200', '800', '50', '5', '200', 'مختبرات الشفاء', '2025-06-30', 'BATCH002', 'رف B2', 'نشط', 'يحفظ في مكان بارد'],
        ];

        return response()->streamDownload(function() use ($sampleData) {
            $file = fopen('php://output', 'w');
            foreach ($sampleData as $row) {
                fputcsv($file, $row);
            }
            fclose($file);
        }, 'items_sample.csv', $headers);
    }
}
