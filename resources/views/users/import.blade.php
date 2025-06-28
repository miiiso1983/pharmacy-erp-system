@extends('layouts.app')

@section('title', 'استيراد المستخدمين من Excel')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <!-- Header -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h1 class="h3 mb-0">استيراد المستخدمين من Excel</h1>
                    <p class="text-muted">رفع ملف Excel لإضافة مستخدمين متعددين دفعة واحدة</p>
                </div>
                <a href="{{ route('users.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-right me-2"></i>العودة للقائمة
                </a>
            </div>

            <div class="row justify-content-center">
                <div class="col-lg-10">
                    <!-- خطوات العملية -->
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5 class="card-title mb-0">
                                <i class="fas fa-list-ol me-2"></i>خطوات الاستيراد
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-4 text-center mb-3">
                                    <div class="step-icon mb-2">
                                        <i class="fas fa-download fa-2x text-primary"></i>
                                    </div>
                                    <h6>1. تحميل النموذج</h6>
                                    <p class="text-muted small">احفظ نموذج Excel واملأه بالبيانات</p>
                                </div>
                                <div class="col-md-4 text-center mb-3">
                                    <div class="step-icon mb-2">
                                        <i class="fas fa-upload fa-2x text-success"></i>
                                    </div>
                                    <h6>2. رفع الملف</h6>
                                    <p class="text-muted small">اختر ملف Excel المملوء بالبيانات</p>
                                </div>
                                <div class="col-md-4 text-center mb-3">
                                    <div class="step-icon mb-2">
                                        <i class="fas fa-check-circle fa-2x text-info"></i>
                                    </div>
                                    <h6>3. المعاينة والاستيراد</h6>
                                    <p class="text-muted small">راجع البيانات وأكد الاستيراد</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- تحميل النموذج -->
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5 class="card-title mb-0">
                                <i class="fas fa-file-excel me-2"></i>الخطوة 1: تحميل نموذج Excel
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="row align-items-center">
                                <div class="col-md-8">
                                    <h6>نموذج المستخدمين</h6>
                                    <p class="text-muted mb-0">
                                        احفظ هذا النموذج واملأه بمعلومات المستخدمين الجدد. 
                                        النموذج يحتوي على أمثلة وتعليمات مفصلة.
                                    </p>
                                </div>
                                <div class="col-md-4 text-end">
                                    <a href="{{ route('users.template') }}" class="btn btn-success">
                                        <i class="fas fa-download me-2"></i>
                                        تحميل النموذج
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- رفع الملف -->
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5 class="card-title mb-0">
                                <i class="fas fa-upload me-2"></i>الخطوة 2: رفع ملف Excel
                            </h5>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('users.import') }}" method="POST" enctype="multipart/form-data" id="importForm">
                                @csrf
                                
                                <!-- منطقة رفع الملف -->
                                <div class="file-drop-zone mb-4" id="dropZone">
                                    <div class="text-center">
                                        <i class="fas fa-cloud-upload-alt fa-3x text-muted mb-3"></i>
                                        <h5>اسحب وأفلت ملف Excel هنا</h5>
                                        <p class="text-muted">أو انقر لاختيار الملف</p>
                                        <input type="file" class="d-none" id="excel_file" name="excel_file" 
                                               accept=".xlsx,.xls" required>
                                        <button type="button" class="btn btn-outline-primary" onclick="document.getElementById('excel_file').click()">
                                            <i class="fas fa-folder-open me-2"></i>اختيار ملف
                                        </button>
                                    </div>
                                </div>

                                <!-- معلومات الملف المختار -->
                                <div id="fileInfo" class="alert alert-info" style="display: none;">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <i class="fas fa-file-excel me-2"></i>
                                            <span id="fileName"></span>
                                            <small class="text-muted">(<span id="fileSize"></span>)</small>
                                        </div>
                                        <button type="button" class="btn btn-sm btn-outline-danger" onclick="clearFile()">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    </div>
                                </div>

                                <!-- خيارات الاستيراد -->
                                <div class="row mb-4">
                                    <div class="col-md-6">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="skip_duplicates" 
                                                   name="skip_duplicates" value="1" checked>
                                            <label class="form-check-label" for="skip_duplicates">
                                                <strong>تجاهل المستخدمين المكررين</strong>
                                                <br><small class="text-muted">تجاهل المستخدمين الذين لديهم نفس البريد الإلكتروني</small>
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="send_notifications" 
                                                   name="send_notifications" value="1">
                                            <label class="form-check-label" for="send_notifications">
                                                <strong>إرسال إشعارات</strong>
                                                <br><small class="text-muted">إرسال بريد إلكتروني للمستخدمين الجدد</small>
                                            </label>
                                        </div>
                                    </div>
                                </div>

                                <!-- أزرار الإجراءات -->
                                <div class="d-flex justify-content-end gap-2">
                                    <button type="button" class="btn btn-info" id="previewBtn" style="display: none;">
                                        <i class="fas fa-eye me-2"></i>معاينة البيانات
                                    </button>
                                    <button type="submit" class="btn btn-success" id="importBtn" disabled>
                                        <span class="loading-spinner spinner-border spinner-border-sm me-2" role="status"></span>
                                        <i class="fas fa-upload me-2 btn-icon"></i>
                                        <span class="btn-text">استيراد المستخدمين</span>
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- معاينة البيانات -->
                    <div class="card" id="previewSection" style="display: none;">
                        <div class="card-header">
                            <h5 class="card-title mb-0">
                                <i class="fas fa-eye me-2"></i>معاينة البيانات
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-sm table-bordered" id="previewTable">
                                    <thead class="table-light">
                                        <tr id="previewHeaders"></tr>
                                    </thead>
                                    <tbody id="previewBody"></tbody>
                                </table>
                            </div>
                            <div id="previewStats" class="text-muted small mt-2"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal نتائج الاستيراد -->
