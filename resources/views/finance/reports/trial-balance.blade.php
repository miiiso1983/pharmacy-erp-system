@extends('layouts.app')

@section('title', 'ميزان المراجعة - ' . __('messages.system_name'))

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">{{ __('navigation.dashboard') }}</a></li>
    <li class="breadcrumb-item"><a href="{{ route('finance.dashboard') }}">النظام المالي</a></li>
    <li class="breadcrumb-item"><a href="{{ route('finance.reports.index') }}">التقارير المالية</a></li>
    <li class="breadcrumb-item active">ميزان المراجعة</li>
@endsection

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0">
                <i class="fas fa-balance-scale me-2"></i>
                ميزان المراجعة
            </h1>
            <p class="text-muted">
                من {{ \Carbon\Carbon::parse($startDate)->format('d/m/Y') }} 
                إلى {{ \Carbon\Carbon::parse($endDate)->format('d/m/Y') }}
            </p>
        </div>
        <div>
            <button class="btn btn-outline-primary me-2" onclick="window.print()">
                <i class="fas fa-print me-2"></i>
                طباعة
            </button>
            <button class="btn btn-outline-success me-2" onclick="exportToExcel()">
                <i class="fas fa-file-excel me-2"></i>
                تصدير Excel
            </button>
            <a href="{{ route('finance.reports.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left me-2"></i>
                العودة
            </a>
        </div>
    </div>

    <!-- فلاتر التقرير -->
    <div class="card mb-4">
        <div class="card-header">
            <h5 class="card-title mb-0">
                <i class="fas fa-filter me-2"></i>
                فلاتر التقرير
            </h5>
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('finance.reports.trial-balance') }}">
                <div class="row">
                    <div class="col-md-3 mb-3">
                        <label for="start_date" class="form-label">من تاريخ</label>
                        <input type="date" class="form-control" id="start_date" name="start_date" 
                               value="{{ $startDate }}">
                    </div>
                    <div class="col-md-3 mb-3">
                        <label for="end_date" class="form-label">إلى تاريخ</label>
                        <input type="date" class="form-control" id="end_date" name="end_date" 
                               value="{{ $endDate }}">
                    </div>
                    <div class="col-md-3 mb-3">
                        <label for="account_type" class="form-label">نوع الحساب</label>
                        <select class="form-select" id="account_type" name="account_type">
                            <option value="">جميع الأنواع</option>
                            <option value="asset" {{ request('account_type') == 'asset' ? 'selected' : '' }}>الأصول</option>
                            <option value="liability" {{ request('account_type') == 'liability' ? 'selected' : '' }}>الخصوم</option>
                            <option value="equity" {{ request('account_type') == 'equity' ? 'selected' : '' }}>حقوق الملكية</option>
                            <option value="revenue" {{ request('account_type') == 'revenue' ? 'selected' : '' }}>الإيرادات</option>
                            <option value="expense" {{ request('account_type') == 'expense' ? 'selected' : '' }}>المصروفات</option>
                        </select>
                    </div>
                    <div class="col-md-3 mb-3 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary me-2">
                            <i class="fas fa-search me-1"></i>
                            تحديث التقرير
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- ميزان المراجعة -->
    <div class="card">
        <div class="card-header">
            <h5 class="card-title mb-0">
                <i class="fas fa-table me-2"></i>
                ميزان المراجعة
                <span class="badge bg-primary ms-2">{{ count($trialBalance) }} حساب</span>
            </h5>
        </div>
        <div class="card-body">
            @if(count($trialBalance) > 0)
                <div class="table-responsive">
                    <table class="table table-bordered table-hover" id="trialBalanceTable">
                        <thead class="table-dark">
                            <tr>
                                <th>رمز الحساب</th>
                                <th>اسم الحساب</th>
                                <th>نوع الحساب</th>
                                <th>الرصيد الافتتاحي</th>
                                <th>إجمالي المدين</th>
                                <th>إجمالي الدائن</th>
                                <th>الرصيد الختامي</th>
                                <th>طبيعة الرصيد</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $totalOpeningBalance = 0;
                                $totalDebits = 0;
                                $totalCredits = 0;
                                $totalClosingBalance = 0;
                            @endphp
                            
                            @foreach($trialBalance as $item)
                                @php
                                    $totalOpeningBalance += $item['opening_balance'];
                                    $totalDebits += $item['debits'];
                                    $totalCredits += $item['credits'];
                                    $totalClosingBalance += abs($item['balance']);
                                @endphp
                                <tr>
                                    <td>
                                        <code>{{ $item['account']->account_code }}</code>
                                    </td>
                                    <td>
                                        <div>
                                            <strong>{{ $item['account']->account_name }}</strong>
                                            @if($item['account']->account_name_en)
                                                <br><small class="text-muted">{{ $item['account']->account_name_en }}</small>
                                            @endif
                                        </div>
                                    </td>
                                    <td>
                                        @switch($item['account']->account_type)
                                            @case('asset')
                                                <span class="badge bg-info">أصول</span>
                                                @break
                                            @case('liability')
                                                <span class="badge bg-warning">خصوم</span>
                                                @break
                                            @case('equity')
                                                <span class="badge bg-secondary">حقوق ملكية</span>
                                                @break
                                            @case('revenue')
                                                <span class="badge bg-success">إيرادات</span>
                                                @break
                                            @case('expense')
                                                <span class="badge bg-danger">مصروفات</span>
                                                @break
                                        @endswitch
                                    </td>
                                    <td class="text-end">
                                        {{ number_format($item['opening_balance'], 2) }}
                                    </td>
                                    <td class="text-end">
                                        {{ number_format($item['debits'], 2) }}
                                    </td>
                                    <td class="text-end">
                                        {{ number_format($item['credits'], 2) }}
                                    </td>
                                    <td class="text-end">
                                        <strong>{{ number_format(abs($item['balance']), 2) }}</strong>
                                    </td>
                                    <td class="text-center">
                                        @if($item['balance'] >= 0)
                                            @if($item['balance_type'] == 'debit')
                                                <span class="badge bg-primary">مدين</span>
                                            @else
                                                <span class="badge bg-success">دائن</span>
                                            @endif
                                        @else
                                            @if($item['balance_type'] == 'debit')
                                                <span class="badge bg-success">دائن</span>
                                            @else
                                                <span class="badge bg-primary">مدين</span>
                                            @endif
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot class="table-secondary">
                            <tr>
                                <th colspan="3">الإجمالي</th>
                                <th class="text-end">{{ number_format($totalOpeningBalance, 2) }}</th>
                                <th class="text-end">{{ number_format($totalDebits, 2) }}</th>
                                <th class="text-end">{{ number_format($totalCredits, 2) }}</th>
                                <th class="text-end">{{ number_format($totalClosingBalance, 2) }}</th>
                                <th></th>
                            </tr>
                        </tfoot>
                    </table>
                </div>

                <!-- ملخص التوازن -->
                <div class="row mt-4">
                    <div class="col-md-6">
                        <div class="card bg-light">
                            <div class="card-body">
                                <h6 class="card-title">ملخص التوازن</h6>
                                <div class="row">
                                    <div class="col-6">
                                        <strong>إجمالي المدين:</strong>
                                    </div>
                                    <div class="col-6 text-end">
                                        {{ number_format($totalDebits, 2) }} د.ع
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-6">
                                        <strong>إجمالي الدائن:</strong>
                                    </div>
                                    <div class="col-6 text-end">
                                        {{ number_format($totalCredits, 2) }} د.ع
                                    </div>
                                </div>
                                <hr>
                                <div class="row">
                                    <div class="col-6">
                                        <strong>الفرق:</strong>
                                    </div>
                                    <div class="col-6 text-end">
                                        <span class="{{ abs($totalDebits - $totalCredits) < 0.01 ? 'text-success' : 'text-danger' }}">
                                            {{ number_format($totalDebits - $totalCredits, 2) }} د.ع
                                        </span>
                                    </div>
                                </div>
                                @if(abs($totalDebits - $totalCredits) < 0.01)
                                    <div class="alert alert-success mt-2 mb-0">
                                        <i class="fas fa-check-circle me-2"></i>
                                        الميزان متوازن
                                    </div>
                                @else
                                    <div class="alert alert-warning mt-2 mb-0">
                                        <i class="fas fa-exclamation-triangle me-2"></i>
                                        الميزان غير متوازن - يرجى مراجعة القيود
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card bg-light">
                            <div class="card-body">
                                <h6 class="card-title">إحصائيات التقرير</h6>
                                <div class="row">
                                    <div class="col-6">
                                        <strong>عدد الحسابات:</strong>
                                    </div>
                                    <div class="col-6 text-end">
                                        {{ count($trialBalance) }}
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-6">
                                        <strong>الفترة:</strong>
                                    </div>
                                    <div class="col-6 text-end">
                                        {{ \Carbon\Carbon::parse($startDate)->diffInDays(\Carbon\Carbon::parse($endDate)) + 1 }} يوم
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-6">
                                        <strong>تاريخ التقرير:</strong>
                                    </div>
                                    <div class="col-6 text-end">
                                        {{ now()->format('d/m/Y H:i') }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @else
                <div class="text-center py-5">
                    <i class="fas fa-balance-scale fa-3x text-muted mb-3"></i>
                    <h5 class="text-muted">لا توجد بيانات لعرضها</h5>
                    <p class="text-muted">لا توجد حركات محاسبية في الفترة المحددة</p>
                    <a href="{{ route('finance.journal-entries.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus me-2"></i>
                        إنشاء قيد محاسبي
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function exportToExcel() {
    // تحويل الجدول إلى Excel
    const table = document.getElementById('trialBalanceTable');
    const wb = XLSX.utils.table_to_book(table, {sheet: "ميزان المراجعة"});
    const filename = `ميزان_المراجعة_${new Date().toISOString().split('T')[0]}.xlsx`;
    XLSX.writeFile(wb, filename);
}

// تحميل مكتبة XLSX
if (typeof XLSX === 'undefined') {
    const script = document.createElement('script');
    script.src = 'https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js';
    document.head.appendChild(script);
}
</script>
@endpush

@push('styles')
<style>
@media print {
    .btn, .card-header, .breadcrumb, nav {
        display: none !important;
    }
    
    .card {
        border: none !important;
        box-shadow: none !important;
    }
    
    .table {
        font-size: 12px;
    }
    
    .badge {
        background-color: #6c757d !important;
        color: white !important;
    }
}

.table th {
    background-color: #343a40 !important;
    color: white !important;
    border-color: #454d55 !important;
}

.table-bordered th,
.table-bordered td {
    border: 1px solid #dee2e6;
}

.text-end {
    text-align: end !important;
}

code {
    background-color: #f8f9fa;
    padding: 2px 4px;
    border-radius: 3px;
    font-size: 0.875em;
}
</style>
@endpush
