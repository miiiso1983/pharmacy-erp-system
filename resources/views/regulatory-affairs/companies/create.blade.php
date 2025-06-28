@extends('layouts.app')

@section('title', __('messages.add_new_company') . ' - ' . __('messages.system_name'))

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">{{ __('navigation.dashboard') }}</a></li>
    <li class="breadcrumb-item"><a href="{{ route('regulatory-affairs.dashboard') }}">{{ __('messages.regulatory_affairs') }}</a></li>
    <li class="breadcrumb-item"><a href="{{ route('regulatory-affairs.companies') }}">{{ __('messages.companies_management') }}</a></li>
    <li class="breadcrumb-item active">{{ __('messages.add_new_company') }}</li>
@endsection

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0">
                <i class="fas fa-plus me-2"></i>
                {{ __('messages.add_new_company') }}
            </h1>
            <p class="text-muted">{{ __('messages.company_registration') }}</p>
        </div>
        <div>
            <a href="{{ route('regulatory-affairs.companies') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left me-2"></i>
                {{ __('messages.back') }}
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8 mx-auto">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-building me-2"></i>
                        معلومات الشركة
                    </h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('regulatory-affairs.companies.store') }}">
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
                                <label for="company_name" class="form-label">اسم الشركة <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('company_name') is-invalid @enderror" 
                                       id="company_name" name="company_name" value="{{ old('company_name') }}" required>
                                @error('company_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="company_name_en" class="form-label">اسم الشركة بالإنجليزية</label>
                                <input type="text" class="form-control @error('company_name_en') is-invalid @enderror" 
                                       id="company_name_en" name="company_name_en" value="{{ old('company_name_en') }}">
                                @error('company_name_en')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="company_code" class="form-label">رمز الشركة <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('company_code') is-invalid @enderror" 
                                       id="company_code" name="company_code" value="{{ old('company_code') }}" required>
                                @error('company_code')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="company_type" class="form-label">نوع الشركة <span class="text-danger">*</span></label>
                                <select class="form-select @error('company_type') is-invalid @enderror" 
                                        id="company_type" name="company_type" required>
                                    <option value="">اختر نوع الشركة</option>
                                    <option value="manufacturer" {{ old('company_type') == 'manufacturer' ? 'selected' : '' }}>شركة تصنيع</option>
                                    <option value="distributor" {{ old('company_type') == 'distributor' ? 'selected' : '' }}>شركة توزيع</option>
                                    <option value="importer" {{ old('company_type') == 'importer' ? 'selected' : '' }}>شركة استيراد</option>
                                    <option value="exporter" {{ old('company_type') == 'exporter' ? 'selected' : '' }}>شركة تصدير</option>
                                    <option value="wholesaler" {{ old('company_type') == 'wholesaler' ? 'selected' : '' }}>تاجر جملة</option>
                                    <option value="retailer" {{ old('company_type') == 'retailer' ? 'selected' : '' }}>تاجر تجزئة</option>
                                </select>
                                @error('company_type')
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
                                    <option value="active" {{ old('status', 'active') == 'active' ? 'selected' : '' }}>نشطة</option>
                                    <option value="suspended" {{ old('status') == 'suspended' ? 'selected' : '' }}>معلقة</option>
                                    <option value="expired" {{ old('status') == 'expired' ? 'selected' : '' }}>منتهية الصلاحية</option>
                                    <option value="cancelled" {{ old('status') == 'cancelled' ? 'selected' : '' }}>ملغية</option>
                                </select>
                                @error('status')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- معلومات الموقع -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h6 class="fw-bold text-info mb-3">
                                    <i class="fas fa-map-marker-alt me-2"></i>
                                    معلومات الموقع
                                </h6>
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="country" class="form-label">البلد <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('country') is-invalid @enderror" 
                                       id="country" name="country" value="{{ old('country') }}" required>
                                @error('country')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="city" class="form-label">المدينة <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('city') is-invalid @enderror" 
                                       id="city" name="city" value="{{ old('city') }}" required>
                                @error('city')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-12 mb-3">
                                <label for="address" class="form-label">العنوان التفصيلي <span class="text-danger">*</span></label>
                                <textarea class="form-control @error('address') is-invalid @enderror" 
                                          id="address" name="address" rows="3" required>{{ old('address') }}</textarea>
                                @error('address')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- معلومات الاتصال -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h6 class="fw-bold text-warning mb-3">
                                    <i class="fas fa-phone me-2"></i>
                                    معلومات الاتصال
                                </h6>
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="phone" class="form-label">الهاتف</label>
                                <input type="text" class="form-control @error('phone') is-invalid @enderror" 
                                       id="phone" name="phone" value="{{ old('phone') }}">
                                @error('phone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="email" class="form-label">البريد الإلكتروني</label>
                                <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                       id="email" name="email" value="{{ old('email') }}">
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="website" class="form-label">الموقع الإلكتروني</label>
                                <input type="url" class="form-control @error('website') is-invalid @enderror" 
                                       id="website" name="website" value="{{ old('website') }}">
                                @error('website')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="contact_person" class="form-label">الشخص المسؤول</label>
                                <input type="text" class="form-control @error('contact_person') is-invalid @enderror" 
                                       id="contact_person" name="contact_person" value="{{ old('contact_person') }}">
                                @error('contact_person')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- ملاحظات -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <label for="notes" class="form-label">ملاحظات</label>
                                <textarea class="form-control @error('notes') is-invalid @enderror" 
                                          id="notes" name="notes" rows="3">{{ old('notes') }}</textarea>
                                @error('notes')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- أزرار الحفظ -->
                        <div class="row">
                            <div class="col-12">
                                <div class="d-flex justify-content-end gap-2">
                                    <a href="{{ route('regulatory-affairs.companies') }}" class="btn btn-secondary">
                                        <i class="fas fa-times me-2"></i>
                                        إلغاء
                                    </a>
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-save me-2"></i>
                                        حفظ الشركة
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
