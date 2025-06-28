@extends('layouts.app')

@section('title', 'إضافة موظف جديد - الموارد البشرية')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">{{ __('navigation.dashboard') }}</a></li>
    <li class="breadcrumb-item"><a href="{{ route('hr.index') }}">الموارد البشرية</a></li>
    <li class="breadcrumb-item"><a href="{{ route('hr.employees') }}">الموظفون</a></li>
    <li class="breadcrumb-item active">إضافة موظف</li>
@endsection

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0">
                <i class="fas fa-user-plus me-2"></i>
                إضافة موظف جديد
            </h1>
            <p class="text-muted">إضافة موظف جديد إلى نظام الموارد البشرية</p>
        </div>
        <div>
            <a href="{{ route('hr.employees') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left me-2"></i>
                العودة للقائمة
            </a>
        </div>
    </div>

    <form action="{{ route('hr.employees.store') }}" method="POST" id="employeeForm">
        @csrf
        
        <div class="row">
            <div class="col-lg-8">
                <!-- المعلومات الشخصية -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-user me-2"></i>
                            المعلومات الشخصية
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="first_name" class="form-label">الاسم الأول <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('first_name') is-invalid @enderror" 
                                       id="first_name" name="first_name" value="{{ old('first_name') }}" required>
                                @error('first_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="last_name" class="form-label">الاسم الأخير <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('last_name') is-invalid @enderror" 
                                       id="last_name" name="last_name" value="{{ old('last_name') }}" required>
                                @error('last_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="email" class="form-label">البريد الإلكتروني <span class="text-danger">*</span></label>
                                <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                       id="email" name="email" value="{{ old('email') }}" required>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="phone" class="form-label">رقم الهاتف <span class="text-danger">*</span></label>
                                <input type="tel" class="form-control @error('phone') is-invalid @enderror" 
                                       id="phone" name="phone" value="{{ old('phone') }}" required>
                                @error('phone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="birth_date" class="form-label">تاريخ الميلاد</label>
                                <input type="date" class="form-control @error('birth_date') is-invalid @enderror" 
                                       id="birth_date" name="birth_date" value="{{ old('birth_date') }}">
                                @error('birth_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="gender" class="form-label">الجنس</label>
                                <select class="form-select @error('gender') is-invalid @enderror" id="gender" name="gender">
                                    <option value="">اختر الجنس</option>
                                    <option value="male" {{ old('gender') == 'male' ? 'selected' : '' }}>ذكر</option>
                                    <option value="female" {{ old('gender') == 'female' ? 'selected' : '' }}>أنثى</option>
                                </select>
                                @error('gender')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="address" class="form-label">العنوان</label>
                            <textarea class="form-control @error('address') is-invalid @enderror" 
                                      id="address" name="address" rows="3">{{ old('address') }}</textarea>
                            @error('address')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- معلومات العمل -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-briefcase me-2"></i>
                            معلومات العمل
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="employee_id" class="form-label">رقم الموظف</label>
                                <input type="text" class="form-control @error('employee_id') is-invalid @enderror" 
                                       id="employee_id" name="employee_id" value="{{ old('employee_id') }}" 
                                       placeholder="سيتم إنشاؤه تلقائياً إذا ترك فارغاً">
                                @error('employee_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="department_id" class="form-label">القسم <span class="text-danger">*</span></label>
                                <select class="form-select @error('department_id') is-invalid @enderror" 
                                        id="department_id" name="department_id" required>
                                    <option value="">اختر القسم</option>
                                    @foreach($departments as $department)
                                        <option value="{{ $department->id }}" 
                                                {{ old('department_id') == $department->id ? 'selected' : '' }}>
                                            {{ $department->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('department_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="position" class="form-label">المنصب <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('position') is-invalid @enderror" 
                                       id="position" name="position" value="{{ old('position') }}" required>
                                @error('position')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="hire_date" class="form-label">تاريخ التوظيف <span class="text-danger">*</span></label>
                                <input type="date" class="form-control @error('hire_date') is-invalid @enderror" 
                                       id="hire_date" name="hire_date" value="{{ old('hire_date', date('Y-m-d')) }}" required>
                                @error('hire_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="employment_type" class="form-label">نوع التوظيف</label>
                                <select class="form-select @error('employment_type') is-invalid @enderror" 
                                        id="employment_type" name="employment_type">
                                    <option value="">اختر نوع التوظيف</option>
                                    <option value="full_time" {{ old('employment_type') == 'full_time' ? 'selected' : '' }}>دوام كامل</option>
                                    <option value="part_time" {{ old('employment_type') == 'part_time' ? 'selected' : '' }}>دوام جزئي</option>
                                    <option value="contract" {{ old('employment_type') == 'contract' ? 'selected' : '' }}>عقد</option>
                                    <option value="temporary" {{ old('employment_type') == 'temporary' ? 'selected' : '' }}>مؤقت</option>
                                </select>
                                @error('employment_type')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="salary" class="form-label">الراتب الأساسي <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <input type="number" class="form-control @error('salary') is-invalid @enderror" 
                                           id="salary" name="salary" value="{{ old('salary') }}" min="0" step="0.01" required>
                                    <span class="input-group-text">د.ع</span>
                                </div>
                                @error('salary')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <!-- الحالة والإعدادات -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-cog me-2"></i>
                            الحالة والإعدادات
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label for="status" class="form-label">حالة الموظف</label>
                            <select class="form-select @error('status') is-invalid @enderror" id="status" name="status">
                                <option value="active" {{ old('status', 'active') == 'active' ? 'selected' : '' }}>نشط</option>
                                <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>غير نشط</option>
                                <option value="on_leave" {{ old('status') == 'on_leave' ? 'selected' : '' }}>في إجازة</option>
                            </select>
                            @error('status')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="create_user_account" 
                                       name="create_user_account" value="1" {{ old('create_user_account') ? 'checked' : '' }}>
                                <label class="form-check-label" for="create_user_account">
                                    إنشاء حساب مستخدم للنظام
                                </label>
                            </div>
                            <small class="text-muted">سيتم إنشاء حساب للموظف للدخول إلى النظام</small>
                        </div>

                        <div class="mb-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="send_welcome_email" 
                                       name="send_welcome_email" value="1" {{ old('send_welcome_email') ? 'checked' : '' }}>
                                <label class="form-check-label" for="send_welcome_email">
                                    إرسال بريد ترحيبي
                                </label>
                            </div>
                            <small class="text-muted">سيتم إرسال بريد إلكتروني ترحيبي للموظف</small>
                        </div>
                    </div>
                </div>

                <!-- ملاحظات -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-sticky-note me-2"></i>
                            ملاحظات
                        </h5>
                    </div>
                    <div class="card-body">
                        <textarea class="form-control @error('notes') is-invalid @enderror" 
                                  id="notes" name="notes" rows="4" 
                                  placeholder="أي ملاحظات إضافية عن الموظف...">{{ old('notes') }}</textarea>
                        @error('notes')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <!-- أزرار الحفظ -->
                <div class="card">
                    <div class="card-body">
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-2"></i>
                                حفظ الموظف
                            </button>
                            <button type="button" class="btn btn-outline-success" onclick="saveAndAddAnother()">
                                <i class="fas fa-plus me-2"></i>
                                حفظ وإضافة آخر
                            </button>
                            <a href="{{ route('hr.employees') }}" class="btn btn-outline-secondary">
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
@endsection

@push('scripts')
<script>
function saveAndAddAnother() {
    // إضافة حقل مخفي للإشارة إلى الرغبة في إضافة موظف آخر
    const form = document.getElementById('employeeForm');
    const input = document.createElement('input');
    input.type = 'hidden';
    input.name = 'add_another';
    input.value = '1';
    form.appendChild(input);
    form.submit();
}

// تحديث رقم الموظف تلقائياً عند تغيير القسم
document.getElementById('department_id').addEventListener('change', function() {
    const departmentId = this.value;
    if (departmentId && !document.getElementById('employee_id').value) {
        // يمكن إضافة AJAX call هنا لإنشاء رقم موظف تلقائي
        // مثال: EMP-DEPT001-001
    }
});

// التحقق من صحة البريد الإلكتروني
document.getElementById('email').addEventListener('blur', function() {
    const email = this.value;
    if (email) {
        // يمكن إضافة AJAX call للتحقق من عدم تكرار البريد
    }
});
</script>
@endpush
