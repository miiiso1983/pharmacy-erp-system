@extends('layouts.app')

@section('title', 'نتائج التقرير المخصص - نظام إدارة المذاخر')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">الرئيسية</a></li>
    <li class="breadcrumb-item"><a href="{{ route('reports.index') }}">التقارير</a></li>
    <li class="breadcrumb-item"><a href="{{ route('reports.custom') }}">التقارير المخصصة</a></li>
    <li class="breadcrumb-item active">نتائج التقرير</li>
@endsection

@section('content')
<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>
                <i class="fas fa-chart-bar me-2"></i>
                @if(isset($reportData['report_name']))
                    {{ $reportData['report_name'] }}
                @else
                    نتائج التقرير المخصص
                @endif
            </h2>
            <div>
                <button class="btn btn-success" onclick="exportReport('excel')">
                    <i class="fas fa-file-excel me-2"></i>
                    تصدير Excel
                </button>
                <button class="btn btn-danger" onclick="exportReport('pdf')">
                    <i class="fas fa-file-pdf me-2"></i>
                    تصدير PDF
                </button>
                @if(!isset($customReport))
                    <button class="btn btn-primary" onclick="saveReport()">
                        <i class="fas fa-save me-2"></i>
                        حفظ التقرير
                    </button>
                @endif
                <a href="{{ route('reports.custom.builder') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-edit me-2"></i>
                    تعديل التقرير
                </a>
                <a href="{{ route('reports.custom') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left me-2"></i>
                    العودة
                </a>
            </div>
        </div>
    </div>
</div>

