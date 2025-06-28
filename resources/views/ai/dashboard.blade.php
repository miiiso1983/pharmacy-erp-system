@extends('layouts.app')

@section('title', 'الذكاء الاصطناعي - ' . __('messages.system_name'))

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">{{ __('navigation.dashboard') }}</a></li>
    <li class="breadcrumb-item active">الذكاء الاصطناعي</li>
@endsection

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0">
                <i class="fas fa-brain me-2 text-primary"></i>
                الذكاء الاصطناعي
            </h1>
            <p class="text-muted">تحليل ذكي للأعمال والتنبؤ بالمستقبل وتطوير الفريق والمبيعات</p>
        </div>
        <div>
            <a href="{{ route('ai.chat') }}" class="btn btn-primary">
                <i class="fas fa-comments me-2"></i>
                محادثة مع AI
            </a>
        </div>
    </div>

    <!-- إحصائيات سريعة -->
    <div class="row mb-4">
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card bg-gradient-primary text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h4 class="mb-1">{{ number_format($stats['total_sales']) }}</h4>
                            <small>إجمالي المبيعات</small>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-chart-line fa-2x opacity-75"></i>
                        </div>
                    </div>
                    <div class="mt-2">
                        <small><i class="fas fa-arrow-up me-1"></i>{{ $stats['monthly_growth'] }}% نمو شهري</small>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card bg-gradient-success text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h4 class="mb-1">{{ $stats['team_performance'] }}%</h4>
                            <small>أداء الفريق</small>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-users fa-2x opacity-75"></i>
                        </div>
                    </div>
                    <div class="mt-2">
                        <small><i class="fas fa-check me-1"></i>أداء ممتاز</small>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card bg-gradient-warning text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h4 class="mb-1">{{ $stats['sales_target_achievement'] }}%</h4>
                            <small>تحقيق الأهداف</small>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-target fa-2x opacity-75"></i>
                        </div>
                    </div>
                    <div class="mt-2">
                        <small><i class="fas fa-clock me-1"></i>قريب من الهدف</small>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card bg-gradient-info text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h4 class="mb-1">{{ $aiPredictions['sales_forecast']['confidence'] }}%</h4>
                            <small>دقة التنبؤ</small>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-brain fa-2x opacity-75"></i>
                        </div>
                    </div>
                    <div class="mt-2">
                        <small><i class="fas fa-robot me-1"></i>ذكاء اصطناعي</small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- الوحدات الرئيسية -->
    <div class="row mb-4">
        <div class="col-lg-4 mb-3">
            <div class="card h-100 border-0 shadow-sm">
                <div class="card-body text-center">
                    <div class="mb-3">
                        <i class="fas fa-chart-line fa-3x text-primary"></i>
                    </div>
                    <h5 class="card-title">التنبؤ بالمبيعات</h5>
                    <p class="card-text text-muted">تحليل البيانات والتنبؤ بالمبيعات المستقبلية باستخدام الذكاء الاصطناعي</p>
                    <a href="{{ route('ai.sales-forecasting') }}" class="btn btn-primary">
                        <i class="fas fa-arrow-right me-2"></i>
                        ابدأ التحليل
                    </a>
                </div>
            </div>
        </div>
        <div class="col-lg-4 mb-3">
            <div class="card h-100 border-0 shadow-sm">
                <div class="card-body text-center">
                    <div class="mb-3">
                        <i class="fas fa-users-cog fa-3x text-success"></i>
                    </div>
                    <h5 class="card-title">تطوير الفريق</h5>
                    <p class="card-text text-muted">تحليل أداء الفريق ووضع خطط التطوير والتدريب المناسبة</p>
                    <a href="{{ route('ai.team-development') }}" class="btn btn-success">
                        <i class="fas fa-arrow-right me-2"></i>
                        تحليل الفريق
                    </a>
                </div>
            </div>
        </div>
        <div class="col-lg-4 mb-3">
            <div class="card h-100 border-0 shadow-sm">
                <div class="card-body text-center">
                    <div class="mb-3">
                        <i class="fas fa-rocket fa-3x text-warning"></i>
                    </div>
                    <h5 class="card-title">تطوير المبيعات</h5>
                    <p class="card-text text-muted">استراتيجيات ذكية لتطوير المبيعات وزيادة الإيرادات</p>
                    <a href="{{ route('ai.sales-development') }}" class="btn btn-warning">
                        <i class="fas fa-arrow-right me-2"></i>
                        تطوير المبيعات
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- رسم بياني للمبيعات -->
        <div class="col-lg-8 mb-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-chart-area me-2"></i>
                        تحليل المبيعات والتنبؤات
                    </h5>
                </div>
                <div class="card-body">
                    <canvas id="salesChart" height="100"></canvas>
                </div>
            </div>
        </div>

        <!-- توقعات الذكاء الاصطناعي -->
        <div class="col-lg-4 mb-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-crystal-ball me-2"></i>
                        توقعات AI
                    </h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <h6 class="text-primary">المبيعات المتوقعة</h6>
                        <div class="d-flex justify-content-between">
                            <span>الشهر القادم:</span>
                            <strong>${{ number_format($aiPredictions['sales_forecast']['next_month']) }}</strong>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span>الربع القادم:</span>
                            <strong>${{ number_format($aiPredictions['sales_forecast']['next_quarter']) }}</strong>
                        </div>
                        <div class="progress mt-2" style="height: 5px;">
                            <div class="progress-bar bg-primary" style="width: {{ $aiPredictions['sales_forecast']['confidence'] }}%"></div>
                        </div>
                        <small class="text-muted">دقة التنبؤ: {{ $aiPredictions['sales_forecast']['confidence'] }}%</small>
                    </div>

                    <div class="mb-3">
                        <h6 class="text-success">رؤى الفريق</h6>
                        <div class="alert alert-success alert-sm">
                            <strong>الأفضل أداءً:</strong> {{ $aiPredictions['team_insights']['top_performer'] }}
                        </div>
                        <div class="alert alert-warning alert-sm">
                            <strong>يحتاج تطوير:</strong> {{ $aiPredictions['team_insights']['needs_improvement'] }}
                        </div>
                    </div>

                    <div class="mb-3">
                        <h6 class="text-info">اتجاهات السوق</h6>
                        <div class="mb-2">
                            <small class="text-success"><i class="fas fa-arrow-up me-1"></i>قطاعات نامية:</small>
                            <ul class="list-unstyled ms-3">
                                @foreach($aiPredictions['market_trends']['growth_sectors'] as $sector)
                                <li><small>• {{ $sector }}</small></li>
                                @endforeach
                            </ul>
                        </div>
                        <div class="mb-2">
                            <small class="text-warning"><i class="fas fa-lightbulb me-1"></i>فرص:</small>
                            <ul class="list-unstyled ms-3">
                                @foreach($aiPredictions['market_trends']['opportunities'] as $opportunity)
                                <li><small>• {{ $opportunity }}</small></li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- أداء الأقسام -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-building me-2"></i>
                        أداء الأقسام
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        @foreach($teamData['departments'] as $index => $department)
                        <div class="col-lg-3 col-md-6 mb-3">
                            <div class="text-center">
                                <h6>{{ $department }}</h6>
                                <div class="progress mb-2" style="height: 10px;">
                                    <div class="progress-bar bg-{{ $index % 2 == 0 ? 'primary' : 'success' }}" 
                                         style="width: {{ $teamData['performance'][$index] }}%"></div>
                                </div>
                                <div class="d-flex justify-content-between">
                                    <small>الأداء: {{ $teamData['performance'][$index] }}%</small>
                                    <small>الكفاءة: {{ $teamData['efficiency'][$index] }}%</small>
                                </div>
                            </div>
                        </div>
                        @endforeach
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
document.addEventListener('DOMContentLoaded', function() {
    // رسم بياني للمبيعات
    const ctx = document.getElementById('salesChart').getContext('2d');
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: @json($salesData['labels']),
            datasets: [{
                label: 'المبيعات الفعلية',
                data: @json($salesData['actual']),
                borderColor: '#007bff',
                backgroundColor: 'rgba(0, 123, 255, 0.1)',
                tension: 0.4,
                fill: true
            }, {
                label: 'التنبؤات',
                data: @json($salesData['predicted']),
                borderColor: '#28a745',
                backgroundColor: 'rgba(40, 167, 69, 0.1)',
                borderDash: [5, 5],
                tension: 0.4,
                fill: false
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'top',
                },
                title: {
                    display: true,
                    text: 'تحليل المبيعات والتنبؤات'
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
.bg-gradient-primary {
    background: linear-gradient(45deg, #007bff, #0056b3);
}

.bg-gradient-success {
    background: linear-gradient(45deg, #28a745, #1e7e34);
}

.bg-gradient-warning {
    background: linear-gradient(45deg, #ffc107, #e0a800);
}

.bg-gradient-info {
    background: linear-gradient(45deg, #17a2b8, #138496);
}

.card {
    transition: transform 0.2s;
}

.card:hover {
    transform: translateY(-2px);
}

.alert-sm {
    padding: 0.5rem;
    margin-bottom: 0.5rem;
    font-size: 0.875rem;
}

.opacity-75 {
    opacity: 0.75;
}

.shadow-sm {
    box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075) !important;
}
</style>
@endpush
