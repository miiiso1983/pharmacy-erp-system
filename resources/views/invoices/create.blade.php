@extends('layouts.app')

@section('title', __('invoices.create_invoice') . ' - ' . __('app.name'))

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">{{ __('app.home') }}</a></li>
    <li class="breadcrumb-item"><a href="{{ route('invoices.index') }}">{{ __('invoices.title') }}</a></li>
    <li class="breadcrumb-item active">{{ __('invoices.create_invoice') }}</li>
@endsection

@section('content')
<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>
                <i class="fas fa-plus-circle me-2 text-primary"></i>
                {{ __('invoices.create_invoice') }}
            </h2>
            <a href="{{ route('invoices.index') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left me-2"></i>
                {{ __('app.back') }}
            </a>
        </div>
    </div>
</div>

<form action="{{ route('invoices.store') }}" method="POST" id="invoiceForm">
    @csrf
    
    <!-- معلومات الفاتورة الأساسية -->
    <div class="row mb-4">
        <div class="col-lg-8">
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-info-circle me-2"></i>
                        معلومات الفاتورة
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="invoice_number" class="form-label">{{ __('invoices.invoice_number') }} <span class="text-danger">*</span></label>
                            <input type="text" name="invoice_number" id="invoice_number" 
                                   class="form-control @error('invoice_number') is-invalid @enderror" 
                                   value="{{ old('invoice_number', 'INV-' . date('Y') . '-' . str_pad(rand(1, 999999), 6, '0', STR_PAD_LEFT)) }}" 
                                   required>
                            @error('invoice_number')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="invoice_date" class="form-label">{{ __('invoices.invoice_date') }} <span class="text-danger">*</span></label>
                            <input type="date" name="invoice_date" id="invoice_date" 
                                   class="form-control @error('invoice_date') is-invalid @enderror" 
                                   value="{{ old('invoice_date', date('Y-m-d')) }}" 
                                   required>
                            @error('invoice_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="customer_id" class="form-label">{{ __('app.customer') }} <span class="text-danger">*</span></label>
                            <select name="customer_id" id="customer_id"
                                    class="form-select customer-select searchable-ajax @error('customer_id') is-invalid @enderror"
                                    data-ajax-url="/api/search/customers"
                                    placeholder="{{ __('customers.select_customer') }}"
                                    required>
                                <option value="">{{ __('customers.select_customer') }}</option>
                                @if(old('customer_id'))
                                    @php
                                        $selectedCustomer = \App\Models\Customer::find(old('customer_id'));
                                    @endphp
                                    @if($selectedCustomer)
                                        <option value="{{ $selectedCustomer->id }}" selected>
                                            {{ $selectedCustomer->name }}
                                            @if($selectedCustomer->customer_code)
                                                ({{ $selectedCustomer->customer_code }})
                                            @endif
                                        </option>
                                    @endif
                                @endif
                            </select>
                            @error('customer_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="due_date" class="form-label">{{ __('invoices.due_date') }}</label>
                            <input type="date" name="due_date" id="due_date" 
                                   class="form-control @error('due_date') is-invalid @enderror" 
                                   value="{{ old('due_date', date('Y-m-d', strtotime('+30 days'))) }}">
                            @error('due_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="order_id" class="form-label">{{ __('orders.related_order') }}</label>
                            <select name="order_id" id="order_id"
                                    class="form-select order-select searchable-ajax @error('order_id') is-invalid @enderror"
                                    data-ajax-url="/api/search/orders"
                                    placeholder="{{ __('orders.no_related_order') }}">
                                <option value="">{{ __('orders.no_related_order') }}</option>
                                @foreach($orders as $order)
                                    <option value="{{ $order->id }}" {{ old('order_id') == $order->id ? 'selected' : '' }}
                                            data-order-number="{{ $order->order_number }}"
                                            data-customer-name="{{ $order->customer->name ?? 'غير محدد' }}"
                                            data-total-amount="{{ $order->total_amount }}">
                                        {{ $order->order_number }} - {{ $order->customer->name ?? 'غير محدد' }}
                                        ({{ number_format($order->total_amount, 0) }} {{ __('app.iqd') }})
                                    </option>
                                @endforeach
                            </select>
                            @error('order_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-12 mb-3">
                            <label for="notes" class="form-label">{{ __('app.notes') }}</label>
                            <textarea name="notes" id="notes" rows="3" 
                                      class="form-control @error('notes') is-invalid @enderror" 
                                      placeholder="ملاحظات إضافية...">{{ old('notes') }}</textarea>
                            @error('notes')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- ملخص الفاتورة -->
        <div class="col-lg-4">
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
                                <!-- صف افتراضي لإضافة العناصر -->
                                <tr id="item-row-1">
                                    <td>
                                        <select name="items[1][item_id]"
                                                class="form-select item-select searchable-ajax item-select-1"
                                                data-ajax-url="/api/search/items"
                                                onchange="updateItemDetails(1)" required>
                                            <option value="">اختر الصنف...</option>
                                        </select>
                                        <input type="hidden" name="items[1][description]"
                                               class="item-description-1">
                                    </td>
                                    <td>
                                        <input type="number" name="items[1][quantity]"
                                               class="form-control item-quantity"
                                               min="1" value="1"
                                               onchange="calculateItemTotal(1)" required>
                                    </td>
                                    <td>
                                        <input type="number" name="items[1][free_quantity]"
                                               class="form-control item-free-quantity"
                                               min="0" value="0"
                                               onchange="calculateItemTotal(1)">
                                    </td>
                                    <td>
                                        <input type="number" name="items[1][unit_price]"
                                               class="form-control item-unit-price"
                                               step="0.01" min="0"
                                               onchange="calculateItemTotal(1)" required>
                                    </td>
                                    <td>
                                        <input type="number" name="items[1][discount_percentage]"
                                               class="form-control item-discount"
                                               step="0.01" min="0" max="100" value="0"
                                               onchange="calculateItemTotal(1)">
                                    </td>
                                    <td>
                                        <input type="number" name="items[1][net_price]"
                                               class="form-control item-net-price"
                                               step="0.01" readonly>
                                    </td>
                                    <td>
                                        <input type="number" name="items[1][total]"
                                               class="form-control item-total"
                                               value="0" readonly>
                                    </td>
                                    <td>
                                        <button type="button" class="btn btn-danger btn-sm"
                                                onclick="removeInvoiceItem(1)" title="حذف">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    
                    <div class="alert alert-info mt-3" id="noItemsAlert" style="display: none;">
                        <i class="fas fa-info-circle me-2"></i>
                        انقر على "إضافة منتج" لبدء إضافة منتجات الفاتورة
                    </div>
                </div>
            </div>
        </div>
        
        <!-- ملخص الفاتورة -->
        <div class="col-lg-4">
            <div class="card sticky-top" style="top: 20px;">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-calculator me-2"></i>
                        ملخص الفاتورة
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-6">
                            <label for="subtotal" class="form-label">المجموع الفرعي:</label>
                        </div>
                        <div class="col-6">
                            <input type="number" name="subtotal" id="subtotal" 
                                   class="form-control @error('subtotal') is-invalid @enderror" 
                                   value="{{ old('subtotal', 0) }}" 
                                   step="0.01" min="0" required readonly>
                            @error('subtotal')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-6">
                            <label for="tax_amount" class="form-label">الضريبة:</label>
                        </div>
                        <div class="col-6">
                            <input type="number" name="tax_amount" id="tax_amount" 
                                   class="form-control @error('tax_amount') is-invalid @enderror" 
                                   value="{{ old('tax_amount', 0) }}" 
                                   step="0.01" min="0" onchange="calculateTotal()">
                            @error('tax_amount')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-6">
                            <label for="discount_amount" class="form-label">الخصم:</label>
                        </div>
                        <div class="col-6">
                            <input type="number" name="discount_amount" id="discount_amount" 
                                   class="form-control @error('discount_amount') is-invalid @enderror" 
                                   value="{{ old('discount_amount', 0) }}" 
                                   step="0.01" min="0" onchange="calculateTotal()">
                            @error('discount_amount')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <hr>
                    
                    <div class="row mb-3">
                        <div class="col-6">
                            <strong>المجموع الإجمالي:</strong>
                        </div>
                        <div class="col-6">
                            <div class="h5 text-primary mb-0" id="totalAmountDisplay">0 {{ __('app.iqd') }}</div>
                            <input type="hidden" name="total_amount" id="total_amount" value="0">
                        </div>
                    </div>
                    
                    <hr>
                    
                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-primary btn-lg">
                            <i class="fas fa-save me-2"></i>
                            {{ __('invoices.create_invoice') }}
                        </button>
                        <a href="{{ route('invoices.index') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-times me-2"></i>
                            {{ __('app.cancel') }}
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- عناصر الفاتورة - عرض كامل -->
    <div class="row">
        <div class="col-12">
            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="fas fa-list me-2"></i>
                        عناصر الفاتورة
                    </h5>
                    <button type="button" class="btn btn-primary btn-sm" onclick="addInvoiceItem()">
                        <i class="fas fa-plus me-2"></i>
                        إضافة منتج
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
                                    <th width="15%">السعر</th>
                                    <th width="10%">خصم %</th>
                                    <th width="15%">السعر الصافي</th>
                                    <th width="15%">المجموع</th>
                                    <th width="8%">إجراء</th>
                                </tr>
                            </thead>
                            <tbody id="invoiceItemsBody">
                                <!-- صف افتراضي لإضافة العناصر -->
                                <tr id="item-row-1">
                                    <td>
                                        <select name="items[1][item_id]"
                                                class="form-select item-select searchable-ajax item-select-1"
                                                data-ajax-url="/api/search/items"
                                                onchange="updateItemDetails(1)" required>
                                            <option value="">اختر الصنف...</option>
                                        </select>
                                        <input type="hidden" name="items[1][description]"
                                               class="item-description-1">
                                    </td>
                                    <td>
                                        <input type="number" name="items[1][quantity]"
                                               class="form-control item-quantity"
                                               min="1" value="1"
                                               onchange="calculateItemTotal(1)" required>
                                    </td>
                                    <td>
                                        <input type="number" name="items[1][free_quantity]"
                                               class="form-control item-free-quantity"
                                               min="0" value="0"
                                               onchange="calculateItemTotal(1)">
                                    </td>
                                    <td>
                                        <input type="number" name="items[1][unit_price]"
                                               class="form-control item-unit-price"
                                               step="0.01" min="0" value="0"
                                               onchange="calculateItemTotal(1)" required>
                                    </td>
                                    <td>
                                        <input type="number" name="items[1][discount_percentage]"
                                               class="form-control item-discount-percentage"
                                               step="0.01" min="0" max="100" value="0"
                                               onchange="calculateItemTotal(1)">
                                    </td>
                                    <td>
                                        <input type="number" name="items[1][net_price]"
                                               class="form-control item-net-price"
                                               step="0.01" min="0" value="0" readonly>
                                    </td>
                                    <td>
                                        <input type="number" name="items[1][total]"
                                               class="form-control item-total"
                                               step="0.01" min="0" value="0" readonly>
                                    </td>
                                    <td>
                                        <button type="button" class="btn btn-danger btn-sm"
                                                onclick="removeInvoiceItem(1)" title="حذف">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <!-- تنبيه عدم وجود عناصر -->
                    <div id="noItemsAlert" class="alert alert-info text-center" style="display: none;">
                        <i class="fas fa-info-circle me-2"></i>
                        انقر على "إضافة منتج" لبدء إضافة منتجات الفاتورة
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>
@endsection

@push('scripts')
<script>
let itemCounter = 1;

function addInvoiceItem() {
    itemCounter++;
    const tbody = document.getElementById('invoiceItemsBody');
    const noItemsAlert = document.getElementById('noItemsAlert');
    
    const row = document.createElement('tr');
    row.id = `item-row-${itemCounter}`;
    row.innerHTML = `
        <td>
            <select name="items[${itemCounter}][item_id]"
                    class="form-select item-select searchable-ajax item-select-${itemCounter}"
                    data-ajax-url="/api/search/items"
                    onchange="updateItemDetails(${itemCounter})" required>
                <option value="">اختر الصنف...</option>
            </select>
            <input type="hidden" name="items[${itemCounter}][description]"
                   class="item-description-${itemCounter}">
        </td>
        <td>
            <input type="number" name="items[${itemCounter}][quantity]"
                   class="form-control item-quantity"
                   value="1" min="1" step="1"
                   onchange="calculateItemTotal(${itemCounter})" required>
        </td>
        <td>
            <input type="number" name="items[${itemCounter}][free_quantity]"
                   class="form-control item-free-quantity"
                   value="0" min="0" step="1"
                   onchange="calculateItemTotal(${itemCounter})"
                   placeholder="0">
        </td>
        <td>
            <input type="number" name="items[${itemCounter}][unit_price]"
                   class="form-control item-unit-price"
                   value="0" min="0" step="0.01"
                   onchange="calculateItemTotal(${itemCounter})" required>
        </td>
        <td>
            <input type="number" name="items[${itemCounter}][discount_percentage]"
                   class="form-control item-discount-percentage"
                   value="0" min="0" max="100" step="0.01"
                   onchange="calculateItemTotal(${itemCounter})"
                   placeholder="0">
        </td>
        <td>
            <input type="number" name="items[${itemCounter}][net_price]"
                   class="form-control item-net-price"
                   value="0" readonly>
        </td>
        <td>
            <input type="number" name="items[${itemCounter}][total]"
                   class="form-control item-total"
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
    noItemsAlert.style.display = 'none';
    calculateSubtotal();

    // تفعيل Select2 للصنف الجديد
    setTimeout(() => {
        if (typeof window.reinitializeSelect2 === 'function') {
            window.reinitializeSelect2();
        }
    }, 100);
}

// جميع الدوال والـ Select2 موجودة في نهاية الملف
</script>
@endpush

@push('styles')
<style>
.sticky-top {
    position: sticky;
    top: 20px;
    z-index: 1020;
}

.table th {
    background-color: #f8f9fa;
    font-weight: 600;
}

.item-total {
    background-color: #f8f9fa;
}

#totalAmountDisplay {
    font-size: 1.25rem;
    font-weight: bold;
}

#invoiceItemsTable {
    margin-bottom: 0;
}

#invoiceItemsTable td {
    vertical-align: middle;
    padding: 8px;
}

#invoiceItemsTable .form-control,
#invoiceItemsTable .form-select {
    font-size: 0.875rem;
    padding: 6px 8px;
}

#invoiceItemsTable .btn-sm {
    padding: 4px 8px;
    font-size: 0.75rem;
}

.item-total {
    background-color: #f8f9fa !important;
    font-weight: bold;
}

.item-net-price {
    background-color: #e9ecef !important;
}

/* تحسين مظهر Select2 */
.select2-container--bootstrap-5 .select2-selection--single {
    height: 38px !important;
    padding: 6px 12px !important;
    border: 1px solid #ced4da !important;
    border-radius: 0.375rem !important;
}

.select2-container--bootstrap-5 .select2-selection--single .select2-selection__rendered {
    line-height: 24px !important;
    padding-left: 0 !important;
    padding-right: 20px !important;
}

.select2-container--bootstrap-5 .select2-selection--single .select2-selection__arrow {
    height: 36px !important;
    right: 3px !important;
}

.select2-result-customer__title,
.select2-result-item__title {
    font-weight: bold;
    color: #333;
    font-size: 14px;
}

.select2-result-customer__description,
.select2-result-item__description {
    font-size: 12px;
    color: #666;
    margin-top: 2px;
}

.select2-container--bootstrap-5 .select2-dropdown {
    border: 1px solid #ced4da;
    border-radius: 0.375rem;
    box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
}

.select2-container--bootstrap-5 .select2-search--dropdown .select2-search__field {
    border: 1px solid #ced4da;
    border-radius: 0.25rem;
    padding: 6px 12px;
    font-size: 14px;
}

.select2-container--bootstrap-5 .select2-results__option {
    padding: 8px 12px;
}

.select2-container--bootstrap-5 .select2-results__option--highlighted {
    background-color: #0d6efd;
    color: white;
}

/* تحسين جدول العناصر */
#invoiceItemsTable {
    font-size: 14px;
}

#invoiceItemsTable th {
    background-color: #f8f9fa;
    font-weight: 600;
    text-align: center;
    vertical-align: middle;
    padding: 12px 8px;
    border: 1px solid #dee2e6;
}

#invoiceItemsTable td {
    vertical-align: middle;
    padding: 8px 6px;
    border: 1px solid #dee2e6;
}

#invoiceItemsTable input,
#invoiceItemsTable select {
    font-size: 13px;
    padding: 6px 8px;
    border-radius: 4px;
}

#invoiceItemsTable .btn-sm {
    padding: 4px 8px;
    font-size: 12px;
}

/* تحسين عرض الأعمدة */
#invoiceItemsTable .item-net-price,
#invoiceItemsTable .item-total {
    background-color: #e9ecef;
    font-weight: 600;
}

/* تحسين الاستجابة */
@media (max-width: 1200px) {
    #invoiceItemsTable {
        font-size: 12px;
    }

    #invoiceItemsTable th,
    #invoiceItemsTable td {
        padding: 6px 4px;
    }

    #invoiceItemsTable input,
    #invoiceItemsTable select {
        font-size: 11px;
        padding: 4px 6px;
    }
}

@media (max-width: 768px) {
    .sticky-top {
        position: relative;
        top: auto;
    }
    
    .table-responsive {
        font-size: 0.875rem;
    }
}
</style>
@endpush

@push('styles')
<!-- Select2 CSS -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" rel="stylesheet" />
@endpush

@push('scripts')

<script>
// انتظار تحميل الصفحة بالكامل
$(document).ready(function() {
    console.log('🚀 تحميل صفحة إنشاء الفاتورة...');

    // التأكد من تحميل jQuery و Select2
    if (typeof $ === 'undefined') {
        console.error('❌ jQuery غير محمل!');
        alert('خطأ: jQuery غير محمل. يرجى إعادة تحميل الصفحة.');
        return;
    }

    if (typeof $.fn.select2 === 'undefined') {
        console.error('❌ Select2 غير محمل!');
        alert('خطأ: Select2 غير محمل. يرجى إعادة تحميل الصفحة.');
        return;
    }

    console.log('✅ jQuery و Select2 محملان بنجاح');

    // تفعيل Select2 للعملاء
    function initCustomerSelect() {
        console.log('🔄 تفعيل Select2 للعملاء...');

        var $customerSelect = $('#customer_id');

        if ($customerSelect.length === 0) {
            console.error('❌ عنصر customer_id غير موجود!');
            return;
        }

        // تدمير Select2 السابق إذا كان موجوداً
        if ($customerSelect.hasClass('select2-hidden-accessible')) {
            $customerSelect.select2('destroy');
        }

        try {
            $customerSelect.select2({
            theme: 'bootstrap-5',
            width: '100%',
            placeholder: 'ابحث عن العميل...',
            allowClear: true,
            ajax: {
                url: '/api/search/customers',
                dataType: 'json',
                delay: 250,
                data: function(params) {
                    return {
                        search: params.term || '',
                        page: params.page || 1
                    };
                },
                processResults: function(data) {
                    console.log('نتائج البحث للعملاء:', data);
                    return {
                        results: data.results || []
                    };
                },
                cache: true
            },
            minimumInputLength: 0,
            templateResult: function(customer) {
                if (customer.loading) {
                    return customer.text;
                }

                if (!customer.name && !customer.text) {
                    return 'لا توجد نتائج';
                }

                var name = customer.name || customer.text;
                var code = customer.customer_code || '';
                var phone = customer.phone || '';
                var email = customer.email || '';

                var html = '<div class="select2-result-customer">';
                html += '<div class="fw-bold">' + name + '</div>';

                var details = [];
                if (code) details.push('كود: ' + code);
                if (phone) details.push('هاتف: ' + phone);
                if (email) details.push('إيميل: ' + email);

                if (details.length > 0) {
                    html += '<div class="text-muted small">' + details.join(' | ') + '</div>';
                }
                html += '</div>';

                return $(html);
            },
            templateSelection: function(customer) {
                if (customer.id === '') {
                    return customer.text || 'اختر العميل...';
                }

                var name = customer.name || customer.text;
                var code = customer.customer_code || '';

                return name + (code ? ' (' + code + ')' : '');
            }
        });

        console.log('✅ تم تفعيل Select2 للعملاء');

        } catch (error) {
            console.error('❌ خطأ في تفعيل Select2 للعملاء:', error);
        }
    }

    // تفعيل Select2 للأصناف
    function initItemSelects() {
        console.log('تفعيل Select2 للأصناف...');

        $('.item-select').each(function() {
            var $select = $(this);

            if ($select.hasClass('select2-hidden-accessible')) {
                $select.select2('destroy');
            }

            $select.select2({
                theme: 'bootstrap-5',
                width: '100%',
                placeholder: 'ابحث عن الصنف...',
                allowClear: true,
                ajax: {
                    url: '/api/search/items',
                    dataType: 'json',
                    delay: 250,
                    data: function(params) {
                        return {
                            search: params.term || '',
                            page: params.page || 1
                        };
                    },
                    processResults: function(data) {
                        console.log('نتائج البحث للأصناف:', data);
                        return {
                            results: data.results || []
                        };
                    },
                    cache: true
                },
                minimumInputLength: 0,
                templateResult: function(item) {
                    if (item.loading) {
                        return item.text;
                    }

                    if (!item.name && !item.text) {
                        return 'لا توجد نتائج';
                    }

                    var name = item.name || item.text;
                    var code = item.code || '';
                    var price = item.price || '';
                    var stock = item.stock || item.stock_quantity || '';

                    var html = '<div class="select2-result-item">';
                    html += '<div class="fw-bold">' + name + '</div>';

                    var details = [];
                    if (code) details.push('كود: ' + code);
                    if (price) details.push('سعر: ' + price + ' د.ع');
                    if (stock) details.push('مخزون: ' + stock);

                    if (details.length > 0) {
                        html += '<div class="text-muted small">' + details.join(' | ') + '</div>';
                    }
                    html += '</div>';

                    return $(html);
                },
                templateSelection: function(item) {
                    if (item.id === '') {
                        return item.text || 'اختر الصنف...';
                    }

                    var name = item.name || item.text;
                    var code = item.code || '';

                    return name + (code ? ' (' + code + ')' : '');
                }
            });

            // إضافة event listener لتحديث تفاصيل الصنف
            $select.on('select2:select', function(e) {
                var data = e.params.data;
                var rowNumber = $select.attr('class').match(/item-select-(\d+)/);
                if (rowNumber && rowNumber[1]) {
                    updateItemDetails(parseInt(rowNumber[1]));
                }
            });
        });

        console.log('تم تفعيل Select2 للأصناف');
    }

    // تفعيل Select2 عند تحميل الصفحة
    setTimeout(function() {
        initCustomerSelect();
        initItemSelects();
    }, 500);

    // تفعيل إضافي للتأكد
    setTimeout(function() {
        // تفعيل بسيط للعملاء
        if (!$('#customer_id').hasClass('select2-hidden-accessible')) {
            $('#customer_id').select2({
                theme: 'bootstrap-5',
                placeholder: 'ابحث عن العميل...',
                allowClear: true,
                ajax: {
                    url: '/api/search/customers',
                    dataType: 'json',
                    delay: 250,
                    data: function(params) {
                        return { search: params.term || '' };
                    },
                    processResults: function(data) {
                        return { results: data.results || [] };
                    }
                }
            });
        }

        // تفعيل بسيط للأصناف
        $('.item-select').each(function() {
            if (!$(this).hasClass('select2-hidden-accessible')) {
                $(this).select2({
                    theme: 'bootstrap-5',
                    placeholder: 'ابحث عن الصنف...',
                    allowClear: true,
                    ajax: {
                        url: '/api/search/items',
                        dataType: 'json',
                        delay: 250,
                        data: function(params) {
                            return { search: params.term || '' };
                        },
                        processResults: function(data) {
                            return { results: data.results || [] };
                        }
                    }
                });
            }
        });

        console.log('✅ تم تفعيل Select2 بنجاح');
    }, 1500);

    // إعادة تفعيل Select2 للصفوف الجديدة
    window.reinitializeSelect2 = function() {
        console.log('إعادة تفعيل Select2...');
        initItemSelects();
    };
});

// دالة لتحديث تفاصيل الصنف عند اختياره
function updateItemDetails(itemCounter) {
    const selectElement = document.querySelector(`select[name="items[${itemCounter}][item_id]"]`);
    if (selectElement && selectElement.value) {
        // الحصول على البيانات من Select2
        const selectedData = $(selectElement).select2('data')[0];
        if (selectedData) {
            // تحديث وصف الصنف المخفي
            const descriptionInput = document.querySelector(`.item-description-${itemCounter}`);
            if (descriptionInput) {
                descriptionInput.value = selectedData.name || selectedData.text;
            }

            // تحديث السعر
            const priceInput = document.querySelector(`input[name="items[${itemCounter}][unit_price]"]`);
            if (priceInput && selectedData.price) {
                priceInput.value = selectedData.price;
            }

            // حساب المجموع
            calculateItemTotal(itemCounter);
        }
    }
}

function removeInvoiceItem(itemId) {
    const tbody = document.getElementById('invoiceItemsBody');
    const row = document.getElementById(`item-row-${itemId}`);
    if (row) {
        // إذا كان هناك صف واحد فقط، امسح محتوياته بدلاً من حذفه
        if (tbody.children.length === 1) {
            // مسح جميع الحقول في الصف الأول
            const selects = row.querySelectorAll('select');
            const inputs = row.querySelectorAll('input');

            selects.forEach(select => {
                select.selectedIndex = 0;
                if ($(select).hasClass('select2-hidden-accessible')) {
                    $(select).val(null).trigger('change');
                }
            });

            inputs.forEach(input => {
                if (input.type === 'hidden') {
                    input.value = '';
                } else if (input.name && input.name.includes('quantity') && !input.name.includes('free')) {
                    input.value = '1';
                } else if (input.name && input.name.includes('discount_percentage')) {
                    input.value = '0';
                } else if (!input.readOnly) {
                    input.value = '';
                } else {
                    input.value = '0';
                }
            });
        } else {
            // حذف الصف إذا كان هناك أكثر من صف
            row.remove();
        }

        calculateSubtotal();

        // إظهار تنبيه "لا توجد عناصر" إذا لم تعد هناك عناصر مملوءة
        const hasItems = Array.from(tbody.querySelectorAll('select[name*="item_id"]')).some(select => select.value);
        const noItemsAlert = document.getElementById('noItemsAlert');
        if (!hasItems) {
            noItemsAlert.style.display = 'block';
        } else {
            noItemsAlert.style.display = 'none';
        }
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

    // تحديث معلومات إضافية في tooltip أو عرض
    const totalQuantity = quantity + freeQuantity;
    const freeValue = freeQuantity * unitPrice;

    // إضافة tooltip للمعلومات الإضافية
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
    calculateTotal();
}

function calculateTotal() {
    const subtotal = parseFloat(document.getElementById('subtotal').value) || 0;
    const taxAmount = parseFloat(document.getElementById('tax_amount').value) || 0;
    const discountAmount = parseFloat(document.getElementById('discount_amount').value) || 0;

    const total = subtotal + taxAmount - discountAmount;

    document.getElementById('total_amount').value = total.toFixed(2);
    document.getElementById('totalAmountDisplay').textContent = new Intl.NumberFormat('ar-IQ').format(total) + ' دينار عراقي';
}

// التحقق من صحة النموذج قبل الإرسال
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('invoiceForm');
    if (form) {
        form.addEventListener('submit', function(e) {
            const subtotal = parseFloat(document.getElementById('subtotal').value) || 0;
            if (subtotal <= 0) {
                e.preventDefault();
                alert('يجب إضافة عناصر للفاتورة قبل الحفظ');
                return false;
            }
        });
    }
});

</script>
@endpush
