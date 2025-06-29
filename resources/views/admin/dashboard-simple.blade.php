<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>لوحة التحكم الإدارية</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="#">
                <i class="fas fa-tachometer-alt me-2"></i>
                لوحة التحكم الإدارية
            </a>
            <div class="navbar-nav ms-auto">
                <span class="navbar-text me-3">
                    مرحباً، {{ auth()->user()->name }}
                </span>
                <form method="POST" action="{{ route('logout') }}" style="display: inline;">
                    @csrf
                    <button type="submit" class="btn btn-outline-light btn-sm">
                        <i class="fas fa-sign-out-alt me-1"></i>
                        تسجيل الخروج
                    </button>
                </form>
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
        <div class="row">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h2>
                        <i class="fas fa-crown me-2 text-warning"></i>
                        لوحة التحكم الإدارية
                    </h2>
                    <div class="btn-group">
                        <a href="{{ route('admin.users.create') }}" class="btn btn-success">
                            <i class="fas fa-user-plus me-2"></i>
                            إضافة مستخدم
                        </a>
                        <a href="{{ route('admin.licenses.create') }}" class="btn btn-primary">
                            <i class="fas fa-key me-2"></i>
                            إضافة ترخيص
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- إحصائيات سريعة -->
        <div class="row mb-4">
            <div class="col-lg-3 col-md-6 mb-3">
                <div class="card bg-primary text-white">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h4 class="mb-0">{{ $stats['total_users'] ?? 0 }}</h4>
                                <p class="mb-0">إجمالي المستخدمين</p>
                                <small>نشط: {{ $stats['active_users'] ?? 0 }}</small>
                            </div>
                            <div class="align-self-center">
                                <i class="fas fa-users fa-2x"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-md-6 mb-3">
                <div class="card bg-success text-white">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h4 class="mb-0">{{ $stats['total_licenses'] ?? 0 }}</h4>
                                <p class="mb-0">إجمالي التراخيص</p>
                                <small>نشط: {{ $stats['active_licenses'] ?? 0 }}</small>
                            </div>
                            <div class="align-self-center">
                                <i class="fas fa-key fa-2x"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-md-6 mb-3">
                <div class="card bg-info text-white">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h4 class="mb-0">{{ $stats['total_warehouses'] ?? 0 }}</h4>
                                <p class="mb-0">إجمالي المخازن</p>
                                <small>نشط: {{ $stats['active_warehouses'] ?? 0 }}</small>
                            </div>
                            <div class="align-self-center">
                                <i class="fas fa-warehouse fa-2x"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-md-6 mb-3">
                <div class="card bg-warning text-white">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h4 class="mb-0">{{ ($stats['near_expiry_licenses'] ?? 0) + ($stats['expired_licenses'] ?? 0) }}</h4>
                                <p class="mb-0">تنبيهات</p>
                                <small>منتهية: {{ $stats['expired_licenses'] ?? 0 }}</small>
                            </div>
                            <div class="align-self-center">
                                <i class="fas fa-exclamation-triangle fa-2x"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- روابط سريعة -->
        <div class="row">
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="fas fa-user-clock me-2"></i>
                            المستخدمين الجدد
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>الاسم</th>
                                        <th>البريد الإلكتروني</th>
                                        <th>الدور</th>
                                        <th>تاريخ الإنشاء</th>
                                        <th>الحالة</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($recentUsers ?? [] as $user)
                                        <tr>
                                            <td>{{ $user->name }}</td>
                                            <td>{{ $user->email }}</td>
                                            <td>
                                                <span class="badge bg-secondary">
                                                    {{ $user->user_role ?? $user->user_type ?? 'غير محدد' }}
                                                </span>
                                            </td>
                                            <td>{{ $user->created_at->format('Y-m-d') }}</td>
                                            <td>
                                                @if($user->is_account_active ?? true)
                                                    <span class="badge bg-success">نشط</span>
                                                @else
                                                    <span class="badge bg-danger">غير نشط</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="text-center text-muted">
                                                لا توجد مستخدمين جدد
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
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="fas fa-link me-2"></i>
                            روابط سريعة
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="d-grid gap-2">
                            <a href="{{ route('admin.users') }}" class="btn btn-outline-primary">
                                <i class="fas fa-users me-2"></i>
                                إدارة المستخدمين
                            </a>
                            <a href="{{ route('admin.licenses') }}" class="btn btn-outline-success">
                                <i class="fas fa-key me-2"></i>
                                إدارة التراخيص
                            </a>
                            <a href="{{ route('admin.warehouses') }}" class="btn btn-outline-info">
                                <i class="fas fa-warehouse me-2"></i>
                                إدارة المخازن
                            </a>
                            <a href="{{ route('admin.reports') }}" class="btn btn-outline-warning">
                                <i class="fas fa-chart-bar me-2"></i>
                                التقارير
                            </a>
                        </div>
                    </div>
                </div>

                <!-- معلومات المستخدم الحالي -->
                <div class="card mt-3">
                    <div class="card-header">
                        <h6 class="mb-0">
                            <i class="fas fa-user me-2"></i>
                            معلومات المستخدم
                        </h6>
                    </div>
                    <div class="card-body">
                        <p><strong>الاسم:</strong> {{ auth()->user()->name }}</p>
                        <p><strong>الإيميل:</strong> {{ auth()->user()->email }}</p>
                        <p><strong>الدور:</strong> {{ auth()->user()->user_role ?? auth()->user()->user_type ?? 'غير محدد' }}</p>
                        <p class="mb-0"><strong>الحالة:</strong> 
                            @if(auth()->user()->is_account_active ?? true)
                                <span class="badge bg-success">نشط</span>
                            @else
                                <span class="badge bg-danger">غير نشط</span>
                            @endif
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
