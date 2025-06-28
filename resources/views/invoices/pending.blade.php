@extends('layouts.app')

@section('title', __('invoices.pending_invoices') . ' - ' . __('app.name'))

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">{{ __('app.home') }}</a></li>
    <li class="breadcrumb-item"><a href="{{ route('invoices.index') }}">{{ __('invoices.title') }}</a></li>
    <li class="breadcrumb-item active">{{ __('invoices.pending_invoices') }}</li>
@endsection

@section('content')
<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>
                <i class="fas fa-clock me-2 text-warning"></i>
                {{ __('invoices.pending_invoices') }}
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

<!-- إحصائيات سريعة -->
<div class="row mb-4">
    <div class="col-lg-3 col-md-6 mb-3">
        <div class="card bg-warning text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h4 class="mb-0">{{ $pendingCount ?? 0 }}</h4>
                        <p class="mb-0">{{ __('invoices.pending_invoices') }}</p>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-clock fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6 mb-3">
        <div class="card bg-info text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h4 class="mb-0">{{ number_format($pendingAmount ?? 0, 0) }}</h4>
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
        <div class="card bg-secondary text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h4 class="mb-0">{{ number_format($overdueAmount ?? 0, 0) }}</h4>
                        <p class="mb-0">{{ __('app.iqd') }} {{ __('invoices.overdue') }}</p>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-calendar-times fa-2x"></i>
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
        <form method="GET" action="{{ route('invoices.pending') }}">
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
                    <label for="from_date" class="form-label">{{ __('app.from_date') }}</label>
                    <input type="date" name="from_date" id="from_date" class="form-control" value="{{ request('from_date') }}">
                </div>
                <div class="col-md-3 mb-3">
                    <label for="to_date" class="form-label">{{ __('app.to_date') }}</label>
                    <input type="date" name="to_date" id="to_date" class="form-control" value="{{ request('to_date') }}">
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
            </div>
            <div class="row">
                <div class="col-12">
                    <button type="submit" class="btn btn-primary me-2">
                        <i class="fas fa-search me-2"></i>
                        {{ __('app.search') }}
                    </button>
                    <a href="{{ route('invoices.pending') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-times me-2"></i>
                        {{ __('app.clear') }}
                    </a>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- جدول الفواتير المعلقة -->
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">
            <i class="fas fa-list me-2"></i>
            {{ __('invoices.pending_invoices') }}
        </h5>
        <div>
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
                            <th>{{ __('invoices.invoice_date') }}</th>
                            <th>{{ __('invoices.due_date') }}</th>
                            <th>{{ __('app.total') }}</th>
                            <th>{{ __('invoices.paid_amount') }}</th>
                            <th>{{ __('invoices.remaining_amount') }}</th>
                            <th>{{ __('app.status') }}</th>
                            <th>{{ __('app.actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($invoices as $invoice)
                            <tr class="{{ $invoice->due_date < now() ? 'table-danger' : '' }}">
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
                                        </div>
                                    @else
                                        <span class="text-muted">{{ __('app.not_specified') }}</span>
                                    @endif
                                </td>
                                <td>{{ $invoice->invoice_date ? $invoice->invoice_date->format('Y/m/d') : '-' }}</td>
                                <td>
                                    @if($invoice->due_date)
                                        <span class="{{ $invoice->due_date < now() ? 'text-danger fw-bold' : '' }}">
                                            {{ $invoice->due_date->format('Y/m/d') }}
                                        </span>
                                        @if($invoice->due_date < now())
                                            <br><small class="text-danger">متأخر {{ $invoice->due_date->diffForHumans() }}</small>
                                        @endif
                                    @else
                                        -
                                    @endif
                                </td>
                                <td>
                                    <strong>{{ number_format($invoice->total_amount, 0) }}</strong>
                                    <small class="text-muted">{{ __('app.iqd') }}</small>
                                </td>
                                <td>
                                    <span class="text-success">{{ number_format($invoice->paid_amount, 0) }}</span>
                                    <small class="text-muted">{{ __('app.iqd') }}</small>
                                </td>
                                <td>
                                    <span class="text-warning fw-bold">{{ number_format($invoice->remaining_amount, 0) }}</span>
                                    <small class="text-muted">{{ __('app.iqd') }}</small>
                                </td>
                                <td>
                                    @switch($invoice->status)
                                        @case('pending')
                                            <span class="badge bg-warning">{{ __('app.pending') }}</span>
                                            @break
                                        @case('partially_paid')
                                            <span class="badge bg-info">{{ __('invoices.partially_paid') }}</span>
                                            @break
                                        @case('overdue')
                                            <span class="badge bg-danger">{{ __('invoices.overdue') }}</span>
                                            @break
                                        @default
                                            <span class="badge bg-secondary">{{ $invoice->status }}</span>
                                    @endswitch
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
                                        @can('edit_invoices')
                                            <a href="{{ route('invoices.edit', $invoice->id) }}" 
                                               class="btn btn-outline-warning" title="{{ __('app.edit') }}">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                        @endcan
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
                <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                <h5 class="text-muted">{{ __('invoices.no_pending_invoices') }}</h5>
                <p class="text-muted">لا توجد فواتير معلقة حالياً</p>
                <a href="{{ route('invoices.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus me-2"></i>
                    {{ __('invoices.create_invoice') }}
                </a>
            </div>
        @endif
    </div>
</div>
@endsection

@push('scripts')
<script>
function exportToExcel() {
    window.location.href = '{{ route("invoices.export") }}?status=pending&' + new URLSearchParams(window.location.search);
}

function exportToPDF() {
    window.open('{{ route("invoices.export") }}?format=pdf&status=pending&' + new URLSearchParams(window.location.search), '_blank');
}

// تحديث تلقائي للصفحة كل 5 دقائق
setInterval(function() {
    if (document.visibilityState === 'visible') {
        location.reload();
    }
}, 300000);
</script>
@endpush

@push('styles')
<style>
.table-danger {
    background-color: rgba(220, 53, 69, 0.1);
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
