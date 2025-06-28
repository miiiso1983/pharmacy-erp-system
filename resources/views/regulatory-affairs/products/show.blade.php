@extends('layouts.app')

@section('title', 'عرض المنتج - ' . $product->product_name . ' - ' . __('messages.system_name'))

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">{{ __('navigation.dashboard') }}</a></li>
    <li class="breadcrumb-item"><a href="{{ route('regulatory-affairs.dashboard') }}">الشؤون التنظيمية</a></li>
    <li class="breadcrumb-item"><a href="{{ route('regulatory-affairs.products') }}">المنتجات الدوائية</a></li>
    <li class="breadcrumb-item active">{{ $product->product_name }}</li>
@endsection

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0">
                <i class="fas fa-pills me-2"></i>
                {{ $product->product_name }}
            </h1>
            <p class="text-muted">{{ $product->generic_name }}</p>
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
            <a href="{{ route('regulatory-affairs.products') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left me-2"></i>
                العودة للقائمة
            </a>
        </div>
    </div>

    <div class="row">
        <!-- معلومات المنتج الأساسية -->
        <div class="col-lg-8 mb-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-info-circle me-2"></i>
                        معلومات المنتج
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">رمز المنتج:</label>
                            <p class="mb-0"><code>{{ $product->product_code }}</code></p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">رقم التسجيل:</label>
                            <p class="mb-0"><code>{{ $product->registration_number }}</code></p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">اسم المنتج:</label>
                            <p class="mb-0">{{ $product->product_name }}</p>
                            @if($product->product_name_en)
                                <small class="text-muted">{{ $product->product_name_en }}</small>
                            @endif
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">الاسم العلمي:</label>
                            <p class="mb-0">{{ $product->generic_name }}</p>
                        </div>
                        @if($product->brand_name)
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">الاسم التجاري:</label>
                            <p class="mb-0">{{ $product->brand_name }}</p>
                        </div>
                        @endif
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">الشركة المصنعة:</label>
                            <p class="mb-0">
                                <a href="{{ route('regulatory-affairs.companies.show', $product->company->id) }}" class="text-decoration-none">
                                    {{ $product->company->company_name }}
                                </a>
                                <br><small class="text-muted">{{ $product->company->country }}</small>
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- التصنيف والمواصفات -->
            <div class="card mt-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-tags me-2"></i>
                        التصنيف والمواصفات
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label class="form-label fw-bold">نوع المنتج:</label>
                            <p class="mb-0">
                                <span class="badge bg-info fs-6">
                                    {{ trans_product_type($product->product_type) }}
                                </span>
                            </p>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label fw-bold">الشكل الصيدلاني:</label>
                            <p class="mb-0">
                                <span class="badge bg-secondary fs-6">
                                    {{ trans_dosage_form($product->dosage_form) }}
                                </span>
                            </p>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label fw-bold">حالة الوصفة:</label>
                            <p class="mb-0">
                                <span class="badge bg-{{ $product->prescription_status === 'prescription' ? 'warning' : ($product->prescription_status === 'controlled' ? 'danger' : 'success') }} fs-6">
                                    {{ trans_prescription_status($product->prescription_status) }}
                                </span>
                            </p>
                        </div>
                        @if($product->strength)
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">التركيز:</label>
                            <p class="mb-0">{{ $product->strength }}</p>
                        </div>
                        @endif
                        @if($product->pack_size)
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">حجم العبوة:</label>
                            <p class="mb-0">{{ $product->pack_size }}</p>
                        </div>
                        @endif
                        @if($product->atc_code)
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">رمز ATC:</label>
                            <p class="mb-0"><code>{{ $product->atc_code }}</code></p>
                        </div>
                        @endif
                        @if($product->price)
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">السعر:</label>
                            <p class="mb-0"><strong>{{ number_format($product->price, 2) }} د.ع</strong></p>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- المعلومات الطبية -->
            @if($product->composition || $product->indications || $product->contraindications || $product->side_effects)
            <div class="card mt-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-stethoscope me-2"></i>
                        المعلومات الطبية
                    </h5>
                </div>
                <div class="card-body">
                    @if($product->composition)
                    <div class="mb-3">
                        <label class="form-label fw-bold">التركيب:</label>
                        <p class="mb-0">{{ $product->composition }}</p>
                    </div>
                    @endif
                    @if($product->indications)
                    <div class="mb-3">
                        <label class="form-label fw-bold">دواعي الاستعمال:</label>
                        <p class="mb-0">{{ $product->indications }}</p>
                    </div>
                    @endif
                    @if($product->contraindications)
                    <div class="mb-3">
                        <label class="form-label fw-bold">موانع الاستعمال:</label>
                        <p class="mb-0">{{ $product->contraindications }}</p>
                    </div>
                    @endif
                    @if($product->side_effects)
                    <div class="mb-3">
                        <label class="form-label fw-bold">الآثار الجانبية:</label>
                        <p class="mb-0">{{ $product->side_effects }}</p>
                    </div>
                    @endif
                    @if($product->dosage_instructions)
                    <div class="mb-3">
                        <label class="form-label fw-bold">تعليمات الجرعة:</label>
                        <p class="mb-0">{{ $product->dosage_instructions }}</p>
                    </div>
                    @endif
                    @if($product->storage_conditions)
                    <div class="mb-3">
                        <label class="form-label fw-bold">شروط التخزين:</label>
                        <p class="mb-0">{{ $product->storage_conditions }}</p>
                    </div>
                    @endif
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
                        <i class="fas fa-certificate me-2"></i>
                        حالة التسجيل
                    </h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label fw-bold">الحالة الحالية:</label>
                        <p class="mb-0">
                            @switch($product->status)
                                @case('registered')
                                    <span class="badge bg-success fs-6">مسجل</span>
                                    @break
                                @case('pending')
                                    <span class="badge bg-warning fs-6">معلق</span>
                                    @break
                                @case('rejected')
                                    <span class="badge bg-danger fs-6">مرفوض</span>
                                    @break
                                @case('expired')
                                    <span class="badge bg-secondary fs-6">منتهي الصلاحية</span>
                                    @break
                                @case('suspended')
                                    <span class="badge bg-dark fs-6">معلق</span>
                                    @break
                                @default
                                    <span class="badge bg-secondary fs-6">{{ $product->status }}</span>
                            @endswitch
                        </p>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">تاريخ التسجيل:</label>
                        <p class="mb-0">{{ $product->registration_date->format('d/m/Y') }}</p>
                    </div>
                    @if($product->expiry_date)
                    <div class="mb-3">
                        <label class="form-label fw-bold">تاريخ الانتهاء:</label>
                        <p class="mb-0">
                            <span class="badge bg-{{ $product->getAlertStatus() === 'expired' ? 'danger' : ($product->getAlertStatus() === 'critical' ? 'warning' : 'success') }} fs-6">
                                {{ $product->expiry_date->format('d/m/Y') }}
                            </span>
                        </p>
                        @if($product->getDaysUntilExpiry() !== null)
                            <small class="text-muted">
                                @if($product->getDaysUntilExpiry() < 0)
                                    منتهي منذ {{ abs($product->getDaysUntilExpiry()) }} يوم
                                @else
                                    باقي {{ $product->getDaysUntilExpiry() }} يوم
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
                                <h4 class="text-primary mb-1">{{ $product->inspectionPermits->count() }}</h4>
                                <small class="text-muted">إجازات فحص</small>
                            </div>
                        </div>
                        <div class="col-6 mb-3">
                            <div class="border rounded p-2">
                                <h4 class="text-success mb-1">{{ $product->importPermits->count() }}</h4>
                                <small class="text-muted">إجازات استيراد</small>
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
                        <p class="mb-0">{{ $product->created_at->format('d/m/Y H:i') }}</p>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">آخر تحديث:</label>
                        <p class="mb-0">{{ $product->updated_at->format('d/m/Y H:i') }}</p>
                    </div>
                    @if($product->barcode)
                    <div class="mb-3">
                        <label class="form-label fw-bold">الباركود:</label>
                        <p class="mb-0"><code>{{ $product->barcode }}</code></p>
                    </div>
                    @endif
                    @if($product->notes)
                    <div class="mb-3">
                        <label class="form-label fw-bold">ملاحظات:</label>
                        <p class="mb-0">{{ $product->notes }}</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- إجازات الفحص والاستيراد -->
    @if($product->inspectionPermits->count() > 0 || $product->importPermits->count() > 0)
    <div class="row mt-4">
        @if($product->inspectionPermits->count() > 0)
        <div class="col-lg-6 mb-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-search me-2"></i>
                        إجازات الفحص
                        <span class="badge bg-primary ms-2">{{ $product->inspectionPermits->count() }}</span>
                    </h5>
                </div>
                <div class="card-body">
                    @foreach($product->inspectionPermits->take(5) as $permit)
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
                    @if($product->inspectionPermits->count() > 5)
                    <div class="text-center mt-3">
                        <a href="{{ route('regulatory-affairs.inspection-permits', ['product_id' => $product->id]) }}" class="btn btn-sm btn-outline-primary">
                            عرض الكل ({{ $product->inspectionPermits->count() }})
                        </a>
                    </div>
                    @endif
                </div>
            </div>
        </div>
        @endif

        @if($product->importPermits->count() > 0)
        <div class="col-lg-6 mb-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-shipping-fast me-2"></i>
                        إجازات الاستيراد
                        <span class="badge bg-primary ms-2">{{ $product->importPermits->count() }}</span>
                    </h5>
                </div>
                <div class="card-body">
                    @foreach($product->importPermits->take(5) as $permit)
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
                    @if($product->importPermits->count() > 5)
                    <div class="text-center mt-3">
                        <a href="{{ route('regulatory-affairs.import-permits', ['product_id' => $product->id]) }}" class="btn btn-sm btn-outline-primary">
                            عرض الكل ({{ $product->importPermits->count() }})
                        </a>
                    </div>
                    @endif
                </div>
            </div>
        </div>
        @endif
    </div>
    @endif
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
