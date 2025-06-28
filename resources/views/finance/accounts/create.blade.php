@extends('layouts.app')

@section('title', 'إضافة حساب جديد')

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="h3 mb-0">
                        <i class="fas fa-plus-circle text-primary me-2"></i>
                        إضافة حساب جديد
                    </h1>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('finance.index') }}">المالية</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('finance.accounts.index') }}">الحسابات</a></li>
                            <li class="breadcrumb-item active">إضافة حساب جديد</li>
                        </ol>
                    </nav>
                </div>
                <div>
                    <a href="{{ route('finance.accounts.index') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-arrow-right me-2"></i>
                        العودة
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- نموذج إضافة الحساب -->
    <div class="row">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-info-circle me-2"></i>
                        معلومات الحساب
                    </h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('finance.accounts.store') }}">
                        @csrf
                        
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="account_code" class="form-label">رمز الحساب <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('account_code') is-invalid @enderror" 
                                       id="account_code" name="account_code" value="{{ old('account_code') }}" 
                                       placeholder="مثال: 1001" required>
                                @error('account_code')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="account_name" class="form-label">اسم الحساب <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('account_name') is-invalid @enderror" 
                                       id="account_name" name="account_name" value="{{ old('account_name') }}" 
                                       placeholder="مثال: النقدية في الصندوق" required>
                                @error('account_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="account_name_en" class="form-label">الاسم بالإنجليزية</label>
                                <input type="text" class="form-control @error('account_name_en') is-invalid @enderror" 
                                       id="account_name_en" name="account_name_en" value="{{ old('account_name_en') }}" 
                                       placeholder="Cash in Hand">
                                @error('account_name_en')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="parent_account_id" class="form-label">الحساب الأب</label>
                                <select class="form-select @error('parent_account_id') is-invalid @enderror" 
                                        id="parent_account_id" name="parent_account_id">
                                    <option value="">حساب رئيسي</option>
                                    @foreach($parentAccounts ?? [] as $parentAccount)
                                        <option value="{{ $parentAccount->id }}" 
                                                {{ old('parent_account_id') == $parentAccount->id ? 'selected' : '' }}>
                                            {{ $parentAccount->account_code }} - {{ $parentAccount->account_name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('parent_account_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="account_type" class="form-label">نوع الحساب <span class="text-danger">*</span></label>
                                <select class="form-select @error('account_type') is-invalid @enderror" 
                                        id="account_type" name="account_type" required>
                                    <option value="">اختر نوع الحساب</option>
                                    <option value="asset" {{ old('account_type') === 'asset' ? 'selected' : '' }}>أصول</option>
                                    <option value="liability" {{ old('account_type') === 'liability' ? 'selected' : '' }}>خصوم</option>
                                    <option value="equity" {{ old('account_type') === 'equity' ? 'selected' : '' }}>حقوق الملكية</option>
                                    <option value="revenue" {{ old('account_type') === 'revenue' ? 'selected' : '' }}>إيرادات</option>
                                    <option value="expense" {{ old('account_type') === 'expense' ? 'selected' : '' }}>مصروفات</option>
                                </select>
                                @error('account_type')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="account_category" class="form-label">فئة الحساب <span class="text-danger">*</span></label>
                                <select class="form-select @error('account_category') is-invalid @enderror" 
                                        id="account_category" name="account_category" required>
                                    <option value="">اختر فئة الحساب</option>
                                    <!-- سيتم ملؤها بـ JavaScript حسب نوع الحساب -->
                                </select>
                                @error('account_category')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="balance_type" class="form-label">نوع الرصيد <span class="text-danger">*</span></label>
                                <select class="form-select @error('balance_type') is-invalid @enderror" 
                                        id="balance_type" name="balance_type" required>
                                    <option value="">اختر نوع الرصيد</option>
                                    <option value="debit" {{ old('balance_type') === 'debit' ? 'selected' : '' }}>مدين</option>
                                    <option value="credit" {{ old('balance_type') === 'credit' ? 'selected' : '' }}>دائن</option>
                                </select>
                                @error('balance_type')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="opening_balance" class="form-label">الرصيد الافتتاحي</label>
                                <div class="input-group">
                                    <input type="number" class="form-control @error('opening_balance') is-invalid @enderror" 
                                           id="opening_balance" name="opening_balance" value="{{ old('opening_balance', 0) }}" 
                                           step="0.01" min="0">
                                    <span class="input-group-text">د.ع</span>
                                </div>
                                @error('opening_balance')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">الوصف</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" 
                                      id="description" name="description" rows="3" 
                                      placeholder="وصف مختصر للحساب">{{ old('description') }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex justify-content-end">
                            <a href="{{ route('finance.accounts.index') }}" class="btn btn-secondary me-2">إلغاء</a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-2"></i>
                                حفظ الحساب
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- معلومات مساعدة -->
        <div class="col-lg-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-lightbulb me-2"></i>
                        نصائح مفيدة
                    </h5>
                </div>
                <div class="card-body">
                    <div class="alert alert-info">
                        <h6><i class="fas fa-info-circle me-2"></i>أنواع الحسابات:</h6>
                        <ul class="mb-0">
                            <li><strong>الأصول:</strong> النقدية، البنوك، المخزون، الأصول الثابتة</li>
                            <li><strong>الخصوم:</strong> الذمم الدائنة، القروض، المستحقات</li>
                            <li><strong>حقوق الملكية:</strong> رأس المال، الأرباح المحتجزة</li>
                            <li><strong>الإيرادات:</strong> المبيعات، الإيرادات الأخرى</li>
                            <li><strong>المصروفات:</strong> تكلفة البضاعة، المصروفات التشغيلية</li>
                        </ul>
                    </div>

                    <div class="alert alert-warning">
                        <h6><i class="fas fa-exclamation-triangle me-2"></i>ملاحظات مهمة:</h6>
                        <ul class="mb-0">
                            <li>رمز الحساب يجب أن يكون فريداً</li>
                            <li>الأصول والمصروفات لها رصيد مدين طبيعي</li>
                            <li>الخصوم وحقوق الملكية والإيرادات لها رصيد دائن طبيعي</li>
                            <li>يمكن إنشاء حسابات فرعية تحت الحسابات الرئيسية</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
const accountCategories = {
    asset: {
        'cash': 'نقدية',
        'bank': 'بنوك',
        'receivables': 'ذمم مدينة',
        'inventory': 'مخزون',
        'fixed_assets': 'أصول ثابتة',
        'other_assets': 'أصول أخرى'
    },
    liability: {
        'payables': 'ذمم دائنة',
        'loans': 'قروض',
        'accrued_expenses': 'مستحقات',
        'other_liabilities': 'خصوم أخرى'
    },
    equity: {
        'capital': 'رأس المال',
        'retained_earnings': 'أرباح محتجزة',
        'other_equity': 'حقوق ملكية أخرى'
    },
    revenue: {
        'sales': 'مبيعات',
        'service_revenue': 'إيرادات خدمات',
        'other_income': 'إيرادات أخرى'
    },
    expense: {
        'cost_of_goods': 'تكلفة البضاعة المباعة',
        'operating_expenses': 'مصروفات تشغيلية',
        'administrative_expenses': 'مصروفات إدارية',
        'financial_expenses': 'مصروفات مالية'
    }
};

document.getElementById('account_type').addEventListener('change', function() {
    const accountType = this.value;
    const categorySelect = document.getElementById('account_category');
    const balanceTypeSelect = document.getElementById('balance_type');
    
    // مسح الخيارات الحالية
    categorySelect.innerHTML = '<option value="">اختر فئة الحساب</option>';
    
    if (accountType && accountCategories[accountType]) {
        // إضافة فئات الحساب
        Object.entries(accountCategories[accountType]).forEach(([key, value]) => {
            const option = document.createElement('option');
            option.value = key;
            option.textContent = value;
            categorySelect.appendChild(option);
        });
        
        // تعيين نوع الرصيد الافتراضي
        if (accountType === 'asset' || accountType === 'expense') {
            balanceTypeSelect.value = 'debit';
        } else {
            balanceTypeSelect.value = 'credit';
        }
    }
});

// تشغيل الحدث عند تحميل الصفحة إذا كان هناك قيمة محددة مسبقاً
document.addEventListener('DOMContentLoaded', function() {
    const accountTypeSelect = document.getElementById('account_type');
    if (accountTypeSelect.value) {
        accountTypeSelect.dispatchEvent(new Event('change'));
        
        // استعادة القيمة المحددة مسبقاً للفئة
        const oldCategory = '{{ old("account_category") }}';
        if (oldCategory) {
            setTimeout(() => {
                document.getElementById('account_category').value = oldCategory;
            }, 100);
        }
    }
});
</script>
@endpush
