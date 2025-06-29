<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تأكيد إعادة التعيين - نظام إدارة الصيدليات</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body {
            background: linear-gradient(135deg, #dc3545 0%, #c82333 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .warning-container {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(15px);
            border-radius: 20px;
            box-shadow: 0 25px 50px rgba(0, 0, 0, 0.15);
            border: 1px solid rgba(255, 255, 255, 0.2);
            max-width: 600px;
            width: 100%;
            margin: 20px;
        }
        .warning-header {
            background: linear-gradient(135deg, #dc3545, #c82333);
            color: white;
            padding: 30px;
            text-align: center;
            border-radius: 20px 20px 0 0;
        }
        .warning-icon {
            font-size: 4rem;
            margin-bottom: 20px;
            animation: pulse 2s infinite;
        }
        @keyframes pulse {
            0% { transform: scale(1); }
            50% { transform: scale(1.1); }
            100% { transform: scale(1); }
        }
        .warning-title {
            font-size: 2rem;
            font-weight: bold;
            margin-bottom: 10px;
        }
        .data-list {
            background: rgba(248, 249, 250, 0.8);
            border-radius: 10px;
            padding: 20px;
            margin: 20px 0;
        }
        .data-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px 0;
            border-bottom: 1px solid #dee2e6;
        }
        .data-item:last-child {
            border-bottom: none;
        }
        .btn-danger-custom {
            background: linear-gradient(135deg, #dc3545, #c82333);
            border: none;
            border-radius: 25px;
            padding: 15px 30px;
            font-weight: bold;
            color: white;
            transition: all 0.3s ease;
        }
        .btn-danger-custom:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(220, 53, 69, 0.4);
            color: white;
        }
        .btn-safe {
            background: linear-gradient(135deg, #28a745, #20c997);
            border: none;
            border-radius: 25px;
            padding: 15px 30px;
            font-weight: bold;
            color: white;
            transition: all 0.3s ease;
        }
        .btn-safe:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(40, 167, 69, 0.4);
            color: white;
        }
    </style>
</head>
<body>
    <div class="warning-container">
        <!-- Header -->
        <div class="warning-header">
            <div class="warning-icon">
                <i class="fas fa-exclamation-triangle"></i>
            </div>
            <h1 class="warning-title">تحذير خطير!</h1>
            <p class="mb-0">أنت على وشك حذف جميع البيانات</p>
        </div>

        <div class="p-4">
            <!-- معلومات الترخيص -->
            <div class="alert alert-warning">
                <h5 class="alert-heading">
                    <i class="fas fa-certificate me-2"></i>
                    معلومات الترخيص
                </h5>
                <p><strong>العميل:</strong> {{ $currentLicense->client_name }}</p>
                <p><strong>مفتاح الترخيص:</strong> <code>{{ $currentLicense->license_key }}</code></p>
                <p class="mb-0"><strong>نوع الترخيص:</strong> 
                    <span class="badge bg-primary">{{ $currentLicense->license_type }}</span>
                </p>
            </div>

            @if($hasExistingSetup)
                <!-- البيانات التي سيتم حذفها -->
                <div class="alert alert-danger">
                    <h5 class="alert-heading">
                        <i class="fas fa-trash-alt me-2"></i>
                        البيانات التي سيتم حذفها نهائياً
                    </h5>
                    <div class="data-list">
                        <div class="data-item">
                            <span><i class="fas fa-users text-danger me-2"></i>جميع المستخدمين</span>
                            <span class="badge bg-danger">حذف نهائي</span>
                        </div>
                        <div class="data-item">
                            <span><i class="fas fa-warehouse text-danger me-2"></i>جميع المخازن</span>
                            <span class="badge bg-danger">حذف نهائي</span>
                        </div>
                        <div class="data-item">
                            <span><i class="fas fa-pills text-danger me-2"></i>جميع المنتجات والأدوية</span>
                            <span class="badge bg-danger">حذف نهائي</span>
                        </div>
                        <div class="data-item">
                            <span><i class="fas fa-shopping-cart text-danger me-2"></i>جميع الطلبات والمبيعات</span>
                            <span class="badge bg-danger">حذف نهائي</span>
                        </div>
                        <div class="data-item">
                            <span><i class="fas fa-file-invoice text-danger me-2"></i>جميع الفواتير</span>
                            <span class="badge bg-danger">حذف نهائي</span>
                        </div>
                        <div class="data-item">
                            <span><i class="fas fa-chart-bar text-danger me-2"></i>جميع التقارير والإحصائيات</span>
                            <span class="badge bg-danger">حذف نهائي</span>
                        </div>
                        <div class="data-item">
                            <span><i class="fas fa-cog text-danger me-2"></i>جميع الإعدادات المخصصة</span>
                            <span class="badge bg-danger">حذف نهائي</span>
                        </div>
                    </div>
                </div>

                <!-- تحذيرات إضافية -->
                <div class="alert alert-warning">
                    <h6 class="alert-heading">
                        <i class="fas fa-exclamation-circle me-2"></i>
                        تحذيرات مهمة
                    </h6>
                    <ul class="mb-0">
                        <li><strong>لا يمكن التراجع:</strong> هذه العملية لا يمكن التراجع عنها</li>
                        <li><strong>فقدان البيانات:</strong> ستفقد جميع البيانات المدخلة سابقاً</li>
                        <li><strong>إعادة الإعداد:</strong> ستحتاج لإعادة إدخال جميع البيانات من البداية</li>
                        <li><strong>توقف الخدمة:</strong> قد يتوقف النظام مؤقتاً أثناء إعادة التعيين</li>
                    </ul>
                </div>

                <!-- نموذج التأكيد -->
                <form method="POST" action="{{ route('setup.process') }}" id="resetForm">
                    @csrf
                    <input type="hidden" name="setup_type" value="reset">
                    
                    <div class="form-check mb-4">
                        <input class="form-check-input" type="checkbox" id="confirmReset" required>
                        <label class="form-check-label fw-bold text-danger" for="confirmReset">
                            أؤكد أنني أفهم أن جميع البيانات ستحذف نهائياً ولا يمكن استرجاعها
                        </label>
                    </div>

                    <div class="form-check mb-4">
                        <input class="form-check-input" type="checkbox" id="confirmBackup" required>
                        <label class="form-check-label fw-bold text-warning" for="confirmBackup">
                            أؤكد أنني قمت بأخذ نسخة احتياطية من البيانات المهمة (إن وجدت)
                        </label>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <button type="submit" class="btn btn-danger-custom w-100" id="resetBtn" disabled>
                                <i class="fas fa-trash-alt me-2"></i>
                                تأكيد إعادة التعيين
                            </button>
                        </div>
                        <div class="col-md-6 mb-3">
                            <a href="{{ route('setup.initial') }}" class="btn btn-safe w-100">
                                <i class="fas fa-arrow-left me-2"></i>
                                إلغاء والعودة
                            </a>
                        </div>
                    </div>
                </form>
            @else
                <!-- لا توجد بيانات للحذف -->
                <div class="alert alert-info">
                    <h5 class="alert-heading">
                        <i class="fas fa-info-circle me-2"></i>
                        لا توجد بيانات للحذف
                    </h5>
                    <p class="mb-0">لا توجد بيانات سابقة مرتبطة بهذا الترخيص. يمكنك المتابعة مباشرة لإعداد النظام.</p>
                </div>

                <div class="text-center">
                    <a href="{{ route('setup.initial') }}" class="btn btn-safe">
                        <i class="fas fa-arrow-left me-2"></i>
                        العودة للإعداد
                    </a>
                </div>
            @endif

            <!-- معلومات الدعم -->
            <div class="text-center mt-4">
                <small class="text-muted">
                    <i class="fas fa-headset me-1"></i>
                    للمساعدة أو الاستفسار: 
                    <strong>07700000000</strong> | 
                    <strong>support@pharmacy-system.com</strong>
                </small>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        // تفعيل زر إعادة التعيين عند تأكيد جميع الشروط
        function checkConfirmations() {
            const confirmReset = document.getElementById('confirmReset');
            const confirmBackup = document.getElementById('confirmBackup');
            const resetBtn = document.getElementById('resetBtn');
            
            if (confirmReset && confirmBackup) {
                resetBtn.disabled = !(confirmReset.checked && confirmBackup.checked);
            }
        }

        // إضافة مستمعين للأحداث
        document.addEventListener('DOMContentLoaded', function() {
            const confirmReset = document.getElementById('confirmReset');
            const confirmBackup = document.getElementById('confirmBackup');
            
            if (confirmReset) {
                confirmReset.addEventListener('change', checkConfirmations);
            }
            
            if (confirmBackup) {
                confirmBackup.addEventListener('change', checkConfirmations);
            }
        });

        // تأكيد نهائي قبل الإرسال
        document.getElementById('resetForm')?.addEventListener('submit', function(e) {
            const finalConfirm = confirm(
                'تأكيد نهائي: هل أنت متأكد تماماً من حذف جميع البيانات؟\n\n' +
                'هذا الإجراء لا يمكن التراجع عنه!'
            );
            
            if (!finalConfirm) {
                e.preventDefault();
                return false;
            }
            
            // تغيير نص الزر أثناء المعالجة
            const resetBtn = document.getElementById('resetBtn');
            resetBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>جاري الحذف...';
            resetBtn.disabled = true;
        });
    </script>
</body>
</html>
