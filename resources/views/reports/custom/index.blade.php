@extends('layouts.app')

@section('title', 'التقارير المخصصة - نظام إدارة المذاخر')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">الرئيسية</a></li>
    <li class="breadcrumb-item"><a href="{{ route('reports.index') }}">التقارير</a></li>
    <li class="breadcrumb-item active">التقارير المخصصة</li>
@endsection

@section('content')
<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>
                <i class="fas fa-chart-pie me-2"></i>
                التقارير المخصصة
            </h2>
            <div>
                <a href="{{ route('reports.custom.builder') }}" class="btn btn-primary">
                    <i class="fas fa-plus me-2"></i>
                    إنشاء تقرير جديد
                </a>
                <a href="{{ route('reports.index') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left me-2"></i>
                    العودة للتقارير
                </a>
            </div>
        </div>
    </div>
</div>

<!-- مقدمة التقارير المخصصة -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card bg-gradient-primary text-white">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-md-8">
                        <h4 class="mb-2">منشئ التقارير المخصصة</h4>
                        <p class="mb-0">أنشئ تقارير مخصصة حسب احتياجاتك باستخدام أداة إنشاء التقارير المتقدمة</p>
                    </div>
                    <div class="col-md-4 text-end">
                        <i class="fas fa-tools fa-4x opacity-75"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- إحصائيات سريعة -->
<div class="row mb-4">
    <div class="col-lg-3 col-md-6 mb-3">
        <div class="card bg-success text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h4 class="mb-0">{{ $savedReports->count() }}</h4>
                        <p class="mb-0">التقارير المحفوظة</p>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-save fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6 mb-3">
        <div class="card bg-info text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h4 class="mb-0">4</h4>
                        <p class="mb-0">مصادر البيانات</p>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-database fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6 mb-3">
        <div class="card bg-warning text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h4 class="mb-0">∞</h4>
                        <p class="mb-0">إمكانيات لا محدودة</p>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-infinity fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6 mb-3">
        <div class="card bg-danger text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h4 class="mb-0">100%</h4>
                        <p class="mb-0">مرونة كاملة</p>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-cogs fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- التقارير المحفوظة -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">
                    <i class="fas fa-bookmark me-2"></i>
                    التقارير المحفوظة
                </h5>
                <div>
                    <button class="btn btn-outline-primary btn-sm" onclick="refreshReports()">
                        <i class="fas fa-sync-alt me-2"></i>
                        تحديث
                    </button>
                </div>
            </div>
            <div class="card-body">
                @if($savedReports->count() > 0)
                    <div class="row">
                        @foreach($savedReports as $report)
                            <div class="col-lg-4 col-md-6 mb-3">
                                <div class="card report-item h-100">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between align-items-start mb-3">
                                            <h6 class="card-title mb-0">{{ $report->name }}</h6>
                                            <div class="dropdown">
                                                <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                                    <i class="fas fa-ellipsis-v"></i>
                                                </button>
                                                <ul class="dropdown-menu">
                                                    <li>
                                                        <a class="dropdown-item" href="{{ route('reports.custom.show', $report->id) }}">
                                                            <i class="fas fa-eye me-2"></i>
                                                            عرض التقرير
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <button class="dropdown-item" onclick="editReport({{ $report->id }})">
                                                            <i class="fas fa-edit me-2"></i>
                                                            تعديل
                                                        </button>
                                                    </li>
                                                    <li>
                                                        <button class="dropdown-item" onclick="duplicateReport({{ $report->id }})">
                                                            <i class="fas fa-copy me-2"></i>
                                                            تكرار
                                                        </button>
                                                    </li>
                                                    <li><hr class="dropdown-divider"></li>
                                                    <li>
                                                        <button class="dropdown-item text-danger" onclick="deleteReport({{ $report->id }})">
                                                            <i class="fas fa-trash me-2"></i>
                                                            حذف
                                                        </button>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                        
                                        @if($report->description)
                                            <p class="card-text text-muted small">{{ $report->description }}</p>
                                        @endif
                                        
                                        <div class="report-meta">
                                            <small class="text-muted">
                                                <i class="fas fa-calendar me-1"></i>
                                                {{ $report->created_at->format('Y/m/d H:i') }}
                                            </small>
                                        </div>
                                        
                                        <div class="mt-3">
                                            <a href="{{ route('reports.custom.show', $report->id) }}" class="btn btn-primary btn-sm">
                                                <i class="fas fa-play me-2"></i>
                                                تشغيل التقرير
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-5">
                        <i class="fas fa-chart-pie fa-4x text-muted mb-3"></i>
                        <h5 class="text-muted">لا توجد تقارير محفوظة</h5>
                        <p class="text-muted">ابدأ بإنشاء تقريرك الأول باستخدام منشئ التقارير</p>
                        <a href="{{ route('reports.custom.builder') }}" class="btn btn-primary">
                            <i class="fas fa-plus me-2"></i>
                            إنشاء تقرير جديد
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- دليل الاستخدام -->
<div class="row mt-4">
    <div class="col-12">
        <div class="card border-info">
            <div class="card-header bg-info text-white">
                <h5 class="mb-0">
                    <i class="fas fa-question-circle me-2"></i>
                    كيفية استخدام التقارير المخصصة
                </h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <h6 class="text-primary">خطوات إنشاء تقرير:</h6>
                        <ol class="list-unstyled">
                            <li class="mb-2">
                                <span class="badge bg-primary me-2">1</span>
                                اختر مصدر البيانات (الطلبات، الفواتير، الأصناف، العملاء)
                            </li>
                            <li class="mb-2">
                                <span class="badge bg-primary me-2">2</span>
                                حدد الحقول التي تريد عرضها في التقرير
                            </li>
                            <li class="mb-2">
                                <span class="badge bg-primary me-2">3</span>
                                أضف فلاتر لتخصيص البيانات
                            </li>
                            <li class="mb-2">
                                <span class="badge bg-primary me-2">4</span>
                                اختر طريقة الترتيب والتجميع
                            </li>
                            <li class="mb-2">
                                <span class="badge bg-primary me-2">5</span>
                                احفظ التقرير لاستخدامه لاحقاً
                            </li>
                        </ol>
                    </div>
                    <div class="col-md-6">
                        <h6 class="text-success">مميزات التقارير المخصصة:</h6>
                        <ul class="list-unstyled">
                            <li class="mb-2">
                                <i class="fas fa-check text-success me-2"></i>
                                مرونة كاملة في اختيار البيانات
                            </li>
                            <li class="mb-2">
                                <i class="fas fa-check text-success me-2"></i>
                                فلاتر متقدمة ومتنوعة
                            </li>
                            <li class="mb-2">
                                <i class="fas fa-check text-success me-2"></i>
                                حفظ التقارير لاستخدامها مرة أخرى
                            </li>
                            <li class="mb-2">
                                <i class="fas fa-check text-success me-2"></i>
                                تصدير النتائج بصيغ مختلفة
                            </li>
                            <li class="mb-2">
                                <i class="fas fa-check text-success me-2"></i>
                                واجهة سهلة الاستخدام
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
function refreshReports() {
    location.reload();
}

