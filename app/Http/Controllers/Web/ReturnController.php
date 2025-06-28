<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\ProductReturn;
use App\Models\Order;
use App\Models\Item;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ReturnController extends Controller
{
    public function index()
    {
        $returns = ProductReturn::with(['customer', 'item', 'order', 'processedBy'])
            ->latest()
            ->paginate(15);

        return view('returns.index', compact('returns'));
    }

    public function create()
    {
        $orders = Order::with('customer')->where('status', 'completed')->get();
        $items = Item::where('status', 'active')->get();
        $customers = User::where('user_type', 'customer')->get();

        return view('returns.create', compact('orders', 'items', 'customers'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'order_id' => 'required|exists:orders,id',
            'customer_id' => 'required|exists:users,id',
            'item_id' => 'required|exists:items,id',
            'quantity' => 'required|integer|min:1',
            'unit_price' => 'required|numeric|min:0',
            'reason' => 'required|in:damaged,expired,wrong_item,customer_request,other',
            'reason_description' => 'nullable|string|max:1000',
            'return_date' => 'required|date',
            'notes' => 'nullable|string|max:1000'
        ]);

        $return = new ProductReturn();
        $return->return_number = $return->generateReturnNumber();
        $return->order_id = $request->order_id;
        $return->customer_id = $request->customer_id;
        $return->item_id = $request->item_id;
        $return->quantity = $request->quantity;
        $return->unit_price = $request->unit_price;
        $return->total_amount = $request->quantity * $request->unit_price;
        $return->reason = $request->reason;
        $return->reason_description = $request->reason_description;
        $return->return_date = $request->return_date;
        $return->notes = $request->notes;
        $return->status = 'pending';
        $return->save();

        return redirect()->route('returns.index')
            ->with('success', 'تم إنشاء المرتجع بنجاح');
    }

    public function show(ProductReturn $return)
    {
        $return->load(['customer', 'item', 'order', 'processedBy']);
        return view('returns.show', compact('return'));
    }

    public function edit(ProductReturn $return)
    {
        if ($return->status !== 'pending') {
            return redirect()->route('returns.index')
                ->with('error', 'لا يمكن تعديل مرتجع تم معالجته');
        }

        $orders = Order::with('customer')->where('status', 'completed')->get();
        $items = Item::where('status', 'active')->get();
        $customers = User::where('user_type', 'customer')->get();

        return view('returns.edit', compact('return', 'orders', 'items', 'customers'));
    }

    public function update(Request $request, ProductReturn $return)
    {
        if ($return->status !== 'pending') {
            return redirect()->route('returns.index')
                ->with('error', 'لا يمكن تعديل مرتجع تم معالجته');
        }

        $request->validate([
            'order_id' => 'required|exists:orders,id',
            'customer_id' => 'required|exists:users,id',
            'item_id' => 'required|exists:items,id',
            'quantity' => 'required|integer|min:1',
            'unit_price' => 'required|numeric|min:0',
            'reason' => 'required|in:damaged,expired,wrong_item,customer_request,other',
            'reason_description' => 'nullable|string|max:1000',
            'return_date' => 'required|date',
            'notes' => 'nullable|string|max:1000'
        ]);

        $return->update([
            'order_id' => $request->order_id,
            'customer_id' => $request->customer_id,
            'item_id' => $request->item_id,
            'quantity' => $request->quantity,
            'unit_price' => $request->unit_price,
            'total_amount' => $request->quantity * $request->unit_price,
            'reason' => $request->reason,
            'reason_description' => $request->reason_description,
            'return_date' => $request->return_date,
            'notes' => $request->notes
        ]);

        return redirect()->route('returns.index')
            ->with('success', 'تم تحديث المرتجع بنجاح');
    }

    public function destroy(ProductReturn $return)
    {
        if ($return->status !== 'pending') {
            return redirect()->route('returns.index')
                ->with('error', 'لا يمكن حذف مرتجع تم معالجته');
        }

        $return->delete();

        return redirect()->route('returns.index')
            ->with('success', 'تم حذف المرتجع بنجاح');
    }

    public function approve(ProductReturn $return)
    {
        if ($return->status !== 'pending') {
            return redirect()->route('returns.index')
                ->with('error', 'هذا المرتجع تم معالجته مسبقاً');
        }

        DB::transaction(function () use ($return) {
            $return->update([
                'status' => 'approved',
                'processed_by' => Auth::id()
            ]);

            // إضافة الكمية المرتجعة إلى المخزون
            $item = $return->item;
            $item->increment('stock_quantity', $return->quantity);
        });

        return redirect()->route('returns.index')
            ->with('success', 'تم الموافقة على المرتجع وإضافة الكمية للمخزون');
    }

    public function reject(Request $request, ProductReturn $return)
    {
        if ($return->status !== 'pending') {
            return redirect()->route('returns.index')
                ->with('error', 'هذا المرتجع تم معالجته مسبقاً');
        }

        $return->update([
            'status' => 'rejected',
            'processed_by' => Auth::id(),
            'notes' => $request->rejection_reason
        ]);

        return redirect()->route('returns.index')
            ->with('success', 'تم رفض المرتجع');
    }
}
