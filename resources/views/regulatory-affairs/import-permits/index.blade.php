@extends('layouts.app')

@section('title', 'إجازات الاستيراد - ' . __('messages.system_name'))

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">{{ __('navigation.dashboard') }}</a></li>
    <li class="breadcrumb-item"><a href="{{ route('regulatory-affairs.dashboard') }}">الشؤون التنظيمية</a></li>
    <li class="breadcrumb-item active">إجازات الاستيراد</li>
@endsection

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0">
                <i class="fas fa-shipping-fast me-2"></i>
                إجازات الاستيراد
            </h1>
            <p class="text-muted">إدارة إجازات استيراد المنتجات الدوائية والأجهزة الطبية</p>
        </div>
        <div>
            <button class="btn btn-primary">
                <i class="fas fa-plus me-2"></i>
                طلب إجازة استيراد جديدة
            </button>
        </div>
    </div>

    <!-- إحصائيات سريعة -->
    <div class="row mb-4">
        <div class="col-lg-2 col-md-4 col-sm-6 mb-3">
            <div class="card bg-primary text-white">
                <div class="card-body text-center">
                    <h4 class="mb-1">{{ number_format($permitStats['total_permits']) }}</h4>
                    <small>إجمالي الإجازات</small>
                </div>
            </div>
        </div>
        <div class="col-lg-2 col-md-4 col-sm-6 mb-3">
            <div class="card bg-warning text-white">
                <div class="card-body text-center">
                    <h4 class="mb-1">{{ number_format($permitStats['pending_permits']) }}</h4>
                    <small>إجازات معلقة</small>
                </div>
            </div>
        </div>
        <div class="col-lg-2 col-md-4 col-sm-6 mb-3">
            <div class="card bg-success text-white">
                <div class="card-body text-center">
                    <h4 class="mb-1">{{ number_format($permitStats['approved_permits']) }}</h4>
                    <small>إجازات معتمدة</small>
                </div>
            </div>
        </div>
        <div class="col-lg-2 col-md-4 col-sm-6 mb-3">
            <div class="card bg-danger text-white">
                <div class="card-body text-center">
                    <h4 class="mb-1">{{ number_format($permitStats['delayed_arrivals']) }}</h4>
                    <small>شحنات متأخرة</small>
                </div>
            </div>
        </div>
        <div class="col-lg-2 col-md-4 col-sm-6 mb-3">
            <div class="card bg-secondary text-white">
                <div class="card-body text-center">
                    <h4 class="mb-1">{{ number_format($permitStats['payment_pending']) }}</h4>
                    <small>معلقة الدفع</small>
                </div>
            </div>
        </div>
        <div class="col-lg-2 col-md-4 col-sm-6 mb-3">
            <div class="card bg-info text-white">
                <div class="card-body text-center">
                    <h4 class="mb-1">${{ number_format($permitStats['total_value'], 0) }}</h4>
                    <small>إجمالي القيمة</small>
                </div>
            </div>
        </div>
    </div>

    <!-- فلاتر البحث -->
    <div class="card mb-4">
        <div class="card-header">
            <h5 class="card-title mb-0">
                <i class="fas fa-filter me-2"></i>
                فلاتر البحث
            </h5>
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('regulatory-affairs.import-permits') }}">
                <div class="row">
                    <div class="col-md-3 mb-3">
                        <label for="search" class="form-label">البحث</label>
                        <input type="text" class="form-control" id="search" name="search" 
                               value="{{ request('search') }}" 
                               placeholder="رقم الإجازة، الشركة المورّدة، أو رقم البيان">
                    </div>
                    <div class="col-md-2 mb-3">
                        <label for="status" class="form-label">الحالة</label>
                        <select class="form-select" id="status" name="status">
                            <option value="">جميع الحالات</option>
                            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>معلقة</option>
                            <option value="under_review" {{ request('status') == 'under_review' ? 'selected' : '' }}>قيد المراجعة</option>
                            <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>معتمدة</option>
                            <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>مرفوضة</option>
                            <option value="expired" {{ request('status') == 'expired' ? 'selected' : '' }}>منتهية</option>
                            <option value="used" {{ request('status') == 'used' ? 'selected' : '' }}>مستخدمة</option>
                            <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>ملغية</option>
                        </select>
                    </div>
                    <div class="col-md-2 mb-3">
                        <label for="company_id" class="form-label">الشركة</label>
                        <select class="form-select" id="company_id" name="company_id">
                            <option value="">جميع الشركات</option>
                            @foreach($companies as $company)
                                <option value="{{ $company->id }}" {{ request('company_id') == $company->id ? 'selected' : '' }}>
                                    {{ $company->company_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2 mb-3">
                        <label for="supplier_country" class="form-label">بلد المورّد</label>
                        <select class="form-select" id="supplier_country" name="supplier_country">
                            <option value="">جميع البلدان</option>
                            @foreach($supplierCountries as $country)
                                <option value="{{ $country }}" {{ request('supplier_country') == $country ? 'selected' : '' }}>
                                    {{ $country }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3 mb-3 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary me-2">
                            <i class="fas fa-search me-2"></i>
                            بحث
                        </button>
                        <a href="{{ route('regulatory-affairs.import-permits') }}" class="btn btn-secondary">
                            <i class="fas fa-times me-2"></i>
                            إعادة تعيين
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- جدول إجازات الاستيراد -->
    <div class="card">
        <div class="card-header">
            <h5 class="card-title mb-0">
                <i class="fas fa-list me-2"></i>
                قائمة إجازات الاستيراد
                <span class="badge bg-primary ms-2">{{ $permits->total() }}</span>
            </h5>
        </div>
        <div class="card-body">
            @if($permits->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>رقم الإجازة</th>
                            <th>الشركة المستوردة</th>
                            <th>المنتج</th>
                            <th>الشركة المورّدة</th>
                            <th>الكمية</th>
                            <th>القيمة الإجمالية</th>
                            <th>تاريخ الوصول المتوقع</th>
                            <th>حالة الجمارك</th>
                            <th>الحالة</th>
                            <th>الإجراءات</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($permits as $permit)
                        <tr>
                            <td>
                                <code>{{ $permit->permit_number }}</code>
                                <br><small class="text-muted">{{ $permit->application_date->format('d/m/Y') }}</small>
                            </td>
                            <td>
                                <div>
                                    <strong>{{ $permit->company->company_name }}</strong>
                                    <br><small class="text-muted">{{ $permit->company->country }}</small>
                                </div>
                            </td>
                            <td>
                                <div>
                                    <strong>{{ $permit->product->product_name }}</strong>
                                    <br><small class="text-muted">{{ $permit->product->generic_name }}</small>
                                    @if($permit->batch_number)
                                        <br><small class="badge bg-secondary">{{ $permit->batch_number }}</small>
                                    @endif
                                </div>
                            </td>
                            <td>
                                <div>
                                    <strong>{{ $permit->supplier_company }}</strong>
                                    <br><small class="text-muted">{{ $permit->supplier_country }}</small>
                                </div>
                            </td>
                            <td>
                                <strong>{{ number_format($permit->quantity) }}</strong>
                                <br><small class="text-muted">{{ $permit->unit }}</small>
                            </td>
                            <td>
                                <strong>${{ number_format($permit->total_value, 2) }}</strong>
                                <br><small class="text-muted">{{ $permit->currency }}</small>
                                @if($permit->unit_price)
                                    <br><small class="text-muted">${{ number_format($permit->unit_price, 2) }}/{{ $permit->unit }}</small>
                                @endif
                            </td>
                            <td>
                                @if($permit->expected_arrival_date)
                                    {{ $permit->expected_arrival_date->format('d/m/Y') }}
                                    @if($permit->isArrivalDelayed())
                                        <br><span class="badge bg-danger">متأخرة</span>
                                    @endif
                                    @if($permit->actual_arrival_date)
                                        <br><small class="text-success">وصلت: {{ $permit->actual_arrival_date->format('d/m/Y') }}</small>
                                    @endif
                                @else
                                    <span class="text-muted">غير محدد</span>
                                @endif
                            </td>
                            <td>
                                @if($permit->customs_status)
                                    @switch($permit->customs_status)
                                        @case('pending')
                                            <span class="badge bg-warning">معلقة</span>
                                            @break
                                        @case('cleared')
                                            <span class="badge bg-success">مخلصة</span>
                                            @break
                                        @case('held')
                                            <span class="badge bg-danger">محتجزة</span>
                                            @break
                                        @case('rejected')
                                            <span class="badge bg-dark">مرفوضة</span>
                                            @break
                                        @default
                                            <span class="badge bg-secondary">{{ $permit->customs_status }}</span>
                                    @endswitch
                                    @if($permit->customs_declaration_number)
                                        <br><small class="text-muted">{{ $permit->customs_declaration_number }}</small>
                                    @endif
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td>
                                @switch($permit->status)
                                    @case('pending')
                                        <span class="badge bg-warning">معلقة</span>
                                        @break
                                    @case('under_review')
                                        <span class="badge bg-info">قيد المراجعة</span>
                                        @break
                                    @case('approved')
                                        <span class="badge bg-success">معتمدة</span>
                                        @break
                                    @case('rejected')
                                        <span class="badge bg-danger">مرفوضة</span>
                                        @break
                                    @case('expired')
                                        <span class="badge bg-secondary">منتهية</span>
                                        @break
                                    @case('used')
                                        <span class="badge bg-primary">مستخدمة</span>
                                        @break
                                    @case('cancelled')
                                        <span class="badge bg-dark">ملغية</span>
                                        @break
                                    @default
                                        <span class="badge bg-secondary">{{ $permit->status }}</span>
                                @endswitch
                                <br>
                                <small class="badge bg-{{ $permit->payment_status === 'paid' ? 'success' : ($permit->payment_status === 'overdue' ? 'danger' : 'warning') }}">
                                    @switch($permit->payment_status)
                                        @case('paid') مدفوع @break
                                        @case('pending') معلق الدفع @break
                                        @case('overdue') متأخر @break
                                        @default {{ $permit->payment_status }}
                                    @endswitch
                                </small>
                            </td>
                            <td>
                                <div class="btn-group" role="group">
                                    <button type="button" class="btn btn-sm btn-outline-primary" title="عرض">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    <button type="button" class="btn btn-sm btn-outline-secondary" title="تعديل">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button type="button" class="btn btn-sm btn-outline-info" title="طباعة">
                                        <i class="fas fa-print"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="d-flex justify-content-center mt-4">
                {{ $permits->appends(request()->query())->links() }}
            </div>
            @else
            <div class="text-center py-5">
                <i class="fas fa-shipping-fast fa-3x text-muted mb-3"></i>
                <h5 class="text-muted">لا توجد إجازات استيراد</h5>
                <p class="text-muted">لم يتم العثور على أي إجازات استيراد تطابق معايير البحث</p>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
.table th {
    background-color: #f8f9fa;
    border-top: none;
    font-weight: 600;
}

.badge {
    font-size: 0.75em;
}

.btn-group .btn {
    border-radius: 0.25rem;
    margin-right: 2px;
}

.card {
    box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
}

.table-responsive {
    border-radius: 0.375rem;
}

code {
    background-color: #f8f9fa;
    padding: 2px 4px;
    border-radius: 3px;
    font-size: 0.875em;
}
</style>
@endpush
