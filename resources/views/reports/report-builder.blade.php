<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>منشئ التقارير المتقدم</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Cairo', sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            direction: rtl;
        }

        .main-container {
            background: white;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            margin: 20px;
            overflow: hidden;
        }

        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 30px;
            text-align: center;
        }

        .header h1 {
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 10px;
        }

        .header p {
            font-size: 1.1rem;
            opacity: 0.9;
        }

        .step-indicator {
            background: #f8f9fa;
            padding: 20px;
            border-bottom: 1px solid #dee2e6;
        }

        .step {
            display: inline-block;
            padding: 10px 20px;
            margin: 0 10px;
            border-radius: 25px;
            background: #e9ecef;
            color: #6c757d;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .step.active {
            background: #28a745;
            color: white;
        }

        .step.completed {
            background: #17a2b8;
            color: white;
        }

        .content-area {
            padding: 30px;
        }

        .data-source-card {
            border: 2px solid #e9ecef;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 15px;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .data-source-card:hover {
            border-color: #667eea;
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.1);
        }

        .data-source-card.selected {
            border-color: #28a745;
            background: #f8fff9;
        }

        .data-source-icon {
            font-size: 2rem;
            color: #667eea;
            margin-bottom: 10px;
        }

        .filter-builder {
            background: #f8f9fa;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 20px;
        }

        .filter-row {
            display: flex;
            gap: 15px;
            margin-bottom: 15px;
            align-items: center;
        }

        .column-selector {
            background: white;
            border: 1px solid #dee2e6;
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 15px;
        }

        .column-item {
            display: flex;
            align-items: center;
            padding: 8px 0;
            border-bottom: 1px solid #f1f3f4;
        }

        .column-item:last-child {
            border-bottom: none;
        }

        .preview-area {
            background: #f8f9fa;
            border-radius: 10px;
            padding: 20px;
            margin-top: 20px;
        }

        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            border-radius: 25px;
            padding: 12px 30px;
            font-weight: 600;
        }

        .btn-success {
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
            border: none;
            border-radius: 25px;
            padding: 12px 30px;
            font-weight: 600;
        }

        .btn-info {
            background: linear-gradient(135deg, #17a2b8 0%, #138496 100%);
            border: none;
            border-radius: 25px;
            padding: 12px 30px;
            font-weight: 600;
        }

        .calculation-badge {
            background: #ffc107;
            color: #212529;
            padding: 4px 8px;
            border-radius: 12px;
            font-size: 0.8rem;
            font-weight: 600;
        }

        .relation-indicator {
            background: #e3f2fd;
            border: 1px solid #bbdefb;
            border-radius: 8px;
            padding: 10px;
            margin: 10px 0;
            font-size: 0.9rem;
        }

        .loading-spinner {
            display: none;
            text-align: center;
            padding: 40px;
        }

        .result-table {
            max-height: 400px;
            overflow-y: auto;
        }

        .export-options {
            background: #e8f5e8;
            border-radius: 8px;
            padding: 20px;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="main-container">
        <!-- رأس الصفحة -->
        <div class="header">
            <h1><i class="fas fa-chart-line"></i> منشئ التقارير المتقدم</h1>
            <p>أنشئ تقارير مخصصة تجمع بين عدة مصادر بيانات مترابطة</p>
        </div>

        <!-- مؤشر الخطوات -->
        <div class="step-indicator">
            <span class="step active" data-step="1">1. اختيار مصادر البيانات</span>
            <span class="step" data-step="2">2. تحديد الأعمدة</span>
            <span class="step" data-step="3">3. إضافة الفلاتر</span>
            <span class="step" data-step="4">4. الحسابات والتجميع</span>
            <span class="step" data-step="5">5. المعاينة والتصدير</span>
        </div>

        <!-- منطقة المحتوى -->
        <div class="content-area">
            <!-- الخطوة 1: اختيار مصادر البيانات -->
            <div id="step-1" class="step-content">
                <h3><i class="fas fa-database"></i> اختر مصادر البيانات</h3>
                <p class="text-muted">يمكنك اختيار عدة مصادر بيانات لإنشاء تقرير متداخل</p>

                <div class="row" id="data-sources-container">
                    <!-- سيتم تحميل مصادر البيانات هنا -->
                </div>

                <div class="relation-indicator" id="relations-info" style="display: none;">
                    <strong>العلاقات المكتشفة:</strong>
                    <div id="relations-list"></div>
                </div>

                <div class="text-end mt-3">
                    <button class="btn btn-primary" onclick="nextStep(2)" id="next-step-1" disabled>
                        التالي <i class="fas fa-arrow-left"></i>
                    </button>
                </div>
            </div>

            <!-- الخطوة 2: تحديد الأعمدة -->
            <div id="step-2" class="step-content" style="display: none;">
                <h3><i class="fas fa-columns"></i> اختر الأعمدة</h3>
                <p class="text-muted">حدد الأعمدة التي تريد عرضها في التقرير</p>

                <div class="row">
                    <div class="col-md-6">
                        <h5>الأعمدة المتاحة</h5>
                        <div id="available-columns" class="column-selector">
                            <!-- سيتم تحميل الأعمدة هنا -->
                        </div>
                    </div>
                    <div class="col-md-6">
                        <h5>الأعمدة المختارة</h5>
                        <div id="selected-columns" class="column-selector">
                            <p class="text-muted text-center">لم يتم اختيار أعمدة بعد</p>
                        </div>
                    </div>
                </div>

                <div class="text-end mt-3">
                    <button class="btn btn-secondary me-2" onclick="prevStep(1)">
                        <i class="fas fa-arrow-right"></i> السابق
                    </button>
                    <button class="btn btn-primary" onclick="nextStep(3)" id="next-step-2" disabled>
                        التالي <i class="fas fa-arrow-left"></i>
                    </button>
                </div>
            </div>

            <!-- الخطوة 3: إضافة الفلاتر -->
            <div id="step-3" class="step-content" style="display: none;">
                <h3><i class="fas fa-filter"></i> إضافة الفلاتر</h3>
                <p class="text-muted">أضف فلاتر لتحديد البيانات المطلوبة</p>

                <div class="filter-builder">
                    <div id="filters-container">
                        <!-- سيتم إضافة الفلاتر هنا -->
                    </div>
                    <button class="btn btn-success btn-sm" onclick="addFilter()">
                        <i class="fas fa-plus"></i> إضافة فلتر
                    </button>
                </div>

                <div class="text-end mt-3">
                    <button class="btn btn-secondary me-2" onclick="prevStep(2)">
                        <i class="fas fa-arrow-right"></i> السابق
                    </button>
                    <button class="btn btn-primary" onclick="nextStep(4)">
                        التالي <i class="fas fa-arrow-left"></i>
                    </button>
                </div>
            </div>

            <!-- الخطوة 4: الحسابات والتجميع -->
            <div id="step-4" class="step-content" style="display: none;">
                <h3><i class="fas fa-calculator"></i> الحسابات والتجميع</h3>
                <p class="text-muted">أضف حسابات وتجميعات للبيانات</p>

                <div class="row">
                    <div class="col-md-6">
                        <h5>الحسابات</h5>
                        <div id="calculations-container">
                            <!-- سيتم إضافة الحسابات هنا -->
                        </div>
                        <button class="btn btn-info btn-sm" onclick="addCalculation()">
                            <i class="fas fa-plus"></i> إضافة حساب
                        </button>
                    </div>
                    <div class="col-md-6">
                        <h5>التجميع</h5>
                        <div id="grouping-container">
                            <!-- سيتم إضافة التجميع هنا -->
                        </div>
                        <button class="btn btn-warning btn-sm" onclick="addGrouping()">
                            <i class="fas fa-plus"></i> إضافة تجميع
                        </button>
                    </div>
                </div>

                <div class="text-end mt-3">
                    <button class="btn btn-secondary me-2" onclick="prevStep(3)">
                        <i class="fas fa-arrow-right"></i> السابق
                    </button>
                    <button class="btn btn-primary" onclick="nextStep(5)">
                        التالي <i class="fas fa-arrow-left"></i>
                    </button>
                </div>
            </div>

            <!-- الخطوة 5: المعاينة والتصدير -->
            <div id="step-5" class="step-content" style="display: none;">
                <h3><i class="fas fa-eye"></i> معاينة التقرير</h3>
                <p class="text-muted">معاينة النتائج وتصدير التقرير</p>

                <div class="mb-3">
                    <label class="form-label">اسم التقرير</label>
                    <input type="text" class="form-control" id="report-name" placeholder="أدخل اسم التقرير">
                </div>

                <div class="mb-3">
                    <label class="form-label">وصف التقرير</label>
                    <textarea class="form-control" id="report-description" rows="3" placeholder="أدخل وصف التقرير"></textarea>
                </div>

                <div class="loading-spinner" id="loading-spinner">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">جاري التحميل...</span>
                    </div>
                    <p class="mt-2">جاري إنشاء التقرير...</p>
                </div>

                <div class="preview-area" id="preview-area" style="display: none;">
                    <h5>معاينة البيانات</h5>
                    <div class="result-table" id="result-table">
                        <!-- سيتم عرض النتائج هنا -->
                    </div>
                </div>

                <div class="export-options" id="export-options" style="display: none;">
                    <h5><i class="fas fa-download"></i> خيارات التصدير</h5>
                    <div class="btn-group" role="group">
                        <button class="btn btn-success" onclick="exportReport('excel')">
                            <i class="fas fa-file-excel"></i> تصدير Excel
                        </button>
                        <button class="btn btn-danger" onclick="exportReport('pdf')">
                            <i class="fas fa-file-pdf"></i> تصدير PDF
                        </button>
                        <button class="btn btn-info" onclick="saveReport()">
                            <i class="fas fa-save"></i> حفظ التقرير
                        </button>
                    </div>
                </div>

                <div class="text-end mt-3">
                    <button class="btn btn-secondary me-2" onclick="prevStep(4)">
                        <i class="fas fa-arrow-right"></i> السابق
                    </button>
                    <button class="btn btn-success" onclick="generateReport()">
                        <i class="fas fa-play"></i> إنشاء التقرير
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script>
        // متغيرات عامة
        let selectedDataSources = [];
        let selectedColumns = [];
        let filters = [];
        let calculations = [];
        let grouping = [];
        let dataSources = {};
        let reportTypes = {};
        let calculationTypes = {};
        let lastReportResults = null; // لحفظ نتائج التقرير الأخيرة

        // تحميل البيانات الأولية
        $(document).ready(function() {
            loadDataSources();
            loadReportTypes();
            loadCalculationTypes();
        });

        // تحميل مصادر البيانات
        function loadDataSources() {
            $.get('/api/advanced-reports/data-sources')
                .done(function(response) {
                    dataSources = response.data;
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
                    reportTypes = response.data;
                })
                .fail(function() {
                    console.log('خطأ في تحميل أنواع التقارير');
                });
        }

        // تحميل أنواع الحسابات
        function loadCalculationTypes() {
            $.get('/api/advanced-reports/calculation-types')
                .done(function(response) {
                    calculationTypes = response.data;
                })
                .fail(function() {
                    console.log('خطأ في تحميل أنواع الحسابات');
                });
        }

        // عرض مصادر البيانات
        function renderDataSources() {
            const container = $('#data-sources-container');
            container.empty();

            Object.keys(dataSources).forEach(function(key) {
                const source = dataSources[key];
                const card = `
                    <div class="col-md-4 mb-3">
                        <div class="data-source-card" data-source="${key}" onclick="toggleDataSource('${key}')">
                            <div class="text-center">
                                <div class="data-source-icon">
                                    <i class="fas fa-${getSourceIcon(key)}"></i>
                                </div>
                                <h5>${source.name}</h5>
                                <p class="text-muted">${source.table}</p>
                                <small class="text-info">${Object.keys(source.fields).length} حقل متاح</small>
                            </div>
                        </div>
                    </div>
                `;
                container.append(card);
            });
        }

        // الحصول على أيقونة المصدر
        function getSourceIcon(source) {
            const icons = {
                'orders': 'shopping-cart',
                'invoices': 'file-invoice',
                'collections': 'money-bill',
                'customers': 'users',
                'items': 'boxes',
                'suppliers': 'truck',
                'warehouses': 'warehouse',
                'employees': 'user-tie',
                'doctors': 'user-md'
            };
            return icons[source] || 'database';
        }

        // تبديل اختيار مصدر البيانات
        function toggleDataSource(source) {
            const card = $(`.data-source-card[data-source="${source}"]`);

            if (selectedDataSources.includes(source)) {
                selectedDataSources = selectedDataSources.filter(s => s !== source);
                card.removeClass('selected');
            } else {
                selectedDataSources.push(source);
                card.addClass('selected');
            }

            updateRelationsInfo();
            $('#next-step-1').prop('disabled', selectedDataSources.length === 0);
        }

        // تحديث معلومات العلاقات
        function updateRelationsInfo() {
            const relationsInfo = $('#relations-info');
            const relationsList = $('#relations-list');

            if (selectedDataSources.length > 1) {
                relationsInfo.show();
                relationsList.empty();

                // عرض العلاقات المكتشفة
                for (let i = 0; i < selectedDataSources.length - 1; i++) {
                    for (let j = i + 1; j < selectedDataSources.length; j++) {
                        const relation = findRelation(selectedDataSources[i], selectedDataSources[j]);
                        if (relation) {
                            relationsList.append(`<div class="badge bg-info me-2">${relation}</div>`);
                        }
                    }
                }
            } else {
                relationsInfo.hide();
            }
        }

        // البحث عن العلاقة بين مصدرين
        function findRelation(source1, source2) {
            const relations = {
                'orders-invoices': 'الطلبات ← الفواتير',
                'orders-customers': 'الطلبات ← العملاء',
                'invoices-collections': 'الفواتير ← التحصيلات',
                'customers-collections': 'العملاء ← التحصيلات',
                'items-suppliers': 'العناصر ← الموردين',
                'employees-departments': 'الموظفين ← الأقسام'
            };

            const key1 = `${source1}-${source2}`;
            const key2 = `${source2}-${source1}`;

            return relations[key1] || relations[key2] || null;
        }

        // الانتقال للخطوة التالية
        function nextStep(step) {
            // إخفاء الخطوة الحالية
            $('.step-content').hide();

            // إظهار الخطوة الجديدة
            $(`#step-${step}`).show();

            // تحديث مؤشر الخطوات
            $('.step').removeClass('active').addClass('completed');
            $(`.step[data-step="${step}"]`).removeClass('completed').addClass('active');

            // تحميل محتوى الخطوة
            if (step === 2) {
                loadAvailableColumns();
            }
        }

        // العودة للخطوة السابقة
        function prevStep(step) {
            $('.step-content').hide();
            $(`#step-${step}`).show();

            $('.step').removeClass('active completed');
            $(`.step[data-step="${step}"]`).addClass('active');

            for (let i = 1; i < step; i++) {
                $(`.step[data-step="${i}"]`).addClass('completed');
            }
        }

        // تحميل الأعمدة المتاحة
        function loadAvailableColumns() {
            const container = $('#available-columns');
            container.empty();

            selectedDataSources.forEach(function(source) {
                const sourceData = dataSources[source];
                const sourceSection = `
                    <div class="mb-3">
                        <h6 class="text-primary">${sourceData.name}</h6>
                        ${Object.keys(sourceData.fields).map(field => `
                            <div class="column-item">
                                <input type="checkbox" class="form-check-input me-2"
                                       id="col-${source}-${field}"
                                       onchange="toggleColumn('${source}', '${field}', '${sourceData.fields[field]}')">
                                <label for="col-${source}-${field}" class="form-check-label">
                                    ${sourceData.fields[field]} <small class="text-muted">(${field})</small>
                                </label>
                            </div>
                        `).join('')}
                    </div>
                `;
                container.append(sourceSection);
            });
        }

        // تبديل اختيار العمود
        function toggleColumn(source, field, label) {
            const columnId = `${source}.${field}`;
            const columnData = { source, field, label, alias: label };

            if ($(`#col-${source}-${field}`).is(':checked')) {
                selectedColumns.push(columnData);
            } else {
                selectedColumns = selectedColumns.filter(col => `${col.source}.${col.field}` !== columnId);
            }

            updateSelectedColumns();
        }

        // تحديث الأعمدة المختارة
        function updateSelectedColumns() {
            const container = $('#selected-columns');

            if (selectedColumns.length === 0) {
                container.html('<p class="text-muted text-center">لم يتم اختيار أعمدة بعد</p>');
                $('#next-step-2').prop('disabled', true);
            } else {
                const columnsHtml = selectedColumns.map((col, index) => `
                    <div class="column-item">
                        <span>${col.label}</span>
                        <button class="btn btn-sm btn-outline-danger" onclick="removeColumn(${index})">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                `).join('');

                container.html(columnsHtml);
                $('#next-step-2').prop('disabled', false);
            }
        }

        // إزالة عمود
        function removeColumn(index) {
            const column = selectedColumns[index];
            $(`#col-${column.source}-${column.field}`).prop('checked', false);
            selectedColumns.splice(index, 1);
            updateSelectedColumns();
        }

        // إضافة فلتر
        function addFilter() {
            const filterId = `filter-${filters.length}`;
            const filterHtml = `
                <div class="filter-row" id="${filterId}">
                    <select class="form-select" style="width: 200px;" onchange="updateFilterField('${filterId}')">
                        <option value="">اختر الحقل</option>
                        ${selectedDataSources.map(source =>
                            Object.keys(dataSources[source].fields).map(field =>
                                `<option value="${source}.${field}">${dataSources[source].fields[field]}</option>`
                            ).join('')
                        ).join('')}
                    </select>
                    <select class="form-select" style="width: 150px;">
                        <option value="=">يساوي</option>
                        <option value="!=">لا يساوي</option>
                        <option value=">">أكبر من</option>
                        <option value="<">أصغر من</option>
                        <option value=">=">أكبر أو يساوي</option>
                        <option value="<=">أصغر أو يساوي</option>
                        <option value="like">يحتوي على</option>
                        <option value="between">بين</option>
                        <option value="date_range">نطاق تاريخ</option>
                    </select>
                    <input type="text" class="form-control" placeholder="القيمة" style="width: 200px;">
                    <button class="btn btn-outline-danger btn-sm" onclick="removeFilter('${filterId}')">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
            `;

            $('#filters-container').append(filterHtml);
            filters.push({ id: filterId });
        }

        // إزالة فلتر
        function removeFilter(filterId) {
            $(`#${filterId}`).remove();
            filters = filters.filter(f => f.id !== filterId);
        }

        // إضافة حساب
        function addCalculation() {
            const calcId = `calc-${calculations.length}`;
            const calcHtml = `
                <div class="mb-3" id="${calcId}">
                    <div class="input-group">
                        <select class="form-select">
                            ${Object.keys(calculationTypes).map(type =>
                                `<option value="${type}">${calculationTypes[type]}</option>`
                            ).join('')}
                        </select>
                        <select class="form-select">
                            <option value="">اختر الحقل</option>
                            ${selectedColumns.filter(col => col.field.includes('amount') || col.field.includes('price') || col.field.includes('quantity')).map(col =>
                                `<option value="${col.source}.${col.field}">${col.label}</option>`
                            ).join('')}
                        </select>
                        <input type="text" class="form-control" placeholder="اسم الحساب">
                        <button class="btn btn-outline-danger" onclick="removeCalculation('${calcId}')">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                </div>
            `;

            $('#calculations-container').append(calcHtml);
            calculations.push({ id: calcId });
        }

        // إزالة حساب
        function removeCalculation(calcId) {
            $(`#${calcId}`).remove();
            calculations = calculations.filter(c => c.id !== calcId);
        }

        // إضافة تجميع
        function addGrouping() {
            const groupId = `group-${grouping.length}`;
            const groupHtml = `
                <div class="mb-3" id="${groupId}">
                    <div class="input-group">
                        <select class="form-select">
                            <option value="">اختر الحقل للتجميع</option>
                            ${selectedColumns.map(col =>
                                `<option value="${col.source}.${col.field}">${col.label}</option>`
                            ).join('')}
                        </select>
                        <button class="btn btn-outline-danger" onclick="removeGrouping('${groupId}')">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                </div>
            `;

            $('#grouping-container').append(groupHtml);
            grouping.push({ id: groupId });
        }

        // إزالة تجميع
        function removeGrouping(groupId) {
            $(`#${groupId}`).remove();
            grouping = grouping.filter(g => g.id !== groupId);
        }

        // إنشاء التقرير
        function generateReport() {
            const reportName = $('#report-name').val();
            const reportDescription = $('#report-description').val();

            if (!reportName) {
                alert('يرجى إدخال اسم التقرير');
                return;
            }

            $('#loading-spinner').show();
            $('#preview-area').hide();
            $('#export-options').hide();

            // جمع بيانات التقرير
            const reportData = {
                name: reportName,
                description: reportDescription,
                data_sources: selectedDataSources,
                columns: selectedColumns,
                filters: collectFilters(),
                calculations: collectCalculations(),
                grouping: collectGrouping(),
                format: 'json'
            };

            // إرسال الطلب
            // استخدام GET مؤقتاً لتجنب مشكلة CSRF
            $.ajax({
                url: '/api/advanced-reports/create-integrated-test?' + $.param(reportData),
                method: 'GET'
            })
            .done(function(response) {
                $('#loading-spinner').hide();
                // حفظ النتائج للتصدير
                lastReportResults = response.data;
                displayResults(response.data);
                $('#export-options').show();
            })
            .fail(function(xhr) {
                $('#loading-spinner').hide();
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
        }

        // جمع الفلاتر
        function collectFilters() {
            const filterData = [];
            $('#filters-container .filter-row').each(function() {
                const row = $(this);
                const field = row.find('select').eq(0).val();
                const operator = row.find('select').eq(1).val();
                const value = row.find('input').val();

                if (field && operator && value) {
                    const [source, fieldName] = field.split('.');
                    filterData.push({
                        field: fieldName,
                        operator: operator,
                        value: value,
                        source: source
                    });
                }
            });
            return filterData;
        }

        // جمع الحسابات
        function collectCalculations() {
            const calcData = [];
            $('#calculations-container > div').each(function() {
                const row = $(this);
                const type = row.find('select').eq(0).val();
                const field = row.find('select').eq(1).val();
                const alias = row.find('input').val();

                if (type && field && alias) {
                    const [source, fieldName] = field.split('.');
                    calcData.push({
                        type: type,
                        field: fieldName,
                        alias: alias,
                        source: source
                    });
                }
            });
            return calcData;
        }

        // جمع التجميع
        function collectGrouping() {
            const groupData = [];
            $('#grouping-container > div').each(function() {
                const field = $(this).find('select').val();
                if (field) {
                    const [source, fieldName] = field.split('.');
                    groupData.push({
                        field: fieldName,
                        source: source
                    });
                }
            });
            return groupData;
        }

        // عرض النتائج
        function displayResults(data) {
            const container = $('#result-table');

            if (!data.data || data.data.length === 0) {
                container.html('<p class="text-center text-muted">لا توجد بيانات لعرضها</p>');
                return;
            }

            // إنشاء الجدول
            const headers = Object.keys(data.data[0]);
            let tableHtml = `
                <table class="table table-striped table-hover">
                    <thead class="table-dark">
                        <tr>
                            ${headers.map(header => `<th>${header}</th>`).join('')}
                        </tr>
                    </thead>
                    <tbody>
                        ${data.data.slice(0, 50).map(row => `
                            <tr>
                                ${headers.map(header => `<td>${row[header] || ''}</td>`).join('')}
                            </tr>
                        `).join('')}
                    </tbody>
                </table>
            `;

            if (data.data.length > 50) {
                tableHtml += `<p class="text-muted text-center">عرض أول 50 سجل من ${data.data.length} سجل</p>`;
            }

            container.html(tableHtml);
            $('#preview-area').show();
        }

        // تصدير التقرير
        function exportReport(format) {
            if (format === 'excel') {
                exportToExcel();
            } else if (format === 'pdf') {
                exportToPDF();
            }
        }

        // تصدير إلى Excel
        function exportToExcel() {
            // التحقق من وجود نتائج
            if (!lastReportResults || !lastReportResults.results || lastReportResults.results.length === 0) {
                Swal.fire({
                    title: 'تنبيه',
                    text: 'يجب إنشاء التقرير أولاً قبل التصدير',
                    icon: 'warning',
                    confirmButtonText: 'موافق'
                });
                return;
            }

            // إظهار مؤشر التحميل
            Swal.fire({
                title: 'جاري التصدير...',
                text: 'يرجى الانتظار',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            const exportData = {
                data_sources: selectedDataSources,
                columns: selectedColumns,
                filters: collectFilters(),
                calculations: collectCalculations(),
                results: lastReportResults.results,
                statistics: lastReportResults.statistics || []
            };

            // إنشاء رابط تحميل مباشر (مؤقت)
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

            Swal.fire({
                title: 'نجح التصدير',
                text: 'تم إنشاء ملف Excel بنجاح',
                icon: 'success',
                confirmButtonText: 'موافق'
            });


        }

        // تصدير إلى PDF (قيد التطوير)
        function exportToPDF() {
            Swal.fire({
                title: 'قيد التطوير',
                text: 'ميزة تصدير PDF قيد التطوير حالياً',
                icon: 'info',
                confirmButtonText: 'موافق'
            });
        }

        // حفظ التقرير
        function saveReport() {
            const reportData = {
                name: $('#report-name').val(),
                description: $('#report-description').val(),
                data_sources: selectedDataSources,
                columns: selectedColumns,
                filters: collectFilters(),
                calculations: collectCalculations(),
                grouping: collectGrouping(),
                save_report: true,
                format: 'json'
            };

            $.ajax({
                url: '/api/advanced-reports/create-integrated',
                method: 'POST',
                data: reportData,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            })
            .done(function(response) {
                alert('تم حفظ التقرير بنجاح');
            })
            .fail(function(xhr) {
                alert('خطأ في حفظ التقرير: ' + (xhr.responseJSON?.message || 'خطأ غير معروف'));
            });
        }
    </script>
</body>
</html>