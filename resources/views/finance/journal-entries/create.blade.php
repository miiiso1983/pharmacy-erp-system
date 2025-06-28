@extends('layouts.app')

@section('title', 'إضافة قيد محاسبي - ' . __('messages.system_name'))

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">{{ __('navigation.dashboard') }}</a></li>
    <li class="breadcrumb-item"><a href="{{ route('finance.dashboard') }}">النظام المالي</a></li>
    <li class="breadcrumb-item"><a href="{{ route('finance.journal-entries.index') }}">القيود المحاسبية</a></li>
    <li class="breadcrumb-item active">إضافة قيد جديد</li>
@endsection

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0">
                <i class="fas fa-plus me-2"></i>
                إضافة قيد محاسبي جديد
            </h1>
            <p class="text-muted">إنشاء قيد محاسبي جديد في النظام</p>
        </div>
        <div>
            <a href="{{ route('finance.journal-entries.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left me-2"></i>
                العودة للقائمة
            </a>
        </div>
    </div>

    <form method="POST" action="{{ route('finance.journal-entries.store') }}" id="journalEntryForm">
        @csrf
        
        <!-- معلومات القيد الأساسية -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-info-circle me-2"></i>
                    معلومات القيد الأساسية
                </h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3 mb-3">
                        <label for="entry_number" class="form-label">رقم القيد</label>
                        <input type="text" class="form-control @error('entry_number') is-invalid @enderror" 
                               id="entry_number" name="entry_number" value="{{ old('entry_number') }}" 
                               placeholder="سيتم إنشاؤه تلقائياً" readonly>
                        @error('entry_number')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-3 mb-3">
                        <label for="entry_date" class="form-label">تاريخ القيد <span class="text-danger">*</span></label>
                        <input type="date" class="form-control @error('entry_date') is-invalid @enderror" 
                               id="entry_date" name="entry_date" value="{{ old('entry_date', now()->format('Y-m-d')) }}" required>
                        @error('entry_date')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-3 mb-3">
                        <label for="reference_type" class="form-label">نوع المرجع</label>
                        <select class="form-select @error('reference_type') is-invalid @enderror" 
                                id="reference_type" name="reference_type">
                            <option value="">اختر نوع المرجع</option>
                            <option value="manual" {{ old('reference_type') == 'manual' ? 'selected' : '' }}>قيد يدوي</option>
                            <option value="invoice" {{ old('reference_type') == 'invoice' ? 'selected' : '' }}>فاتورة</option>
                            <option value="payment" {{ old('reference_type') == 'payment' ? 'selected' : '' }}>دفعة</option>
                            <option value="adjustment" {{ old('reference_type') == 'adjustment' ? 'selected' : '' }}>تسوية</option>
                        </select>
                        @error('reference_type')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-3 mb-3">
                        <label for="reference_id" class="form-label">رقم المرجع</label>
                        <input type="text" class="form-control @error('reference_id') is-invalid @enderror" 
                               id="reference_id" name="reference_id" value="{{ old('reference_id') }}" 
                               placeholder="رقم الفاتورة أو المرجع">
                        @error('reference_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="row">
                    <div class="col-12 mb-3">
                        <label for="description" class="form-label">وصف القيد <span class="text-danger">*</span></label>
                        <textarea class="form-control @error('description') is-invalid @enderror" 
                                  id="description" name="description" rows="3" required 
                                  placeholder="اكتب وصفاً مفصلاً للقيد المحاسبي">{{ old('description') }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>
        </div>

        <!-- تفاصيل القيد -->
        <div class="card mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">
                    <i class="fas fa-list me-2"></i>
                    تفاصيل القيد
                </h5>
                <button type="button" class="btn btn-sm btn-primary" onclick="addEntryLine()">
                    <i class="fas fa-plus me-1"></i>
                    إضافة سطر
                </button>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered" id="entryDetailsTable">
                        <thead class="table-light">
                            <tr>
                                <th width="30%">الحساب <span class="text-danger">*</span></th>
                                <th width="25%">الوصف</th>
                                <th width="15%">مدين</th>
                                <th width="15%">دائن</th>
                                <th width="10%">الإجراءات</th>
                            </tr>
                        </thead>
                        <tbody id="entryDetailsBody">
                            <!-- سيتم إضافة الصفوف هنا بـ JavaScript -->
                        </tbody>
                        <tfoot>
                            <tr class="table-secondary">
                                <th colspan="2">الإجمالي</th>
                                <th id="totalDebits">0.00</th>
                                <th id="totalCredits">0.00</th>
                                <th></th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
                
                <!-- تحقق من التوازن -->
                <div class="row mt-3">
                    <div class="col-md-6">
                        <div id="balanceCheck" class="alert alert-info">
                            <i class="fas fa-info-circle me-2"></i>
                            أضف تفاصيل القيد للتحقق من التوازن
                        </div>
                    </div>
                    <div class="col-md-6 text-end">
                        <div class="d-flex justify-content-end gap-2">
                            <button type="button" class="btn btn-outline-secondary" onclick="clearAllLines()">
                                <i class="fas fa-trash me-1"></i>
                                مسح الكل
                            </button>
                            <button type="button" class="btn btn-outline-info" onclick="checkBalance()">
                                <i class="fas fa-calculator me-1"></i>
                                فحص التوازن
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- أزرار الحفظ -->
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <button type="submit" name="action" value="save_draft" class="btn btn-warning">
                            <i class="fas fa-save me-2"></i>
                            حفظ كمسودة
                        </button>
                        <button type="submit" name="action" value="save_and_post" class="btn btn-success" id="saveAndPostBtn" disabled>
                            <i class="fas fa-check me-2"></i>
                            حفظ وترحيل
                        </button>
                    </div>
                    <div>
                        <a href="{{ route('finance.journal-entries.index') }}" class="btn btn-secondary">
                            <i class="fas fa-times me-2"></i>
                            إلغاء
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<!-- Modal اختيار الحساب -->
<div class="modal fade" id="accountModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">اختيار الحساب</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <input type="text" class="form-control" id="accountSearch" placeholder="البحث عن حساب...">
                </div>
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>رمز الحساب</th>
                                <th>اسم الحساب</th>
                                <th>النوع</th>
                                <th>الرصيد</th>
                                <th>اختيار</th>
                            </tr>
                        </thead>
                        <tbody id="accountsTableBody">
                            <!-- سيتم ملؤها بـ JavaScript -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
let entryLineCounter = 0;
let currentAccountInput = null;
let accounts = [];

// تحميل الحسابات عند تحميل الصفحة
document.addEventListener('DOMContentLoaded', function() {
    loadAccounts();
    addEntryLine(); // إضافة سطر افتراضي
});

// تحميل قائمة الحسابات
function loadAccounts() {
    fetch('/finance/api/accounts')
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                accounts = data.data;
            } else {
                console.error('خطأ في تحميل الحسابات:', data.message);
                // استخدام بيانات افتراضية في حالة الخطأ
                accounts = [
                    {id: 1, code: '1110', name: 'النقدية في الصندوق', type: 'asset', balance: 0},
                    {id: 2, code: '1120', name: 'البنوك', type: 'asset', balance: 0},
                    {id: 3, code: '1130', name: 'المخزون', type: 'asset', balance: 0},
                    {id: 4, code: '2110', name: 'الموردين', type: 'liability', balance: 0},
                    {id: 5, code: '3100', name: 'رأس المال', type: 'equity', balance: 0},
                    {id: 6, code: '4100', name: 'إيرادات المبيعات', type: 'revenue', balance: 0},
                    {id: 7, code: '5100', name: 'تكلفة البضاعة المباعة', type: 'expense', balance: 0}
                ];
            }
        })
        .catch(error => {
            console.error('خطأ في الاتصال:', error);
            // استخدام بيانات افتراضية في حالة الخطأ
            accounts = [
                {id: 1, code: '1110', name: 'النقدية في الصندوق', type: 'asset', balance: 0},
                {id: 2, code: '1120', name: 'البنوك', type: 'asset', balance: 0}
            ];
        });
}

