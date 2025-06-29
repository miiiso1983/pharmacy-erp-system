<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Master Admin - تسجيل الدخول</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .login-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }
        .master-admin-badge {
            background: linear-gradient(45deg, #ff6b6b, #ee5a24);
            color: white;
            padding: 8px 20px;
            border-radius: 50px;
            font-weight: bold;
            display: inline-block;
            margin-bottom: 20px;
            box-shadow: 0 4px 15px rgba(238, 90, 36, 0.3);
        }
        .form-control:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
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
        .security-notice {
            background: rgba(255, 193, 7, 0.1);
            border: 1px solid rgba(255, 193, 7, 0.3);
            border-radius: 10px;
            padding: 15px;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6 col-lg-5">
                <div class="card login-card">
                    <div class="card-body p-5">
                        <!-- Header -->
                        <div class="text-center mb-4">
                            <div class="master-admin-badge">
                                <i class="fas fa-crown me-2"></i>
                                MASTER ADMIN
                            </div>
                            <h3 class="fw-bold text-dark">نظام التحكم الرئيسي</h3>
                            <p class="text-muted">إدارة التراخيص وحدود المستخدمين</p>
                        </div>

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

                        @if($errors->any())
                            <div class="alert alert-danger">
                                <ul class="mb-0">
                                    @foreach($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <!-- نموذج تسجيل الدخول -->
                        <form method="POST" action="{{ route('master-admin.login.submit') }}">
                            @csrf
                            
                            <div class="mb-4">
                                <label for="email" class="form-label fw-bold">
                                    <i class="fas fa-envelope me-2 text-primary"></i>
                                    البريد الإلكتروني
                                </label>
                                <input type="email" 
                                       class="form-control form-control-lg @error('email') is-invalid @enderror" 
                                       id="email" 
                                       name="email" 
                                       value="{{ old('email') }}" 
                                       placeholder="أدخل البريد الإلكتروني"
                                       required 
                                       autofocus>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-4">
                                <label for="password" class="form-label fw-bold">
                                    <i class="fas fa-lock me-2 text-primary"></i>
                                    كلمة المرور
                                </label>
                                <div class="input-group">
                                    <input type="password" 
                                           class="form-control form-control-lg @error('password') is-invalid @enderror" 
                                           id="password" 
                                           name="password" 
                                           placeholder="أدخل كلمة المرور"
                                           required>
                                    <button class="btn btn-outline-secondary" type="button" onclick="togglePassword()">
                                        <i class="fas fa-eye" id="toggleIcon"></i>
                                    </button>
                                </div>
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="remember" name="remember">
                                    <label class="form-check-label" for="remember">
                                        تذكرني لمدة 30 يوم
                                    </label>
                                </div>
                            </div>

                            <div class="d-grid">
                                <button type="submit" class="btn btn-master btn-lg text-white">
                                    <i class="fas fa-sign-in-alt me-2"></i>
                                    تسجيل الدخول
                                </button>
                            </div>
                        </form>

                        <!-- معلومات الأمان -->
                        <div class="security-notice">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-shield-alt text-warning me-2"></i>
                                <small class="text-muted">
                                    <strong>تنبيه أمني:</strong> هذا النظام مخصص لـ Master Admins فقط. 
                                    جميع العمليات مسجلة ومراقبة.
                                </small>
                            </div>
                        </div>

                        <!-- معلومات النظام -->
                        <div class="text-center mt-4">
                            <small class="text-muted">
                                نظام إدارة الصيدليات - Master Control Panel<br>
                                الإصدار 2.0 | {{ date('Y') }}
                            </small>
                        </div>
                    </div>
                </div>

                <!-- بيانات تجريبية للاختبار -->
                <div class="card mt-3" style="background: rgba(255, 255, 255, 0.9);">
                    <div class="card-body">
                        <h6 class="text-center mb-3">
                            <i class="fas fa-info-circle me-2"></i>
                            بيانات الاختبار
                        </h6>
                        <div class="row text-center">
                            <div class="col-12">
                                <small class="text-muted">
                                    <strong>الإيميل:</strong> master@pharmacy-system.com<br>
                                    <strong>كلمة المرور:</strong> master123456
                                </small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function togglePassword() {
            const passwordInput = document.getElementById('password');
            const toggleIcon = document.getElementById('toggleIcon');
            
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                toggleIcon.classList.remove('fa-eye');
                toggleIcon.classList.add('fa-eye-slash');
            } else {
                passwordInput.type = 'password';
                toggleIcon.classList.remove('fa-eye-slash');
                toggleIcon.classList.add('fa-eye');
            }
        }

        // Auto-fill للاختبار (يمكن إزالته في الإنتاج)
        document.addEventListener('DOMContentLoaded', function() {
            // يمكن تفعيل هذا للاختبار السريع
            // document.getElementById('email').value = 'master@pharmacy-system.com';
            // document.getElementById('password').value = 'master123456';
        });
    </script>
</body>
</html>
