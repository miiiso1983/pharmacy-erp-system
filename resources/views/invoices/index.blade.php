@extends('layouts.app')

@section('title', 'الفواتير - نظام إدارة المذاخر')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">الرئيسية</a></li>
    <li class="breadcrumb-item active">الفواتير</li>
@endsection

@section('content')
<!-- رسائل النجاح والخطأ -->
@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="fas fa-check-circle me-2"></i>
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

@if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="fas fa-exclamation-circle me-2"></i>
        {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>
                <i class="fas fa-file-invoice me-2"></i>
                الفواتير
            </h2>
            <div>
                <button type="button" class="btn btn-success me-2" data-bs-toggle="modal" data-bs-target="#createInvoiceModal">
                    <i class="fas fa-plus me-2"></i>
                    فاتورة جديدة
                </button>
                @can('view_invoices')
                    <a href="{{ route('invoices.pending') }}" class="btn btn-warning me-2">
                        <i class="fas fa-clock me-2"></i>
                        معلقة
                    </a>
                    <a href="{{ route('invoices.overdue') }}" class="btn btn-danger me-2">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        متأخرة
                    </a>
                @endcan
            </div>
        </div>
    </div>
</div>

<!-- إحصائيات سريعة -->
<div class="row mb-4">
    <div class="col-lg-3 col-md-6 mb-3">
        <div class="stat-card">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h4 class="mb-1">{{ $invoices->total() }}</h4>
                    <p class="mb-0">إجمالي الفواتير</p>
                </div>
                <div class="fs-1 opacity-75">
                    <i class="fas fa-file-invoice"></i>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-lg-3 col-md-6 mb-3">
        <div class="stat-card success">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h4 class="mb-1">{{ number_format($invoices->where('status', 'paid')->sum('total_amount'), 2) }}</h4>
                    <p class="mb-0">مدفوعة (دينار)</p>
                </div>
                <div class="fs-1 opacity-75">
                    <i class="fas fa-check-circle"></i>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-lg-3 col-md-6 mb-3">
        <div class="stat-card warning">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h4 class="mb-1">{{ number_format($invoices->where('status', 'pending')->sum('total_amount'), 2) }}</h4>
                    <p class="mb-0">معلقة (دينار)</p>
                </div>
                <div class="fs-1 opacity-75">
                    <i class="fas fa-clock"></i>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-lg-3 col-md-6 mb-3">
        <div class="stat-card danger">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h4 class="mb-1">{{ number_format($invoices->sum('remaining_amount'), 2) }}</h4>
                    <p class="mb-0">متبقية (دينار)</p>
                </div>
                <div class="fs-1 opacity-75">
                    <i class="fas fa-exclamation-triangle"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- فلاتر البحث -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <form method="GET" action="{{ route('invoices.index') }}">
                    <div class="row">
                        <div class="col-md-3">
                            <label class="form-label">رقم الفاتورة</label>
                            <input type="text" class="form-control" name="invoice_number" 
                                   value="{{ request('invoice_number') }}" placeholder="ابحث برقم الفاتورة">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">الحالة</label>
                            <select class="form-select" name="status">
                                <option value="">جميع الحالات</option>
                                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>معلقة</option>
                                <option value="paid" {{ request('status') == 'paid' ? 'selected' : '' }}>مدفوعة</option>
                                <option value="partially_paid" {{ request('status') == 'partially_paid' ? 'selected' : '' }}>مدفوعة جزئياً</option>
                                <option value="overdue" {{ request('status') == 'overdue' ? 'selected' : '' }}>متأخرة</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">من تاريخ</label>
                            <input type="date" class="form-control" name="from_date" value="{{ request('from_date') }}">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">إلى تاريخ</label>
                            <input type="date" class="form-control" name="to_date" value="{{ request('to_date') }}">
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-12">
                            <button type="submit" class="btn btn-primary me-2">
                                <i class="fas fa-search me-2"></i>بحث
                            </button>
                            <a href="{{ route('invoices.index') }}" class="btn btn-outline-secondary">
                                <i class="fas fa-times me-2"></i>إلغاء
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- جدول الفواتير -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-list me-2"></i>
                    قائمة الفواتير ({{ $invoices->total() }})
                </h5>
            </div>
            <div class="card-body">
                @if($invoices->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>رقم الفاتورة</th>
                                    <th>رقم الطلب</th>
                                    @if(Auth::user()->user_type !== 'customer')
                                        <th>العميل</th>
                                    @endif
                                    <th>الحالة</th>
                                    <th>المبلغ الإجمالي</th>
                                    <th>المبلغ المدفوع</th>
                                    <th>المبلغ المتبقي</th>
                                    <th>تاريخ الاستحقاق</th>
                                    <th>الإجراءات</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($invoices as $invoice)
                                    <tr class="{{ $invoice->status === 'overdue' ? 'table-danger' : '' }}">
                                        <td>
                                            <strong>{{ $invoice->invoice_number }}</strong>
                                        </td>
                                        <td>{{ $invoice->order->order_number }}</td>
                                        @if(Auth::user()->user_type !== 'customer')
                                            <td>
                                                <div>
                                                    <strong>{{ $invoice->customer->name }}</strong>
                                                    @if($invoice->customer->company_name)
                                                        <br><small class="text-muted">{{ $invoice->customer->company_name }}</small>
                                                    @endif
                                                </div>
                                            </td>
                                        @endif
                                        <td>
                                            @switch($invoice->status)
                                                @case('pending')
                                                    <span class="badge bg-warning">معلقة</span>
                                                    @break
                                                @case('paid')
                                                    <span class="badge bg-success">مدفوعة</span>
                                                    @break
                                                @case('partially_paid')
                                                    <span class="badge bg-info">مدفوعة جزئياً</span>
                                                    @break
                                                @case('overdue')
                                                    <span class="badge bg-danger">متأخرة</span>
                                                    @break
                                            @endswitch
                                        </td>
                                        <td>{{ number_format($invoice->total_amount, 2) }} دينار</td>
                                        <td>{{ number_format($invoice->paid_amount, 2) }} دينار</td>
                                        <td>
                                            <span class="badge {{ $invoice->remaining_amount > 0 ? 'bg-danger' : 'bg-success' }}">
                                                {{ number_format($invoice->remaining_amount, 2) }} دينار
                                            </span>
                                        </td>
                                        <td>
                                            @if($invoice->due_date)
                                                {{ $invoice->due_date->format('Y-m-d') }}
                                                @if($invoice->due_date->isPast() && $invoice->remaining_amount > 0)
                                                    <br><small class="text-danger">متأخرة</small>
                                                @endif
                                            @else
                                                غير محدد
                                            @endif
                                        </td>
                                        <td>
                                            <div class="btn-group btn-group-sm">
                                                <a href="{{ route('invoices.show', $invoice->id) }}" 
                                                   class="btn btn-outline-primary" title="عرض التفاصيل">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="{{ route('invoices.print', $invoice->id) }}" 
                                                   class="btn btn-outline-secondary" title="طباعة" target="_blank">
                                                    <i class="fas fa-print"></i>
                                                </a>
                                                @can('edit_invoices')
                                                    @if($invoice->remaining_amount > 0)
                                                        <form method="POST" action="{{ route('invoices.markAsPaid', $invoice->id) }}" 
                                                              class="d-inline" onsubmit="return confirm('هل أنت متأكد من وضع علامة مدفوع؟')">
                                                            @csrf
                                                            <button type="submit" class="btn btn-outline-success" title="وضع علامة مدفوع">
                                                                <i class="fas fa-check"></i>
                                                            </button>
                                                        </form>
                                                    @endif
                                                @endcan
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    
                    <!-- Pagination -->
                    <div class="d-flex justify-content-center mt-4">
                        {{ $invoices->links() }}
                    </div>
                @else
                    <div class="text-center py-5">
                        <i class="fas fa-file-invoice fa-3x text-muted mb-3"></i>
                        <h5 class="text-muted">لا توجد فواتير</h5>
                        <p class="text-muted">لم يتم العثور على أي فواتير تطابق معايير البحث</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Modal إنشاء فاتورة جديدة -->
<div class="modal fade" id="createInvoiceModal" tabindex="-1" aria-labelledby="createInvoiceModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="createInvoiceModalLabel">
                    <i class="fas fa-plus me-2"></i>
                    إنشاء فاتورة جديدة
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('invoices.store') }}" method="POST" id="createInvoiceForm">
                @csrf
                <div class="modal-body">
                    <div class="row">
                        <!-- معلومات الفاتورة الأساسية -->
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header">
                                    <h6 class="mb-0">معلومات الفاتورة</h6>
                                </div>
                                <div class="card-body">
                                    <div class="mb-3">
                                        <label for="invoice_number" class="form-label">رقم الفاتورة <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="invoice_number" name="invoice_number"
                                               placeholder="سيتم توليده تلقائياً" required>
                                    </div>

                                    <div class="mb-3">
                                        <label for="customer_id" class="form-label">العميل <span class="text-danger">*</span></label>
                                        <select class="form-select" id="customer_id" name="customer_id" required>
                                            <option value="">اختر العميل</option>
                                            @foreach(\App\Models\Customer::where('status', 'active')->get() as $customer)
                                                <option value="{{ $customer->id }}">{{ $customer->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="mb-3">
                                        <label for="invoice_date" class="form-label">تاريخ الفاتورة <span class="text-danger">*</span></label>
                                        <input type="date" class="form-control" id="invoice_date" name="invoice_date"
                                               value="{{ date('Y-m-d') }}" required>
                                    </div>

                                    <div class="mb-3">
                                        <label for="due_date" class="form-label">تاريخ الاستحقاق</label>
                                        <input type="date" class="form-control" id="due_date" name="due_date"
                                               value="{{ date('Y-m-d', strtotime('+30 days')) }}">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- ملخص الفاتورة -->
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header">
                                    <h6 class="mb-0">ملخص الفاتورة</h6>
                                </div>
                                <div class="card-body">
                                    <div class="mb-3">
                                        <label for="subtotal" class="form-label">المجموع الفرعي</label>
                                        <input type="number" class="form-control" id="subtotal" name="subtotal"
                                               value="0" step="0.01" readonly>
                                    </div>

                                    <div class="mb-3">
                                        <label for="tax_amount" class="form-label">الضريبة</label>
                                        <input type="number" class="form-control" id="tax_amount" name="tax_amount"
                                               value="0" step="0.01" onchange="calculateInvoiceTotal()">
                                    </div>

                                    <div class="mb-3">
                                        <label for="discount_amount" class="form-label">خصم إضافي</label>
                                        <input type="number" class="form-control" id="discount_amount" name="discount_amount"
                                               value="0" step="0.01" onchange="calculateInvoiceTotal()">
                                    </div>

                                    <div class="mb-3">
                                        <label for="total_amount" class="form-label">المجموع الإجمالي</label>
                                        <input type="number" class="form-control" id="total_amount" name="total_amount"
                                               value="0" step="0.01" readonly>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- أصناف الفاتورة -->
                    <div class="row mt-4">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header d-flex justify-content-between align-items-center">
                                    <h6 class="mb-0">أصناف الفاتورة</h6>
                                    <button type="button" class="btn btn-sm btn-primary" onclick="addInvoiceItem()">
                                        <i class="fas fa-plus me-1"></i>
                                        إضافة صنف
                                    </button>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-bordered" id="invoiceItemsTable">
                                            <thead class="table-light">
                                                <tr>
                                                    <th width="25%">الصنف</th>
                                                    <th width="10%">الكمية</th>
                                                    <th width="10%">مجاني</th>
                                                    <th width="12%">السعر</th>
                                                    <th width="10%">خصم %</th>
                                                    <th width="12%">السعر الصافي</th>
                                                    <th width="15%">المجموع</th>
                                                    <th width="6%">إجراء</th>
                                                </tr>
                                            </thead>
                                            <tbody id="invoiceItemsBody">
                                                <!-- سيتم إضافة الأصناف هنا ديناميكياً -->
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- ملاحظات -->
                    <div class="row mt-3">
                        <div class="col-12">
                            <div class="mb-3">
                                <label for="notes" class="form-label">ملاحظات</label>
                                <textarea class="form-control" id="notes" name="notes" rows="3"
                                          placeholder="ملاحظات إضافية للفاتورة..."></textarea>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-save me-2"></i>
                        حفظ الفاتورة
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .table-danger {
        background-color: rgba(220, 53, 69, 0.1) !important;
    }

    .modal-xl {
        max-width: 1200px;
    }

    #invoiceItemsTable th {
        font-size: 0.875rem;
        padding: 0.5rem;
    }

    #invoiceItemsTable td {
        padding: 0.5rem;
    }

    #invoiceItemsTable input {
        font-size: 0.875rem;
    }
