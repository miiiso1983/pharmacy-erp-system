@extends('layouts.app')

@section('title', 'النظام المالي - ' . __('messages.system_name'))

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">{{ __('navigation.dashboard') }}</a></li>
    <li class="breadcrumb-item active">النظام المالي</li>
@endsection

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0">
                <i class="fas fa-chart-line me-2"></i>
                النظام المالي
            </h1>
            <p class="text-muted">إدارة شاملة للحسابات والقيود المحاسبية والتقارير المالية</p>
        </div>
        <div>
            @if($stats['current_period'])
                <span class="badge bg-primary fs-6">
                    الفترة الحالية: {{ $stats['current_period']->period_name }}
                </span>
            @else
                <span class="badge bg-warning fs-6">
                    لا توجد فترة مالية نشطة
                </span>
            @endif
        </div>
    </div>

    <!-- إحصائيات سريعة -->
    <div class="row mb-4">
        <div class="col-xl-2 col-md-4 mb-3">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                إجمالي الحسابات
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $stats['total_accounts'] ?? 0 }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-list fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-2 col-md-4 mb-3">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                القيود المرحلة
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $stats['posted_entries'] ?? 0 }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-check-circle fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-2 col-md-4 mb-3">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                قيود مسودة
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $stats['draft_entries'] ?? 0 }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-edit fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-2 col-md-4 mb-3">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                إجمالي الأصول
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ number_format($stats['total_assets'] ?? 0) }} د.ع
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-building fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-2 col-md-4 mb-3">
            <div class="card border-left-danger shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">
                                إجمالي الخصوم
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ number_format($stats['total_liabilities'] ?? 0) }} د.ع
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-credit-card fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-2 col-md-4 mb-3">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                حقوق الملكية
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ number_format($stats['total_equity'] ?? 0) }} د.ع
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-chart-pie fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- الوصول السريع -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-tachometer-alt me-2"></i>
                        الوصول السريع
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3 mb-3">
                            <a href="{{ route('finance.accounts.index') }}" class="btn btn-outline-primary w-100 h-100 d-flex flex-column justify-content-center">
                                <i class="fas fa-list fa-2x mb-2"></i>
                                <span>دليل الحسابات</span>
                                <small class="text-muted">إدارة الحسابات المالية</small>
                            </a>
                        </div>
                        <div class="col-md-3 mb-3">
                            <a href="{{ route('finance.journal-entries.index') }}" class="btn btn-outline-success w-100 h-100 d-flex flex-column justify-content-center">
                                <i class="fas fa-book fa-2x mb-2"></i>
                                <span>القيود المحاسبية</span>
                                <small class="text-muted">إدارة القيود اليومية</small>
                            </a>
                        </div>
                        <div class="col-md-3 mb-3">
                            <a href="{{ route('finance.reports.index') }}" class="btn btn-outline-info w-100 h-100 d-flex flex-column justify-content-center">
                                <i class="fas fa-chart-bar fa-2x mb-2"></i>
                                <span>التقارير المالية</span>
                                <small class="text-muted">ميزان المراجعة والقوائم</small>
                            </a>
                        </div>
                        <div class="col-md-3 mb-3">
                            <a href="{{ route('finance.periods.index') }}" class="btn btn-outline-warning w-100 h-100 d-flex flex-column justify-content-center">
                                <i class="fas fa-calendar fa-2x mb-2"></i>
                                <span>الفترات المالية</span>
                                <small class="text-muted">إدارة السنوات المالية</small>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- القيود الأخيرة -->
        <div class="col-lg-8 mb-4">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-clock me-2"></i>
                        القيود الأخيرة
                    </h5>
                    <a href="{{ route('finance.journal-entries.index') }}" class="btn btn-sm btn-outline-primary">
                        عرض الكل
                    </a>
                </div>
                <div class="card-body">
                    @if($recentEntries->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>رقم القيد</th>
                                        <th>التاريخ</th>
                                        <th>الوصف</th>
                                        <th>المبلغ</th>
                                        <th>الحالة</th>
                                        <th>المستخدم</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($recentEntries as $entry)
                                    <tr>
                                        <td>
                                            <a href="{{ route('finance.journal-entries.show', $entry->id) }}" class="text-decoration-none">
                                                {{ $entry->entry_number }}
                                            </a>
                                        </td>
                                        <td>{{ $entry->entry_date->format('d/m/Y') }}</td>
                                        <td>{{ Str::limit($entry->description, 50) }}</td>
                                        <td>{{ number_format($entry->total_amount) }} د.ع</td>
                                        <td>
                                            @switch($entry->status)
                                                @case('posted')
                                                    <span class="badge bg-success">مرحل</span>
                                                    @break
                                                @case('draft')
                                                    <span class="badge bg-warning">مسودة</span>
                                                    @break
                                                @case('cancelled')
                                                    <span class="badge bg-danger">ملغي</span>
                                                    @break
                                            @endswitch
                                        </td>
                                        <td>{{ $entry->creator->name ?? 'غير محدد' }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="fas fa-book fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">لا توجد قيود محاسبية</h5>
                            <p class="text-muted">ابدأ بإنشاء أول قيد محاسبي</p>
                            <a href="{{ route('finance.journal-entries.create') }}" class="btn btn-primary">
                                <i class="fas fa-plus me-2"></i>
                                إنشاء قيد جديد
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- الحسابات الأكثر نشاطاً -->
        <div class="col-lg-4 mb-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-fire me-2"></i>
                        الحسابات الأكثر نشاطاً
                    </h5>
                </div>
                <div class="card-body">
                    @if($activeAccounts->count() > 0)
                        @foreach($activeAccounts as $account)
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <div>
                                <h6 class="mb-0">{{ $account->account_name }}</h6>
                                <small class="text-muted">{{ $account->account_code }}</small>
                            </div>
                            <div class="text-end">
                                <span class="badge bg-primary">{{ $account->journal_entry_details_count }}</span>
                                <br>
                                <small class="text-muted">حركة</small>
                            </div>
                        </div>
                        @endforeach
                    @else
                        <div class="text-center py-4">
                            <i class="fas fa-chart-line fa-3x text-muted mb-3"></i>
                            <h6 class="text-muted">لا توجد حركات</h6>
                        </div>
                    @endif
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

.border-left-danger {
    border-left: 0.25rem solid #e74a3b !important;
}

.table th {
    background-color: #f8f9fa;
    border-top: none;
}

.btn.h-100 {
    min-height: 120px;
}
</style>
@endpush