// إضافة سطر جديد
function addEntryLine() {
    entryLineCounter++;
    const tbody = document.getElementById('entryDetailsBody');
    const row = document.createElement('tr');
    row.id = `entryLine_${entryLineCounter}`;
    
    row.innerHTML = `
        <td>
            <div class="input-group">
                <input type="hidden" name="details[${entryLineCounter}][account_id]" id="accountId_${entryLineCounter}">
                <input type="text" class="form-control" name="details[${entryLineCounter}][account_display]" 
                       id="accountDisplay_${entryLineCounter}" placeholder="اختر حساب..." readonly 
                       onclick="openAccountModal(${entryLineCounter})" style="cursor: pointer;">
                <button type="button" class="btn btn-outline-secondary" onclick="openAccountModal(${entryLineCounter})">
                    <i class="fas fa-search"></i>
                </button>
            </div>
        </td>
        <td>
            <input type="text" class="form-control" name="details[${entryLineCounter}][description]" 
                   placeholder="وصف السطر...">
        </td>
        <td>
            <input type="number" class="form-control debit-input" name="details[${entryLineCounter}][debit_amount]" 
                   step="0.01" min="0" placeholder="0.00" onchange="updateTotals()" 
                   oninput="handleDebitInput(${entryLineCounter})">
        </td>
        <td>
            <input type="number" class="form-control credit-input" name="details[${entryLineCounter}][credit_amount]" 
                   step="0.01" min="0" placeholder="0.00" onchange="updateTotals()" 
                   oninput="handleCreditInput(${entryLineCounter})">
        </td>
        <td>
            <button type="button" class="btn btn-sm btn-outline-danger" onclick="removeEntryLine(${entryLineCounter})">
                <i class="fas fa-trash"></i>
            </button>
        </td>
    `;
    
    tbody.appendChild(row);
    updateTotals();
}

