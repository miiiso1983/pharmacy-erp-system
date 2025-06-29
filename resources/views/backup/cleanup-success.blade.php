<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تم المسح بنجاح - نظام إدارة الصيدليات</title>
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
        .btn-primary-custom {
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
        .btn-primary-custom:hover {
            transform: translateY(-3px);
            box-shadow: 0 15px 30px rgba(102, 126, 234, 0.4);
            color: white;
        }
        .cleanup-summary {
            background: rgba(248, 249, 250, 0.8);
            border-radius: 15px;
            padding: 25px;
            margin: 25px 0;
            text-align: right;
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
            <h1 class="success-title">تم المسح بنجاح!</h1>
            <p class="mb-0 fs-5">تم حذف البيانات المحددة بنجاح</p>
        </div>

        <div class="p-4">
            <!-- ملخص المسح -->
            <div class="cleanup-summary">
                <h5 class="text-success mb-4">
                    <i class="fas fa-clipboard-check me-2"></i>
                    ملخص عملية المسح
                </h5>
                
                <div class="row">
                    <div class="col-12">
                        <p><strong>نوع المسح:</strong> 
                            @if(session('cleanup_type') === 'customers')
                                <span class="badge bg-primary">العملاء</span>
                            @elseif(session('cleanup_type') === 'products')
                                <span class="badge bg-success">المنتجات</span>
                            @elseif(session('cleanup_type') === 'orders')
                                <span class="badge bg-warning">الطلبات</span>
                            @elseif(session('cleanup_type') === 'invoices')
                                <span class="badge bg-info">الفواتير</span>
                            @elseif(session('cleanup_type') === 'all')
                                <span class="badge bg-danger">جميع البيانات</span>
                            @else
                                <span class="badge bg-secondary">غير محدد</span>
                            @endif
                        </p>
                        <p><strong>تاريخ المسح:</strong> {{ now()->format('Y-m-d H:i:s') }}</p>
                        <p><strong>المستخدم:</strong> {{ auth()->user()->name ?? 'غير محدد' }}</p>
                    </div>
                </div>
            </div>

            <!-- إحصائيات المسح -->
            @if(session('deleted_counts'))
                <div class="alert alert-success">
                    <h6 class="alert-heading">
                        <i class="fas fa-chart-bar me-2"></i>
                        إحصائيات المسح
                    </h6>
                    <ul class="list-unstyled mb-0">
                        @foreach(session('deleted_counts') as $type => $count)
                            <li class="mb-1">
                                <i class="fas fa-check text-success me-2"></i>
                                <strong>
                                    @if($type === 'customers')
                                        العملاء:
                                    @elseif($type === 'products')
                                        المنتجات:
                                    @elseif($type === 'orders')
                                        الطلبات:
                                    @elseif($type === 'invoices')
                                        الفواتير:
                                    @elseif($type === 'inventory')
                                        المخزون:
                                    @else
                                        {{ $type }}:
                                    @endif
                                </strong>
                                {{ number_format($count) }} عنصر محذوف
                            </li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- تنبيهات مهمة -->
            <div class="alert alert-warning">
                <h6 class="alert-heading">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    تنبيهات مهمة
                </h6>
                <ul class="mb-0">
                    <li><strong>البيانات محذوفة نهائياً:</strong> لا يمكن استرجاع البيانات المحذوفة</li>
                    <li><strong>النسخ الاحتياطية:</strong> تأكد من وجود نسخة احتياطية حديثة</li>
                    <li><strong>إعادة الإدخال:</strong> يمكنك الآن إدخال بيانات جديدة</li>
                    <li><strong>التقارير:</strong> قد تحتاج لإعادة إنشاء التقارير</li>
                </ul>
            </div>

            <!-- الخطوات التالية -->
            <div class="alert alert-info">
                <h6 class="alert-heading">
                    <i class="fas fa-route me-2"></i>
                    الخطوات التالية المقترحة
                </h6>
                <ul class="mb-0">
                    <li><i class="fas fa-plus text-primary me-2"></i>إدخال بيانات جديدة</li>
                    <li><i class="fas fa-download text-success me-2"></i>إنشاء نسخة احتياطية جديدة</li>
                    <li><i class="fas fa-chart-line text-info me-2"></i>مراجعة التقارير والإحصائيات</li>
                    <li><i class="fas fa-cog text-warning me-2"></i>تحديث إعدادات النظام</li>
                </ul>
            </div>

            <!-- أزرار الإجراء -->
            <div class="text-center">
                <a href="{{ route('backup.index') }}" class="btn-primary-custom me-3">
                    <i class="fas fa-arrow-left me-2"></i>
                    العودة للنسخ الاحتياطية
                </a>
                
                <div class="mt-3">
                    <a href="{{ route('dashboard') }}" class="btn btn-outline-primary me-2">
                        <i class="fas fa-tachometer-alt me-1"></i>
                        لوحة التحكم
                    </a>
                    <button class="btn btn-outline-secondary" onclick="window.print()">
                        <i class="fas fa-print me-1"></i>
                        طباعة الملخص
                    </button>
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

            <!-- رسالة تحفيزية -->
            <div class="mt-4 p-3" style="background: linear-gradient(135deg, rgba(40, 167, 69, 0.1), rgba(32, 201, 151, 0.1)); border-radius: 10px;">
                <h6 class="text-success">
                    <i class="fas fa-thumbs-up me-2"></i>
                    عملية ناجحة!
                </h6>
                <p class="mb-0 small">
                    تم تنظيف البيانات بنجاح. نظامك الآن نظيف وجاهز لاستقبال بيانات جديدة.
                    نتمنى لك تجربة ممتازة!
                </p>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        // تأثير الاحتفال
        document.addEventListener('DOMContentLoaded', function() {
            console.log('🎉 تم مسح البيانات بنجاح! 🎉');
        });

        // توجيه تلقائي بعد 15 ثانية (اختياري)
        // setTimeout(() => {
        //     window.location.href = "{{ route('backup.index') }}";
        // }, 15000);
    </script>
</body>
</html>
