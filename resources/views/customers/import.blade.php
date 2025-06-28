@extends('layouts.app')

@section('title', 'استيراد العملاء - ' . __('messages.system_name'))

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">{{ __('navigation.dashboard') }}</a></li>
    <li class="breadcrumb-item"><a href="{{ route('customers.index') }}">{{ __('messages.customers') }}</a></li>
    <li class="breadcrumb-item active">استيراد العملاء</li>
@endsection

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0">
                <i class="fas fa-file-excel me-2 text-success"></i>
                استيراد العملاء
            </h1>
            <p class="text-muted">رفع العملاء بكميات كبيرة من ملف Excel</p>
        </div>
        <div>
            <a href="{{ route('customers.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left me-2"></i>
                العودة للقائمة
            </a>
        </div>
    </div>

    <!-- إحصائيات سريعة -->
    <div class="row mb-4">
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card bg-primary text-white">
                <div class="card-body text-center">
                    <h4 class="mb-1">{{ number_format($stats['total_customers']) }}</h4>
                    <small>إجمالي العملاء</small>
                    <div class="mt-2">
                        <i class="fas fa-users fa-2x opacity-75"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card bg-success text-white">
                <div class="card-body text-center">
                    <h4 class="mb-1">{{ number_format($stats['active_customers']) }}</h4>
                    <small>عملاء نشطون</small>
                    <div class="mt-2">
                        <i class="fas fa-user-check fa-2x opacity-75"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card bg-warning text-white">
                <div class="card-body text-center">
                    <h4 class="mb-1">{{ number_format($stats['inactive_customers']) }}</h4>
                    <small>عملاء غير نشطين</small>
                    <div class="mt-2">
                        <i class="fas fa-user-times fa-2x opacity-75"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card bg-info text-white">
                <div class="card-body text-center">
                    <h4 class="mb-1">{{ number_format($stats['recent_imports']) }}</h4>
                    <small>مستوردون هذا الأسبوع</small>
                    <div class="mt-2">
                        <i class="fas fa-calendar-week fa-2x opacity-75"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- تعليمات الاستيراد -->
        <div class="col-lg-4 mb-4">
            <div class="card">
                <div class="card-header bg-info text-white">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-info-circle me-2"></i>
                        تعليمات الاستيراد
                    </h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <h6 class="text-primary">الخطوات المطلوبة:</h6>
                        <ol class="small">
                            <li>تحميل النموذج المطلوب</li>
                            <li>ملء البيانات في النموذج</li>
                            <li>حفظ الملف بصيغة CSV</li>
                            <li>رفع الملف للنظام</li>
                        </ol>
                    </div>

                    <div class="mb-3">
                        <h6 class="text-primary">الحقول المطلوبة:</h6>
                        <ul class="small text-danger">
                            <li>اسم الزبون</li>
                            <li>نوع الزبون (retail/wholesale/pharmacy)</li>
                            <li>سقف الدين</li>
                            <li>مدة السداد</li>
                            <li>الحالة (active/inactive/blocked)</li>
                        </ul>
                    </div>

                    <div class="mb-3">
                        <h6 class="text-primary">أنواع الزبائن:</h6>
                        <ul class="small">
                            <li><strong>retail:</strong> تجزئة</li>
                            <li><strong>wholesale:</strong> جملة</li>
                            <li><strong>pharmacy:</strong> صيدلية</li>
                        </ul>
                    </div>

                    <div class="mb-3">
                        <h6 class="text-primary">حالات الزبون:</h6>
                        <ul class="small">
                            <li><strong>active:</strong> نشط</li>
                            <li><strong>inactive:</strong> غير نشط</li>
                            <li><strong>blocked:</strong> محظور</li>
                        </ul>
                    </div>

                    <div class="alert alert-warning small">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        <strong>تنبيه:</strong> تأكد من صحة البيانات قبل الرفع. الأخطاء في التنسيق قد تؤدي إلى فشل الاستيراد.
                    </div>
                </div>
            </div>
        </div>

        <!-- نموذج الاستيراد -->
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-upload me-2"></i>
                        رفع ملف الزبائن
                    </h5>
                </div>
                <div class="card-body">
                    <!-- تحميل النموذج -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <div class="alert alert-info">
                                <h6 class="alert-heading">
                                    <i class="fas fa-download me-2"></i>
                                    تحميل النموذج
                                </h6>
                                <p class="mb-3">قم بتحميل النموذج المطلوب وملء البيانات فيه قبل رفعه للنظام</p>
                                <a href="{{ route('customers.template') }}" class="btn btn-info">
                                    <i class="fas fa-file-csv me-2"></i>
                                    تحميل نموذج CSV
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- نموذج رفع الملف -->
                    <form action="{{ route('customers.import') }}" method="POST" enctype="multipart/form-data" id="importForm">
                        @csrf
                        
                        <div class="row">
                            <div class="col-12">
                                <div class="mb-3">
                                    <label for="file" class="form-label">اختر ملف الزبائن <span class="text-danger">*</span></label>
                                    <input type="file" class="form-control @error('file') is-invalid @enderror" 
                                           id="file" name="file" accept=".csv,.txt,.xlsx,.xls" required>
                                    <div class="form-text">
                                        الصيغ المدعومة: CSV, TXT, XLSX, XLS (الحد الأقصى: 2MB)
                                    </div>
                                    @error('file')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- معاينة الملف -->
                        <div class="row" id="filePreview" style="display: none;">
                            <div class="col-12">
                                <div class="card bg-light">
                                    <div class="card-header">
                                        <h6 class="card-title mb-0">معاينة الملف</h6>
                                    </div>
                                    <div class="card-body">
                                        <div id="fileInfo"></div>
                                        <div id="previewTable" class="table-responsive mt-3"></div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- أزرار الإجراءات -->
                        <div class="row mt-4">
                            <div class="col-12">
                                <div class="d-flex justify-content-end gap-2">
                                    <a href="{{ route('customers.index') }}" class="btn btn-outline-secondary">
                                        <i class="fas fa-times me-2"></i>
                                        إلغاء
                                    </a>
                                    <button type="submit" class="btn btn-primary" id="submitBtn" disabled>
                                        <i class="fas fa-upload me-2"></i>
                                        <span id="submitText">استيراد الزبائن</span>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- إحصائيات الاستيراد السابق -->
            <div class="card mt-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-chart-bar me-2"></i>
                        إحصائيات النظام
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-md-3">
                            <div class="border rounded p-3">
                                <h4 class="text-primary">{{ \App\Models\Customer::count() }}</h4>
                                <small class="text-muted">إجمالي الزبائن</small>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="border rounded p-3">
                                <h4 class="text-success">{{ \App\Models\Customer::where('status', 'active')->count() }}</h4>
                                <small class="text-muted">زبائن نشطون</small>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="border rounded p-3">
                                <h4 class="text-info">{{ \App\Models\Customer::where('customer_type', 'pharmacy')->count() }}</h4>
                                <small class="text-muted">صيدليات</small>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="border rounded p-3">
                                <h4 class="text-warning">{{ \App\Models\Customer::where('customer_type', 'wholesale')->count() }}</h4>
                                <small class="text-muted">جملة</small>
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
document.getElementById('file').addEventListener('change', function(e) {
    const file = e.target.files[0];
    const submitBtn = document.getElementById('submitBtn');
    const filePreview = document.getElementById('filePreview');
    const fileInfo = document.getElementById('fileInfo');
    
    if (file) {
        // عرض معلومات الملف
        const fileSize = (file.size / 1024 / 1024).toFixed(2);
        const fileType = file.name.split('.').pop().toUpperCase();
        
        fileInfo.innerHTML = `
            <div class="row">
                <div class="col-md-4">
                    <strong>اسم الملف:</strong><br>
                    <span class="text-muted">${file.name}</span>
                </div>
                <div class="col-md-4">
                    <strong>حجم الملف:</strong><br>
                    <span class="text-muted">${fileSize} MB</span>
                </div>
                <div class="col-md-4">
                    <strong>نوع الملف:</strong><br>
                    <span class="text-muted">${fileType}</span>
                </div>
            </div>
        `;
        
        // تفعيل زر الرفع
        submitBtn.disabled = false;
        filePreview.style.display = 'block';
        
        // قراءة الملف إذا كان CSV
        if (file.type === 'text/csv' || file.name.endsWith('.csv')) {
            const reader = new FileReader();
            reader.onload = function(e) {
                const csv = e.target.result;
                const lines = csv.split('\n').slice(0, 6); // أول 5 صفوف فقط
                
                if (lines.length > 1) {
                    let tableHtml = '<table class="table table-sm table-bordered"><thead><tr>';
                    const headers = lines[0].split(',');
                    
                    headers.forEach(header => {
                        tableHtml += `<th>${header.replace(/"/g, '')}</th>`;
                    });
                    tableHtml += '</tr></thead><tbody>';
                    
                    for (let i = 1; i < lines.length && i < 6; i++) {
                        if (lines[i].trim()) {
                            tableHtml += '<tr>';
                            const cells = lines[i].split(',');
                            cells.forEach(cell => {
                                tableHtml += `<td>${cell.replace(/"/g, '')}</td>`;
                            });
                            tableHtml += '</tr>';
                        }
                    }
                    
                    tableHtml += '</tbody></table>';
                    document.getElementById('previewTable').innerHTML = tableHtml;
                }
            };
            reader.readAsText(file);
        }
    } else {
        submitBtn.disabled = true;
        filePreview.style.display = 'none';
    }
});

// تحديث نص الزر أثناء الرفع
document.getElementById('importForm').addEventListener('submit', function() {
    const submitBtn = document.getElementById('submitBtn');
    const submitText = document.getElementById('submitText');
    
    submitBtn.disabled = true;
    submitText.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>جاري الاستيراد...';
});
</script>
@endpush
