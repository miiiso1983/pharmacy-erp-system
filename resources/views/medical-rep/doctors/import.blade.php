@extends('layouts.app')

@section('title', 'استيراد الأطباء - ' . __('messages.system_name'))

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">{{ __('navigation.dashboard') }}</a></li>
    <li class="breadcrumb-item"><a href="{{ route('medical-rep.dashboard') }}">المندوبين العلميين</a></li>
    <li class="breadcrumb-item"><a href="{{ route('medical-rep.doctors.index') }}">الأطباء</a></li>
    <li class="breadcrumb-item active">استيراد الأطباء</li>
@endsection

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0">
                <i class="fas fa-file-upload me-2"></i>
                استيراد الأطباء
            </h1>
            <p class="text-muted">رفع ملف Excel/CSV لإضافة أطباء جدد بشكل مجمع</p>
        </div>
        <div>
            <a href="{{ route('medical-rep.doctors.index') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left me-2"></i>
                العودة لقائمة الأطباء
            </a>
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
                            <li>ملء بيانات الأطباء في النموذج</li>
                            <li>حفظ الملف بصيغة CSV</li>
                            <li>رفع الملف للنظام</li>
                        </ol>
                    </div>

                    <div class="mb-3">
                        <h6 class="text-primary">الحقول المطلوبة:</h6>
                        <ul class="small text-danger">
                            <li>اسم الطبيب</li>
                            <li>التخصص</li>
                            <li>رقم المندوب العلمي</li>
                            <li>تكرار الزيارة</li>
                            <li>الحالة</li>
                        </ul>
                    </div>

                    <div class="mb-3">
                        <h6 class="text-primary">تكرار الزيارة:</h6>
                        <ul class="small">
                            <li><strong>weekly:</strong> أسبوعياً</li>
                            <li><strong>monthly:</strong> شهرياً</li>
                            <li><strong>quarterly:</strong> ربع سنوي</li>
                        </ul>
                    </div>

                    <div class="mb-3">
                        <h6 class="text-primary">حالات الطبيب:</h6>
                        <ul class="small">
                            <li><strong>active:</strong> نشط</li>
                            <li><strong>inactive:</strong> غير نشط</li>
                        </ul>
                    </div>

                    <div class="mb-3">
                        <h6 class="text-primary">المندوبين المتاحين:</h6>
                        <ul class="small">
                            @foreach($representatives as $rep)
                            <li><strong>{{ $rep->id }}:</strong> {{ $rep->name }} ({{ $rep->territory }})</li>
                            @endforeach
                        </ul>
                    </div>

                    <div class="alert alert-warning small">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        <strong>تنبيه:</strong> تأكد من صحة أرقام المندوبين العلميين في الملف.
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
                        رفع ملف الأطباء
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
                                <p class="mb-3">قم بتحميل النموذج المطلوب وملء بيانات الأطباء فيه قبل رفعه للنظام</p>
                                <a href="{{ route('medical-rep.doctors.template') }}" class="btn btn-info">
                                    <i class="fas fa-file-csv me-2"></i>
                                    تحميل نموذج الأطباء CSV
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- نموذج رفع الملف -->
                    <form action="{{ route('medical-rep.doctors.import') }}" method="POST" enctype="multipart/form-data" id="importForm">
                        @csrf
                        
                        <div class="row">
                            <div class="col-12">
                                <div class="mb-3">
                                    <label for="file" class="form-label">اختر ملف الأطباء <span class="text-danger">*</span></label>
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
                                    <a href="{{ route('medical-rep.doctors.index') }}" class="btn btn-outline-secondary">
                                        <i class="fas fa-times me-2"></i>
                                        إلغاء
                                    </a>
                                    <button type="submit" class="btn btn-primary" id="submitBtn" disabled>
                                        <i class="fas fa-upload me-2"></i>
                                        <span id="submitText">استيراد الأطباء</span>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- إحصائيات النظام -->
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
                                <h4 class="text-primary">{{ \App\Models\Doctor::count() }}</h4>
                                <small class="text-muted">إجمالي الأطباء</small>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="border rounded p-3">
                                <h4 class="text-success">{{ \App\Models\Doctor::where('status', 'active')->count() }}</h4>
                                <small class="text-muted">أطباء نشطون</small>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="border rounded p-3">
                                <h4 class="text-info">{{ \App\Models\MedicalRepresentative::where('status', 'active')->count() }}</h4>
                                <small class="text-muted">مندوبين نشطين</small>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="border rounded p-3">
                                <h4 class="text-warning">{{ \App\Models\Doctor::distinct('specialization')->count('specialization') }}</h4>
                                <small class="text-muted">التخصصات</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- التخصصات الشائعة -->
            <div class="card mt-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-stethoscope me-2"></i>
                        التخصصات الشائعة
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        @php
                            $specializations = \App\Models\Doctor::select('specialization', \DB::raw('count(*) as count'))
                                ->groupBy('specialization')
                                ->orderByDesc('count')
                                ->take(8)
                                ->get();
                        @endphp
                        @foreach($specializations as $spec)
                        <div class="col-md-3 mb-2">
                            <div class="d-flex justify-content-between align-items-center p-2 bg-light rounded">
                                <span class="small">{{ $spec->specialization }}</span>
                                <span class="badge bg-primary">{{ $spec->count }}</span>
                            </div>
                        </div>
                        @endforeach
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
