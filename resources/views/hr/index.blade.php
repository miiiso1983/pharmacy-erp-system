@extends('layouts.app')

@section('title', 'نظام الموارد البشرية')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">الرئيسية</a></li>
    <li class="breadcrumb-item active">الموارد البشرية</li>
@endsection

@section('content')
<div class="container-fluid">
    <!-- الإحصائيات الرئيسية -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h4>{{ number_format($stats['total_employees']) }}</h4>
                            <p class="mb-0">إجمالي الموظفين</p>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-users fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h4>{{ number_format($stats['active_employees']) }}</h4>
                            <p class="mb-0">الموظفون النشطون</p>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-user-check fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-info text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h4>{{ number_format($stats['total_departments']) }}</h4>
                            <p class="mb-0">الأقسام</p>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-building fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-warning text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h4>{{ number_format($stats['pending_leaves']) }}</h4>
                            <p class="mb-0">الإجازات المعلقة</p>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-calendar-times fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- إحصائيات الحضور اليوم -->
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card bg-light">
                <div class="card-body text-center">
                    <h3 class="text-success">{{ number_format($stats['present_today']) }}</h3>
                    <p class="mb-0">حاضر اليوم</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card bg-light">
                <div class="card-body text-center">
                    <h3 class="text-danger">{{ number_format($stats['absent_today']) }}</h3>
                    <p class="mb-0">غائب اليوم</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card bg-light">
                <div class="card-body text-center">
                    <h3 class="text-primary">{{ number_format($stats['total_payroll_this_month']) }} د.ع</h3>
                    <p class="mb-0">رواتب هذا الشهر</p>
                </div>
            </div>
        </div>
    </div>

    <!-- أزرار الوصول السريع -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-tachometer-alt me-2"></i>
                        الوصول السريع
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-2 mb-3">
                            <a href="{{ route('hr.employees') }}" class="btn btn-primary btn-lg w-100">
                                <i class="fas fa-users d-block mb-2"></i>
                                الموظفون
                            </a>
                        </div>
                        <div class="col-md-2 mb-3">
                            <a href="{{ route('hr.departments') }}" class="btn btn-info btn-lg w-100">
                                <i class="fas fa-building d-block mb-2"></i>
                                الأقسام
                            </a>
                        </div>
                        <div class="col-md-2 mb-3">
                            <a href="{{ route('hr.attendance') }}" class="btn btn-success btn-lg w-100">
                                <i class="fas fa-clock d-block mb-2"></i>
                                الحضور
                            </a>
                        </div>
                        <div class="col-md-2 mb-3">
                            <a href="{{ route('hr.leaves') }}" class="btn btn-warning btn-lg w-100">
                                <i class="fas fa-calendar-times d-block mb-2"></i>
                                الإجازات
                            </a>
                        </div>
                        <div class="col-md-2 mb-3">
                            <a href="{{ route('hr.payroll') }}" class="btn btn-secondary btn-lg w-100">
                                <i class="fas fa-money-bill-wave d-block mb-2"></i>
                                الرواتب
                            </a>
                        </div>
                        <div class="col-md-2 mb-3">
                            <a href="{{ route('hr.reports') }}" class="btn btn-dark btn-lg w-100">
                                <i class="fas fa-chart-bar d-block mb-2"></i>
                                التقارير
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- الموظفون الجدد -->
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h6 class="mb-0">
                        <i class="fas fa-user-plus me-2"></i>
                        الموظفون الجدد هذا الشهر
                    </h6>
                </div>
                <div class="card-body">
                    @if($newEmployees->count() > 0)
                        @foreach($newEmployees as $employee)
                        <div class="d-flex align-items-center mb-3">
                            <div class="avatar me-3">
                                <i class="fas fa-user-circle fa-2x text-primary"></i>
                            </div>
                            <div>
                                <h6 class="mb-0">{{ $employee->full_name }}</h6>
                                <small class="text-muted">{{ $employee->department->name ?? 'غير محدد' }}</small>
                                <br>
                                <small class="text-muted">{{ $employee->hire_date->format('Y-m-d') }}</small>
                            </div>
                        </div>
                        @endforeach
                    @else
                        <p class="text-muted text-center">لا يوجد موظفون جدد هذا الشهر</p>
                    @endif
                </div>
            </div>
        </div>

        <!-- الإجازات المعلقة -->
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h6 class="mb-0">
                        <i class="fas fa-hourglass-half me-2"></i>
                        الإجازات المعلقة
                    </h6>
                </div>
                <div class="card-body">
                    @if($pendingLeaves->count() > 0)
                        @foreach($pendingLeaves as $leave)
                        <div class="d-flex align-items-center mb-3">
                            <div class="avatar me-3">
                                <i class="fas fa-calendar-times fa-2x text-warning"></i>
                            </div>
                            <div>
                                <h6 class="mb-0">{{ $leave->employee->full_name }}</h6>
                                <small class="text-muted">{{ $leave->type }} - {{ $leave->days_requested }} أيام</small>
                                <br>
                                <small class="text-muted">{{ $leave->start_date->format('Y-m-d') }}</small>
                            </div>
                        </div>
                        @endforeach
                    @else
                        <p class="text-muted text-center">لا توجد إجازات معلقة</p>
                    @endif
                </div>
            </div>
        </div>

        <!-- أعياد الميلاد -->
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h6 class="mb-0">
                        <i class="fas fa-birthday-cake me-2"></i>
                        أعياد الميلاد هذا الشهر
                    </h6>
                </div>
                <div class="card-body">
                    @if($birthdays->count() > 0)
                        @foreach($birthdays as $employee)
                        <div class="d-flex align-items-center mb-3">
                            <div class="avatar me-3">
                                <i class="fas fa-birthday-cake fa-2x text-success"></i>
                            </div>
                            <div>
                                <h6 class="mb-0">{{ $employee->full_name }}</h6>
                                <small class="text-muted">{{ $employee->birth_date->format('m-d') }}</small>
                                <br>
                                <small class="text-muted">{{ $employee->age }} سنة</small>
                            </div>
                        </div>
                        @endforeach
                    @else
                        <p class="text-muted text-center">لا توجد أعياد ميلاد هذا الشهر</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
.avatar {
    width: 40px;
    height: 40px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.btn-lg i {
    font-size: 1.5rem;
}
</style>
@endpush
