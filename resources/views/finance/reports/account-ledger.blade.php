@extends('layouts.app')

@section('title', 'دفتر الأستاذ')

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="h3 mb-0">
                        <i class="fas fa-book text-primary me-2"></i>
                        دفتر الأستاذ
                        @if($account)
                            - {{ $account->account_name }}
                        @endif
                    </h1>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('finance.index') }}">المالية</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('finance.reports.index') }}">التقارير</a></li>
                            <li class="breadcrumb-item active">دفتر الأستاذ</li>
                        </ol>
                    </nav>
                </div>
                <div>
                    @if($account)
                    <div class="btn-group" role="group">
                        <button type="button" class="btn btn-outline-danger" onclick="exportToPDF()">
                            <i class="fas fa-file-pdf me-2"></i>
                            PDF
                        </button>
                        <button type="button" class="btn btn-outline-success" onclick="exportToExcel()">
                            <i class="fas fa-file-excel me-2"></i>
                            Excel
                        </button>
                        <button type="button" class="btn btn-outline-info" onclick="printReport()">
                            <i class="fas fa-print me-2"></i>
                            طباعة
                        </button>
                    </div>
                    @endif
                    <a href="{{ route('finance.reports.index') }}" class="btn btn-outline-secondary ms-2">
                        <i class="fas fa-arrow-right me-2"></i>
                        العودة
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- فلاتر التقرير -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <form method="GET" action="{{ route('finance.reports.account-ledger') }}">
                        <div class="row align-items-end">
                            <div class="col-md-3">
                                <label for="account_id" class="form-label">الحساب <span class="text-danger">*</span></label>
                                <select class="form-select" id="account_id" name="account_id" required>
                                    <option value="">اختر الحساب</option>
                                    @foreach($accounts ?? [] as $acc)
                                        <option value="{{ $acc->id }}" 
                                                {{ request('account_id') == $acc->id ? 'selected' : '' }}>
                                            {{ $acc->account_code }} - {{ $acc->account_name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label for="start_date" class="form-label">من تاريخ</label>
                                <input type="date" class="form-control" id="start_date" name="start_date" 
                                       value="{{ request('start_date', $startDate->format('Y-m-d')) }}">
                            </div>
                            <div class="col-md-2">
                                <label for="end_date" class="form-label">إلى تاريخ</label>
                                <input type="date" class="form-control" id="end_date" name="end_date" 
                                       value="{{ request('end_date', $endDate->format('Y-m-d')) }}">
                            </div>
                            <div class="col-md-3">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-search me-2"></i>
                                    عرض دفتر الأستاذ
                                </button>
                            </div>
                            <div class="col-md-2 text-end">
                                @if($account)
                                <small class="text-muted">
                                    الفترة: {{ $startDate->format('Y-m-d') }} - {{ $endDate->format('Y-m-d') }}
                                </small>
                                @endif
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @if($account)
    <!-- معلومات الحساب -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-info-circle me-2"></i>
                        معلومات الحساب
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-8">
                            <table class="table table-borderless table-sm">
                                <tr>
                                    <td width="150"><strong>رمز الحساب:</strong></td>
                                    <td>{{ $account->account_code }}</td>
                                    <td width="150"><strong>نوع الحساب:</strong></td>
                                    <td>
                                        <span class="badge bg-primary">
                                            {{ trans('finance.account_types.' . $account->account_type) }}
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>اسم الحساب:</strong></td>
                                    <td>{{ $account->account_name }}</td>
                                    <td><strong>نوع الرصيد:</strong></td>
                                    <td>
                                        <span class="badge bg-{{ $account->balance_type === 'debit' ? 'success' : 'danger' }}">
                                            {{ $account->balance_type === 'debit' ? 'مدين' : 'دائن' }}
                                        </span>
                                    </td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-4 text-end">
                            <div class="card bg-light">
                                <div class="card-body text-center">
                                    <h6>الرصيد الحالي</h6>
                                    <h3 class="text-{{ $account->current_balance >= 0 ? 'success' : 'danger' }}">
                                        {{ number_format($account->current_balance, 0) }} د.ع
                                    </h3>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- دفتر الأستاذ -->
    <div class="row" id="ledger-report">
        <div class="col-12">
            <div class="card">
                <div class="card-header text-center">
                    <h4 class="mb-0">دفتر الأستاذ - {{ $account->account_name }}</h4>
                    <p class="text-muted mb-0">
                        للفترة من {{ $startDate->format('Y-m-d') }} إلى {{ $endDate->format('Y-m-d') }}
                    </p>
                </div>
                <div class="card-body">
                    @if($transactions && $transactions->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>التاريخ</th>
                                    <th>رقم القيد</th>
                                    <th>الوصف</th>
                                    <th class="text-center">مدين</th>
                                    <th class="text-center">دائن</th>
                                    <th class="text-center">الرصيد الجاري</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- الرصيد الافتتاحي -->
                                @php
                                    $openingBalance = $account->opening_balance ?? 0;
                                @endphp
                                <tr class="table-info">
                                    <td>{{ $startDate->copy()->subDay()->format('Y-m-d') }}</td>
                                    <td>-</td>
                                    <td><strong>الرصيد الافتتاحي</strong></td>
                                    <td class="text-center">-</td>
                                    <td class="text-center">-</td>
                                    <td class="text-center">
                                        <strong>{{ number_format($openingBalance, 0) }}</strong>
                                    </td>
                                </tr>
                                
                                <!-- المعاملات -->
                                @foreach($transactions as $transaction)
                                <tr>
                                    <td>{{ $transaction->journalEntry->entry_date->format('Y-m-d') }}</td>
                                    <td>
                                        <a href="{{ route('finance.journal-entries.show', $transaction->journalEntry) }}">
                                            #{{ $transaction->journalEntry->entry_number }}
                                        </a>
                                    </td>
                                    <td>{{ $transaction->description ?: $transaction->journalEntry->description }}</td>
                                    <td class="text-center">
                                        @if($transaction->debit_amount > 0)
                                            <span class="text-success">
                                                {{ number_format($transaction->debit_amount, 0) }}
                                            </span>
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        @if($transaction->credit_amount > 0)
                                            <span class="text-danger">
                                                {{ number_format($transaction->credit_amount, 0) }}
                                            </span>
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        <strong class="text-{{ $transaction->running_balance >= 0 ? 'success' : 'danger' }}">
                                            {{ number_format($transaction->running_balance, 0) }}
                                        </strong>
                                    </td>
                                </tr>
                                @endforeach
                                
                                <!-- الرصيد النهائي -->
                                <tr class="table-success">
                                    <td>{{ $endDate->format('Y-m-d') }}</td>
                                    <td>-</td>
                                    <td><strong>الرصيد النهائي</strong></td>
                                    <td class="text-center">-</td>
                                    <td class="text-center">-</td>
                                    <td class="text-center">
                                        <strong>{{ number_format($runningBalance ?? 0, 0) }}</strong>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <!-- ملخص الحركة -->
                    <div class="row mt-4">
                        <div class="col-12">
                            <div class="card bg-light">
                                <div class="card-body">
                                    <h6 class="card-title">ملخص حركة الحساب:</h6>
                                    <div class="row text-center">
                                        <div class="col-md-3">
                                            <h6>الرصيد الافتتاحي</h6>
                                            <h5 class="text-info">{{ number_format($openingBalance, 0) }} د.ع</h5>
                                        </div>
                                        <div class="col-md-3">
                                            <h6>إجمالي المدين</h6>
                                            <h5 class="text-success">
                                                {{ number_format($transactions->sum('debit_amount'), 0) }} د.ع
                                            </h5>
                                        </div>
                                        <div class="col-md-3">
                                            <h6>إجمالي الدائن</h6>
                                            <h5 class="text-danger">
                                                {{ number_format($transactions->sum('credit_amount'), 0) }} د.ع
                                            </h5>
                                        </div>
                                        <div class="col-md-3">
                                            <h6>الرصيد النهائي</h6>
                                            <h5 class="text-{{ ($runningBalance ?? 0) >= 0 ? 'success' : 'danger' }}">
                                                {{ number_format($runningBalance ?? 0, 0) }} د.ع
                                            </h5>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    @else
                    <div class="text-center py-5">
                        <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                        <h5 class="text-muted">لا توجد معاملات</h5>
                        <p class="text-muted">لا توجد معاملات على هذا الحساب في الفترة المحددة</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    @else
    <!-- لم يتم اختيار حساب -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body text-center py-5">
                    <i class="fas fa-search fa-4x text-muted mb-3"></i>
                    <h4 class="text-muted">اختر حساباً لعرض دفتر الأستاذ</h4>
                    <p class="text-muted">يرجى اختيار حساب من القائمة أعلاه لعرض تفاصيل حركة الحساب</p>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>
@endsection

@push('scripts')
<script>
function exportToPDF() {
    alert('سيتم تطبيق تصدير PDF قريباً');
}

function exportToExcel() {
    alert('سيتم تطبيق تصدير Excel قريباً');
}

function printReport() {
    if (!{{ $account ? 'true' : 'false' }}) {
        alert('يرجى اختيار حساب أولاً');
        return;
    }
    
    const printContent = document.getElementById('ledger-report').innerHTML;
    const originalContent = document.body.innerHTML;
    
    document.body.innerHTML = `
        <div style="direction: rtl; font-family: Arial, sans-serif;">
            <div style="text-align: center; margin-bottom: 20px;">
                <h2>دفتر الأستاذ - {{ $account ? $account->account_name : '' }}</h2>
                <p>للفترة من {{ $startDate->format('Y-m-d') }} إلى {{ $endDate->format('Y-m-d') }}</p>
            </div>
            ${printContent}
        </div>
    `;
    
    window.print();
    document.body.innerHTML = originalContent;
    location.reload();
}

// تحسين تجربة المستخدم
document.getElementById('account_id').addEventListener('change', function() {
    if (this.value) {
        // يمكن إضافة تحديث تلقائي هنا
    }
});
</script>
@endpush

@push('styles')
<style>
    .card {
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        border: none;
        border-radius: 10px;
    }
    
    .card-header {
        border-radius: 10px 10px 0 0 !important;
        font-weight: 600;
    }
    
    .table th, .table td {
        border-top: none;
        padding: 0.75rem;
        vertical-align: middle;
    }
    
    .table-info {
        background-color: rgba(13, 202, 240, 0.1);
    }
    
    .table-success {
        background-color: rgba(25, 135, 84, 0.1);
    }
    
    @media print {
        .btn, .breadcrumb, nav, .card:first-child {
            display: none !important;
        }
        
        .card {
            box-shadow: none !important;
            border: 1px solid #dee2e6 !important;
        }
        
        .card-header {
            background: #f8f9fa !important;
            color: #000 !important;
        }
    }
</style>
@endpush
