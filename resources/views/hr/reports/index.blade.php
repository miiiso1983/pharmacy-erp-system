@extends('layouts.app')

@section('title', 'تقارير الموارد البشرية')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">الرئيسية</a></li>
    <li class="breadcrumb-item"><a href="{{ route('hr.index') }}">الموارد البشرية</a></li>
    <li class="breadcrumb-item active">التقارير</li>
@endsection

@section('content')
<div class="container-fluid">
    <!-- الإحصائيات العامة -->
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
            <div class="card bg-info text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h4>{{ number_format($stats['total_payroll_this_month']) }} د.ع</h4>
                            <p class="mb-0">رواتب هذا الشهر</p>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-money-bill-wave fa-2x"></i>
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
                            <h4>{{ number_format($stats['average_salary']) }} د.ع</h4>
                            <p class="mb-0">متوسط الراتب</p>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-chart-line fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- أنواع التقارير -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-chart-bar me-2"></i>
                        أنواع التقارير المتاحة
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3 mb-3">
                            <div class="card border-primary">
                                <div class="card-body text-center">
                                    <i class="fas fa-users fa-3x text-primary mb-3"></i>
                                    <h6>تقرير الموظفين</h6>
                                    <p class="text-muted small">قائمة شاملة بجميع الموظفين ومعلوماتهم</p>
                                    <button class="btn btn-primary btn-sm">
                                        <i class="fas fa-download me-1"></i>
                                        تحميل
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 mb-3">
                            <div class="card border-success">
                                <div class="card-body text-center">
                                    <i class="fas fa-clock fa-3x text-success mb-3"></i>
                                    <h6>تقرير الحضور</h6>
                                    <p class="text-muted small">إحصائيات الحضور والانصراف الشهرية</p>
                                    <button class="btn btn-success btn-sm">
                                        <i class="fas fa-download me-1"></i>
                                        تحميل
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 mb-3">
                            <div class="card border-warning">
                                <div class="card-body text-center">
                                    <i class="fas fa-calendar-times fa-3x text-warning mb-3"></i>
                                    <h6>تقرير الإجازات</h6>
                                    <p class="text-muted small">تفاصيل الإجازات المأخوذة والمتبقية</p>
                                    <button class="btn btn-warning btn-sm">
                                        <i class="fas fa-download me-1"></i>
                                        تحميل
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 mb-3">
                            <div class="card border-info">
                                <div class="card-body text-center">
                                    <i class="fas fa-money-bill-wave fa-3x text-info mb-3"></i>
                                    <h6>تقرير الرواتب</h6>
                                    <p class="text-muted small">كشوف الرواتب والبدلات والخصومات</p>
                                    <button class="btn btn-info btn-sm">
                                        <i class="fas fa-download me-1"></i>
                                        تحميل
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- إحصائيات الحضور -->
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h6 class="mb-0">
                        <i class="fas fa-chart-pie me-2"></i>
                        إحصائيات الحضور (هذا الشهر)
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-4">
                            <h4 class="text-success">{{ $attendanceStats['present_days'] }}</h4>
                            <small>أيام حضور</small>
                        </div>
                        <div class="col-4">
                            <h4 class="text-danger">{{ $attendanceStats['absent_days'] }}</h4>
                            <small>أيام غياب</small>
                        </div>
                        <div class="col-4">
                            <h4 class="text-warning">{{ $attendanceStats['late_days'] }}</h4>
                            <small>أيام تأخير</small>
                        </div>
                    </div>
                    <hr>
                    <div class="text-center">
                        <button class="btn btn-outline-primary btn-sm">
                            <i class="fas fa-chart-bar me-1"></i>
                            تقرير مفصل
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- إحصائيات الإجازات -->
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h6 class="mb-0">
                        <i class="fas fa-calendar-alt me-2"></i>
                        إحصائيات الإجازات
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-4">
                            <h4 class="text-primary">{{ $leaveStats['annual_leaves'] }}</h4>
                            <small>إجازات سنوية</small>
                        </div>
                        <div class="col-4">
                            <h4 class="text-danger">{{ $leaveStats['sick_leaves'] }}</h4>
                            <small>إجازات مرضية</small>
                        </div>
                        <div class="col-4">
                            <h4 class="text-warning">{{ $leaveStats['emergency_leaves'] }}</h4>
                            <small>إجازات طارئة</small>
                        </div>
                    </div>
                    <hr>
                    <div class="text-center">
                        <button class="btn btn-outline-warning btn-sm">
                            <i class="fas fa-calendar-times me-1"></i>
                            تقرير مفصل
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- إحصائيات الأقسام -->
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h6 class="mb-0">
                        <i class="fas fa-building me-2"></i>
                        توزيع الموظفين حسب الأقسام
                    </h6>
                </div>
                <div class="card-body">
                    <div class="list-group list-group-flush">
                        <div class="list-group-item d-flex justify-content-between align-items-center px-0">
                            الإدارة العامة
                            <span class="badge bg-primary rounded-pill">1</span>
                        </div>
                        <div class="list-group-item d-flex justify-content-between align-items-center px-0">
                            الصيدلة
                            <span class="badge bg-success rounded-pill">1</span>
                        </div>
                        <div class="list-group-item d-flex justify-content-between align-items-center px-0">
                            المبيعات
                            <span class="badge bg-info rounded-pill">1</span>
                        </div>
                        <div class="list-group-item d-flex justify-content-between align-items-center px-0">
                            المحاسبة
                            <span class="badge bg-warning rounded-pill">0</span>
                        </div>
                        <div class="list-group-item d-flex justify-content-between align-items-center px-0">
                            المخازن
                            <span class="badge bg-secondary rounded-pill">0</span>
                        </div>
                    </div>
                    <hr>
                    <div class="text-center">
                        <button class="btn btn-outline-info btn-sm">
                            <i class="fas fa-building me-1"></i>
                            تقرير مفصل
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- أدوات التصدير -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h6 class="mb-0">
                        <i class="fas fa-download me-2"></i>
                        تصدير التقارير
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h6>تصدير بيانات الموظفين</h6>
                            <p class="text-muted">تصدير قائمة شاملة بجميع الموظفين ومعلوماتهم</p>
                            <button class="btn btn-success me-2">
                                <i class="fas fa-file-excel me-1"></i>
                                Excel
                            </button>
                            <button class="btn btn-danger">
                                <i class="fas fa-file-pdf me-1"></i>
                                PDF
                            </button>
                        </div>
                        <div class="col-md-6">
                            <h6>تصدير كشوف الرواتب</h6>
                            <p class="text-muted">تصدير كشوف الرواتب للشهر الحالي</p>
                            <button class="btn btn-success me-2">
                                <i class="fas fa-file-excel me-1"></i>
                                Excel
                            </button>
                            <button class="btn btn-danger">
                                <i class="fas fa-file-pdf me-1"></i>
                                PDF
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
