@extends('layouts.app')

@section('title', 'قائمة الدخل')

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="h3 mb-0">
                        <i class="fas fa-chart-line text-primary me-2"></i>
                        قائمة الدخل
                    </h1>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('finance.index') }}">المالية</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('finance.reports.index') }}">التقارير</a></li>
                            <li class="breadcrumb-item active">قائمة الدخل</li>
                        </ol>
                    </nav>
                </div>
                <div>
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
                    <form method="GET" action="{{ route('finance.reports.income-statement') }}">
                        <div class="row align-items-end">
                            <div class="col-md-3">
                                <label for="start_date" class="form-label">من تاريخ</label>
                                <input type="date" class="form-control" id="start_date" name="start_date" 
                                       value="{{ request('start_date', $startDate->format('Y-m-d')) }}">
                            </div>
                            <div class="col-md-3">
                                <label for="end_date" class="form-label">إلى تاريخ</label>
                                <input type="date" class="form-control" id="end_date" name="end_date" 
                                       value="{{ request('end_date', $endDate->format('Y-m-d')) }}">
                            </div>
                            <div class="col-md-3">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-sync me-2"></i>
                                    تحديث التقرير
                                </button>
                            </div>
                            <div class="col-md-3 text-end">
                                <h6 class="mb-0 text-muted">
                                    الفترة: {{ $startDate->format('Y-m-d') }} - {{ $endDate->format('Y-m-d') }}
                                </h6>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- التقرير -->
    <div class="row" id="income-statement-report">
        <div class="col-12">
            <div class="card">
                <div class="card-header text-center">
                    <h4 class="mb-0">قائمة الدخل</h4>
                    <p class="text-muted mb-0">
                        للفترة من {{ $startDate->format('Y-m-d') }} إلى {{ $endDate->format('Y-m-d') }}
                    </p>
                </div>
                <div class="card-body">
                    <div class="row">
                        <!-- الإيرادات -->
                        <div class="col-md-6">
                            <div class="card h-100">
                                <div class="card-header bg-success text-white">
                                    <h5 class="mb-0">
                                        <i class="fas fa-arrow-up me-2"></i>
                                        الإيرادات
                                    </h5>
                                </div>
                                <div class="card-body">
                                    @if(isset($revenues) && $revenues->count() > 0)
                                    <div class="table-responsive">
                                        <table class="table table-sm">
                                            <tbody>
                                                @foreach($revenues as $revenue)
                                                <tr>
                                                    <td>{{ $revenue->account_name }}</td>
                                                    <td class="text-end">
                                                        {{ number_format($revenue->period_balance ?? 0, 0) }}
                                                    </td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                            <tfoot>
                                                <tr class="table-success">
                                                    <th>إجمالي الإيرادات</th>
                                                    <th class="text-end">
                                                        {{ number_format($totalRevenues ?? 0, 0) }} د.ع
                                                    </th>
                                                </tr>
                                            </tfoot>
                                        </table>
                                    </div>
                                    @else
                                    <div class="text-center py-4">
                                        <i class="fas fa-inbox fa-2x text-muted mb-2"></i>
                                        <p class="text-muted">لا توجد إيرادات</p>
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- المصروفات -->
                        <div class="col-md-6">
                            <div class="card h-100">
                                <div class="card-header bg-danger text-white">
                                    <h5 class="mb-0">
                                        <i class="fas fa-arrow-down me-2"></i>
                                        المصروفات
                                    </h5>
                                </div>
                                <div class="card-body">
                                    @if(isset($expenses) && $expenses->count() > 0)
                                    <div class="table-responsive">
                                        <table class="table table-sm">
                                            <tbody>
                                                @foreach($expenses as $expense)
                                                <tr>
                                                    <td>{{ $expense->account_name }}</td>
                                                    <td class="text-end">
                                                        {{ number_format($expense->period_balance ?? 0, 0) }}
                                                    </td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                            <tfoot>
                                                <tr class="table-danger">
                                                    <th>إجمالي المصروفات</th>
                                                    <th class="text-end">
                                                        {{ number_format($totalExpenses ?? 0, 0) }} د.ع
                                                    </th>
                                                </tr>
                                            </tfoot>
                                        </table>
                                    </div>
                                    @else
                                    <div class="text-center py-4">
                                        <i class="fas fa-inbox fa-2x text-muted mb-2"></i>
                                        <p class="text-muted">لا توجد مصروفات</p>
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- النتيجة النهائية -->
                    <div class="row mt-4">
                        <div class="col-12">
                            <div class="card bg-light">
                                <div class="card-body">
                                    <div class="row text-center">
                                        <div class="col-md-4">
                                            <h5 class="text-success">إجمالي الإيرادات</h5>
                                            <h3 class="text-success">{{ number_format($totalRevenues ?? 0, 0) }} د.ع</h3>
                                        </div>
                                        <div class="col-md-4">
                                            <h5 class="text-danger">إجمالي المصروفات</h5>
                                            <h3 class="text-danger">{{ number_format($totalExpenses ?? 0, 0) }} د.ع</h3>
                                        </div>
                                        <div class="col-md-4">
                                            @php
                                                $netIncome = ($totalRevenues ?? 0) - ($totalExpenses ?? 0);
                                            @endphp
                                            <h5>صافي الدخل</h5>
                                            <h3 class="text-{{ $netIncome >= 0 ? 'success' : 'danger' }}">
                                                {{ number_format($netIncome, 0) }} د.ع
                                            </h3>
                                            @if($netIncome >= 0)
                                            <small class="text-success">
                                                <i class="fas fa-arrow-up me-1"></i>
                                                ربح
                                            </small>
                                            @else
                                            <small class="text-danger">
                                                <i class="fas fa-arrow-down me-1"></i>
                                                خسارة
                                            </small>
                                            @endif
                                        </div>
                                    </div>
                                    
                                    <hr>
                                    
                                    <!-- نسب مئوية -->
                                    <div class="row text-center">
                                        <div class="col-md-6">
                                            <h6>نسبة المصروفات إلى الإيرادات</h6>
                                            @php
                                                $expenseRatio = ($totalRevenues ?? 0) > 0 ? (($totalExpenses ?? 0) / ($totalRevenues ?? 0)) * 100 : 0;
                                            @endphp
                                            <h4 class="text-info">{{ number_format($expenseRatio, 1) }}%</h4>
                                        </div>
                                        <div class="col-md-6">
                                            <h6>هامش الربح</h6>
                                            @php
                                                $profitMargin = ($totalRevenues ?? 0) > 0 ? ($netIncome / ($totalRevenues ?? 0)) * 100 : 0;
                                            @endphp
                                            <h4 class="text-{{ $profitMargin >= 0 ? 'success' : 'danger' }}">
                                                {{ number_format($profitMargin, 1) }}%
                                            </h4>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- تحليل إضافي -->
                    @if(($totalRevenues ?? 0) > 0 || ($totalExpenses ?? 0) > 0)
                    <div class="row mt-4">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="mb-0">
                                        <i class="fas fa-chart-pie me-2"></i>
                                        تحليل الأداء
                                    </h5>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <h6>تقييم الأداء:</h6>
                                            @if($netIncome > 0)
                                            <div class="alert alert-success">
                                                <i class="fas fa-thumbs-up me-2"></i>
                                                الشركة تحقق أرباحاً في هذه الفترة
                                            </div>
                                            @elseif($netIncome == 0)
                                            <div class="alert alert-warning">
                                                <i class="fas fa-balance-scale me-2"></i>
                                                الشركة في نقطة التعادل
                                            </div>
                                            @else
                                            <div class="alert alert-danger">
                                                <i class="fas fa-exclamation-triangle me-2"></i>
                                                الشركة تتكبد خسائر في هذه الفترة
                                            </div>
                                            @endif
                                        </div>
                                        <div class="col-md-6">
                                            <h6>توصيات:</h6>
                                            @if($profitMargin < 10)
                                            <ul class="list-unstyled">
                                                <li><i class="fas fa-arrow-up text-success me-2"></i>زيادة الإيرادات</li>
                                                <li><i class="fas fa-arrow-down text-danger me-2"></i>تقليل المصروفات</li>
                                                <li><i class="fas fa-search text-info me-2"></i>مراجعة الاستراتيجية</li>
                                            </ul>
                                            @else
                                            <ul class="list-unstyled">
                                                <li><i class="fas fa-check text-success me-2"></i>أداء جيد</li>
                                                <li><i class="fas fa-chart-line text-primary me-2"></i>استمرار النمو</li>
                                            </ul>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
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
    const printContent = document.getElementById('income-statement-report').innerHTML;
    const originalContent = document.body.innerHTML;
    
    document.body.innerHTML = `
        <div style="direction: rtl; font-family: Arial, sans-serif;">
            <div style="text-align: center; margin-bottom: 20px;">
                <h2>قائمة الدخل</h2>
                <p>للفترة من {{ $startDate->format('Y-m-d') }} إلى {{ $endDate->format('Y-m-d') }}</p>
            </div>
            ${printContent}
        </div>
    `;
    
    window.print();
    document.body.innerHTML = originalContent;
    location.reload();
}
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
        padding: 0.5rem;
    }
    
    .table tfoot th {
        border-top: 2px solid #dee2e6;
        font-weight: 700;
    }
    
    @media print {
        .btn, .breadcrumb, nav {
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
        
        .bg-success, .bg-danger {
            background: #f8f9fa !important;
            color: #000 !important;
        }
    }
</style>
@endpush
