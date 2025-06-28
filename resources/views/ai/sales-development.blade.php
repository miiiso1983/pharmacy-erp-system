@extends('layouts.app')

@section('title', 'تطوير المبيعات - الذكاء الاصطناعي - ' . __('messages.system_name'))

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">{{ __('navigation.dashboard') }}</a></li>
    <li class="breadcrumb-item"><a href="{{ route('ai.dashboard') }}">الذكاء الاصطناعي</a></li>
    <li class="breadcrumb-item active">تطوير المبيعات</li>
@endsection

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0">
                <i class="fas fa-rocket me-2 text-warning"></i>
                تطوير المبيعات
            </h1>
            <p class="text-muted">استراتيجيات ذكية لتطوير المبيعات وزيادة الإيرادات</p>
        </div>
        <div>
            <a href="{{ route('ai.dashboard') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left me-2"></i>
                العودة
            </a>
        </div>
    </div>

    <!-- مؤشرات الأداء الحالي -->
    <div class="row mb-4">
        <div class="col-lg-2 col-md-4 col-sm-6 mb-3">
            <div class="card bg-primary text-white">
                <div class="card-body text-center">
                    <h4 class="mb-1">${{ number_format($salesAnalysis['current_performance']['monthly_sales']) }}</h4>
                    <small>المبيعات الشهرية</small>
                </div>
            </div>
        </div>
        <div class="col-lg-2 col-md-4 col-sm-6 mb-3">
            <div class="card bg-warning text-white">
                <div class="card-body text-center">
                    <h4 class="mb-1">{{ $salesAnalysis['current_performance']['target_achievement'] }}%</h4>
                    <small>تحقيق الهدف</small>
                </div>
            </div>
        </div>
        <div class="col-lg-2 col-md-4 col-sm-6 mb-3">
            <div class="card bg-success text-white">
                <div class="card-body text-center">
                    <h4 class="mb-1">{{ $salesAnalysis['current_performance']['customer_acquisition'] }}</h4>
                    <small>عملاء جدد</small>
                </div>
            </div>
        </div>
        <div class="col-lg-2 col-md-4 col-sm-6 mb-3">
            <div class="card bg-info text-white">
                <div class="card-body text-center">
                    <h4 class="mb-1">{{ $salesAnalysis['current_performance']['customer_retention'] }}%</h4>
                    <small>الاحتفاظ بالعملاء</small>
                </div>
            </div>
        </div>
        <div class="col-lg-2 col-md-4 col-sm-6 mb-3">
            <div class="card bg-secondary text-white">
                <div class="card-body text-center">
                    <h4 class="mb-1">${{ number_format($salesAnalysis['current_performance']['average_deal_size']) }}</h4>
                    <small>متوسط الصفقة</small>
                </div>
            </div>
        </div>
        <div class="col-lg-2 col-md-4 col-sm-6 mb-3">
            <div class="card bg-dark text-white">
                <div class="card-body text-center">
                    <h4 class="mb-1">{{ $salesAnalysis['market_analysis']['market_share'] }}%</h4>
                    <small>حصة السوق</small>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- تحليل قنوات المبيعات -->
        <div class="col-lg-6 mb-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-chart-pie me-2"></i>
                        أداء قنوات المبيعات
                    </h5>
                </div>
                <div class="card-body">
                    @foreach($salesAnalysis['sales_channels'] as $channel)
                    <div class="mb-3">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <h6 class="mb-0">{{ $channel['channel'] }}</h6>
                            <span class="badge bg-{{ $channel['performance'] >= 80 ? 'success' : ($channel['performance'] >= 60 ? 'warning' : 'danger') }}">
                                {{ $channel['performance'] }}%
                            </span>
                        </div>
                        
                        <div class="row">
                            <div class="col-6">
                                <small class="text-muted">الأداء</small>
                                <div class="progress mb-1" style="height: 8px;">
                                    <div class="progress-bar bg-{{ $channel['performance'] >= 80 ? 'success' : ($channel['performance'] >= 60 ? 'warning' : 'danger') }}" 
                                         style="width: {{ $channel['performance'] }}%"></div>
                                </div>
                            </div>
                            <div class="col-6">
                                <small class="text-muted">المساهمة: {{ $channel['contribution'] }}%</small>
                                <div class="progress mb-1" style="height: 8px;">
                                    <div class="progress-bar bg-info" style="width: {{ $channel['contribution'] }}%"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @if(!$loop->last)<hr>@endif
                    @endforeach
                </div>
            </div>
        </div>

        <!-- تحليل السوق -->
        <div class="col-lg-6 mb-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-chart-line me-2"></i>
                        تحليل السوق
                    </h5>
                </div>
                <div class="card-body">
                    <div class="mb-4">
                        <h6 class="text-success">فرص النمو</h6>
                        @foreach($salesAnalysis['market_analysis']['growth_opportunities'] as $opportunity)
                        <span class="badge bg-success me-1 mb-1">{{ $opportunity }}</span>
                        @endforeach
                    </div>
                    
                    <div class="mb-4">
                        <h6 class="text-danger">التهديدات</h6>
                        @foreach($salesAnalysis['market_analysis']['threats'] as $threat)
                        <span class="badge bg-danger me-1 mb-1">{{ $threat }}</span>
                        @endforeach
                    </div>
                    
                    <div class="alert alert-info">
                        <strong>تقييم المنافسة:</strong> {{ $salesAnalysis['market_analysis']['competitor_analysis'] }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- استراتيجيات التطوير -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-lightbulb me-2"></i>
                        استراتيجيات التطوير المقترحة
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        @foreach($salesPlan['strategies'] as $strategy)
                        <div class="col-lg-4 mb-4">
                            <div class="card border-primary h-100">
                                <div class="card-header bg-primary text-white">
                                    <h6 class="card-title mb-0">{{ $strategy['strategy'] }}</h6>
                                </div>
                                <div class="card-body">
                                    <p class="card-text">{{ $strategy['description'] }}</p>
                                    
                                    <div class="row text-center">
                                        <div class="col-6 mb-2">
                                            <small class="text-muted">الاستثمار</small>
                                            <div class="fw-bold text-primary">${{ number_format($strategy['investment']) }}</div>
                                        </div>
                                        <div class="col-6 mb-2">
                                            <small class="text-muted">العائد المتوقع</small>
                                            <div class="fw-bold text-success">{{ $strategy['expected_roi'] }}</div>
                                        </div>
                                    </div>
                                    
                                    <div class="text-center">
                                        <small class="text-muted">المدة الزمنية</small>
                                        <div class="fw-bold">{{ $strategy['timeline'] }}</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- خطة العمل -->
    <div class="row">
        <div class="col-lg-4 mb-4">
            <div class="card border-danger">
                <div class="card-header bg-danger text-white">
                    <h6 class="card-title mb-0">
                        <i class="fas fa-bolt me-2"></i>
                        إجراءات فورية
                    </h6>
                </div>
                <div class="card-body">
                    <ul class="list-unstyled">
                        @foreach($salesPlan['action_plan']['immediate'] as $action)
                        <li class="mb-2">
                            <i class="fas fa-check-circle text-danger me-2"></i>
                            {{ $action }}
                        </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>

        <div class="col-lg-4 mb-4">
            <div class="card border-warning">
                <div class="card-header bg-warning text-white">
                    <h6 class="card-title mb-0">
                        <i class="fas fa-clock me-2"></i>
                        خطط قصيرة المدى
                    </h6>
                </div>
                <div class="card-body">
                    <ul class="list-unstyled">
                        @foreach($salesPlan['action_plan']['short_term'] as $action)
                        <li class="mb-2">
                            <i class="fas fa-check-circle text-warning me-2"></i>
                            {{ $action }}
                        </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>

        <div class="col-lg-4 mb-4">
            <div class="card border-success">
                <div class="card-header bg-success text-white">
                    <h6 class="card-title mb-0">
                        <i class="fas fa-flag me-2"></i>
                        أهداف طويلة المدى
                    </h6>
                </div>
                <div class="card-body">
                    <ul class="list-unstyled">
                        @foreach($salesPlan['action_plan']['long_term'] as $action)
                        <li class="mb-2">
                            <i class="fas fa-check-circle text-success me-2"></i>
                            {{ $action }}
                        </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <!-- رسوم بيانية للتحليل -->
    <div class="row">
        <div class="col-lg-6 mb-4">
            <div class="card">
                <div class="card-header">
                    <h6 class="card-title mb-0">
                        <i class="fas fa-chart-doughnut me-2"></i>
                        توزيع قنوات المبيعات
                    </h6>
                </div>
                <div class="card-body">
                    <canvas id="channelsChart" height="200"></canvas>
                </div>
            </div>
        </div>

        <div class="col-lg-6 mb-4">
            <div class="card">
                <div class="card-header">
                    <h6 class="card-title mb-0">
                        <i class="fas fa-chart-bar me-2"></i>
                        توزيع الاستثمار في الاستراتيجيات
                    </h6>
                </div>
                <div class="card-body">
                    <canvas id="investmentChart" height="200"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // رسم بياني لقنوات المبيعات
    const channelsCtx = document.getElementById('channelsChart').getContext('2d');
    const channelsData = @json(array_column($salesAnalysis['sales_channels'], 'contribution'));
    const channelsLabels = @json(array_column($salesAnalysis['sales_channels'], 'channel'));
    
    new Chart(channelsCtx, {
        type: 'doughnut',
        data: {
            labels: channelsLabels,
            datasets: [{
                data: channelsData,
                backgroundColor: [
                    '#007bff',
                    '#28a745',
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
                    position: 'bottom',
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            return context.label + ': ' + context.parsed + '%';
                        }
                    }
                }
            }
        }
    });

    // رسم بياني للاستثمار
    const investmentCtx = document.getElementById('investmentChart').getContext('2d');
    const investmentData = @json(array_column($salesPlan['strategies'], 'investment'));
    const investmentLabels = @json(array_column($salesPlan['strategies'], 'strategy'));
    
    new Chart(investmentCtx, {
        type: 'bar',
        data: {
            labels: investmentLabels,
            datasets: [{
                label: 'الاستثمار ($)',
                data: investmentData,
                backgroundColor: [
                    '#007bff',
                    '#28a745',
                    '#ffc107'
                ],
                borderWidth: 1
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
});
</script>
@endpush

@push('styles')
<style>
.card {
    box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
}

.progress {
    border-radius: 10px;
}

.badge {
    font-size: 0.75em;
}

.list-unstyled li {
    padding: 0.25rem 0;
}

.border-primary {
    border-color: #007bff !important;
}

.border-danger {
    border-color: #dc3545 !important;
}

.border-warning {
    border-color: #ffc107 !important;
}

.border-success {
    border-color: #28a745 !important;
}
</style>
@endpush
