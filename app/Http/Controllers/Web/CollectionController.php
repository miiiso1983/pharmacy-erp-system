<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Collection;
use App\Models\Customer;
use App\Models\Invoice;
use App\Services\WhatsAppService;
use App\Services\CollectionDocumentService;
use App\Services\SimpleCollectionService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class CollectionController extends Controller
{
    /**
     * عرض قائمة التحصيلات
     */
    public function index(Request $request)
    {
        try {
            $query = Collection::query();

            // تطبيق الفلاتر
            if ($request->has('customer_id') && $request->customer_id) {
                $query->where('customer_id', $request->customer_id);
            }

            if ($request->has('status') && $request->status) {
                $query->where('status', $request->status);
            }

            if ($request->has('date_from') && $request->date_from) {
                $query->whereDate('collection_date', '>=', $request->date_from);
            }

            if ($request->has('date_to') && $request->date_to) {
                $query->whereDate('collection_date', '<=', $request->date_to);
            }

            if ($request->has('search') && $request->search) {
                $search = $request->search;
                $query->where(function($q) use ($search) {
                    $q->where('collection_number', 'like', '%' . $search . '%')
                      ->orWhereHas('customer', function($customerQuery) use ($search) {
                          $customerQuery->where('name', 'like', '%' . $search . '%');
                      });
                });
            }

            // تحميل العلاقات بشكل آمن
            $collections = $query->with(['customer' => function($query) {
                                    $query->select('id', 'name', 'customer_code', 'phone', 'company_name');
                                }, 'invoice' => function($query) {
                                    $query->select('id', 'invoice_number', 'total_amount', 'paid_amount');
                                }, 'collectedBy' => function($query) {
                                    $query->select('id', 'name', 'phone', 'email', 'user_type');
                                }])
                                ->orderBy('collection_date', 'desc')
                                ->paginate(20);

            // إحصائيات
            $stats = [
                'total_collections' => Collection::count(),
                'pending_collections' => Collection::where('status', 'pending')->count(),
                'completed_collections' => Collection::where('status', 'completed')->count(),
                'total_amount' => Collection::where('status', 'completed')->sum('amount'),
            ];

            // جلب العملاء للفلتر
            $customers = Customer::select('id', 'name', 'customer_code')
                               ->where('status', 'active')
                               ->orderBy('name')
                               ->get();

            return view('collections.index', compact('collections', 'stats', 'customers'));

        } catch (\Exception $e) {
            \Log::error('Collections index error', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return back()->withErrors(['error' => 'حدث خطأ أثناء تحميل التحصيلات: ' . $e->getMessage()]);
        }
    }

    /**
     * عرض نموذج إنشاء تحصيل جديد
     */
    public function create(Request $request)
    {
        try {
            $customers = Customer::where('status', 'active')->orderBy('name')->get();
            $invoices = [];
            
            // إذا تم تحديد عميل، جلب فواتيره غير المدفوعة
            if ($request->has('customer_id') && $request->customer_id) {
                $invoices = Invoice::where('customer_id', $request->customer_id)
                                  ->whereIn('status', ['pending', 'partially_paid'])
                                  ->orderBy('created_at', 'desc')
                                  ->get();
            }

            return view('collections.create', compact('customers', 'invoices'));

        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'حدث خطأ أثناء تحميل الصفحة: ' . $e->getMessage()]);
        }
    }

    /**
     * حفظ تحصيل جديد
     */
    public function store(Request $request)
    {
        try {
            $request->validate([
                'customer_id' => 'required|exists:customers,id',
                'amount' => 'required|numeric|min:0.01',
                'collection_date' => 'required|date',
                'payment_method' => 'required|in:cash,bank_transfer,check,credit_card',
                'reference_number' => 'nullable|string|max:100',
                'notes' => 'nullable|string',
                'invoice_id' => 'nullable|exists:invoices,id',
            ], [
                'customer_id.required' => 'يرجى اختيار العميل',
                'customer_id.exists' => 'العميل المحدد غير موجود',
                'amount.required' => 'يرجى إدخال المبلغ',
                'amount.numeric' => 'المبلغ يجب أن يكون رقماً',
                'amount.min' => 'المبلغ يجب أن يكون أكبر من صفر',
                'collection_date.required' => 'يرجى إدخال تاريخ التحصيل',
                'collection_date.date' => 'تاريخ التحصيل غير صحيح',
                'payment_method.required' => 'يرجى اختيار طريقة الدفع',
                'payment_method.in' => 'طريقة الدفع غير صحيحة',
            ]);

            DB::beginTransaction();

            // إنشاء رقم التحصيل
            $collectionNumber = 'COL-' . date('Y') . '-' . str_pad(Collection::count() + 1, 6, '0', STR_PAD_LEFT);

            // إنشاء التحصيل
            $collection = Collection::create([
                'collection_number' => $collectionNumber,
                'customer_id' => $request->customer_id,
                'invoice_id' => $request->invoice_id ?: null,
                'amount' => $request->amount,
                'collection_date' => $request->collection_date,
                'payment_method' => $request->payment_method,
                'reference_number' => $request->reference_number ?: null,
                'notes' => $request->notes ?: null,
                'status' => 'completed',
                'collected_by' => auth()->id(),
            ]);

            // تحديث رصيد العميل
            $customer = Customer::find($request->customer_id);
            $customer->current_balance -= $request->amount;
            $customer->total_payments += $request->amount;
            $customer->last_payment_date = $request->collection_date;
            $customer->save();

            // إذا كان التحصيل مرتبط بفاتورة، تحديث حالة الفاتورة
            if ($request->invoice_id) {
                $invoice = Invoice::find($request->invoice_id);
                $invoice->paid_amount += $request->amount;
                
                if ($invoice->paid_amount >= $invoice->total_amount) {
                    $invoice->status = 'paid';
                } else {
                    $invoice->status = 'partially_paid';
                }
                
                $invoice->save();
            }

            DB::commit();

            // إرسال رسالة واتساب مع مستند الاستحصال إذا كان مطلوباً
            if ($request->has('send_whatsapp') && $request->send_whatsapp) {
                $this->sendWhatsAppNotification($collection);
            }

            return redirect()->route('collections.index')
                           ->with('success', 'تم إنشاء التحصيل بنجاح برقم: ' . $collectionNumber);

        } catch (\Exception $e) {
            DB::rollback();
            return back()->withErrors(['error' => 'حدث خطأ أثناء حفظ التحصيل: ' . $e->getMessage()])
                        ->withInput();
        }
    }

    /**
     * عرض تفاصيل التحصيل
     */
    public function show($id)
    {
        try {
            $collection = Collection::with(['customer', 'invoice'])->findOrFail($id);
            
            return view('collections.show', compact('collection'));

        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'حدث خطأ أثناء تحميل التحصيل: ' . $e->getMessage()]);
        }
    }

    /**
     * عرض نموذج تعديل التحصيل
     */
    public function edit($id)
    {
        try {
            $collection = Collection::with(['customer', 'invoice'])->findOrFail($id);
            $customers = Customer::where('status', 'active')->orderBy('name')->get();
            $invoices = Invoice::where('customer_id', $collection->customer_id)
                              ->whereIn('status', ['pending', 'partially_paid'])
                              ->orderBy('created_at', 'desc')
                              ->get();

            return view('collections.edit', compact('collection', 'customers', 'invoices'));

        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'حدث خطأ أثناء تحميل التحصيل: ' . $e->getMessage()]);
        }
    }

    /**
     * تحديث التحصيل
     */
    public function update(Request $request, $id)
    {
        try {
            $collection = Collection::findOrFail($id);

            $request->validate([
                'amount' => 'required|numeric|min:0.01',
                'collection_date' => 'required|date',
                'payment_method' => 'required|in:cash,bank_transfer,check,credit_card',
                'reference_number' => 'nullable|string|max:100',
                'notes' => 'nullable|string',
                'status' => 'required|in:pending,completed,cancelled',
            ]);

            DB::beginTransaction();

            // حفظ القيم القديمة
            $oldAmount = $collection->amount;
            $oldStatus = $collection->status;

            // تحديث التحصيل
            $collection->update([
                'amount' => $request->amount,
                'collection_date' => $request->collection_date,
                'payment_method' => $request->payment_method,
                'reference_number' => $request->reference_number,
                'notes' => $request->notes,
                'status' => $request->status,
            ]);

            // تحديث رصيد العميل إذا تغير المبلغ أو الحالة
            if ($oldAmount != $request->amount || $oldStatus != $request->status) {
                $customer = $collection->customer;
                
                // إلغاء التأثير القديم
                if ($oldStatus == 'completed') {
                    $customer->current_balance += $oldAmount;
                    $customer->total_payments -= $oldAmount;
                }
                
                // تطبيق التأثير الجديد
                if ($request->status == 'completed') {
                    $customer->current_balance -= $request->amount;
                    $customer->total_payments += $request->amount;
                    $customer->last_payment_date = $request->collection_date;
                }
                
                $customer->save();
            }

            DB::commit();

            return redirect()->route('collections.index')
                           ->with('success', 'تم تحديث التحصيل بنجاح');

        } catch (\Exception $e) {
            DB::rollback();
            return back()->withErrors(['error' => 'حدث خطأ أثناء تحديث التحصيل: ' . $e->getMessage()])
                        ->withInput();
        }
    }

    /**
     * حذف التحصيل
     */
    public function destroy($id)
    {
        try {
            $collection = Collection::findOrFail($id);

            DB::beginTransaction();

            // إلغاء تأثير التحصيل على رصيد العميل
            if ($collection->status == 'completed') {
                $customer = $collection->customer;
                $customer->current_balance += $collection->amount;
                $customer->total_payments -= $collection->amount;
                $customer->save();

                // إلغاء تأثير التحصيل على الفاتورة
                if ($collection->invoice_id) {
                    $invoice = $collection->invoice;
                    $invoice->paid_amount -= $collection->amount;
                    
                    if ($invoice->paid_amount <= 0) {
                        $invoice->status = 'pending';
                    } else {
                        $invoice->status = 'partially_paid';
                    }
                    
                    $invoice->save();
                }
            }

            $collection->delete();

            DB::commit();

            return redirect()->route('collections.index')
                           ->with('success', 'تم حذف التحصيل بنجاح');

        } catch (\Exception $e) {
            DB::rollback();
            return back()->withErrors(['error' => 'حدث خطأ أثناء حذف التحصيل: ' . $e->getMessage()]);
        }
    }

    /**
     * جلب فواتير العميل غير المدفوعة
     */
    public function getCustomerInvoices(Request $request)
    {
        try {
            $customerId = $request->customer_id;
            
            $invoices = Invoice::where('customer_id', $customerId)
                              ->whereIn('status', ['pending', 'partially_paid'])
                              ->orderBy('created_at', 'desc')
                              ->get(['id', 'invoice_number', 'total_amount', 'paid_amount', 'created_at']);

            return response()->json([
                'success' => true,
                'invoices' => $invoices
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => 'حدث خطأ أثناء جلب الفواتير'
            ]);
        }
    }

    /**
     * إرسال إشعار واتساب مع مستند الاستحصال
     */
    private function sendWhatsAppNotification(Collection $collection)
    {
        try {
            // التحقق من وجود رقم هاتف للعميل
            if (empty($collection->customer->phone)) {
                \Log::warning('Customer has no phone number for WhatsApp', [
                    'collection_id' => $collection->id,
                    'customer_id' => $collection->customer_id
                ]);
                return;
            }

            $whatsappService = new WhatsAppService();
            $documentService = new CollectionDocumentService();

            // التحقق من إعدادات الواتساب
            $configCheck = $whatsappService->checkConfiguration();
            if (!$configCheck['configured']) {
                \Log::warning('WhatsApp not configured', $configCheck['errors']);
                return;
            }

            // التحقق من صحة رقم الهاتف
            if (!$whatsappService->validatePhoneNumber($collection->customer->phone)) {
                \Log::warning('Invalid phone number for WhatsApp', [
                    'collection_id' => $collection->id,
                    'phone' => $collection->customer->phone
                ]);
                return;
            }

            // إنتاج مستند الاستحصال
            $documentResult = $documentService->generateCollectionDocument($collection);

            if (!$documentResult['success']) {
                \Log::error('Failed to generate collection document', [
                    'collection_id' => $collection->id,
                    'error' => $documentResult['error']
                ]);
                return;
            }

            // إعداد الرسالة
            $message = $this->prepareWhatsAppMessage($collection);

            // إرسال المستند مع الرسالة
            $result = $whatsappService->sendDocument(
                $collection->customer->phone,
                $documentResult['full_url'],
                $documentResult['filename'],
                $message
            );

            if ($result['success']) {
                \Log::info('WhatsApp collection document sent successfully', [
                    'collection_id' => $collection->id,
                    'customer_phone' => $collection->customer->phone,
                    'message_id' => $result['message_id']
                ]);

                // حفظ معلومات الإرسال في قاعدة البيانات (اختياري)
                $this->saveWhatsAppLog($collection, $result, 'document');

            } else {
                \Log::error('Failed to send WhatsApp collection document', [
                    'collection_id' => $collection->id,
                    'customer_phone' => $collection->customer->phone,
                    'error' => $result['error']
                ]);
            }

        } catch (\Exception $e) {
            \Log::error('WhatsApp notification error', [
                'collection_id' => $collection->id,
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * إعداد نص رسالة الواتساب
     */
    private function prepareWhatsAppMessage(Collection $collection)
    {
        $template = config('whatsapp.messages.collection_with_document');

        $message = str_replace([
            '{{number}}',
            '{{amount}}',
            '{{date}}',
            '{{customer_name}}'
        ], [
            $collection->collection_number,
            number_format($collection->amount, 0),
            $collection->collection_date->format('Y/m/d'),
            $collection->customer->name
        ], $template);

        return $message;
    }

    /**
     * حفظ سجل إرسال الواتساب
     */
    private function saveWhatsAppLog(Collection $collection, $result, $type = 'message')
    {
        try {
            // يمكن إنشاء جدول منفصل لسجلات الواتساب
            \DB::table('whatsapp_logs')->insert([
                'model_type' => 'App\Models\Collection',
                'model_id' => $collection->id,
                'phone_number' => $collection->customer->phone,
                'message_type' => $type,
                'message_id' => $result['message_id'] ?? null,
                'status' => $result['success'] ? 'sent' : 'failed',
                'response_data' => json_encode($result),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        } catch (\Exception $e) {
            \Log::error('Failed to save WhatsApp log', [
                'collection_id' => $collection->id,
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * تحميل مستند الاستحصال
     */
    public function downloadDocument($id)
    {
        try {
            $collection = Collection::with(['customer', 'invoice', 'collectedBy'])->findOrFail($id);

            // التحقق من وجود العميل
            if (!$collection->customer) {
                return response()->json(['error' => 'بيانات العميل غير موجودة'], 400);
            }

            $documentService = new SimpleCollectionService();

            // إنتاج المستند
            $result = $documentService->generateCollectionDocument($collection);

            if (!$result['success']) {
                return response()->json(['error' => $result['error']], 500);
            }

            // إذا كان المستند HTML، إعادة توجيه للعرض
            if (str_contains($result['filename'], '.html')) {
                $url = url('/storage/' . $result['path']);
                return redirect($url);
            }

            // تحميل المستند PDF
            $filePath = storage_path('app/public/' . $result['path']);

            if (!file_exists($filePath)) {
                return response()->json(['error' => 'الملف غير موجود'], 404);
            }

            return response()->download(
                $filePath,
                $result['filename'],
                [
                    'Content-Type' => 'application/pdf',
                ]
            );

        } catch (\Exception $e) {
            Log::error('Document download error', [
                'collection_id' => $id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json(['error' => 'حدث خطأ أثناء تحميل المستند: ' . $e->getMessage()], 500);
        }
    }
}
