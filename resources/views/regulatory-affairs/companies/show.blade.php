@extends('layouts.app')

@section('title', 'عرض الشركة - ' . $company->company_name . ' - ' . __('messages.system_name'))

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">{{ __('navigation.dashboard') }}</a></li>
    <li class="breadcrumb-item"><a href="{{ route('regulatory-affairs.dashboard') }}">الشؤون التنظيمية</a></li>
    <li class="breadcrumb-item"><a href="{{ route('regulatory-affairs.companies') }}">إدارة الشركات</a></li>
    <li class="breadcrumb-item active">{{ $company->company_name }}</li>
@endsection

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0">
                <i class="fas fa-building me-2"></i>
                {{ $company->company_name }}
            </h1>
            <p class="text-muted">{{ $company->company_name_en }}</p>
        </div>
        <div>
            <button class="btn btn-outline-primary me-2" onclick="window.print()">
                <i class="fas fa-print me-2"></i>
                طباعة
            </button>
            <button class="btn btn-primary me-2">
                <i class="fas fa-edit me-2"></i>
                تعديل
            </button>
            <a href="{{ route('regulatory-affairs.companies') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left me-2"></i>
                العودة للقائمة
            </a>
        </div>
    </div>

    <div class="row">
        <!-- معلومات الشركة الأساسية -->
        <div class="col-lg-8 mb-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-info-circle me-2"></i>
                        معلومات الشركة
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">رمز الشركة:</label>
                            <p class="mb-0"><code>{{ $company->company_code }}</code></p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">رقم التسجيل:</label>
                            <p class="mb-0"><code>{{ $company->registration_number }}</code></p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">اسم الشركة:</label>
                            <p class="mb-0">{{ $company->company_name }}</p>
                            @if($company->company_name_en)
                                <small class="text-muted">{{ $company->company_name_en }}</small>
                            @endif
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">نوع الشركة:</label>
                            <p class="mb-0">
                                <span class="badge bg-info fs-6">
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
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- معلومات الموقع والاتصال -->
            <div class="card mt-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-map-marker-alt me-2"></i>
                        معلومات الموقع والاتصال
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">البلد:</label>
                            <p class="mb-0">{{ $company->country }}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">المدينة:</label>
                            <p class="mb-0">{{ $company->city }}</p>
                        </div>
                        <div class="col-12 mb-3">
                            <label class="form-label fw-bold">العنوان:</label>
                            <p class="mb-0">{{ $company->address }}</p>
                        </div>
                        @if($company->phone)
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">الهاتف:</label>
                            <p class="mb-0">
                                <a href="tel:{{ $company->phone }}" class="text-decoration-none">
                                    <i class="fas fa-phone me-1"></i>{{ $company->phone }}
                                </a>
                            </p>
                        </div>
                        @endif
                        @if($company->email)
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">البريد الإلكتروني:</label>
                            <p class="mb-0">
                                <a href="mailto:{{ $company->email }}" class="text-decoration-none">
                                    <i class="fas fa-envelope me-1"></i>{{ $company->email }}
                                </a>
                            </p>
                        </div>
                        @endif
                        @if($company->website)
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">الموقع الإلكتروني:</label>
                            <p class="mb-0">
                                <a href="{{ $company->website }}" target="_blank" class="text-decoration-none">
                                    <i class="fas fa-globe me-1"></i>{{ $company->website }}
                                </a>
                            </p>
                        </div>
                        @endif
                        @if($company->contact_person)
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">الشخص المسؤول:</label>
                            <p class="mb-0">{{ $company->contact_person }}</p>
                            @if($company->contact_phone)
                                <br><small class="text-muted">
                                    <i class="fas fa-phone me-1"></i>{{ $company->contact_phone }}
                                </small>
                            @endif
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- معلومات الترخيص -->
            @if($company->license_number || $company->gmp_status !== 'not_certified')
            <div class="card mt-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-certificate me-2"></i>
                        معلومات الترخيص والشهادات
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        @if($company->license_number)
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">رقم الترخيص:</label>
                            <p class="mb-0"><code>{{ $company->license_number }}</code></p>
                        </div>
                        @endif
                        @if($company->license_issue_date)
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">تاريخ إصدار الترخيص:</label>
                            <p class="mb-0">{{ $company->license_issue_date->format('d/m/Y') }}</p>
                        </div>
                        @endif
                        @if($company->license_expiry_date)
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">تاريخ انتهاء الترخيص:</label>
                            <p class="mb-0">
                                <span class="badge bg-{{ $company->isLicenseExpired() ? 'danger' : 'success' }} fs-6">
                                    {{ $company->license_expiry_date->format('d/m/Y') }}
                                </span>
                            </p>
                        </div>
                        @endif
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">حالة GMP:</label>
                            <p class="mb-0">
                                <span class="badge bg-{{ $company->gmp_status === 'certified' ? 'success' : ($company->gmp_status === 'expired' ? 'danger' : 'warning') }} fs-6">
                                    @switch($company->gmp_status)
                                        @case('certified') معتمد @break
                                        @case('not_certified') غير معتمد @break
                                        @case('pending') معلق @break
                                        @case('expired') منتهي @break
                                        @default {{ $company->gmp_status }}
                                    @endswitch
                                </span>
                            </p>
                        </div>
                        @if($company->gmp_expiry_date)
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">تاريخ انتهاء GMP:</label>
                            <p class="mb-0">
                                <span class="badge bg-{{ $company->isGmpExpired() ? 'danger' : 'success' }} fs-6">
                                    {{ $company->gmp_expiry_date->format('d/m/Y') }}
                                </span>
                            </p>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
            @endif
        </div>

        <!-- الشريط الجانبي -->
        <div class="col-lg-4">
            <!-- حالة التسجيل -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-clipboard-check me-2"></i>
                        حالة التسجيل
                    </h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label fw-bold">الحالة الحالية:</label>
                        <p class="mb-0">
                            @switch($company->status)
                                @case('active')
                                    <span class="badge bg-success fs-6">نشطة</span>
                                    @break
                                @case('suspended')
                                    <span class="badge bg-warning fs-6">معلقة</span>
                                    @break
                                @case('expired')
                                    <span class="badge bg-danger fs-6">منتهية الصلاحية</span>
                                    @break
                                @case('cancelled')
                                    <span class="badge bg-secondary fs-6">ملغية</span>
                                    @break
                                @default
                                    <span class="badge bg-secondary fs-6">{{ $company->status }}</span>
                            @endswitch
                        </p>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">تاريخ التسجيل:</label>
                        <p class="mb-0">{{ $company->registration_date->format('d/m/Y') }}</p>
                    </div>
                    @if($company->expiry_date)
                    <div class="mb-3">
                        <label class="form-label fw-bold">تاريخ الانتهاء:</label>
                        <p class="mb-0">
                            <span class="badge bg-{{ $company->getAlertStatus() === 'expired' ? 'danger' : ($company->getAlertStatus() === 'critical' ? 'warning' : 'success') }} fs-6">
                                {{ $company->expiry_date->format('d/m/Y') }}
                            </span>
                        </p>
                        @if($company->getDaysUntilExpiry() !== null)
                            <small class="text-muted">
                                @if($company->getDaysUntilExpiry() < 0)
                                    منتهية منذ {{ abs($company->getDaysUntilExpiry()) }} يوم
                                @else
                                    باقي {{ $company->getDaysUntilExpiry() }} يوم
                                @endif
                            </small>
                        @endif
                    </div>
                    @endif
                </div>
            </div>

            <!-- إحصائيات سريعة -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-chart-bar me-2"></i>
                        إحصائيات سريعة
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-6 mb-3">
                            <div class="border rounded p-2">
                                <h4 class="text-primary mb-1">{{ $company->pharmaceuticalProducts->count() }}</h4>
                                <small class="text-muted">منتجات مسجلة</small>
                            </div>
                        </div>
                        <div class="col-6 mb-3">
                            <div class="border rounded p-2">
                                <h4 class="text-success mb-1">{{ $company->inspectionPermits->count() }}</h4>
                                <small class="text-muted">إجازات فحص</small>
                            </div>
                        </div>
                        <div class="col-6 mb-3">
                            <div class="border rounded p-2">
                                <h4 class="text-warning mb-1">{{ $company->importPermits->count() }}</h4>
                                <small class="text-muted">إجازات استيراد</small>
                            </div>
                        </div>
                        <div class="col-6 mb-3">
                            <div class="border rounded p-2">
                                <h4 class="text-info mb-1">{{ $company->pharmaceuticalProducts->where('status', 'registered')->count() }}</h4>
                                <small class="text-muted">منتجات نشطة</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- معلومات إضافية -->
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-clock me-2"></i>
                        معلومات إضافية
                    </h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label fw-bold">تاريخ الإنشاء:</label>
                        <p class="mb-0">{{ $company->created_at->format('d/m/Y H:i') }}</p>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">آخر تحديث:</label>
                        <p class="mb-0">{{ $company->updated_at->format('d/m/Y H:i') }}</p>
                    </div>
                    @if($company->notes)
                    <div class="mb-3">
                        <label class="form-label fw-bold">ملاحظات:</label>
                        <p class="mb-0">{{ $company->notes }}</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- المنتجات وإجازات الفحص والاستيراد -->
    <div class="row mt-4">
        @if($company->pharmaceuticalProducts->count() > 0)
        <div class="col-lg-4 mb-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-pills me-2"></i>
                        المنتجات المسجلة
                        <span class="badge bg-primary ms-2">{{ $company->pharmaceuticalProducts->count() }}</span>
                    </h5>
                </div>
                <div class="card-body">
                    @foreach($company->pharmaceuticalProducts->take(5) as $product)
                    <div class="d-flex justify-content-between align-items-center mb-2 pb-2 border-bottom">
                        <div>
                            <a href="{{ route('regulatory-affairs.products.show', $product->id) }}" class="text-decoration-none">
                                <strong>{{ $product->product_name }}</strong>
                            </a>
                            <br><small class="text-muted">{{ $product->generic_name }}</small>
                        </div>
                        <span class="badge bg-{{ $product->status === 'registered' ? 'success' : ($product->status === 'pending' ? 'warning' : 'secondary') }}">
                            {{ $product->status }}
                        </span>
                    </div>
                    @endforeach
                    @if($company->pharmaceuticalProducts->count() > 5)
                    <div class="text-center mt-3">
                        <a href="{{ route('regulatory-affairs.products', ['company_id' => $company->id]) }}" class="btn btn-sm btn-outline-primary">
                            عرض الكل ({{ $company->pharmaceuticalProducts->count() }})
                        </a>
                    </div>
                    @endif
                </div>
            </div>
        </div>
        @endif

        @if($company->inspectionPermits->count() > 0)
        <div class="col-lg-4 mb-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-search me-2"></i>
                        إجازات الفحص
                        <span class="badge bg-primary ms-2">{{ $company->inspectionPermits->count() }}</span>
                    </h5>
                </div>
                <div class="card-body">
                    @foreach($company->inspectionPermits->take(5) as $permit)
                    <div class="d-flex justify-content-between align-items-center mb-2 pb-2 border-bottom">
                        <div>
                            <strong>{{ $permit->permit_number }}</strong>
                            <br><small class="text-muted">{{ $permit->permit_type }}</small>
                        </div>
                        <span class="badge bg-{{ $permit->status === 'approved' ? 'success' : ($permit->status === 'pending' ? 'warning' : 'secondary') }}">
                            {{ $permit->status }}
                        </span>
                    </div>
                    @endforeach
                    @if($company->inspectionPermits->count() > 5)
                    <div class="text-center mt-3">
                        <a href="{{ route('regulatory-affairs.inspection-permits', ['company_id' => $company->id]) }}" class="btn btn-sm btn-outline-primary">
                            عرض الكل ({{ $company->inspectionPermits->count() }})
                        </a>
                    </div>
                    @endif
                </div>
            </div>
        </div>
        @endif

        @if($company->importPermits->count() > 0)
        <div class="col-lg-4 mb-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-shipping-fast me-2"></i>
                        إجازات الاستيراد
                        <span class="badge bg-primary ms-2">{{ $company->importPermits->count() }}</span>
                    </h5>
                </div>
                <div class="card-body">
                    @foreach($company->importPermits->take(5) as $permit)
                    <div class="d-flex justify-content-between align-items-center mb-2 pb-2 border-bottom">
                        <div>
                            <strong>{{ $permit->permit_number }}</strong>
                            <br><small class="text-muted">{{ $permit->supplier_company }}</small>
                        </div>
                        <span class="badge bg-{{ $permit->status === 'approved' ? 'success' : ($permit->status === 'pending' ? 'warning' : 'secondary') }}">
                            {{ $permit->status }}
                        </span>
                    </div>
                    @endforeach
                    @if($company->importPermits->count() > 5)
                    <div class="text-center mt-3">
                        <a href="{{ route('regulatory-affairs.import-permits', ['company_id' => $company->id]) }}" class="btn btn-sm btn-outline-primary">
                            عرض الكل ({{ $company->importPermits->count() }})
                        </a>
                    </div>
                    @endif
                </div>
            </div>
        </div>
        @endif
    </div>
</div>
@endsection

@push('styles')
<style>
@media print {
    .btn, .card-header, .breadcrumb, nav {
        display: none !important;
    }
    
    .card {
        border: none !important;
        box-shadow: none !important;
    }
    
    .badge {
        background-color: #6c757d !important;
        color: white !important;
    }
}

.card {
    box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
}

.form-label {
    color: #495057;
    margin-bottom: 0.25rem;
}

.fw-bold {
    font-weight: 600 !important;
}

code {
    background-color: #f8f9fa;
    padding: 2px 4px;
    border-radius: 3px;
    font-size: 0.875em;
}

.badge {
    font-size: 0.875em;
}

.border-bottom {
    border-bottom: 1px solid #dee2e6 !important;
}
</style>
@endpush