</style>
@endpush

@push('scripts')
<script>
let itemCounter = 0;

function addInvoiceItem() {
    itemCounter++;
    const tbody = document.getElementById('invoiceItemsBody');
    const row = document.createElement('tr');
    row.id = `item-row-${itemCounter}`;

    row.innerHTML = `
        <td>
            <input type="text" name="items[${itemCounter}][description]"
                   class="form-control form-control-sm" placeholder="وصف الصنف" required>
        </td>
        <td>
            <input type="number" name="items[${itemCounter}][quantity]"
                   class="form-control form-control-sm item-quantity"
                   value="1" min="1" step="1"
                   onchange="calculateItemTotal(${itemCounter})" required>
        </td>
        <td>
            <input type="number" name="items[${itemCounter}][free_quantity]"
                   class="form-control form-control-sm item-free-quantity"
                   value="0" min="0" step="1"
                   onchange="calculateItemTotal(${itemCounter})"
                   placeholder="0">
        </td>
        <td>
            <input type="number" name="items[${itemCounter}][unit_price]"
                   class="form-control form-control-sm item-unit-price"
                   value="0" min="0" step="0.01"
                   onchange="calculateItemTotal(${itemCounter})" required>
        </td>
        <td>
            <input type="number" name="items[${itemCounter}][discount_percentage]"
                   class="form-control form-control-sm item-discount-percentage"
                   value="0" min="0" max="100" step="0.01"
                   onchange="calculateItemTotal(${itemCounter})"
                   placeholder="0">
        </td>
        <td>
            <input type="number" name="items[${itemCounter}][net_price]"
                   class="form-control form-control-sm item-net-price"
                   value="0" readonly>
        </td>
        <td>
            <input type="number" name="items[${itemCounter}][total]"
                   class="form-control form-control-sm item-total"
                   value="0" readonly>
        </td>
        <td>
            <button type="button" class="btn btn-danger btn-sm"
                    onclick="removeInvoiceItem(${itemCounter})" title="حذف">
                <i class="fas fa-trash"></i>
            </button>
        </td>
    `;

    tbody.appendChild(row);
}

