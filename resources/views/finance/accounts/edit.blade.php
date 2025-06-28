@extends('layouts.app')

@section('title', 'تعديل الحساب - ' . $account->account_name)

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="h3 mb-0">
                        <i class="fas fa-edit text-primary me-2"></i>
                        تعديل الحساب
                    </h1>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('finance.index') }}">المالية</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('finance.accounts.index') }}">الحسابات</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('finance.accounts.show', $account) }}">{{ $account->account_name }}</a></li>
                            <li class="breadcrumb-item active">تعديل</li>
                        </ol>
                    </nav>
                </div>
                <div>
                    <a href="{{ route('finance.accounts.show', $account) }}" class="btn btn-outline-secondary">
                        <i class="fas fa-arrow-right me-2"></i>
                        العودة
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- نموذج تعديل الحساب -->
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
                    <form method="POST" action="{{ route('finance.accounts.update', $account) }}">
                        @csrf
                        @method('PUT')
                        
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="account_code" class="form-label">رمز الحساب <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('account_code') is-invalid @enderror" 
                                       id="account_code" name="account_code" value="{{ old('account_code', $account->account_code) }}" 
                                       placeholder="مثال: 1001" required>
                                @error('account_code')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="account_name" class="form-label">اسم الحساب <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('account_name') is-invalid @enderror" 
                                       id="account_name" name="account_name" value="{{ old('account_name', $account->account_name) }}" 
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
                                       id="account_name_en" name="account_name_en" value="{{ old('account_name_en', $account->account_name_en) }}" 
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
                                                {{ old('parent_account_id', $account->parent_account_id) == $parentAccount->id ? 'selected' : '' }}>
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
                                    <option value="asset" {{ old('account_type', $account->account_type) === 'asset' ? 'selected' : '' }}>أصول</option>
                                    <option value="liability" {{ old('account_type', $account->account_type) === 'liability' ? 'selected' : '' }}>خصوم</option>
                                    <option value="equity" {{ old('account_type', $account->account_type) === 'equity' ? 'selected' : '' }}>حقوق الملكية</option>
                                    <option value="revenue" {{ old('account_type', $account->account_type) === 'revenue' ? 'selected' : '' }}>إيرادات</option>
                                    <option value="expense" {{ old('account_type', $account->account_type) === 'expense' ? 'selected' : '' }}>مصروفات</option>
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
                                    <option value="debit" {{ old('balance_type', $account->balance_type) === 'debit' ? 'selected' : '' }}>مدين</option>
                                    <option value="credit" {{ old('balance_type', $account->balance_type) === 'credit' ? 'selected' : '' }}>دائن</option>
                                </select>
                                @error('balance_type')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="opening_balance" class="form-label">الرصيد الافتتاحي</label>
                                <div class="input-group">
                                    <input type="number" class="form-control @error('opening_balance') is-invalid @enderror" 
                                           id="opening_balance" name="opening_balance" value="{{ old('opening_balance', $account->opening_balance) }}" 
                                           step="0.01" min="0">
                                    <span class="input-group-text">د.ع</span>
                                </div>
                                @error('opening_balance')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="is_active" name="is_active" 
                                           {{ old('is_active', $account->is_active) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="is_active">
                                        حساب نشط
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">الوصف</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" 
                                      id="description" name="description" rows="3" 
                                      placeholder="وصف مختصر للحساب">{{ old('description', $account->description) }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex justify-content-end">
                            <a href="{{ route('finance.accounts.show', $account) }}" class="btn btn-secondary me-2">إلغاء</a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-2"></i>
                                حفظ التغييرات
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- معلومات الحساب الحالية -->
        <div class="col-lg-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-info-circle me-2"></i>
                        معلومات الحساب الحالية
                    </h5>
                </div>
                <div class="card-body">
                    <table class="table table-borderless table-sm">
                        <tr>
                            <td><strong>الرصيد الحالي:</strong></td>
                            <td class="text-{{ $account->current_balance >= 0 ? 'success' : 'danger' }}">
                                {{ number_format($account->current_balance, 0) }} د.ع
                            </td>
                        </tr>
                        <tr>
                            <td><strong>مستوى الحساب:</strong></td>
                            <td>{{ $account->account_level }}</td>
                        </tr>
                        <tr>
                            <td><strong>عدد الحسابات الفرعية:</strong></td>
                            <td>{{ $account->childAccounts->count() }}</td>
                        </tr>
                        <tr>
                            <td><strong>تاريخ الإنشاء:</strong></td>
                            <td>{{ $account->created_at->format('Y-m-d') }}</td>
                        </tr>
                        @if($account->is_system_account)
                        <tr>
                            <td colspan="2">
                                <span class="badge bg-info">
                                    <i class="fas fa-shield-alt me-1"></i>
                                    حساب نظام
                                </span>
                            </td>
                        </tr>
                        @endif
                    </table>
                </div>
            </div>

            @if($account->is_system_account)
            <div class="alert alert-warning">
                <h6><i class="fas fa-exclamation-triangle me-2"></i>تحذير:</h6>
                <p class="mb-0">هذا حساب نظام. تعديل بعض الخصائص قد يؤثر على عمل النظام.</p>
            </div>
            @endif
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
    const currentCategory = '{{ old("account_category", $account->account_category) }}';
    
    // مسح الخيارات الحالية
    categorySelect.innerHTML = '<option value="">اختر فئة الحساب</option>';
    
    if (accountType && accountCategories[accountType]) {
        // إضافة فئات الحساب
        Object.entries(accountCategories[accountType]).forEach(([key, value]) => {
            const option = document.createElement('option');
            option.value = key;
            option.textContent = value;
            if (key === currentCategory) {
                option.selected = true;
            }
            categorySelect.appendChild(option);
        });
    }
});

// تشغيل الحدث عند تحميل الصفحة
document.addEventListener('DOMContentLoaded', function() {
    const accountTypeSelect = document.getElementById('account_type');
    if (accountTypeSelect.value) {
        accountTypeSelect.dispatchEvent(new Event('change'));
    }
});
</script>
@endpush