// حذف سطر
function removeEntryLine(lineId) {
    const row = document.getElementById(`entryLine_${lineId}`);
    if (row) {
        row.remove();
        updateTotals();
    }
}

// مسح جميع الأسطر
function clearAllLines() {
    if (confirm('هل تريد مسح جميع أسطر القيد؟')) {
        document.getElementById('entryDetailsBody').innerHTML = '';
        entryLineCounter = 0;
        addEntryLine();
    }
}

// فتح نافذة اختيار الحساب
function openAccountModal(lineId) {
    currentAccountInput = lineId;
    populateAccountsModal();
    const modal = new bootstrap.Modal(document.getElementById('accountModal'));
    modal.show();
}

// ملء نافذة الحسابات
function populateAccountsModal() {
    const tbody = document.getElementById('accountsTableBody');
    tbody.innerHTML = '';
    
    accounts.forEach(account => {
        const row = document.createElement('tr');
        row.innerHTML = `
            <td><code>${account.code}</code></td>
            <td>${account.name}</td>
            <td>
                <span class="badge bg-${getAccountTypeBadge(account.type)}">${getAccountTypeLabel(account.type)}</span>
            </td>
            <td>${account.balance.toFixed(2)}</td>
            <td>
                <button type="button" class="btn btn-sm btn-primary" onclick="selectAccount(${account.id}, '${account.code}', '${account.name}')">
                    اختيار
                </button>
            </td>
        `;
        tbody.appendChild(row);
    });
}

// اختيار حساب
function selectAccount(accountId, accountCode, accountName) {
    if (currentAccountInput) {
        document.getElementById(`accountId_${currentAccountInput}`).value = accountId;
        document.getElementById(`accountDisplay_${currentAccountInput}`).value = `${accountCode} - ${accountName}`;
        
        const modal = bootstrap.Modal.getInstance(document.getElementById('accountModal'));
        modal.hide();
        
        currentAccountInput = null;
    }
}

// التعامل مع إدخال المدين
function handleDebitInput(lineId) {
    const debitInput = document.querySelector(`#entryLine_${lineId} .debit-input`);
    const creditInput = document.querySelector(`#entryLine_${lineId} .credit-input`);
    
    if (debitInput.value && parseFloat(debitInput.value) > 0) {
        creditInput.value = '';
        creditInput.disabled = true;
    } else {
        creditInput.disabled = false;
    }
    
    updateTotals();
}

