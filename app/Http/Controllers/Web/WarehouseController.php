<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Warehouse;
use App\Models\WarehouseItem;
use App\Models\Item;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class WarehouseController extends Controller
{
    public function index()
    {
        $warehouses = Warehouse::withCount('warehouseItems')
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        $stats = [
            'total_warehouses' => Warehouse::count(),
            'active_warehouses' => Warehouse::where('status', 'active')->count(),
            'total_value' => Warehouse::sum('total_value'),
            'total_items' => Warehouse::sum('total_items'),
        ];

        return view('warehouses.index', compact('warehouses', 'stats'));
    }

    public function show($id)
    {
        $warehouse = Warehouse::with('warehouseItems.item')->findOrFail($id);

        $stats = [
            'total_items' => $warehouse->warehouseItems->count(),
            'total_value' => $warehouse->warehouseItems->sum(function($warehouseItem) {
                return $warehouseItem->quantity * ($warehouseItem->unit_cost ?? $warehouseItem->item->price);
            }),
            'low_stock_items' => $warehouse->warehouseItems->filter(function($warehouseItem) {
                return $warehouseItem->quantity <= ($warehouseItem->item->min_stock_level ?? 0);
            })->count(),
        ];

        return view('warehouses.show', compact('warehouse', 'stats'));
    }

    public function create()
    {
        return view('warehouses.create');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:warehouses',
            'city' => 'required|string|max:100',
            'area' => 'required|string|max:100',
            'address' => 'required|string',
            'phone' => 'nullable|string|max:20',
            'manager' => 'nullable|string|max:255',
            'type' => 'required|in:main,branch,pharmacy,distribution',
            'description' => 'nullable|string',
            'notes' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        Warehouse::create($request->all());

        return redirect()->route('warehouses.index')
            ->with('success', 'تم إنشاء المخزن بنجاح');
    }

    public function edit($id)
    {
        $warehouse = Warehouse::findOrFail($id);
        return view('warehouses.edit', compact('warehouse'));
    }

    public function update(Request $request, $id)
    {
        $warehouse = Warehouse::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:warehouses,code,' . $id,
            'city' => 'required|string|max:100',
            'area' => 'required|string|max:100',
            'address' => 'required|string',
            'phone' => 'nullable|string|max:20',
            'manager' => 'nullable|string|max:255',
            'type' => 'required|in:main,branch,pharmacy,distribution',
            'status' => 'required|in:active,inactive,maintenance',
            'description' => 'nullable|string',
            'notes' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $warehouse->update($request->all());

        return redirect()->route('warehouses.index')
            ->with('success', 'تم تحديث المخزن بنجاح');
    }

    public function destroy($id)
    {
        $warehouse = Warehouse::findOrFail($id);

        // التحقق من وجود عناصر في المخزن
        if ($warehouse->items()->count() > 0) {
            return redirect()->back()
                ->with('error', 'لا يمكن حذف المخزن لأنه يحتوي على عناصر');
        }

        $warehouse->delete();

        return redirect()->route('warehouses.index')
            ->with('success', 'تم حذف المخزن بنجاح');
    }

    public function items($id)
    {
        $warehouse = Warehouse::findOrFail($id);
        $warehouseItems = $warehouse->warehouseItems()->with('item.supplier')->paginate(15);

        return view('warehouses.items', compact('warehouse', 'warehouseItems'));
    }

    public function reports($id)
    {
        $warehouse = Warehouse::with('warehouseItems.item')->findOrFail($id);

        // إحصائيات المخزن
        $stats = [
            'total_items' => $warehouse->warehouseItems->count(),
            'total_value' => $warehouse->warehouseItems->sum(function($warehouseItem) {
                return $warehouseItem->quantity * ($warehouseItem->unit_cost ?? $warehouseItem->item->price);
            }),
            'low_stock_items' => $warehouse->warehouseItems->filter(function($warehouseItem) {
                return $warehouseItem->quantity <= ($warehouseItem->item->min_stock_level ?? 0);
            })->count(),
            'expired_items' => $warehouse->warehouseItems->filter(function($warehouseItem) {
                return $warehouseItem->item->expiry_date && $warehouseItem->item->expiry_date < now();
            })->count(),
        ];

        return view('warehouses.reports', compact('warehouse', 'stats'));
    }

    // نقل البضائع بين المخازن
    public function transfers()
    {
        try {
            $warehouses = Warehouse::where('status', 'active')->get();
            return view('warehouses.transfers', compact('warehouses'));
        } catch (\Exception $e) {
            \Log::error('Warehouse transfers error: ' . $e->getMessage());
            return response()->view('errors.500', [], 500);
        }
    }

    public function processTransfer(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'from_warehouse_id' => 'required|exists:warehouses,id',
            'to_warehouse_id' => 'required|exists:warehouses,id|different:from_warehouse_id',
            'item_id' => 'required|exists:items,id',
            'quantity' => 'required|numeric|min:1',
            'notes' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        DB::beginTransaction();
        try {
            $fromWarehouse = Warehouse::findOrFail($request->from_warehouse_id);
            $toWarehouse = Warehouse::findOrFail($request->to_warehouse_id);
            $item = Item::findOrFail($request->item_id);

            // التحقق من توفر الكمية في المخزن المصدر
            $sourceWarehouseItem = WarehouseItem::where('warehouse_id', $fromWarehouse->id)
                ->where('item_id', $item->id)
                ->first();

            if (!$sourceWarehouseItem || $sourceWarehouseItem->quantity < $request->quantity) {
                throw new \Exception('الكمية المطلوبة غير متوفرة في المخزن المصدر');
            }

            // تقليل الكمية من المخزن المصدر
            $sourceWarehouseItem->quantity -= $request->quantity;
            $sourceWarehouseItem->save();

            // إضافة الكمية إلى المخزن الهدف
            $targetWarehouseItem = WarehouseItem::where('warehouse_id', $toWarehouse->id)
                ->where('item_id', $item->id)
                ->first();

            if ($targetWarehouseItem) {
                $targetWarehouseItem->quantity += $request->quantity;
                $targetWarehouseItem->save();
            } else {
                WarehouseItem::create([
                    'warehouse_id' => $toWarehouse->id,
                    'item_id' => $item->id,
                    'quantity' => $request->quantity,
                    'unit_cost' => $sourceWarehouseItem->unit_cost,
                ]);
            }

            // تسجيل عملية النقل (يمكن إضافة جدول منفصل للتحويلات)

            DB::commit();

            return redirect()->route('warehouses.transfers')
                ->with('success', 'تم نقل البضائع بنجاح من ' . $fromWarehouse->name . ' إلى ' . $toWarehouse->name);

        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()
                ->with('error', 'حدث خطأ: ' . $e->getMessage())
                ->withInput();
        }
    }

    // الحصول على عناصر مخزن معين (AJAX)
    public function getWarehouseItems($warehouseId)
    {
        $items = WarehouseItem::with('item')
            ->where('warehouse_id', $warehouseId)
            ->where('quantity', '>', 0)
            ->get()
            ->map(function($warehouseItem) {
                return [
                    'id' => $warehouseItem->item->id,
                    'name' => $warehouseItem->item->name,
                    'available_quantity' => $warehouseItem->quantity,
                    'unit' => $warehouseItem->item->unit,
                ];
            });

        return response()->json($items);
    }

    // تقارير شاملة لجميع المخازن
    public function allReports()
    {
        $warehouses = Warehouse::with('warehouseItems.item')->get();

        $totalStats = [
            'total_warehouses' => $warehouses->count(),
            'active_warehouses' => $warehouses->where('status', 'active')->count(),
            'total_value' => $warehouses->sum('total_value'),
            'total_items' => $warehouses->sum('total_items'),
        ];

        $warehouseStats = $warehouses->map(function($warehouse) {
            return [
                'warehouse' => $warehouse,
                'total_items' => $warehouse->warehouseItems->count(),
                'total_value' => $warehouse->warehouseItems->sum(function($warehouseItem) {
                    return $warehouseItem->quantity * ($warehouseItem->unit_cost ?? $warehouseItem->item->price);
                }),
                'low_stock_items' => $warehouse->warehouseItems->filter(function($warehouseItem) {
                    return $warehouseItem->quantity <= ($warehouseItem->item->min_stock_level ?? 0);
                })->count(),
            ];
        });

        return view('warehouses.all-reports', compact('totalStats', 'warehouseStats'));
    }
}
