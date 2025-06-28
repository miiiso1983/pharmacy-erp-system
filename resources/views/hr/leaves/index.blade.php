@extends('layouts.app')

@section('title', 'إدارة الإجازات')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">الرئيسية</a></li>
    <li class="breadcrumb-item"><a href="{{ route('hr.index') }}">الموارد البشرية</a></li>
    <li class="breadcrumb-item active">الإجازات</li>
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
                            <h4>{{ number_format($stats['total_leaves']) }}</h4>
                            <p class="mb-0">إجمالي الإجازات</p>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-calendar-times fa-2x"></i>
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
                            <p class="mb-0">معلقة</p>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-hourglass-half fa-2x"></i>
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
                            <h4>{{ number_format($stats['approved_leaves']) }}</h4>
                            <p class="mb-0">موافق عليها</p>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-check-circle fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-danger text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h4>{{ number_format($stats['rejected_leaves']) }}</h4>
                            <p class="mb-0">مرفوضة</p>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-times-circle fa-2x"></i>
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
                        <i class="fas fa-calendar-times me-2"></i>
                        طلبات الإجازات
                    </h5>
                </div>
                <div class="col-md-6 text-end">
                    <button class="btn btn-primary me-2">
                        <i class="fas fa-plus me-2"></i>
                        طلب إجازة جديد
                    </button>
                    <button class="btn btn-success">
                        <i class="fas fa-file-excel me-2"></i>
                        تصدير Excel
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- جدول الإجازات -->
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>الموظف</th>
                            <th>نوع الإجازة</th>
                            <th>تاريخ البداية</th>
                            <th>تاريخ النهاية</th>
                            <th>عدد الأيام</th>
                            <th>السبب</th>
                            <th>الحالة</th>
                            <th>الإجراءات</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($leaves as $leave)
                        <tr>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="avatar me-3">
                                        <i class="fas fa-user-circle fa-2x text-primary"></i>
                                    </div>
                                    <div>
                                        <strong>{{ $leave->employee->full_name }}</strong>
                                        <br>
                                        <small class="text-muted">{{ $leave->employee->employee_id }}</small>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span class="badge bg-info">{{ $leave->type_label }}</span>
                            </td>
                            <td>{{ $leave->start_date->format('Y-m-d') }}</td>
                            <td>{{ $leave->end_date->format('Y-m-d') }}</td>
                            <td>
                                <strong>{{ $leave->days_requested }} يوم</strong>
                            </td>
                            <td>
                                <span title="{{ $leave->reason }}">
                                    {{ Str::limit($leave->reason, 30) }}
                                </span>
                            </td>
                            <td>
                                <span class="badge bg-{{ $leave->status_badge }}">
                                    {{ $leave->status_label }}
                                </span>
                            </td>
                            <td>
                                <div class="btn-group" role="group">
                                    @if($leave->status === 'pending')
                                    <button class="btn btn-sm btn-outline-success" title="موافقة">
                                        <i class="fas fa-check"></i>
                                    </button>
                                    <button class="btn btn-sm btn-outline-danger" title="رفض">
                                        <i class="fas fa-times"></i>
                                    </button>
                                    @endif
                                    <button class="btn btn-sm btn-outline-primary" title="عرض">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center text-muted py-4">
                                <i class="fas fa-calendar-times fa-3x mb-3"></i>
                                <br>
                                لا توجد طلبات إجازات
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if($leaves->hasPages())
            <div class="d-flex justify-content-center mt-4">
                {{ $leaves->links() }}
            </div>
            @endif
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
</style>
@endpush
