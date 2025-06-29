@extends('super-admin.layout')

@section('title', 'لوحة التحكم الرئيسية')

@section('content')
@push('styles')
<style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .navbar {
            background: rgba(255, 255, 255, 0.95) !important;
            backdrop-filter: blur(15px);
            box-shadow: 0 2px 20px rgba(0, 0, 0, 0.1);
        }
        .main-container {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(15px);
            border-radius: 20px;
            box-shadow: 0 25px 50px rgba(0, 0, 0, 0.15);
            border: 1px solid rgba(255, 255, 255, 0.2);
            margin: 20px;
            padding: 30px;
        }
        .stat-card {
            background: white;
            border-radius: 15px;
            padding: 25px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            border: none;
            transition: all 0.3s ease;
            margin-bottom: 20px;
        }
        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
        }
        .stat-icon {
            width: 60px;
            height: 60px;
            border-radius: 15px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            color: white;
            margin-bottom: 15px;
        }
        .stat-number {
            font-size: 2rem;
            font-weight: bold;
            margin-bottom: 5px;
        }
        .stat-label {
            color: #6c757d;
            font-size: 0.9rem;
        }
        .primary { background: linear-gradient(135deg, #667eea, #764ba2); }
        .success { background: linear-gradient(135deg, #28a745, #20c997); }
        .warning { background: linear-gradient(135deg, #ffc107, #fd7e14); }
        .danger { background: linear-gradient(135deg, #dc3545, #c82333); }
        .info { background: linear-gradient(135deg, #17a2b8, #138496); }
        .secondary { background: linear-gradient(135deg, #6c757d, #495057); }
        
        .nav-pills .nav-link {
            border-radius: 25px;
            margin: 0 5px;
            transition: all 0.3s ease;
        }
        .nav-pills .nav-link.active {
            background: linear-gradient(135deg, #667eea, #764ba2);
        }
        .table {
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }
        .btn-custom {
            border-radius: 25px;
            padding: 10px 25px;
            font-weight: bold;
            transition: all 0.3s ease;
        }
        .btn-custom:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
        }
    </style>
@endpush

<!-- Header -->
<div class="row mb-4">
    <div class="col-12">
        <h1 class="h2 mb-0">
            <i class="fas fa-tachometer-alt me-3"></i>
            لوحة تحكم Super Admin
        </h1>
        <p class="text-muted">مرحباً بك في لوحة التحكم الرئيسية للنظام</p>
    </div>
</div>

<!-- Navigation Tabs -->
<ul class="nav nav-pills mb-4" role="tablist">
    <li class="nav-item">
        <a class="nav-link active" data-bs-toggle="pill" href="#overview">
            <i class="fas fa-chart-pie me-2"></i>نظرة عامة
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link" href="{{ route('super-admin.licenses') }}">
            <i class="fas fa-certificate me-2"></i>التراخيص
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link" href="{{ route('super-admin.users') }}">
            <i class="fas fa-users me-2"></i>المستخدمين
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link" href="{{ route('super-admin.reports') }}">
            <i class="fas fa-chart-bar me-2"></i>التقارير
        </a>
    </li>
</ul>

<!-- Tab Content -->
<div class="tab-content">
    <div class="tab-pane fade show active" id="overview">
            <!-- Header -->
            <div class="row mb-4">
                <div class="col-12">
                    <h1 class="h2 mb-0">
                        <i class="fas fa-tachometer-alt me-3"></i>
                        لوحة تحكم Super Admin
                    </h1>
                    <p class="text-muted">مرحباً بك في لوحة التحكم الرئيسية للنظام</p>
                </div>
            </div>

            <!-- Navigation Tabs -->
            <ul class="nav nav-pills mb-4" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" data-bs-toggle="pill" href="#overview">
                        <i class="fas fa-chart-pie me-2"></i>نظرة عامة
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('super-admin.licenses') }}">
                        <i class="fas fa-certificate me-2"></i>التراخيص
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('super-admin.users') }}">
                        <i class="fas fa-users me-2"></i>المستخدمين
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('super-admin.reports') }}">
                        <i class="fas fa-chart-bar me-2"></i>التقارير
                    </a>
                </li>
            </ul>

            <!-- Tab Content -->
            <div class="tab-content">
                <div class="tab-pane fade show active" id="overview">
                    <!-- Statistics Cards -->
                    <div class="row mb-4">
                        <div class="col-lg-3 col-md-6">
                            <div class="stat-card">
                                <div class="stat-icon primary">
                                    <i class="fas fa-certificate"></i>
                                </div>
                                <div class="stat-number text-primary">{{ number_format($stats['total_licenses']) }}</div>
                                <div class="stat-label">إجمالي التراخيص</div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-6">
                            <div class="stat-card">
                                <div class="stat-icon success">
                                    <i class="fas fa-check-circle"></i>
                                </div>
                                <div class="stat-number text-success">{{ number_format($stats['active_licenses']) }}</div>
                                <div class="stat-label">التراخيص النشطة</div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-6">
                            <div class="stat-card">
                                <div class="stat-icon warning">
                                    <i class="fas fa-exclamation-triangle"></i>
                                </div>
                                <div class="stat-number text-warning">{{ number_format($stats['expired_licenses']) }}</div>
                                <div class="stat-label">التراخيص المنتهية</div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-6">
                            <div class="stat-card">
                                <div class="stat-icon info">
                                    <i class="fas fa-users"></i>
                                </div>
                                <div class="stat-number text-info">{{ number_format($stats['total_users']) }}</div>
                                <div class="stat-label">إجمالي المستخدمين</div>
                            </div>
                        </div>
                    </div>

                    <!-- System Health -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="card-title mb-0">
                                        <i class="fas fa-heartbeat me-2"></i>
                                        صحة النظام
                                    </h5>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        @foreach($systemHealth as $component => $status)
                                            <div class="col-md-3 mb-3">
                                                <div class="d-flex align-items-center">
                                                    <i class="fas fa-circle me-2 
                                                        @if($status === 'healthy') text-success
                                                        @elseif($status === 'warning') text-warning
                                                        @else text-danger
                                                        @endif
                                                    "></i>
                                                    <span class="fw-bold">
                                                        @if($component === 'database') قاعدة البيانات
                                                        @elseif($component === 'storage') التخزين
                                                        @elseif($component === 'cache') التخزين المؤقت
                                                        @elseif($component === 'queue') قائمة الانتظار
                                                        @else {{ $component }}
                                                        @endif
                                                    </span>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Recent Licenses -->
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="card-title mb-0">
                                        <i class="fas fa-clock me-2"></i>
                                        أحدث التراخيص
                                    </h5>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-hover">
                                            <thead>
                                                <tr>
                                                    <th>مفتاح الترخيص</th>
                                                    <th>العميل</th>
                                                    <th>النوع</th>
                                                    <th>الحالة</th>
                                                    <th>تاريخ الإنشاء</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($recentLicenses as $license)
                                                    <tr>
                                                        <td><code>{{ $license->license_key }}</code></td>
                                                        <td>{{ $license->client_name }}</td>
                                                        <td>
                                                            <span class="badge 
                                                                @if($license->license_type === 'premium') bg-primary
                                                                @elseif($license->license_type === 'full') bg-success
                                                                @else bg-secondary
                                                                @endif
                                                            ">
                                                                {{ $license->license_type }}
                                                            </span>
                                                        </td>
                                                        <td>
                                                            @if($license->is_active)
                                                                <span class="badge bg-success">نشط</span>
                                                            @else
                                                                <span class="badge bg-danger">غير نشط</span>
                                                            @endif
                                                        </td>
                                                        <td>{{ $license->created_at->format('Y-m-d') }}</td>
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
            </div>
        </div>
    </div>
</div>
@endsection
