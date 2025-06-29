<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Master Admin - تفاصيل الترخيص</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
        }
        .navbar-master {
            background: rgba(255, 255, 255, 0.95) !important;
            backdrop-filter: blur(10px);
            box-shadow: 0 2px 20px rgba(0, 0, 0, 0.1);
        }
        .master-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
            margin-bottom: 20px;
        }
        .master-badge {
            background: linear-gradient(45deg, #ff6b6b, #ee5a24);
            color: white;
            padding: 5px 15px;
            border-radius: 50px;
            font-size: 12px;
            font-weight: bold;
        }
        .btn-master {
            background: linear-gradient(45deg, #667eea, #764ba2);
            border: none;
            border-radius: 50px;
            padding: 10px 25px;
            font-weight: bold;
            transition: all 0.3s ease;
        }
        .btn-master:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(102, 126, 234, 0.3);
        }
        .info-card {
            background: white;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 15px;
            border-left: 4px solid #667eea;
        }
        .usage-bar {
            background: #e9ecef;
            border-radius: 10px;
            height: 20px;
            overflow: hidden;
        }
        .usage-fill {
            height: 100%;
            border-radius: 10px;
            transition: width 0.3s ease;
        }
        .usage-fill.safe { background: linear-gradient(90deg, #28a745, #20c997); }
        .usage-fill.warning { background: linear-gradient(90deg, #ffc107, #fd7e14); }
        .usage-fill.danger { background: linear-gradient(90deg, #dc3545, #e83e8c); }
    </style>
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-master">
        <div class="container">
            <a class="navbar-brand fw-bold" href="{{ route('master-admin.dashboard') }}">
                <i class="fas fa-crown text-warning me-2"></i>
                Master Admin
            </a>
            
            <div class="navbar-nav ms-auto">
                <div class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" role="button" data-bs-toggle="dropdown">
                        <div class="avatar-sm me-2">
                            <div class="avatar-title bg-primary rounded-circle">
                                {{ strtoupper(substr(auth('master_admin')->user()->name, 0, 1)) }}
                            </div>
                        </div>
                        {{ auth('master_admin')->user()->name }}
                        <span class="master-badge ms-2">MASTER</span>
                    </a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="{{ route('master-admin.dashboard') }}"><i class="fas fa-tachometer-alt me-2"></i>لوحة التحكم</a></li>
                        <li><a class="dropdown-item" href="{{ route('master-admin.licenses.index') }}"><i class="fas fa-key me-2"></i>إدارة التراخيص</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <form method="POST" action="{{ route('master-admin.logout') }}">
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
    </nav>

    <div class="container mt-4">
        <!-- Breadcrumb -->
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('master-admin.dashboard') }}" class="text-white">لوحة التحكم</a></li>
                <li class="breadcrumb-item"><a href="{{ route('master-admin.licenses.index') }}" class="text-white">إدارة التراخيص</a></li>
                <li class="breadcrumb-item active text-white-50">{{ $license->client_name }}</li>
            </ol>
        </nav>

        <!-- رسائل النجاح والخطأ -->
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle me-2"></i>
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <!-- العنوان الرئيسي -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h2 class="text-white fw-bold">
                            <i class="fas fa-key me-2"></i>
                            تفاصيل الترخيص
                        </h2>
                        <p class="text-white-50">{{ $license->client_name }} - {{ $license->license_key }}</p>
                    </div>
                    <div>
                        @if($license->is_active && $license->end_date > now())
                            <span class="badge bg-success fs-6 px-3 py-2">نشط</span>
                        @elseif($license->end_date < now())
                            <span class="badge bg-danger fs-6 px-3 py-2">منتهي</span>
                        @else
                            <span class="badge bg-warning fs-6 px-3 py-2">معلق</span>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-8">
                <!-- معلومات العميل -->
                <div class="master-card">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="fas fa-user me-2"></i>
                            معلومات العميل
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="info-card">
                                    <h6 class="text-primary">اسم العميل</h6>
                                    <p class="mb-0 fw-bold">{{ $license->client_name }}</p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="info-card">
                                    <h6 class="text-primary">البريد الإلكتروني</h6>
                                    <p class="mb-0">{{ $license->client_email }}</p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="info-card">
                                    <h6 class="text-primary">رقم الهاتف</h6>
                                    <p class="mb-0">{{ $license->client_phone ?? 'غير محدد' }}</p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="info-card">
                                    <h6 class="text-primary">اسم الشركة</h6>
                                    <p class="mb-0">{{ $license->client_company ?? 'غير محدد' }}</p>
                                </div>
                            </div>
                            @if($license->client_address)
                                <div class="col-12">
                                    <div class="info-card">
                                        <h6 class="text-primary">العنوان</h6>
                                        <p class="mb-0">{{ $license->client_address }}</p>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- معلومات الترخيص -->
                <div class="master-card">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="fas fa-certificate me-2"></i>
                            معلومات الترخيص
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="info-card">
                                    <h6 class="text-primary">مفتاح الترخيص</h6>
                                    <code class="fs-6">{{ $license->license_key }}</code>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="info-card">
                                    <h6 class="text-primary">نوع الترخيص</h6>
                                    <span class="badge bg-secondary fs-6">{{ $license->license_type }}</span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="info-card">
                                    <h6 class="text-primary">تاريخ البداية</h6>
                                    <p class="mb-0">{{ $license->start_date->format('Y-m-d') }}</p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="info-card">
                                    <h6 class="text-primary">تاريخ الانتهاء</h6>
                                    <p class="mb-0">
                                        {{ $license->end_date->format('Y-m-d') }}
                                        @if($license->end_date < now())
                                            <br><small class="text-danger">منتهي منذ {{ $license->end_date->diffForHumans() }}</small>
                                        @elseif($license->end_date <= now()->addDays(30))
                                            <br><small class="text-warning">ينتهي خلال {{ $license->end_date->diffForHumans() }}</small>
                                        @else
                                            <br><small class="text-success">ينتهي خلال {{ $license->end_date->diffForHumans() }}</small>
                                        @endif
                                    </p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="info-card">
                                    <h6 class="text-primary">التكلفة</h6>
                                    <p class="mb-0 fw-bold">${{ number_format($license->license_cost ?? 0, 2) }}</p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="info-card">
                                    <h6 class="text-primary">حالة الدفع</h6>
                                    @switch($license->payment_status)
                                        @case('paid')
                                            <span class="badge bg-success">مدفوع</span>
                                            @break
                                        @case('pending')
                                            <span class="badge bg-warning">في الانتظار</span>
                                            @break
                                        @case('overdue')
                                            <span class="badge bg-danger">متأخر</span>
                                            @break
                                        @default
                                            <span class="badge bg-secondary">{{ $license->payment_status }}</span>
                                    @endswitch
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- حدود الاستخدام -->
                <div class="master-card">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="fas fa-chart-bar me-2"></i>
                            حدود الاستخدام
                        </h5>
                    </div>
                    <div class="card-body">
                        @if($license->usage)
                            @php
                                $userPercentage = $license->usage->users_usage_percentage ?? 0;
                                $warehousePercentage = $license->usage->warehouses_usage_percentage ?? 0;
                                $branchPercentage = $license->usage->branches_usage_percentage ?? 0;
                            @endphp

                            <!-- المستخدمين -->
                            <div class="mb-4">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <h6 class="mb-0">المستخدمين</h6>
                                    <span class="badge bg-primary">{{ $license->usage->current_users ?? 0 }}/{{ $license->max_users }}</span>
                                </div>
                                <div class="usage-bar">
                                    <div class="usage-fill {{ $userPercentage >= 90 ? 'danger' : ($userPercentage >= 70 ? 'warning' : 'safe') }}" 
                                         style="width: {{ min($userPercentage, 100) }}%"></div>
                                </div>
                                <small class="text-muted">{{ number_format($userPercentage, 1) }}% من الحد الأقصى</small>
                            </div>

                            <!-- المخازن -->
                            <div class="mb-4">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <h6 class="mb-0">المخازن</h6>
                                    <span class="badge bg-success">{{ $license->usage->current_warehouses ?? 0 }}/{{ $license->max_warehouses }}</span>
                                </div>
                                <div class="usage-bar">
                                    <div class="usage-fill {{ $warehousePercentage >= 90 ? 'danger' : ($warehousePercentage >= 70 ? 'warning' : 'safe') }}" 
                                         style="width: {{ min($warehousePercentage, 100) }}%"></div>
                                </div>
                                <small class="text-muted">{{ number_format($warehousePercentage, 1) }}% من الحد الأقصى</small>
                            </div>

                            <!-- الفروع -->
                            <div class="mb-3">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <h6 class="mb-0">الفروع</h6>
                                    <span class="badge bg-info">{{ $license->usage->current_branches ?? 0 }}/{{ $license->max_branches }}</span>
                                </div>
                                <div class="usage-bar">
                                    <div class="usage-fill {{ $branchPercentage >= 90 ? 'danger' : ($branchPercentage >= 70 ? 'warning' : 'safe') }}" 
                                         style="width: {{ min($branchPercentage, 100) }}%"></div>
                                </div>
                                <small class="text-muted">{{ number_format($branchPercentage, 1) }}% من الحد الأقصى</small>
                            </div>
                        @else
                            <div class="text-center py-4">
                                <i class="fas fa-chart-bar fa-3x text-muted mb-3"></i>
                                <h6 class="text-muted">لا توجد بيانات استخدام</h6>
                                <p class="text-muted">لم يتم تسجيل أي استخدام لهذا الترخيص بعد</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <!-- إجراءات سريعة -->
                <div class="master-card">
                    <div class="card-header">
                        <h6 class="mb-0">
                            <i class="fas fa-tools me-2"></i>
                            إجراءات سريعة
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="d-grid gap-2">
                            <button type="button" class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#extendModal">
                                <i class="fas fa-calendar-plus me-2"></i>
                                تمديد الترخيص
                            </button>
                            
                            <form method="POST" action="{{ route('master-admin.licenses.toggle', $license) }}">
                                @csrf
                                @method('PUT')
                                <button type="submit" class="btn {{ $license->is_active ? 'btn-danger' : 'btn-success' }} w-100">
                                    <i class="fas {{ $license->is_active ? 'fa-pause' : 'fa-play' }} me-2"></i>
                                    {{ $license->is_active ? 'تعليق الترخيص' : 'تفعيل الترخيص' }}
                                </button>
                            </form>
                            
                            <a href="{{ route('master-admin.licenses.index') }}" class="btn btn-outline-secondary">
                                <i class="fas fa-arrow-left me-2"></i>
                                العودة للقائمة
                            </a>
                        </div>
                    </div>
                </div>

                <!-- معلومات إضافية -->
                <div class="master-card">
                    <div class="card-header">
                        <h6 class="mb-0">
                            <i class="fas fa-info-circle me-2"></i>
                            معلومات إضافية
                        </h6>
                    </div>
                    <div class="card-body">
                        <p><strong>أنشأه:</strong> {{ $license->creator->name ?? 'غير محدد' }}</p>
                        <p><strong>تاريخ الإنشاء:</strong> {{ $license->created_at->format('Y-m-d H:i') }}</p>
                        <p><strong>آخر تحديث:</strong> {{ $license->updated_at->format('Y-m-d H:i') }}</p>
                        @if($license->last_check)
                            <p><strong>آخر فحص:</strong> {{ $license->last_check->diffForHumans() }}</p>
                        @endif
                        @if($license->notes)
                            <hr>
                            <p><strong>ملاحظات:</strong></p>
                            <p class="text-muted">{{ $license->notes }}</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal تمديد الترخيص -->
    <div class="modal fade" id="extendModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">تمديد ترخيص: {{ $license->client_name }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form method="POST" action="{{ route('master-admin.licenses.extend', $license) }}">
                    @csrf
                    @method('PUT')
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="months" class="form-label">عدد الأشهر</label>
                            <select class="form-select" id="months" name="months" required>
                                <option value="1">شهر واحد</option>
                                <option value="3">3 أشهر</option>
                                <option value="6">6 أشهر</option>
                                <option value="12" selected>سنة واحدة</option>
                                <option value="24">سنتان</option>
                            </select>
                        </div>
                        <div class="alert alert-info">
                            <small>
                                <strong>تاريخ الانتهاء الحالي:</strong> {{ $license->end_date->format('Y-m-d') }}<br>
                                <strong>بعد التمديد سيصبح:</strong> <span id="newDate">{{ $license->end_date->copy()->addMonths(12)->format('Y-m-d') }}</span>
                            </small>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                        <button type="submit" class="btn btn-primary">تمديد الترخيص</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    
    <style>
    .avatar-sm {
        width: 35px;
        height: 35px;
    }
    .avatar-title {
        width: 100%;
        height: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 600;
        font-size: 14px;
    }
    </style>

    <script>
    // تحديث التاريخ الجديد عند تغيير عدد الأشهر
    document.getElementById('months').addEventListener('change', function() {
        const months = parseInt(this.value);
        const currentDate = new Date('{{ $license->end_date->format('Y-m-d') }}');
        const newDate = new Date(currentDate.setMonth(currentDate.getMonth() + months));
        
        document.getElementById('newDate').textContent = newDate.toISOString().split('T')[0];
    });
    </script>
</body>
</html>
