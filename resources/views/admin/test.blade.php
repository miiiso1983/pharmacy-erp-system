<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>اختبار لوحة التحكم الإدارية</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header bg-success text-white">
                        <h4 class="mb-0">
                            <i class="fas fa-check-circle me-2"></i>
                            تم إصلاح لوحة التحكم الإدارية بنجاح!
                        </h4>
                    </div>
                    <div class="card-body">
                        <div class="alert alert-success">
                            <h5><i class="fas fa-info-circle me-2"></i>معلومات تسجيل الدخول:</h5>
                            <ul class="mb-0">
                                <li><strong>الإيميل:</strong> system.admin@pharmacy.com</li>
                                <li><strong>كلمة المرور:</strong> admin123456</li>
                                <li><strong>الدور:</strong> super_admin</li>
                            </ul>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <h6>الروابط المتاحة:</h6>
                                <ul class="list-group">
                                    <li class="list-group-item">
                                        <a href="{{ route('admin.dashboard') }}" class="text-decoration-none">
                                            <i class="fas fa-tachometer-alt me-2"></i>
                                            لوحة التحكم الإدارية
                                        </a>
                                    </li>
                                    <li class="list-group-item">
                                        <a href="{{ route('admin.users') }}" class="text-decoration-none">
                                            <i class="fas fa-users me-2"></i>
                                            إدارة المستخدمين
                                        </a>
                                    </li>
                                    <li class="list-group-item">
                                        <a href="{{ route('admin.licenses') }}" class="text-decoration-none">
                                            <i class="fas fa-key me-2"></i>
                                            إدارة التراخيص
                                        </a>
                                    </li>
                                    <li class="list-group-item">
                                        <a href="{{ route('admin.warehouses') }}" class="text-decoration-none">
                                            <i class="fas fa-warehouse me-2"></i>
                                            إدارة المخازن
                                        </a>
                                    </li>
                                </ul>
                            </div>
                            <div class="col-md-6">
                                <h6>المميزات المطبقة:</h6>
                                <ul class="list-group">
                                    <li class="list-group-item">
                                        <i class="fas fa-check text-success me-2"></i>
                                        إدارة المستخدمين مع التصنيفات
                                    </li>
                                    <li class="list-group-item">
                                        <i class="fas fa-check text-success me-2"></i>
                                        مدة صلاحية للمستخدمين
                                    </li>
                                    <li class="list-group-item">
                                        <i class="fas fa-check text-success me-2"></i>
                                        إدارة التراخيص والصلاحيات
                                    </li>
                                    <li class="list-group-item">
                                        <i class="fas fa-check text-success me-2"></i>
                                        إنشاء وإدارة المخازن
                                    </li>
                                    <li class="list-group-item">
                                        <i class="fas fa-check text-success me-2"></i>
                                        واجهة رسومية مع إحصائيات
                                    </li>
                                </ul>
                            </div>
                        </div>

                        <div class="mt-4">
                            <h6>معلومات المستخدم الحالي:</h6>
                            @auth
                                <div class="alert alert-info">
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
                            @else
                                <div class="alert alert-warning">
                                    <p class="mb-0">لم تسجل دخول بعد. <a href="{{ route('login') }}">سجل دخول الآن</a></p>
                                </div>
                            @endauth
                        </div>

                        <div class="text-center mt-4">
                            @auth
                                <a href="{{ route('admin.dashboard') }}" class="btn btn-primary btn-lg">
                                    <i class="fas fa-arrow-right me-2"></i>
                                    الذهاب إلى لوحة التحكم
                                </a>
                            @else
                                <a href="{{ route('login') }}" class="btn btn-success btn-lg">
                                    <i class="fas fa-sign-in-alt me-2"></i>
                                    تسجيل الدخول
                                </a>
                            @endauth
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
