@extends('layouts.app')

@section('title', 'إجازات الفحص - ' . __('messages.system_name'))

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">{{ __('navigation.dashboard') }}</a></li>
    <li class="breadcrumb-item"><a href="{{ route('regulatory-affairs.dashboard') }}">الشؤون التنظيمية</a></li>
    <li class="breadcrumb-item active">إجازات الفحص</li>
@endsection

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0">
                <i class="fas fa-search me-2"></i>
                إجازات الفحص
            </h1>
            <p class="text-muted">إدارة إجازات فحص المرافق والمنتجات والتفتيش</p>
        </div>
        <div>
            <button class="btn btn-primary">
                <i class="fas fa-plus me-2"></i>
                طلب إجازة فحص جديدة
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
            <div class="card bg-info text-white">
                <div class="card-body text-center">
                    <h4 class="mb-1">{{ number_format($permitStats['facility_inspections']) }}</h4>
                    <small>فحص مرافق</small>
                </div>
            </div>
        </div>
        <div class="col-lg-2 col-md-4 col-sm-6 mb-3">
            <div class="card bg-secondary text-white">
                <div class="card-body text-center">
                    <h4 class="mb-1">{{ number_format($permitStats['gmp_inspections']) }}</h4>
                    <small>فحص GMP</small>
                </div>
            </div>
        </div>
        <div class="col-lg-2 col-md-4 col-sm-6 mb-3">
            <div class="card bg-danger text-white">
                <div class="card-body text-center">
                    <h4 class="mb-1">{{ number_format($permitStats['overdue_payments']) }}</h4>
                    <small>متأخرة الدفع</small>
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
            <form method="GET" action="{{ route('regulatory-affairs.inspection-permits') }}">
                <div class="row">
                    <div class="col-md-3 mb-3">
                        <label for="search" class="form-label">البحث</label>
                        <input type="text" class="form-control" id="search" name="search" 
                               value="{{ request('search') }}" 
                               placeholder="رقم الإجازة أو اسم المفتش">
                    </div>
                    <div class="col-md-2 mb-3">
                        <label for="permit_type" class="form-label">نوع الإجازة</label>
                        <select class="form-select" id="permit_type" name="permit_type">
                            <option value="">جميع الأنواع</option>
                            <option value="facility_inspection" {{ request('permit_type') == 'facility_inspection' ? 'selected' : '' }}>فحص مرافق</option>
                            <option value="product_inspection" {{ request('permit_type') == 'product_inspection' ? 'selected' : '' }}>فحص منتج</option>
                            <option value="gmp_inspection" {{ request('permit_type') == 'gmp_inspection' ? 'selected' : '' }}>فحص GMP</option>
                            <option value="import_inspection" {{ request('permit_type') == 'import_inspection' ? 'selected' : '' }}>فحص استيراد</option>
                            <option value="export_inspection" {{ request('permit_type') == 'export_inspection' ? 'selected' : '' }}>فحص تصدير</option>
                        </select>
                    </div>
                    <div class="col-md-2 mb-3">
                        <label for="status" class="form-label">الحالة</label>
                        <select class="form-select" id="status" name="status">
                            <option value="">جميع الحالات</option>
                            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>معلقة</option>
                            <option value="scheduled" {{ request('status') == 'scheduled' ? 'selected' : '' }}>مجدولة</option>
                            <option value="in_progress" {{ request('status') == 'in_progress' ? 'selected' : '' }}>قيد التنفيذ</option>
                            <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>مكتملة</option>
                            <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>معتمدة</option>
                            <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>مرفوضة</option>
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
                    <div class="col-md-3 mb-3 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary me-2">
                            <i class="fas fa-search me-2"></i>
                            بحث
                        </button>
                        <a href="{{ route('regulatory-affairs.inspection-permits') }}" class="btn btn-secondary">
                            <i class="fas fa-times me-2"></i>
                            إعادة تعيين
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- جدول إجازات الفحص -->
    <div class="card">
        <div class="card-header">
            <h5 class="card-title mb-0">
                <i class="fas fa-list me-2"></i>
                قائمة إجازات الفحص
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
                            <th>الشركة</th>
                            <th>نوع الفحص</th>
                            <th>تاريخ التقديم</th>
                            <th>تاريخ الفحص</th>
                            <th>المفتش</th>
                            <th>النتيجة</th>
                            <th>الرسوم</th>
                            <th>الحالة</th>
                            <th>الإجراءات</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($permits as $permit)
                        <tr>
                            <td>
                                <code>{{ $permit->permit_number }}</code>
                            </td>
                            <td>
                                <div>
                                    <strong>{{ $permit->company->company_name }}</strong>
                                    <br><small class="text-muted">{{ $permit->company->country }}</small>
                                </div>
                            </td>
                            <td>
                                <span class="badge bg-info">
                                    @switch($permit->permit_type)
                                        @case('facility_inspection') فحص مرافق @break
                                        @case('product_inspection') فحص منتج @break
                                        @case('gmp_inspection') فحص GMP @break
                                        @case('import_inspection') فحص استيراد @break
                                        @case('export_inspection') فحص تصدير @break
                                        @default {{ $permit->permit_type }}
                                    @endswitch
                                </span>
                                @if($permit->product)
                                    <br><small class="text-muted">{{ $permit->product->product_name }}</small>
                                @endif
                            </td>
                            <td>{{ $permit->application_date->format('d/m/Y') }}</td>
                            <td>
                                @if($permit->inspection_date)
                                    {{ $permit->inspection_date->format('d/m/Y') }}
                                @else
                                    <span class="text-muted">غير محدد</span>
                                @endif
                            </td>
                            <td>
                                @if($permit->inspector_name)
                                    {{ $permit->inspector_name }}
                                @else
                                    <span class="text-muted">غير محدد</span>
                                @endif
                            </td>
                            <td>
                                @if($permit->result)
                                    @switch($permit->result)
                                        @case('passed')
                                            <span class="badge bg-success">نجح</span>
                                            @break
                                        @case('failed')
                                            <span class="badge bg-danger">فشل</span>
                                            @break
                                        @case('conditional')
                                            <span class="badge bg-warning">مشروط</span>
                                            @break
                                        @case('pending')
                                            <span class="badge bg-secondary">معلق</span>
                                            @break
                                        @default
                                            <span class="badge bg-secondary">{{ $permit->result }}</span>
                                    @endswitch
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td>
                                @if($permit->fees)
                                    <strong>{{ number_format($permit->fees, 0) }} د.ع</strong>
                                    <br>
                                    <small class="badge bg-{{ $permit->payment_status === 'paid' ? 'success' : ($permit->payment_status === 'overdue' ? 'danger' : 'warning') }}">
                                        @switch($permit->payment_status)
                                            @case('paid') مدفوع @break
                                            @case('pending') معلق @break
                                            @case('overdue') متأخر @break
                                            @default {{ $permit->payment_status }}
                                        @endswitch
                                    </small>
                                @else
                                    <span class="text-muted">غير محدد</span>
                                @endif
                            </td>
                            <td>
                                @switch($permit->status)
                                    @case('pending')
                                        <span class="badge bg-warning">معلقة</span>
                                        @break
                                    @case('scheduled')
                                        <span class="badge bg-info">مجدولة</span>
                                        @break
                                    @case('in_progress')
                                        <span class="badge bg-primary">قيد التنفيذ</span>
                                        @break
                                    @case('completed')
                                        <span class="badge bg-secondary">مكتملة</span>
                                        @break
                                    @case('approved')
                                        <span class="badge bg-success">معتمدة</span>
                                        @break
                                    @case('rejected')
                                        <span class="badge bg-danger">مرفوضة</span>
                                        @break
                                    @case('expired')
                                        <span class="badge bg-dark">منتهية</span>
                                        @break
                                    @default
                                        <span class="badge bg-secondary">{{ $permit->status }}</span>
                                @endswitch
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
                <i class="fas fa-search fa-3x text-muted mb-3"></i>
                <h5 class="text-muted">لا توجد إجازات فحص</h5>
                <p class="text-muted">لم يتم العثور على أي إجازات فحص تطابق معايير البحث</p>
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
