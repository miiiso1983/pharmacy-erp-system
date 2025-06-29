<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Master Admin - إضافة ترخيص جديد</title>
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
        .form-section {
            background: rgba(248, 249, 250, 0.8);
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 20px;
            border: 1px solid rgba(0, 0, 0, 0.1);
        }
        .feature-checkbox {
            background: white;
            border: 1px solid #dee2e6;
            border-radius: 8px;
            padding: 10px;
            margin-bottom: 10px;
            transition: all 0.3s ease;
        }
        .feature-checkbox:hover {
            background: #f8f9fa;
            border-color: #667eea;
        }
        .feature-checkbox input:checked + label {
            color: #667eea;
            font-weight: bold;
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
                <li class="breadcrumb-item active text-white-50">إضافة ترخيص جديد</li>
            </ol>
        </nav>

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

        <!-- العنوان الرئيسي -->
        <div class="row mb-4">
            <div class="col-12">
                <h2 class="text-white fw-bold">
                    <i class="fas fa-plus me-2"></i>
                    إضافة ترخيص جديد
                </h2>
                <p class="text-white-50">إنشاء ترخيص جديد لعميل مع تحديد الحدود والمميزات</p>
            </div>
        </div>

        <!-- نموذج إضافة الترخيص -->
        <form method="POST" action="{{ route('master-admin.licenses.store') }}">
            @csrf
            
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
                                <div class="col-md-6 mb-3">
                                    <label for="client_name" class="form-label">اسم العميل *</label>
                                    <input type="text" class="form-control @error('client_name') is-invalid @enderror" 
                                           id="client_name" name="client_name" value="{{ old('client_name') }}" 
                                           placeholder="اسم الشركة أو العميل" required>
                                    @error('client_name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="col-md-6 mb-3">
                                    <label for="client_email" class="form-label">البريد الإلكتروني *</label>
                                    <input type="email" class="form-control @error('client_email') is-invalid @enderror" 
                                           id="client_email" name="client_email" value="{{ old('client_email') }}" 
                                           placeholder="email@example.com" required>
                                    @error('client_email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="col-md-6 mb-3">
                                    <label for="client_phone" class="form-label">رقم الهاتف</label>
                                    <input type="text" class="form-control @error('client_phone') is-invalid @enderror" 
                                           id="client_phone" name="client_phone" value="{{ old('client_phone') }}" 
                                           placeholder="07xxxxxxxxx">
                                    @error('client_phone')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="col-md-6 mb-3">
                                    <label for="client_company" class="form-label">اسم الشركة</label>
                                    <input type="text" class="form-control @error('client_company') is-invalid @enderror" 
                                           id="client_company" name="client_company" value="{{ old('client_company') }}" 
                                           placeholder="اسم الشركة (اختياري)">
                                    @error('client_company')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="col-12 mb-3">
                                    <label for="client_address" class="form-label">العنوان</label>
                                    <textarea class="form-control @error('client_address') is-invalid @enderror" 
                                              id="client_address" name="client_address" rows="2" 
                                              placeholder="عنوان العميل">{{ old('client_address') }}</textarea>
                                    @error('client_address')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- إعدادات الترخيص -->
                    <div class="master-card">
                        <div class="card-header">
                            <h5 class="mb-0">
                                <i class="fas fa-cog me-2"></i>
                                إعدادات الترخيص
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <label for="license_type" class="form-label">نوع الترخيص *</label>
                                    <select class="form-select @error('license_type') is-invalid @enderror" 
                                            id="license_type" name="license_type" required>
                                        <option value="">اختر نوع الترخيص</option>
                                        @foreach($licenseTypes as $key => $value)
                                            <option value="{{ $key }}" {{ old('license_type') === $key ? 'selected' : '' }}>
                                                {{ $value }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('license_type')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="col-md-4 mb-3">
                                    <label for="duration_months" class="form-label">مدة الترخيص (بالأشهر) *</label>
                                    <select class="form-select @error('duration_months') is-invalid @enderror" 
                                            id="duration_months" name="duration_months" required>
                                        <option value="">اختر المدة</option>
                                        <option value="1" {{ old('duration_months') == '1' ? 'selected' : '' }}>شهر واحد</option>
                                        <option value="3" {{ old('duration_months') == '3' ? 'selected' : '' }}>3 أشهر</option>
                                        <option value="6" {{ old('duration_months') == '6' ? 'selected' : '' }}>6 أشهر</option>
                                        <option value="12" {{ old('duration_months') == '12' ? 'selected' : '' }}>سنة واحدة</option>
                                        <option value="24" {{ old('duration_months') == '24' ? 'selected' : '' }}>سنتان</option>
                                        <option value="36" {{ old('duration_months') == '36' ? 'selected' : '' }}>3 سنوات</option>
                                    </select>
                                    @error('duration_months')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="col-md-4 mb-3">
                                    <label for="license_cost" class="form-label">تكلفة الترخيص ($)</label>
                                    <input type="number" step="0.01" min="0" 
                                           class="form-control @error('license_cost') is-invalid @enderror" 
                                           id="license_cost" name="license_cost" value="{{ old('license_cost') }}" 
                                           placeholder="0.00">
                                    @error('license_cost')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- حدود الاستخدام -->
                    <div class="master-card">
                        <div class="card-header">
                            <h5 class="mb-0">
                                <i class="fas fa-users me-2"></i>
                                حدود الاستخدام
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <label for="max_users" class="form-label">عدد المستخدمين الأقصى *</label>
                                    <input type="number" min="1" max="1000" 
                                           class="form-control @error('max_users') is-invalid @enderror" 
                                           id="max_users" name="max_users" value="{{ old('max_users', 10) }}" required>
                                    @error('max_users')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="col-md-4 mb-3">
                                    <label for="max_warehouses" class="form-label">عدد المخازن الأقصى *</label>
                                    <input type="number" min="1" max="100" 
                                           class="form-control @error('max_warehouses') is-invalid @enderror" 
                                           id="max_warehouses" name="max_warehouses" value="{{ old('max_warehouses', 1) }}" required>
                                    @error('max_warehouses')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="col-md-4 mb-3">
                                    <label for="max_branches" class="form-label">عدد الفروع الأقصى *</label>
                                    <input type="number" min="1" max="50" 
                                           class="form-control @error('max_branches') is-invalid @enderror" 
                                           id="max_branches" name="max_branches" value="{{ old('max_branches', 1) }}" required>
                                    @error('max_branches')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- ملاحظات -->
                    <div class="master-card">
                        <div class="card-header">
                            <h5 class="mb-0">
                                <i class="fas fa-sticky-note me-2"></i>
                                ملاحظات إضافية
                            </h5>
                        </div>
                        <div class="card-body">
                            <textarea class="form-control" id="notes" name="notes" rows="3" 
                                      placeholder="أي ملاحظات إضافية حول الترخيص...">{{ old('notes') }}</textarea>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4">
                    <!-- المميزات -->
                    <div class="master-card">
                        <div class="card-header">
                            <h5 class="mb-0">
                                <i class="fas fa-star me-2"></i>
                                المميزات المتاحة
                            </h5>
                        </div>
                        <div class="card-body">
                            @foreach($features as $key => $value)
                                <div class="feature-checkbox">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" 
                                               id="feature_{{ $key }}" name="features[]" value="{{ $key }}"
                                               {{ in_array($key, old('features', [])) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="feature_{{ $key }}">
                                            {{ $value }}
                                        </label>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- الوحدات -->
                    <div class="master-card">
                        <div class="card-header">
                            <h5 class="mb-0">
                                <i class="fas fa-puzzle-piece me-2"></i>
                                الوحدات المتاحة
                            </h5>
                        </div>
                        <div class="card-body">
                            @foreach($modules as $key => $value)
                                <div class="feature-checkbox">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" 
                                               id="module_{{ $key }}" name="modules[]" value="{{ $key }}"
                                               {{ in_array($key, old('modules', [])) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="module_{{ $key }}">
                                            {{ $value }}
                                        </label>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- أزرار الحفظ -->
                    <div class="master-card">
                        <div class="card-body">
                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-master text-white btn-lg">
                                    <i class="fas fa-save me-2"></i>
                                    إنشاء الترخيص
                                </button>
                                <a href="{{ route('master-admin.licenses.index') }}" class="btn btn-outline-secondary">
                                    <i class="fas fa-times me-2"></i>
                                    إلغاء
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
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
    // تحديث القيم تلقائياً حسب نوع الترخيص
    document.getElementById('license_type').addEventListener('change', function() {
        const type = this.value;
        const maxUsers = document.getElementById('max_users');
        const maxWarehouses = document.getElementById('max_warehouses');
        const maxBranches = document.getElementById('max_branches');
        
        switch(type) {
            case 'basic':
                maxUsers.value = 5;
                maxWarehouses.value = 1;
                maxBranches.value = 1;
                break;
            case 'full':
                maxUsers.value = 25;
                maxWarehouses.value = 3;
                maxBranches.value = 2;
                break;
            case 'premium':
                maxUsers.value = 100;
                maxWarehouses.value = 10;
                maxBranches.value = 5;
                break;
        }
    });
    </script>
</body>
</html>
