@extends('layouts.app')

@section('title', 'إدارة الموظفين')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">الرئيسية</a></li>
    <li class="breadcrumb-item"><a href="{{ route('hr.index') }}">الموارد البشرية</a></li>
    <li class="breadcrumb-item active">الموظفين</li>
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
            <div class="card bg-warning text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h4>{{ number_format($stats['inactive_employees']) }}</h4>
                            <p class="mb-0">غير النشطين</p>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-user-times fa-2x"></i>
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
                            <h4>{{ number_format($stats['terminated_employees']) }}</h4>
                            <p class="mb-0">منتهي الخدمة</p>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-user-slash fa-2x"></i>
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
                        <i class="fas fa-users me-2"></i>
                        قائمة الموظفين
                    </h5>
                </div>
                <div class="col-md-6 text-end">
                    <button class="btn btn-primary me-2">
                        <i class="fas fa-plus me-2"></i>
                        إضافة موظف جديد
                    </button>
                    <button class="btn btn-success me-2">
                        <i class="fas fa-file-excel me-2"></i>
                        تصدير Excel
                    </button>
                    <button class="btn btn-info">
                        <i class="fas fa-file-pdf me-2"></i>
                        تصدير PDF
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- جدول الموظفين -->
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>رقم الموظف</th>
                            <th>الاسم</th>
                            <th>القسم</th>
                            <th>المنصب</th>
                            <th>تاريخ التوظيف</th>
                            <th>الراتب</th>
                            <th>الحالة</th>
                            <th>الإجراءات</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($employees as $employee)
                        <tr>
                            <td>
                                <strong>{{ $employee->employee_id }}</strong>
                            </td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="avatar me-3">
                                        <i class="fas fa-user-circle fa-2x text-primary"></i>
                                    </div>
                                    <div>
                                        <strong>{{ $employee->full_name }}</strong>
                                        <br>
                                        <small class="text-muted">{{ $employee->email }}</small>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span class="badge bg-info">{{ $employee->department->name ?? 'غير محدد' }}</span>
                            </td>
                            <td>{{ $employee->position }}</td>
                            <td>{{ $employee->hire_date->format('Y-m-d') }}</td>
                            <td>
                                <strong>{{ number_format($employee->total_salary) }} د.ع</strong>
                            </td>
                            <td>
                                <span class="badge bg-{{ $employee->status_badge }}">
                                    {{ $employee->status_label }}
                                </span>
                            </td>
                            <td>
                                <div class="btn-group" role="group">
                                    <button class="btn btn-sm btn-outline-primary" title="عرض">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    <button class="btn btn-sm btn-outline-warning" title="تعديل">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button class="btn btn-sm btn-outline-danger" title="حذف">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center text-muted py-4">
                                <i class="fas fa-users fa-3x mb-3"></i>
                                <br>
                                لا يوجد موظفون مسجلون
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if($employees->hasPages())
            <div class="d-flex justify-content-center mt-4">
                {{ $employees->links() }}
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