function editReport(reportId) {
    // سيتم تطوير وظيفة التعديل لاحقاً
    alert('وظيفة التعديل قيد التطوير');
}

function duplicateReport(reportId) {
    if (confirm('هل تريد تكرار هذا التقرير؟')) {
        // سيتم تطوير وظيفة التكرار لاحقاً
        alert('وظيفة التكرار قيد التطوير');
    }
}

function deleteReport(reportId) {
    if (confirm('هل أنت متأكد من حذف هذا التقرير؟ لا يمكن التراجع عن هذا الإجراء.')) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = `/reports/custom/${reportId}`;
        
        const csrfToken = document.createElement('input');
        csrfToken.type = 'hidden';
        csrfToken.name = '_token';
        csrfToken.value = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        form.appendChild(csrfToken);
        
        const methodField = document.createElement('input');
        methodField.type = 'hidden';
        methodField.name = '_method';
        methodField.value = 'DELETE';
        form.appendChild(methodField);
        
        document.body.appendChild(form);
        form.submit();
    }
}
</script>
@endpush

@push('styles')
<style>
.report-item {
    transition: transform 0.3s ease, box-shadow 0.3s ease;
    border: 1px solid #e3e6f0;
}

.report-item:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
}

.bg-gradient-primary {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
}

.report-meta {
    border-top: 1px solid #e3e6f0;
    padding-top: 10px;
    margin-top: 10px;
}

.card-title {
    color: #2c3e50;
    font-weight: 600;
}

.badge {
    font-size: 0.75rem;
}

@media (max-width: 768px) {
    .card-body {
        padding: 1rem;
    }
    
    .btn-group-sm .btn {
        padding: 0.25rem 0.5rem;
        font-size: 0.875rem;
    }
}
</style>
@endpush
