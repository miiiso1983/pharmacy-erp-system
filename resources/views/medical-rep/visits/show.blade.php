@extends('layouts.app')

@section('title', 'تفاصيل الزيارة')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">الرئيسية</a></li>
    <li class="breadcrumb-item"><a href="{{ route('medical-rep.dashboard') }}">المندوبين العلميين</a></li>
    <li class="breadcrumb-item"><a href="{{ route('medical-rep.visits.index') }}">الزيارات</a></li>
    <li class="breadcrumb-item active">تفاصيل الزيارة</li>
@endsection

@section('content')
<div class="container-fluid">
    <div class="row">
        <!-- معلومات الزيارة الأساسية -->
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-info-circle me-2"></i>
                        معلومات الزيارة
                    </h5>
                </div>
                <div class="card-body">
                    <table class="table table-borderless">
                        <tr>
                            <td><strong>رقم الزيارة:</strong></td>
                            <td>#{{ $visit->id }}</td>
                        </tr>
                        <tr>
                            <td><strong>المندوب:</strong></td>
                            <td>{{ $visit->medicalRepresentative->name }}</td>
                        </tr>
                        <tr>
                            <td><strong>الطبيب:</strong></td>
                            <td>
                                <strong>{{ $visit->doctor->name }}</strong>
                                <br>
                                <small class="text-muted">{{ $visit->doctor->specialty }}</small>
                            </td>
                        </tr>
                        <tr>
                            <td><strong>تاريخ الزيارة:</strong></td>
                            <td>{{ $visit->visit_date->format('Y-m-d H:i') }}</td>
                        </tr>
                        <tr>
                            <td><strong>نوع الزيارة:</strong></td>
                            <td>{{ $visit->visit_type_label }}</td>
                        </tr>
                        <tr>
                            <td><strong>الحالة:</strong></td>
                            <td>
                                <span class="badge bg-{{ $visit->status_badge }}">
                                    {{ $visit->status_label }}
                                </span>
                            </td>
                        </tr>
                        @if($visit->next_visit_date)
                        <tr>
                            <td><strong>الزيارة القادمة:</strong></td>
                            <td>{{ $visit->next_visit_date->format('Y-m-d H:i') }}</td>
                        </tr>
                        @endif
                        @if($visit->duration_formatted)
                        <tr>
                            <td><strong>مدة الزيارة:</strong></td>
                            <td>{{ $visit->duration_formatted }}</td>
                        </tr>
                        @endif
                    </table>
                </div>
            </div>
        </div>

        <!-- معلومات الموقع -->
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-map-marker-alt me-2"></i>
                        معلومات الموقع
                    </h5>
                </div>
                <div class="card-body">
                    @if($visit->latitude && $visit->longitude)
                        <table class="table table-borderless">
                            <tr>
                                <td><strong>خط العرض:</strong></td>
                                <td>{{ $visit->latitude }}</td>
                            </tr>
                            <tr>
                                <td><strong>خط الطول:</strong></td>
                                <td>{{ $visit->longitude }}</td>
                            </tr>
                            @if($visit->location_address)
                            <tr>
                                <td><strong>العنوان:</strong></td>
                                <td>{{ $visit->location_address }}</td>
                            </tr>
                            @endif
                        </table>
                        
                        <div class="text-center">
                            <a href="https://maps.google.com/?q={{ $visit->latitude }},{{ $visit->longitude }}" 
                               target="_blank" class="btn btn-primary">
                                <i class="fas fa-external-link-alt me-2"></i>
                                عرض على الخريطة
                            </a>
                        </div>
                    @else
                        <div class="text-center text-muted">
                            <i class="fas fa-map-marker-alt fa-3x mb-3"></i>
                            <p>لم يتم تسجيل موقع للزيارة</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- ملاحظات الزيارة -->
    @if($visit->visit_notes || $visit->doctor_feedback)
    <div class="row mt-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-sticky-note me-2"></i>
                        ملاحظات الزيارة
                    </h5>
                </div>
                <div class="card-body">
                    @if($visit->visit_notes)
                        <div class="mb-3">
                            <h6><strong>ملاحظات المندوب:</strong></h6>
                            <p class="text-muted">{{ $visit->visit_notes }}</p>
                        </div>
                    @endif
                    
                    @if($visit->doctor_feedback)
                        <div class="mb-3">
                            <h6><strong>تعليقات الطبيب:</strong></h6>
                            <p class="text-muted">{{ $visit->doctor_feedback }}</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- العينات الموزعة -->
    @if($visit->samples->count() > 0)
    <div class="row mt-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-pills me-2"></i>
                        العينات الموزعة
                    </h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>اسم المنتج</th>
                                    <th>الكمية</th>
                                    <th>رقم الدفعة</th>
                                    <th>تاريخ الانتهاء</th>
                                    <th>حالة الانتهاء</th>
                                    <th>ملاحظات</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($visit->samples as $sample)
                                <tr>
                                    <td><strong>{{ $sample->item_name }}</strong></td>
                                    <td>{{ $sample->quantity_distributed }}</td>
                                    <td>{{ $sample->batch_number ?? '-' }}</td>
                                    <td>
                                        @if($sample->expiry_date)
                                            {{ $sample->expiry_date->format('Y-m-d') }}
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td>
                                        <span class="badge bg-{{ $sample->expiry_status_badge }}">
                                            {{ $sample->expiry_status_label }}
                                        </span>
                                    </td>
                                    <td>{{ $sample->notes ?? '-' }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- الدعم التسويقي -->
    @if($visit->marketing_support_type || $visit->marketing_support_details)
    <div class="row mt-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-bullhorn me-2"></i>
                        الدعم التسويقي
                    </h5>
                </div>
                <div class="card-body">
                    @if($visit->marketing_support_type)
                        <div class="mb-3">
                            <h6><strong>نوع الدعم:</strong></h6>
                            <p class="text-muted">{{ $visit->marketing_support_type }}</p>
                        </div>
                    @endif
                    
                    @if($visit->marketing_support_details)
                        <div class="mb-3">
                            <h6><strong>تفاصيل الدعم:</strong></h6>
                            <p class="text-muted">{{ $visit->marketing_support_details }}</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- الطلبية المرتبطة -->
    @if($visit->order)
    <div class="row mt-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-shopping-cart me-2"></i>
                        الطلبية المرتبطة
                    </h5>
                </div>
                <div class="card-body">
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle me-2"></i>
                        تم إنشاء طلبية رقم <strong>#{{ $visit->order->id }}</strong> من هذه الزيارة
                        <a href="{{ route('orders.show', $visit->order->id) }}" class="btn btn-sm btn-primary ms-2">
                            عرض الطلبية
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>
@endsection