// التعامل مع إدخال الدائن
function handleCreditInput(lineId) {
    const debitInput = document.querySelector(`#entryLine_${lineId} .debit-input`);
    const creditInput = document.querySelector(`#entryLine_${lineId} .credit-input`);
    
    if (creditInput.value && parseFloat(creditInput.value) > 0) {
        debitInput.value = '';
        debitInput.disabled = true;
    } else {
        debitInput.disabled = false;
    }
    
    updateTotals();
}

// تحديث الإجماليات
function updateTotals() {
    let totalDebits = 0;
    let totalCredits = 0;
    
    document.querySelectorAll('.debit-input').forEach(input => {
        if (input.value) {
            totalDebits += parseFloat(input.value);
        }
    });
    
    document.querySelectorAll('.credit-input').forEach(input => {
        if (input.value) {
            totalCredits += parseFloat(input.value);
        }
    });
    
    document.getElementById('totalDebits').textContent = totalDebits.toFixed(2);
    document.getElementById('totalCredits').textContent = totalCredits.toFixed(2);
    
    checkBalance();
}

// فحص التوازن
function checkBalance() {
    const totalDebits = parseFloat(document.getElementById('totalDebits').textContent);
    const totalCredits = parseFloat(document.getElementById('totalCredits').textContent);
    const balanceCheck = document.getElementById('balanceCheck');
    const saveAndPostBtn = document.getElementById('saveAndPostBtn');
    
    const difference = Math.abs(totalDebits - totalCredits);
    
    if (difference < 0.01 && totalDebits > 0) {
        balanceCheck.className = 'alert alert-success';
        balanceCheck.innerHTML = '<i class="fas fa-check-circle me-2"></i>القيد متوازن - يمكن الترحيل';
        saveAndPostBtn.disabled = false;
    } else if (totalDebits === 0 && totalCredits === 0) {
        balanceCheck.className = 'alert alert-info';
        balanceCheck.innerHTML = '<i class="fas fa-info-circle me-2"></i>أضف تفاصيل القيد للتحقق من التوازن';
        saveAndPostBtn.disabled = true;
    } else {
        balanceCheck.className = 'alert alert-danger';
        balanceCheck.innerHTML = `<i class="fas fa-exclamation-triangle me-2"></i>القيد غير متوازن - الفرق: ${difference.toFixed(2)}`;
        saveAndPostBtn.disabled = true;
    }
}

// دوال مساعدة
function getAccountTypeBadge(type) {
    const badges = {
        'asset': 'info',
        'liability': 'warning',
        'equity': 'secondary',
        'revenue': 'success',
        'expense': 'danger'
    };
    return badges[type] || 'secondary';
}

function getAccountTypeLabel(type) {
    const labels = {
        'asset': 'أصول',
        'liability': 'خصوم',
        'equity': 'حقوق ملكية',
        'revenue': 'إيرادات',
        'expense': 'مصروفات'
    };
    return labels[type] || type;
}

// البحث في الحسابات
document.getElementById('accountSearch').addEventListener('input', function() {
    const searchTerm = this.value.toLowerCase();
    const rows = document.querySelectorAll('#accountsTableBody tr');
    
    rows.forEach(row => {
        const text = row.textContent.toLowerCase();
        row.style.display = text.includes(searchTerm) ? '' : 'none';
    });
});
</script>
@endpush

@push('styles')
<style>
.table th {
    background-color: #f8f9fa;
    border-top: none;
}

.input-group .form-control[readonly] {
    background-color: #fff;
}

.alert {
    margin-bottom: 0;
}

#entryDetailsTable tbody tr:hover {
    background-color: #f8f9fa;
}

.modal-lg {
    max-width: 800px;
}

code {
    background-color: #f8f9fa;
    padding: 2px 4px;
    border-radius: 3px;
    font-size: 0.875em;
}
</style>
@endpush