<!-- معلومات التقرير -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card bg-light">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-database fa-2x text-primary me-3"></i>
                            <div>
                                <h6 class="mb-0">مصدر البيانات</h6>
                                <small class="text-muted">
                                    @switch($reportData['table'])
                                        @case('orders')
                                            الطلبات
                                            @break
                                        @case('invoices')
                                            الفواتير
                                            @break
                                        @case('items')
                                            المنتجات
                                            @break
                                        @case('customers')
                                            العملاء
                                            @break
                                    @endswitch
                                </small>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-list fa-2x text-success me-3"></i>
                            <div>
                                <h6 class="mb-0">عدد الحقول</h6>
                                <small class="text-muted">{{ count($reportData['fields']) }} حقل</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-filter fa-2x text-warning me-3"></i>
                            <div>
                                <h6 class="mb-0">عدد الفلاتر</h6>
                                <small class="text-muted">{{ count($reportData['filters'] ?? []) }} فلتر</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-chart-line fa-2x text-info me-3"></i>
                            <div>
                                <h6 class="mb-0">إجمالي النتائج</h6>
                                <small class="text-muted">{{ $reportData['total_records'] }} سجل</small>
                            </div>
                        </div>
                    </div>
                </div>
                
                @if(isset($reportData['report_description']) && $reportData['report_description'])
                    <div class="mt-3 pt-3 border-top">
                        <h6 class="text-muted mb-1">وصف التقرير:</h6>
                        <p class="mb-0">{{ $reportData['report_description'] }}</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- نتائج التقرير -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">
                    <i class="fas fa-table me-2"></i>
                    نتائج التقرير
                </h5>
                <div>
                    <button class="btn btn-outline-primary btn-sm" onclick="refreshReport()">
                        <i class="fas fa-sync-alt me-2"></i>
                        تحديث
                    </button>
                    <button class="btn btn-outline-secondary btn-sm" onclick="printReport()">
                        <i class="fas fa-print me-2"></i>
                        طباعة
                    </button>
                </div>
            </div>
            <div class="card-body">
                @if($reportData['results']->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-striped table-hover" id="reportTable">
                            <thead class="table-dark">
                                <tr>
                                    <th>#</th>
                                    @foreach($reportData['fields'] as $field)
                                        <th>
                                            @switch($reportData['table'])
                                                @case('orders')
                                                    @switch($field)
                                                        @case('id') رقم الطلب @break
                                                        @case('customer_name') اسم العميل @break
                                                        @case('total_amount') إجمالي المبلغ @break
                                                        @case('status') الحالة @break
                                                        @case('created_at') تاريخ الإنشاء @break
                                                        @case('delivery_date') تاريخ التسليم @break
                                                        @default {{ $field }}
                                                    @endswitch
                                                    @break
                                                @case('invoices')
                                                    @switch($field)
                                                        @case('id') رقم الفاتورة @break
                                                        @case('customer_name') اسم العميل @break
                                                        @case('total_amount') إجمالي المبلغ @break
                                                        @case('paid_amount') المبلغ المدفوع @break
                                                        @case('remaining_amount') المبلغ المتبقي @break
                                                        @case('status') الحالة @break
                                                        @case('created_at') تاريخ الإنشاء @break
                                                        @case('due_date') تاريخ الاستحقاق @break
                                                        @default {{ $field }}
                                                    @endswitch
                                                    @break
                                                @case('items')
                                                    @switch($field)
                                                        @case('id') رقم المنتج @break
                                                        @case('name') اسم المنتج @break
                                                        @case('code') رمز المنتج @break
                                                        @case('category') الفئة @break
                                                        @case('stock_quantity') كمية المخزون @break
                                                        @case('min_stock_level') الحد الأدنى @break
                                                        @case('price') السعر @break
                                                        @case('cost') التكلفة @break
                                                        @case('supplier_name') اسم المورد @break
                                                        @case('created_at') تاريخ الإنشاء @break
                                                        @default {{ $field }}
                                                    @endswitch
                                                    @break
                                                @case('customers')
                                                    @switch($field)
                                                        @case('id') رقم العميل @break
                                                        @case('name') اسم العميل @break
                                                        @case('email') البريد الإلكتروني @break
                                                        @case('phone') رقم الهاتف @break
                                                        @case('address') العنوان @break
                                                        @case('total_orders') إجمالي الطلبات @break
                                                        @case('total_amount') إجمالي المبلغ @break
                                                        @case('created_at') تاريخ التسجيل @break
                                                        @default {{ $field }}
                                                    @endswitch
                                                    @break
                                            @endswitch
                                        </th>
                                    @endforeach
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($reportData['results'] as $index => $row)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        @foreach($reportData['fields'] as $field)
                                            <td>
                                                @if(in_array($field, ['created_at', 'updated_at', 'delivery_date', 'due_date']))
                                                    {{ $row->$field ? \Carbon\Carbon::parse($row->$field)->format('Y/m/d H:i') : '-' }}
                                                @elseif(in_array($field, ['total_amount', 'paid_amount', 'remaining_amount', 'price', 'cost']))
                                                    {{ number_format($row->$field ?? 0, 0) }} د.ع
                                                @elseif($field === 'status')
                                                    <span class="badge bg-{{ $row->$field === 'completed' ? 'success' : ($row->$field === 'pending' ? 'warning' : 'secondary') }}">
                                                        {{ $row->$field }}
                                                    </span>
                                                @elseif($field === 'customer_name' && $row->customer)
                                                    {{ $row->customer->name }}
                                                @elseif($field === 'supplier_name' && $row->supplier)
                                                    {{ $row->supplier->name }}
                                                @else
                                                    {{ $row->$field ?? '-' }}
                                                @endif
                                            </td>
                                        @endforeach
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-5">
                        <i class="fas fa-search fa-4x text-muted mb-3"></i>
                        <h5 class="text-muted">لا توجد نتائج</h5>
                        <p class="text-muted">لم يتم العثور على بيانات تطابق معايير التقرير المحددة</p>
                        <a href="{{ route('reports.custom.builder') }}" class="btn btn-primary">
                            <i class="fas fa-edit me-2"></i>
                            تعديل معايير التقرير
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Modal حفظ التقرير -->
<div class="modal fade" id="saveReportModal" tabindex="-1" aria-labelledby="saveReportModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="saveReportModalLabel">حفظ التقرير</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="saveReportForm">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="save_report_name" class="form-label">اسم التقرير <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="save_report_name" name="name" required>
                    </div>
                    <div class="mb-3">
                        <label for="save_report_description" class="form-label">وصف التقرير</label>
                        <textarea class="form-control" id="save_report_description" name="description" rows="3" placeholder="وصف مختصر للتقرير..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                    <button type="submit" class="btn btn-primary">حفظ التقرير</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
const reportConfig = @json($reportData);

function refreshReport() {
    location.reload();
}

function printReport() {
    window.print();
}

function exportReport(format) {
    // سيتم تطوير وظيفة التصدير لاحقاً
    alert(`تصدير ${format.toUpperCase()} قيد التطوير`);
}

function saveReport() {
    const modal = new bootstrap.Modal(document.getElementById('saveReportModal'));
    modal.show();
}

document.getElementById('saveReportForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    const config = {
        table: reportConfig.table,
        fields: reportConfig.fields,
        filters: reportConfig.filters || [],
        groupBy: reportConfig.groupBy,
        orderBy: reportConfig.orderBy,
        orderDirection: reportConfig.orderDirection
    };
    
    fetch('{{ route("reports.custom.save") }}', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({
            name: formData.get('name'),
            description: formData.get('description'),
            config: config
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('تم حفظ التقرير بنجاح');
            const modal = bootstrap.Modal.getInstance(document.getElementById('saveReportModal'));
            modal.hide();
            
            // إعادة توجيه إلى صفحة التقرير المحفوظ
            window.location.href = `/reports/custom/${data.report_id}`;
        } else {
            alert('حدث خطأ في حفظ التقرير');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('حدث خطأ في حفظ التقرير');
    });
});

// تحسين عرض الجدول
$(document).ready(function() {
    if ($.fn.DataTable) {
        $('#reportTable').DataTable({
            language: {
                url: '//cdn.datatables.net/plug-ins/1.10.24/i18n/Arabic.json'
            },
            responsive: true,
            pageLength: 25,
            order: [],
            columnDefs: [
                { orderable: false, targets: 0 }
            ]
        });
    }
});
</script>
@endpush

@push('styles')
<style>
@media print {
    .btn, .breadcrumb, .card-header .btn {
        display: none !important;
    }
    
    .card {
        border: none !important;
        box-shadow: none !important;
    }
    
    .table {
        font-size: 12px;
    }
}

.table th {
    background-color: #2c3e50;
    color: white;
    font-weight: 600;
    border: none;
}

.table td {
    vertical-align: middle;
}

.badge {
    font-size: 0.75rem;
}

.card {
    box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
}

.bg-light {
    background-color: #f8f9fc !important;
}

@media (max-width: 768px) {
    .table-responsive {
        font-size: 0.875rem;
    }
    
    .btn-group .btn {
        padding: 0.375rem 0.5rem;
        font-size: 0.875rem;
    }
}
</style>
@endpush
