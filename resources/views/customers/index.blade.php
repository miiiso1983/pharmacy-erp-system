@extends('layouts.app')

@section('title', 'إدارة الزبائن - ' . __('messages.system_name'))

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">{{ __('navigation.dashboard') }}</a></li>
    <li class="breadcrumb-item active">الزبائن</li>
@endsection

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0">
                <i class="fas fa-users me-2"></i>
                إدارة الزبائن
            </h1>
            <p class="text-muted">إدارة شاملة للزبائن وحركاتهم المالية</p>
        </div>
        <div>
            <div class="btn-group" role="group">
                <a href="{{ route('customers.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus me-2"></i>
                    إضافة زبون جديد
                </a>
                <button type="button" class="btn btn-success dropdown-toggle dropdown-toggle-split"
                        data-bs-toggle="dropdown" aria-expanded="false">
                    <span class="visually-hidden">Toggle Dropdown</span>
                </button>
                <ul class="dropdown-menu">
                    <li>
                        <a class="dropdown-item" href="{{ route('customers.import.form') }}">
                            <i class="fas fa-file-upload me-2"></i>
                            استيراد من Excel
                        </a>
                    </li>
                    <li>
                        <a class="dropdown-item" href="{{ route('customers.template') }}">
                            <i class="fas fa-file-csv me-2"></i>
                            تحميل نموذج CSV
                        </a>
                    </li>
                    <li><hr class="dropdown-divider"></li>
                    <li>
                        <a class="dropdown-item" href="{{ route('customers.export') }}{{ request()->getQueryString() ? '?' . request()->getQueryString() : '' }}">
                            <i class="fas fa-file-excel me-2"></i>
                            تصدير إلى Excel
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </div>

    <!-- إحصائيات سريعة -->
    <div class="row mb-4">
        <div class="col-xl-2 col-md-4 mb-3">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                إجمالي الزبائن
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $stats['total_customers'] ?? 0 }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-users fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-2 col-md-4 mb-3">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                زبائن نشطون
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $stats['active_customers'] ?? 0 }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-user-check fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-2 col-md-4 mb-3">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                تجاوز سقف الدين
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $stats['over_credit_limit'] ?? 0 }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-exclamation-triangle fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-2 col-md-4 mb-3">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                إجمالي المديونية
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ number_format($stats['total_outstanding'] ?? 0) }} د.ع
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-dollar-sign fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-2 col-md-4 mb-3">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                مبيعات الشهر
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ number_format($stats['total_sales_this_month'] ?? 0) }} د.ع
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-chart-line fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-2 col-md-4 mb-3">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                تحصيلات الشهر
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ number_format($stats['total_collections_this_month'] ?? 0) }} د.ع
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-hand-holding-usd fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- فلاتر البحث -->
    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('customers.index') }}">
                <div class="row">
                    <div class="col-md-3">
                        <label for="search" class="form-label">البحث</label>
                        <input type="text" class="form-control" id="search" name="search" 
                               value="{{ request('search') }}" placeholder="اسم الزبون أو الكود...">
                    </div>
                    <div class="col-md-2">
                        <label for="customer_type" class="form-label">نوع الزبون</label>
                        <select class="form-select searchable" id="customer_type" name="customer_type"
                                placeholder="جميع الأنواع">
                            <option value="">جميع الأنواع</option>
                            <option value="retail" {{ request('customer_type') == 'retail' ? 'selected' : '' }}>تجزئة</option>
                            <option value="wholesale" {{ request('customer_type') == 'wholesale' ? 'selected' : '' }}>جملة</option>
                            <option value="pharmacy" {{ request('customer_type') == 'pharmacy' ? 'selected' : '' }}>صيدلية</option>
                            <option value="hospital" {{ request('customer_type') == 'hospital' ? 'selected' : '' }}>مستشفى</option>
                            <option value="clinic" {{ request('customer_type') == 'clinic' ? 'selected' : '' }}>عيادة</option>
                            <option value="distributor" {{ request('customer_type') == 'distributor' ? 'selected' : '' }}>موزع</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label for="status" class="form-label">الحالة</label>
                        <select class="form-select" id="status" name="status">
                            <option value="">جميع الحالات</option>
                            <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>نشط</option>
                            <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>غير نشط</option>
                            <option value="blocked" {{ request('status') == 'blocked' ? 'selected' : '' }}>محظور</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">فلاتر خاصة</label>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="over_credit_limit" 
                                   name="over_credit_limit" value="1" {{ request('over_credit_limit') ? 'checked' : '' }}>
                            <label class="form-check-label" for="over_credit_limit">
                                تجاوز سقف الدين
                            </label>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">&nbsp;</label>
                        <div class="d-grid gap-2 d-md-flex">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-search me-1"></i>
                                بحث
                            </button>
                            <a href="{{ route('customers.index') }}" class="btn btn-outline-secondary">
                                <i class="fas fa-times me-1"></i>
                                إلغاء
                            </a>
                            <div class="btn-group">
                                <button type="button" class="btn btn-success" onclick="exportData()">
                                    <i class="fas fa-download me-1"></i>
                                    تصدير
                                </button>
                                <button type="button" class="btn btn-success dropdown-toggle dropdown-toggle-split"
                                        data-bs-toggle="dropdown" aria-expanded="false">
                                    <span class="visually-hidden">Toggle Dropdown</span>
                                </button>
                                <ul class="dropdown-menu">
                                    <li>
                                        <a class="dropdown-item" href="{{ route('customers.export') }}{{ request()->getQueryString() ? '?' . request()->getQueryString() : '' }}">
                                            <i class="fas fa-file-excel me-2"></i>
                                            تصدير النتائج الحالية
                                        </a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item" href="{{ route('customers.export') }}">
                                            <i class="fas fa-file-csv me-2"></i>
                                            تصدير جميع الزبائن
                                        </a>
                                    </li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li>
                                        <a class="dropdown-item" href="{{ route('customers.import.form') }}">
                                            <i class="fas fa-file-upload me-2"></i>
                                            استيراد زبائن جدد
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- جدول الزبائن -->
    <div class="card">
        <div class="card-header">
            <h5 class="card-title mb-0">
                <i class="fas fa-list me-2"></i>
                قائمة الزبائن
            </h5>
        </div>
        <div class="card-body">
            @if($customers->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>الزبون</th>
                                <th>النوع</th>
                                <th>الرصيد الحالي</th>
                                <th>سقف الدين</th>
                                <th>نسبة الاستخدام</th>
                                <th>معدل المشتريات</th>
                                <th>معدل التحصيلات</th>
                                <th>الحالة</th>
                                <th>الإجراءات</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($customers as $customer)
                            <tr class="{{ $customer->isOverCreditLimit() ? 'table-warning' : '' }}">
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="avatar me-3">
                                            <div class="avatar-initial bg-primary rounded-circle">
                                                {{ substr($customer->name, 0, 2) }}
                                            </div>
                                        </div>
                                        <div>
                                            <h6 class="mb-0">{{ $customer->name }}</h6>
                                            <small class="text-muted">{{ $customer->customer_code }}</small>
                                            @if($customer->business_name)
                                                <br><small class="text-info">{{ $customer->business_name }}</small>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    @switch($customer->customer_type)
                                        @case('retail')
                                            <span class="badge bg-info">تجزئة</span>
                                            @break
                                        @case('wholesale')
                                            <span class="badge bg-success">جملة</span>
                                            @break
                                        @case('pharmacy')
                                            <span class="badge bg-primary">صيدلية</span>
                                            @break
                                        @default
                                            <span class="badge bg-secondary">غير محدد</span>
                                    @endswitch
                                </td>
                                <td>
                                    <span class="fw-bold {{ $customer->current_balance > 0 ? 'text-danger' : 'text-success' }}">
                                        {{ number_format($customer->current_balance) }} د.ع
                                    </span>
                                </td>
                                <td>{{ number_format($customer->credit_limit) }} د.ع</td>
                                <td>
                                    <div class="progress" style="height: 20px;">
                                        <div class="progress-bar {{ $customer->credit_utilization > 100 ? 'bg-danger' : ($customer->credit_utilization > 80 ? 'bg-warning' : 'bg-success') }}" 
                                             role="progressbar" 
                                             style="width: {{ min($customer->credit_utilization, 100) }}%">
                                            {{ number_format($customer->credit_utilization, 1) }}%
                                        </div>
                                    </div>
                                </td>
                                <td>{{ number_format($customer->monthly_purchase_avg) }} د.ع</td>
                                <td>{{ number_format($customer->monthly_collection_avg) }} د.ع</td>
                                <td>
                                    @switch($customer->status)
                                        @case('active')
                                            <span class="badge bg-success">نشط</span>
                                            @break
                                        @case('inactive')
                                            <span class="badge bg-warning">غير نشط</span>
                                            @break
                                        @case('blocked')
                                            <span class="badge bg-danger">محظور</span>
                                            @break
                                        @default
                                            <span class="badge bg-secondary">غير محدد</span>
                                    @endswitch
                                </td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('customers.show', $customer->id) }}" 
                                           class="btn btn-sm btn-outline-primary" title="عرض التفاصيل">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('customers.edit', $customer->id) }}" 
                                           class="btn btn-sm btn-outline-success" title="تعديل">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <button type="button" class="btn btn-sm btn-outline-info" 
                                                onclick="showTransactions({{ $customer->id }})" title="الحركات">
                                            <i class="fas fa-list"></i>
                                        </button>
                                        <button type="button" class="btn btn-sm btn-outline-warning" 
                                                onclick="addPayment({{ $customer->id }})" title="إضافة دفعة">
                                            <i class="fas fa-plus"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="d-flex justify-content-center">
                    {{ $customers->links() }}
                </div>
            @else
                <div class="text-center py-4">
                    <i class="fas fa-users fa-3x text-muted mb-3"></i>
                    <h5 class="text-muted">لا توجد زبائن</h5>
                    <p class="text-muted">لم يتم العثور على أي زبائن بالمعايير المحددة</p>
                    <a href="{{ route('customers.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus me-2"></i>
                        إضافة زبون جديد
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
.border-left-primary {
    border-left: 0.25rem solid #4e73df !important;
}

.border-left-success {
    border-left: 0.25rem solid #1cc88a !important;
}

.border-left-info {
    border-left: 0.25rem solid #36b9cc !important;
}

.border-left-warning {
    border-left: 0.25rem solid #f6c23e !important;
}

.avatar {
    width: 40px;
    height: 40px;
}

.avatar-initial {
    width: 100%;
    height: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 600;
    color: white;
}

.table th {
    background-color: #f8f9fa;
    border-top: none;
}

.table-warning {
    background-color: rgba(255, 193, 7, 0.1);
}
</style>
@endpush

@push('scripts')
<script>
function showTransactions(customerId) {
    // عرض حركات الزبون
    window.location.href = `/customers/${customerId}#transactions`;
}

function addPayment(customerId) {
    // إضافة دفعة جديدة
    window.location.href = `/customers/${customerId}/payments/create`;
}

function exportData() {
    // تصدير البيانات
    const params = new URLSearchParams(window.location.search);
    params.set('export', 'excel');
    window.open(`{{ route('customers.index') }}?${params.toString()}`, '_blank');
}
</script>
@endpush
