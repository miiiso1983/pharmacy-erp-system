@extends('layouts.app')

@section('title', 'قائمة التدفقات النقدية')

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="h3 mb-0">
                        <i class="fas fa-coins text-primary me-2"></i>
                        قائمة التدفقات النقدية
                    </h1>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('finance.index') }}">المالية</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('finance.reports.index') }}">التقارير</a></li>
                            <li class="breadcrumb-item active">التدفقات النقدية</li>
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
                    <form method="GET" action="{{ route('finance.reports.cash-flow') }}">
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
    <div class="row" id="cash-flow-report">
        <div class="col-12">
            <div class="card">
                <div class="card-header text-center">
                    <h4 class="mb-0">قائمة التدفقات النقدية</h4>
                    <p class="text-muted mb-0">
                        للفترة من {{ $startDate->format('Y-m-d') }} إلى {{ $endDate->format('Y-m-d') }}
                    </p>
                </div>
                <div class="card-body">
                    @if(isset($cashFlowData) && count($cashFlowData) > 0)
                    
                    <!-- ملخص التدفقات -->
                    <div class="row mb-4">
                        @php
                            $totalInflow = collect($cashFlowData)->sum('total_inflow');
                            $totalOutflow = collect($cashFlowData)->sum('total_outflow');
                            $netFlow = $totalInflow - $totalOutflow;
                        @endphp
                        
                        <div class="col-md-4">
                            <div class="card bg-success text-white">
                                <div class="card-body text-center">
                                    <h5>إجمالي التدفقات الداخلة</h5>
                                    <h3>{{ number_format($totalInflow, 0) }} د.ع</h3>
                                    <i class="fas fa-arrow-down fa-2x"></i>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-4">
                            <div class="card bg-danger text-white">
                                <div class="card-body text-center">
                                    <h5>إجمالي التدفقات الخارجة</h5>
                                    <h3>{{ number_format($totalOutflow, 0) }} د.ع</h3>
                                    <i class="fas fa-arrow-up fa-2x"></i>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-4">
                            <div class="card bg-{{ $netFlow >= 0 ? 'primary' : 'warning' }} text-white">
                                <div class="card-body text-center">
                                    <h5>صافي التدفق النقدي</h5>
                                    <h3>{{ number_format($netFlow, 0) }} د.ع</h3>
                                    <i class="fas fa-{{ $netFlow >= 0 ? 'plus' : 'minus' }}-circle fa-2x"></i>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- تفاصيل التدفقات لكل حساب -->
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="mb-0">
                                        <i class="fas fa-list me-2"></i>
                                        تفاصيل التدفقات النقدية
                                    </h5>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-hover">
                                            <thead>
                                                <tr>
                                                    <th>الحساب النقدي</th>
                                                    <th class="text-center">التدفقات الداخلة</th>
                                                    <th class="text-center">التدفقات الخارجة</th>
                                                    <th class="text-center">صافي التدفق</th>
                                                    <th class="text-center">عدد المعاملات</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($cashFlowData as $data)
                                                <tr>
                                                    <td>
                                                        <strong>{{ $data['account']->account_name }}</strong>
                                                        <br>
                                                        <small class="text-muted">{{ $data['account']->account_code }}</small>
                                                    </td>
                                                    <td class="text-center text-success">
                                                        <strong>{{ number_format($data['total_inflow'], 0) }}</strong>
                                                        <small class="d-block text-muted">د.ع</small>
                                                    </td>
                                                    <td class="text-center text-danger">
                                                        <strong>{{ number_format($data['total_outflow'], 0) }}</strong>
                                                        <small class="d-block text-muted">د.ع</small>
                                                    </td>
                                                    <td class="text-center text-{{ $data['net_flow'] >= 0 ? 'success' : 'danger' }}">
                                                        <strong>{{ number_format($data['net_flow'], 0) }}</strong>
                                                        <small class="d-block text-muted">د.ع</small>
                                                    </td>
                                                    <td class="text-center">
                                                        <span class="badge bg-primary">
                                                            {{ $data['transactions']->count() }}
                                                        </span>
                                                    </td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                            <tfoot>
                                                <tr class="table-light">
                                                    <th>الإجمالي</th>
                                                    <th class="text-center text-success">
                                                        {{ number_format($totalInflow, 0) }} د.ع
                                                    </th>
                                                    <th class="text-center text-danger">
                                                        {{ number_format($totalOutflow, 0) }} د.ع
                                                    </th>
                                                    <th class="text-center text-{{ $netFlow >= 0 ? 'success' : 'danger' }}">
                                                        {{ number_format($netFlow, 0) }} د.ع
                                                    </th>
                                                    <th class="text-center">
                                                        {{ collect($cashFlowData)->sum(function($data) { return $data['transactions']->count(); }) }}
                                                    </th>
                                                </tr>
                                            </tfoot>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- تحليل التدفقات -->
                    <div class="row mt-4">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="mb-0">
                                        <i class="fas fa-chart-pie me-2"></i>
                                        تحليل التدفقات النقدية
                                    </h5>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <h6>تقييم السيولة:</h6>
                                            @if($netFlow > 0)
                                            <div class="alert alert-success">
                                                <i class="fas fa-thumbs-up me-2"></i>
                                                تدفق نقدي إيجابي - سيولة جيدة
                                            </div>
                                            @elseif($netFlow == 0)
                                            <div class="alert alert-warning">
                                                <i class="fas fa-balance-scale me-2"></i>
                                                تدفق نقدي متوازن
                                            </div>
                                            @else
                                            <div class="alert alert-danger">
                                                <i class="fas fa-exclamation-triangle me-2"></i>
                                                تدفق نقدي سلبي - انتبه للسيولة
                                            </div>
                                            @endif
                                        </div>
                                        <div class="col-md-6">
                                            <h6>النسب المالية:</h6>
                                            <table class="table table-sm">
                                                <tr>
                                                    <td>نسبة التدفق الداخل/الخارج:</td>
                                                    <td class="text-end">
                                                        @if($totalOutflow > 0)
                                                            {{ number_format(($totalInflow / $totalOutflow), 2) }}
                                                        @else
                                                            ∞
                                                        @endif
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>متوسط التدفق لكل حساب:</td>
                                                    <td class="text-end">
                                                        {{ number_format($netFlow / count($cashFlowData), 0) }} د.ع
                                                    </td>
                                                </tr>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    @else
                    <!-- لا توجد بيانات -->
                    <div class="text-center py-5">
                        <i class="fas fa-inbox fa-4x text-muted mb-3"></i>
                        <h4 class="text-muted">لا توجد حسابات نقدية</h4>
                        <p class="text-muted">لا توجد حسابات نقدية أو لا توجد معاملات في الفترة المحددة</p>
                        <a href="{{ route('finance.accounts.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus me-2"></i>
                            إضافة حساب نقدي
                        </a>
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
    const printContent = document.getElementById('cash-flow-report').innerHTML;
    const originalContent = document.body.innerHTML;
    
    document.body.innerHTML = `
        <div style="direction: rtl; font-family: Arial, sans-serif;">
            <div style="text-align: center; margin-bottom: 20px;">
                <h2>قائمة التدفقات النقدية</h2>
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
        padding: 0.75rem;
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
        
        .bg-success, .bg-danger, .bg-primary, .bg-warning {
            background: #f8f9fa !important;
            color: #000 !important;
        }
    }
</style>
@endpush
