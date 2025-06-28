@extends('layouts.app')

@section('title', 'الحضور والانصراف')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">الرئيسية</a></li>
    <li class="breadcrumb-item"><a href="{{ route('hr.index') }}">الموارد البشرية</a></li>
    <li class="breadcrumb-item active">الحضور والانصراف</li>
@endsection

@section('content')
<div class="container-fluid">
    <!-- إحصائيات اليوم -->
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
                            <h4>{{ number_format($stats['present_today']) }}</h4>
                            <p class="mb-0">حاضر اليوم</p>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-user-check fa-2x"></i>
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
                            <h4>{{ number_format($stats['absent_today']) }}</h4>
                            <p class="mb-0">غائب اليوم</p>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-user-times fa-2x"></i>
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
                            <h4>{{ number_format($stats['late_today']) }}</h4>
                            <p class="mb-0">متأخر اليوم</p>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-clock fa-2x"></i>
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
                        <i class="fas fa-clock me-2"></i>
                        سجل الحضور - {{ today()->format('Y-m-d') }}
                    </h5>
                </div>
                <div class="col-md-6 text-end">
                    <button class="btn btn-primary me-2">
                        <i class="fas fa-plus me-2"></i>
                        تسجيل حضور
                    </button>
                    <button class="btn btn-success me-2">
                        <i class="fas fa-file-excel me-2"></i>
                        تصدير Excel
                    </button>
                    <button class="btn btn-info">
                        <i class="fas fa-calendar me-2"></i>
                        تقرير شهري
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- جدول الحضور -->
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>الموظف</th>
                            <th>القسم</th>
                            <th>وقت الحضور</th>
                            <th>وقت الانصراف</th>
                            <th>ساعات العمل</th>
                            <th>الحالة</th>
                            <th>ملاحظات</th>
                            <th>الإجراءات</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($attendances as $attendance)
                        <tr>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="avatar me-3">
                                        <i class="fas fa-user-circle fa-2x text-primary"></i>
                                    </div>
                                    <div>
                                        <strong>{{ $attendance->employee->full_name }}</strong>
                                        <br>
                                        <small class="text-muted">{{ $attendance->employee->employee_id }}</small>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span class="badge bg-info">{{ $attendance->employee->department->name ?? 'غير محدد' }}</span>
                            </td>
                            <td>
                                @if($attendance->check_in)
                                    <span class="text-success">
                                        <i class="fas fa-sign-in-alt me-1"></i>
                                        {{ $attendance->check_in }}
                                    </span>
                                @else
                                    <span class="text-muted">لم يسجل</span>
                                @endif
                            </td>
                            <td>
                                @if($attendance->check_out)
                                    <span class="text-danger">
                                        <i class="fas fa-sign-out-alt me-1"></i>
                                        {{ $attendance->check_out }}
                                    </span>
                                @else
                                    <span class="text-muted">لم يسجل</span>
                                @endif
                            </td>
                            <td>
                                @if($attendance->total_hours > 0)
                                    <strong>{{ $attendance->total_hours_formatted }}</strong>
                                @else
                                    <span class="text-muted">--</span>
                                @endif
                            </td>
                            <td>
                                <span class="badge bg-{{ $attendance->status_badge }}">
                                    {{ $attendance->status_label }}
                                </span>
                            </td>
                            <td>
                                @if($attendance->notes)
                                    <span title="{{ $attendance->notes }}">
                                        {{ Str::limit($attendance->notes, 30) }}
                                    </span>
                                @else
                                    <span class="text-muted">--</span>
                                @endif
                            </td>
                            <td>
                                <div class="btn-group" role="group">
                                    <button class="btn btn-sm btn-outline-primary" title="عرض">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    <button class="btn btn-sm btn-outline-warning" title="تعديل">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center text-muted py-4">
                                <i class="fas fa-clock fa-3x mb-3"></i>
                                <br>
                                لا توجد سجلات حضور لهذا اليوم
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if($attendances->hasPages())
            <div class="d-flex justify-content-center mt-4">
                {{ $attendances->links() }}
            </div>
            @endif
        </div>
    </div>

    <!-- إحصائيات إضافية -->
    <div class="row mt-4">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h6 class="mb-0">
                        <i class="fas fa-chart-pie me-2"></i>
                        توزيع الحضور اليوم
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-3">
                            <h4 class="text-success">{{ $stats['present_today'] }}</h4>
                            <small>حاضر</small>
                        </div>
                        <div class="col-3">
                            <h4 class="text-danger">{{ $stats['absent_today'] }}</h4>
                            <small>غائب</small>
                        </div>
                        <div class="col-3">
                            <h4 class="text-warning">{{ $stats['late_today'] }}</h4>
                            <small>متأخر</small>
                        </div>
                        <div class="col-3">
                            <h4 class="text-info">{{ $stats['total_employees'] - $stats['present_today'] - $stats['absent_today'] - $stats['late_today'] }}</h4>
                            <small>لم يسجل</small>
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
                        <li><i class="fas fa-clock text-primary me-2"></i>ساعات العمل: 8:00 ص - 4:00 م</li>
                        <li><i class="fas fa-coffee text-warning me-2"></i>استراحة: 12:00 ظ - 1:00 م</li>
                        <li><i class="fas fa-calendar text-info me-2"></i>أيام العمل: الأحد - الخميس</li>
                        <li><i class="fas fa-exclamation-triangle text-danger me-2"></i>التأخير أكثر من 15 دقيقة يعتبر تأخير</li>
                    </ul>
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
</style>
@endpush
