@extends('layouts.app')

@section('title', 'لوحة التحكم الإدارية')

@section('breadcrumb')
    <li class="breadcrumb-item active">لوحة التحكم الإدارية</li>
@endsection

@section('content')
<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>
                <i class="fas fa-tachometer-alt me-2 text-primary"></i>
                لوحة التحكم الإدارية
            </h2>
            <div class="btn-group">
                <a href="{{ route('admin.users.create') }}" class="btn btn-success">
                    <i class="fas fa-user-plus me-2"></i>
                    إضافة مستخدم
                </a>
                <a href="{{ route('admin.licenses.create') }}" class="btn btn-primary">
                    <i class="fas fa-key me-2"></i>
                    إضافة ترخيص
                </a>
            </div>
        </div>
    </div>
</div>

<!-- إحصائيات سريعة -->
<div class="row mb-4">
    <!-- إحصائيات المستخدمين -->
    <div class="col-lg-3 col-md-6 mb-3">
        <div class="card bg-primary text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h4 class="mb-0">{{ $stats['total_users'] }}</h4>
                        <p class="mb-0">إجمالي المستخدمين</p>
                        <small>نشط: {{ $stats['active_users'] }}</small>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-users fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- إحصائيات التراخيص -->
    <div class="col-lg-3 col-md-6 mb-3">
        <div class="card bg-success text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h4 class="mb-0">{{ $stats['total_licenses'] }}</h4>
                        <p class="mb-0">إجمالي التراخيص</p>
                        <small>نشط: {{ $stats['active_licenses'] }}</small>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-key fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- إحصائيات المخازن -->
    <div class="col-lg-3 col-md-6 mb-3">
        <div class="card bg-info text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h4 class="mb-0">{{ $stats['total_warehouses'] }}</h4>
                        <p class="mb-0">إجمالي المخازن</p>
                        <small>نشط: {{ $stats['active_warehouses'] }}</small>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-warehouse fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- تنبيهات -->
    <div class="col-lg-3 col-md-6 mb-3">
        <div class="card bg-warning text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h4 class="mb-0">{{ $stats['near_expiry_licenses'] + $stats['expired_licenses'] }}</h4>
                        <p class="mb-0">تنبيهات</p>
                        <small>منتهية: {{ $stats['expired_licenses'] }}</small>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-exclamation-triangle fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- الرسوم البيانية والتحليلات -->
<div class="row mb-4">
    <!-- توزيع المستخدمين حسب الدور -->
    <div class="col-lg-6 mb-3">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-chart-pie me-2"></i>
                    توزيع المستخدمين حسب الدور
                </h5>
            </div>
            <div class="card-body">
                <canvas id="userRolesChart" width="400" height="200"></canvas>
            </div>
        </div>
    </div>

    <!-- إحصائيات شهرية -->
    <div class="col-lg-6 mb-3">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-chart-line me-2"></i>
                    نمو المستخدمين الشهري
                </h5>
            </div>
            <div class="card-body">
                <canvas id="monthlyGrowthChart" width="400" height="200"></canvas>
            </div>
        </div>
    </div>
</div>

