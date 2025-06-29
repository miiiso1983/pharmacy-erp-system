@extends('super-admin.layout')

@section('title', 'التقارير والإحصائيات')

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <h1 class="h2 mb-0">
            <i class="fas fa-chart-bar me-3"></i>
            التقارير والإحصائيات
        </h1>
        <p class="text-muted">تقارير شاملة عن أداء النظام</p>
    </div>
</div>

<!-- إحصائيات التراخيص حسب النوع -->
<div class="row mb-4">
    <div class="col-lg-6">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-pie-chart me-2"></i>
                    التراخيص حسب النوع
                </h5>
            </div>
            <div class="card-body">
                <canvas id="licenseTypeChart" width="400" height="200"></canvas>
            </div>
        </div>
    </div>
    <div class="col-lg-6">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-chart-line me-2"></i>
                    الإيرادات الشهرية
                </h5>
            </div>
            <div class="card-body">
                <canvas id="revenueChart" width="400" height="200"></canvas>
            </div>
        </div>
    </div>
</div>

<!-- إحصائيات مفصلة -->
<div class="row mb-4">
    @foreach($licenseStats['by_type'] as $type)
        <div class="col-lg-3 col-md-6">
            <div class="stat-card">
                <div class="stat-icon 
                    @if($type->license_type === 'premium') primary
                    @elseif($type->license_type === 'full') success
                    @elseif($type->license_type === 'basic') secondary
                    @else info
                    @endif
                ">
                    <i class="fas fa-certificate"></i>
                </div>
                <div class="stat-number 
                    @if($type->license_type === 'premium') text-primary
                    @elseif($type->license_type === 'full') text-success
                    @elseif($type->license_type === 'basic') text-secondary
                    @else text-info
                    @endif
                ">{{ number_format($type->count) }}</div>
                <div class="stat-label">
                    @if($type->license_type === 'premium') تراخيص مميزة
                    @elseif($type->license_type === 'full') تراخيص كاملة
                    @elseif($type->license_type === 'basic') تراخيص أساسية
                    @else {{ $type->license_type }}
                    @endif
                </div>
            </div>
        </div>
    @endforeach
</div>

