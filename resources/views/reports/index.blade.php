@extends('layouts.app')

@section('title', 'التقارير - نظام إدارة المذاخر')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">الرئيسية</a></li>
    <li class="breadcrumb-item active">التقارير</li>
@endsection

@section('content')
<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>
                <i class="fas fa-chart-bar me-2"></i>
                التقارير والإحصائيات
            </h2>
        </div>
    </div>
</div>

<!-- مقدمة التقارير -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card bg-primary text-white">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-md-8">
                        <h4 class="mb-2">مركز التقارير والتحليلات</h4>
                        <p class="mb-0">احصل على رؤى شاملة حول أداء عملك من خلال التقارير التفصيلية والإحصائيات المتقدمة</p>
                    </div>
                    <div class="col-md-4 text-end">
                        <i class="fas fa-chart-line fa-4x opacity-75"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- تقارير المبيعات والمالية -->
<div class="row mb-4">
    <div class="col-12">
        <h4 class="mb-3">
            <i class="fas fa-dollar-sign me-2"></i>
            التقارير المالية والمبيعات
        </h4>
    </div>
    
    <div class="col-lg-4 col-md-6 mb-3">
        <div class="card report-card h-100">
            <div class="card-body text-center">
                <div class="report-icon mb-3">
                    <i class="fas fa-chart-line fa-3x text-success"></i>
                </div>
                <h5 class="card-title">تقرير المبيعات</h5>
                <p class="card-text text-muted">تحليل شامل للمبيعات والطلبات خلال فترة محددة</p>
                <a href="{{ route('reports.sales') }}" class="btn btn-success">
                    <i class="fas fa-eye me-2"></i>
                    عرض التقرير
                </a>
            </div>
        </div>
    </div>
    
    <div class="col-lg-4 col-md-6 mb-3">
        <div class="card report-card h-100">
            <div class="card-body text-center">
                <div class="report-icon mb-3">
                    <i class="fas fa-file-invoice-dollar fa-3x text-primary"></i>
                </div>
                <h5 class="card-title">التقرير المالي</h5>
                <p class="card-text text-muted">تقرير الفواتير والتحصيلات والمبالغ المستحقة</p>
                <a href="{{ route('reports.financial') }}" class="btn btn-primary">
                    <i class="fas fa-eye me-2"></i>
                    عرض التقرير
                </a>
            </div>
        </div>
    </div>
    
    <div class="col-lg-4 col-md-6 mb-3">
        <div class="card report-card h-100">
            <div class="card-body text-center">
                <div class="report-icon mb-3">
                    <i class="fas fa-star fa-3x text-warning"></i>
                </div>
                <h5 class="card-title">أفضل المنتجات</h5>
                <p class="card-text text-muted">تقرير المنتجات الأكثر مبيعاً وربحية</p>
                <a href="{{ route('reports.topItems') }}" class="btn btn-warning">
                    <i class="fas fa-eye me-2"></i>
                    عرض التقرير
                </a>
            </div>
        </div>
    </div>
</div>

<!-- تقارير المخزون والعملاء -->
<div class="row mb-4">
    <div class="col-12">
        <h4 class="mb-3">
            <i class="fas fa-warehouse me-2"></i>
            تقارير المخزون والعملاء
        </h4>
    </div>
    
    <div class="col-lg-4 col-md-6 mb-3">
        <div class="card report-card h-100">
            <div class="card-body text-center">
                <div class="report-icon mb-3">
                    <i class="fas fa-boxes fa-3x text-info"></i>
                </div>
                <h5 class="card-title">تقرير المخزون</h5>
                <p class="card-text text-muted">حالة المخزون والمنتجات منخفضة المخزون</p>
                <a href="{{ route('reports.inventory') }}" class="btn btn-info">
                    <i class="fas fa-eye me-2"></i>
                    عرض التقرير
                </a>
            </div>
        </div>
    </div>
    
    <div class="col-lg-4 col-md-6 mb-3">
        <div class="card report-card h-100">
            <div class="card-body text-center">
                <div class="report-icon mb-3">
                    <i class="fas fa-users fa-3x text-secondary"></i>
                </div>
                <h5 class="card-title">تقرير العملاء</h5>
                <p class="card-text text-muted">تحليل أداء العملاء ومشترياتهم</p>
                <a href="{{ route('reports.customers') }}" class="btn btn-secondary">
                    <i class="fas fa-eye me-2"></i>
                    عرض التقرير
                </a>
            </div>
        </div>
    </div>
    
    <div class="col-lg-4 col-md-6 mb-3">
        <div class="card report-card h-100">
            <div class="card-body text-center">
                <div class="report-icon mb-3">
                    <i class="fas fa-chart-pie fa-3x text-danger"></i>
                </div>
                <h5 class="card-title">تقارير مخصصة</h5>
                <p class="card-text text-muted">إنشاء تقارير مخصصة حسب احتياجاتك</p>
                <a href="{{ route('reports.custom') }}" class="btn btn-danger">
                    <i class="fas fa-chart-pie me-2"></i>
                    عرض التقارير المخصصة
                </a>
            </div>
        </div>
    </div>
