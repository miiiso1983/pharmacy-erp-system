@extends('layouts.app')

@section('title', __('messages.regulatory_affairs') . ' - ' . __('messages.system_name'))

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">{{ __('navigation.dashboard') }}</a></li>
    <li class="breadcrumb-item active">{{ __('messages.regulatory_affairs') }}</li>
@endsection

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0">
                <i class="fas fa-shield-alt me-2"></i>
                {{ __('messages.regulatory_affairs') }}
            </h1>
            <p class="text-muted">{{ __('messages.regulatory_affairs_management') }}</p>
        </div>
    </div>

    <!-- إحصائيات سريعة -->
    <div class="row mb-4">
        <!-- الشركات -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                الشركات المسجلة
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ number_format($stats['total_companies']) }}
                            </div>
                            <div class="text-xs text-success">
                                <i class="fas fa-check-circle"></i>
                                نشطة: {{ number_format($stats['active_companies']) }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-building fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- المنتجات الدوائية -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                المنتجات الدوائية
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ number_format($stats['total_products']) }}
                            </div>
                            <div class="text-xs text-success">
                                <i class="fas fa-check-circle"></i>
                                مسجلة: {{ number_format($stats['registered_products']) }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-pills fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- إجازات الفحص -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                إجازات الفحص
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ number_format($stats['total_inspections']) }}
                            </div>
                            <div class="text-xs text-warning">
                                <i class="fas fa-clock"></i>
                                معلقة: {{ number_format($stats['pending_inspections']) }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-search fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- إجازات الاستيراد -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                إجازات الاستيراد
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ number_format($stats['total_imports']) }}
                            </div>
                            <div class="text-xs text-warning">
                                <i class="fas fa-clock"></i>
                                معلقة: {{ number_format($stats['pending_imports']) }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-shipping-fast fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- التنبيهات والإشعارات -->
    <div class="row mb-4">
        <!-- الشركات منتهية الصلاحية -->
        @if($alerts['companies_expiring']->count() > 0)
        <div class="col-lg-6 mb-4">
            <div class="card shadow">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-warning">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        شركات تنتهي صلاحيتها قريباً
                    </h6>
                    <span class="badge bg-warning">{{ $alerts['companies_expiring']->count() }}</span>
                </div>
                <div class="card-body">
                    @foreach($alerts['companies_expiring'] as $company)
                    <div class="d-flex align-items-center mb-2">
                        <div class="flex-grow-1">
                            <strong>{{ $company->company_name }}</strong>
                            <br>
                            <small class="text-muted">
                                ينتهي في: {{ $company->expiry_date->format('d/m/Y') }}
                                ({{ $company->getDaysUntilExpiry() }} يوم)
                            </small>
                        </div>
                        <span class="badge bg-{{ $company->getAlertStatus() === 'critical' ? 'danger' : 'warning' }}">
                            {{ $company->getAlertStatus() === 'critical' ? 'حرج' : 'تحذير' }}
                        </span>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
        @endif

        <!-- المنتجات منتهية الصلاحية -->
        @if($alerts['products_expiring']->count() > 0)
        <div class="col-lg-6 mb-4">
            <div class="card shadow">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-warning">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        منتجات تنتهي صلاحيتها قريباً
                    </h6>
                    <span class="badge bg-warning">{{ $alerts['products_expiring']->count() }}</span>
                </div>
                <div class="card-body">
                    @foreach($alerts['products_expiring'] as $product)
                    <div class="d-flex align-items-center mb-2">
                        <div class="flex-grow-1">
                            <strong>{{ $product->product_name }}</strong>
                            <br>
                            <small class="text-muted">
                                ينتهي في: {{ $product->expiry_date->format('d/m/Y') }}
                                ({{ $product->getDaysUntilExpiry() }} يوم)
                            </small>
                        </div>
                        <span class="badge bg-{{ $product->getAlertStatus() === 'critical' ? 'danger' : 'warning' }}">
                            {{ $product->getAlertStatus() === 'critical' ? 'حرج' : 'تحذير' }}
                        </span>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
        @endif
    </div>

    <!-- الروابط السريعة -->
    <div class="row">
        <!-- إدارة الشركات -->
        <div class="col-lg-3 col-md-6 mb-4">
            <div class="card text-center shadow h-100">
                <div class="card-body d-flex flex-column">
                    <div class="mb-3">
                        <i class="fas fa-building fa-3x text-primary"></i>
                    </div>
                    <h5 class="card-title">إدارة الشركات</h5>
                    <p class="card-text flex-grow-1">
                        تسجيل وإدارة الشركات الدوائية والموردين
                    </p>
                    <a href="{{ route('regulatory-affairs.companies') }}" class="btn btn-primary">
                        <i class="fas fa-arrow-left me-2"></i>
                        عرض الشركات
                    </a>
                </div>
            </div>
        </div>

        <!-- إدارة المنتجات -->
        <div class="col-lg-3 col-md-6 mb-4">
            <div class="card text-center shadow h-100">
                <div class="card-body d-flex flex-column">
                    <div class="mb-3">
                        <i class="fas fa-pills fa-3x text-success"></i>
                    </div>
                    <h5 class="card-title">المنتجات الدوائية</h5>
                    <p class="card-text flex-grow-1">
                        تسجيل وإدارة المنتجات الدوائية والأجهزة الطبية
                    </p>
                    <a href="{{ route('regulatory-affairs.products') }}" class="btn btn-success">
                        <i class="fas fa-arrow-left me-2"></i>
                        عرض المنتجات
                    </a>
                </div>
            </div>
        </div>

        <!-- إجازات الفحص -->
        <div class="col-lg-3 col-md-6 mb-4">
            <div class="card text-center shadow h-100">
                <div class="card-body d-flex flex-column">
                    <div class="mb-3">
                        <i class="fas fa-search fa-3x text-info"></i>
                    </div>
                    <h5 class="card-title">إجازات الفحص</h5>
                    <p class="card-text flex-grow-1">
                        إدارة إجازات فحص المرافق والمنتجات
                    </p>
                    <a href="{{ route('regulatory-affairs.inspection-permits') }}" class="btn btn-info">
                        <i class="fas fa-arrow-left me-2"></i>
                        عرض الإجازات
                    </a>
                </div>
            </div>
        </div>

        <!-- إجازات الاستيراد -->
        <div class="col-lg-3 col-md-6 mb-4">
            <div class="card text-center shadow h-100">
                <div class="card-body d-flex flex-column">
                    <div class="mb-3">
                        <i class="fas fa-shipping-fast fa-3x text-warning"></i>
                    </div>
                    <h5 class="card-title">إجازات الاستيراد</h5>
                    <p class="card-text flex-grow-1">
                        إدارة إجازات استيراد المنتجات الدوائية
                    </p>
                    <a href="{{ route('regulatory-affairs.import-permits') }}" class="btn btn-warning">
                        <i class="fas fa-arrow-left me-2"></i>
                        عرض الإجازات
                    </a>
                </div>
            </div>
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

.text-gray-800 {
    color: #5a5c69 !important;
}

.text-gray-300 {
    color: #dddfeb !important;
}

.card {
    transition: all 0.3s;
}

.card:hover {
    transform: translateY(-2px);
    box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15) !important;
}

.badge {
    font-size: 0.75em;
}
</style>
@endpush
