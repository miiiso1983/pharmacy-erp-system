@extends('layouts.app')

@section('title', __('messages.hr_management') . ' - ' . __('messages.system_name'))

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">{{ __('navigation.dashboard') }}</a></li>
    <li class="breadcrumb-item active">{{ __('messages.hr_management') }}</li>
@endsection

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0">
                <i class="fas fa-users-cog me-2"></i>
                {{ __('messages.hr_management') }}
            </h1>
            <p class="text-muted">لوحة تحكم شاملة لإدارة الموارد البشرية</p>
        </div>
    </div>

    <!-- إحصائيات سريعة -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                إجمالي الموظفين
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $stats['total_employees'] ?? 0 }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-users fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                الموظفون النشطون
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $stats['active_employees'] ?? 0 }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-user-check fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                الحضور اليوم
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $stats['present_today'] ?? 0 }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-calendar-check fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                الإجازات المعلقة
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $stats['pending_leaves'] ?? 0 }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-clock fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- الوحدات الرئيسية -->
    <div class="row mb-4">
        <div class="col-12">
            <h4 class="mb-3">
                <i class="fas fa-th-large me-2"></i>
                وحدات الموارد البشرية
            </h4>
        </div>
    </div>

    <div class="row">
        <!-- إدارة الموظفين -->
        <div class="col-lg-4 col-md-6 mb-4">
            <div class="card h-100 shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-users me-2"></i>
                        إدارة الموظفين
                    </h5>
                </div>
                <div class="card-body">
                    <p class="card-text">إدارة بيانات الموظفين، إضافة موظفين جدد، تحديث المعلومات</p>
                    <div class="mb-3">
                        <small class="text-muted">
                            <i class="fas fa-user me-1"></i>
                            {{ $stats['active_employees'] ?? 0 }} موظف نشط
                        </small>
                    </div>
                    <div class="d-grid gap-2">
                        <a href="{{ route('hr.employees') }}" class="btn btn-primary">
                            <i class="fas fa-eye me-1"></i>
                            عرض الموظفين
                        </a>
                        <a href="{{ route('hr.employees.create') }}" class="btn btn-outline-primary">
                            <i class="fas fa-plus me-1"></i>
                            إضافة موظف
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- إدارة الأقسام -->
        <div class="col-lg-4 col-md-6 mb-4">
            <div class="card h-100 shadow-sm">
                <div class="card-header bg-success text-white">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-building me-2"></i>
                        إدارة الأقسام
                    </h5>
                </div>
                <div class="card-body">
                    <p class="card-text">إدارة أقسام الشركة، تنظيم الهيكل التنظيمي</p>
                    <div class="mb-3">
                        <small class="text-muted">
                            <i class="fas fa-building me-1"></i>
                            {{ $stats['departments_count'] ?? 0 }} قسم
                        </small>
                    </div>
                    <div class="d-grid gap-2">
                        <a href="{{ route('hr.departments') }}" class="btn btn-success">
                            <i class="fas fa-eye me-1"></i>
                            عرض الأقسام
                        </a>
                        <a href="{{ route('hr.departments.create') }}" class="btn btn-outline-success">
                            <i class="fas fa-plus me-1"></i>
                            إضافة قسم
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- الحضور والانصراف -->
        <div class="col-lg-4 col-md-6 mb-4">
            <div class="card h-100 shadow-sm">
                <div class="card-header bg-info text-white">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-calendar-check me-2"></i>
                        الحضور والانصراف
                    </h5>
                </div>
                <div class="card-body">
                    <p class="card-text">تسجيل ومتابعة حضور وانصراف الموظفين</p>
                    <div class="mb-3">
                        <small class="text-muted">
                            <i class="fas fa-check me-1"></i>
                            {{ $stats['present_today'] ?? 0 }} حاضر اليوم
                        </small>
                    </div>
                    <div class="d-grid gap-2">
                        <a href="{{ route('hr.attendance') }}" class="btn btn-info">
                            <i class="fas fa-eye me-1"></i>
                            عرض الحضور
                        </a>
                        <a href="{{ route('hr.attendance.mark') }}" class="btn btn-outline-info">
                            <i class="fas fa-plus me-1"></i>
                            تسجيل حضور
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- إدارة الإجازات -->
        <div class="col-lg-4 col-md-6 mb-4">
            <div class="card h-100 shadow-sm">
                <div class="card-header bg-warning text-white">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-calendar-times me-2"></i>
                        إدارة الإجازات
                    </h5>
                </div>
                <div class="card-body">
                    <p class="card-text">إدارة طلبات الإجازات والموافقة عليها</p>
                    <div class="mb-3">
                        <small class="text-muted">
                            <i class="fas fa-clock me-1"></i>
                            {{ $stats['pending_leaves'] ?? 0 }} طلب معلق
                        </small>
                    </div>
                    <div class="d-grid gap-2">
                        <a href="{{ route('hr.leaves') }}" class="btn btn-warning">
                            <i class="fas fa-eye me-1"></i>
                            عرض الإجازات
                        </a>
                        <a href="{{ route('hr.leaves.create') }}" class="btn btn-outline-warning">
                            <i class="fas fa-plus me-1"></i>
                            طلب إجازة
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- إدارة الرواتب -->
        <div class="col-lg-4 col-md-6 mb-4">
            <div class="card h-100 shadow-sm">
                <div class="card-header bg-danger text-white">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-money-bill-wave me-2"></i>
                        إدارة الرواتب
                    </h5>
                </div>
                <div class="card-body">
                    <p class="card-text">إدارة رواتب الموظفين وكشوف المرتبات</p>
                    <div class="mb-3">
                        <small class="text-muted">
                            <i class="fas fa-dollar-sign me-1"></i>
                            كشف مرتبات الشهر الحالي
                        </small>
                    </div>
                    <div class="d-grid gap-2">
                        <a href="{{ route('hr.payroll') }}" class="btn btn-danger">
                            <i class="fas fa-eye me-1"></i>
                            عرض الرواتب
                        </a>
                        <a href="{{ route('hr.payroll.create') }}" class="btn btn-outline-danger">
                            <i class="fas fa-plus me-1"></i>
                            إنشاء كشف مرتبات
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- تقارير HR -->
        <div class="col-lg-4 col-md-6 mb-4">
            <div class="card h-100 shadow-sm">
                <div class="card-header bg-dark text-white">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-chart-bar me-2"></i>
                        تقارير الموارد البشرية
                    </h5>
                </div>
                <div class="card-body">
                    <p class="card-text">تقارير شاملة عن الموظفين والحضور والرواتب</p>
                    <div class="mb-3">
                        <small class="text-muted">
                            <i class="fas fa-file-alt me-1"></i>
                            تقارير متنوعة ومفصلة
                        </small>
                    </div>
                    <div class="d-grid gap-2">
                        <a href="{{ route('hr.reports') }}" class="btn btn-dark">
                            <i class="fas fa-eye me-1"></i>
                            عرض التقارير
                        </a>
                        <a href="{{ route('hr.reports.generate') }}" class="btn btn-outline-dark">
                            <i class="fas fa-download me-1"></i>
                            إنشاء تقرير
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- الأنشطة الأخيرة -->
    @if(isset($newEmployees) && $newEmployees->count() > 0)
    <div class="row mt-4">
        <div class="col-lg-6">
            <div class="card shadow">
                <div class="card-header">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-user-plus me-2"></i>
                        الموظفون الجدد هذا الشهر
                    </h6>
                </div>
                <div class="card-body">
                    @foreach($newEmployees as $employee)
                    <div class="d-flex align-items-center mb-3">
                        <div class="avatar me-3">
                            <div class="avatar-initial bg-primary rounded-circle">
                                {{ substr($employee->first_name, 0, 1) }}{{ substr($employee->last_name, 0, 1) }}
                            </div>
                        </div>
                        <div>
                            <h6 class="mb-0">{{ $employee->first_name }} {{ $employee->last_name }}</h6>
                            <small class="text-muted">{{ $employee->department->name ?? 'غير محدد' }}</small>
                        </div>
                        <div class="ms-auto">
                            <small class="text-muted">{{ $employee->hire_date->format('d/m/Y') }}</small>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="card shadow">
                <div class="card-header">
                    <h6 class="m-0 font-weight-bold text-warning">
                        <i class="fas fa-clock me-2"></i>
                        الإجازات المعلقة
                    </h6>
                </div>
                <div class="card-body">
                    @if(isset($pendingLeaves) && $pendingLeaves->count() > 0)
                        @foreach($pendingLeaves as $leave)
                        <div class="d-flex align-items-center mb-3">
                            <div class="avatar me-3">
                                <div class="avatar-initial bg-warning rounded-circle">
                                    <i class="fas fa-calendar-times"></i>
                                </div>
                            </div>
                            <div>
                                <h6 class="mb-0">{{ $leave->employee->first_name }} {{ $leave->employee->last_name }}</h6>
                                <small class="text-muted">{{ $leave->type }} - {{ $leave->days }} أيام</small>
                            </div>
                            <div class="ms-auto">
                                <small class="text-muted">{{ $leave->created_at->diffForHumans() }}</small>
                            </div>
                        </div>
                        @endforeach
                    @else
                        <p class="text-muted text-center">لا توجد إجازات معلقة</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
    @endif
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

.card {
    transition: transform 0.2s;
}

.card:hover {
    transform: translateY(-2px);
}
</style>
@endpush
