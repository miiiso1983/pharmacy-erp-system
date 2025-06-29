<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تأكيد مسح البيانات - نظام إدارة الصيدليات</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body {
            background: linear-gradient(135deg, #dc3545 0%, #c82333 100%);
            min-height: 100vh;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .cleanup-container {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(15px);
            border-radius: 20px;
            box-shadow: 0 25px 50px rgba(0, 0, 0, 0.15);
            border: 1px solid rgba(255, 255, 255, 0.2);
            margin: 20px 0;
        }
        .cleanup-header {
            background: linear-gradient(135deg, #dc3545, #c82333);
            color: white;
            padding: 30px;
            text-align: center;
            border-radius: 20px 20px 0 0;
        }
        .cleanup-icon {
            font-size: 4rem;
            margin-bottom: 20px;
            animation: pulse 2s infinite;
        }
        @keyframes pulse {
            0% { transform: scale(1); }
            50% { transform: scale(1.1); }
            100% { transform: scale(1); }
        }
        .cleanup-title {
            font-size: 2rem;
            font-weight: bold;
            margin-bottom: 10px;
        }
        .data-stats {
            background: rgba(248, 249, 250, 0.8);
            border-radius: 15px;
            padding: 20px;
            margin: 20px 0;
        }
        .stat-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px 0;
            border-bottom: 1px solid #dee2e6;
        }
        .stat-item:last-child {
            border-bottom: none;
        }
        .stat-count {
            font-weight: bold;
            font-size: 1.2rem;
        }
        .cleanup-type-card {
            background: white;
            border: 2px solid #e9ecef;
            border-radius: 15px;
            padding: 20px;
            margin-bottom: 15px;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        .cleanup-type-card:hover {
            border-color: #dc3545;
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(220, 53, 69, 0.15);
        }
        .cleanup-type-card.selected {
            border-color: #dc3545;
            background: rgba(220, 53, 69, 0.05);
        }
        .cleanup-type-card input[type="radio"] {
            position: absolute;
            opacity: 0;
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
        .confirmation-input {
            background: #fff;
            border: 2px solid #dc3545;
            border-radius: 10px;
            padding: 15px;
            font-size: 1.1rem;
            text-align: center;
            font-weight: bold;
            letter-spacing: 2px;
        }
        .confirmation-input:focus {
            border-color: #c82333;
            box-shadow: 0 0 0 0.2rem rgba(220, 53, 69, 0.25);
        }
    </style>
</head>
<body>
    <div class="container mt-4">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="cleanup-container">
                    <!-- Header -->
                    <div class="cleanup-header">
                        <div class="cleanup-icon">
                            <i class="fas fa-exclamation-triangle"></i>
                        </div>
                        <h1 class="cleanup-title">تحذير خطير!</h1>
                        <p class="mb-0">أنت على وشك حذف بيانات مهمة من النظام</p>
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

                        <!-- إحصائيات البيانات -->
                        <div class="alert alert-info">
                            <h5 class="alert-heading">
                                <i class="fas fa-chart-bar me-2"></i>
                                إحصائيات البيانات الحالية
                            </h5>
                            <div class="data-stats">
                                <div class="stat-item">
                                    <span><i class="fas fa-users text-primary me-2"></i>العملاء</span>
                                    <span class="stat-count text-primary">{{ number_format($dataStats['customers']) }}</span>
                                </div>
                                <div class="stat-item">
                                    <span><i class="fas fa-pills text-success me-2"></i>المنتجات</span>
                                    <span class="stat-count text-success">{{ number_format($dataStats['products']) }}</span>
                                </div>
                                <div class="stat-item">
                                    <span><i class="fas fa-shopping-cart text-warning me-2"></i>الطلبات</span>
                                    <span class="stat-count text-warning">{{ number_format($dataStats['orders']) }}</span>
                                </div>
                                <div class="stat-item">
                                    <span><i class="fas fa-file-invoice text-info me-2"></i>الفواتير</span>
                                    <span class="stat-count text-info">{{ number_format($dataStats['invoices']) }}</span>
                                </div>
                                <div class="stat-item">
                                    <span><i class="fas fa-boxes text-secondary me-2"></i>المخزون</span>
                                    <span class="stat-count text-secondary">{{ number_format($dataStats['inventory']) }}</span>
                                </div>
                            </div>
                        </div>

                        <!-- رسائل الخطأ -->
                        @if($errors->any())
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <h6 class="alert-heading"><i class="fas fa-exclamation-circle me-2"></i>يرجى تصحيح الأخطاء التالية:</h6>
                                <ul class="mb-0">
                                    @foreach($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        @endif

                        <!-- نموذج المسح -->
                        <form method="POST" action="{{ route('data.cleanup.execute') }}" id="cleanupForm">
                            @csrf

                            <!-- اختيار نوع المسح -->
                            <div class="mb-4">
                                <h5 class="mb-3">
                                    <i class="fas fa-trash-alt me-2 text-danger"></i>
                                    اختر نوع البيانات المراد حذفها
                                </h5>

                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <div class="cleanup-type-card" onclick="selectCleanupType('customers')">
                                            <input type="radio" name="cleanup_type" value="customers" id="customers" 
                                                   {{ old('cleanup_type', $cleanupType) === 'customers' ? 'checked' : '' }}>
                                            <div class="text-center">
                                                <i class="fas fa-users fa-2x text-primary mb-2"></i>
                                                <h6>العملاء فقط</h6>
                                                <small class="text-muted">حذف جميع بيانات العملاء</small>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <div class="cleanup-type-card" onclick="selectCleanupType('products')">
                                            <input type="radio" name="cleanup_type" value="products" id="products"
                                                   {{ old('cleanup_type', $cleanupType) === 'products' ? 'checked' : '' }}>
                                            <div class="text-center">
                                                <i class="fas fa-pills fa-2x text-success mb-2"></i>
                                                <h6>المنتجات فقط</h6>
                                                <small class="text-muted">حذف جميع المنتجات والأدوية</small>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <div class="cleanup-type-card" onclick="selectCleanupType('orders')">
                                            <input type="radio" name="cleanup_type" value="orders" id="orders"
                                                   {{ old('cleanup_type', $cleanupType) === 'orders' ? 'checked' : '' }}>
                                            <div class="text-center">
                                                <i class="fas fa-shopping-cart fa-2x text-warning mb-2"></i>
                                                <h6>الطلبات فقط</h6>
                                                <small class="text-muted">حذف جميع الطلبات</small>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <div class="cleanup-type-card" onclick="selectCleanupType('invoices')">
                                            <input type="radio" name="cleanup_type" value="invoices" id="invoices"
                                                   {{ old('cleanup_type', $cleanupType) === 'invoices' ? 'checked' : '' }}>
                                            <div class="text-center">
                                                <i class="fas fa-file-invoice fa-2x text-info mb-2"></i>
                                                <h6>الفواتير فقط</h6>
                                                <small class="text-muted">حذف جميع الفواتير</small>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12 mb-3">
                                        <div class="cleanup-type-card" onclick="selectCleanupType('all')">
                                            <input type="radio" name="cleanup_type" value="all" id="all"
                                                   {{ old('cleanup_type', $cleanupType) === 'all' ? 'checked' : '' }}>
                                            <div class="text-center">
                                                <i class="fas fa-trash-alt fa-2x text-danger mb-2"></i>
                                                <h6 class="text-danger">حذف جميع البيانات</h6>
                                                <small class="text-muted">حذف العملاء والمنتجات والطلبات والفواتير والمخزون</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- خيارات إضافية -->
                            <div class="mb-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="backup_before_cleanup" 
                                           name="backup_before_cleanup" value="1" {{ old('backup_before_cleanup') ? 'checked' : '' }}>
                                    <label class="form-check-label fw-bold text-success" for="backup_before_cleanup">
                                        <i class="fas fa-download me-2"></i>
                                        إنشاء نسخة احتياطية قبل المسح (مستحسن)
                                    </label>
                                </div>
                            </div>

                            <!-- تأكيد المسح -->
                            <div class="alert alert-danger">
                                <h6 class="alert-heading">
                                    <i class="fas fa-exclamation-triangle me-2"></i>
                                    تأكيد المسح
                                </h6>
                                <p class="mb-3">لتأكيد عملية المسح، يرجى كتابة <strong>"DELETE"</strong> في الحقل أدناه:</p>
                                <input type="text" class="form-control confirmation-input @error('confirmation_text') is-invalid @enderror" 
                                       id="confirmation_text" name="confirmation_text" 
                                       placeholder="اكتب DELETE للتأكيد" required>
                                @error('confirmation_text')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- أزرار الإجراء -->
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <button type="submit" class="btn btn-danger-custom w-100" id="cleanupBtn" disabled>
                                        <i class="fas fa-trash-alt me-2"></i>
                                        تأكيد المسح
                                    </button>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <a href="{{ route('backup.index') }}" class="btn btn-safe w-100">
                                        <i class="fas fa-arrow-left me-2"></i>
                                        إلغاء والعودة
                                    </a>
                                </div>
                            </div>
                        </form>

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
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        function selectCleanupType(type) {
            // إزالة التحديد من جميع البطاقات
            document.querySelectorAll('.cleanup-type-card').forEach(card => {
                card.classList.remove('selected');
            });
            
            // تحديد البطاقة المختارة
            event.currentTarget.classList.add('selected');
            
            // تحديد الراديو بوتن
            document.getElementById(type).checked = true;
            
            // تحديث نص الزر
            updateButtonText(type);
            checkConfirmation();
        }

        function updateButtonText(type) {
            const cleanupBtn = document.getElementById('cleanupBtn');
            const messages = {
                'customers': 'حذف العملاء',
                'products': 'حذف المنتجات',
                'orders': 'حذف الطلبات',
                'invoices': 'حذف الفواتير',
                'all': 'حذف جميع البيانات'
            };
            
            cleanupBtn.innerHTML = `<i class="fas fa-trash-alt me-2"></i>${messages[type] || 'تأكيد المسح'}`;
        }

        function checkConfirmation() {
            const confirmationText = document.getElementById('confirmation_text').value;
            const cleanupBtn = document.getElementById('cleanupBtn');
            const selectedType = document.querySelector('input[name="cleanup_type"]:checked');
            
            cleanupBtn.disabled = !(confirmationText.toUpperCase() === 'DELETE' && selectedType);
        }

        // تحديد النوع المختار عند تحميل الصفحة
        document.addEventListener('DOMContentLoaded', function() {
            const selectedType = document.querySelector('input[name="cleanup_type"]:checked');
            if (selectedType) {
                selectCleanupType(selectedType.value);
            }
            
            // مراقبة تغيير نص التأكيد
            document.getElementById('confirmation_text').addEventListener('input', checkConfirmation);
        });

        // تأكيد نهائي قبل الإرسال
        document.getElementById('cleanupForm').addEventListener('submit', function(e) {
            const selectedType = document.querySelector('input[name="cleanup_type"]:checked').value;
            const typeNames = {
                'customers': 'العملاء',
                'products': 'المنتجات',
                'orders': 'الطلبات',
                'invoices': 'الفواتير',
                'all': 'جميع البيانات'
            };
            
            const finalConfirm = confirm(
                `تأكيد نهائي: هل أنت متأكد تماماً من حذف ${typeNames[selectedType]}؟\n\n` +
                'هذا الإجراء لا يمكن التراجع عنه!'
            );
            
            if (!finalConfirm) {
                e.preventDefault();
                return false;
            }
            
            // تغيير نص الزر أثناء المعالجة
            const cleanupBtn = document.getElementById('cleanupBtn');
            cleanupBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>جاري الحذف...';
            cleanupBtn.disabled = true;
        });
    </script>
</body>
</html>
