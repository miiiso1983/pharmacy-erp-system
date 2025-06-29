<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تفعيل الترخيص - نظام إدارة الصيدليات</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .license-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(15px);
            border-radius: 20px;
            box-shadow: 0 25px 50px rgba(0, 0, 0, 0.15);
            border: 1px solid rgba(255, 255, 255, 0.2);
            overflow: hidden;
        }
        .license-header {
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: white;
            padding: 30px;
            text-align: center;
            position: relative;
        }
        .license-header::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="grain" width="100" height="100" patternUnits="userSpaceOnUse"><circle cx="25" cy="25" r="1" fill="white" opacity="0.1"/><circle cx="75" cy="75" r="1" fill="white" opacity="0.1"/><circle cx="50" cy="10" r="0.5" fill="white" opacity="0.1"/><circle cx="10" cy="50" r="0.5" fill="white" opacity="0.1"/><circle cx="90" cy="30" r="0.5" fill="white" opacity="0.1"/></pattern></defs><rect width="100" height="100" fill="url(%23grain)"/></svg>');
        }
        .license-icon {
            font-size: 4rem;
            margin-bottom: 20px;
            position: relative;
            z-index: 1;
        }
        .license-title {
            font-size: 2rem;
            font-weight: bold;
            margin-bottom: 10px;
            position: relative;
            z-index: 1;
        }
        .license-subtitle {
            opacity: 0.9;
            position: relative;
            z-index: 1;
        }
        .license-body {
            padding: 40px;
        }
        .license-input {
            background: rgba(248, 249, 250, 0.8);
            border: 2px solid #e9ecef;
            border-radius: 15px;
            padding: 20px 25px;
            font-size: 1.3rem;
            text-align: center;
            letter-spacing: 3px;
            font-weight: bold;
            font-family: 'Courier New', monospace;
            transition: all 0.3s ease;
            text-transform: uppercase;
        }
        .license-input:focus {
            background: white;
            border-color: #667eea;
            box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
            transform: translateY(-2px);
            outline: none;
        }
        .license-input::placeholder {
            color: #adb5bd;
            font-weight: normal;
            letter-spacing: 2px;
        }
        .quick-fill-buttons {
            display: flex;
            gap: 10px;
            justify-content: center;
            margin-top: 15px;
            flex-wrap: wrap;
        }
        .quick-fill-btn {
            background: linear-gradient(45deg, #28a745, #20c997);
            color: white;
            border: none;
            border-radius: 20px;
            padding: 8px 15px;
            font-size: 0.9rem;
            cursor: pointer;
            transition: all 0.3s ease;
            font-weight: bold;
        }
        .quick-fill-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(40, 167, 69, 0.3);
        }
        .input-helper {
            background: rgba(255, 255, 255, 0.9);
            border-radius: 10px;
            padding: 15px;
            margin-top: 20px;
            border-left: 4px solid #17a2b8;
        }
        .format-example {
            background: #f8f9fa;
            border: 1px solid #dee2e6;
            border-radius: 8px;
            padding: 10px;
            font-family: 'Courier New', monospace;
            font-weight: bold;
            text-align: center;
            margin: 10px 0;
            color: #495057;
        }
        .copy-btn {
            background: #17a2b8;
            color: white;
            border: none;
            border-radius: 15px;
            padding: 5px 10px;
            font-size: 0.8rem;
            cursor: pointer;
            margin-right: 5px;
            transition: all 0.3s ease;
        }
        .copy-btn:hover {
            background: #138496;
            transform: scale(1.05);
        }
        .btn-activate {
            background: linear-gradient(135deg, #667eea, #764ba2);
            border: none;
            border-radius: 15px;
            padding: 15px 40px;
            font-size: 1.1rem;
            font-weight: bold;
            color: white;
            transition: all 0.3s ease;
            width: 100%;
        }
        .btn-activate:hover {
            transform: translateY(-3px);
            box-shadow: 0 15px 30px rgba(102, 126, 234, 0.4);
            color: white;
        }
        .current-license {
            background: rgba(40, 167, 69, 0.1);
            border: 2px solid rgba(40, 167, 69, 0.3);
            border-radius: 15px;
            padding: 20px;
            margin-bottom: 30px;
        }
        .license-key-display {
            background: #f8f9fa;
            border: 1px solid #dee2e6;
            border-radius: 8px;
            padding: 10px;
            font-family: 'Courier New', monospace;
            font-weight: bold;
            letter-spacing: 1px;
            text-align: center;
        }
        .feature-list {
            background: rgba(248, 249, 250, 0.5);
            border-radius: 10px;
            padding: 20px;
            margin-top: 20px;
        }
        .feature-item {
            display: flex;
            align-items: center;
            margin-bottom: 8px;
        }
        .feature-item i {
            color: #28a745;
            margin-left: 10px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-6 col-md-8">
                <div class="license-card">
                    <!-- Header -->
                    <div class="license-header">
                        <div class="license-icon">
                            <i class="fas fa-key"></i>
                        </div>
                        <h1 class="license-title">تفعيل الترخيص</h1>
                        <p class="license-subtitle">أدخل مفتاح الترخيص للوصول إلى النظام</p>
                    </div>

                    <div class="license-body">
                        <!-- الترخيص الحالي إذا كان موجود -->
                        @if($currentLicense)
                            <div class="current-license">
                                <h5 class="text-success mb-3">
                                    <i class="fas fa-check-circle me-2"></i>
                                    الترخيص مفعل حالياً
                                </h5>
                                <div class="row">
                                    <div class="col-md-6">
                                        <p><strong>العميل:</strong> {{ $currentLicense->client_name }}</p>
                                        <p><strong>النوع:</strong> 
                                            <span class="badge bg-primary">{{ $currentLicense->license_type }}</span>
                                        </p>
                                    </div>
                                    <div class="col-md-6">
                                        <p><strong>تاريخ الانتهاء:</strong> {{ $currentLicense->end_date->format('Y-m-d') }}</p>
                                        <p><strong>الحالة:</strong> 
                                            @if($currentLicense->end_date > now())
                                                <span class="badge bg-success">نشط</span>
                                            @else
                                                <span class="badge bg-danger">منتهي</span>
                                            @endif
                                        </p>
                                    </div>
                                </div>
                                <div class="license-key-display">
                                    {{ $currentLicense->license_key }}
                                </div>
                                <div class="mt-3">
                                    <a href="{{ route('license.info') }}" class="btn btn-outline-success me-2">
                                        <i class="fas fa-info-circle me-1"></i>
                                        عرض التفاصيل
                                    </a>
                                    <a href="{{ route('license.deactivate') }}" class="btn btn-outline-danger">
                                        <i class="fas fa-times me-1"></i>
                                        إلغاء التفعيل
                                    </a>
                                </div>
                            </div>
                        @endif

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

                        @if(session('warning'))
                            <div class="alert alert-warning alert-dismissible fade show" role="alert">
                                <i class="fas fa-exclamation-triangle me-2"></i>
                                {{ session('warning') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        @endif

                        @if($errors->any())
                            <div class="alert alert-danger">
                                <ul class="mb-0">
                                    @foreach($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <!-- نموذج إدخال مفتاح الترخيص -->
                        <form method="POST" action="{{ route('license.verify.submit') }}" id="licenseForm">
                            @csrf

                            <div class="mb-4">
                                <label for="license_key" class="form-label fw-bold">
                                    <i class="fas fa-key me-2 text-primary"></i>
                                    مفتاح الترخيص
                                </label>
                                <input type="text"
                                       class="form-control license-input @error('license_key') is-invalid @enderror"
                                       id="license_key"
                                       name="license_key"
                                       value="{{ old('license_key') }}"
                                       placeholder="PH-XXXX-XXXX-XXXX"
                                       maxlength="19"
                                       required
                                       autofocus
                                       autocomplete="off">
                                @error('license_key')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror

                                <!-- أزرار الملء السريع -->
                                <div class="quick-fill-buttons">
                                    <button type="button" class="quick-fill-btn" onclick="fillLicense('PH-TEST-2024-DEMO')">
                                        <i class="fas fa-flask me-1"></i>
                                        تجريبي
                                    </button>
                                    <button type="button" class="quick-fill-btn" onclick="fillLicense('PH-FULL-2024-PROD')">
                                        <i class="fas fa-star me-1"></i>
                                        كامل
                                    </button>
                                    <button type="button" class="quick-fill-btn" onclick="fillLicense('PH-BASIC-2024-STD')">
                                        <i class="fas fa-circle me-1"></i>
                                        أساسي
                                    </button>
                                    <button type="button" class="quick-fill-btn" onclick="fillLicense('PH-NEW-2024-SETUP')" style="background: linear-gradient(45deg, #17a2b8, #138496);">
                                        <i class="fas fa-plus me-1"></i>
                                        جديد
                                    </button>
                                    <button type="button" class="quick-fill-btn" onclick="fillLicense('PH-CLEAN-2024-TEST')" style="background: linear-gradient(45deg, #6f42c1, #5a32a3);">
                                        <i class="fas fa-broom me-1"></i>
                                        نظيف
                                    </button>
                                    <button type="button" class="quick-fill-btn" onclick="fillLicense('PH-FINAL-2024-WORK')" style="background: linear-gradient(45deg, #dc3545, #c82333);">
                                        <i class="fas fa-rocket me-1"></i>
                                        نهائي
                                    </button>
                                    <button type="button" class="copy-btn" onclick="pasteFromClipboard()">
                                        <i class="fas fa-paste me-1"></i>
                                        لصق
                                    </button>
                                    <button type="button" class="copy-btn" onclick="clearInput()">
                                        <i class="fas fa-eraser me-1"></i>
                                        مسح
                                    </button>
                                </div>

                                <div class="form-text text-center mt-2">
                                    أدخل مفتاح الترخيص أو استخدم الأزرار السريعة أعلاه
                                </div>
                            </div>

                            <!-- مساعد التنسيق -->
                            <div class="input-helper">
                                <h6 class="text-info mb-2">
                                    <i class="fas fa-info-circle me-2"></i>
                                    تنسيق مفتاح الترخيص
                                </h6>
                                <p class="mb-2 small">يجب أن يكون مفتاح الترخيص بالتنسيق التالي:</p>
                                <div class="format-example">
                                    PH-XXXX-XXXX-XXXX
                                </div>
                                <div class="row text-center">
                                    <div class="col-3">
                                        <small class="text-muted">البادئة</small><br>
                                        <strong>PH</strong>
                                    </div>
                                    <div class="col-3">
                                        <small class="text-muted">المجموعة 1</small><br>
                                        <strong>XXXX</strong>
                                    </div>
                                    <div class="col-3">
                                        <small class="text-muted">المجموعة 2</small><br>
                                        <strong>XXXX</strong>
                                    </div>
                                    <div class="col-3">
                                        <small class="text-muted">المجموعة 3</small><br>
                                        <strong>XXXX</strong>
                                    </div>
                                </div>
                            </div>

                            <div class="d-grid mb-4">
                                <button type="submit" class="btn-activate" id="activateBtn">
                                    <i class="fas fa-unlock me-2"></i>
                                    تفعيل الترخيص
                                </button>
                            </div>
                        </form>

                        <!-- معلومات إضافية -->
                        <div class="feature-list">
                            <h6 class="mb-3">
                                <i class="fas fa-star me-2 text-warning"></i>
                                مميزات النظام
                            </h6>
                            <div class="feature-item">
                                <i class="fas fa-check"></i>
                                <span>إدارة شاملة للمخزون والمبيعات</span>
                            </div>
                            <div class="feature-item">
                                <i class="fas fa-check"></i>
                                <span>تقارير مالية ومحاسبية متقدمة</span>
                            </div>
                            <div class="feature-item">
                                <i class="fas fa-check"></i>
                                <span>إدارة المستخدمين والصلاحيات</span>
                            </div>
                            <div class="feature-item">
                                <i class="fas fa-check"></i>
                                <span>نظام نقطة البيع المتطور</span>
                            </div>
                            <div class="feature-item">
                                <i class="fas fa-check"></i>
                                <span>دعم متعدد الفروع والمخازن</span>
                            </div>
                        </div>

                        <!-- معلومات الدعم -->
                        <div class="text-center mt-4">
                            <small class="text-muted">
                                <i class="fas fa-phone me-1"></i>
                                للحصول على مفتاح الترخيص أو الدعم الفني، يرجى التواصل معنا
                                <br>
                                <strong>هاتف:</strong> 07700000000 | 
                                <strong>إيميل:</strong> support@pharmacy-system.com
                            </small>
                        </div>

                        <!-- مفاتيح تجريبية للاختبار -->
                        <div class="text-center mt-3">
                            <div class="alert alert-info">
                                <h6 class="mb-3">
                                    <i class="fas fa-flask me-2"></i>
                                    مفاتيح تجريبية للاختبار
                                </h6>
                                <div class="row">
                                    <div class="col-md-3 mb-2">
                                        <strong>تجريبي:</strong><br>
                                        <code class="small">PH-TEST-2024-DEMO</code>
                                        <button type="button" class="copy-btn" onclick="fillLicense('PH-TEST-2024-DEMO')">
                                            <i class="fas fa-copy"></i>
                                        </button>
                                    </div>
                                    <div class="col-md-3 mb-2">
                                        <strong>كامل:</strong><br>
                                        <code class="small">PH-FULL-2024-PROD</code>
                                        <button type="button" class="copy-btn" onclick="fillLicense('PH-FULL-2024-PROD')">
                                            <i class="fas fa-copy"></i>
                                        </button>
                                    </div>
                                    <div class="col-md-3 mb-2">
                                        <strong>أساسي:</strong><br>
                                        <code class="small">PH-BASIC-2024-STD</code>
                                        <button type="button" class="copy-btn" onclick="fillLicense('PH-BASIC-2024-STD')">
                                            <i class="fas fa-copy"></i>
                                        </button>
                                    </div>
                                    <div class="col-md-3 mb-2">
                                        <strong>جديد:</strong><br>
                                        <code class="small">PH-NEW-2024-SETUP</code>
                                        <button type="button" class="copy-btn" onclick="fillLicense('PH-NEW-2024-SETUP')">
                                            <i class="fas fa-copy"></i>
                                        </button>
                                    </div>
                                </div>
                                <small class="text-muted">
                                    <i class="fas fa-info-circle me-1"></i>
                                    هذه مفاتيح تجريبية للاختبار فقط
                                </small>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- روابط إضافية -->
                <div class="text-center mt-4">
                    <a href="{{ route('master-admin.test') }}" class="btn btn-outline-light me-2">
                        <i class="fas fa-crown me-1"></i>
                        Master Admin
                    </a>
                    <a href="{{ route('login') }}" class="btn btn-outline-light">
                        <i class="fas fa-sign-in-alt me-1"></i>
                        تسجيل الدخول
                    </a>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        const licenseInput = document.getElementById('license_key');
        const activateBtn = document.getElementById('activateBtn');

        // تحسين تنسيق مفتاح الترخيص
        licenseInput.addEventListener('input', function(e) {
            let value = e.target.value.replace(/[^A-Z0-9]/g, '').toUpperCase();
            let formatted = '';

            // تنسيق PH-XXXX-XXXX-XXXX
            for (let i = 0; i < value.length && i < 14; i++) {
                if (i === 2 || i === 6 || i === 10) {
                    formatted += '-';
                }
                formatted += value[i];
            }

            e.target.value = formatted;

            // تغيير لون الزر حسب صحة التنسيق
            updateButtonState();
        });

        // تحديث حالة الزر
        function updateButtonState() {
            const value = licenseInput.value;
            const isValid = /^PH-[A-Z0-9]{4}-[A-Z0-9]{4}-[A-Z0-9]{4}$/.test(value);

            if (isValid) {
                activateBtn.style.background = 'linear-gradient(135deg, #28a745, #20c997)';
                activateBtn.innerHTML = '<i class="fas fa-check me-2"></i>تفعيل الترخيص';
                licenseInput.style.borderColor = '#28a745';
                licenseInput.style.color = '#28a745';
            } else if (value.length > 0) {
                activateBtn.style.background = 'linear-gradient(135deg, #ffc107, #fd7e14)';
                activateBtn.innerHTML = '<i class="fas fa-exclamation-triangle me-2"></i>تنسيق غير صحيح';
                licenseInput.style.borderColor = '#ffc107';
                licenseInput.style.color = '#fd7e14';
            } else {
                activateBtn.style.background = 'linear-gradient(135deg, #667eea, #764ba2)';
                activateBtn.innerHTML = '<i class="fas fa-unlock me-2"></i>تفعيل الترخيص';
                licenseInput.style.borderColor = '#e9ecef';
                licenseInput.style.color = '#495057';
            }
        }

        // ملء سريع للترخيص
        function fillLicense(key) {
            licenseInput.value = key;
            licenseInput.focus();
            updateButtonState();

            // تأثير بصري
            licenseInput.style.transform = 'scale(1.05)';
            setTimeout(() => {
                licenseInput.style.transform = 'scale(1)';
            }, 200);
        }

        // لصق من الحافظة
        async function pasteFromClipboard() {
            try {
                const text = await navigator.clipboard.readText();
                licenseInput.value = text.toUpperCase();
                licenseInput.dispatchEvent(new Event('input'));
                licenseInput.focus();

                // إشعار نجاح
                showNotification('تم اللصق بنجاح!', 'success');
            } catch (err) {
                // fallback للمتصفحات القديمة
                licenseInput.focus();
                licenseInput.select();
                showNotification('اضغط Ctrl+V للصق', 'info');
            }
        }

        // مسح المدخل
        function clearInput() {
            licenseInput.value = '';
            licenseInput.focus();
            updateButtonState();
            showNotification('تم المسح', 'info');
        }

        // إشعارات سريعة
        function showNotification(message, type = 'info') {
            const notification = document.createElement('div');
            notification.className = `alert alert-${type} position-fixed`;
            notification.style.cssText = `
                top: 20px;
                right: 20px;
                z-index: 9999;
                min-width: 250px;
                opacity: 0;
                transform: translateX(100%);
                transition: all 0.3s ease;
            `;
            notification.innerHTML = `
                <i class="fas fa-${type === 'success' ? 'check' : 'info'}-circle me-2"></i>
                ${message}
            `;

            document.body.appendChild(notification);

            // إظهار الإشعار
            setTimeout(() => {
                notification.style.opacity = '1';
                notification.style.transform = 'translateX(0)';
            }, 100);

            // إخفاء الإشعار
            setTimeout(() => {
                notification.style.opacity = '0';
                notification.style.transform = 'translateX(100%)';
                setTimeout(() => {
                    document.body.removeChild(notification);
                }, 300);
            }, 2000);
        }

        // اختصارات لوحة المفاتيح
        document.addEventListener('keydown', function(e) {
            // Ctrl+V للصق
            if (e.ctrlKey && e.key === 'v' && document.activeElement === licenseInput) {
                setTimeout(() => {
                    licenseInput.dispatchEvent(new Event('input'));
                }, 10);
            }

            // Enter لتفعيل الترخيص
            if (e.key === 'Enter' && document.activeElement === licenseInput) {
                e.preventDefault();
                if (/^PH-[A-Z0-9]{4}-[A-Z0-9]{4}-[A-Z0-9]{4}$/.test(licenseInput.value)) {
                    document.getElementById('licenseForm').submit();
                } else {
                    showNotification('يرجى إدخال مفتاح ترخيص صحيح', 'warning');
                }
            }
        });

        // تحديث حالة الزر عند تحميل الصفحة
        document.addEventListener('DOMContentLoaded', function() {
            updateButtonState();

            // تركيز تلقائي مع تأخير صغير
            setTimeout(() => {
                licenseInput.focus();
            }, 500);
        });

        // منع إرسال النموذج إذا كان التنسيق خاطئ
        document.getElementById('licenseForm').addEventListener('submit', function(e) {
            const value = licenseInput.value;
            if (!/^PH-[A-Z0-9]{4}-[A-Z0-9]{4}-[A-Z0-9]{4}$/.test(value)) {
                e.preventDefault();
                showNotification('تنسيق مفتاح الترخيص غير صحيح', 'danger');
                licenseInput.focus();
                return false;
            }

            // تغيير نص الزر أثناء الإرسال
            activateBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>جاري التحقق...';
            activateBtn.disabled = true;
        });
    </script>
</body>
</html>