</div>

<!-- منشئ التقارير المتقدم -->
<div class="row mb-4">
    <div class="col-12">
        <h4 class="mb-3">
            <i class="fas fa-cogs me-2"></i>
            منشئ التقارير المتقدم
        </h4>
    </div>

    <div class="col-12">
        <div class="card border-0 shadow-lg">
            <div class="card-header bg-gradient text-white">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="fas fa-magic me-2"></i>
                        إنشاء تقارير متداخلة ومخصصة
                    </h5>
                    <button class="btn btn-light btn-sm" onclick="toggleAdvancedReports()">
                        <i class="fas fa-chevron-down" id="toggle-icon"></i>
                    </button>
                </div>
            </div>
            <div class="card-body" id="advanced-reports-content" style="display: none;">
                <!-- منشئ التقارير المتقدم سيتم تحميله هنا -->
                <div id="advanced-report-builder">
                    <div class="text-center py-4">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">جاري التحميل...</span>
                        </div>
                        <p class="mt-2">جاري تحميل منشئ التقارير المتقدم...</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- إحصائيات سريعة -->
<div class="row">
    <div class="col-12">
        <h4 class="mb-3">
            <i class="fas fa-tachometer-alt me-2"></i>
            إحصائيات سريعة
        </h4>
    </div>
    
    <div class="col-lg-3 col-md-6 mb-3">
        <div class="card bg-gradient-success text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h4 class="mb-1">{{ \App\Models\Order::count() }}</h4>
                        <p class="mb-0">إجمالي الطلبات</p>
                    </div>
                    <div class="fs-1 opacity-75">
                        <i class="fas fa-shopping-cart"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-lg-3 col-md-6 mb-3">
        <div class="card bg-gradient-primary text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h4 class="mb-1">{{ number_format(\App\Models\Invoice::sum('total_amount'), 0) }}</h4>
                        <p class="mb-0">إجمالي المبيعات (دينار)</p>
                    </div>
                    <div class="fs-1 opacity-75">
                        <i class="fas fa-dollar-sign"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-lg-3 col-md-6 mb-3">
        <div class="card bg-gradient-warning text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h4 class="mb-1">{{ \App\Models\Item::count() }}</h4>
                        <p class="mb-0">إجمالي المنتجات</p>
                    </div>
                    <div class="fs-1 opacity-75">
                        <i class="fas fa-pills"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-lg-3 col-md-6 mb-3">
        <div class="card bg-gradient-info text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h4 class="mb-1">{{ \App\Models\User::where('user_type', 'customer')->count() }}</h4>
                        <p class="mb-0">إجمالي العملاء</p>
                    </div>
                    <div class="fs-1 opacity-75">
                        <i class="fas fa-users"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- نصائح وإرشادات -->