function removeInvoiceItem(itemId) {
    const row = document.getElementById(`item-row-${itemId}`);
    if (row) {
        row.remove();
        calculateSubtotal();
    }
}

function calculateItemTotal(itemId) {
    const quantityInput = document.querySelector(`input[name="items[${itemId}][quantity]"]`);
    const freeQuantityInput = document.querySelector(`input[name="items[${itemId}][free_quantity]"]`);
    const unitPriceInput = document.querySelector(`input[name="items[${itemId}][unit_price]"]`);
    const discountPercentageInput = document.querySelector(`input[name="items[${itemId}][discount_percentage]"]`);
    const netPriceInput = document.querySelector(`input[name="items[${itemId}][net_price]"]`);
    const totalInput = document.querySelector(`input[name="items[${itemId}][total]"]`);

    const quantity = parseFloat(quantityInput.value) || 0;
    const freeQuantity = parseFloat(freeQuantityInput.value) || 0;
    const unitPrice = parseFloat(unitPriceInput.value) || 0;
    const discountPercentage = parseFloat(discountPercentageInput.value) || 0;

    // حساب مبلغ الخصم
    const discountAmount = (unitPrice * discountPercentage) / 100;

    // حساب السعر الصافي بعد الخصم
    const netPrice = unitPrice - discountAmount;
    netPriceInput.value = netPrice.toFixed(2);

    // حساب المجموع (الكمية المدفوعة فقط × السعر الصافي)
    const total = quantity * netPrice;
    totalInput.value = total.toFixed(2);

    // تحديث معلومات إضافية في tooltip
    const totalQuantity = quantity + freeQuantity;
    const freeValue = freeQuantity * unitPrice;

    totalInput.title = `الكمية الإجمالية: ${totalQuantity}\nقيمة المجاني: ${freeValue.toFixed(2)}\nقيمة الخصم: ${(quantity * discountAmount).toFixed(2)}`;

    calculateSubtotal();
}