<!-- التنبيهات والإشعارات -->
@if($expiredLicenses->count() > 0 || $nearExpiryLicenses->count() > 0)
<div class="row mb-4">
    <div class="col-12">
        <div class="card border-warning">
            <div class="card-header bg-warning text-dark">
                <h5 class="mb-0">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    تنبيهات مهمة
                </h5>
            </div>
            <div class="card-body">
                @if($expiredLicenses->count() > 0)
                    <div class="alert alert-danger">
                        <h6><i class="fas fa-times-circle me-2"></i>تراخيص منتهية الصلاحية ({{ $expiredLicenses->count() }})</h6>
                        <ul class="mb-0">
                            @foreach($expiredLicenses->take(5) as $license)
                                <li>{{ $license->client_name }} - انتهت في {{ $license->end_date->format('Y-m-d') }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                @if($nearExpiryLicenses->count() > 0)
                    <div class="alert alert-warning">
                        <h6><i class="fas fa-clock me-2"></i>تراخيص قريبة الانتهاء ({{ $nearExpiryLicenses->count() }})</h6>
                        <ul class="mb-0">
                            @foreach($nearExpiryLicenses->take(5) as $license)
                                <li>{{ $license->client_name }} - تنتهي في {{ $license->end_date->format('Y-m-d') }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endif

<!-- المستخدمين الجدد -->
<div class="row">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">
                    <i class="fas fa-user-clock me-2"></i>
                    المستخدمين الجدد
                </h5>
                <a href="{{ route('admin.users') }}" class="btn btn-outline-primary btn-sm">
                    عرض الكل
                </a>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>الاسم</th>
                                <th>البريد الإلكتروني</th>
                                <th>الدور</th>
                                <th>تاريخ الإنشاء</th>
                                <th>الحالة</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentUsers as $user)
                                <tr>
                                    <td>{{ $user->name }}</td>
                                    <td>{{ $user->email }}</td>
                                    <td>
                                        <span class="badge bg-secondary">
                                            {{ $user->user_role ?? 'غير محدد' }}
                                        </span>
                                    </td>
                                    <td>{{ $user->created_at->format('Y-m-d') }}</td>
                                    <td>
                                        @if($user->is_account_active ?? true)
                                            <span class="badge bg-success">نشط</span>
                                        @else
                                            <span class="badge bg-danger">غير نشط</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center text-muted">
                                        لا توجد مستخدمين جدد
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- روابط سريعة -->
    <div class="col-lg-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-link me-2"></i>
                    روابط سريعة
                </h5>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <a href="{{ route('admin.users') }}" class="btn btn-outline-primary">
                        <i class="fas fa-users me-2"></i>
                        إدارة المستخدمين
                    </a>
                    <a href="{{ route('admin.licenses') }}" class="btn btn-outline-success">
                        <i class="fas fa-key me-2"></i>
                        إدارة التراخيص
                    </a>
                    <a href="{{ route('admin.warehouses') }}" class="btn btn-outline-info">
                        <i class="fas fa-warehouse me-2"></i>
                        إدارة المخازن
                    </a>
                    <a href="{{ route('admin.reports') }}" class="btn btn-outline-warning">
                        <i class="fas fa-chart-bar me-2"></i>
                        التقارير
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// رسم بياني لتوزيع المستخدمين حسب الدور
const userRolesCtx = document.getElementById('userRolesChart').getContext('2d');
const userRolesChart = new Chart(userRolesCtx, {
    type: 'doughnut',
    data: {
        labels: {!! json_encode(array_keys($stats['users_by_role']->toArray())) !!},
        datasets: [{
            data: {!! json_encode(array_values($stats['users_by_role']->toArray())) !!},
            backgroundColor: [
                '#FF6384',
                '#36A2EB',
                '#FFCE56',
                '#4BC0C0',
                '#9966FF',
                '#FF9F40',
                '#FF6384'
            ]
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                position: 'bottom'
            }
        }
    }
});

// رسم بياني للنمو الشهري (بيانات تجريبية)
const monthlyGrowthCtx = document.getElementById('monthlyGrowthChart').getContext('2d');
const monthlyGrowthChart = new Chart(monthlyGrowthCtx, {
    type: 'line',
    data: {
        labels: ['يناير', 'فبراير', 'مارس', 'أبريل', 'مايو', 'يونيو'],
        datasets: [{
            label: 'مستخدمين جدد',
            data: [12, 19, 3, 5, 2, 3],
            borderColor: '#36A2EB',
            backgroundColor: 'rgba(54, 162, 235, 0.1)',
            tension: 0.4
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        scales: {
            y: {
                beginAtZero: true
            }
        }
    }
});
</script>
@endpush