<div class="row mt-4">
    <div class="col-12">
        <div class="card border-info">
            <div class="card-header bg-info text-white">
                <h5 class="mb-0">
                    <i class="fas fa-lightbulb me-2"></i>
                    نصائح لاستخدام التقارير
                </h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <ul class="list-unstyled">
                            <li class="mb-2">
                                <i class="fas fa-check text-success me-2"></i>
                                استخدم فلاتر التاريخ للحصول على بيانات دقيقة
                            </li>
                            <li class="mb-2">
                                <i class="fas fa-check text-success me-2"></i>
                                راجع التقارير بانتظام لمتابعة الأداء
                            </li>
                            <li class="mb-2">
                                <i class="fas fa-check text-success me-2"></i>
                                استخدم تقرير المخزون لتجنب نفاد المنتجات
                            </li>
                        </ul>
                    </div>
                    <div class="col-md-6">
                        <ul class="list-unstyled">
                            <li class="mb-2">
                                <i class="fas fa-check text-success me-2"></i>
                                قارن الأداء بين فترات مختلفة
                            </li>
                            <li class="mb-2">
                                <i class="fas fa-check text-success me-2"></i>
                                اطبع التقارير للاجتماعات والعروض
                            </li>
                            <li class="mb-2">
                                <i class="fas fa-check text-success me-2"></i>
                                تابع أفضل المنتجات لزيادة المبيعات
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
let advancedReportsLoaded = false;

function toggleAdvancedReports() {
    const content = document.getElementById('advanced-reports-content');
    const icon = document.getElementById('toggle-icon');

    if (content.style.display === 'none') {
        content.style.display = 'block';
        icon.classList.remove('fa-chevron-down');
        icon.classList.add('fa-chevron-up');

        // تحميل منشئ التقارير المتقدم إذا لم يتم تحميله بعد
        if (!advancedReportsLoaded) {
            loadAdvancedReportBuilder();
        }
    } else {
        content.style.display = 'none';
        icon.classList.remove('fa-chevron-up');
        icon.classList.add('fa-chevron-down');
    }
}

function loadAdvancedReportBuilder() {
    // تحميل المحتوى من الخادم
    fetch('/api/advanced-reports/builder-content')
        .then(response => {
            if (!response.ok) {
                throw new Error('فشل في تحميل المحتوى');
            }
            return response.text();
        })
        .then(html => {
            document.getElementById('advanced-report-builder').innerHTML = html;
            loadAdvancedReportScripts();
            advancedReportsLoaded = true;

            // إضافة event listeners بعد تحميل المحتوى
            setTimeout(() => {
                addEventListeners();
                console.log('Advanced report builder loaded and event listeners added');
            }, 1000);
        })
        .catch(error => {
            console.error('خطأ في تحميل منشئ التقارير المتقدم:', error);
            loadLocalBuilderContent();
        });
}

function loadLocalBuilderContent() {
    // عرض رسالة خطأ بسيطة
    document.getElementById('advanced-report-builder').innerHTML = `
        <div class="alert alert-warning text-center">
            <i class="fas fa-exclamation-triangle me-2"></i>
            حدث خطأ في تحميل منشئ التقارير المتقدم
            <br>
            <button class="btn btn-warning btn-sm mt-2" onclick="loadAdvancedReportBuilder()">
                <i class="fas fa-redo me-1"></i>
                إعادة المحاولة
            </button>
        </div>
    `;
}

function loadAdvancedReportScripts() {
    console.log('Loading advanced report scripts...');

    // تحميل jQuery إذا لم يكن محملاً
    if (typeof jQuery === 'undefined') {
        const jqueryScript = document.createElement('script');
        jqueryScript.src = 'https://code.jquery.com/jquery-3.6.0.min.js';
        document.head.appendChild(jqueryScript);
    }

    // تحميل SweetAlert2 إذا لم يكن محملاً
    if (typeof Swal === 'undefined') {
        const swalScript = document.createElement('script');
        swalScript.src = 'https://cdn.jsdelivr.net/npm/sweetalert2@11';
        document.head.appendChild(swalScript);
    }

    // تشغيل الكود الخاص بمنشئ التقارير
    setTimeout(() => {
        initializeAdvancedReportBuilder();
        console.log('Advanced report builder initialized');
    }, 1000);
}

