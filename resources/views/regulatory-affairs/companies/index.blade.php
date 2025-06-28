@extends('layouts.app')

@section('title', 'إدارة الشركات - ' . __('messages.system_name'))

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">{{ __('navigation.dashboard') }}</a></li>
    <li class="breadcrumb-item"><a href="{{ route('regulatory-affairs.dashboard') }}">الشؤون التنظيمية</a></li>
    <li class="breadcrumb-item active">إدارة الشركات</li>
@endsection

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0">
                <i class="fas fa-building me-2"></i>
                إدارة الشركات
            </h1>
            <p class="text-muted">تسجيل وإدارة الشركات الدوائية والموردين</p>
        </div>
        <div>
            <a href="{{ route('regulatory-affairs.companies.create') }}" class="btn btn-primary">
                <i class="fas fa-plus me-2"></i>
                إضافة شركة جديدة
            </a>
        </div>
    </div>

    <!-- إحصائيات سريعة -->
    <div class="row mb-4">
        <div class="col-lg-2 col-md-4 col-sm-6 mb-3">
            <div class="card bg-primary text-white">
                <div class="card-body text-center">
                    <h4 class="mb-1">{{ number_format($companyStats['total_companies']) }}</h4>
                    <small>إجمالي الشركات</small>
                </div>
            </div>
        </div>
        <div class="col-lg-2 col-md-4 col-sm-6 mb-3">
            <div class="card bg-success text-white">
                <div class="card-body text-center">
                    <h4 class="mb-1">{{ number_format($companyStats['active_companies']) }}</h4>
                    <small>شركات نشطة</small>
                </div>
            </div>
        </div>
        <div class="col-lg-2 col-md-4 col-sm-6 mb-3">
            <div class="card bg-info text-white">
                <div class="card-body text-center">
                    <h4 class="mb-1">{{ number_format($companyStats['manufacturers']) }}</h4>
                    <small>شركات تصنيع</small>
                </div>
            </div>
        </div>
        <div class="col-lg-2 col-md-4 col-sm-6 mb-3">
            <div class="card bg-warning text-white">
                <div class="card-body text-center">
                    <h4 class="mb-1">{{ number_format($companyStats['distributors']) }}</h4>
                    <small>شركات توزيع</small>
                </div>
            </div>
        </div>
        <div class="col-lg-2 col-md-4 col-sm-6 mb-3">
            <div class="card bg-secondary text-white">
                <div class="card-body text-center">
                    <h4 class="mb-1">{{ number_format($companyStats['importers']) }}</h4>
                    <small>شركات استيراد</small>
                </div>
            </div>
        </div>
        <div class="col-lg-2 col-md-4 col-sm-6 mb-3">
            <div class="card bg-danger text-white">
                <div class="card-body text-center">
                    <h4 class="mb-1">{{ number_format($companyStats['expired_companies']) }}</h4>
                    <small>منتهية الصلاحية</small>
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
            <form method="GET" action="{{ route('regulatory-affairs.companies') }}">
                <div class="row">
                    <div class="col-md-3 mb-3">
                        <label for="search" class="form-label">البحث</label>
                        <input type="text" class="form-control" id="search" name="search" 
                               value="{{ request('search') }}" 
                               placeholder="اسم الشركة، الرمز، أو رقم التسجيل">
                    </div>
                    <div class="col-md-2 mb-3">
                        <label for="company_type" class="form-label">نوع الشركة</label>
                        <select class="form-select" id="company_type" name="company_type">
                            <option value="">جميع الأنواع</option>
                            <option value="manufacturer" {{ request('company_type') == 'manufacturer' ? 'selected' : '' }}>شركة تصنيع</option>
                            <option value="distributor" {{ request('company_type') == 'distributor' ? 'selected' : '' }}>شركة توزيع</option>
                            <option value="importer" {{ request('company_type') == 'importer' ? 'selected' : '' }}>شركة استيراد</option>
                            <option value="exporter" {{ request('company_type') == 'exporter' ? 'selected' : '' }}>شركة تصدير</option>
                            <option value="wholesaler" {{ request('company_type') == 'wholesaler' ? 'selected' : '' }}>تاجر جملة</option>
                            <option value="retailer" {{ request('company_type') == 'retailer' ? 'selected' : '' }}>تاجر تجزئة</option>
                        </select>
                    </div>
                    <div class="col-md-2 mb-3">
                        <label for="status" class="form-label">الحالة</label>
                        <select class="form-select" id="status" name="status">
                            <option value="">جميع الحالات</option>
                            <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>نشطة</option>
                            <option value="suspended" {{ request('status') == 'suspended' ? 'selected' : '' }}>معلقة</option>
                            <option value="expired" {{ request('status') == 'expired' ? 'selected' : '' }}>منتهية الصلاحية</option>
                            <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>ملغية</option>
                        </select>
                    </div>
                    <div class="col-md-2 mb-3">
                        <label for="country" class="form-label">البلد</label>
                        <input type="text" class="form-control" id="country" name="country" 
                               value="{{ request('country') }}" placeholder="البلد">
                    </div>
                    <div class="col-md-3 mb-3 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary me-2">
                            <i class="fas fa-search me-2"></i>
                            بحث
                        </button>
                        <a href="{{ route('regulatory-affairs.companies') }}" class="btn btn-secondary">
                            <i class="fas fa-times me-2"></i>
                            إعادة تعيين
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- جدول الشركات -->
    <div class="card">
        <div class="card-header">
            <h5 class="card-title mb-0">
                <i class="fas fa-list me-2"></i>
                قائمة الشركات
                <span class="badge bg-primary ms-2">{{ $companies->total() }}</span>
            </h5>
        </div>
        <div class="card-body">
            @if($companies->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>رمز الشركة</th>
                            <th>اسم الشركة</th>
                            <th>نوع الشركة</th>
                            <th>البلد</th>
                            <th>تاريخ التسجيل</th>
                            <th>تاريخ الانتهاء</th>
                            <th>الحالة</th>
                            <th>الإجراءات</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($companies as $company)
                        <tr>
                            <td>
                                <code>{{ $company->company_code }}</code>
                            </td>
                            <td>
                                <div>
                                    <strong>{{ $company->company_name }}</strong>
                                    @if($company->company_name_en)
                                        <br><small class="text-muted">{{ $company->company_name_en }}</small>
                                    @endif
                                </div>
                            </td>
                            <td>
                                <span class="badge bg-info">
                                    @switch($company->company_type)
                                        @case('manufacturer') شركة تصنيع @break
                                        @case('distributor') شركة توزيع @break
                                        @case('importer') شركة استيراد @break
                                        @case('exporter') شركة تصدير @break
                                        @case('wholesaler') تاجر جملة @break
                                        @case('retailer') تاجر تجزئة @break
                                        @default {{ $company->company_type }}
                                    @endswitch
                                </span>
                            </td>
                            <td>{{ $company->country }}</td>
                            <td>{{ $company->registration_date->format('d/m/Y') }}</td>
                            <td>
                                @if($company->expiry_date)
                                    <span class="badge bg-{{ $company->getAlertStatus() === 'expired' ? 'danger' : ($company->getAlertStatus() === 'critical' ? 'warning' : 'success') }}">
                                        {{ $company->expiry_date->format('d/m/Y') }}
                                    </span>
                                    @if($company->getDaysUntilExpiry() !== null)
                                        <br><small class="text-muted">
                                            @if($company->getDaysUntilExpiry() < 0)
                                                منتهية منذ {{ abs($company->getDaysUntilExpiry()) }} يوم
                                            @else
                                                باقي {{ $company->getDaysUntilExpiry() }} يوم
                                            @endif
                                        </small>
                                    @endif
                                @else
                                    <span class="text-muted">غير محدد</span>
                                @endif
                            </td>
                            <td>
                                @switch($company->status)
                                    @case('active')
                                        <span class="badge bg-success">نشطة</span>
                                        @break
                                    @case('suspended')
                                        <span class="badge bg-warning">معلقة</span>
                                        @break
                                    @case('expired')
                                        <span class="badge bg-danger">منتهية الصلاحية</span>
                                        @break
                                    @case('cancelled')
                                        <span class="badge bg-secondary">ملغية</span>
                                        @break
                                    @default
                                        <span class="badge bg-secondary">{{ $company->status }}</span>
                                @endswitch
                            </td>
                            <td>
                                <div class="btn-group" role="group">
                                    <a href="{{ route('regulatory-affairs.companies.show', $company->id) }}" class="btn btn-sm btn-outline-primary" title="عرض">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <button type="button" class="btn btn-sm btn-outline-secondary" title="تعديل">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button type="button" class="btn btn-sm btn-outline-danger" title="حذف">
                                        <i class="fas fa-trash"></i>
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
                {{ $companies->appends(request()->query())->links() }}
            </div>
            @else
            <div class="text-center py-5">
                <i class="fas fa-building fa-3x text-muted mb-3"></i>
                <h5 class="text-muted">لا توجد شركات</h5>
                <p class="text-muted">لم يتم العثور على أي شركات تطابق معايير البحث</p>
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
