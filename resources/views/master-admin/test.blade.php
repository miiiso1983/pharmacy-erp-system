<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Master Admin - اختبار النظام</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
        }
        .master-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }
        .master-badge {
            background: linear-gradient(45deg, #ff6b6b, #ee5a24);
            color: white;
            padding: 8px 20px;
            border-radius: 50px;
            font-weight: bold;
            display: inline-block;
            box-shadow: 0 4px 15px rgba(238, 90, 36, 0.3);
        }
        .feature-card {
            background: rgba(255, 255, 255, 0.8);
            border-radius: 15px;
            padding: 20px;
            margin-bottom: 20px;
            border: 1px solid rgba(255, 255, 255, 0.3);
            transition: all 0.3s ease;
        }
        .feature-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        }
        .btn-master {
            background: linear-gradient(45deg, #667eea, #764ba2);
            border: none;
            border-radius: 50px;
            padding: 12px 30px;
            font-weight: bold;
            transition: all 0.3s ease;
        }
        .btn-master:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(102, 126, 234, 0.3);
        }
    </style>
</head>
<body>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="master-card">
                    <div class="card-body p-5">
                        <!-- Header -->
                        <div class="text-center mb-5">
                            <div class="master-badge mb-3">
                                <i class="fas fa-crown me-2"></i>
                                MASTER ADMIN SYSTEM
                            </div>
                            <h2 class="fw-bold text-dark">نظام Master Admin معزول تماماً</h2>
                            <p class="text-muted lead">إدارة التراخيص وحدود المستخدمين لجميع العملاء</p>
                        </div>

                        <!-- معلومات النظام -->
                        <div class="row mb-5">
                            <div class="col-md-6">
                                <div class="feature-card">
                                    <h5 class="text-primary">
                                        <i class="fas fa-database me-2"></i>
                                        قاعدة البيانات
                                    </h5>
                                    <ul class="list-unstyled">
                                        <li><i class="fas fa-check text-success me-2"></i>جدول Master Admins منفصل</li>
                                        <li><i class="fas fa-check text-success me-2"></i>جدول System Licenses</li>
                                        <li><i class="fas fa-check text-success me-2"></i>جدول License Usage</li>
                                        <li><i class="fas fa-check text-success me-2"></i>نظام مصادقة منفصل</li>
                                    </ul>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="feature-card">
                                    <h5 class="text-success">
                                        <i class="fas fa-shield-alt me-2"></i>
                                        الأمان والعزل
                                    </h5>
                                    <ul class="list-unstyled">
                                        <li><i class="fas fa-check text-success me-2"></i>Guard منفصل للمصادقة</li>
                                        <li><i class="fas fa-check text-success me-2"></i>Middleware مخصص</li>
                                        <li><i class="fas fa-check text-success me-2"></i>Routes منفصلة تماماً</li>
                                        <li><i class="fas fa-check text-success me-2"></i>Controllers معزولة</li>
                                    </ul>
                                </div>
                            </div>
                        </div>

                        <!-- المميزات الرئيسية -->
                        <div class="row mb-5">
                            <div class="col-12">
                                <h4 class="text-center mb-4">
                                    <i class="fas fa-star me-2"></i>
                                    المميزات الرئيسية
                                </h4>
                            </div>
                            <div class="col-md-4">
                                <div class="feature-card text-center">
                                    <i class="fas fa-key fa-3x text-primary mb-3"></i>
                                    <h6>إدارة التراخيص</h6>
                                    <p class="small text-muted">إنشاء وإدارة تراخيص العملاء مع تحديد الحدود والمميزات</p>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="feature-card text-center">
                                    <i class="fas fa-users fa-3x text-success mb-3"></i>
                                    <h6>حدود المستخدمين</h6>
                                    <p class="small text-muted">تحديد عدد المستخدمين والمخازن والفروع لكل ترخيص</p>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="feature-card text-center">
                                    <i class="fas fa-chart-line fa-3x text-warning mb-3"></i>
                                    <h6>مراقبة الاستخدام</h6>
                                    <p class="small text-muted">تتبع استخدام كل ترخيص وإرسال تنبيهات عند تجاوز الحدود</p>
                                </div>
                            </div>
                        </div>

                        <!-- بيانات الدخول -->
                        <div class="row mb-4">
                            <div class="col-md-8 mx-auto">
                                <div class="alert alert-info">
                                    <h6 class="alert-heading">
                                        <i class="fas fa-info-circle me-2"></i>
                                        بيانات تسجيل الدخول
                                    </h6>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <p class="mb-1"><strong>الإيميل:</strong></p>
                                            <code>master@pharmacy-system.com</code>
                                        </div>
                                        <div class="col-md-6">
                                            <p class="mb-1"><strong>كلمة المرور:</strong></p>
                                            <code>master123456</code>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- الروابط -->
                        <div class="row">
                            <div class="col-12">
                                <h5 class="text-center mb-4">
                                    <i class="fas fa-link me-2"></i>
                                    الروابط المتاحة
                                </h5>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="d-grid">
                                    <a href="{{ route('master-admin.login') }}" class="btn btn-master btn-lg text-white">
                                        <i class="fas fa-sign-in-alt me-2"></i>
                                        تسجيل الدخول
                                    </a>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="d-grid">
                                    <a href="{{ route('master-admin.dashboard') }}" class="btn btn-outline-primary btn-lg">
                                        <i class="fas fa-tachometer-alt me-2"></i>
                                        لوحة التحكم
                                    </a>
                                </div>
                            </div>
                        </div>

                        <!-- معلومات تقنية -->
                        <div class="row mt-5">
                            <div class="col-12">
                                <div class="feature-card">
                                    <h6 class="text-dark mb-3">
                                        <i class="fas fa-cog me-2"></i>
                                        المعلومات التقنية
                                    </h6>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <ul class="list-unstyled small">
                                                <li><strong>Guard:</strong> master_admin</li>
                                                <li><strong>Provider:</strong> master_admins</li>
                                                <li><strong>Model:</strong> MasterAdmin</li>
                                                <li><strong>Middleware:</strong> master.admin</li>
                                            </ul>
                                        </div>
                                        <div class="col-md-6">
                                            <ul class="list-unstyled small">
                                                <li><strong>Routes File:</strong> master-admin.php</li>
                                                <li><strong>Controllers:</strong> MasterAdmin/</li>
                                                <li><strong>Views:</strong> master-admin/</li>
                                                <li><strong>Database:</strong> معزولة تماماً</li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Footer -->
                        <div class="text-center mt-4">
                            <small class="text-muted">
                                Master Admin Control Panel | نظام إدارة الصيدليات<br>
                                الإصدار 2.0 - معزول تماماً عن المشروع الأساسي | {{ date('Y') }}
                            </small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
