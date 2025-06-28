@extends('layouts.app')

@section('title', 'تفاصيل المندوب العلمي')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">الرئيسية</a></li>
    <li class="breadcrumb-item"><a href="{{ route('medical-rep.dashboard') }}">المندوبين العلميين</a></li>
    <li class="breadcrumb-item"><a href="{{ route('medical-rep.representatives.index') }}">المندوبين</a></li>
    <li class="breadcrumb-item active">{{ $representative->name }}</li>
@endsection

@section('content')
<div class="container-fluid">
    <!-- معلومات المندوب -->
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">معلومات المندوب</h5>
                </div>
                <div class="card-body">
                    <div class="text-center mb-3">
                        <i class="fas fa-user-circle fa-5x text-primary"></i>
                        <h4 class="mt-2">{{ $representative->name }}</h4>
                        <p class="text-muted">{{ $representative->employee_id }}</p>
                    </div>
                    
                    <table class="table table-borderless">
                        <tr>
                            <td><strong>البريد الإلكتروني:</strong></td>
                            <td>{{ $representative->email }}</td>
                        </tr>
                        <tr>
                            <td><strong>الهاتف:</strong></td>
                            <td>{{ $representative->phone }}</td>
                        </tr>
                        <tr>
                            <td><strong>المنطقة:</strong></td>
                            <td>{{ $representative->territory ?? 'غير محدد' }}</td>
                        </tr>
                        <tr>
                            <td><strong>المشرف:</strong></td>
                            <td>{{ $representative->supervisor->name ?? 'غير محدد' }}</td>
                        </tr>
                        <tr>
                            <td><strong>تاريخ التوظيف:</strong></td>
                            <td>{{ $representative->hire_date->format('Y-m-d') }}</td>
                        </tr>
                        <tr>
                            <td><strong>الحالة:</strong></td>
                            <td>
                                <span class="badge bg-{{ $representative->status_badge }}">
                                    {{ $representative->status_label }}
                                </span>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
        
        <!-- الإحصائيات -->
        <div class="col-md-8">
            <div class="row">
                <div class="col-md-3">
                    <div class="card bg-primary text-white">
                        <div class="card-body text-center">
                            <h3>{{ $stats['total_doctors'] }}</h3>
                            <p class="mb-0">إجمالي الأطباء</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-success text-white">
                        <div class="card-body text-center">
                            <h3>{{ $stats['monthly_visits'] }}</h3>
                            <p class="mb-0">زيارات هذا الشهر</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-info text-white">
                        <div class="card-body text-center">
                            <h3>{{ $stats['total_samples'] }}</h3>
                            <p class="mb-0">العينات الموزعة</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-warning text-white">
                        <div class="card-body text-center">
                            <h3>{{ $stats['achievement_percentage'] }}%</h3>
                            <p class="mb-0">نسبة الإنجاز</p>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- الأطباء -->
            <div class="card mt-3">
                <div class="card-header">
                    <h5 class="mb-0">الأطباء المكلف بهم</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>الطبيب</th>
                                    <th>التخصص</th>
                                    <th>التصنيف</th>
                                    <th>المدينة</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($representative->doctors as $doctor)
                                <tr>
                                    <td>{{ $doctor->name }}</td>
                                    <td>{{ $doctor->specialty }}</td>
                                    <td>
                                        <span class="badge bg-{{ $doctor->classification_badge }}">
                                            {{ $doctor->classification }}
                                        </span>
                                    </td>
                                    <td>{{ $doctor->city }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- الزيارات الأخيرة -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">الزيارات الأخيرة</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>الطبيب</th>
                                    <th>تاريخ الزيارة</th>
                                    <th>نوع الزيارة</th>
                                    <th>الحالة</th>
                                    <th>الإجراءات</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($recentVisits as $visit)
                                <tr>
                                    <td>
                                        <strong>{{ $visit->doctor->name }}</strong>
                                        <br>
                                        <small class="text-muted">{{ $visit->doctor->specialty }}</small>
                                    </td>
                                    <td>{{ $visit->visit_date->format('Y-m-d H:i') }}</td>
                                    <td>{{ $visit->visit_type_label }}</td>
                                    <td>
                                        <span class="badge bg-{{ $visit->status_badge }}">
                                            {{ $visit->status_label }}
                                        </span>
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
</div>
@endsection
