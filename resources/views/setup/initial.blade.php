<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>إعداد النظام - نظام إدارة الصيدليات</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .setup-container {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(15px);
            border-radius: 20px;
            box-shadow: 0 25px 50px rgba(0, 0, 0, 0.15);
            border: 1px solid rgba(255, 255, 255, 0.2);
            margin: 20px 0;
        }
        .setup-header {
            background: linear-gradient(135deg, #28a745, #20c997);
            color: white;
            padding: 30px;
            text-align: center;
            border-radius: 20px 20px 0 0;
            position: relative;
        }
        .setup-header::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="setup-pattern" width="20" height="20" patternUnits="userSpaceOnUse"><circle cx="10" cy="10" r="1" fill="white" opacity="0.1"/></pattern></defs><rect width="100" height="100" fill="url(%23setup-pattern)"/></svg>');
        }
        .setup-icon {
            font-size: 4rem;
            margin-bottom: 20px;
            position: relative;
            z-index: 1;
        }
        .setup-title {
            font-size: 2.5rem;
            font-weight: bold;
            margin-bottom: 10px;
            position: relative;
            z-index: 1;
        }
        .setup-subtitle {
            opacity: 0.9;
            position: relative;
            z-index: 1;
            font-size: 1.1rem;
        }
        .license-info {
            background: rgba(40, 167, 69, 0.1);
            border: 2px solid rgba(40, 167, 69, 0.3);
            border-radius: 15px;
            padding: 20px;
            margin-bottom: 30px;
        }
        .setup-type-card {
            background: white;
            border: 2px solid #e9ecef;
            border-radius: 15px;
            padding: 25px;
            margin-bottom: 20px;
            cursor: pointer;
            transition: all 0.3s ease;
            position: relative;
        }
        .setup-type-card:hover {
            border-color: #667eea;
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(102, 126, 234, 0.15);
        }
        .setup-type-card.selected {
            border-color: #28a745;
            background: rgba(40, 167, 69, 0.05);
        }
        .setup-type-card input[type="radio"] {
            position: absolute;
            opacity: 0;
        }
        .form-section {
            background: rgba(248, 249, 250, 0.8);
            border-radius: 15px;
            padding: 25px;
            margin-bottom: 25px;
            border-left: 5px solid #667eea;
        }
        .btn-setup {
            background: linear-gradient(135deg, #28a745, #20c997);
            border: none;
            border-radius: 25px;
            padding: 15px 40px;
            font-size: 1.2rem;
            font-weight: bold;
            color: white;
            transition: all 0.3s ease;
            width: 100%;
        }
        .btn-setup:hover {
            transform: translateY(-3px);
            box-shadow: 0 15px 30px rgba(40, 167, 69, 0.4);
            color: white;
        }
        .warning-card {
            background: rgba(255, 193, 7, 0.1);
            border: 2px solid rgba(255, 193, 7, 0.3);
            border-radius: 15px;
            padding: 20px;
            margin-bottom: 20px;
        }
        .step-indicator {
            display: flex;
            justify-content: center;
            margin-bottom: 30px;
        }
        .step {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: #e9ecef;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 10px;
            font-weight: bold;
            position: relative;
        }
        .step.active {
            background: #28a745;
            color: white;
        }
        .step.completed {
            background: #20c997;
            color: white;
        }
        .step::after {
            content: '';
            position: absolute;
            top: 50%;
            left: 100%;
            width: 20px;
            height: 2px;
            background: #e9ecef;
            transform: translateY(-50%);
        }
        .step:last-child::after {
            display: none;
        }
    </style>
</head>
<body>
    <div class="container mt-4">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="setup-container">
                    <!-- Header -->
                    <div class="setup-header">
                        <div class="setup-icon">
                            <i class="fas fa-cogs"></i>
                        </div>
                        <h1 class="setup-title">إعداد النظام</h1>
                        <p class="setup-subtitle">مرحباً بك! دعنا نقوم بإعداد نظام إدارة الصيدليات الخاص بك</p>
                    </div>

                    <div class="p-4">
                        <!-- مؤشر الخطوات -->
                        <div class="step-indicator">
                            <div class="step completed">1</div>
                            <div class="step active">2</div>
                            <div class="step">3</div>
                            <div class="step">4</div>
                        </div>

                        <!-- معلومات الترخيص -->
                        <div class="license-info">
                            <h5 class="text-success mb-3">
                                <i class="fas fa-certificate me-2"></i>
                                معلومات الترخيص المفعل
                            </h5>
                            <div class="row">
                                <div class="col-md-6">
                                    <p><strong>العميل:</strong> {{ $currentLicense->client_name }}</p>
                                    <p><strong>نوع الترخيص:</strong> 
                                        <span class="badge bg-primary">{{ $currentLicense->license_type }}</span>
                                    </p>
                                </div>
                                <div class="col-md-6">
                                    <p><strong>مفتاح الترخيص:</strong> <code>{{ $currentLicense->license_key }}</code></p>
                                    <p><strong>صالح حتى:</strong> {{ $currentLicense->end_date->format('Y-m-d') }}</p>
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

                        <!-- نموذج الإعداد -->
                        <form method="POST" action="{{ route('setup.process') }}" id="setupForm">
                            @csrf

                            <!-- اختيار نوع الإعداد -->
                            <div class="form-section">
                                <h5 class="mb-4">
                                    <i class="fas fa-route me-2 text-primary"></i>
                                    اختر نوع الإعداد
                                </h5>

                                @if($hasExistingSetup)
                                    <div class="warning-card">
                                        <h6 class="text-warning mb-2">
                                            <i class="fas fa-exclamation-triangle me-2"></i>
                                            تحذير: يوجد إعداد سابق
                                        </h6>
                                        <p class="mb-0">تم العثور على بيانات سابقة لهذا الترخيص. يمكنك إعادة تعيين النظام أو الاستمرار مع البيانات الموجودة.</p>
                                    </div>
                                @endif

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="setup-type-card" onclick="selectSetupType('fresh')">
                                            <input type="radio" name="setup_type" value="fresh" id="fresh" 
                                                   {{ old('setup_type', 'fresh') === 'fresh' ? 'checked' : '' }}>
                                            <div class="text-center">
                                                <i class="fas fa-plus-circle fa-3x text-success mb-3"></i>
                                                <h5>إعداد جديد</h5>
                                                <p class="text-muted">بدء نظام جديد تماماً مع الاحتفاظ بالبيانات الموجودة</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="setup-type-card" onclick="selectSetupType('reset')">
                                            <input type="radio" name="setup_type" value="reset" id="reset"
                                                   {{ old('setup_type') === 'reset' ? 'checked' : '' }}>
                                            <div class="text-center">
                                                <i class="fas fa-redo-alt fa-3x text-warning mb-3"></i>
                                                <h5>إعادة تعيين</h5>
                                                <p class="text-muted">مسح جميع البيانات والبدء من الصفر</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- معلومات المدير -->
                            <div class="form-section">
                                <h5 class="mb-4">
                                    <i class="fas fa-user-shield me-2 text-primary"></i>
                                    معلومات مدير النظام
                                </h5>
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="admin_name" class="form-label">اسم المدير *</label>
                                        <input type="text" class="form-control @error('admin_name') is-invalid @enderror" 
                                               id="admin_name" name="admin_name" value="{{ old('admin_name') }}" 
                                               placeholder="الاسم الكامل للمدير" required>
                                        @error('admin_name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="admin_email" class="form-label">البريد الإلكتروني *</label>
                                        <input type="email" class="form-control @error('admin_email') is-invalid @enderror" 
                                               id="admin_email" name="admin_email" value="{{ old('admin_email') }}" 
                                               placeholder="admin@example.com" required>
                                        @error('admin_email')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="admin_password" class="form-label">كلمة المرور *</label>
                                        <input type="password" class="form-control @error('admin_password') is-invalid @enderror" 
                                               id="admin_password" name="admin_password" 
                                               placeholder="كلمة مرور قوية (8 أحرف على الأقل)" required>
                                        @error('admin_password')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="admin_password_confirmation" class="form-label">تأكيد كلمة المرور *</label>
                                        <input type="password" class="form-control"
                                               id="admin_password_confirmation" name="admin_password_confirmation"
                                               placeholder="أعد كتابة كلمة المرور" required>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="admin_phone" class="form-label">رقم هاتف المدير</label>
                                        <input type="text" class="form-control @error('admin_phone') is-invalid @enderror"
                                               id="admin_phone" name="admin_phone" value="{{ old('admin_phone') }}"
                                               placeholder="07xxxxxxxxx">
                                        @error('admin_phone')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="admin_address" class="form-label">عنوان المدير</label>
                                        <input type="text" class="form-control @error('admin_address') is-invalid @enderror"
                                               id="admin_address" name="admin_address" value="{{ old('admin_address') }}"
                                               placeholder="عنوان المدير">
                                        @error('admin_address')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <!-- معلومات الشركة -->
                            <div class="form-section">
                                <h5 class="mb-4">
                                    <i class="fas fa-building me-2 text-primary"></i>
                                    معلومات الشركة/الصيدلية
                                </h5>
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="company_name" class="form-label">اسم الشركة/الصيدلية *</label>
                                        <input type="text" class="form-control @error('company_name') is-invalid @enderror" 
                                               id="company_name" name="company_name" value="{{ old('company_name') }}" 
                                               placeholder="اسم الشركة أو الصيدلية" required>
                                        @error('company_name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="company_phone" class="form-label">رقم الهاتف</label>
                                        <input type="text" class="form-control @error('company_phone') is-invalid @enderror" 
                                               id="company_phone" name="company_phone" value="{{ old('company_phone') }}" 
                                               placeholder="07xxxxxxxxx">
                                        @error('company_phone')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-12 mb-3">
                                        <label for="company_address" class="form-label">عنوان الشركة</label>
                                        <textarea class="form-control @error('company_address') is-invalid @enderror" 
                                                  id="company_address" name="company_address" rows="2" 
                                                  placeholder="العنوان الكامل للشركة أو الصيدلية">{{ old('company_address') }}</textarea>
                                        @error('company_address')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <!-- معلومات المخزن الرئيسي -->
                            <div class="form-section">
                                <h5 class="mb-4">
                                    <i class="fas fa-warehouse me-2 text-primary"></i>
                                    المخزن الرئيسي
                                </h5>
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="warehouse_name" class="form-label">اسم المخزن *</label>
                                        <input type="text" class="form-control @error('warehouse_name') is-invalid @enderror" 
                                               id="warehouse_name" name="warehouse_name" value="{{ old('warehouse_name', 'المخزن الرئيسي') }}" 
                                               placeholder="اسم المخزن الرئيسي" required>
                                        @error('warehouse_name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="warehouse_address" class="form-label">عنوان المخزن</label>
                                        <input type="text" class="form-control @error('warehouse_address') is-invalid @enderror" 
                                               id="warehouse_address" name="warehouse_address" value="{{ old('warehouse_address') }}" 
                                               placeholder="عنوان المخزن">
                                        @error('warehouse_address')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <!-- أزرار الإجراء -->
                            <div class="text-center">
                                <button type="submit" class="btn-setup" id="setupBtn">
                                    <i class="fas fa-rocket me-2"></i>
                                    بدء إعداد النظام
                                </button>
                                <div class="mt-3">
                                    <a href="{{ route('license.verify') }}" class="btn btn-outline-secondary">
                                        <i class="fas fa-arrow-left me-2"></i>
                                        العودة لصفحة الترخيص
                                    </a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        function selectSetupType(type) {
            // إزالة التحديد من جميع البطاقات
            document.querySelectorAll('.setup-type-card').forEach(card => {
                card.classList.remove('selected');
            });
            
            // تحديد البطاقة المختارة
            event.currentTarget.classList.add('selected');
            
            // تحديد الراديو بوتن
            document.getElementById(type).checked = true;
            
            // تحديث نص الزر
            const setupBtn = document.getElementById('setupBtn');
            if (type === 'reset') {
                setupBtn.innerHTML = '<i class="fas fa-exclamation-triangle me-2"></i>إعادة تعيين النظام';
                setupBtn.style.background = 'linear-gradient(135deg, #dc3545, #c82333)';
            } else {
                setupBtn.innerHTML = '<i class="fas fa-rocket me-2"></i>بدء إعداد النظام';
                setupBtn.style.background = 'linear-gradient(135deg, #28a745, #20c997)';
            }
        }

        // تحديد النوع المختار عند تحميل الصفحة
        document.addEventListener('DOMContentLoaded', function() {
            const selectedType = document.querySelector('input[name="setup_type"]:checked');
            if (selectedType) {
                selectSetupType(selectedType.value);
            }
        });

        // تأكيد إعادة التعيين
        document.getElementById('setupForm').addEventListener('submit', function(e) {
            const setupType = document.querySelector('input[name="setup_type"]:checked').value;
            
            if (setupType === 'reset') {
                if (!confirm('تحذير: سيتم حذف جميع البيانات الموجودة. هل أنت متأكد من المتابعة؟')) {
                    e.preventDefault();
                    return false;
                }
            }
            
            // تغيير نص الزر أثناء المعالجة
            const setupBtn = document.getElementById('setupBtn');
            setupBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>جاري الإعداد...';
            setupBtn.disabled = true;
        });
    </script>
</body>
</html>