<div class="modal fade" id="resultsModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-chart-bar me-2"></i>نتائج الاستيراد
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="resultsContent">
                <!-- سيتم ملؤها بـ JavaScript -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إغلاق</button>
                <a href="{{ route('users.index') }}" class="btn btn-primary">
                    <i class="fas fa-list me-2"></i>عرض المستخدمين
                </a>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .file-drop-zone {
        border: 2px dashed #dee2e6;
        border-radius: 8px;
        padding: 3rem 2rem;
        text-align: center;
        transition: all 0.3s ease;
        cursor: pointer;
        background-color: #f8f9fa;
    }

    .file-drop-zone:hover,
    .file-drop-zone.dragover {
        border-color: #28a745;
        background-color: #f8fff9;
        transform: translateY(-2px);
    }

    .step-icon {
        height: 60px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .loading-spinner {
        display: none;
    }

    .loading .loading-spinner {
        display: inline-block;
    }

    .loading .btn-icon {
        display: none;
    }

    .preview-table {
        max-height: 400px;
        overflow-y: auto;
    }

    .card {
        box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
        border: 1px solid rgba(0, 0, 0, 0.125);
    }
</style>
@endpush

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const fileInput = document.getElementById('excel_file');
    const dropZone = document.getElementById('dropZone');
    const fileInfo = document.getElementById('fileInfo');
    const previewBtn = document.getElementById('previewBtn');
    const importBtn = document.getElementById('importBtn');
    const previewSection = document.getElementById('previewSection');
    const importForm = document.getElementById('importForm');
    
    let excelData = null;

    // معالجة السحب والإفلات
    dropZone.addEventListener('dragover', function(e) {
        e.preventDefault();
        this.classList.add('dragover');
    });

    dropZone.addEventListener('dragleave', function(e) {
        e.preventDefault();
        this.classList.remove('dragover');
    });

    dropZone.addEventListener('drop', function(e) {
        e.preventDefault();
        this.classList.remove('dragover');
        const files = e.dataTransfer.files;
        if (files.length > 0) {
            fileInput.files = files;
            handleFileSelect(files[0]);
        }
    });

    dropZone.addEventListener('click', function() {
        fileInput.click();
    });

    fileInput.addEventListener('change', function(e) {
        if (e.target.files.length > 0) {
            handleFileSelect(e.target.files[0]);
        }
    });

    function handleFileSelect(file) {
        // عرض معلومات الملف
        document.getElementById('fileName').textContent = file.name;
        document.getElementById('fileSize').textContent = formatFileSize(file.size);
        fileInfo.style.display = 'block';

        // التحقق من الملف ومعاينته
        validateAndPreviewFile(file);
    }

    function validateAndPreviewFile(file) {
        // التحقق من نوع الملف
        const allowedTypes = [
            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'application/vnd.ms-excel'
        ];
        
        if (!allowedTypes.includes(file.type)) {
            showAlert('يجب أن يكون الملف بصيغة Excel (.xlsx أو .xls)', 'error');
            return;
        }

        // التحقق من حجم الملف (5MB)
        if (file.size > 5 * 1024 * 1024) {
            showAlert('حجم الملف كبير جداً. الحد الأقصى 5MB', 'error');
            return;
        }

        // قراءة الملف
        const reader = new FileReader();
        reader.onload = function(e) {
            try {
                const data = new Uint8Array(e.target.result);
                const workbook = XLSX.read(data, { type: 'array' });
                const firstSheet = workbook.Sheets[workbook.SheetNames[0]];
                const jsonData = XLSX.utils.sheet_to_json(firstSheet, { header: 1 });
                
                if (jsonData.length < 2) {
                    showAlert('الملف فارغ أو لا يحتوي على بيانات كافية', 'error');
                    return;
                }

                excelData = jsonData;
                previewBtn.style.display = 'inline-block';
                importBtn.disabled = false;
                showAlert('تم تحميل الملف بنجاح. يمكنك الآن معاينة البيانات أو استيرادها مباشرة', 'success');
                
            } catch (error) {
                showAlert('خطأ في قراءة الملف. تأكد من أن الملف صحيح', 'error');
                console.error('Error reading file:', error);
            }
        };
        
        reader.readAsArrayBuffer(file);
    }

    // معاينة البيانات
    previewBtn.addEventListener('click', function() {
        if (!excelData) return;

        const headers = excelData[0];
        const rows = excelData.slice(1, 11); // أول 10 صفوف للمعاينة

        // إنشاء headers
        const headerRow = document.getElementById('previewHeaders');
        headerRow.innerHTML = headers.map(header => `<th>${header || 'عمود فارغ'}</th>`).join('');

        // إنشاء البيانات
        const tbody = document.getElementById('previewBody');
        tbody.innerHTML = rows.map(row => 
            `<tr>${headers.map((_, index) => `<td>${row[index] || ''}</td>`).join('')}</tr>`
        ).join('');

        // إحصائيات
        const stats = document.getElementById('previewStats');
        stats.innerHTML = `
            إجمالي الصفوف: ${excelData.length - 1} | 
            الأعمدة: ${headers.length} | 
            معاينة أول 10 صفوف
        `;

        previewSection.style.display = 'block';
        previewSection.scrollIntoView({ behavior: 'smooth' });
    });

    // معالجة إرسال النموذج
    importForm.addEventListener('submit', function(e) {
        e.preventDefault();
        
        if (!fileInput.files[0]) {
            showAlert('يرجى اختيار ملف Excel أولاً', 'error');
            return;
        }

        // إظهار حالة التحميل
        importBtn.classList.add('loading');
        importBtn.disabled = true;

        // إرسال النموذج
        const formData = new FormData(this);
        
        fetch(this.action, {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showResults(data);
            } else {
                showAlert(data.message || 'حدث خطأ أثناء رفع الملف', 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showAlert('حدث خطأ أثناء رفع الملف', 'error');
        })
        .finally(() => {
            importBtn.classList.remove('loading');
            importBtn.disabled = false;
        });
    });

    function clearFile() {
        fileInput.value = '';
        fileInfo.style.display = 'none';
        previewBtn.style.display = 'none';
        importBtn.disabled = true;
        previewSection.style.display = 'none';
        excelData = null;
    }

    function formatFileSize(bytes) {
        if (bytes === 0) return '0 Bytes';
        const k = 1024;
        const sizes = ['Bytes', 'KB', 'MB', 'GB'];
        const i = Math.floor(Math.log(bytes) / Math.log(k));
        return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
    }

    function showAlert(message, type) {
        const alertClass = type === 'success' ? 'alert-success' : 'alert-danger';
        const alertHtml = `
            <div class="alert ${alertClass} alert-dismissible fade show" role="alert">
                ${message}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        `;
        
        // إضافة التنبيه في أعلى الصفحة
        const container = document.querySelector('.container-fluid');
        const existingAlert = container.querySelector('.alert');
        if (existingAlert) {
            existingAlert.remove();
        }
        container.insertAdjacentHTML('afterbegin', alertHtml);
        
        // التمرير لأعلى
        window.scrollTo({ top: 0, behavior: 'smooth' });
    }

    function showResults(data) {
        const resultsContent = document.getElementById('resultsContent');
        
        let html = `
            <div class="row text-center mb-4">
                <div class="col-md-4">
                    <div class="card bg-success text-white">
                        <div class="card-body">
                            <h3>${data.details.imported}</h3>
                            <p class="mb-0">تم استيرادهم</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card bg-warning text-white">
                        <div class="card-body">
                            <h3>${data.details.skipped}</h3>
                            <p class="mb-0">تم تجاهلهم</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card bg-danger text-white">
                        <div class="card-body">
                            <h3>${data.details.errors.length}</h3>
                            <p class="mb-0">أخطاء</p>
                        </div>
                    </div>
                </div>
            </div>
        `;

        if (data.details.errors.length > 0) {
            html += `
                <div class="alert alert-warning">
                    <h6>الأخطاء التي حدثت:</h6>
                    <ul class="mb-0">
                        ${data.details.errors.map(error => `<li>${error}</li>`).join('')}
                    </ul>
                </div>
            `;
        }

        resultsContent.innerHTML = html;
        
        const resultsModal = new bootstrap.Modal(document.getElementById('resultsModal'));
        resultsModal.show();
    }
});
</script>
@endpush
