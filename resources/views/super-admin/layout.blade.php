<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') - Super Admin Panel</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .navbar {
            background: rgba(255, 255, 255, 0.95) !important;
            backdrop-filter: blur(15px);
            box-shadow: 0 2px 20px rgba(0, 0, 0, 0.1);
        }
        .main-container {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(15px);
            border-radius: 20px;
            box-shadow: 0 25px 50px rgba(0, 0, 0, 0.15);
            border: 1px solid rgba(255, 255, 255, 0.2);
            margin: 20px;
            padding: 30px;
        }
        .stat-card {
            background: white;
            border-radius: 15px;
            padding: 25px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            border: none;
            transition: all 0.3s ease;
            margin-bottom: 20px;
        }
        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
        }
        .stat-icon {
            width: 60px;
            height: 60px;
            border-radius: 15px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            color: white;
            margin-bottom: 15px;
        }
        .stat-number {
            font-size: 2rem;
            font-weight: bold;
            margin-bottom: 5px;
        }
        .stat-label {
            color: #6c757d;
            font-size: 0.9rem;
        }
        .primary { background: linear-gradient(135deg, #667eea, #764ba2); }
        .success { background: linear-gradient(135deg, #28a745, #20c997); }
        .warning { background: linear-gradient(135deg, #ffc107, #fd7e14); }
        .danger { background: linear-gradient(135deg, #dc3545, #c82333); }
        .info { background: linear-gradient(135deg, #17a2b8, #138496); }
        .secondary { background: linear-gradient(135deg, #6c757d, #495057); }
        
        .nav-pills .nav-link {
            border-radius: 25px;
            margin: 0 5px;
            transition: all 0.3s ease;
        }
        .nav-pills .nav-link.active {
            background: linear-gradient(135deg, #667eea, #764ba2);
        }
        .table {
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }
        .btn-custom {
            border-radius: 25px;
            padding: 10px 25px;
            font-weight: bold;
            transition: all 0.3s ease;
        }
        .btn-custom:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
        }
        .card {
            border-radius: 15px;
            border: none;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }
        .card-header {
            background: rgba(248, 249, 250, 0.8);
            border-bottom: 1px solid rgba(0, 0, 0, 0.1);
            border-radius: 15px 15px 0 0 !important;
        }
        .sidebar {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(15px);
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            padding: 20px;
            margin: 20px 0;
        }
        .sidebar-nav .nav-link {
            color: #495057;
            border-radius: 10px;
            margin-bottom: 5px;
            transition: all 0.3s ease;
        }
        .sidebar-nav .nav-link:hover,
        .sidebar-nav .nav-link.active {
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: white;
        }
    </style>
    @stack('styles')
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-light">
        <div class="container-fluid">
            <a class="navbar-brand fw-bold" href="{{ route('super-admin.dashboard') }}">
                <i class="fas fa-crown text-warning me-2"></i>
                Super Admin Panel
            </a>
            
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('super-admin.dashboard') ? 'active' : '' }}" 
                           href="{{ route('super-admin.dashboard') }}">
                            <i class="fas fa-tachometer-alt me-1"></i>لوحة التحكم
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('super-admin.licenses') ? 'active' : '' }}" 
                           href="{{ route('super-admin.licenses') }}">
                            <i class="fas fa-certificate me-1"></i>التراخيص
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('super-admin.users') ? 'active' : '' }}" 
                           href="{{ route('super-admin.users') }}">
                            <i class="fas fa-users me-1"></i>المستخدمين
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('super-admin.reports') ? 'active' : '' }}"
                           href="{{ route('super-admin.reports') }}">
                            <i class="fas fa-chart-bar me-1"></i>التقارير
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('super-admin.data-isolation.*') ? 'active' : '' }}"
                           href="{{ route('super-admin.data-isolation.dashboard') }}">
                            <i class="fas fa-shield-alt me-1"></i>عزل البيانات
                        </a>
                    </li>
                </ul>
                
                <div class="navbar-nav">
                    <div class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                            <i class="fas fa-user-shield me-2"></i>
                            {{ auth()->user()->name }}
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="{{ route('super-admin.settings') }}">
                                <i class="fas fa-cog me-2"></i>الإعدادات
                            </a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="{{ route('dashboard') }}">
                                <i class="fas fa-external-link-alt me-2"></i>النظام العادي
                            </a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <form method="POST" action="{{ route('super-admin.logout') }}">
                                    @csrf
                                    <button type="submit" class="dropdown-item text-danger">
                                        <i class="fas fa-sign-out-alt me-2"></i>تسجيل الخروج
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <div class="container-fluid">
        <div class="main-container">
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
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    <ul class="mb-0">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <!-- المحتوى الرئيسي -->
            @yield('content')
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <script>
        // تأكيد العمليات الخطيرة
        function confirmAction(message, callback) {
            Swal.fire({
                title: 'تأكيد العملية',
                text: message,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc3545',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'تأكيد',
                cancelButtonText: 'إلغاء'
            }).then((result) => {
                if (result.isConfirmed && callback) {
                    callback();
                }
            });
        }

        // رسائل النجاح
        function showSuccess(message) {
            Swal.fire({
                title: 'نجح!',
                text: message,
                icon: 'success',
                confirmButtonText: 'موافق'
            });
        }

        // رسائل الخطأ
        function showError(message) {
            Swal.fire({
                title: 'خطأ!',
                text: message,
                icon: 'error',
                confirmButtonText: 'موافق'
            });
        }
    </script>
    
    @stack('scripts')
</body>
</html>
