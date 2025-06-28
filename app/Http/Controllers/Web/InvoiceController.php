<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use App\Models\Collection;
use App\Models\Customer;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Item;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class InvoiceController extends Controller
{
    public function index()
    {
        try {
            $user = Auth::user();

            $query = Invoice::with(['customer', 'order'])
                ->orderBy('created_at', 'desc');

            // إذا كان المستخدم عميل، عرض فواتيره فقط
            if ($user && $user->user_type === 'customer') {
                $query->where('customer_id', $user->id);
            }

            $invoices = $query->paginate(15);

            return view('invoices.index', compact('invoices'));
        } catch (\Exception $e) {
            \Log::error('Invoice index error: ' . $e->getMessage());
            return response()->view('errors.500', [], 500);
        }
    }

    public function show($id)
    {
        $user = Auth::user();

        $query = Invoice::with(['customer', 'order.orderItems.item', 'collections']);

        // إذا كان المستخدم عميل، التأكد أن الفاتورة له
        if ($user->user_type === 'customer') {
            $query->where('customer_id', $user->id);
        }

        $invoice = $query->findOrFail($id);

        return view('invoices.show', compact('invoice'));
    }

    public function edit($id)
    {
        $invoice = Invoice::with(['customer', 'order'])->findOrFail($id);
        return view('invoices.edit', compact('invoice'));
    }

    public function update(Request $request, $id)
    {
        $invoice = Invoice::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'discount_amount' => 'nullable|numeric|min:0|max:' . $invoice->subtotal,
            'due_date' => 'nullable|date',
            'notes' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        // إعادة حساب المبلغ الإجمالي
        $subtotal = $invoice->subtotal;
        $taxAmount = $invoice->tax_amount;
        $discountAmount = $request->discount_amount ?? 0;
        $totalAmount = $subtotal + $taxAmount - $discountAmount;

        $invoice->update([
            'discount_amount' => $discountAmount,
            'total_amount' => $totalAmount,
            'remaining_amount' => $totalAmount - $invoice->paid_amount,
            'due_date' => $request->due_date,
            'notes' => $request->notes,
        ]);

        return redirect()->route('invoices.show', $invoice->id)
            ->with('success', 'تم تحديث الفاتورة بنجاح');
    }

    public function markAsPaid($id)
    {
        $invoice = Invoice::findOrFail($id);

        // إنشاء تحصيل للمبلغ المتبقي
        Collection::create([
            'invoice_id' => $invoice->id,
            'customer_id' => $invoice->customer_id,
            'amount' => $invoice->remaining_amount,
            'payment_method' => 'cash',
            'collection_date' => now(),
            'collected_by' => Auth::id(),
            'notes' => 'تم وضع علامة كمدفوع من النظام',
        ]);

        return back()->with('success', 'تم وضع علامة على الفاتورة كمدفوعة');
    }

    public function pending()
    {
        $user = Auth::user();

        $query = Invoice::with(['customer', 'order'])
            ->where('status', 'pending')
            ->orderBy('created_at', 'desc');

        if ($user->user_type === 'customer') {
            $query->where('customer_id', $user->id);
        }

        $invoices = $query->paginate(15);

        return view('invoices.pending', compact('invoices'));
    }

    public function overdue()
    {
        $user = Auth::user();

        $query = Invoice::with(['customer', 'order'])
            ->where('status', 'overdue')
            ->orWhere(function($q) {
                $q->where('status', 'pending')
                  ->where('due_date', '<', now());
            })
            ->orderBy('due_date', 'asc');

        if ($user->user_type === 'customer') {
            $query->where('customer_id', $user->id);
        }

        $invoices = $query->paginate(15);

        return view('invoices.overdue', compact('invoices'));
    }

    public function print($id)
    {
        $user = Auth::user();

        $query = Invoice::with(['customer', 'order.orderItems.item']);

        if ($user->user_type === 'customer') {
            $query->where('customer_id', $user->id);
        }

        $invoice = $query->findOrFail($id);

        return view('invoices.print', compact('invoice'));
    }

    public function create()
    {
        $customers = Customer::where('status', 'active')->orderBy('name')->get();
        $orders = Order::where('status', 'delivered')
            ->whereDoesntHave('invoice')
            ->with('customer')
            ->orderBy('created_at', 'desc')
            ->get();
        $items = Item::where('status', 'active')->orderBy('name')->get();

        return view('invoices.create', compact('customers', 'orders', 'items'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'customer_id' => 'required|exists:customers,id',
            'order_id' => 'nullable|exists:orders,id',
            'invoice_number' => 'required|string|unique:invoices,invoice_number',
            'invoice_date' => 'required|date',
            'due_date' => 'nullable|date|after_or_equal:invoice_date',
            'subtotal' => 'required|numeric|min:0',
            'tax_amount' => 'nullable|numeric|min:0',
            'discount_amount' => 'nullable|numeric|min:0',
            'notes' => 'nullable|string',
            'items' => 'required|array|min:1',
            'items.*.item_id' => 'required|exists:items,id',
            'items.*.description' => 'required|string',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.free_quantity' => 'nullable|integer|min:0',
            'items.*.unit_price' => 'required|numeric|min:0',
            'items.*.discount_percentage' => 'nullable|numeric|min:0|max:100',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        return DB::transaction(function () use ($request) {
            // إنشاء طلب جديد أولاً إذا لم يكن مرتبط بطلب موجود
            if (!$request->order_id) {
                $order = Order::create([
                    'order_number' => 'ORD-' . date('Y') . '-' . str_pad(Order::count() + 1, 6, '0', STR_PAD_LEFT),
                    'customer_id' => $request->customer_id,
                    'status' => 'delivered',
                    'total_amount' => $request->subtotal,
                    'notes' => 'طلب تم إنشاؤه من الفاتورة',
                    'created_by' => auth()->id() ?? 1, // المستخدم الحالي أو المدير الافتراضي
                ]);

                // إضافة عناصر الطلب
                foreach ($request->items as $item) {
                    $quantity = (int) $item['quantity'];
                    $freeQuantity = (int) ($item['free_quantity'] ?? 0);
                    $unitPrice = (float) $item['unit_price'];
                    $discountPercentage = (float) ($item['discount_percentage'] ?? 0);
                    $itemId = $item['item_id'] ?? null;

                    // حساب مبلغ الخصم والسعر الصافي
                    $discountAmount = ($unitPrice * $discountPercentage) / 100;
                    $netPrice = $unitPrice - $discountAmount;
                    $totalPrice = $quantity * $netPrice;

                    OrderItem::create([
                        'order_id' => $order->id,
                        'item_id' => $itemId,
                        'quantity' => $quantity,
                        'free_quantity' => $freeQuantity,
                        'unit_price' => $unitPrice,
                        'discount_percentage' => $discountPercentage,
                        'discount_amount' => $discountAmount,
                        'net_price' => $netPrice,
                        'total_price' => $totalPrice,
                        'notes' => $item['description'] ?? '',
                    ]);
                }

                $orderId = $order->id;
            } else {
                $orderId = $request->order_id;
            }

            // إنشاء الفاتورة
            $subtotal = $request->subtotal;
            $taxAmount = $request->tax_amount ?? 0;
            $discountAmount = $request->discount_amount ?? 0;
            $totalAmount = $subtotal + $taxAmount - $discountAmount;

            $invoice = Invoice::create([
                'invoice_number' => $request->invoice_number,
                'customer_id' => $request->customer_id,
                'order_id' => $orderId,
                'issue_date' => $request->invoice_date ?? now(),
                'due_date' => $request->due_date,
                'subtotal' => $subtotal,
                'tax_amount' => $taxAmount,
                'discount_amount' => $discountAmount,
                'total_amount' => $totalAmount,
                'paid_amount' => 0,
                'remaining_amount' => $totalAmount,
                'status' => 'pending',
                'notes' => $request->notes,
            ]);

            return redirect()->route('invoices.index')
                ->with('success', 'تم إنشاء الفاتورة بنجاح - رقم الفاتورة: ' . $invoice->invoice_number);
        });
    }

    public function paid()
    {
        $user = Auth::user();

        $query = Invoice::with(['customer', 'order'])
            ->where('status', 'paid')
            ->orderBy('payment_date', 'desc');

        if ($user->user_type === 'customer') {
            $query->where('customer_id', $user->id);
        }

        $invoices = $query->paginate(15);

        // إحصائيات
        $paidCount = $query->count();
        $paidAmount = $query->sum('total_amount');
        $thisMonthCount = $query->whereMonth('payment_date', now()->month)->count();
        $averageAmount = $paidCount > 0 ? $paidAmount / $paidCount : 0;

        $customers = Customer::where('status', 'active')->orderBy('name')->get();

        return view('invoices.paid', compact('invoices', 'paidCount', 'paidAmount', 'thisMonthCount', 'averageAmount', 'customers'));
    }

    public function export(Request $request)
    {
        // سيتم تنفيذ هذا لاحقاً
        return response()->json(['message' => 'Export functionality will be implemented']);
    }

    public function destroy($id)
    {
        $invoice = Invoice::findOrFail($id);

        // التحقق من عدم وجود تحصيلات
        if ($invoice->collections()->count() > 0) {
            return back()->with('error', 'لا يمكن حذف الفاتورة لوجود تحصيلات مرتبطة بها');
        }

        $invoice->delete();

        return redirect()->route('invoices.index')
            ->with('success', 'تم حذف الفاتورة بنجاح');
    }

    public function sendReminder($id)
    {
        // سيتم تنفيذ هذا لاحقاً
        return response()->json(['success' => true, 'message' => 'تم إرسال التذكير']);
    }

    public function sendBulkReminders(Request $request)
    {
        // سيتم تنفيذ هذا لاحقاً
        return response()->json(['success' => true, 'count' => 0, 'message' => 'تم إرسال التذكيرات']);
    }

    public function verify($id)
    {
        try {
            $invoice = Invoice::with(['customer', 'order.orderItems.item'])->findOrFail($id);

            // إرجاع بيانات الفاتورة للتحقق
            return response()->json([
                'success' => true,
                'invoice' => [
                    'invoice_number' => $invoice->invoice_number,
                    'customer_name' => $invoice->customer->name ?? 'غير محدد',
                    'total_amount' => number_format($invoice->total_amount, 2),
                    'issue_date' => $invoice->issue_date ? $invoice->issue_date->format('Y-m-d') : now()->format('Y-m-d'),
                    'due_date' => $invoice->due_date ? $invoice->due_date->format('Y-m-d') : now()->addDays(30)->format('Y-m-d'),
                    'status' => $invoice->status,
                    'status_text' => $this->getStatusText($invoice->status),
                    'items_count' => $invoice->order ? $invoice->order->orderItems->count() : 0,
                    'created_at' => $invoice->created_at->format('Y-m-d H:i:s')
                ],
                'message' => 'تم التحقق من صحة الفاتورة بنجاح'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'فاتورة غير صحيحة أو غير موجودة'
            ], 404);
        }
    }

    private function getStatusText($status)
    {
        switch ($status) {
            case 'paid':
                return 'مدفوعة';
            case 'pending':
                return 'معلقة';
            case 'partially_paid':
                return 'مدفوعة جزئياً';
            case 'overdue':
                return 'متأخرة';
            default:
                return 'غير محدد';
        }
    }
}
