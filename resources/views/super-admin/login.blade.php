<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Super Admin Login - نظام إدارة الصيدليات</title>
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
        .login-container {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(15px);
            border-radius: 20px;
            box-shadow: 0 25px 50px rgba(0, 0, 0, 0.15);
            border: 1px solid rgba(255, 255, 255, 0.2);
            max-width: 450px;
            width: 100%;
            margin: 20px;
        }
        .login-header {
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: white;
            padding: 40px 30px;
            text-align: center;
            border-radius: 20px 20px 0 0;
        }
        .login-icon {
            font-size: 3rem;
            margin-bottom: 20px;
        }
        .login-title {
            font-size: 1.8rem;
            font-weight: bold;
            margin-bottom: 10px;
        }
        .form-control {
            border: 2px solid #e9ecef;
            border-radius: 15px;
            padding: 15px 20px;
            font-size: 1rem;
            transition: all 0.3s ease;
        }
        .form-control:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
        }
        .btn-login {
            background: linear-gradient(135deg, #667eea, #764ba2);
            border: none;
            border-radius: 15px;
            padding: 15px;
            font-size: 1.1rem;
            font-weight: bold;
            color: white;
            transition: all 0.3s ease;
        }
        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(102, 126, 234, 0.4);
            color: white;
        }
        .input-group-text {
            background: transparent;
            border: 2px solid #e9ecef;
            border-left: none;
            border-radius: 0 15px 15px 0;
        }
        .form-control.with-icon {
            border-left: none;
            border-radius: 15px 0 0 15px;
        }
        .alert {
            border-radius: 15px;
            border: none;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <!-- Header -->
        <div class="login-header">
            <div class="login-icon">
                <i class="fas fa-crown"></i>
            </div>
            <h1 class="login-title">Super Admin</h1>
            <p class="mb-0">لوحة التحكم الرئيسية</p>
        </div>

        <div class="p-4">
            <!-- رسائل الخطأ -->
            @if($errors->any())
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fas fa-exclamation-circle me-2"></i>
                    @foreach($errors->all() as $error)
                        {{ $error }}
                    @endforeach
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <!-- رسائل النجاح -->
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fas fa-check-circle me-2"></i>
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <!-- نموذج تسجيل الدخول -->
            <form method="POST" action="{{ route('login') }}">
                @csrf

                <!-- البريد الإلكتروني -->
                <div class="mb-3">
                    <label for="email" class="form-label fw-bold">البريد الإلكتروني</label>
                    <div class="input-group">
                        <input type="email" 
                               class="form-control with-icon @error('email') is-invalid @enderror" 
                               id="email" 
                               name="email" 
                               value="{{ old('email') }}" 
                               placeholder="master@pharmacy-system.com"
                               required 
                               autofocus>
                        <span class="input-group-text">
                            <i class="fas fa-envelope text-muted"></i>
                        </span>
                    </div>
                    @error('email')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>

                <!-- كلمة المرور -->
                <div class="mb-3">
                    <label for="password" class="form-label fw-bold">كلمة المرور</label>
                    <div class="input-group">
                        <input type="password" 
                               class="form-control with-icon @error('password') is-invalid @enderror" 
                               id="password" 
                               name="password" 
                               placeholder="••••••••"
                               required>
                        <span class="input-group-text">
                            <i class="fas fa-lock text-muted"></i>
                        </span>
                    </div>
                    @error('password')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>

                <!-- تذكرني -->
                <div class="mb-4">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="remember" name="remember" {{ old('remember') ? 'checked' : '' }}>
                        <label class="form-check-label" for="remember">
                            تذكرني
                        </label>
                    </div>
                </div>

                <!-- زر تسجيل الدخول -->
                <div class="d-grid">
                    <button type="submit" class="btn btn-login">
                        <i class="fas fa-sign-in-alt me-2"></i>
                        تسجيل الدخول
                    </button>
                </div>
            </form>

            <!-- معلومات إضافية -->
            <div class="text-center mt-4">
                <small class="text-muted">
                    <i class="fas fa-shield-alt me-1"></i>
                    منطقة محمية - Super Admin فقط
                </small>
            </div>

            <!-- رابط العودة -->
            <div class="text-center mt-3">
                <a href="{{ route('license.verify') }}" class="btn btn-outline-secondary btn-sm">
                    <i class="fas fa-arrow-left me-1"></i>
                    العودة للنظام العادي
                </a>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        // تأثيرات بصرية
        document.addEventListener('DOMContentLoaded', function() {
            // تركيز تلقائي على حقل الإيميل
            document.getElementById('email').focus();
            
            // تأثير على الأزرار
            const loginBtn = document.querySelector('.btn-login');
            loginBtn.addEventListener('click', function() {
                this.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>جاري تسجيل الدخول...';
            });
        });

        // ملء سريع للبيانات التجريبية (للتطوير فقط)
        function fillTestData() {
            document.getElementById('email').value = 'master@pharmacy-system.com';
            document.getElementById('password').value = 'master123456';
        }

        // إضافة اختصار لوحة المفاتيح للملء السريع (Ctrl+T)
        document.addEventListener('keydown', function(e) {
            if (e.ctrlKey && e.key === 't') {
                e.preventDefault();
                fillTestData();
            }
        });
    </script>
</body>
</html>