<!-- تقارير مفصلة -->
<div class="row">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-calendar-alt me-2"></i>
                    الإيرادات الشهرية (آخر 12 شهر)
                </h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>الشهر</th>
                                <th>عدد التراخيص</th>
                                <th>الإيرادات</th>
                                <th>متوسط قيمة الترخيص</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($licenseStats['monthly_revenue'] as $month)
                                <tr>
                                    <td>{{ $month->year }}/{{ str_pad($month->month, 2, '0', STR_PAD_LEFT) }}</td>
                                    <td>{{ number_format($month->count ?? 0) }}</td>
                                    <td>${{ number_format($month->revenue, 2) }}</td>
                                    <td>${{ $month->count > 0 ? number_format($month->revenue / $month->count, 2) : '0.00' }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-lg-4">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-info-circle me-2"></i>
                    ملخص سريع
                </h5>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <small class="text-muted">إجمالي الإيرادات</small>
                    <h4 class="text-success">${{ number_format($licenseStats['monthly_revenue']->sum('revenue'), 2) }}</h4>
                </div>
                
                <div class="mb-3">
                    <small class="text-muted">متوسط الإيرادات الشهرية</small>
                    <h5 class="text-primary">${{ number_format($licenseStats['monthly_revenue']->avg('revenue'), 2) }}</h5>
                </div>
                
                <div class="mb-3">
                    <small class="text-muted">أعلى إيرادات شهرية</small>
                    <h5 class="text-warning">${{ number_format($licenseStats['monthly_revenue']->max('revenue'), 2) }}</h5>
                </div>
                
                <div class="mb-3">
                    <small class="text-muted">النمو الشهري</small>
                    @php
                        $current = $licenseStats['monthly_revenue']->first()->revenue ?? 0;
                        $previous = $licenseStats['monthly_revenue']->skip(1)->first()->revenue ?? 0;
                        $growth = $previous > 0 ? (($current - $previous) / $previous) * 100 : 0;
                    @endphp
                    <h5 class="@if($growth >= 0) text-success @else text-danger @endif">
                        @if($growth >= 0) +@endif{{ number_format($growth, 1) }}%
                    </h5>
                </div>
            </div>
        </div>
        
        <!-- حالة التراخيص -->
        <div class="card mt-4">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-chart-donut me-2"></i>
                    حالة التراخيص
                </h5>
            </div>
            <div class="card-body">
                @foreach($licenseStats['by_status'] as $status)
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span>
                            @if($status->is_active) 
                                <i class="fas fa-circle text-success me-2"></i>نشطة
                            @else 
                                <i class="fas fa-circle text-danger me-2"></i>غير نشطة
                            @endif
                        </span>
                        <span class="fw-bold">{{ number_format($status->count) }}</span>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>

<!-- أزرار التصدير -->
<div class="row mt-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-download me-2"></i>
                    تصدير التقارير
                </h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3 mb-2">
                        <button class="btn btn-success w-100" onclick="exportReport('excel')">
                            <i class="fas fa-file-excel me-2"></i>
                            تصدير Excel
                        </button>
                    </div>
                    <div class="col-md-3 mb-2">
                        <button class="btn btn-danger w-100" onclick="exportReport('pdf')">
                            <i class="fas fa-file-pdf me-2"></i>
                            تصدير PDF
                        </button>
                    </div>
                    <div class="col-md-3 mb-2">
                        <button class="btn btn-info w-100" onclick="exportReport('csv')">
                            <i class="fas fa-file-csv me-2"></i>
                            تصدير CSV
                        </button>
                    </div>
                    <div class="col-md-3 mb-2">
                        <button class="btn btn-secondary w-100" onclick="window.print()">
                            <i class="fas fa-print me-2"></i>
                            طباعة
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// رسم بياني للتراخيص حسب النوع
const licenseTypeCtx = document.getElementById('licenseTypeChart').getContext('2d');
const licenseTypeChart = new Chart(licenseTypeCtx, {
    type: 'doughnut',
    data: {
        labels: [
            @foreach($licenseStats['by_type'] as $type)
                '{{ $type->license_type }}',
            @endforeach
        ],
        datasets: [{
            data: [
                @foreach($licenseStats['by_type'] as $type)
                    {{ $type->count }},
                @endforeach
            ],
            backgroundColor: [
                '#667eea',
                '#28a745',
                '#6c757d',
                '#17a2b8',
                '#ffc107'
            ],
            borderWidth: 2,
            borderColor: '#fff'
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

// رسم بياني للإيرادات الشهرية
const revenueCtx = document.getElementById('revenueChart').getContext('2d');
const revenueChart = new Chart(revenueCtx, {
    type: 'line',
    data: {
        labels: [
            @foreach($licenseStats['monthly_revenue']->reverse() as $month)
                '{{ $month->year }}/{{ str_pad($month->month, 2, '0', STR_PAD_LEFT) }}',
            @endforeach
        ],
        datasets: [{
            label: 'الإيرادات ($)',
            data: [
                @foreach($licenseStats['monthly_revenue']->reverse() as $month)
                    {{ $month->revenue }},
                @endforeach
            ],
            borderColor: '#667eea',
            backgroundColor: 'rgba(102, 126, 234, 0.1)',
            borderWidth: 3,
            fill: true,
            tension: 0.4
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                display: false
            }
        },
        scales: {
            y: {
                beginAtZero: true,
                ticks: {
                    callback: function(value) {
                        return '$' + value.toLocaleString();
                    }
                }
            }
        }
    }
});

// دوال التصدير
function exportReport(format) {
    switch(format) {
        case 'excel':
            showSuccess('سيتم تصدير التقرير بصيغة Excel');
            break;
        case 'pdf':
            showSuccess('سيتم تصدير التقرير بصيغة PDF');
            break;
        case 'csv':
            showSuccess('سيتم تصدير التقرير بصيغة CSV');
            break;
        default:
            showError('صيغة غير مدعومة');
    }
}
</script>
@endpush
