@extends('layouts.app')

@section('title', 'تقارير المخازن الشاملة')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">الرئيسية</a></li>
    <li class="breadcrumb-item"><a href="{{ route('warehouses.index') }}">المخازن</a></li>
    <li class="breadcrumb-item active">التقارير الشاملة</li>
@endsection

@section('content')
<div class="container-fluid">
    <!-- الإحصائيات العامة -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h4>{{ number_format($totalStats['total_warehouses']) }}</h4>
                            <p class="mb-0">إجمالي المخازن</p>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-warehouse fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h4>{{ number_format($totalStats['active_warehouses']) }}</h4>
                            <p class="mb-0">المخازن النشطة</p>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-check-circle fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-info text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h4>{{ number_format($totalStats['total_value']) }} د.ع</h4>
                            <p class="mb-0">إجمالي القيمة</p>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-dollar-sign fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-warning text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h4>{{ number_format($totalStats['total_items']) }}</h4>
                            <p class="mb-0">إجمالي المنتجات</p>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-boxes fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- أزرار التصدير والطباعة -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">
                            <i class="fas fa-chart-bar me-2"></i>
                            تقارير تفصيلية للمخازن
                        </h5>
                        <div>
                            <button class="btn btn-success me-2" onclick="exportToExcel()">
                                <i class="fas fa-file-excel me-2"></i>
                                تصدير Excel
                            </button>
                            <button class="btn btn-danger me-2" onclick="exportToPDF()">
                                <i class="fas fa-file-pdf me-2"></i>
                                تصدير PDF
                            </button>
                            <button class="btn btn-primary" onclick="printReport()">
                                <i class="fas fa-print me-2"></i>
                                طباعة
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- تقارير تفصيلية لكل مخزن -->
    <div class="row">
        @foreach($warehouseStats as $stat)
        <div class="col-md-6 mb-4">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h6 class="mb-0">
                        <i class="fas fa-warehouse me-2"></i>
                        {{ $stat['warehouse']->name }}
                    </h6>
                    <span class="badge bg-{{ $stat['warehouse']->status == 'active' ? 'success' : 'secondary' }}">
                        {{ $stat['warehouse']->status == 'active' ? 'نشط' : 'غير نشط' }}
                    </span>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-6">
                            <div class="text-center">
                                <h5 class="text-primary">{{ number_format($stat['total_items']) }}</h5>
                                <small class="text-muted">عدد المنتجات</small>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="text-center">
                                <h5 class="text-success">{{ number_format($stat['total_value']) }} د.ع</h5>
                                <small class="text-muted">إجمالي القيمة</small>
                            </div>
                        </div>
                    </div>
                    
                    <hr>
                    
                    <div class="row">
                        <div class="col-6">
                            <div class="text-center">
                                <h6 class="text-warning">{{ number_format($stat['low_stock_items']) }}</h6>
                                <small class="text-muted">عناصر منخفضة المخزون</small>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="text-center">
                                <small class="text-muted">الموقع:</small>
                                <div>{{ $stat['warehouse']->city }} - {{ $stat['warehouse']->area }}</div>
                            </div>
                        </div>
                    </div>

                    <div class="mt-3">
                        <div class="d-flex justify-content-between">
                            <a href="{{ route('warehouses.show', $stat['warehouse']->id) }}" 
                               class="btn btn-sm btn-outline-primary">
                                <i class="fas fa-eye me-1"></i>
                                عرض التفاصيل
                            </a>
                            <a href="{{ route('warehouses.reports', $stat['warehouse']->id) }}" 
                               class="btn btn-sm btn-outline-info">
                                <i class="fas fa-chart-line me-1"></i>
                                تقرير مفصل
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <!-- جدول مقارنة المخازن -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-table me-2"></i>
                        جدول مقارنة المخازن
                    </h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>اسم المخزن</th>
                                    <th>الموقع</th>
                                    <th>النوع</th>
                                    <th>عدد المنتجات</th>
                                    <th>إجمالي القيمة</th>
                                    <th>منتجات منخفضة المخزون</th>
                                    <th>الحالة</th>
                                    <th>الإجراءات</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($warehouseStats as $stat)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <i class="fas fa-warehouse text-primary me-2"></i>
                                            <div>
                                                <strong>{{ $stat['warehouse']->name }}</strong>
                                                <br>
                                                <small class="text-muted">{{ $stat['warehouse']->code }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div>
                                            <strong>{{ $stat['warehouse']->city }}</strong>
                                            <br>
                                            <small class="text-muted">{{ $stat['warehouse']->area }}</small>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge bg-info">{{ $stat['warehouse']->type }}</span>
                                    </td>
                                    <td>
                                        <span class="badge bg-primary">{{ number_format($stat['total_items']) }}</span>
                                    </td>
                                    <td>
                                        <strong>{{ number_format($stat['total_value']) }} د.ع</strong>
                                    </td>
                                    <td>
                                        @if($stat['low_stock_items'] > 0)
                                            <span class="badge bg-warning">{{ number_format($stat['low_stock_items']) }}</span>
                                        @else
                                            <span class="badge bg-success">0</span>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="badge bg-{{ $stat['warehouse']->status == 'active' ? 'success' : 'secondary' }}">
                                            {{ $stat['warehouse']->status == 'active' ? 'نشط' : 'غير نشط' }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('warehouses.show', $stat['warehouse']->id) }}" 
                                               class="btn btn-sm btn-outline-primary" title="عرض">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('warehouses.reports', $stat['warehouse']->id) }}" 
                                               class="btn btn-sm btn-outline-info" title="تقرير">
                                                <i class="fas fa-chart-bar"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- العودة -->
    <div class="row mt-4">
        <div class="col-12">
            <a href="{{ route('warehouses.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-right me-2"></i>
                العودة إلى المخازن
            </a>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function exportToExcel() {
    alert('سيتم إضافة وظيفة تصدير Excel قريباً');
}

function exportToPDF() {
    alert('سيتم إضافة وظيفة تصدير PDF قريباً');
}

function printReport() {
    window.print();
}
</script>
@endpush

@push('styles')
<style>
@media print {
    .btn, .card-header .badge {
        display: none !important;
    }
}
</style>
@endpush
