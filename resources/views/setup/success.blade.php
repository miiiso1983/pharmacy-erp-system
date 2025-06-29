<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تم الإعداد بنجاح - نظام إدارة الصيدليات</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body {
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .success-container {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(15px);
            border-radius: 20px;
            box-shadow: 0 25px 50px rgba(0, 0, 0, 0.15);
            border: 1px solid rgba(255, 255, 255, 0.2);
            max-width: 600px;
            width: 100%;
            margin: 20px;
            text-align: center;
        }
        .success-header {
            background: linear-gradient(135deg, #28a745, #20c997);
            color: white;
            padding: 40px;
            border-radius: 20px 20px 0 0;
        }
        .success-icon {
            font-size: 5rem;
            margin-bottom: 20px;
            animation: bounce 2s infinite;
        }
        @keyframes bounce {
            0%, 20%, 50%, 80%, 100% { transform: translateY(0); }
            40% { transform: translateY(-30px); }
            60% { transform: translateY(-15px); }
        }
        .success-title {
            font-size: 2.5rem;
            font-weight: bold;
            margin-bottom: 10px;
        }
        .btn-dashboard {
            background: linear-gradient(135deg, #667eea, #764ba2);
            border: none;
            border-radius: 25px;
            padding: 15px 40px;
            font-size: 1.2rem;
            font-weight: bold;
            color: white;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-block;
        }
        .btn-dashboard:hover {
            transform: translateY(-3px);
            box-shadow: 0 15px 30px rgba(102, 126, 234, 0.4);
            color: white;
        }
        .setup-summary {
            background: rgba(248, 249, 250, 0.8);
            border-radius: 15px;
            padding: 25px;
            margin: 25px 0;
            text-align: right;
        }
        .feature-list {
            list-style: none;
            padding: 0;
        }
        .feature-list li {
            padding: 8px 0;
            border-bottom: 1px solid #e9ecef;
        }
        .feature-list li:last-child {
            border-bottom: none;
        }
        .feature-list i {
            color: #28a745;
            margin-left: 10px;
        }
    </style>
</head>
<body>
    <div class="success-container">
        <!-- Header -->
        <div class="success-header">
            <div class="success-icon">
                <i class="fas fa-check-circle"></i>
            </div>
            <h1 class="success-title">تم الإعداد بنجاح!</h1>
            <p class="mb-0 fs-5">مرحباً بك في نظام إدارة الصيدليات</p>
        </div>

        <div class="p-4">
            <!-- ملخص الإعداد -->
            <div class="setup-summary">
                <h5 class="text-success mb-4">
                    <i class="fas fa-clipboard-check me-2"></i>
                    ملخص الإعداد
                </h5>
                
                <div class="row">
                    <div class="col-md-6">
                        <p><strong>اسم المدير:</strong> {{ session('setup_admin_name', 'غير محدد') }}</p>
                        <p><strong>اسم الشركة:</strong> {{ session('setup_company_name', 'غير محدد') }}</p>
                    </div>
                    <div class="col-md-6">
                        <p><strong>نوع الإعداد:</strong> 
                            @if(session('setup_type') === 'reset')
                                <span class="badge bg-warning">إعادة تعيين</span>
                            @else
                                <span class="badge bg-success">إعداد جديد</span>
                            @endif
                        </p>
                        <p><strong>تاريخ الإعداد:</strong> {{ now()->format('Y-m-d H:i') }}</p>
                    </div>
                </div>
            </div>

            <!-- ما تم إنشاؤه -->
            <div class="alert alert-success">
                <h6 class="alert-heading">
                    <i class="fas fa-magic me-2"></i>
                    ما تم إنشاؤه لك
                </h6>
                <ul class="feature-list">
                    <li><i class="fas fa-user-shield"></i>حساب مدير النظام مع صلاحيات كاملة</li>
                    <li><i class="fas fa-warehouse"></i>المخزن الرئيسي جاهز للاستخدام</li>
                    <li><i class="fas fa-cog"></i>إعدادات النظام الأساسية</li>
                    <li><i class="fas fa-shield-alt"></i>ربط آمن مع الترخيص المفعل</li>
                    <li><i class="fas fa-chart-line"></i>نظام مراقبة الاستخدام</li>
                </ul>
            </div>

            <!-- الخطوات التالية -->
            <div class="alert alert-info">
                <h6 class="alert-heading">
                    <i class="fas fa-route me-2"></i>
                    الخطوات التالية
                </h6>
                <ul class="feature-list">
                    <li><i class="fas fa-users"></i>إضافة المستخدمين والموظفين</li>
                    <li><i class="fas fa-pills"></i>إدخال المنتجات والأدوية</li>
                    <li><i class="fas fa-truck"></i>إضافة الموردين</li>
                    <li><i class="fas fa-users-cog"></i>تخصيص الصلاحيات</li>
                    <li><i class="fas fa-chart-bar"></i>مراجعة التقارير والإحصائيات</li>
                </ul>
            </div>

            <!-- أزرار الإجراء -->
            <div class="text-center">
                <a href="{{ route('dashboard') }}" class="btn-dashboard">
                    <i class="fas fa-tachometer-alt me-2"></i>
                    الذهاب إلى لوحة التحكم
                </a>
                
                <div class="mt-3">
                    <a href="{{ route('license.info') }}" class="btn btn-outline-info me-2">
                        <i class="fas fa-info-circle me-1"></i>
                        معلومات الترخيص
                    </a>
                    <a href="#" class="btn btn-outline-secondary" onclick="window.print()">
                        <i class="fas fa-print me-1"></i>
                        طباعة الملخص
                    </a>
                </div>
            </div>

            <!-- معلومات الدعم -->
            <div class="text-center mt-4">
                <small class="text-muted">
                    <i class="fas fa-headset me-1"></i>
                    للمساعدة والدعم الفني: 
                    <strong>07700000000</strong> | 
                    <strong>support@pharmacy-system.com</strong>
                </small>
            </div>

            <!-- رسالة ترحيب -->
            <div class="mt-4 p-3" style="background: linear-gradient(135deg, rgba(102, 126, 234, 0.1), rgba(118, 75, 162, 0.1)); border-radius: 10px;">
                <h6 class="text-primary">
                    <i class="fas fa-heart me-2"></i>
                    مرحباً بك في عائلة نظام إدارة الصيدليات
                </h6>
                <p class="mb-0 small">
                    نحن سعداء لانضمامك إلينا. نظامك جاهز الآن لإدارة جميع عمليات الصيدلية بكفاءة وأمان.
                    نتمنى لك تجربة ممتازة!
                </p>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        // تأثير الاحتفال
        document.addEventListener('DOMContentLoaded', function() {
            // إضافة كونفيتي أو تأثيرات احتفالية
            setTimeout(() => {
                console.log('🎉 تم إعداد النظام بنجاح! 🎉');
            }, 1000);
        });

        // توجيه تلقائي بعد 10 ثوان (اختياري)
        // setTimeout(() => {
        //     window.location.href = "{{ route('dashboard') }}";
        // }, 10000);
    </script>
</body>
</html>
