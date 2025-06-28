@extends('layouts.app')

@section('title', 'إدارة الأقسام')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">الرئيسية</a></li>
    <li class="breadcrumb-item"><a href="{{ route('hr.index') }}">الموارد البشرية</a></li>
    <li class="breadcrumb-item active">الأقسام</li>
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
                            <h4>{{ number_format($stats['total_departments']) }}</h4>
                            <p class="mb-0">إجمالي الأقسام</p>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-building fa-2x"></i>
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
                            <h4>{{ number_format($stats['active_departments']) }}</h4>
                            <p class="mb-0">الأقسام النشطة</p>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-check-circle fa-2x"></i>
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
        <div class="col-md-3">
            <div class="card bg-warning text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h4>{{ number_format($stats['total_budget']) }} د.ع</h4>
                            <p class="mb-0">إجمالي الميزانيات</p>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-dollar-sign fa-2x"></i>
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
                        <i class="fas fa-building me-2"></i>
                        قائمة الأقسام
                    </h5>
                </div>
                <div class="col-md-6 text-end">
                    <button class="btn btn-primary me-2">
                        <i class="fas fa-plus me-2"></i>
                        إضافة قسم جديد
                    </button>
                    <button class="btn btn-success">
                        <i class="fas fa-chart-bar me-2"></i>
                        تقرير الأقسام
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- بطاقات الأقسام -->
    <div class="row">
        @forelse($departments as $department)
        <div class="col-md-6 col-lg-4 mb-4">
            <div class="card h-100">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h6 class="mb-0">
                        <i class="fas fa-building me-2"></i>
                        {{ $department->name }}
                    </h6>
                    <span class="badge bg-{{ $department->status_badge }}">
                        {{ $department->status_label }}
                    </span>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-6">
                            <small class="text-muted">الكود:</small>
                            <div><strong>{{ $department->code }}</strong></div>
                        </div>
                        <div class="col-6">
                            <small class="text-muted">عدد الموظفين:</small>
                            <div><strong>{{ $department->employees_count }}</strong></div>
                        </div>
                    </div>
                    
                    @if($department->description)
                    <div class="mb-3">
                        <small class="text-muted">الوصف:</small>
                        <p class="mb-0">{{ Str::limit($department->description, 100) }}</p>
                    </div>
                    @endif

                    <div class="row mb-3">
                        <div class="col-6">
                            <small class="text-muted">الموقع:</small>
                            <div>{{ $department->location ?? 'غير محدد' }}</div>
                        </div>
                        <div class="col-6">
                            <small class="text-muted">الميزانية:</small>
                            <div><strong>{{ number_format($department->budget) }} د.ع</strong></div>
                        </div>
                    </div>

                    @if($department->manager)
                    <div class="mb-3">
                        <small class="text-muted">المدير:</small>
                        <div>{{ $department->manager->full_name }}</div>
                    </div>
                    @endif

                    @if($department->phone || $department->email)
                    <div class="mb-3">
                        @if($department->phone)
                        <div><i class="fas fa-phone me-2"></i>{{ $department->phone }}</div>
                        @endif
                        @if($department->email)
                        <div><i class="fas fa-envelope me-2"></i>{{ $department->email }}</div>
                        @endif
                    </div>
                    @endif
                </div>
                <div class="card-footer">
                    <div class="btn-group w-100" role="group">
                        <button class="btn btn-outline-primary btn-sm" title="عرض">
                            <i class="fas fa-eye"></i>
                        </button>
                        <button class="btn btn-outline-warning btn-sm" title="تعديل">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button class="btn btn-outline-info btn-sm" title="الموظفين">
                            <i class="fas fa-users"></i>
                        </button>
                        <button class="btn btn-outline-danger btn-sm" title="حذف">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
        @empty
        <div class="col-12">
            <div class="card">
                <div class="card-body text-center py-5">
                    <i class="fas fa-building fa-3x text-muted mb-3"></i>
                    <h5 class="text-muted">لا توجد أقسام مسجلة</h5>
                    <p class="text-muted">ابدأ بإضافة قسم جديد لتنظيم الموظفين</p>
                    <button class="btn btn-primary">
                        <i class="fas fa-plus me-2"></i>
                        إضافة قسم جديد
                    </button>
                </div>
            </div>
        </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if($departments->hasPages())
    <div class="d-flex justify-content-center mt-4">
        {{ $departments->links() }}
    </div>
    @endif
</div>
@endsection
