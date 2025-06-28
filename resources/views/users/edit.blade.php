@extends('layouts.app')

@section('title', 'تعديل المستخدم')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <!-- Header -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h1 class="h3 mb-0">تعديل المستخدم</h1>
                    <p class="text-muted">تعديل معلومات المستخدم: {{ $user->name }}</p>
                </div>
                <div>
                    <a href="{{ route('users.show', $user->id) }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-right me-2"></i>العودة للتفاصيل
                    </a>
                    <a href="{{ route('users.index') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-list me-2"></i>القائمة
                    </a>
                </div>
            </div>

            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">
                                <i class="fas fa-user-edit me-2"></i>تعديل معلومات المستخدم
                            </h5>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('users.update', $user->id) }}" method="POST" id="userEditForm">
                                @csrf
                                @method('PUT')
                                
                                <div class="row">
                                    <!-- الاسم الكامل -->
                                    <div class="col-md-6 mb-3">
                                        <label for="name" class="form-label">الاسم الكامل <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                               id="name" name="name" value="{{ old('name', $user->name) }}" required>
                                        @error('name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <!-- البريد الإلكتروني -->
                                    <div class="col-md-6 mb-3">
                                        <label for="email" class="form-label">البريد الإلكتروني <span class="text-danger">*</span></label>
                                        <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                               id="email" name="email" value="{{ old('email', $user->email) }}" required>
                                        @error('email')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <!-- كلمة المرور الجديدة -->
                                    <div class="col-md-6 mb-3">
                                        <label for="password" class="form-label">كلمة المرور الجديدة</label>
                                        <input type="password" class="form-control @error('password') is-invalid @enderror" 
                                               id="password" name="password">
                                        @error('password')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        <div class="form-text">اتركها فارغة إذا كنت لا تريد تغيير كلمة المرور</div>
                                    </div>

                                    <!-- تأكيد كلمة المرور -->
                                    <div class="col-md-6 mb-3">
                                        <label for="password_confirmation" class="form-label">تأكيد كلمة المرور الجديدة</label>
                                        <input type="password" class="form-control" 
                                               id="password_confirmation" name="password_confirmation">
                                    </div>

                                    <!-- نوع المستخدم -->
                                    <div class="col-md-6 mb-3">
                                        <label for="user_type" class="form-label">نوع المستخدم <span class="text-danger">*</span></label>
                                        <select class="form-select @error('user_type') is-invalid @enderror" 
                                                id="user_type" name="user_type" required>
                                            <option value="">اختر نوع المستخدم</option>
                                            <option value="admin" {{ old('user_type', $user->user_type) === 'admin' ? 'selected' : '' }}>مدير</option>
                                            <option value="manager" {{ old('user_type', $user->user_type) === 'manager' ? 'selected' : '' }}>مدير مساعد</option>
                                            <option value="employee" {{ old('user_type', $user->user_type) === 'employee' ? 'selected' : '' }}>موظف</option>
                                            <option value="customer" {{ old('user_type', $user->user_type) === 'customer' ? 'selected' : '' }}>عميل</option>
                                        </select>
                                        @error('user_type')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <!-- الحالة -->
                                    <div class="col-md-6 mb-3">
                                        <label for="status" class="form-label">الحالة <span class="text-danger">*</span></label>
                                        <select class="form-select @error('status') is-invalid @enderror" 
                                                id="status" name="status" required>
                                            <option value="active" {{ old('status', $user->status) === 'active' ? 'selected' : '' }}>نشط</option>
                                            <option value="inactive" {{ old('status', $user->status) === 'inactive' ? 'selected' : '' }}>غير نشط</option>
                                        </select>
                                        @error('status')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <!-- رقم الهاتف -->
                                    <div class="col-md-6 mb-3">
                                        <label for="phone" class="form-label">رقم الهاتف</label>
                                        <input type="tel" class="form-control @error('phone') is-invalid @enderror" 
                                               id="phone" name="phone" value="{{ old('phone', $user->phone) }}">
                                        @error('phone')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <!-- اسم الشركة -->
                                    <div class="col-md-6 mb-3">
                                        <label for="company_name" class="form-label">اسم الشركة</label>
                                        <input type="text" class="form-control @error('company_name') is-invalid @enderror" 
                                               id="company_name" name="company_name" value="{{ old('company_name', $user->company_name) }}">
                                        @error('company_name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <!-- الرقم الضريبي -->
                                    <div class="col-md-6 mb-3">
                                        <label for="tax_number" class="form-label">الرقم الضريبي</label>
                                        <input type="text" class="form-control @error('tax_number') is-invalid @enderror" 
                                               id="tax_number" name="tax_number" value="{{ old('tax_number', $user->tax_number) }}">
                                        @error('tax_number')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <!-- العنوان -->
                                    <div class="col-12 mb-3">
                                        <label for="address" class="form-label">العنوان</label>
                                        <textarea class="form-control @error('address') is-invalid @enderror" 
                                                  id="address" name="address" rows="3">{{ old('address', $user->address) }}</textarea>
                                        @error('address')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <!-- الأدوار والصلاحيات -->
                                @if(isset($roles) && isset($groupedPermissions))
                                <div class="row mt-4">
                                    <div class="col-12">
                                        <h5 class="mb-3">
                                            <i class="fas fa-shield-alt me-2"></i>
                                            الأدوار والصلاحيات
                                        </h5>
                                    </div>
                                </div>

                                <!-- الأدوار الحالية -->
                                <div class="row">
                                    <div class="col-12 mb-4">
                                        <label class="form-label">الأدوار</label>
                                        <div class="card">
                                            <div class="card-body">
                                                <div class="row">
                                                    @foreach($roles as $role)
                                                    <div class="col-md-4 mb-2">
                                                        <div class="form-check">
                                                            @php
                                                                $hasRole = false;
                                                                try {
                                                                    $hasRole = $user->hasRole($role->name);
                                                                } catch (\Exception $e) {
                                                                    $hasRole = false;
                                                                }
                                                            @endphp
                                                            <input class="form-check-input" type="checkbox"
                                                                   id="role_{{ $role->id }}" name="roles[]"
                                                                   value="{{ $role->name }}"
                                                                   {{ $hasRole ? 'checked' : '' }}>
                                                            <label class="form-check-label" for="role_{{ $role->id }}">
                                                                <strong>{{ $role->description ?? $role->name }}</strong>
                                                                <br><small class="text-muted">{{ $role->permissions()->count() }} صلاحية</small>
                                                            </label>
                                                        </div>
                                                    </div>
                                                    @endforeach
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- الصلاحيات المخصصة -->
                                <div class="row">
                                    <div class="col-12 mb-4">
                                        <label class="form-label">صلاحيات إضافية مخصصة</label>
                                        <div class="card">
                                            <div class="card-header">
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <span>اختر الصلاحيات المطلوبة</span>
                                                    <div>
                                                        <button type="button" class="btn btn-sm btn-outline-primary" onclick="selectAllPermissions()">
                                                            تحديد الكل
                                                        </button>
                                                        <button type="button" class="btn btn-sm btn-outline-secondary" onclick="clearAllPermissions()">
                                                            إلغاء الكل
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="card-body">
                                                @foreach($groupedPermissions as $category => $group)
                                                <div class="permission-group mb-4">
                                                    <h6 class="text-primary mb-3">
                                                        <i class="fas fa-folder me-2"></i>
                                                        {{ $group['name'] }}
                                                        <button type="button" class="btn btn-sm btn-outline-info ms-2"
                                                                onclick="toggleCategoryPermissions('{{ $category }}')">
                                                            تحديد/إلغاء الفئة
                                                        </button>
                                                    </h6>
                                                    <div class="row">
                                                        @foreach($group['permissions'] as $permission)
                                                        <div class="col-md-4 col-lg-3 mb-2">
                                                            <div class="form-check">
                                                                @php
                                                                    $hasPermission = false;
                                                                    try {
                                                                        $hasPermission = $user->hasPermissionTo($permission['name']);
                                                                    } catch (\Exception $e) {
                                                                        $hasPermission = false;
                                                                    }
                                                                @endphp
                                                                <input class="form-check-input permission-checkbox category-{{ $category }}"
                                                                       type="checkbox"
                                                                       id="permission_{{ $permission['id'] }}"
                                                                       name="permissions[]"
                                                                       value="{{ $permission['name'] }}"
                                                                       {{ $hasPermission ? 'checked' : '' }}>
                                                                <label class="form-check-label" for="permission_{{ $permission['id'] }}">
                                                                    <small>{{ $permission['description'] }}</small>
                                                                </label>
                                                            </div>
                                                        </div>
                                                        @endforeach
                                                    </div>
                                                </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @endif

                                <!-- معلومات إضافية -->
                                <div class="row">
                                    <div class="col-12">
                                        <div class="alert alert-info">
                                            <h6 class="alert-heading">معلومات إضافية:</h6>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <small><strong>تاريخ التسجيل:</strong> {{ $user->created_at->format('Y-m-d H:i') }}</small>
                                                </div>
                                                <div class="col-md-6">
                                                    <small><strong>آخر تحديث:</strong> {{ $user->updated_at->format('Y-m-d H:i') }}</small>
                                                </div>
                                                @if(isset($user->last_login_at))
                                                <div class="col-md-6">
                                                    <small><strong>آخر تسجيل دخول:</strong> 
                                                        {{ $user->last_login_at ? $user->last_login_at->format('Y-m-d H:i') : 'لم يسجل دخول بعد' }}
                                                    </small>
                                                </div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- أزرار الإجراءات -->
                                <div class="d-flex justify-content-end gap-2">
                                    <a href="{{ route('users.show', $user->id) }}" class="btn btn-secondary">
                                        <i class="fas fa-times me-2"></i>إلغاء
                                    </a>
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-save me-2"></i>حفظ التغييرات
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // التحقق من تطابق كلمات المرور
    const password = document.getElementById('password');
    const passwordConfirmation = document.getElementById('password_confirmation');
    
    function validatePasswords() {
        if (password.value && password.value !== passwordConfirmation.value) {
            passwordConfirmation.setCustomValidity('كلمات المرور غير متطابقة');
        } else {
            passwordConfirmation.setCustomValidity('');
        }
    }
    
    password.addEventListener('input', validatePasswords);
    passwordConfirmation.addEventListener('input', validatePasswords);
    
    // التحقق من قوة كلمة المرور
    password.addEventListener('input', function() {
        const value = this.value;
        const feedback = this.parentNode.querySelector('.form-text');
        
        if (!value) {
            feedback.textContent = 'اتركها فارغة إذا كنت لا تريد تغيير كلمة المرور';
            feedback.className = 'form-text text-muted';
        } else if (value.length < 8) {
            feedback.textContent = 'كلمة المرور قصيرة جداً (8 أحرف على الأقل)';
            feedback.className = 'form-text text-danger';
        } else if (!/(?=.*[a-z])(?=.*[A-Z])(?=.*\d)/.test(value)) {
            feedback.textContent = 'يجب أن تحتوي على حرف كبير وصغير ورقم';
            feedback.className = 'form-text text-warning';
        } else {
            feedback.textContent = 'كلمة مرور قوية';
            feedback.className = 'form-text text-success';
        }
    });

    // إدارة الصلاحيات
    const userTypeSelect = document.getElementById('user_type');
    if (userTypeSelect) {
        userTypeSelect.addEventListener('change', function() {
            suggestPermissions(this.value);
        });
    }
});

