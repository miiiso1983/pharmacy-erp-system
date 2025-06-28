<!-- منشئ التقارير المتقدم - نسخة مدمجة -->
<div class="advanced-report-builder">
    <!-- الخطوة 1: اختيار مصادر البيانات -->
    <div class="step-section mb-4">
        <div class="step-header">
            <div class="d-flex align-items-center">
                <span class="step-number">1</span>
                <h5 class="mb-0">اختيار مصادر البيانات</h5>
            </div>
            <p class="mb-0 mt-2 text-muted">اختر مصادر البيانات التي تريد دمجها في التقرير</p>
        </div>
        
        <div class="row" id="data-sources-list">
            <!-- سيتم تحميل مصادر البيانات هنا -->
        </div>
    </div>

    <!-- الخطوة 2: اختيار الأعمدة -->
    <div class="step-section mb-4" id="columns-section" style="display: none;">
        <div class="step-header">
            <div class="d-flex align-items-center">
                <span class="step-number">2</span>
                <h5 class="mb-0">اختيار الأعمدة</h5>
            </div>
            <p class="mb-0 mt-2 text-muted">اختر الحقول التي تريد عرضها في التقرير</p>
        </div>
        
        <div id="columns-list">
            <!-- سيتم تحميل الأعمدة هنا -->
        </div>
    </div>

    <!-- الخطوة 3: الفلاتر (اختياري) -->
    <div class="step-section mb-4" id="filters-section" style="display: none;">
        <div class="step-header">
            <div class="d-flex align-items-center">
                <span class="step-number">3</span>
                <h5 class="mb-0">الفلاتر</h5>
                <button type="button" class="btn btn-sm btn-outline-primary ms-auto" onclick="addFilter()">
                    <i class="fas fa-plus me-1"></i>
                    إضافة فلتر
                </button>
            </div>
            <p class="mb-0 mt-2 text-muted">أضف فلاتر لتحديد البيانات المطلوبة</p>
        </div>
        
        <div id="filters-list">
            <!-- سيتم إضافة الفلاتر هنا -->
        </div>
    </div>

    <!-- الخطوة 4: الحسابات (اختياري) -->
    <div class="step-section mb-4" id="calculations-section" style="display: none;">
        <div class="step-header">
            <div class="d-flex align-items-center">
                <span class="step-number">4</span>
                <h5 class="mb-0">الحسابات والإحصائيات</h5>
                <button type="button" class="btn btn-sm btn-outline-success ms-auto" onclick="addCalculation()">
                    <i class="fas fa-calculator me-1"></i>
                    إضافة حساب
                </button>
            </div>
            <p class="mb-0 mt-2 text-muted">أضف حسابات مثل المجموع والمتوسط والعدد</p>
        </div>
        
        <div id="calculations-list">
            <!-- سيتم إضافة الحسابات هنا -->
        </div>
    </div>

    <!-- الخطوة 5: إنشاء التقرير -->
    <div class="step-section mb-4" id="generate-section" style="display: none;">
        <div class="step-header">
            <div class="d-flex align-items-center">
                <span class="step-number">5</span>
                <h5 class="mb-0">إنشاء التقرير</h5>
            </div>
        </div>
        
        <div class="text-center">
            <button type="button" class="btn btn-primary btn-lg" id="generate-advanced-report-btn">
                <i class="fas fa-chart-bar me-2"></i>
                إنشاء التقرير المتداخل
            </button>
        </div>
    </div>

    <!-- منطقة النتائج -->
    <div id="results-section" style="display: none;">
        <div class="step-header">
            <div class="d-flex align-items-center">
                <span class="step-number">
                    <i class="fas fa-check"></i>
                </span>
                <h5 class="mb-0">نتائج التقرير</h5>
            </div>
        </div>
        
        <div id="results-content">
            <!-- سيتم عرض النتائج هنا -->
        </div>
        
        <!-- خيارات التصدير -->
        <div id="export-options" class="export-options mt-4" style="display: none;">
            <h6 class="mb-3">
                <i class="fas fa-download me-2"></i>
                خيارات التصدير
            </h6>
            <div class="d-flex gap-2 flex-wrap">
                <button type="button" class="btn btn-success" id="export-excel-btn">
                    <i class="fas fa-file-excel me-2"></i>
                    تصدير إلى Excel
                </button>
                <button type="button" class="btn btn-info" id="export-pdf-btn">
                    <i class="fas fa-file-pdf me-2"></i>
                    تصدير إلى PDF
                </button>
                <button type="button" class="btn btn-secondary" id="save-report-btn">
                    <i class="fas fa-save me-2"></i>
                    حفظ التقرير
                </button>
            </div>
        </div>
    </div>

    <!-- مؤشر التحميل -->
    <div id="loading-spinner" class="loading-spinner">
        <div class="spinner-border text-primary" role="status">
            <span class="visually-hidden">جاري إنشاء التقرير...</span>
        </div>
        <p class="mt-3">جاري إنشاء التقرير المتداخل...</p>
    </div>
</div>

<script>
// متغيرات عامة لمنشئ التقارير
let selectedDataSources = [];
let selectedColumns = [];
let reportFilters = [];
let reportCalculations = [];
let lastReportResults = null;

