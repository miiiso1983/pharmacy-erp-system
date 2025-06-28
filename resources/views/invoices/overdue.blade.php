@extends('layouts.app')

@section('title', __('invoices.overdue_invoices') . ' - ' . __('app.name'))

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">{{ __('app.home') }}</a></li>
    <li class="breadcrumb-item"><a href="{{ route('invoices.index') }}">{{ __('invoices.title') }}</a></li>
    <li class="breadcrumb-item active">{{ __('invoices.overdue_invoices') }}</li>
@endsection

@section('content')
<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>
                <i class="fas fa-exclamation-triangle me-2 text-danger"></i>
                {{ __('invoices.overdue_invoices') }}
            </h2>
            <div>
                <a href="{{ route('invoices.create') }}" class="btn btn-primary me-2">
                    <i class="fas fa-plus me-2"></i>
                    {{ __('invoices.new_invoice') }}
                </a>
                <a href="{{ route('invoices.index') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-list me-2"></i>
                    {{ __('invoices.all_invoices') }}
                </a>
            </div>
        </div>
    </div>
</div>

<!-- تنبيه مهم -->
<div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
    <i class="fas fa-exclamation-triangle me-2"></i>
    <strong>تنبيه مهم:</strong> هذه الفواتير متأخرة الدفع ويجب متابعتها فوراً مع العملاء.
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>

<!-- إحصائيات سريعة -->
<div class="row mb-4">
    <div class="col-lg-3 col-md-6 mb-3">
        <div class="card bg-danger text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h4 class="mb-0">{{ $overdueCount ?? 0 }}</h4>
                        <p class="mb-0">{{ __('invoices.overdue_invoices') }}</p>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-exclamation-triangle fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6 mb-3">
        <div class="card bg-warning text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h4 class="mb-0">{{ number_format($overdueAmount ?? 0, 0) }}</h4>
                        <p class="mb-0">{{ __('app.iqd') }}</p>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-money-bill-wave fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6 mb-3">
        <div class="card bg-dark text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h4 class="mb-0">{{ $criticalCount ?? 0 }}</h4>
                        <p class="mb-0">أكثر من 30 يوم</p>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-calendar-times fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6 mb-3">
        <div class="card bg-secondary text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h4 class="mb-0">{{ $averageDays ?? 0 }}</h4>
                        <p class="mb-0">متوسط أيام التأخير</p>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-clock fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- فلاتر البحث -->
