@extends('layouts.app')

@section('title', 'منشئ التقارير المخصصة - نظام إدارة المذاخر')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">الرئيسية</a></li>
    <li class="breadcrumb-item"><a href="{{ route('reports.index') }}">التقارير</a></li>
    <li class="breadcrumb-item"><a href="{{ route('reports.custom') }}">التقارير المخصصة</a></li>
    <li class="breadcrumb-item active">منشئ التقارير</li>
@endsection

@section('content')
<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>
                <i class="fas fa-tools me-2"></i>
                منشئ التقارير المخصصة
            </h2>
            <div>
                <button type="button" class="btn btn-success" onclick="generateReport()">
                    <i class="fas fa-play me-2"></i>
                    إنشاء التقرير
                </button>
                <a href="{{ route('reports.custom') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left me-2"></i>
                    العودة
                </a>
            </div>
        </div>
    </div>
</div>

<!-- نموذج إنشاء التقرير -->
<form id="reportForm" method="POST" action="{{ route('reports.custom.generate') }}">
    @csrf
    
    <!-- الخطوة 1: اختيار مصدر البيانات -->
    <div class="card mb-4">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">
                <span class="step-number">1</span>
                اختيار مصدر البيانات
            </h5>
        </div>
        <div class="card-body">
            <div class="row">
                @foreach($tables as $tableKey => $tableInfo)
                    <div class="col-lg-3 col-md-6 mb-3">
                        <div class="form-check table-option">
                            <input class="form-check-input" type="radio" name="table" id="table_{{ $tableKey }}" value="{{ $tableKey }}" onchange="updateFields('{{ $tableKey }}')">
                            <label class="form-check-label w-100" for="table_{{ $tableKey }}">
                                <div class="table-card">
                                    <div class="table-icon">
                                        @switch($tableKey)
                                            @case('orders')
                                                <i class="fas fa-shopping-cart fa-2x text-success"></i>
                                                @break
                                            @case('invoices')
                                                <i class="fas fa-file-invoice fa-2x text-primary"></i>
                                                @break
                                            @case('items')
                                                <i class="fas fa-pills fa-2x text-warning"></i>
                                                @break
                                            @case('customers')
                                                <i class="fas fa-users fa-2x text-info"></i>
                                                @break
                                        @endswitch
                                    </div>
                                    <h6 class="mt-2">{{ $tableInfo['name'] }}</h6>
                                    <small class="text-muted">{{ count($tableInfo['fields']) }} حقل متاح</small>
                                </div>
                            </label>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- الخطوة 2: اختيار الحقول -->
    <div class="card mb-4">
        <div class="card-header bg-info text-white">
            <h5 class="mb-0">
                <span class="step-number">2</span>
                اختيار الحقول المطلوبة
            </h5>
        </div>
        <div class="card-body">
            <div id="fieldsContainer">
                <div class="alert alert-info">
                    <i class="fas fa-info-circle me-2"></i>
                    يرجى اختيار مصدر البيانات أولاً
                </div>
            </div>
        </div>
    </div>

    <!-- الخطوة 3: إضافة فلاتر -->
    <div class="card mb-4">
        <div class="card-header bg-warning text-white">
            <h5 class="mb-0">
                <span class="step-number">3</span>
                إضافة فلاتر (اختياري)
            </h5>
        </div>
        <div class="card-body">
            <div id="filtersContainer">
                <div class="alert alert-warning">
                    <i class="fas fa-filter me-2"></i>
                    الفلاتر تساعد في تخصيص البيانات المعروضة
                </div>
            </div>
            <button type="button" class="btn btn-outline-warning" onclick="addFilter()" disabled id="addFilterBtn">
                <i class="fas fa-plus me-2"></i>
                إضافة فلتر
            </button>
        </div>
    </div>

    <!-- الخطوة 4: ترتيب وتجميع -->
    <div class="card mb-4">
        <div class="card-header bg-secondary text-white">
            <h5 class="mb-0">
                <span class="step-number">4</span>
                ترتيب وتجميع البيانات (اختياري)
            </h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-4">
                    <label for="order_by" class="form-label">ترتيب حسب</label>
                    <select name="order_by" id="order_by" class="form-select" disabled>
                        <option value="">بدون ترتيب</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <label for="order_direction" class="form-label">اتجاه الترتيب</label>
                    <select name="order_direction" id="order_direction" class="form-select">
                        <option value="asc">تصاعدي</option>
                        <option value="desc">تنازلي</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <label for="group_by" class="form-label">تجميع حسب</label>
                    <select name="group_by" id="group_by" class="form-select" disabled>
                        <option value="">بدون تجميع</option>
                    </select>
                </div>
            </div>
        </div>
    </div>

    <!-- الخطوة 5: حفظ التقرير -->
    <div class="card mb-4">
        <div class="card-header bg-success text-white">
            <h5 class="mb-0">
                <span class="step-number">5</span>
                حفظ التقرير (اختياري)
            </h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <label for="report_name" class="form-label">اسم التقرير</label>
                    <input type="text" class="form-control" id="report_name" name="report_name" placeholder="أدخل اسم التقرير">
                </div>
                <div class="col-md-6">
                    <label for="report_description" class="form-label">وصف التقرير</label>
                    <input type="text" class="form-control" id="report_description" name="report_description" placeholder="وصف مختصر للتقرير">
                </div>
            </div>
            <div class="form-check mt-3">
                <input class="form-check-input" type="checkbox" id="save_report" name="save_report">
                <label class="form-check-label" for="save_report">
                    حفظ هذا التقرير لاستخدامه لاحقاً
                </label>
            </div>
        </div>
    </div>
</form>