function calculateSubtotal() {
    const totalInputs = document.querySelectorAll('.item-total');
    let subtotal = 0;

    totalInputs.forEach(input => {
        subtotal += parseFloat(input.value) || 0;
    });

    document.getElementById('subtotal').value = subtotal.toFixed(2);
    calculateInvoiceTotal();
}

function calculateInvoiceTotal() {
    const subtotal = parseFloat(document.getElementById('subtotal').value) || 0;
    const taxAmount = parseFloat(document.getElementById('tax_amount').value) || 0;
    const discountAmount = parseFloat(document.getElementById('discount_amount').value) || 0;

    const total = subtotal + taxAmount - discountAmount;
    document.getElementById('total_amount').value = total.toFixed(2);
}

// إضافة صنف واحد افتراضي عند فتح Modal
document.getElementById('createInvoiceModal').addEventListener('shown.bs.modal', function () {
    if (document.getElementById('invoiceItemsBody').children.length === 0) {
        addInvoiceItem();
    }

    // توليد رقم فاتورة جديد
    generateInvoiceNumber();
});

function generateInvoiceNumber() {
    const now = new Date();
    const year = now.getFullYear();
    const randomNum = Math.floor(Math.random() * 999999) + 1;
    const invoiceNumber = `INV-${year}-${randomNum.toString().padStart(6, '0')}`;
    document.getElementById('invoice_number').value = invoiceNumber;
}

