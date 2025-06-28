@extends('layouts.app')

@section('title', 'تطوير الفريق - الذكاء الاصطناعي - ' . __('messages.system_name'))

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">{{ __('navigation.dashboard') }}</a></li>
    <li class="breadcrumb-item"><a href="{{ route('ai.dashboard') }}">الذكاء الاصطناعي</a></li>
    <li class="breadcrumb-item active">تطوير الفريق</li>
@endsection

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0">
                <i class="fas fa-users-cog me-2 text-success"></i>
                تطوير الفريق
            </h1>
            <p class="text-muted">تحليل أداء الفريق ووضع خطط التطوير والتدريب</p>
        </div>
        <div>
            <a href="{{ route('ai.dashboard') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left me-2"></i>
                العودة
            </a>
        </div>
    </div>

    <!-- نظرة عامة على الأداء -->
    <div class="row mb-4">
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card bg-success text-white">
                <div class="card-body text-center">
                    <h3 class="mb-1">{{ $teamAnalysis['overall_score'] }}%</h3>
                    <small>الأداء العام</small>
                    <div class="mt-2">
                        <i class="fas fa-chart-line fa-2x opacity-75"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card bg-primary text-white">
                <div class="card-body text-center">
                    <h3 class="mb-1">{{ count($teamAnalysis['departments']) }}</h3>
                    <small>الأقسام</small>
                    <div class="mt-2">
                        <i class="fas fa-building fa-2x opacity-75"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card bg-info text-white">
                <div class="card-body text-center">
                    <h3 class="mb-1">{{ array_sum(array_column($teamAnalysis['departments'], 'employees_count')) }}</h3>
                    <small>إجمالي الموظفين</small>
                    <div class="mt-2">
                        <i class="fas fa-users fa-2x opacity-75"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card bg-warning text-white">
                <div class="card-body text-center">
                    <h3 class="mb-1">{{ array_sum(array_column($teamAnalysis['departments'], 'top_performers')) }}</h3>
                    <small>المتميزون</small>
                    <div class="mt-2">
                        <i class="fas fa-star fa-2x opacity-75"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- تحليل الأقسام -->
        <div class="col-lg-8 mb-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-chart-bar me-2"></i>
                        تحليل أداء الأقسام
                    </h5>
                </div>
                <div class="card-body">
                    @foreach($teamAnalysis['departments'] as $department)
                    <div class="department-analysis mb-4">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <h6 class="mb-0">{{ $department['name'] }}</h6>
                            <span class="badge bg-{{ $department['score'] >= 85 ? 'success' : ($department['score'] >= 70 ? 'warning' : 'danger') }} fs-6">
                                {{ $department['score'] }}%
                            </span>
                        </div>
                        
                        <div class="progress mb-3" style="height: 10px;">
                            <div class="progress-bar bg-{{ $department['score'] >= 85 ? 'success' : ($department['score'] >= 70 ? 'warning' : 'danger') }}" 
                                 style="width: {{ $department['score'] }}%"></div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-4">
                                <small class="text-muted">عدد الموظفين:</small>
                                <div class="fw-bold">{{ $department['employees_count'] }}</div>
                            </div>
                            <div class="col-md-4">
                                <small class="text-muted">المتميزون:</small>
                                <div class="fw-bold text-success">{{ $department['top_performers'] }}</div>
                            </div>
                            <div class="col-md-4">
                                <small class="text-muted">معدل التميز:</small>
                                <div class="fw-bold">{{ round(($department['top_performers'] / $department['employees_count']) * 100) }}%</div>
                            </div>
                        </div>
                        
                        <div class="row mt-2">
                            <div class="col-md-6">
                                <small class="text-success"><i class="fas fa-plus-circle me-1"></i>نقاط القوة:</small>
                                <ul class="list-unstyled ms-3">
                                    @foreach($department['strengths'] as $strength)
                                    <li><small>• {{ $strength }}</small></li>
                                    @endforeach
                                </ul>
                            </div>
                            <div class="col-md-6">
                                <small class="text-warning"><i class="fas fa-exclamation-triangle me-1"></i>نقاط التحسين:</small>
                                <ul class="list-unstyled ms-3">
                                    @foreach($department['weaknesses'] as $weakness)
                                    <li><small>• {{ $weakness }}</small></li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                    @if(!$loop->last)
                    <hr>
                    @endif
                    @endforeach
                </div>
            </div>
        </div>

        <!-- الاتجاهات والتوصيات -->
        <div class="col-lg-4 mb-4">
            <div class="card mb-4">
                <div class="card-header">
                    <h6 class="card-title mb-0">
                        <i class="fas fa-trending-up me-2"></i>
                        اتجاهات الأداء
                    </h6>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <h6 class="text-success">في تحسن</h6>
                        @foreach($teamAnalysis['trends']['improving'] as $dept)
                        <span class="badge bg-success me-1 mb-1">{{ $dept }}</span>
                        @endforeach
                    </div>
                    
                    <div class="mb-3">
                        <h6 class="text-primary">مستقر</h6>
                        @foreach($teamAnalysis['trends']['stable'] as $dept)
                        <span class="badge bg-primary me-1 mb-1">{{ $dept }}</span>
                        @endforeach
                    </div>
                    
                    @if(count($teamAnalysis['trends']['declining']) > 0)
                    <div class="mb-3">
                        <h6 class="text-danger">يحتاج انتباه</h6>
                        @foreach($teamAnalysis['trends']['declining'] as $dept)
                        <span class="badge bg-danger me-1 mb-1">{{ $dept }}</span>
                        @endforeach
                    </div>
                    @endif
                </div>
            </div>

            <!-- مؤشرات النجاح -->
            <div class="card">
                <div class="card-header">
                    <h6 class="card-title mb-0">
                        <i class="fas fa-bullseye me-2"></i>
                        مؤشرات النجاح
                    </h6>
                </div>
                <div class="card-body">
                    @foreach($developmentPlan['success_metrics'] as $metric)
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span>{{ $metric }}</span>
                        <div class="progress" style="width: 60px; height: 8px;">
                            <div class="progress-bar bg-success" style="width: {{ rand(60, 95) }}%"></div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <!-- خطة التطوير -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-road me-2"></i>
                        خطة التطوير المقترحة
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        @foreach($developmentPlan['priority_areas'] as $area)
                        <div class="col-lg-4 mb-4">
                            <div class="card border-{{ $area['priority'] == 'عالية' ? 'danger' : ($area['priority'] == 'متوسطة' ? 'warning' : 'info') }}">
                                <div class="card-header bg-{{ $area['priority'] == 'عالية' ? 'danger' : ($area['priority'] == 'متوسطة' ? 'warning' : 'info') }} text-white">
                                    <h6 class="card-title mb-0">{{ $area['area'] }}</h6>
                                    <small>أولوية: {{ $area['priority'] }}</small>
                                </div>
                                <div class="card-body">
                                    <div class="mb-2">
                                        <small class="text-muted">المدة الزمنية:</small>
                                        <div class="fw-bold">{{ $area['timeline'] }}</div>
                                    </div>
                                    <div class="mb-2">
                                        <small class="text-muted">الميزانية:</small>
                                        <div class="fw-bold text-primary">${{ number_format($area['budget']) }}</div>
                                    </div>
                                    <div class="mb-2">
                                        <small class="text-muted">التأثير المتوقع:</small>
                                        <div class="fw-bold text-success">{{ $area['expected_impact'] }}</div>
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

    <!-- برامج التدريب -->
    <div class="row mt-4">
        <div class="col-lg-6">
            <div class="card">
                <div class="card-header">
                    <h6 class="card-title mb-0">
                        <i class="fas fa-graduation-cap me-2"></i>
                        برامج التدريب المقترحة
                    </h6>
                </div>
                <div class="card-body">
                    <div class="list-group list-group-flush">
                        @foreach($developmentPlan['training_programs'] as $program)
                        <div class="list-group-item d-flex justify-content-between align-items-center">
                            <div>
                                <i class="fas fa-book me-2 text-primary"></i>
                                {{ $program }}
                            </div>
                            <span class="badge bg-primary rounded-pill">{{ rand(2, 8) }} أسابيع</span>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="card">
                <div class="card-header">
                    <h6 class="card-title mb-0">
                        <i class="fas fa-chart-pie me-2"></i>
                        توزيع الاستثمار
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
    // رسم بياني لتوزيع الاستثمار
    const ctx = document.getElementById('investmentChart').getContext('2d');
    
    const investmentData = @json(array_column($developmentPlan['priority_areas'], 'budget'));
    const investmentLabels = @json(array_column($developmentPlan['priority_areas'], 'area'));
    
    new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: investmentLabels,
            datasets: [{
                data: investmentData,
                backgroundColor: [
                    '#dc3545',
                    '#ffc107', 
                    '#17a2b8'
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
                            return context.label + ': $' + context.parsed.toLocaleString();
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
.department-analysis {
    border-left: 4px solid #007bff;
    padding-left: 1rem;
}

.opacity-75 {
    opacity: 0.75;
}

.progress {
    border-radius: 10px;
}

.badge {
    font-size: 0.75em;
}

.card {
    box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
}

.list-group-item {
    border-left: none;
    border-right: none;
}

.list-group-item:first-child {
    border-top: none;
}

.list-group-item:last-child {
    border-bottom: none;
}
</style>
@endpush
