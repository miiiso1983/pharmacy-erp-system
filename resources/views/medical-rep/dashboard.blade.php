@extends('layouts.app')

@section('title', 'لوحة تحكم المندوبين العلميين')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">الرئيسية</a></li>
    <li class="breadcrumb-item active">المندوبين العلميين</li>
@endsection

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0">
                <i class="fas fa-user-tie me-2"></i>
                المندوبين العلميين
            </h1>
            <p class="text-muted">نظام إدارة شامل للمندوبين العلميين والأطباء والزيارات</p>
        </div>
    </div>

    <!-- الوحدات الرئيسية -->
    <div class="row mb-4">
        <div class="col-12">
            <h4 class="mb-3">
                <i class="fas fa-th-large me-2"></i>
                وحدات المندوبين العلميين
            </h4>
        </div>
    </div>

    <div class="row mb-4">
        <!-- لوحة تحكم المندوبين -->
        <div class="col-lg-3 col-md-6 mb-4">
            <div class="card h-100 shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-tachometer-alt me-2"></i>
                        لوحة التحكم
                    </h5>
                </div>
                <div class="card-body">
                    <p class="card-text">إحصائيات شاملة وتقارير الأداء</p>
                    <div class="mb-3">
                        <small class="text-muted">
                            <i class="fas fa-chart-bar me-1"></i>
                            تقارير مباشرة ومتابعة الأداء
                        </small>
                    </div>
                    <div class="d-grid">
                        <span class="btn btn-primary disabled">
                            <i class="fas fa-eye me-1"></i>
                            الصفحة الحالية
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <!-- إدارة المندوبين -->
        <div class="col-lg-3 col-md-6 mb-4">
            <div class="card h-100 shadow-sm">
                <div class="card-header bg-success text-white">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-user-tie me-2"></i>
                        إدارة المندوبين
                    </h5>
                </div>
                <div class="card-body">
                    <p class="card-text">إدارة بيانات المندوبين العلميين</p>
                    <div class="mb-3">
                        <small class="text-muted">
                            <i class="fas fa-users me-1"></i>
                            {{ $stats['total_reps'] ?? 0 }} مندوب علمي
                        </small>
                    </div>
                    <div class="d-grid gap-2">
                        <a href="{{ route('medical-rep.representatives.index') }}" class="btn btn-success">
                            <i class="fas fa-eye me-1"></i>
                            عرض المندوبين
                        </a>
                        <a href="#" class="btn btn-outline-success">
                            <i class="fas fa-plus me-1"></i>
                            إضافة مندوب
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- إدارة الأطباء -->
        <div class="col-lg-3 col-md-6 mb-4">
            <div class="card h-100 shadow-sm">
                <div class="card-header bg-info text-white">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-user-md me-2"></i>
                        إدارة الأطباء
                    </h5>
                </div>
                <div class="card-body">
                    <p class="card-text">إدارة قاعدة بيانات الأطباء</p>
                    <div class="mb-3">
                        <small class="text-muted">
                            <i class="fas fa-stethoscope me-1"></i>
                            {{ $stats['total_doctors'] ?? 0 }} طبيب
                        </small>
                    </div>
                    <div class="d-grid gap-2">
                        <a href="{{ route('medical-rep.doctors.index') }}" class="btn btn-info">
                            <i class="fas fa-eye me-1"></i>
                            عرض الأطباء
                        </a>
                        <a href="#" class="btn btn-outline-info">
                            <i class="fas fa-upload me-1"></i>
                            رفع ملف Excel
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- إدارة الزيارات -->
        <div class="col-lg-3 col-md-6 mb-4">
            <div class="card h-100 shadow-sm">
                <div class="card-header bg-warning text-white">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-calendar-check me-2"></i>
                        إدارة الزيارات
                    </h5>
                </div>
                <div class="card-body">
                    <p class="card-text">تسجيل ومتابعة زيارات الأطباء</p>
                    <div class="mb-3">
                        <small class="text-muted">
                            <i class="fas fa-calendar me-1"></i>
                            {{ $stats['total_visits_this_month'] ?? 0 }} زيارة هذا الشهر
                        </small>
                    </div>
                    <div class="d-grid gap-2">
                        <a href="{{ route('medical-rep.visits.index') }}" class="btn btn-warning">
                            <i class="fas fa-eye me-1"></i>
                            عرض الزيارات
                        </a>
                        <a href="#" class="btn btn-outline-warning">
                            <i class="fas fa-plus me-1"></i>
                            إضافة زيارة
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- تقارير المندوبين -->
        <div class="col-lg-3 col-md-6 mb-4">
            <div class="card h-100 shadow-sm">
                <div class="card-header bg-danger text-white">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-chart-line me-2"></i>
                        تقارير المندوبين
                    </h5>
                </div>
                <div class="card-body">
                    <p class="card-text">تقارير شاملة عن الأداء والزيارات</p>
                    <div class="mb-3">
                        <small class="text-muted">
                            <i class="fas fa-file-alt me-1"></i>
                            تقارير متنوعة ومفصلة
                        </small>
                    </div>
                    <div class="d-grid gap-2">
                        <a href="{{ route('medical-rep.reports.index') }}" class="btn btn-danger">
                            <i class="fas fa-eye me-1"></i>
                            عرض التقارير
                        </a>
                        <a href="#" class="btn btn-outline-danger">
                            <i class="fas fa-download me-1"></i>
                            تصدير تقرير
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- الإحصائيات الرئيسية -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h4>{{ number_format($stats['total_reps']) }}</h4>
                            <p class="mb-0">إجمالي المندوبين</p>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-user-tie fa-2x"></i>
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
                            <h4>{{ number_format($stats['total_doctors']) }}</h4>
                            <p class="mb-0">إجمالي الأطباء</p>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-user-md fa-2x"></i>
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
                            <h4>{{ number_format($stats['completed_visits_this_month']) }}</h4>
                            <p class="mb-0">زيارات مكتملة</p>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-check-circle fa-2x"></i>
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
                            <h4>{{ number_format($stats['total_samples_distributed']) }}</h4>
                            <p class="mb-0">العينات الموزعة</p>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-pills fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- أداء المندوبين -->
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-chart-bar me-2"></i>
                        أداء المندوبين هذا الشهر
                    </h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>المندوب</th>
                                    <th>المنطقة</th>
                                    <th>الزيارات</th>
                                    <th>الهدف</th>
                                    <th>نسبة الإنجاز</th>
                                    <th>الإجراءات</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($repsPerformance as $rep)
                                <tr>
                                    <td>
                                        <strong>{{ $rep['name'] }}</strong>
                                    </td>
                                    <td>{{ $rep['territory'] ?? 'غير محدد' }}</td>
                                    <td>
                                        <span class="badge bg-info">{{ $rep['monthly_visits'] }}</span>
                                    </td>
                                    <td>
                                        <span class="badge bg-secondary">{{ $rep['monthly_target'] }}</span>
                                    </td>
                                    <td>
                                        @php
                                            $percentage = $rep['achievement_percentage'];
                                            $badgeClass = $percentage >= 100 ? 'success' : ($percentage >= 75 ? 'warning' : 'danger');
                                        @endphp
                                        <span class="badge bg-{{ $badgeClass }}">{{ $percentage }}%</span>
                                    </td>
                                    <td>
                                        <a href="{{ route('medical-rep.representatives.show', $rep['id']) }}" 
                                           class="btn btn-sm btn-outline-primary">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- أعلى المؤدين -->
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-trophy me-2"></i>
                        أعلى المؤدين
                    </h5>
                </div>
                <div class="card-body">
                    @foreach($topPerformers as $index => $performer)
                    <div class="d-flex align-items-center mb-3">
                        <div class="me-3">
                            @if($index == 0)
                                <i class="fas fa-medal text-warning fa-2x"></i>
                            @elseif($index == 1)
                                <i class="fas fa-medal text-secondary fa-2x"></i>
                            @elseif($index == 2)
                                <i class="fas fa-medal text-danger fa-2x"></i>
                            @else
                                <i class="fas fa-star text-info fa-2x"></i>
                            @endif
                        </div>
                        <div class="flex-grow-1">
                            <h6 class="mb-0">{{ $performer['name'] }}</h6>
                            <small class="text-muted">{{ $performer['territory'] }}</small>
                            <div class="progress mt-1" style="height: 5px;">
                                <div class="progress-bar bg-success" 
                                     style="width: {{ min($performer['achievement_percentage'], 100) }}%"></div>
                            </div>
                        </div>
                        <div class="ms-2">
                            <span class="badge bg-success">{{ $performer['achievement_percentage'] }}%</span>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <!-- الزيارات الأخيرة -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-clock me-2"></i>
                        الزيارات الأخيرة
                    </h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>المندوب</th>
                                    <th>الطبيب</th>
                                    <th>تاريخ الزيارة</th>
                                    <th>الحالة</th>
                                    <th>الموقع</th>
                                    <th>الإجراءات</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($recentVisits as $visit)
                                <tr>
                                    <td>{{ $visit->medicalRepresentative->name }}</td>
                                    <td>
                                        <div>
                                            <strong>{{ $visit->doctor->name }}</strong>
                                            <br>
                                            <small class="text-muted">{{ $visit->doctor->specialty }}</small>
                                        </div>
                                    </td>
                                    <td>{{ $visit->visit_date->format('Y-m-d H:i') }}</td>
                                    <td>
                                        <span class="badge bg-{{ $visit->status_badge }}">
                                            {{ $visit->status_label }}
                                        </span>
                                    </td>
                                    <td>
                                        @if($visit->latitude && $visit->longitude)
                                            <a href="https://maps.google.com/?q={{ $visit->latitude }},{{ $visit->longitude }}" 
                                               target="_blank" class="btn btn-sm btn-outline-info">
                                                <i class="fas fa-map-marker-alt"></i>
                                            </a>
                                        @else
                                            <span class="text-muted">غير محدد</span>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('medical-rep.visits.show', $visit->id) }}" 
                                           class="btn btn-sm btn-outline-primary">
                                            <i class="fas fa-eye"></i>
                                        </a>
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

    <!-- روابط سريعة -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-link me-2"></i>
                        روابط سريعة
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3">
                            <a href="{{ route('medical-rep.representatives.index') }}" class="btn btn-outline-primary w-100 mb-2">
                                <i class="fas fa-user-tie me-2"></i>
                                إدارة المندوبين
                            </a>
                        </div>
                        <div class="col-md-3">
                            <a href="{{ route('medical-rep.doctors.index') }}" class="btn btn-outline-success w-100 mb-2">
                                <i class="fas fa-user-md me-2"></i>
                                إدارة الأطباء
                            </a>
                        </div>
                        <div class="col-md-3">
                            <a href="{{ route('medical-rep.visits.index') }}" class="btn btn-outline-info w-100 mb-2">
                                <i class="fas fa-calendar-check me-2"></i>
                                إدارة الزيارات
                            </a>
                        </div>
                        <div class="col-md-3">
                            <a href="{{ route('medical-rep.reports.index') }}" class="btn btn-outline-warning w-100 mb-2">
                                <i class="fas fa-chart-line me-2"></i>
                                التقارير
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