// تحديث المصادر المختارة
function updateSelectedSources() {
    selectedDataSources = Array.from(document.querySelectorAll('#data-sources-list input:checked')).map(cb => cb.value);
    
    // إظهار/إخفاء الأقسام التالية
    const columnsSection = document.getElementById('columns-section');
    const filtersSection = document.getElementById('filters-section');
    const calculationsSection = document.getElementById('calculations-section');
    const generateSection = document.getElementById('generate-section');
    
    if (selectedDataSources.length > 0) {
        columnsSection.style.display = 'block';
        filtersSection.style.display = 'block';
        calculationsSection.style.display = 'block';
        generateSection.style.display = 'block';
        updateColumnsSection(selectedDataSources);
    } else {
        columnsSection.style.display = 'none';
        filtersSection.style.display = 'none';
        calculationsSection.style.display = 'none';
        generateSection.style.display = 'none';
    }
}

// تحديث قسم الأعمدة
function updateColumnsSection(selectedSources) {
    const container = document.getElementById('columns-list');
    if (!container || !window.dataSources) return;
    
    container.innerHTML = '';
    
    selectedSources.forEach(sourceKey => {
        const source = window.dataSources[sourceKey];
        if (!source) return;
        
        const sourceDiv = document.createElement('div');
        sourceDiv.className = 'mb-4';
        sourceDiv.innerHTML = `
            <h6 class="text-primary border-bottom pb-2">
                <i class="fas fa-table me-2"></i>
                ${source.name}
            </h6>
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

// إضافة فلتر جديد
function addFilter() {
    const container = document.getElementById('filters-list');
    const filterIndex = container.children.length;
    
    const filterDiv = document.createElement('div');
    filterDiv.className = 'filter-row';
    filterDiv.innerHTML = `
        <div class="row align-items-center">
            <div class="col-md-3">
                <label class="form-label">الحقل</label>
                <select class="form-select" name="filter_field_${filterIndex}">
                    <option value="">اختر الحقل</option>
                    ${getAvailableFields()}
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label">المشغل</label>
                <select class="form-select" name="filter_operator_${filterIndex}">
                    <option value="=">=</option>
                    <option value="!=">!=</option>
                    <option value=">">></option>
                    <option value="<"><</option>
                    <option value=">=">>=</option>
                    <option value="<="><=</option>
                    <option value="like">يحتوي على</option>
                </select>
            </div>
            <div class="col-md-5">
                <label class="form-label">القيمة</label>
                <input type="text" class="form-control" name="filter_value_${filterIndex}" placeholder="أدخل القيمة">
            </div>
            <div class="col-md-2">
                <label class="form-label">&nbsp;</label>
                <button type="button" class="btn btn-danger btn-sm d-block" onclick="removeFilter(this)">
                    <i class="fas fa-trash"></i>
                </button>
            </div>
        </div>
    `;
    
    container.appendChild(filterDiv);
}

// إزالة فلتر
function removeFilter(button) {
    button.closest('.filter-row').remove();
}

// إضافة حساب جديد
function addCalculation() {
    const container = document.getElementById('calculations-list');
    const calcIndex = container.children.length;
    
    const calcDiv = document.createElement('div');
    calcDiv.className = 'calculation-row';
    calcDiv.innerHTML = `
        <div class="row align-items-center">
            <div class="col-md-3">
                <label class="form-label">نوع الحساب</label>
                <select class="form-select" name="calc_type_${calcIndex}">
                    ${getCalculationTypes()}
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label">الحقل</label>
                <select class="form-select" name="calc_field_${calcIndex}">
                    <option value="">اختر الحقل</option>
                    ${getNumericFields()}
                </select>
            </div>
            <div class="col-md-4">
                <label class="form-label">اسم الحساب</label>
                <input type="text" class="form-control" name="calc_alias_${calcIndex}" placeholder="مثل: إجمالي المبيعات">
            </div>
            <div class="col-md-2">
                <label class="form-label">&nbsp;</label>
                <button type="button" class="btn btn-danger btn-sm d-block" onclick="removeCalculation(this)">
                    <i class="fas fa-trash"></i>
                </button>
            </div>
        </div>
    `;
    
    container.appendChild(calcDiv);
}

// إزالة حساب
function removeCalculation(button) {
    button.closest('.calculation-row').remove();
}

// الحصول على الحقول المتاحة
function getAvailableFields() {
    let options = '';
    selectedDataSources.forEach(sourceKey => {
        const source = window.dataSources[sourceKey];
        if (source) {
            Object.keys(source.fields).forEach(fieldKey => {
                options += `<option value="${sourceKey}.${fieldKey}">${source.name} - ${source.fields[fieldKey]}</option>`;
            });
        }
    });
    return options;
}

// الحصول على أنواع الحسابات
function getCalculationTypes() {
    let options = '';
    if (window.calculationTypes) {
        Object.keys(window.calculationTypes).forEach(key => {
            options += `<option value="${key}">${window.calculationTypes[key]}</option>`;
        });
    }
    return options;
}

// الحصول على الحقول الرقمية
function getNumericFields() {
    let options = '';
    selectedDataSources.forEach(sourceKey => {
        const source = window.dataSources[sourceKey];
        if (source) {
            // افتراض أن الحقول التي تحتوي على "amount" أو "price" أو "total" رقمية
            Object.keys(source.fields).forEach(fieldKey => {
                if (fieldKey.includes('amount') || fieldKey.includes('price') || fieldKey.includes('total') || fieldKey.includes('id')) {
                    options += `<option value="${sourceKey}.${fieldKey}">${source.name} - ${source.fields[fieldKey]}</option>`;
                }
            });
        }
    });
    return options;
}
</script>