// تحديد جميع الصلاحيات
function selectAllPermissions() {
    const checkboxes = document.querySelectorAll('.permission-checkbox');
    checkboxes.forEach(checkbox => {
        checkbox.checked = true;
    });
}

// إلغاء تحديد جميع الصلاحيات
function clearAllPermissions() {
    const checkboxes = document.querySelectorAll('.permission-checkbox');
    checkboxes.forEach(checkbox => {
        checkbox.checked = false;
    });
}

// تحديد/إلغاء صلاحيات فئة معينة
function toggleCategoryPermissions(category) {
    const checkboxes = document.querySelectorAll('.category-' + category);
    const allChecked = Array.from(checkboxes).every(cb => cb.checked);

    checkboxes.forEach(checkbox => {
        checkbox.checked = !allChecked;
    });
}

// اقتراح صلاحيات حسب نوع المستخدم
function suggestPermissions(userType) {
    // الصلاحيات المقترحة حسب نوع المستخدم
    const suggestions = {
        'admin': [
            'dashboard.view', 'dashboard.stats', 'dashboard.charts',
            'users.view', 'users.create', 'users.edit', 'users.delete',
            'employees.view', 'employees.create', 'employees.edit',
            'warehouses.view', 'warehouses.create', 'warehouses.edit',
            'items.view', 'items.create', 'items.edit',
            'orders.view', 'orders.create', 'orders.edit', 'orders.approve',
            'invoices.view', 'invoices.create', 'invoices.edit',
            'reports.view', 'reports.export'
        ],
        'manager': [
            'dashboard.view', 'dashboard.stats',
            'users.view', 'users.create', 'users.edit',
            'employees.view', 'employees.create', 'employees.edit',
            'orders.view', 'orders.create', 'orders.edit',
            'invoices.view', 'invoices.create',
            'reports.view'
        ],
        'employee': [
            'dashboard.view',
            'orders.view', 'orders.create',
            'items.view'
        ],
        'customer': [
            'orders.view', 'orders.create',
            'invoices.view'
        ]
    };

    const suggestedPermissions = suggestions[userType] || [];

    if (suggestedPermissions.length > 0 && confirm(`هل تريد تطبيق الصلاحيات المقترحة لنوع المستخدم: ${userType}؟`)) {
        // إلغاء تحديد جميع الصلاحيات أولاً
        clearAllPermissions();

        // تحديد الصلاحيات المقترحة
        suggestedPermissions.forEach(permission => {
            const checkbox = document.querySelector(`input[value="${permission}"]`);
            if (checkbox) {
                checkbox.checked = true;
            }
        });

        showNotification(`تم تطبيق ${suggestedPermissions.length} صلاحية مقترحة`, 'success');
    }
}

// إظهار إشعار
function showNotification(message, type = 'info') {
    const alertClass = type === 'success' ? 'alert-success' :
                      type === 'error' ? 'alert-danger' : 'alert-info';

    const alertHtml = `
        <div class="alert ${alertClass} alert-dismissible fade show" role="alert">
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    `;

    // إضافة الإشعار في أعلى النموذج
    const form = document.getElementById('userEditForm');
    const existingAlert = form.querySelector('.alert');
    if (existingAlert) {
        existingAlert.remove();
    }
    form.insertAdjacentHTML('afterbegin', alertHtml);

    // إزالة الإشعار تلقائياً بعد 5 ثوان
    setTimeout(() => {
        const alert = form.querySelector('.alert');
        if (alert) {
            alert.remove();
        }
    }, 5000);
}
</script>
@endpush
