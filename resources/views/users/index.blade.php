@extends('layouts.app')

@section('title', __('messages.users') . ' - ' . __('messages.system_name'))

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">{{ __('navigation.dashboard') }}</a></li>
    <li class="breadcrumb-item active">{{ __('messages.users') }}</li>
@endsection

@section('content')
<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2>
                    <i class="fas fa-users me-2"></i>
                    {{ __('messages.users') }}
                    <span class="badge bg-primary ms-2">{{ $users->total() }}</span>
                </h2>
                <p class="text-muted mb-0">
                    {{ __('messages.users_management') }}
                    <span class="text-primary">•</span>
                    <span class="small">{{ $users->where('status', 'active')->count() }} {{ __('messages.active') }}</span>
                    <span class="text-muted">•</span>
                    <span class="small">{{ $users->where('status', 'inactive')->count() }} {{ __('messages.inactive') }}</span>
                </p>
            </div>
            <div class="d-flex flex-wrap gap-2">
                @can('create_users')
                    <!-- زر إضافة مستخدم جديد -->
                    <a href="{{ route('users.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus me-2"></i>
                        إضافة مستخدم جديد
                    </a>

                    <!-- زر رفع ملف Excel -->
                    <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#importUsersModal">
                        <i class="fas fa-file-excel me-2"></i>
                        رفع ملف Excel
                    </button>

                    <!-- زر تحميل نموذج Excel -->
                    <a href="{{ route('users.template') }}" class="btn btn-outline-success">
                        <i class="fas fa-download me-2"></i>
                        تحميل نموذج Excel
                    </a>

                    <!-- زر تصدير -->
                    <div class="dropdown">
                        <button class="btn btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                            <i class="fas fa-download me-2"></i>
                            تصدير البيانات
                        </button>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="{{ route('users.export', ['format' => 'excel']) }}">
                                <i class="fas fa-file-excel me-2 text-success"></i>تصدير Excel
                            </a></li>
                            <li><a class="dropdown-item" href="{{ route('users.export', ['format' => 'csv']) }}">
                                <i class="fas fa-file-csv me-2 text-info"></i>تصدير CSV
                            </a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item text-muted" href="#" onclick="alert('تصدير PDF قريباً')">
                                <i class="fas fa-file-pdf me-2"></i>تصدير PDF (قريباً)
                            </a></li>
                        </ul>
                    </div>
                @endcan
            </div>
        </div>
    </div>
</div>

