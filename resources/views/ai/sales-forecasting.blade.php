@extends('layouts.app')

@section('title', 'التنبؤ بالمبيعات - الذكاء الاصطناعي - ' . __('messages.system_name'))

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">{{ __('navigation.dashboard') }}</a></li>
    <li class="breadcrumb-item"><a href="{{ route('ai.dashboard') }}">الذكاء الاصطناعي</a></li>
    <li class="breadcrumb-item active">التنبؤ بالمبيعات</li>
@endsection

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0">
                <i class="fas fa-chart-line me-2 text-primary"></i>
                التنبؤ بالمبيعات
            </h1>
            <p class="text-muted">تحليل ذكي للمبيعات والتنبؤ بالاتجاهات المستقبلية</p>
        </div>
        <div>
            <a href="{{ route('ai.dashboard') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left me-2"></i>
                العودة
            </a>
        </div>
    </div>

    <!-- فلاتر التحليل -->
    <div class="card mb-4">
        <div class="card-header">
            <h5 class="card-title mb-0">
                <i class="fas fa-filter me-2"></i>
                إعدادات التحليل
            </h5>
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('ai.sales-forecasting') }}">
                <div class="row">
                    <div class="col-md-3 mb-3">
                        <label for="period" class="form-label">فترة التنبؤ</label>
                        <select class="form-select" id="period" name="period">
                            <option value="weekly" {{ $period == 'weekly' ? 'selected' : '' }}>أسبوعي</option>
                            <option value="monthly" {{ $period == 'monthly' ? 'selected' : '' }}>شهري</option>
                            <option value="quarterly" {{ $period == 'quarterly' ? 'selected' : '' }}>ربع سنوي</option>
                        </select>
                    </div>
                    <div class="col-md-3 mb-3">
                        <label for="department" class="form-label">القسم</label>
                        <select class="form-select" id="department" name="department">
                            <option value="all" {{ $department == 'all' ? 'selected' : '' }}>جميع الأقسام</option>
                            <option value="sales" {{ $department == 'sales' ? 'selected' : '' }}>المبيعات</option>
                            <option value="marketing" {{ $department == 'marketing' ? 'selected' : '' }}>التسويق</option>
                            <option value="retail" {{ $department == 'retail' ? 'selected' : '' }}>التجزئة</option>
                        </select>
                    </div>
                    <div class="col-md-3 mb-3">
                        <label for="model" class="form-label">نموذج التنبؤ</label>
                        <select class="form-select" id="model" name="model">
                            <option value="linear">خطي</option>
                            <option value="exponential">أسي</option>
                            <option value="seasonal">موسمي</option>
                            <option value="neural" selected>شبكة عصبية</option>
                        </select>
                    </div>
                    <div class="col-md-3 mb-3 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary me-2">
                            <i class="fas fa-brain me-2"></i>
                            تحليل بالذكاء الاصطناعي
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- ملخص التنبؤات -->
    <div class="row mb-4">
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card bg-primary text-white">
                <div class="card-body text-center">
                    <h4 class="mb-1">${{ number_format($forecast['summary']['total_predicted']) }}</h4>
                    <small>إجمالي متوقع</small>
                    <div class="mt-2">
                        <i class="fas fa-chart-line fa-2x opacity-75"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card bg-success text-white">
                <div class="card-body text-center">
                    <h4 class="mb-1">{{ $forecast['summary']['average_confidence'] }}%</h4>
                    <small>دقة التنبؤ</small>
                    <div class="mt-2">
                        <i class="fas fa-bullseye fa-2x opacity-75"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card bg-warning text-white">
                <div class="card-body text-center">
                    <h4 class="mb-1">
                        <i class="fas fa-arrow-{{ $forecast['summary']['growth_trend'] == 'positive' ? 'up' : 'down' }}"></i>
                    </h4>
                    <small>اتجاه النمو</small>
                    <div class="mt-2">
                        <span class="badge bg-light text-dark">{{ $forecast['summary']['growth_trend'] == 'positive' ? 'إيجابي' : 'سلبي' }}</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card bg-info text-white">
                <div class="card-body text-center">
                    <h4 class="mb-1">{{ count($forecast['data']) }}</h4>
                    <small>فترات التنبؤ</small>
                    <div class="mt-2">
                        <i class="fas fa-calendar fa-2x opacity-75"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- الرسم البياني -->
        <div class="col-lg-8 mb-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-chart-area me-2"></i>
                        رسم بياني للتنبؤات
                    </h5>
                </div>
                <div class="card-body">
                    <canvas id="forecastChart" height="100"></canvas>
                </div>
            </div>
        </div>

        <!-- التوصيات -->
        <div class="col-lg-4 mb-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-lightbulb me-2"></i>
                        توصيات الذكاء الاصطناعي
                    </h5>
                </div>
                <div class="card-body">
                    @foreach($forecast['summary']['recommendations'] as $recommendation)
                    <div class="alert alert-info alert-sm">
                        <i class="fas fa-robot me-2"></i>
                        {{ $recommendation }}
                    </div>
                    @endforeach
                    
                    <div class="mt-3">
                        <h6 class="text-primary">مؤشرات الأداء</h6>
                        <div class="mb-2">
                            <div class="d-flex justify-content-between">
                                <span>دقة النموذج:</span>
                                <strong>{{ $forecast['summary']['average_confidence'] }}%</strong>
                            </div>
                            <div class="progress" style="height: 5px;">
                                <div class="progress-bar bg-success" style="width: {{ $forecast['summary']['average_confidence'] }}%"></div>
                            </div>
                        </div>
                        
                        <div class="mb-2">
                            <div class="d-flex justify-content-between">
                                <span>مستوى الثقة:</span>
                                <strong>
                                    @if($forecast['summary']['average_confidence'] >= 90)
                                        عالي جداً
                                    @elseif($forecast['summary']['average_confidence'] >= 80)
                                        عالي
                                    @elseif($forecast['summary']['average_confidence'] >= 70)
                                        متوسط
                                    @else
                                        منخفض
                                    @endif
                                </strong>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- جدول التنبؤات التفصيلي -->
    <div class="card">
        <div class="card-header">
            <h5 class="card-title mb-0">
                <i class="fas fa-table me-2"></i>
                تفاصيل التنبؤات
            </h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>الفترة</th>
                            <th>القيمة المتوقعة</th>
                            <th>مستوى الثقة</th>
                            <th>الاتجاه</th>
                            <th>التقييم</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($forecast['data'] as $item)
                        <tr>
                            <td>
                                <strong>{{ $item['period'] }}</strong>
                            </td>
                            <td>
                                <span class="text-primary fw-bold">${{ number_format($item['predicted_value']) }}</span>
                            </td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <span class="me-2">{{ $item['confidence'] }}%</span>
                                    <div class="progress flex-grow-1" style="height: 8px; width: 60px;">
                                        <div class="progress-bar bg-{{ $item['confidence'] >= 80 ? 'success' : ($item['confidence'] >= 60 ? 'warning' : 'danger') }}" 
                                             style="width: {{ $item['confidence'] }}%"></div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                @if($item['trend'] == 'up')
                                    <span class="badge bg-success">
                                        <i class="fas fa-arrow-up me-1"></i>صاعد
                                    </span>
                                @else
                                    <span class="badge bg-danger">
                                        <i class="fas fa-arrow-down me-1"></i>هابط
                                    </span>
                                @endif
                            </td>
                            <td>
                                @if($item['confidence'] >= 85)
                                    <span class="badge bg-success">ممتاز</span>
                                @elseif($item['confidence'] >= 75)
                                    <span class="badge bg-primary">جيد</span>
                                @elseif($item['confidence'] >= 65)
                                    <span class="badge bg-warning">متوسط</span>
                                @else
                                    <span class="badge bg-danger">ضعيف</span>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const ctx = document.getElementById('forecastChart').getContext('2d');
    
    const labels = @json(array_column($forecast['data'], 'period'));
    const data = @json(array_column($forecast['data'], 'predicted_value'));
    const confidence = @json(array_column($forecast['data'], 'confidence'));
    
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: labels,
            datasets: [{
                label: 'التنبؤات',
                data: data,
                borderColor: '#007bff',
                backgroundColor: 'rgba(0, 123, 255, 0.1)',
                tension: 0.4,
                fill: true,
                pointBackgroundColor: data.map((value, index) => {
                    const conf = confidence[index];
                    return conf >= 80 ? '#28a745' : conf >= 60 ? '#ffc107' : '#dc3545';
                }),
                pointBorderColor: '#fff',
                pointBorderWidth: 2,
                pointRadius: 6
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
                    text: 'تنبؤات المبيعات بالذكاء الاصطناعي'
                },
                tooltip: {
                    callbacks: {
                        afterLabel: function(context) {
                            const index = context.dataIndex;
                            return 'مستوى الثقة: ' + confidence[index] + '%';
                        }
                    }
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
.alert-sm {
    padding: 0.5rem;
    margin-bottom: 0.5rem;
    font-size: 0.875rem;
}

.opacity-75 {
    opacity: 0.75;
}

.table th {
    background-color: #f8f9fa;
    border-top: none;
    font-weight: 600;
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
</style>
@endpush
