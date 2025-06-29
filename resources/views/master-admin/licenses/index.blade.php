<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Master Admin - إدارة التراخيص</title>
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
        .license-card {
            border: 1px solid #e0e0e0;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 15px;
            transition: all 0.3s ease;
        }
        .license-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }
        .license-card.expired {
            border-color: #dc3545;
            background-color: rgba(220, 53, 69, 0.05);
        }
        .license-card.near-expiry {
            border-color: #ffc107;
            background-color: rgba(255, 193, 7, 0.05);
        }
        .license-card.active {
            border-color: #28a745;
            background-color: rgba(40, 167, 69, 0.05);
        }
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
                <li class="breadcrumb-item active text-white-50">إدارة التراخيص</li>
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

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-circle me-2"></i>
                {{ session('error') }}
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
                            إدارة التراخيص
                        </h2>
                        <p class="text-white-50">إدارة تراخيص جميع العملاء وحدود الاستخدام</p>
                    </div>
                    <div>
                        <a href="{{ route('master-admin.licenses.create') }}" class="btn btn-master text-white">
                            <i class="fas fa-plus me-2"></i>
                            إضافة ترخيص جديد
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- فلاتر البحث -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="master-card">
                    <div class="card-body">
                        <form method="GET" action="{{ route('master-admin.licenses.index') }}">
                            <div class="row">
                                <div class="col-md-3 mb-3">
                                    <label for="search" class="form-label">البحث</label>
                                    <input type="text" class="form-control" id="search" name="search" 
                                           value="{{ request('search') }}" placeholder="اسم العميل، الإيميل، أو مفتاح الترخيص...">
                                </div>
                                <div class="col-md-2 mb-3">
                                    <label for="status" class="form-label">الحالة</label>
                                    <select class="form-select" id="status" name="status">
                                        <option value="">جميع الحالات</option>
                                        <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>نشط</option>
                                        <option value="expired" {{ request('status') === 'expired' ? 'selected' : '' }}>منتهي</option>
                                        <option value="near_expiry" {{ request('status') === 'near_expiry' ? 'selected' : '' }}>قريب الانتهاء</option>
                                        <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>معلق</option>
                                    </select>
                                </div>
                                <div class="col-md-2 mb-3">
                                    <label for="type" class="form-label">النوع</label>
                                    <select class="form-select" id="type" name="type">
                                        <option value="">جميع الأنواع</option>
                                        <option value="basic" {{ request('type') === 'basic' ? 'selected' : '' }}>أساسي</option>
                                        <option value="full" {{ request('type') === 'full' ? 'selected' : '' }}>كامل</option>
                                        <option value="premium" {{ request('type') === 'premium' ? 'selected' : '' }}>مميز</option>
                                    </select>
                                </div>
                                <div class="col-md-3 mb-3">
                                    <label for="date_range" class="form-label">تاريخ الانتهاء</label>
                                    <select class="form-select" id="date_range" name="date_range">
                                        <option value="">جميع التواريخ</option>
                                        <option value="this_month" {{ request('date_range') === 'this_month' ? 'selected' : '' }}>هذا الشهر</option>
                                        <option value="next_month" {{ request('date_range') === 'next_month' ? 'selected' : '' }}>الشهر القادم</option>
                                        <option value="this_year" {{ request('date_range') === 'this_year' ? 'selected' : '' }}>هذا العام</option>
                                    </select>
                                </div>
                                <div class="col-md-2 mb-3">
                                    <label class="form-label">&nbsp;</label>
                                    <div class="d-grid">
                                        <button type="submit" class="btn btn-primary">
                                            <i class="fas fa-search me-1"></i>
                                            بحث
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- قائمة التراخيص -->
        <div class="row">
            <div class="col-12">
                <div class="master-card">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="fas fa-list me-2"></i>
                            قائمة التراخيص ({{ $licenses->total() ?? $licenses->count() }})
                        </h5>
                    </div>
                    <div class="card-body">
                        @forelse($licenses ?? [] as $license)
                            @php
                                $cardClass = 'active';
                                if ($license->end_date < now()) {
                                    $cardClass = 'expired';
                                } elseif ($license->end_date <= now()->addDays(30)) {
                                    $cardClass = 'near-expiry';
                                } elseif (!$license->is_active) {
                                    $cardClass = 'inactive';
                                }
                            @endphp
                            
                            <div class="license-card {{ $cardClass }}">
                                <div class="row align-items-center">
                                    <div class="col-md-3">
                                        <h6 class="mb-1">{{ $license->client_name }}</h6>
                                        <small class="text-muted">{{ $license->client_email }}</small>
                                        <br>
                                        <code class="small">{{ $license->license_key }}</code>
                                    </div>
                                    
                                    <div class="col-md-2">
                                        <span class="badge bg-secondary">{{ $license->license_type }}</span>
                                        <br>
                                        <small class="text-muted">
                                            ${{ number_format($license->license_cost ?? 0, 2) }}
                                        </small>
                                    </div>
                                    
                                    <div class="col-md-2">
                                        <small class="text-muted">المستخدمين:</small>
                                        <strong>{{ $license->max_users }}</strong>
                                        <br>
                                        <small class="text-muted">المخازن:</small>
                                        <strong>{{ $license->max_warehouses }}</strong>
                                    </div>
                                    
                                    <div class="col-md-2">
                                        <small class="text-muted">تاريخ الانتهاء:</small>
                                        <br>
                                        <strong>{{ $license->end_date->format('Y-m-d') }}</strong>
                                        <br>
                                        @if($license->end_date < now())
                                            <small class="text-danger">منتهي منذ {{ $license->end_date->diffForHumans() }}</small>
                                        @elseif($license->end_date <= now()->addDays(30))
                                            <small class="text-warning">ينتهي خلال {{ $license->end_date->diffForHumans() }}</small>
                                        @else
                                            <small class="text-success">ينتهي خلال {{ $license->end_date->diffForHumans() }}</small>
                                        @endif
                                    </div>
                                    
                                    <div class="col-md-1">
                                        @if($license->is_active && $license->end_date > now())
                                            <span class="badge bg-success">نشط</span>
                                        @elseif($license->end_date < now())
                                            <span class="badge bg-danger">منتهي</span>
                                        @else
                                            <span class="badge bg-warning">معلق</span>
                                        @endif
                                    </div>
                                    
                                    <div class="col-md-2">
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('master-admin.licenses.show', $license) }}" 
                                               class="btn btn-sm btn-outline-primary" title="عرض">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            
                                            <button type="button" class="btn btn-sm btn-outline-warning" 
                                                    data-bs-toggle="modal" data-bs-target="#extendModal{{ $license->id }}" title="تمديد">
                                                <i class="fas fa-calendar-plus"></i>
                                            </button>
                                            
                                            <form method="POST" action="{{ route('master-admin.licenses.toggle', $license) }}" 
                                                  style="display: inline;">
                                                @csrf
                                                @method('PUT')
                                                <button type="submit" class="btn btn-sm {{ $license->is_active ? 'btn-outline-danger' : 'btn-outline-success' }}" 
                                                        title="{{ $license->is_active ? 'تعليق' : 'تفعيل' }}">
                                                    <i class="fas {{ $license->is_active ? 'fa-pause' : 'fa-play' }}"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Modal تمديد الترخيص -->
                            <div class="modal fade" id="extendModal{{ $license->id }}" tabindex="-1">
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
                                                    <label for="months{{ $license->id }}" class="form-label">عدد الأشهر</label>
                                                    <select class="form-select" id="months{{ $license->id }}" name="months" required>
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
                                                        <strong>بعد التمديد سيصبح:</strong> <span id="newDate{{ $license->id }}">{{ $license->end_date->addMonths(12)->format('Y-m-d') }}</span>
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
                        @empty
                            <div class="text-center py-5">
                                <i class="fas fa-key fa-3x text-muted mb-3"></i>
                                <h5 class="text-muted">لا توجد تراخيص</h5>
                                <p class="text-muted">لم يتم إنشاء أي تراخيص بعد</p>
                                <a href="{{ route('master-admin.licenses.create') }}" class="btn btn-master text-white">
                                    <i class="fas fa-plus me-2"></i>
                                    إضافة أول ترخيص
                                </a>
                            </div>
                        @endforelse
                        
                        <!-- Pagination -->
                        @if(method_exists($licenses, 'hasPages') && $licenses->hasPages())
                            <div class="d-flex justify-content-center mt-4">
                                {{ $licenses->links() }}
                            </div>
                        @endif
                    </div>
                </div>
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
</body>
</html>