// تنظيف النموذج عند إغلاق Modal
document.getElementById('createInvoiceModal').addEventListener('hidden.bs.modal', function () {
    document.getElementById('createInvoiceForm').reset();
    document.getElementById('invoiceItemsBody').innerHTML = '';
    itemCounter = 0;
    // إعادة تعيين القيم الافتراضية
    document.getElementById('subtotal').value = '0.00';
    document.getElementById('tax_amount').value = '0.00';
    document.getElementById('discount_amount').value = '0.00';
    document.getElementById('total_amount').value = '0.00';
});

// التحقق من صحة النموذج قبل الإرسال
document.getElementById('createInvoiceForm').addEventListener('submit', function(e) {
    const subtotal = parseFloat(document.getElementById('subtotal').value) || 0;
    const itemsCount = document.getElementById('invoiceItemsBody').children.length;

    if (subtotal <= 0 || itemsCount === 0) {
        e.preventDefault();
        alert('يجب إضافة أصناف للفاتورة قبل الحفظ');
        return false;
    }

    // إظهار مؤشر التحميل
    const submitBtn = this.querySelector('button[type="submit"]');
    const originalText = submitBtn.innerHTML;
    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>جاري الحفظ...';
    submitBtn.disabled = true;

    // إعادة تفعيل الزر في حالة فشل الإرسال
    setTimeout(() => {
        submitBtn.innerHTML = originalText;
        submitBtn.disabled = false;
    }, 10000);
});
</script>
@endpush