function initializeAdvancedReportBuilder() {
    // متغيرات عامة
    window.dataSources = {};
    window.reportTypes = {};
    window.calculationTypes = {};
    window.lastReportResults = null;

    // تحميل البيانات الأساسية
    loadDataSources();
    loadReportTypes();
    loadCalculationTypes();

    // إضافة event listeners للمحتوى المحمل
    setTimeout(() => {
        addEventListeners();
    }, 500);

    // إضافة event listeners
    function addEventListeners() {
        // إضافة listeners لـ checkboxes مصادر البيانات
        const sourceCheckboxes = document.querySelectorAll('#data-sources-list input[type="checkbox"]');
        sourceCheckboxes.forEach(checkbox => {
            checkbox.addEventListener('change', window.updateSelectedSources);
        });

        // إضافة listener لزر إنشاء التقرير
        let generateBtn = document.getElementById('generate-advanced-report-btn');
        if (!generateBtn) {
            // البحث بطرق أخرى
            generateBtn = document.querySelector('button[onclick="generateReport()"]');
        }
        if (!generateBtn) {
            generateBtn = Array.from(document.querySelectorAll('button')).find(btn =>
                btn.textContent.includes('إنشاء التقرير المتداخل')
            );
        }

        if (generateBtn) {
            generateBtn.removeAttribute('onclick');
            generateBtn.addEventListener('click', function(e) {
                e.preventDefault();
                console.log('Generate button clicked');
                if (typeof window.generateAdvancedReport === 'function') {
                    window.generateAdvancedReport();
                } else {
                    console.error('generateAdvancedReport function not found');
                }
            });
            console.log('Generate button event listener added');
        } else {
            console.log('Generate button not found');
        }

        // إضافة event listeners لأزرار التصدير
        addExportEventListeners();
    }

    // إضافة event listeners لأزرار التصدير
    function addExportEventListeners() {
        const exportExcelBtn = document.getElementById('export-excel-btn');
        if (exportExcelBtn && !exportExcelBtn.hasAttribute('data-listener-added')) {
            exportExcelBtn.addEventListener('click', function(e) {
                e.preventDefault();
                console.log('Export Excel button clicked');
                if (typeof window.exportAdvancedToExcel === 'function') {
                    window.exportAdvancedToExcel();
                } else {
                    console.error('exportAdvancedToExcel function not found');
                }
            });
            exportExcelBtn.setAttribute('data-listener-added', 'true');
            console.log('Export Excel button event listener added');
        }

        const exportPdfBtn = document.getElementById('export-pdf-btn');
        if (exportPdfBtn && !exportPdfBtn.hasAttribute('data-listener-added')) {
            exportPdfBtn.addEventListener('click', function(e) {
                e.preventDefault();
                console.log('Export PDF button clicked');
                alert('تصدير PDF قيد التطوير');
            });
            exportPdfBtn.setAttribute('data-listener-added', 'true');
        }

        const saveReportBtn = document.getElementById('save-report-btn');
        if (saveReportBtn && !saveReportBtn.hasAttribute('data-listener-added')) {
            saveReportBtn.addEventListener('click', function(e) {
                e.preventDefault();
                console.log('Save Report button clicked');
                alert('حفظ التقرير قيد التطوير');
            });
            saveReportBtn.setAttribute('data-listener-added', 'true');
        }
    }

    // تحميل مصادر البيانات
    function loadDataSources() {
        $.get('/api/advanced-reports/data-sources')
            .done(function(response) {
                window.dataSources = response.data;
                renderDataSources();
            })
            .fail(function(xhr) {
                console.log('خطأ في تحميل مصادر البيانات:', xhr);
                let errorMessage = 'خطأ في تحميل مصادر البيانات';

                if (xhr.responseJSON && xhr.responseJSON.message) {
                    errorMessage += ': ' + xhr.responseJSON.message;
                } else if (xhr.status) {
                    errorMessage += ': ' + xhr.status + ' ' + xhr.statusText;
                }

                alert(errorMessage);
            });
    }

    // تحميل أنواع التقارير
    function loadReportTypes() {
        $.get('/api/advanced-reports/report-types')
            .done(function(response) {
                window.reportTypes = response.data;
            })
            .fail(function() {
                console.log('خطأ في تحميل أنواع التقارير');
            });
    }

    // تحميل أنواع الحسابات
    function loadCalculationTypes() {
        $.get('/api/advanced-reports/calculation-types')
            .done(function(response) {
                window.calculationTypes = response.data;
            })
            .fail(function() {
                console.log('خطأ في تحميل أنواع الحسابات');
            });
    }

    // عرض مصادر البيانات
    function renderDataSources() {
        const container = document.getElementById('data-sources-list');
        if (!container) return;

        container.innerHTML = '';

        Object.keys(window.dataSources).forEach(key => {
            const source = window.dataSources[key];
            const div = document.createElement('div');
            div.className = 'col-md-6 mb-3';
            div.innerHTML = `
                <div class="card data-source-card" data-source="${key}">
                    <div class="card-body">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" value="${key}" id="source-${key}">
                            <label class="form-check-label" for="source-${key}">
                                <strong>${source.name}</strong>
                                <small class="text-muted d-block">${Object.keys(source.fields).length} حقل متاح</small>
                            </label>
                        </div>
                    </div>
                </div>
            `;
            container.appendChild(div);
        });

        // إضافة event listeners
        container.querySelectorAll('input[type="checkbox"]').forEach(checkbox => {
            checkbox.addEventListener('change', window.updateSelectedSources);
        });

        // تحديث event listeners للزر
        setTimeout(() => {
            addEventListeners();
        }, 100);
    }

    // تحديث المصادر المختارة
    function updateSelectedSources() {
        const selectedSources = Array.from(document.querySelectorAll('#data-sources-list input:checked')).map(cb => cb.value);
        updateColumnsSection(selectedSources);
    }

    // تحديث قسم الأعمدة
    function updateColumnsSection(selectedSources) {
        const container = document.getElementById('columns-list');
        if (!container) return;

        container.innerHTML = '';

        selectedSources.forEach(sourceKey => {
            const source = window.dataSources[sourceKey];
            if (!source) return;

            const sourceDiv = document.createElement('div');
            sourceDiv.className = 'mb-4';
            sourceDiv.innerHTML = `
                <h6 class="text-primary">${source.name}</h6>
                <div class="row" id="columns-${sourceKey}"></div>
            `;
            container.appendChild(sourceDiv);

            const columnsContainer = document.getElementById(`columns-${sourceKey}`);
            Object.keys(source.fields).forEach(fieldKey => {
                const fieldName = source.fields[fieldKey];
                const div = document.createElement('div');
                div.className = 'col-md-6 mb-2';
                div.innerHTML = `
                    <div class="form-check">
                        <input class="form-check-input column-checkbox" type="checkbox"
                               value="${fieldKey}" data-source="${sourceKey}" id="col-${sourceKey}-${fieldKey}">
                        <label class="form-check-label" for="col-${sourceKey}-${fieldKey}">
                            ${fieldName}
                        </label>
                    </div>
                `;
                columnsContainer.appendChild(div);
            });
        });
    }

    // متغيرات عامة لمنشئ التقارير
    window.selectedDataSources = [];
    window.selectedColumns = [];
    window.lastReportResults = null;

    console.log('Global variables initialized');

    // تحديث المصادر المختارة
    window.updateSelectedSources = function() {
        window.selectedDataSources = Array.from(document.querySelectorAll('#data-sources-list input:checked')).map(cb => cb.value);

        console.log('Updated selected sources:', window.selectedDataSources);

        // إظهار/إخفاء الأقسام التالية
        const columnsSection = document.getElementById('columns-section');
        const generateSection = document.getElementById('generate-section');

        if (window.selectedDataSources.length > 0) {
            columnsSection.style.display = 'block';
            generateSection.style.display = 'block';
            updateColumnsSection(window.selectedDataSources);
        } else {
            columnsSection.style.display = 'none';
            generateSection.style.display = 'none';
        }

        // تحديث الأعمدة المختارة
        window.selectedColumns = window.selectedDataSources;
    };

    // إنشاء التقرير المتقدم
    window.generateAdvancedReport = function() {
        console.log('generateAdvancedReport called');

        // جمع البيانات
        const selectedColumns = Array.from(document.querySelectorAll('.column-checkbox:checked')).map(cb => ({
            field: cb.value,
            source: cb.dataset.source,
            alias: cb.nextElementSibling.textContent.trim()
        }));

        console.log('Selected columns:', selectedColumns);
        console.log('Selected data sources:', window.selectedDataSources);

        if (selectedColumns.length === 0) {
            alert('يرجى اختيار عمود واحد على الأقل');
            return;
        }

        const reportData = {
            data_sources: window.selectedDataSources,
            columns: selectedColumns,
            calculations: [
                {
                    type: 'count',
                    field: 'id',
                    alias: 'عدد السجلات',
                    source: window.selectedDataSources[0]
                }
            ]
        };

        // إظهار مؤشر التحميل
        document.getElementById('loading-spinner').classList.add('show');
        document.getElementById('results-section').style.display = 'none';

        // إرسال الطلب
        $.ajax({
            url: '/api/advanced-reports/create-integrated-test?' + $.param(reportData),
            method: 'GET'
        })
        .done(function(response) {
            document.getElementById('loading-spinner').classList.remove('show');
            if (response.success) {
                window.lastReportResults = response.data;
                displayAdvancedResults(response.data);
                document.getElementById('results-section').style.display = 'block';
                document.getElementById('export-options').style.display = 'block';

                // إضافة event listeners لأزرار التصدير بعد إظهارها
                setTimeout(() => {
                    addExportEventListeners();
                }, 100);
            } else {
                alert('خطأ في إنشاء التقرير: ' + (response.error || 'خطأ غير معروف'));
            }
        })
        .fail(function(xhr) {
            document.getElementById('loading-spinner').classList.remove('show');
            console.log('خطأ في إنشاء التقرير:', xhr);

            let errorMessage = 'خطأ غير معروف';

            if (xhr.responseJSON) {
                if (xhr.responseJSON.message) {
                    errorMessage = xhr.responseJSON.message;
                } else if (xhr.responseJSON.error) {
                    errorMessage = xhr.responseJSON.error;
                }
            } else if (xhr.responseText) {
                try {
                    const response = JSON.parse(xhr.responseText);
                    errorMessage = response.message || response.error || errorMessage;
                } catch (e) {
                    errorMessage = 'خطأ في الخادم: ' + xhr.status + ' ' + xhr.statusText;
                }
            } else {
                errorMessage = 'خطأ في الاتصال: ' + xhr.status + ' ' + xhr.statusText;
            }

            alert('خطأ في إنشاء التقرير: ' + errorMessage);
        });
    };

    // عرض النتائج
    function displayAdvancedResults(data) {
        const container = document.getElementById('results-content');

        let html = '<div class="row mb-4">';

        // إحصائيات سريعة
        if (data.statistics && data.statistics.length > 0) {
            html += '<div class="col-12 mb-3"><h6>الإحصائيات:</h6><div class="row">';
            data.statistics.forEach(stat => {
                html += `
                    <div class="col-md-3 mb-2">
                        <div class="card bg-light">
                            <div class="card-body text-center">
                                <h5 class="text-primary">${stat.value}</h5>
                                <small>${stat.label}</small>
                            </div>
                        </div>
                    </div>
                `;
            });
            html += '</div></div>';
        }

        // جدول النتائج
        if (data.results && data.results.length > 0) {
            html += '<div class="col-12"><h6>النتائج:</h6>';
            html += '<div class="table-responsive results-table">';
            html += '<table class="table table-striped table-hover">';

            // رؤوس الجدول
            html += '<thead class="table-dark"><tr>';
            const firstRow = data.results[0];
            Object.keys(firstRow).forEach(key => {
                if (key !== 'id') {
                    html += `<th>${key}</th>`;
                }
            });
            html += '</tr></thead>';

            // بيانات الجدول
            html += '<tbody>';
            data.results.forEach(row => {
                html += '<tr>';
                Object.keys(row).forEach(key => {
                    if (key !== 'id') {
                        html += `<td>${row[key] || '-'}</td>`;
                    }
                });
                html += '</tr>';
            });
            html += '</tbody></table></div></div>';
        }

        html += '</div>';
        container.innerHTML = html;
    }

    // تصدير إلى Excel
    window.exportAdvancedToExcel = function() {
        console.log('exportAdvancedToExcel called');

        if (!window.lastReportResults) {
            alert('لا توجد نتائج للتصدير');
            return;
        }

        // جمع الأعمدة المختارة
        const selectedColumns = Array.from(document.querySelectorAll('.column-checkbox:checked')).map(cb => ({
            field: cb.value,
            source: cb.dataset.source,
            alias: cb.nextElementSibling.textContent.trim()
        }));

        const exportData = {
            data_sources: window.selectedDataSources,
            columns: selectedColumns,
            results: window.lastReportResults.results || [],
            statistics: window.lastReportResults.statistics || []
        };

        console.log('Export data:', exportData);

        // إنشاء رابط تحميل مباشر
        const downloadUrl = '/api/advanced-reports/export-excel-test?' +
            'data_sources=' + encodeURIComponent(JSON.stringify(exportData.data_sources)) +
            '&columns=' + encodeURIComponent(JSON.stringify(exportData.columns)) +
            '&results=' + encodeURIComponent(JSON.stringify(exportData.results)) +
            '&statistics=' + encodeURIComponent(JSON.stringify(exportData.statistics));

        // تحميل الملف مباشرة
        const link = document.createElement('a');
        link.href = downloadUrl;
        link.download = 'تقرير_متداخل_' + new Date().toISOString().slice(0,19).replace(/:/g, '-') + '.xlsx';
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);

        // إظهار رسالة نجاح
        setTimeout(() => {
            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    title: 'نجح التصدير',
                    text: 'تم إنشاء ملف Excel بنجاح',
                    icon: 'success',
                    confirmButtonText: 'موافق'
                });
            } else {
                alert('تم تصدير التقرير بنجاح');
            }
        }, 500);
    };
}
</script>
@endpush

