<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Master Admin - لوحة التحكم</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
        }
        .navbar-master {
            background: rgba(255, 255, 255, 0.95) !important;
            backdrop-filter: blur(10px);
            box-shadow: 0 2px 20px rgba(0, 0, 0, 0.1);
        }
        .master-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
            margin-bottom: 20px;
        }
        .stat-card {
            background: linear-gradient(135deg, var(--bg-color), var(--bg-color-light));
            color: white;
            border-radius: 15px;
            padding: 25px;
            margin-bottom: 20px;
            transition: all 0.3s ease;
        }
        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.2);
        }
        .stat-card.primary {
            --bg-color: #667eea;
            --bg-color-light: #764ba2;
        }
        .stat-card.success {
            --bg-color: #56ab2f;
            --bg-color-light: #a8e6cf;
        }
        .stat-card.warning {
            --bg-color: #f093fb;
            --bg-color-light: #f5576c;
        }
        .stat-card.danger {
            --bg-color: #ff6b6b;
            --bg-color-light: #ee5a24;
        }
        .master-badge {
            background: linear-gradient(45deg, #ff6b6b, #ee5a24);
            color: white;
            padding: 5px 15px;
            border-radius: 50px;
            font-size: 12px;
            font-weight: bold;
        }
        .btn-master {
            background: linear-gradient(45deg, #667eea, #764ba2);
            border: none;
            border-radius: 50px;
            padding: 10px 25px;
            font-weight: bold;
            transition: all 0.3s ease;
        }
        .btn-master:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(102, 126, 234, 0.3);
        }
    </style>
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-master">
        <div class="container">
            <a class="navbar-brand fw-bold" href="{{ route('master-admin.dashboard') }}">
                <i class="fas fa-crown text-warning me-2"></i>
                Master Admin
            </a>
            
            <div class="navbar-nav ms-auto">
                <div class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" role="button" data-bs-toggle="dropdown">
                        <div class="avatar-sm me-2">
                            <div class="avatar-title bg-primary rounded-circle">
                                {{ strtoupper(substr(auth('master_admin')->user()->name, 0, 1)) }}
                            </div>
                        </div>
                        {{ auth('master_admin')->user()->name }}
                        <span class="master-badge ms-2">MASTER</span>
                    </a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="#"><i class="fas fa-user me-2"></i>الملف الشخصي</a></li>
                        <li><a class="dropdown-item" href="#"><i class="fas fa-cog me-2"></i>الإعدادات</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <form method="POST" action="{{ route('master-admin.logout') }}">
                                @csrf
                                <button type="submit" class="dropdown-item text-danger">
                                    <i class="fas fa-sign-out-alt me-2"></i>تسجيل الخروج
                                </button>
                            </form>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        <!-- رسائل النجاح والخطأ -->
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle me-2"></i>
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-circle me-2"></i>
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <!-- العنوان الرئيسي -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h2 class="text-white fw-bold">
                            <i class="fas fa-tachometer-alt me-2"></i>
                            لوحة التحكم الرئيسية
                        </h2>
                        <p class="text-white-50">إدارة التراخيص وحدود المستخدمين</p>
                    </div>
                    <div>
                        <a href="{{ route('master-admin.licenses.create') }}" class="btn btn-master text-white">
                            <i class="fas fa-plus me-2"></i>
                            إضافة ترخيص جديد
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- الإحصائيات الرئيسية -->
        <div class="row mb-4">
            <div class="col-lg-3 col-md-6">
                <div class="stat-card primary">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h3 class="mb-0">{{ $stats['total_licenses'] ?? 0 }}</h3>
                            <p class="mb-0">إجمالي التراخيص</p>
                            <small>نشط: {{ $stats['active_licenses'] ?? 0 }}</small>
                        </div>
                        <div>
                            <i class="fas fa-key fa-2x opacity-75"></i>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-md-6">
                <div class="stat-card success">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h3 class="mb-0">${{ number_format($stats['total_revenue'] ?? 0, 2) }}</h3>
                            <p class="mb-0">إجمالي الإيرادات</p>
                            <small>مدفوع: ${{ number_format($stats['total_revenue'] ?? 0, 2) }}</small>
                        </div>
                        <div>
                            <i class="fas fa-dollar-sign fa-2x opacity-75"></i>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-md-6">
                <div class="stat-card warning">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h3 class="mb-0">{{ $stats['near_expiry_licenses'] ?? 0 }}</h3>
                            <p class="mb-0">قريبة الانتهاء</p>
                            <small>خلال 30 يوم</small>
                        </div>
                        <div>
                            <i class="fas fa-clock fa-2x opacity-75"></i>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-md-6">
                <div class="stat-card danger">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h3 class="mb-0">{{ $stats['expired_licenses'] ?? 0 }}</h3>
                            <p class="mb-0">منتهية الصلاحية</p>
                            <small>تحتاج تجديد</small>
                        </div>
                        <div>
                            <i class="fas fa-exclamation-triangle fa-2x opacity-75"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- إحصائيات إضافية -->
        <div class="row mb-4">
            <div class="col-lg-4 col-md-6">
                <div class="master-card">
                    <div class="card-body text-center">
                        <i class="fas fa-users fa-3x text-primary mb-3"></i>
                        <h4>{{ $stats['total_users_allowed'] ?? 0 }}</h4>
                        <p class="text-muted">إجمالي المستخدمين المسموح</p>
                    </div>
                </div>
            </div>

            <div class="col-lg-4 col-md-6">
                <div class="master-card">
                    <div class="card-body text-center">
                        <i class="fas fa-warehouse fa-3x text-success mb-3"></i>
                        <h4>{{ $stats['total_warehouses_allowed'] ?? 0 }}</h4>
                        <p class="text-muted">إجمالي المخازن المسموح</p>
                    </div>
                </div>
            </div>

            <div class="col-lg-4 col-md-6">
                <div class="master-card">
                    <div class="card-body text-center">
                        <i class="fas fa-exclamation-circle fa-3x text-warning mb-3"></i>
                        <h4>{{ $stats['over_limit_licenses'] ?? 0 }}</h4>
                        <p class="text-muted">تراخيص تجاوزت الحد</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- التراخيص الحديثة والتنبيهات -->
        <div class="row">
            <div class="col-lg-8">
                <div class="master-card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">
                            <i class="fas fa-history me-2"></i>
                            التراخيص الحديثة
                        </h5>
                        <a href="{{ route('master-admin.licenses.index') }}" class="btn btn-outline-primary btn-sm">
                            عرض الكل
                        </a>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>العميل</th>
                                        <th>مفتاح الترخيص</th>
                                        <th>النوع</th>
                                        <th>تاريخ الانتهاء</th>
                                        <th>الحالة</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($recentLicenses ?? [] as $license)
                                        <tr>
                                            <td>
                                                <strong>{{ $license->client_name }}</strong>
                                                <br><small class="text-muted">{{ $license->client_email }}</small>
                                            </td>
                                            <td><code>{{ $license->license_key }}</code></td>
                                            <td>
                                                <span class="badge bg-secondary">{{ $license->license_type }}</span>
                                            </td>
                                            <td>{{ $license->end_date->format('Y-m-d') }}</td>
                                            <td>
                                                @if($license->is_active && $license->end_date > now())
                                                    <span class="badge bg-success">نشط</span>
                                                @elseif($license->end_date < now())
                                                    <span class="badge bg-danger">منتهي</span>
                                                @else
                                                    <span class="badge bg-warning">معلق</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="text-center text-muted">
                                                لا توجد تراخيص حديثة
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <!-- روابط سريعة -->
                <div class="master-card mb-3">
                    <div class="card-header">
                        <h6 class="mb-0">
                            <i class="fas fa-link me-2"></i>
                            روابط سريعة
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="d-grid gap-2">
                            <a href="{{ route('master-admin.licenses.index') }}" class="btn btn-outline-primary">
                                <i class="fas fa-key me-2"></i>
                                إدارة التراخيص
                            </a>
                            <a href="#" class="btn btn-outline-success">
                                <i class="fas fa-chart-bar me-2"></i>
                                التقارير
                            </a>
                            <a href="#" class="btn btn-outline-warning">
                                <i class="fas fa-users me-2"></i>
                                إدارة المديرين
                            </a>
                            <a href="#" class="btn btn-outline-info">
                                <i class="fas fa-cog me-2"></i>
                                إعدادات النظام
                            </a>
                        </div>
                    </div>
                </div>

                <!-- معلومات النظام -->
                <div class="master-card">
                    <div class="card-header">
                        <h6 class="mb-0">
                            <i class="fas fa-info-circle me-2"></i>
                            معلومات النظام
                        </h6>
                    </div>
                    <div class="card-body">
                        <p><strong>المستخدم:</strong> {{ auth('master_admin')->user()->name }}</p>
                        <p><strong>الإيميل:</strong> {{ auth('master_admin')->user()->email }}</p>
                        <p><strong>آخر دخول:</strong> 
                            @if(auth('master_admin')->user()->last_login_at)
                                {{ auth('master_admin')->user()->last_login_at->diffForHumans() }}
                            @else
                                أول مرة
                            @endif
                        </p>
                        <p class="mb-0"><strong>الحالة:</strong> 
                            <span class="badge bg-success">نشط</span>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    
    <style>
    .avatar-sm {
        width: 35px;
        height: 35px;
    }
    .avatar-title {
        width: 100%;
        height: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 600;
        font-size: 14px;
    }
    </style>
</body>
</html>