<!-- إحصائيات سريعة -->
<div class="row mb-4">
    <div class="col-lg-3 col-md-6 mb-3">
        <div class="stat-card">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h4 class="mb-1">{{ $users->total() }}</h4>
                    <p class="mb-0">إجمالي المستخدمين</p>
                </div>
                <div class="fs-1 opacity-75">
                    <i class="fas fa-users"></i>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-lg-3 col-md-6 mb-3">
        <div class="stat-card success">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h4 class="mb-1">{{ $users->where('user_type', 'admin')->count() }}</h4>
                    <p class="mb-0">مديرين</p>
                </div>
                <div class="fs-1 opacity-75">
                    <i class="fas fa-user-shield"></i>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-lg-3 col-md-6 mb-3">
        <div class="stat-card warning">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h4 class="mb-1">{{ $users->where('user_type', 'employee')->count() }}</h4>
                    <p class="mb-0">موظفين</p>
                </div>
                <div class="fs-1 opacity-75">
                    <i class="fas fa-user-tie"></i>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-lg-3 col-md-6 mb-3">
        <div class="stat-card info">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h4 class="mb-1">{{ $users->where('user_type', 'customer')->count() }}</h4>
                    <p class="mb-0">عملاء</p>
                </div>
                <div class="fs-1 opacity-75">
                    <i class="fas fa-user"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- روابط سريعة -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-light">
                <h6 class="card-title mb-0">
                    <i class="fas fa-bolt me-2 text-warning"></i>
                    روابط سريعة
                </h6>
            </div>
            <div class="card-body py-3">
                <div class="row g-3">
                    <!-- إضافة مستخدم جديد -->
                    <div class="col-lg-3 col-md-6">
                        <a href="{{ route('users.create') }}" class="quick-link-card text-decoration-none">
                            <div class="d-flex align-items-center p-3 bg-primary bg-opacity-10 rounded">
                                <div class="quick-link-icon bg-primary text-white rounded-circle me-3">
                                    <i class="fas fa-user-plus"></i>
                                </div>
                                <div>
                                    <h6 class="mb-0 text-primary">إضافة مستخدم جديد</h6>
                                    <small class="text-muted">إنشاء حساب مستخدم واحد</small>
                                </div>
                            </div>
                        </a>
                    </div>

                    <!-- تحميل نموذج Excel -->
                    <div class="col-lg-3 col-md-6">
                        <a href="{{ route('users.template') }}" class="quick-link-card text-decoration-none">
                            <div class="d-flex align-items-center p-3 bg-success bg-opacity-10 rounded">
                                <div class="quick-link-icon bg-success text-white rounded-circle me-3">
                                    <i class="fas fa-download"></i>
                                </div>
                                <div>
                                    <h6 class="mb-0 text-success">تحميل نموذج Excel</h6>
                                    <small class="text-muted">نموذج لإضافة مستخدمين متعددين</small>
                                </div>
                            </div>
                        </a>
                    </div>

                    <!-- تصدير Excel -->
                    <div class="col-lg-3 col-md-6">
                        <a href="{{ route('users.export', ['format' => 'excel']) }}" class="quick-link-card text-decoration-none">
                            <div class="d-flex align-items-center p-3 bg-info bg-opacity-10 rounded">
                                <div class="quick-link-icon bg-info text-white rounded-circle me-3">
                                    <i class="fas fa-file-excel"></i>
                                </div>
                                <div>
                                    <h6 class="mb-0 text-info">تصدير Excel</h6>
                                    <small class="text-muted">تحميل جميع المستخدمين</small>
                                </div>
                            </div>
                        </a>
                    </div>

                    <!-- تصدير CSV -->
                    <div class="col-lg-3 col-md-6">
                        <a href="{{ route('users.export', ['format' => 'csv']) }}" class="quick-link-card text-decoration-none">
                            <div class="d-flex align-items-center p-3 bg-warning bg-opacity-10 rounded">
                                <div class="quick-link-icon bg-warning text-white rounded-circle me-3">
                                    <i class="fas fa-file-csv"></i>
                                </div>
                                <div>
                                    <h6 class="mb-0 text-warning">تصدير CSV</h6>
                                    <small class="text-muted">ملف CSV للمستخدمين</small>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>

                <!-- رابط رفع Excel (زر كبير) -->
                <div class="row mt-3">
                    <div class="col-12">
                        <button type="button" class="btn btn-outline-success btn-lg w-100" data-bs-toggle="modal" data-bs-target="#importUsersModal">
                            <i class="fas fa-upload me-2"></i>
                            رفع ملف Excel لإضافة مستخدمين متعددين
                            <small class="d-block mt-1">اختر ملف Excel لاستيراد مئات المستخدمين دفعة واحدة</small>
                        </button>
                    </div>
                </div>

                <!-- إرشادات سريعة -->
                <div class="row mt-4">
                    <div class="col-12">
                        <div class="alert alert-info border-0">
                            <h6 class="alert-heading">
                                <i class="fas fa-lightbulb me-2"></i>
                                إرشادات سريعة:
                            </h6>
                            <div class="row">
                                <div class="col-md-6">
                                    <ul class="mb-0 small">
                                        <li><strong>إضافة مستخدم واحد:</strong> استخدم "إضافة مستخدم جديد"</li>
                                        <li><strong>إضافة مستخدمين متعددين:</strong> حمل النموذج، املأه، ثم ارفعه</li>
                                    </ul>
                                </div>
                                <div class="col-md-6">
                                    <ul class="mb-0 small">
                                        <li><strong>تصدير البيانات:</strong> اختر Excel للتحرير أو CSV للتحليل</li>
                                        <li><strong>النموذج:</strong> يحتوي على أمثلة وتعليمات مفصلة</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- فلاتر البحث -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <form method="GET" action="{{ route('users.index') }}">
                    <div class="row">
                        <div class="col-md-3">
                            <label class="form-label">الاسم</label>
                            <input type="text" class="form-control" name="name" 
                                   value="{{ request('name') }}" placeholder="ابحث بالاسم">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">البريد الإلكتروني</label>
                            <input type="email" class="form-control" name="email" 
                                   value="{{ request('email') }}" placeholder="ابحث بالبريد الإلكتروني">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">نوع المستخدم</label>
                            <select class="form-select" name="user_type">
                                <option value="">جميع الأنواع</option>
                                <option value="admin" {{ request('user_type') == 'admin' ? 'selected' : '' }}>مدير</option>
                                <option value="employee" {{ request('user_type') == 'employee' ? 'selected' : '' }}>موظف</option>
                                <option value="customer" {{ request('user_type') == 'customer' ? 'selected' : '' }}>عميل</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">الحالة</label>
                            <select class="form-select" name="status">
                                <option value="">جميع الحالات</option>
                                <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>نشط</option>
                                <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>غير نشط</option>
                            </select>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-12">
                            <button type="submit" class="btn btn-primary me-2">
                                <i class="fas fa-search me-2"></i>بحث
                            </button>
                            <a href="{{ route('users.index') }}" class="btn btn-outline-secondary">
                                <i class="fas fa-times me-2"></i>إلغاء
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- جدول المستخدمين -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-list me-2"></i>
                    قائمة المستخدمين ({{ $users->total() }})
                </h5>
            </div>
            <div class="card-body">
                @if($users->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>الاسم</th>
                                    <th>البريد الإلكتروني</th>
                                    <th>نوع المستخدم</th>
                                    <th>الهاتف</th>
                                    <th>الأدوار</th>
                                    <th>الحالة</th>
                                    <th>تاريخ التسجيل</th>
                                    <th>الإجراءات</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($users as $user)
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="avatar-circle me-3">
                                                    {{ strtoupper(substr($user->name, 0, 2)) }}
                                                </div>
                                                <div>
                                                    <strong>{{ $user->name }}</strong>
                                                    @if($user->company_name)
                                                        <br><small class="text-muted">{{ $user->company_name }}</small>
                                                    @endif
                                                </div>
                                            </div>
                                        </td>
                                        <td>{{ $user->email }}</td>
                                        <td>
                                            @switch($user->user_type)
                                                @case('admin')
                                                    <span class="badge bg-danger">مدير</span>
                                                    @break
                                                @case('employee')
                                                    <span class="badge bg-warning">موظف</span>
                                                    @break
                                                @case('customer')
                                                    <span class="badge bg-info">عميل</span>
                                                    @break
                                            @endswitch
                                        </td>
                                        <td>{{ $user->phone ?? 'غير محدد' }}</td>
                                        <td>
                                            <span class="text-muted">غير محدد</span>
                                        </td>
                                        <td>
                                            @if($user->status === 'active')
                                                <span class="badge bg-success">نشط</span>
                                            @else
                                                <span class="badge bg-secondary">غير نشط</span>
                                            @endif
                                        </td>
                                        <td>{{ $user->created_at->format('Y-m-d') }}</td>
                                        <td>
                                            <div class="btn-group btn-group-sm">
                                                <a href="{{ route('users.show', $user->id) }}" 
                                                   class="btn btn-outline-primary" title="عرض التفاصيل">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                @can('edit_users')
                                                    <a href="{{ route('users.edit', $user->id) }}" 
                                                       class="btn btn-outline-warning" title="تعديل">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                @endcan
                                                @can('delete_users')
                                                    @if($user->id !== auth()->id())
                                                        <form method="POST" action="{{ route('users.destroy', $user->id) }}" 
                                                              class="d-inline" onsubmit="return confirm('هل أنت متأكد من حذف هذا المستخدم؟')">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-outline-danger" title="حذف">
                                                                <i class="fas fa-trash"></i>
                                                            </button>
                                                        </form>
                                                    @endif
                                                @endcan
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    
                    <!-- Pagination -->
                    <div class="d-flex justify-content-center mt-4">
                        {{ $users->links() }}
                    </div>
                @else
                    <div class="text-center py-5">
                        <i class="fas fa-users fa-3x text-muted mb-3"></i>
                        <h5 class="text-muted">لا توجد مستخدمين</h5>
                        <p class="text-muted">لم يتم العثور على أي مستخدمين تطابق معايير البحث</p>
                        @can('create_users')
                            <a href="{{ route('users.create') }}" class="btn btn-primary">
                                <i class="fas fa-plus me-2"></i>
                                إضافة مستخدم جديد
                            </a>
                        @endcan
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Modal رفع ملف Excel -->
<div class="modal fade" id="importUsersModal" tabindex="-1" aria-labelledby="importUsersModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="importUsersModalLabel">
                    <i class="fas fa-file-excel me-2"></i>
                    رفع ملف Excel للمستخدمين
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('users.import') }}" method="POST" enctype="multipart/form-data" id="importForm">
                @csrf
                <div class="modal-body">
                    <div class="row">
                        <div class="col-12">
                            <!-- تعليمات -->
                            <div class="alert alert-info">
                                <h6 class="alert-heading">
                                    <i class="fas fa-info-circle me-2"></i>
                                    تعليمات مهمة:
                                </h6>
                                <ul class="mb-0">
                                    <li>يجب أن يكون الملف بصيغة Excel (.xlsx أو .xls)</li>
                                    <li>الصف الأول يجب أن يحتوي على أسماء الأعمدة</li>
                                    <li>الأعمدة المطلوبة: الاسم، البريد الإلكتروني، كلمة المرور، نوع المستخدم</li>
                                    <li>الأعمدة الاختيارية: الهاتف، العنوان، اسم الشركة، الرقم الضريبي</li>
                                </ul>
                            </div>

                            <!-- تحميل نموذج -->
                            <div class="mb-3">
                                <label class="form-label">تحميل نموذج Excel:</label>
                                <div>
                                    <a href="{{ route('users.template') }}" class="btn btn-outline-success btn-sm">
                                        <i class="fas fa-download me-2"></i>
                                        تحميل نموذج Excel
                                    </a>
                                    <small class="text-muted d-block mt-1">
                                        احفظ هذا النموذج واملأه بالبيانات المطلوبة
                                    </small>
                                </div>
                            </div>

                            <!-- رفع الملف -->
                            <div class="mb-3">
                                <label for="excel_file" class="form-label">اختر ملف Excel:</label>
                                <input type="file" class="form-control" id="excel_file" name="excel_file"
                                       accept=".xlsx,.xls" required>
                                <div class="form-text">الحد الأقصى لحجم الملف: 5MB</div>
                            </div>

                            <!-- خيارات الاستيراد -->
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="skip_duplicates"
                                               name="skip_duplicates" value="1" checked>
                                        <label class="form-check-label" for="skip_duplicates">
                                            تجاهل المستخدمين المكررين
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="send_notifications"
                                               name="send_notifications" value="1">
                                        <label class="form-check-label" for="send_notifications">
                                            إرسال إشعارات للمستخدمين الجدد
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <!-- معاينة البيانات -->
                            <div id="preview_section" class="mt-4" style="display: none;">
                                <h6>معاينة البيانات:</h6>
                                <div class="table-responsive">
                                    <table class="table table-sm table-bordered" id="preview_table">
                                        <thead class="table-light">
                                            <tr id="preview_headers"></tr>
                                        </thead>
                                        <tbody id="preview_body"></tbody>
                                    </table>
                                </div>
                                <div id="preview_stats" class="text-muted small"></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times me-2"></i>إلغاء
                    </button>
                    <button type="button" class="btn btn-info" id="preview_btn" style="display: none;">
                        <i class="fas fa-eye me-2"></i>معاينة البيانات
                    </button>
                    <button type="submit" class="btn btn-success" id="import_btn" disabled>
                        <i class="fas fa-upload me-2"></i>
                        <span class="btn-text">رفع المستخدمين</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .stat-card {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 1.5rem;
        border-radius: 15px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        transition: transform 0.3s ease;
    }

    .stat-card:hover {
        transform: translateY(-5px);
    }

    .stat-card.success {
        background: linear-gradient(135deg, #56ab2f 0%, #a8e6cf 100%);
    }

    .stat-card.warning {
        background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
    }

    .stat-card.info {
        background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
    }

    .avatar-circle {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: bold;
        font-size: 14px;
    }

    /* روابط سريعة */
    .quick-link-card {
        transition: all 0.3s ease;
        display: block;
    }

    .quick-link-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    }

    .quick-link-icon {
        width: 45px;
        height: 45px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 18px;
    }

    .quick-link-card .bg-primary.bg-opacity-10:hover {
        background-color: rgba(13, 110, 253, 0.15) !important;
    }

    .quick-link-card .bg-success.bg-opacity-10:hover {
        background-color: rgba(25, 135, 84, 0.15) !important;
    }

    .quick-link-card .bg-info.bg-opacity-10:hover {
        background-color: rgba(13, 202, 240, 0.15) !important;
    }

    .quick-link-card .bg-warning.bg-opacity-10:hover {
        background-color: rgba(255, 193, 7, 0.15) !important;
    }

    /* تحسين الأزرار */
    .btn-lg {
        padding: 1rem 2rem;
        font-size: 1.1rem;
    }

    .btn-outline-success:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(25, 135, 84, 0.3);
    }

    /* تحسين البطاقات */
    .card {
        transition: all 0.3s ease;
    }

    .card:hover {
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15) !important;
    }

    .file-drop-zone {
        border: 2px dashed #dee2e6;
        border-radius: 8px;
        padding: 2rem;
        text-align: center;
        transition: all 0.3s ease;
        cursor: pointer;
    }

    .file-drop-zone:hover,
    .file-drop-zone.dragover {
        border-color: #28a745;
        background-color: #f8fff9;
    }

    .preview-table {
        max-height: 300px;
        overflow-y: auto;
    }

    .loading-spinner {
        display: none;
    }

    .loading .loading-spinner {
        display: inline-block;
    }

    .loading .btn-text {
        display: none;
    }
