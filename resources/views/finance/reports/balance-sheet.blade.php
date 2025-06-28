@extends('layouts.app')

@section('title', 'الميزانية العمومية')

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="h3 mb-0">
                        <i class="fas fa-chart-bar text-primary me-2"></i>
                        الميزانية العمومية
                    </h1>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('finance.index') }}">المالية</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('finance.reports.index') }}">التقارير</a></li>
                            <li class="breadcrumb-item active">الميزانية العمومية</li>
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
                    <form method="GET" action="{{ route('finance.reports.balance-sheet') }}">
                        <div class="row align-items-end">
                            <div class="col-md-3">
                                <label for="as_of_date" class="form-label">كما في تاريخ</label>
                                <input type="date" class="form-control" id="as_of_date" name="as_of_date" 
                                       value="{{ request('as_of_date', $asOfDate->format('Y-m-d')) }}">
                            </div>
                            <div class="col-md-3">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-sync me-2"></i>
                                    تحديث التقرير
                                </button>
                            </div>
                            <div class="col-md-6 text-end">
                                <h5 class="mb-0 text-muted">
                                    كما في: {{ $asOfDate->format('Y-m-d') }}
                                </h5>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- التقرير -->
    <div class="row" id="balance-sheet-report">
        <div class="col-12">
            <div class="card">
                <div class="card-header text-center">
                    <h4 class="mb-0">الميزانية العمومية</h4>
                    <p class="text-muted mb-0">كما في {{ $asOfDate->format('Y-m-d') }}</p>
                </div>
                <div class="card-body">
                    <div class="row">
                        <!-- الأصول -->
                        <div class="col-md-6">
                            <div class="card h-100">
                                <div class="card-header bg-success text-white">
                                    <h5 class="mb-0">
                                        <i class="fas fa-coins me-2"></i>
                                        الأصول
                                    </h5>
                                </div>
                                <div class="card-body">
                                    @if(isset($assets) && $assets->count() > 0)
                                    <div class="table-responsive">
                                        <table class="table table-sm">
                                            <tbody>
                                                @foreach($assets as $asset)
                                                <tr>
                                                    <td>{{ $asset->account_name }}</td>
                                                    <td class="text-end">
                                                        {{ number_format($asset->balance_as_of_date ?? 0, 0) }}
                                                    </td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                            <tfoot>
                                                <tr class="table-success">
                                                    <th>إجمالي الأصول</th>
                                                    <th class="text-end">
                                                        {{ number_format($totalAssets ?? 0, 0) }} د.ع
                                                    </th>
                                                </tr>
                                            </tfoot>
                                        </table>
                                    </div>
                                    @else
                                    <div class="text-center py-4">
                                        <i class="fas fa-inbox fa-2x text-muted mb-2"></i>
                                        <p class="text-muted">لا توجد أصول</p>
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- الخصوم وحقوق الملكية -->
                        <div class="col-md-6">
                            <!-- الخصوم -->
                            <div class="card mb-3">
                                <div class="card-header bg-warning text-dark">
                                    <h5 class="mb-0">
                                        <i class="fas fa-credit-card me-2"></i>
                                        الخصوم
                                    </h5>
                                </div>
                                <div class="card-body">
                                    @if(isset($liabilities) && $liabilities->count() > 0)
                                    <div class="table-responsive">
                                        <table class="table table-sm">
                                            <tbody>
                                                @foreach($liabilities as $liability)
                                                <tr>
                                                    <td>{{ $liability->account_name }}</td>
                                                    <td class="text-end">
                                                        {{ number_format($liability->balance_as_of_date ?? 0, 0) }}
                                                    </td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                            <tfoot>
                                                <tr class="table-warning">
                                                    <th>إجمالي الخصوم</th>
                                                    <th class="text-end">
                                                        {{ number_format($totalLiabilities ?? 0, 0) }} د.ع
                                                    </th>
                                                </tr>
                                            </tfoot>
                                        </table>
                                    </div>
                                    @else
                                    <div class="text-center py-2">
                                        <p class="text-muted mb-0">لا توجد خصوم</p>
                                    </div>
                                    @endif
                                </div>
                            </div>

                            <!-- حقوق الملكية -->
                            <div class="card">
                                <div class="card-header bg-info text-white">
                                    <h5 class="mb-0">
                                        <i class="fas fa-user-tie me-2"></i>
                                        حقوق الملكية
                                    </h5>
                                </div>
                                <div class="card-body">
                                    @if(isset($equity) && $equity->count() > 0)
                                    <div class="table-responsive">
                                        <table class="table table-sm">
                                            <tbody>
                                                @foreach($equity as $equityAccount)
                                                <tr>
                                                    <td>{{ $equityAccount->account_name }}</td>
                                                    <td class="text-end">
                                                        {{ number_format($equityAccount->balance_as_of_date ?? 0, 0) }}
                                                    </td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                            <tfoot>
                                                <tr class="table-info">
                                                    <th>إجمالي حقوق الملكية</th>
                                                    <th class="text-end">
                                                        {{ number_format($totalEquity ?? 0, 0) }} د.ع
                                                    </th>
                                                </tr>
                                            </tfoot>
                                        </table>
                                    </div>
                                    @else
                                    <div class="text-center py-2">
                                        <p class="text-muted mb-0">لا توجد حقوق ملكية</p>
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- الإجماليات -->
                    <div class="row mt-4">
                        <div class="col-12">
                            <div class="card bg-light">
                                <div class="card-body">
                                    <div class="row text-center">
                                        <div class="col-md-4">
                                            <h5 class="text-success">إجمالي الأصول</h5>
                                            <h3 class="text-success">{{ number_format($totalAssets ?? 0, 0) }} د.ع</h3>
                                        </div>
                                        <div class="col-md-4">
                                            <h5 class="text-warning">إجمالي الخصوم</h5>
                                            <h3 class="text-warning">{{ number_format($totalLiabilities ?? 0, 0) }} د.ع</h3>
                                        </div>
                                        <div class="col-md-4">
                                            <h5 class="text-info">إجمالي حقوق الملكية</h5>
                                            <h3 class="text-info">{{ number_format($totalEquity ?? 0, 0) }} د.ع</h3>
                                        </div>
                                    </div>
                                    
                                    <hr>
                                    
                                    <div class="row text-center">
                                        <div class="col-md-6">
                                            <h5>إجمالي الخصوم وحقوق الملكية</h5>
                                            <h3 class="text-primary">
                                                {{ number_format(($totalLiabilities ?? 0) + ($totalEquity ?? 0), 0) }} د.ع
                                            </h3>
                                        </div>
                                        <div class="col-md-6">
                                            <h5>الفرق (يجب أن يكون صفر)</h5>
                                            @php
                                                $difference = ($totalAssets ?? 0) - (($totalLiabilities ?? 0) + ($totalEquity ?? 0));
                                            @endphp
                                            <h3 class="text-{{ $difference == 0 ? 'success' : 'danger' }}">
                                                {{ number_format($difference, 0) }} د.ع
                                            </h3>
                                            @if($difference != 0)
                                            <small class="text-danger">
                                                <i class="fas fa-exclamation-triangle me-1"></i>
                                                الميزانية غير متوازنة
                                            </small>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function exportToPDF() {
    // يمكن تطبيق تصدير PDF هنا
    alert('سيتم تطبيق تصدير PDF قريباً');
}

function exportToExcel() {
    // يمكن تطبيق تصدير Excel هنا
    alert('سيتم تطبيق تصدير Excel قريباً');
}

function printReport() {
    // إخفاء العناصر غير المرغوب فيها في الطباعة
    const printContent = document.getElementById('balance-sheet-report').innerHTML;
    const originalContent = document.body.innerHTML;
    
    document.body.innerHTML = `
        <div style="direction: rtl; font-family: Arial, sans-serif;">
            <div style="text-align: center; margin-bottom: 20px;">
                <h2>الميزانية العمومية</h2>
                <p>كما في {{ $asOfDate->format('Y-m-d') }}</p>
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
        
        .bg-success, .bg-warning, .bg-info {
            background: #f8f9fa !important;
            color: #000 !important;
        }
    }
</style>
@endpush
