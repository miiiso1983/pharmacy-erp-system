@extends('layouts.app')

@section('title', 'تقارير المندوبين العلميين')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">الرئيسية</a></li>
    <li class="breadcrumb-item"><a href="{{ route('medical-rep.dashboard') }}">المندوبين العلميين</a></li>
    <li class="breadcrumb-item active">التقارير</li>
@endsection

@section('content')
<div class="container-fluid">
    <!-- تقرير الأداء الشهري -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-chart-line me-2"></i>
                        تقرير الأداء الشهري
                    </h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>المندوب</th>
                                    <th>المنطقة</th>
                                    <th>الزيارات المكتملة</th>
                                    <th>الهدف الشهري</th>
                                    <th>نسبة الإنجاز</th>
                                    <th>الحالة</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($monthlyPerformance as $performance)
                                <tr>
                                    <td><strong>{{ $performance->name }}</strong></td>
                                    <td>{{ $performance->territory ?? 'غير محدد' }}</td>
                                    <td>
                                        <span class="badge bg-success">{{ $performance->monthly_visits }}</span>
                                    </td>
                                    <td>
                                        <span class="badge bg-secondary">{{ $performance->monthly_target ?? 0 }}</span>
                                    </td>
                                    <td>
                                        @php
                                            $percentage = $performance->achievement_percentage;
                                            $badgeClass = $percentage >= 100 ? 'success' : ($percentage >= 75 ? 'warning' : 'danger');
                                        @endphp
                                        <span class="badge bg-{{ $badgeClass }}">{{ $percentage }}%</span>
                                    </td>
                                    <td>
                                        @if($percentage >= 100)
                                            <i class="fas fa-check-circle text-success"></i> مكتمل
                                        @elseif($percentage >= 75)
                                            <i class="fas fa-clock text-warning"></i> جيد
                                        @else
                                            <i class="fas fa-exclamation-triangle text-danger"></i> يحتاج تحسين
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
    </div>

    <!-- تقرير توزيع الزيارات -->
    <div class="row mb-4">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-calendar-alt me-2"></i>
                        توزيع الزيارات اليومي
                    </h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>التاريخ</th>
                                    <th>إجمالي</th>
                                    <th>مكتملة</th>
                                    <th>ملغية</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($visitDistribution as $day)
                                <tr>
                                    <td>{{ \Carbon\Carbon::parse($day->date)->format('Y-m-d') }}</td>
                                    <td><span class="badge bg-info">{{ $day->total_visits }}</span></td>
                                    <td><span class="badge bg-success">{{ $day->completed_visits }}</span></td>
                                    <td><span class="badge bg-danger">{{ $day->cancelled_visits }}</span></td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- تقرير العينات -->
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-pills me-2"></i>
                        تقرير العينات الموزعة
                    </h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>المنتج</th>
                                    <th>الكمية</th>
                                    <th>عدد الزيارات</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($samplesReport as $sample)
                                <tr>
                                    <td><strong>{{ $sample->item_name }}</strong></td>
                                    <td><span class="badge bg-primary">{{ $sample->total_distributed }}</span></td>
                                    <td><span class="badge bg-info">{{ $sample->visits_count }}</span></td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- أزرار التصدير -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-download me-2"></i>
                        تصدير التقارير
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4">
                            <button class="btn btn-success w-100 mb-2">
                                <i class="fas fa-file-excel me-2"></i>
                                تصدير تقرير الأداء (Excel)
                            </button>
                        </div>
                        <div class="col-md-4">
                            <button class="btn btn-danger w-100 mb-2">
                                <i class="fas fa-file-pdf me-2"></i>
                                تصدير تقرير الزيارات (PDF)
                            </button>
                        </div>
                        <div class="col-md-4">
                            <button class="btn btn-info w-100 mb-2">
                                <i class="fas fa-file-csv me-2"></i>
                                تصدير تقرير العينات (CSV)
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
// يمكن إضافة JavaScript للمخططات البيانية هنا
</script>
@endpush
@endsection