@push('styles')
<style>
    .report-card {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        border: none;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    }
    
    .report-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 5px 20px rgba(0,0,0,0.15);
    }
    
    .report-icon {
        height: 80px;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    
    .bg-gradient-success {
        background: linear-gradient(135deg, #56ab2f 0%, #a8e6cf 100%);
    }
    
    .bg-gradient-primary {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    }
    
    .bg-gradient-warning {
        background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
    }
    
    .bg-gradient-info {
        background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
    }

    /* منشئ التقارير المتقدم */
    .bg-gradient {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    }

    #advanced-reports-content {
        max-height: 800px;
        overflow-y: auto;
    }

    .data-source-card {
        transition: all 0.3s ease;
        border: 2px solid transparent;
    }

    .data-source-card:hover {
        border-color: #007bff;
        transform: translateY(-2px);
        box-shadow: 0 4px 15px rgba(0,123,255,0.2);
    }

    .data-source-card.selected {
        border-color: #28a745;
        background-color: #f8fff9;
    }

    .step-header {
        background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
        border-left: 4px solid #007bff;
        padding: 15px;
        margin-bottom: 20px;
        border-radius: 8px;
    }

    .step-number {
        background: #007bff;
        color: white;
        width: 30px;
        height: 30px;
        border-radius: 50%;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-weight: bold;
        margin-left: 10px;
    }

    .column-checkbox:checked + label {
        color: #007bff;
        font-weight: 600;
    }

    .filter-row {
        background: #f8f9fa;
        padding: 15px;
        border-radius: 8px;
        margin-bottom: 10px;
        border: 1px solid #dee2e6;
    }

    .calculation-row {
        background: #fff3cd;
        padding: 15px;
        border-radius: 8px;
        margin-bottom: 10px;
        border: 1px solid #ffeaa7;
    }

    .results-table {
        max-height: 400px;
        overflow-y: auto;
    }

    .export-options {
        background: #e8f5e8;
        padding: 20px;
        border-radius: 8px;
        border: 1px solid #c3e6c3;
    }

    .loading-spinner {
        display: none;
        text-align: center;
        padding: 40px;
    }

    .loading-spinner.show {
        display: block;
    }
</style>
@endpush
