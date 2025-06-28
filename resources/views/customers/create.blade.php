@extends('layouts.app')

@section('title', 'إضافة زبون جديد - ' . __('messages.system_name'))

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">{{ __('navigation.dashboard') }}</a></li>
    <li class="breadcrumb-item"><a href="{{ route('customers.index') }}">الزبائن</a></li>
    <li class="breadcrumb-item active">إضافة زبون جديد</li>
@endsection

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0">
                <i class="fas fa-user-plus me-2"></i>
                إضافة زبون جديد
            </h1>
            <p class="text-muted">إضافة زبون جديد إلى قاعدة البيانات</p>
        </div>
        <div>
            <a href="{{ route('customers.index') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left me-2"></i>
                العودة للقائمة
            </a>
        </div>
    </div>

    <!-- نموذج إضافة الزبون -->
    <div class="row">
        <div class="col-lg-8 mx-auto">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-info-circle me-2"></i>
                        معلومات الزبون
                    </h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('customers.store') }}" method="POST">
                        @csrf
                        
                        <div class="row">
                            <!-- المعلومات الأساسية -->
                            <div class="col-md-6">
                                <h6 class="text-primary mb-3">المعلومات الأساسية</h6>
                                
                                <div class="mb-3">
                                    <label for="customer_code" class="form-label">كود الزبون <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('customer_code') is-invalid @enderror" 
                                           id="customer_code" name="customer_code" value="{{ old('customer_code') }}" 
                                           placeholder="سيتم إنشاؤه تلقائياً إذا ترك فارغاً">
                                    @error('customer_code')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="name" class="form-label">اسم الزبون <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                           id="name" name="name" value="{{ old('name') }}" required>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="business_name" class="form-label">اسم الشركة/المؤسسة</label>
                                    <input type="text" class="form-control @error('business_name') is-invalid @enderror" 
                                           id="business_name" name="business_name" value="{{ old('business_name') }}">
                                    @error('business_name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="customer_type" class="form-label">نوع الزبون <span class="text-danger">*</span></label>
                                    <select class="form-select @error('customer_type') is-invalid @enderror"
                                            id="customer_type" name="customer_type" required>
                                        <option value="">اختر نوع الزبون</option>
                                        <option value="individual" {{ old('customer_type') == 'individual' ? 'selected' : '' }}>فرد</option>
                                        <option value="company" {{ old('customer_type') == 'company' ? 'selected' : '' }}>شركة</option>
                                        <option value="pharmacy" {{ old('customer_type') == 'pharmacy' ? 'selected' : '' }}>صيدلية</option>
                                        <option value="hospital" {{ old('customer_type') == 'hospital' ? 'selected' : '' }}>مستشفى</option>
                                        <option value="clinic" {{ old('customer_type') == 'clinic' ? 'selected' : '' }}>عيادة</option>
                                    </select>
                                    @error('customer_type')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="status" class="form-label">الحالة <span class="text-danger">*</span></label>
                                    <select class="form-select @error('status') is-invalid @enderror" 
                                            id="status" name="status" required>
                                        <option value="active" {{ old('status', 'active') == 'active' ? 'selected' : '' }}>نشط</option>
                                        <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>غير نشط</option>
                                        <option value="blocked" {{ old('status') == 'blocked' ? 'selected' : '' }}>محظور</option>
                                    </select>
                                    @error('status')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- معلومات الاتصال -->
                            <div class="col-md-6">
                                <h6 class="text-primary mb-3">معلومات الاتصال</h6>
                                
                                <div class="mb-3">
                                    <label for="phone" class="form-label">رقم الهاتف</label>
                                    <input type="text" class="form-control @error('phone') is-invalid @enderror" 
                                           id="phone" name="phone" value="{{ old('phone') }}">
                                    @error('phone')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="mobile" class="form-label">رقم الموبايل</label>
                                    <input type="text" class="form-control @error('mobile') is-invalid @enderror" 
                                           id="mobile" name="mobile" value="{{ old('mobile') }}">
                                    @error('mobile')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="email" class="form-label">البريد الإلكتروني</label>
                                    <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                           id="email" name="email" value="{{ old('email') }}">
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="address" class="form-label">العنوان</label>
                                    <textarea class="form-control @error('address') is-invalid @enderror" 
                                              id="address" name="address" rows="2">{{ old('address') }}</textarea>
                                    @error('address')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="city" class="form-label">المدينة</label>
                                            <input type="text" class="form-control @error('city') is-invalid @enderror" 
                                                   id="city" name="city" value="{{ old('city') }}">
                                            @error('city')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="area" class="form-label">المنطقة</label>
                                            <input type="text" class="form-control @error('area') is-invalid @enderror" 
                                                   id="area" name="area" value="{{ old('area') }}">
                                            @error('area')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- المعلومات المالية -->
                        <hr>
                        <div class="row">
                            <div class="col-12">
                                <h6 class="text-primary mb-3">المعلومات المالية</h6>
                            </div>
                            
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="credit_limit" class="form-label">سقف الدين (د.ع) <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control @error('credit_limit') is-invalid @enderror" 
                                           id="credit_limit" name="credit_limit" value="{{ old('credit_limit', 0) }}" 
                                           min="0" step="1000" required>
                                    @error('credit_limit')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="payment_terms_days" class="form-label">مدة السداد (يوم) <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control @error('payment_terms_days') is-invalid @enderror" 
                                           id="payment_terms_days" name="payment_terms_days" value="{{ old('payment_terms_days', 30) }}" 
                                           min="1" max="365" required>
                                    @error('payment_terms_days')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="current_balance" class="form-label">الرصيد الحالي (د.ع)</label>
                                    <input type="number" class="form-control @error('current_balance') is-invalid @enderror" 
                                           id="current_balance" name="current_balance" value="{{ old('current_balance', 0) }}" 
                                           step="0.01" readonly>
                                    <small class="text-muted">سيتم حسابه تلقائياً من المعاملات</small>
                                    @error('current_balance')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- ملاحظات -->
                        <hr>
                        <div class="row">
                            <div class="col-12">
                                <div class="mb-3">
                                    <label for="notes" class="form-label">ملاحظات</label>
                                    <textarea class="form-control @error('notes') is-invalid @enderror" 
                                              id="notes" name="notes" rows="3" 
                                              placeholder="أي ملاحظات إضافية عن الزبون...">{{ old('notes') }}</textarea>
                                    @error('notes')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- أزرار الحفظ -->
                        <div class="row">
                            <div class="col-12">
                                <div class="d-flex justify-content-end gap-2">
                                    <a href="{{ route('customers.index') }}" class="btn btn-outline-secondary">
                                        <i class="fas fa-times me-2"></i>
                                        إلغاء
                                    </a>
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-save me-2"></i>
                                        حفظ الزبون
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
// إنشاء كود الزبون تلقائياً
document.getElementById('name').addEventListener('input', function() {
    const customerCodeField = document.getElementById('customer_code');
    if (!customerCodeField.value) {
        const name = this.value.trim();
        if (name) {
            // إنشاء كود بناءً على الاسم والوقت الحالي
            const timestamp = Date.now().toString().slice(-4);
            const nameCode = name.substring(0, 3).toUpperCase().replace(/[^A-Z]/g, 'CUS');
            customerCodeField.value = nameCode + timestamp;
        }
    }
});

// تحديث سقف الدين بناءً على نوع الزبون
document.getElementById('customer_type').addEventListener('change', function() {
    const creditLimitField = document.getElementById('credit_limit');
    const paymentTermsField = document.getElementById('payment_terms_days');
    
    switch(this.value) {
        case 'retail':
            creditLimitField.value = 500000; // 500 ألف
            paymentTermsField.value = 15;
            break;
        case 'wholesale':
            creditLimitField.value = 5000000; // 5 مليون
            paymentTermsField.value = 45;
            break;
        case 'pharmacy':
            creditLimitField.value = 3000000; // 3 مليون
            paymentTermsField.value = 30;
            break;
        default:
            creditLimitField.value = 0;
            paymentTermsField.value = 30;
    }
});
</script>
@endpush
