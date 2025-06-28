@extends('layouts.app')

@section('title', 'إدارة الرواتب')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">الرئيسية</a></li>
    <li class="breadcrumb-item"><a href="{{ route('hr.index') }}">الموارد البشرية</a></li>
    <li class="breadcrumb-item active">الرواتب</li>
@endsection

@section('content')
<div class="container-fluid">
    <!-- الإحصائيات -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h4>{{ number_format($stats['total_payroll']) }} د.ع</h4>
                            <p class="mb-0">إجمالي الرواتب</p>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-money-bill-wave fa-2x"></i>
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
                            <h4>{{ number_format($stats['paid_payrolls']) }}</h4>
                            <p class="mb-0">رواتب مدفوعة</p>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-check-circle fa-2x"></i>
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
                            <h4>{{ number_format($stats['pending_payrolls']) }}</h4>
                            <p class="mb-0">رواتب معلقة</p>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-hourglass-half fa-2x"></i>
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
    </div>

    <!-- أدوات التحكم -->
    <div class="card mb-4">
        <div class="card-body">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <h5 class="mb-0">
                        <i class="fas fa-money-bill-wave me-2"></i>
                        كشوف الرواتب - {{ $currentMonth }}
                    </h5>
                </div>
                <div class="col-md-6 text-end">
                    <button class="btn btn-primary me-2">
                        <i class="fas fa-plus me-2"></i>
                        إنشاء كشف راتب
                    </button>
                    <button class="btn btn-success me-2">
                        <i class="fas fa-calculator me-2"></i>
                        حساب الرواتب
                    </button>
                    <button class="btn btn-info">
                        <i class="fas fa-file-excel me-2"></i>
                        تصدير Excel
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- جدول الرواتب -->
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>الموظف</th>
                            <th>الراتب الأساسي</th>
                            <th>البدلات</th>
                            <th>الخصومات</th>
                            <th>صافي الراتب</th>
                            <th>تاريخ الدفع</th>
                            <th>الحالة</th>
                            <th>الإجراءات</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($payrolls as $payroll)
                        <tr>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="avatar me-3">
                                        <i class="fas fa-user-circle fa-2x text-primary"></i>
                                    </div>
                                    <div>
                                        <strong>{{ $payroll->employee->full_name }}</strong>
                                        <br>
                                        <small class="text-muted">{{ $payroll->employee->employee_id }}</small>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <strong>{{ number_format($payroll->basic_salary) }} د.ع</strong>
                            </td>
                            <td>
                                <span class="text-success">
                                    +{{ number_format($payroll->total_allowances) }} د.ع
                                </span>
                            </td>
                            <td>
                                <span class="text-danger">
                                    -{{ number_format($payroll->total_deductions) }} د.ع
                                </span>
                            </td>
                            <td>
                                <strong class="text-primary">
                                    {{ number_format($payroll->net_salary) }} د.ع
                                </strong>
                            </td>
                            <td>{{ $payroll->pay_date->format('Y-m-d') }}</td>
                            <td>
                                <span class="badge bg-{{ $payroll->status_badge }}">
                                    {{ $payroll->status_label }}
                                </span>
                            </td>
                            <td>
                                <div class="btn-group" role="group">
                                    <button class="btn btn-sm btn-outline-primary" title="عرض">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    <button class="btn btn-sm btn-outline-info" title="طباعة">
                                        <i class="fas fa-print"></i>
                                    </button>
                                    @if($payroll->status === 'approved')
                                    <button class="btn btn-sm btn-outline-success" title="دفع">
                                        <i class="fas fa-money-bill"></i>
                                    </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center text-muted py-4">
                                <i class="fas fa-money-bill-wave fa-3x mb-3"></i>
                                <br>
                                لا توجد كشوف رواتب لهذا الشهر
                                <br>
                                <button class="btn btn-primary mt-3">
                                    <i class="fas fa-plus me-2"></i>
                                    إنشاء كشوف الرواتب
                                </button>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if($payrolls->hasPages())
            <div class="d-flex justify-content-center mt-4">
                {{ $payrolls->links() }}
            </div>
            @endif
        </div>
    </div>

    <!-- ملخص الرواتب -->
    @if($payrolls->count() > 0)
    <div class="row mt-4">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h6 class="mb-0">
                        <i class="fas fa-chart-pie me-2"></i>
                        ملخص الرواتب
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-4">
                            <h5 class="text-primary">{{ number_format($payrolls->sum('basic_salary')) }}</h5>
                            <small>الرواتب الأساسية</small>
                        </div>
                        <div class="col-4">
                            <h5 class="text-success">{{ number_format($payrolls->sum('total_allowances')) }}</h5>
                            <small>البدلات</small>
                        </div>
                        <div class="col-4">
                            <h5 class="text-danger">{{ number_format($payrolls->sum('total_deductions')) }}</h5>
                            <small>الخصومات</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h6 class="mb-0">
                        <i class="fas fa-info-circle me-2"></i>
                        معلومات مهمة
                    </h6>
                </div>
                <div class="card-body">
                    <ul class="list-unstyled mb-0">
                        <li><i class="fas fa-calendar text-primary me-2"></i>تاريخ دفع الرواتب: 25 من كل شهر</li>
                        <li><i class="fas fa-percentage text-warning me-2"></i>ضريبة الدخل: 15%</li>
                        <li><i class="fas fa-shield-alt text-info me-2"></i>التأمين الاجتماعي: 5%</li>
                        <li><i class="fas fa-clock text-success me-2"></i>ساعات العمل الشهرية: 176 ساعة</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    @endif
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
</style>
@endpush
