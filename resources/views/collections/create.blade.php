@extends('layouts.app')

@section('title', 'إضافة تحصيل جديد - ' . __('messages.system_name'))

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">{{ __('navigation.dashboard') }}</a></li>
    <li class="breadcrumb-item"><a href="{{ route('collections.index') }}">التحصيلات</a></li>
    <li class="breadcrumb-item active">إضافة تحصيل جديد</li>
@endsection

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0">
                <i class="fas fa-plus me-2"></i>
                إضافة تحصيل جديد
            </h1>
            <p class="text-muted">تسجيل تحصيل مبلغ من العميل</p>
        </div>
        <div>
            <a href="{{ route('collections.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left me-2"></i>
                العودة للقائمة
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-money-bill-wave me-2"></i>
                        بيانات التحصيل
                    </h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('collections.store') }}" method="POST">
                        @csrf

                        <div class="row">
                            <!-- العميل -->
                            <div class="col-md-6 mb-3">
                                <label for="customer_id" class="form-label">العميل <span class="text-danger">*</span></label>
                                <select class="form-select searchable @error('customer_id') is-invalid @enderror"
                                        id="customer_id" name="customer_id"
                                        placeholder="اختر العميل"
                                        required>
                                    <option value="">اختر العميل</option>
                                    @foreach($customers as $customer)
                                        <option value="{{ $customer->id }}" 
                                                {{ old('customer_id') == $customer->id ? 'selected' : '' }}
                                                data-balance="{{ $customer->current_balance }}">
                                            {{ $customer->name }} 
                                            @if($customer->current_balance > 0)
                                                (مديون: {{ number_format($customer->current_balance, 2) }} د.ع)
                                            @endif
                                        </option>
                                    @endforeach
                                </select>
                                @error('customer_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- المبلغ -->
                            <div class="col-md-6 mb-3">
                                <label for="amount" class="form-label">المبلغ <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <input type="number" class="form-control @error('amount') is-invalid @enderror" 
                                           id="amount" name="amount" step="0.01" min="0.01" 
                                           value="{{ old('amount') }}" required>
                                    <span class="input-group-text">د.ع</span>
                                </div>
                                @error('amount')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- تاريخ التحصيل -->
                            <div class="col-md-6 mb-3">
                                <label for="collection_date" class="form-label">تاريخ التحصيل <span class="text-danger">*</span></label>
                                <input type="date" class="form-control @error('collection_date') is-invalid @enderror" 
                                       id="collection_date" name="collection_date" 
                                       value="{{ old('collection_date', date('Y-m-d')) }}" required>
                                @error('collection_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- طريقة الدفع -->
                            <div class="col-md-6 mb-3">
                                <label for="payment_method" class="form-label">طريقة الدفع <span class="text-danger">*</span></label>
                                <select class="form-select @error('payment_method') is-invalid @enderror" 
                                        id="payment_method" name="payment_method" required>
                                    <option value="">اختر طريقة الدفع</option>
                                    <option value="cash" {{ old('payment_method') == 'cash' ? 'selected' : '' }}>نقدي</option>
                                    <option value="bank_transfer" {{ old('payment_method') == 'bank_transfer' ? 'selected' : '' }}>تحويل بنكي</option>
                                    <option value="check" {{ old('payment_method') == 'check' ? 'selected' : '' }}>شيك</option>
                                    <option value="credit_card" {{ old('payment_method') == 'credit_card' ? 'selected' : '' }}>بطاقة ائتمان</option>
                                </select>
                                @error('payment_method')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- الفاتورة المرتبطة -->
                            <div class="col-md-6 mb-3">
                                <label for="invoice_id" class="form-label">الفاتورة المرتبطة (اختياري)</label>
                                <select class="form-select @error('invoice_id') is-invalid @enderror" 
                                        id="invoice_id" name="invoice_id">
                                    <option value="">بدون فاتورة محددة</option>
                                    @foreach($invoices as $invoice)
                                        <option value="{{ $invoice->id }}" 
                                                {{ old('invoice_id') == $invoice->id ? 'selected' : '' }}
                                                data-amount="{{ $invoice->total_amount - $invoice->paid_amount }}">
                                            {{ $invoice->invoice_number }} 
                                            (المتبقي: {{ number_format($invoice->total_amount - $invoice->paid_amount, 2) }} د.ع)
                                        </option>
                                    @endforeach
                                </select>
                                @error('invoice_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- رقم المرجع -->
                            <div class="col-md-6 mb-3">
                                <label for="reference_number" class="form-label">رقم المرجع</label>
                                <input type="text" class="form-control @error('reference_number') is-invalid @enderror" 
                                       id="reference_number" name="reference_number" 
                                       value="{{ old('reference_number') }}"
                                       placeholder="رقم الشيك، رقم التحويل، إلخ">
                                @error('reference_number')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- الملاحظات -->
                            <div class="col-12 mb-3">
                                <label for="notes" class="form-label">ملاحظات</label>
                                <textarea class="form-control @error('notes') is-invalid @enderror"
                                          id="notes" name="notes" rows="3"
                                          placeholder="أي ملاحظات إضافية">{{ old('notes') }}</textarea>
                                @error('notes')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- خيارات الإرسال -->
                            <div class="col-12 mb-3">
                                <div class="card border-success">
                                    <div class="card-header bg-success text-white">
                                        <h6 class="card-title mb-0">
                                            <i class="fab fa-whatsapp me-2"></i>
                                            إرسال عبر الواتساب
                                        </h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="send_whatsapp"
                                                   name="send_whatsapp" value="1" checked>
                                            <label class="form-check-label" for="send_whatsapp">
                                                <strong>إرسال مستند الاستحصال للعميل عبر الواتساب</strong>
                                            </label>
                                        </div>
                                        <div class="form-text mt-2">
                                            <i class="fas fa-info-circle me-1"></i>
                                            سيتم إرسال مستند PDF يحتوي على تفاصيل الاستحصال إلى رقم هاتف العميل تلقائياً
                                        </div>
                                        <div id="whatsapp-preview" class="mt-3" style="display: none;">
                                            <div class="alert alert-info">
                                                <strong>معاينة الرسالة:</strong>
                                                <div id="message-preview" class="mt-2"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- أزرار الإجراءات -->
                        <div class="d-flex justify-content-end gap-2">
                            <a href="{{ route('collections.index') }}" class="btn btn-secondary">
                                <i class="fas fa-times me-2"></i>
                                إلغاء
                            </a>
                            <button type="submit" class="btn btn-success">
                                <i class="fas fa-save me-2"></i>
                                حفظ التحصيل
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- الشريط الجانبي -->
        <div class="col-lg-4">
            <!-- معلومات العميل -->
            <div class="card mb-4" id="customerInfo" style="display: none;">
                <div class="card-header">
                    <h6 class="card-title mb-0">
                        <i class="fas fa-user me-2"></i>
                        معلومات العميل
                    </h6>
                </div>
                <div class="card-body">
                    <div id="customerDetails">
                        <!-- سيتم ملؤها بـ JavaScript -->
                    </div>
                </div>
            </div>

            <!-- الفواتير غير المدفوعة -->
            <div class="card mb-4" id="unpaidInvoices" style="display: none;">
                <div class="card-header">
                    <h6 class="card-title mb-0">
                        <i class="fas fa-file-invoice me-2"></i>
                        الفواتير غير المدفوعة
                    </h6>
                </div>
                <div class="card-body">
                    <div id="invoicesList">
                        <!-- سيتم ملؤها بـ JavaScript -->
                    </div>
                </div>
            </div>

            <!-- نصائح -->
            <div class="card">
                <div class="card-header">
                    <h6 class="card-title mb-0">
                        <i class="fas fa-lightbulb me-2"></i>
                        نصائح
                    </h6>
                </div>
                <div class="card-body">
                    <div class="alert alert-info alert-sm">
                        <i class="fas fa-info-circle me-2"></i>
                        يمكنك ربط التحصيل بفاتورة محددة أو تركه عام
                    </div>
                    <div class="alert alert-warning alert-sm">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        تأكد من صحة المبلغ وطريقة الدفع
                    </div>
                    <div class="alert alert-success alert-sm">
                        <i class="fas fa-check-circle me-2"></i>
                        سيتم تحديث رصيد العميل تلقائياً
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const customerSelect = document.getElementById('customer_id');
    const invoiceSelect = document.getElementById('invoice_id');
    const amountInput = document.getElementById('amount');
    const customerInfo = document.getElementById('customerInfo');
    const unpaidInvoices = document.getElementById('unpaidInvoices');
    const sendWhatsappCheckbox = document.getElementById('send_whatsapp');
    const whatsappPreview = document.getElementById('whatsapp-preview');
    const messagePreview = document.getElementById('message-preview');

    // عند تغيير العميل
    customerSelect.addEventListener('change', function() {
        const customerId = this.value;
        
        if (customerId) {
            // إظهار معلومات العميل
            showCustomerInfo(this.options[this.selectedIndex]);
            
            // جلب فواتير العميل
            fetchCustomerInvoices(customerId);
        } else {
            customerInfo.style.display = 'none';
            unpaidInvoices.style.display = 'none';
            invoiceSelect.innerHTML = '<option value="">بدون فاتورة محددة</option>';
        }
    });

    // عند تغيير الفاتورة
    invoiceSelect.addEventListener('change', function() {
        if (this.value) {
            const selectedOption = this.options[this.selectedIndex];
            const remainingAmount = selectedOption.getAttribute('data-amount');
            
            if (remainingAmount) {
                amountInput.value = remainingAmount;
            }
        }
    });

    // إظهار معلومات العميل
    function showCustomerInfo(option) {
        const balance = option.getAttribute('data-balance');
        const customerName = option.text.split(' (')[0];
        
        const html = `
            <div class="mb-2">
                <strong>الاسم:</strong> ${customerName}
            </div>
            <div class="mb-2">
                <strong>الرصيد الحالي:</strong> 
                <span class="badge bg-${balance > 0 ? 'danger' : 'success'}">
                    ${parseFloat(balance).toLocaleString()} د.ع
                </span>
            </div>
        `;
        
        document.getElementById('customerDetails').innerHTML = html;
        customerInfo.style.display = 'block';
    }

    // جلب فواتير العميل
    function fetchCustomerInvoices(customerId) {
        fetch(`/collections/customer-invoices?customer_id=${customerId}`)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    updateInvoicesList(data.invoices);
                    updateInvoicesSelect(data.invoices);
                }
            })
            .catch(error => {
                console.error('Error fetching invoices:', error);
            });
    }

    // تحديث قائمة الفواتير في الشريط الجانبي
    function updateInvoicesList(invoices) {
        if (invoices.length === 0) {
            unpaidInvoices.style.display = 'none';
            return;
        }

        let html = '';
        invoices.forEach(invoice => {
            const remaining = invoice.total_amount - invoice.paid_amount;
            html += `
                <div class="d-flex justify-content-between align-items-center mb-2 pb-2 border-bottom">
                    <div>
                        <strong>${invoice.invoice_number}</strong>
                        <br><small class="text-muted">${invoice.invoice_date}</small>
                    </div>
                    <div class="text-end">
                        <div class="fw-bold">${remaining.toLocaleString()} د.ع</div>
                    </div>
                </div>
            `;
        });

        document.getElementById('invoicesList').innerHTML = html;
        unpaidInvoices.style.display = 'block';
    }

    // تحديث قائمة الفواتير في الـ select
    function updateInvoicesSelect(invoices) {
        let html = '<option value="">بدون فاتورة محددة</option>';
        
        invoices.forEach(invoice => {
            const remaining = invoice.total_amount - invoice.paid_amount;
            html += `
                <option value="${invoice.id}" data-amount="${remaining}">
                    ${invoice.invoice_number} (المتبقي: ${remaining.toLocaleString()} د.ع)
                </option>
            `;
        });

        invoiceSelect.innerHTML = html;
    }

    // معاينة رسالة الواتساب
    function updateWhatsAppPreview() {
        if (!sendWhatsappCheckbox.checked) {
            whatsappPreview.style.display = 'none';
            return;
        }

        const customerName = customerSelect.options[customerSelect.selectedIndex]?.text.split(' (')[0] || 'العميل';
        const amount = amountInput.value || '0';
        const date = document.getElementById('collection_date').value || new Date().toISOString().split('T')[0];

        const message = `عزيزي العميل، تم إنشاء سند استحصال جديد بمبلغ ${parseFloat(amount).toLocaleString()} دينار عراقي بتاريخ ${date}. يرجى مراجعة المستند المرفق للتفاصيل.`;

        messagePreview.textContent = message;
        whatsappPreview.style.display = 'block';
    }

    // تحديث معاينة الواتساب عند تغيير البيانات
    sendWhatsappCheckbox.addEventListener('change', updateWhatsAppPreview);
    customerSelect.addEventListener('change', updateWhatsAppPreview);
    amountInput.addEventListener('input', updateWhatsAppPreview);
    document.getElementById('collection_date').addEventListener('change', updateWhatsAppPreview);

    // تحديث المعاينة عند تحميل الصفحة
    updateWhatsAppPreview();
});
</script>
@endpush

@push('styles')
<style>
.alert-sm {
    padding: 0.5rem;
    margin-bottom: 0.5rem;
    font-size: 0.875rem;
}

.card {
    box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
}

.border-bottom {
    border-bottom: 1px solid #dee2e6 !important;
}
</style>
@endpush
