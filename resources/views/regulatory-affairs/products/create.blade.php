@extends('layouts.app')

@section('title', 'إضافة منتج دوائي جديد - ' . __('messages.system_name'))

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">{{ __('navigation.dashboard') }}</a></li>
    <li class="breadcrumb-item"><a href="{{ route('regulatory-affairs.dashboard') }}">الشؤون التنظيمية</a></li>
    <li class="breadcrumb-item"><a href="{{ route('regulatory-affairs.products') }}">المنتجات الدوائية</a></li>
    <li class="breadcrumb-item active">إضافة منتج جديد</li>
@endsection

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0">
                <i class="fas fa-plus me-2"></i>
                إضافة منتج دوائي جديد
            </h1>
            <p class="text-muted">تسجيل منتج دوائي أو جهاز طبي جديد في النظام</p>
        </div>
        <div>
            <a href="{{ route('regulatory-affairs.products') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left me-2"></i>
                العودة للقائمة
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-10 mx-auto">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-pills me-2"></i>
                        معلومات المنتج
                    </h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('regulatory-affairs.products.store') }}">
                        @csrf
                        
                        <!-- المعلومات الأساسية -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h6 class="fw-bold text-primary mb-3">
                                    <i class="fas fa-info-circle me-2"></i>
                                    المعلومات الأساسية
                                </h6>
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="product_name" class="form-label">اسم المنتج <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('product_name') is-invalid @enderror" 
                                       id="product_name" name="product_name" value="{{ old('product_name') }}" required>
                                @error('product_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="product_name_en" class="form-label">اسم المنتج بالإنجليزية</label>
                                <input type="text" class="form-control @error('product_name_en') is-invalid @enderror" 
                                       id="product_name_en" name="product_name_en" value="{{ old('product_name_en') }}">
                                @error('product_name_en')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="product_code" class="form-label">رمز المنتج <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('product_code') is-invalid @enderror" 
                                       id="product_code" name="product_code" value="{{ old('product_code') }}" required>
                                @error('product_code')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="generic_name" class="form-label">الاسم العلمي <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('generic_name') is-invalid @enderror" 
                                       id="generic_name" name="generic_name" value="{{ old('generic_name') }}" required>
                                @error('generic_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="brand_name" class="form-label">الاسم التجاري</label>
                                <input type="text" class="form-control @error('brand_name') is-invalid @enderror" 
                                       id="brand_name" name="brand_name" value="{{ old('brand_name') }}">
                                @error('brand_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="company_id" class="form-label">الشركة المصنعة <span class="text-danger">*</span></label>
                                <select class="form-select @error('company_id') is-invalid @enderror" 
                                        id="company_id" name="company_id" required>
                                    <option value="">اختر الشركة المصنعة</option>
                                    @foreach($companies as $company)
                                        <option value="{{ $company->id }}" {{ old('company_id') == $company->id ? 'selected' : '' }}>
                                            {{ $company->company_name }} ({{ $company->country }})
                                        </option>
                                    @endforeach
                                </select>
                                @error('company_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- معلومات التسجيل -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h6 class="fw-bold text-success mb-3">
                                    <i class="fas fa-certificate me-2"></i>
                                    معلومات التسجيل
                                </h6>
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="registration_number" class="form-label">رقم التسجيل <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('registration_number') is-invalid @enderror" 
                                       id="registration_number" name="registration_number" value="{{ old('registration_number') }}" required>
                                @error('registration_number')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="registration_date" class="form-label">تاريخ التسجيل <span class="text-danger">*</span></label>
                                <input type="date" class="form-control @error('registration_date') is-invalid @enderror" 
                                       id="registration_date" name="registration_date" value="{{ old('registration_date') }}" required>
                                @error('registration_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="expiry_date" class="form-label">تاريخ انتهاء التسجيل</label>
                                <input type="date" class="form-control @error('expiry_date') is-invalid @enderror" 
                                       id="expiry_date" name="expiry_date" value="{{ old('expiry_date') }}">
                                @error('expiry_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="status" class="form-label">حالة التسجيل</label>
                                <select class="form-select @error('status') is-invalid @enderror" 
                                        id="status" name="status">
                                    <option value="pending" {{ old('status', 'pending') == 'pending' ? 'selected' : '' }}>معلق</option>
                                    <option value="registered" {{ old('status') == 'registered' ? 'selected' : '' }}>مسجل</option>
                                    <option value="rejected" {{ old('status') == 'rejected' ? 'selected' : '' }}>مرفوض</option>
                                    <option value="expired" {{ old('status') == 'expired' ? 'selected' : '' }}>منتهي الصلاحية</option>
                                    <option value="suspended" {{ old('status') == 'suspended' ? 'selected' : '' }}>معلق</option>
                                </select>
                                @error('status')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- تصنيف المنتج -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h6 class="fw-bold text-info mb-3">
                                    <i class="fas fa-tags me-2"></i>
                                    تصنيف المنتج
                                </h6>
                            </div>
                            
                            <div class="col-md-4 mb-3">
                                <label for="product_type" class="form-label">نوع المنتج <span class="text-danger">*</span></label>
                                <select class="form-select @error('product_type') is-invalid @enderror" 
                                        id="product_type" name="product_type" required>
                                    <option value="">اختر نوع المنتج</option>
                                    <option value="medicine" {{ old('product_type') == 'medicine' ? 'selected' : '' }}>دواء</option>
                                    <option value="medical_device" {{ old('product_type') == 'medical_device' ? 'selected' : '' }}>جهاز طبي</option>
                                    <option value="supplement" {{ old('product_type') == 'supplement' ? 'selected' : '' }}>مكمل غذائي</option>
                                    <option value="cosmetic" {{ old('product_type') == 'cosmetic' ? 'selected' : '' }}>مستحضر تجميل</option>
                                    <option value="veterinary" {{ old('product_type') == 'veterinary' ? 'selected' : '' }}>بيطري</option>
                                </select>
                                @error('product_type')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-4 mb-3">
                                <label for="dosage_form" class="form-label">الشكل الصيدلاني <span class="text-danger">*</span></label>
                                <select class="form-select @error('dosage_form') is-invalid @enderror" 
                                        id="dosage_form" name="dosage_form" required>
                                    <option value="">اختر الشكل الصيدلاني</option>
                                    <option value="tablet" {{ old('dosage_form') == 'tablet' ? 'selected' : '' }}>أقراص</option>
                                    <option value="capsule" {{ old('dosage_form') == 'capsule' ? 'selected' : '' }}>كبسولات</option>
                                    <option value="syrup" {{ old('dosage_form') == 'syrup' ? 'selected' : '' }}>شراب</option>
                                    <option value="injection" {{ old('dosage_form') == 'injection' ? 'selected' : '' }}>حقن</option>
                                    <option value="cream" {{ old('dosage_form') == 'cream' ? 'selected' : '' }}>كريم</option>
                                    <option value="ointment" {{ old('dosage_form') == 'ointment' ? 'selected' : '' }}>مرهم</option>
                                    <option value="drops" {{ old('dosage_form') == 'drops' ? 'selected' : '' }}>قطرة</option>
                                    <option value="inhaler" {{ old('dosage_form') == 'inhaler' ? 'selected' : '' }}>بخاخ</option>
                                    <option value="other" {{ old('dosage_form') == 'other' ? 'selected' : '' }}>أخرى</option>
                                </select>
                                @error('dosage_form')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-4 mb-3">
                                <label for="prescription_status" class="form-label">حالة الوصفة <span class="text-danger">*</span></label>
                                <select class="form-select @error('prescription_status') is-invalid @enderror" 
                                        id="prescription_status" name="prescription_status" required>
                                    <option value="">اختر حالة الوصفة</option>
                                    <option value="prescription" {{ old('prescription_status') == 'prescription' ? 'selected' : '' }}>بوصفة طبية</option>
                                    <option value="otc" {{ old('prescription_status') == 'otc' ? 'selected' : '' }}>بدون وصفة طبية</option>
                                    <option value="controlled" {{ old('prescription_status') == 'controlled' ? 'selected' : '' }}>مراقب</option>
                                </select>
                                @error('prescription_status')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="strength" class="form-label">التركيز</label>
                                <input type="text" class="form-control @error('strength') is-invalid @enderror" 
                                       id="strength" name="strength" value="{{ old('strength') }}" 
                                       placeholder="مثال: 500 مجم">
                                @error('strength')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="pack_size" class="form-label">حجم العبوة</label>
                                <input type="text" class="form-control @error('pack_size') is-invalid @enderror" 
                                       id="pack_size" name="pack_size" value="{{ old('pack_size') }}" 
                                       placeholder="مثال: 20 قرص">
                                @error('pack_size')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- معلومات إضافية -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h6 class="fw-bold text-warning mb-3">
                                    <i class="fas fa-clipboard-list me-2"></i>
                                    معلومات إضافية
                                </h6>
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="atc_code" class="form-label">رمز ATC</label>
                                <input type="text" class="form-control @error('atc_code') is-invalid @enderror" 
                                       id="atc_code" name="atc_code" value="{{ old('atc_code') }}" 
                                       placeholder="مثال: N02BE01">
                                @error('atc_code')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="price" class="form-label">السعر (د.ع)</label>
                                <input type="number" step="0.01" class="form-control @error('price') is-invalid @enderror" 
                                       id="price" name="price" value="{{ old('price') }}">
                                @error('price')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-12 mb-3">
                                <label for="composition" class="form-label">التركيب</label>
                                <textarea class="form-control @error('composition') is-invalid @enderror" 
                                          id="composition" name="composition" rows="2">{{ old('composition') }}</textarea>
                                @error('composition')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-12 mb-3">
                                <label for="indications" class="form-label">دواعي الاستعمال</label>
                                <textarea class="form-control @error('indications') is-invalid @enderror" 
                                          id="indications" name="indications" rows="3">{{ old('indications') }}</textarea>
                                @error('indications')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-12 mb-3">
                                <label for="notes" class="form-label">ملاحظات</label>
                                <textarea class="form-control @error('notes') is-invalid @enderror" 
                                          id="notes" name="notes" rows="2">{{ old('notes') }}</textarea>
                                @error('notes')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- أزرار الحفظ -->
                        <div class="row">
                            <div class="col-12">
                                <div class="d-flex justify-content-end gap-2">
                                    <a href="{{ route('regulatory-affairs.products') }}" class="btn btn-secondary">
                                        <i class="fas fa-times me-2"></i>
                                        إلغاء
                                    </a>
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-save me-2"></i>
                                        حفظ المنتج
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

@push('styles')
<style>
.card {
    box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
}

.form-label {
    font-weight: 600;
    color: #495057;
}

.text-danger {
    color: #dc3545 !important;
}

.fw-bold {
    font-weight: 600 !important;
}

.gap-2 {
    gap: 0.5rem !important;
}

.is-invalid {
    border-color: #dc3545;
}

.invalid-feedback {
    display: block;
}
</style>
@endpush