<div class="card mb-4">
    <div class="card-header">
        <h5 class="mb-0">
            <i class="fas fa-filter me-2"></i>
            {{ __('app.filter') }}
        </h5>
    </div>
    <div class="card-body">
        <form method="GET" action="{{ route('invoices.overdue') }}">
            <div class="row">
                <div class="col-md-3 mb-3">
                    <label for="customer_id" class="form-label">{{ __('app.customer') }}</label>
                    <select name="customer_id" id="customer_id"
                            class="form-select searchable"
                            placeholder="{{ __('customers.all_customers') }}">
                        <option value="">{{ __('customers.all_customers') }}</option>
                        @foreach($customers ?? [] as $customer)
                            <option value="{{ $customer->id }}" {{ request('customer_id') == $customer->id ? 'selected' : '' }}
                                    data-customer-code="{{ $customer->customer_code }}"
                                    data-customer-phone="{{ $customer->phone }}">
                                {{ $customer->name }}
                                @if($customer->customer_code)
                                    ({{ $customer->customer_code }})
                                @endif
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3 mb-3">
                    <label for="overdue_period" class="form-label">فترة التأخير</label>
                    <select name="overdue_period" id="overdue_period" class="form-select">
                        <option value="">جميع الفترات</option>
                        <option value="1-7" {{ request('overdue_period') == '1-7' ? 'selected' : '' }}>1-7 أيام</option>
                        <option value="8-15" {{ request('overdue_period') == '8-15' ? 'selected' : '' }}>8-15 يوم</option>
                        <option value="16-30" {{ request('overdue_period') == '16-30' ? 'selected' : '' }}>16-30 يوم</option>
                        <option value="31+" {{ request('overdue_period') == '31+' ? 'selected' : '' }}>أكثر من 30 يوم</option>
                    </select>
                </div>
                <div class="col-md-3 mb-3">
                    <label for="amount_range" class="form-label">{{ __('app.amount') }}</label>
                    <select name="amount_range" id="amount_range" class="form-select">
                        <option value="">{{ __('app.all') }}</option>
                        <option value="0-100000" {{ request('amount_range') == '0-100000' ? 'selected' : '' }}>أقل من 100,000</option>
                        <option value="100000-500000" {{ request('amount_range') == '100000-500000' ? 'selected' : '' }}>100,000 - 500,000</option>
                        <option value="500000-1000000" {{ request('amount_range') == '500000-1000000' ? 'selected' : '' }}>500,000 - 1,000,000</option>
                        <option value="1000000+" {{ request('amount_range') == '1000000+' ? 'selected' : '' }}>أكثر من 1,000,000</option>
                    </select>
                </div>
                <div class="col-md-3 mb-3">
                    <label for="sort_by" class="form-label">ترتيب حسب</label>
                    <select name="sort_by" id="sort_by" class="form-select">
                        <option value="due_date" {{ request('sort_by') == 'due_date' ? 'selected' : '' }}>تاريخ الاستحقاق</option>
                        <option value="amount" {{ request('sort_by') == 'amount' ? 'selected' : '' }}>المبلغ</option>
                        <option value="customer" {{ request('sort_by') == 'customer' ? 'selected' : '' }}>العميل</option>
                        <option value="overdue_days" {{ request('sort_by') == 'overdue_days' ? 'selected' : '' }}>أيام التأخير</option>
                    </select>
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <button type="submit" class="btn btn-primary me-2">
                        <i class="fas fa-search me-2"></i>
                        {{ __('app.search') }}
                    </button>
                    <a href="{{ route('invoices.overdue') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-times me-2"></i>
                        {{ __('app.clear') }}
                    </a>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- جدول الفواتير المتأخرة -->
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">
            <i class="fas fa-list me-2"></i>
            {{ __('invoices.overdue_invoices') }}
        </h5>
        <div>
            <button class="btn btn-outline-warning btn-sm me-2" onclick="sendReminders()">
                <i class="fas fa-bell me-2"></i>
                إرسال تذكيرات
            </button>
            <button class="btn btn-outline-success btn-sm me-2" onclick="exportToExcel()">
                <i class="fas fa-file-excel me-2"></i>
                {{ __('app.export') }} Excel
            </button>
            <button class="btn btn-outline-danger btn-sm" onclick="exportToPDF()">
                <i class="fas fa-file-pdf me-2"></i>
                {{ __('app.export') }} PDF
            </button>
        </div>
    </div>
    <div class="card-body">
        @if(isset($invoices) && $invoices->count() > 0)
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead class="table-dark">
                        <tr>
                            <th>{{ __('invoices.invoice_number') }}</th>
                            <th>{{ __('app.customer') }}</th>
                            <th>{{ __('invoices.due_date') }}</th>
                            <th>أيام التأخير</th>
                            <th>{{ __('app.total') }}</th>
                            <th>{{ __('invoices.remaining_amount') }}</th>
                            <th>مستوى الخطر</th>
                            <th>{{ __('app.actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($invoices as $invoice)
                            @php
                                $overdueDays = $invoice->due_date ? $invoice->due_date->diffInDays(now()) : 0;
                                $riskLevel = $overdueDays <= 7 ? 'low' : ($overdueDays <= 30 ? 'medium' : 'high');
                            @endphp
                            <tr class="table-{{ $riskLevel == 'high' ? 'danger' : ($riskLevel == 'medium' ? 'warning' : 'light') }}">
                                <td>
                                    <a href="{{ route('invoices.show', $invoice->id) }}" class="text-decoration-none fw-bold">
                                        {{ $invoice->invoice_number }}
                                    </a>
                                </td>
                                <td>
                                    @if($invoice->customer)
                                        <div>
                                            <strong>{{ $invoice->customer->name }}</strong>
                                            @if($invoice->customer->customer_code)
                                                <br><small class="text-muted">{{ $invoice->customer->customer_code }}</small>
                                            @endif
                                            @if($invoice->customer->phone)
                                                <br><small class="text-info">{{ $invoice->customer->phone }}</small>
                                            @endif
                                        </div>
                                    @else
                                        <span class="text-muted">{{ __('app.not_specified') }}</span>
                                    @endif
                                </td>
                                <td>
                                    <span class="text-danger fw-bold">
                                        {{ $invoice->due_date ? $invoice->due_date->format('Y/m/d') : '-' }}
                                    </span>
                                </td>
                                <td>
                                    <span class="badge bg-{{ $riskLevel == 'high' ? 'danger' : ($riskLevel == 'medium' ? 'warning' : 'info') }}">
                                        {{ $overdueDays }} {{ $overdueDays == 1 ? 'يوم' : 'أيام' }}
                                    </span>
                                </td>
                                <td>
                                    <strong>{{ number_format($invoice->total_amount, 0) }}</strong>
                                    <small class="text-muted">{{ __('app.iqd') }}</small>
                                </td>
                                <td>
                                    <span class="text-danger fw-bold">{{ number_format($invoice->remaining_amount, 0) }}</span>
                                    <small class="text-muted">{{ __('app.iqd') }}</small>
                                </td>
                                <td>
                                    @if($riskLevel == 'high')
                                        <span class="badge bg-danger">خطر عالي</span>
                                    @elseif($riskLevel == 'medium')
                                        <span class="badge bg-warning">خطر متوسط</span>
                                    @else
                                        <span class="badge bg-info">خطر منخفض</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="btn-group btn-group-sm">
                                        <a href="{{ route('invoices.show', $invoice->id) }}" 
                                           class="btn btn-outline-primary" title="{{ __('app.view') }}">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('collections.create', ['invoice_id' => $invoice->id]) }}" 
                                           class="btn btn-outline-success" title="{{ __('collections.new_collection') }}">
                                            <i class="fas fa-money-bill-wave"></i>
                                        </a>
                                        <button class="btn btn-outline-warning" onclick="sendReminder({{ $invoice->id }})" title="إرسال تذكير">
                                            <i class="fas fa-bell"></i>
                                        </button>
                                        <a href="{{ route('invoices.print', $invoice->id) }}" 
                                           class="btn btn-outline-info" title="{{ __('app.print') }}" target="_blank">
                                            <i class="fas fa-print"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if(method_exists($invoices, 'links'))
                <div class="d-flex justify-content-center mt-4">
                    {{ $invoices->appends(request()->query())->links() }}
                </div>
            @endif
        @else
            <div class="text-center py-5">
                <i class="fas fa-check-circle fa-3x text-success mb-3"></i>
                <h5 class="text-success">ممتاز! لا توجد فواتير متأخرة</h5>
                <p class="text-muted">جميع الفواتير مدفوعة في الوقت المحدد</p>
                <a href="{{ route('invoices.pending') }}" class="btn btn-warning">
                    <i class="fas fa-clock me-2"></i>
                    {{ __('invoices.view_pending') }}
                </a>
            </div>
        @endif
    </div>
</div>
@endsection

@push('scripts')
<script>
function exportToExcel() {
    window.location.href = '{{ route("invoices.export") }}?status=overdue&' + new URLSearchParams(window.location.search);
}

function exportToPDF() {
    window.open('{{ route("invoices.export") }}?format=pdf&status=overdue&' + new URLSearchParams(window.location.search), '_blank');
}

function sendReminder(invoiceId) {
    if (confirm('هل تريد إرسال تذكير للعميل بخصوص هذه الفاتورة؟')) {
        fetch(`/invoices/${invoiceId}/send-reminder`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Content-Type': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('تم إرسال التذكير بنجاح');
            } else {
                alert('حدث خطأ في إرسال التذكير');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('حدث خطأ في إرسال التذكير');
        });
    }
}

function sendReminders() {
    if (confirm('هل تريد إرسال تذكيرات لجميع العملاء الذين لديهم فواتير متأخرة؟')) {
        fetch('/invoices/send-bulk-reminders', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                filters: Object.fromEntries(new URLSearchParams(window.location.search))
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert(`تم إرسال ${data.count} تذكير بنجاح`);
            } else {
                alert('حدث خطأ في إرسال التذكيرات');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('حدث خطأ في إرسال التذكيرات');
        });
    }
}
</script>
@endpush

@push('styles')
<style>
.table-danger {
    background-color: rgba(220, 53, 69, 0.1);
}

.table-warning {
    background-color: rgba(255, 193, 7, 0.1);
}

.card {
    box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
    border: 1px solid rgba(0, 0, 0, 0.125);
}

.btn-group-sm .btn {
    padding: 0.25rem 0.5rem;
    font-size: 0.875rem;
}

.badge {
    font-size: 0.75rem;
}

@media (max-width: 768px) {
    .table-responsive {
        font-size: 0.875rem;
    }
    
    .btn-group-sm .btn {
        padding: 0.125rem 0.25rem;
        font-size: 0.75rem;
    }
}
</style>
@endpush