<!-- معاينة التقرير -->
<div class="card">
    <div class="card-header bg-dark text-white">
        <h5 class="mb-0">
            <i class="fas fa-eye me-2"></i>
            معاينة التقرير
        </h5>
    </div>
    <div class="card-body">
        <div id="previewContainer">
            <div class="text-center py-5 text-muted">
                <i class="fas fa-chart-bar fa-4x mb-3"></i>
                <h5>معاينة التقرير</h5>
                <p>قم بإعداد التقرير واضغط على "إنشاء التقرير" لرؤية النتائج</p>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
const tablesData = @json($tables);
const operatorsData = @json($operators);
let filterCount = 0;

function updateFields(tableKey) {
    const table = tablesData[tableKey];
    const fieldsContainer = document.getElementById('fieldsContainer');
    const orderBySelect = document.getElementById('order_by');
    const groupBySelect = document.getElementById('group_by');
    const addFilterBtn = document.getElementById('addFilterBtn');
    
    // تحديث الحقول
    let fieldsHtml = '<div class="row">';
    Object.entries(table.fields).forEach(([fieldKey, fieldName]) => {
        fieldsHtml += `
            <div class="col-lg-3 col-md-4 col-sm-6 mb-2">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="fields[]" value="${fieldKey}" id="field_${fieldKey}">
                    <label class="form-check-label" for="field_${fieldKey}">
                        ${fieldName}
                    </label>
                </div>
            </div>
        `;
    });
    fieldsHtml += '</div>';
    fieldsContainer.innerHTML = fieldsHtml;
    
    // تحديث خيارات الترتيب والتجميع
    orderBySelect.innerHTML = '<option value="">بدون ترتيب</option>';
    groupBySelect.innerHTML = '<option value="">بدون تجميع</option>';
    
    Object.entries(table.fields).forEach(([fieldKey, fieldName]) => {
        orderBySelect.innerHTML += `<option value="${fieldKey}">${fieldName}</option>`;
        groupBySelect.innerHTML += `<option value="${fieldKey}">${fieldName}</option>`;
    });
    
    // تفعيل المنتجات
    orderBySelect.disabled = false;
    groupBySelect.disabled = false;
    addFilterBtn.disabled = false;
    
    // مسح الفلاتر السابقة
    document.getElementById('filtersContainer').innerHTML = `
        <div class="alert alert-warning">
            <i class="fas fa-filter me-2"></i>
            الفلاتر تساعد في تخصيص البيانات المعروضة
        </div>
    `;
    filterCount = 0;
}

function addFilter() {
    const selectedTable = document.querySelector('input[name="table"]:checked');
    if (!selectedTable) {
        alert('يرجى اختيار مصدر البيانات أولاً');
        return;
    }
    
    const table = tablesData[selectedTable.value];
    const filtersContainer = document.getElementById('filtersContainer');
    
    if (filterCount === 0) {
        filtersContainer.innerHTML = '';
    }
    
    const filterHtml = `
        <div class="filter-row mb-3" id="filter_${filterCount}">
            <div class="row align-items-end">
                <div class="col-md-3">
                    <label class="form-label">الحقل</label>
                    <select name="filters[${filterCount}][field]" class="form-select">
                        ${Object.entries(table.fields).map(([key, name]) => `<option value="${key}">${name}</option>`).join('')}
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">المشغل</label>
                    <select name="filters[${filterCount}][operator]" class="form-select">
                        ${Object.entries(operatorsData).map(([key, name]) => `<option value="${key}">${name}</option>`).join('')}
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label">القيمة</label>
                    <input type="text" name="filters[${filterCount}][value]" class="form-control" placeholder="أدخل القيمة">
                </div>
                <div class="col-md-2">
                    <button type="button" class="btn btn-outline-danger" onclick="removeFilter(${filterCount})">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
            </div>
        </div>
    `;
    
    filtersContainer.insertAdjacentHTML('beforeend', filterHtml);
    filterCount++;
}

function removeFilter(index) {
    document.getElementById(`filter_${index}`).remove();
}

function generateReport() {
    const selectedTable = document.querySelector('input[name="table"]:checked');
    const selectedFields = document.querySelectorAll('input[name="fields[]"]:checked');
    
    if (!selectedTable) {
        alert('يرجى اختيار مصدر البيانات');
        return;
    }
    
    if (selectedFields.length === 0) {
        alert('يرجى اختيار حقل واحد على الأقل');
        return;
    }
    
    // إرسال النموذج
    document.getElementById('reportForm').submit();
}
</script>
@endpush

@push('styles')
<style>
.step-number {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 30px;
    height: 30px;
    background: rgba(255,255,255,0.2);
    border-radius: 50%;
    margin-right: 10px;
    font-weight: bold;
}

.table-option {
    height: 100%;
}

.table-card {
    border: 2px solid #e3e6f0;
    border-radius: 8px;
    padding: 20px;
    text-align: center;
    transition: all 0.3s ease;
    height: 100%;
    cursor: pointer;
}

.table-card:hover {
    border-color: #4e73df;
    background-color: #f8f9fc;
}

.form-check-input:checked + .form-check-label .table-card {
    border-color: #4e73df;
    background-color: #4e73df;
    color: white;
}

.form-check-input:checked + .form-check-label .table-card .text-muted {
    color: rgba(255,255,255,0.8) !important;
}

.table-icon {
    margin-bottom: 10px;
}

.filter-row {
    border: 1px solid #e3e6f0;
    border-radius: 8px;
    padding: 15px;
    background-color: #f8f9fc;
}

.card-header {
    font-weight: 600;
}

@media (max-width: 768px) {
    .table-card {
        padding: 15px;
    }
    
    .step-number {
        width: 25px;
        height: 25px;
        font-size: 0.875rem;
    }
}
</style>
@endpush