</style>
@endpush

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const fileInput = document.getElementById('excel_file');
    const previewBtn = document.getElementById('preview_btn');
    const importBtn = document.getElementById('import_btn');
    const previewSection = document.getElementById('preview_section');
    const importForm = document.getElementById('importForm');

    let excelData = null;

    // معالجة اختيار الملف
    fileInput.addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            validateAndPreviewFile(file);
        }
    });

    // معالجة السحب والإفلات
    const dropZone = document.querySelector('.file-drop-zone');
    if (dropZone) {
        dropZone.addEventListener('dragover', function(e) {
            e.preventDefault();
            this.classList.add('dragover');
        });

        dropZone.addEventListener('dragleave', function(e) {
            e.preventDefault();
            this.classList.remove('dragover');
        });

        dropZone.addEventListener('drop', function(e) {
            e.preventDefault();
            this.classList.remove('dragover');
            const files = e.dataTransfer.files;
            if (files.length > 0) {
                fileInput.files = files;
                validateAndPreviewFile(files[0]);
            }
        });
    }

    // التحقق من الملف ومعاينته
    function validateAndPreviewFile(file) {
        // التحقق من نوع الملف
        const allowedTypes = [
            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'application/vnd.ms-excel'
        ];

        if (!allowedTypes.includes(file.type)) {
            showAlert('يجب أن يكون الملف بصيغة Excel (.xlsx أو .xls)', 'error');
            return;
        }

        // التحقق من حجم الملف (5MB)
        if (file.size > 5 * 1024 * 1024) {
            showAlert('حجم الملف كبير جداً. الحد الأقصى 5MB', 'error');
            return;
        }

        // قراءة الملف
        const reader = new FileReader();
        reader.onload = function(e) {
            try {
                const data = new Uint8Array(e.target.result);
                const workbook = XLSX.read(data, { type: 'array' });
                const firstSheet = workbook.Sheets[workbook.SheetNames[0]];
                const jsonData = XLSX.utils.sheet_to_json(firstSheet, { header: 1 });

                if (jsonData.length < 2) {
                    showAlert('الملف فارغ أو لا يحتوي على بيانات كافية', 'error');
                    return;
                }

                excelData = jsonData;
                previewBtn.style.display = 'inline-block';
                importBtn.disabled = false;
                showAlert('تم تحميل الملف بنجاح. يمكنك الآن معاينة البيانات أو رفعها مباشرة', 'success');

            } catch (error) {
                showAlert('خطأ في قراءة الملف. تأكد من أن الملف صحيح', 'error');
                console.error('Error reading file:', error);
            }
        };

        reader.readAsArrayBuffer(file);
    }

    // معاينة البيانات
    previewBtn.addEventListener('click', function() {
        if (!excelData) return;

        const headers = excelData[0];
        const rows = excelData.slice(1, 6); // أول 5 صفوف فقط للمعاينة

        // إنشاء headers
        const headerRow = document.getElementById('preview_headers');
        headerRow.innerHTML = headers.map(header => `<th>${header || 'عمود فارغ'}</th>`).join('');

        // إنشاء البيانات
        const tbody = document.getElementById('preview_body');
        tbody.innerHTML = rows.map(row =>
            `<tr>${headers.map((_, index) => `<td>${row[index] || ''}</td>`).join('')}</tr>`
        ).join('');

        // إحصائيات
        const stats = document.getElementById('preview_stats');
        stats.innerHTML = `
            إجمالي الصفوف: ${excelData.length - 1} |
            الأعمدة: ${headers.length} |
            معاينة أول 5 صفوف
        `;

        previewSection.style.display = 'block';
    });

    // معالجة إرسال النموذج
    importForm.addEventListener('submit', function(e) {
        e.preventDefault();

        if (!fileInput.files[0]) {
            showAlert('يرجى اختيار ملف Excel أولاً', 'error');
            return;
        }

        // إظهار حالة التحميل
        importBtn.classList.add('loading');
        importBtn.disabled = true;

        // إرسال النموذج
        const formData = new FormData(this);

        fetch(this.action, {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showAlert(data.message, 'success');
                setTimeout(() => {
                    window.location.reload();
                }, 2000);
            } else {
                showAlert(data.message || 'حدث خطأ أثناء رفع الملف', 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showAlert('حدث خطأ أثناء رفع الملف', 'error');
        })
        .finally(() => {
            importBtn.classList.remove('loading');
            importBtn.disabled = false;
        });
    });

    // دالة إظهار التنبيهات
    function showAlert(message, type) {
        const alertClass = type === 'success' ? 'alert-success' : 'alert-danger';
        const alertHtml = `
            <div class="alert ${alertClass} alert-dismissible fade show" role="alert">
                ${message}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        `;

        // إضافة التنبيه في أعلى Modal
        const modalBody = document.querySelector('#importUsersModal .modal-body');
        const existingAlert = modalBody.querySelector('.alert');
        if (existingAlert) {
            existingAlert.remove();
        }
        modalBody.insertAdjacentHTML('afterbegin', alertHtml);
    }
});
</script>
@endpush
