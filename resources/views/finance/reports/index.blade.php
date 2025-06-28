@extends('layouts.app')

@section('title', 'التقارير المالية - ' . __('messages.system_name'))

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">{{ __('navigation.dashboard') }}</a></li>
    <li class="breadcrumb-item"><a href="{{ route('finance.dashboard') }}">النظام المالي</a></li>
    <li class="breadcrumb-item active">التقارير المالية</li>
@endsection

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0">
                <i class="fas fa-chart-bar me-2"></i>
                التقارير المالية
            </h1>
            <p class="text-muted">تقارير شاملة للوضع المالي والأداء المحاسبي</p>
        </div>
        <div>
            @if($currentPeriod)
                <span class="badge bg-primary fs-6">
                    الفترة الحالية: {{ $currentPeriod->period_name }}
                </span>
            @else
                <span class="badge bg-warning fs-6">
                    لا توجد فترة مالية نشطة
                </span>
            @endif
        </div>
    </div>

    <!-- التقارير الأساسية -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-file-alt me-2"></i>
                        التقارير الأساسية
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <!-- ميزان المراجعة -->
                        <div class="col-lg-4 col-md-6 mb-4">
                            <div class="card h-100 border-primary">
                                <div class="card-body text-center">
                                    <div class="mb-3">
                                        <i class="fas fa-balance-scale fa-3x text-primary"></i>
                                    </div>
                                    <h5 class="card-title">ميزان المراجعة</h5>
                                    <p class="card-text">عرض أرصدة جميع الحسابات في فترة محددة</p>
                                    <a href="{{ route('finance.reports.trial-balance') }}" class="btn btn-primary">
                                        <i class="fas fa-eye me-2"></i>
                                        عرض التقرير
                                    </a>
                                </div>
                            </div>
                        </div>

                        <!-- الميزانية العمومية -->
                        <div class="col-lg-4 col-md-6 mb-4">
                            <div class="card h-100 border-success">
                                <div class="card-body text-center">
                                    <div class="mb-3">
                                        <i class="fas fa-chart-pie fa-3x text-success"></i>
                                    </div>
                                    <h5 class="card-title">الميزانية العمومية</h5>
                                    <p class="card-text">عرض الأصول والخصوم وحقوق الملكية</p>
                                    <a href="{{ route('finance.reports.balance-sheet') }}" class="btn btn-success">
                                        <i class="fas fa-eye me-2"></i>
                                        عرض التقرير
                                    </a>
                                </div>
                            </div>
                        </div>

                        <!-- قائمة الدخل -->
                        <div class="col-lg-4 col-md-6 mb-4">
                            <div class="card h-100 border-info">
                                <div class="card-body text-center">
                                    <div class="mb-3">
                                        <i class="fas fa-chart-line fa-3x text-info"></i>
                                    </div>
                                    <h5 class="card-title">قائمة الدخل</h5>
                                    <p class="card-text">عرض الإيرادات والمصروفات والأرباح</p>
                                    <a href="{{ route('finance.reports.income-statement') }}" class="btn btn-info">
                                        <i class="fas fa-eye me-2"></i>
                                        عرض التقرير
                                    </a>
                                </div>
                            </div>
                        </div>

                        <!-- قائمة التدفقات النقدية -->
                        <div class="col-lg-4 col-md-6 mb-4">
                            <div class="card h-100 border-warning">
                                <div class="card-body text-center">
                                    <div class="mb-3">
                                        <i class="fas fa-money-bill-wave fa-3x text-warning"></i>
                                    </div>
                                    <h5 class="card-title">التدفقات النقدية</h5>
                                    <p class="card-text">عرض حركة النقدية الداخلة والخارجة</p>
                                    <a href="{{ route('finance.reports.cash-flow') }}" class="btn btn-warning">
                                        <i class="fas fa-eye me-2"></i>
                                        عرض التقرير
                                    </a>
                                </div>
                            </div>
                        </div>

                        <!-- كشف حساب -->
                        <div class="col-lg-4 col-md-6 mb-4">
                            <div class="card h-100 border-danger">
                                <div class="card-body text-center">
                                    <div class="mb-3">
                                        <i class="fas fa-list-alt fa-3x text-danger"></i>
                                    </div>
                                    <h5 class="card-title">كشف حساب</h5>
                                    <p class="card-text">عرض تفاصيل حركة حساب معين</p>
                                    <a href="{{ route('finance.reports.account-ledger') }}" class="btn btn-danger">
                                        <i class="fas fa-eye me-2"></i>
                                        عرض التقرير
                                    </a>
                                </div>
                            </div>
                        </div>

                        <!-- تقرير مخصص -->
                        <div class="col-lg-4 col-md-6 mb-4">
                            <div class="card h-100 border-secondary">
                                <div class="card-body text-center">
                                    <div class="mb-3">
                                        <i class="fas fa-cogs fa-3x text-secondary"></i>
                                    </div>
                                    <h5 class="card-title">تقرير مخصص</h5>
                                    <p class="card-text">إنشاء تقرير مخصص حسب المعايير المحددة</p>
                                    <button class="btn btn-secondary" onclick="showCustomReportModal()">
                                        <i class="fas fa-plus me-2"></i>
                                        إنشاء تقرير
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- إعدادات التقارير -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-cog me-2"></i>
                        إعدادات التقارير
                    </h5>
                </div>
                <div class="card-body">
                    <form id="reportSettingsForm">
                        <div class="row">
                            <div class="col-md-3 mb-3">
                                <label for="start_date" class="form-label">من تاريخ</label>
                                <input type="date" class="form-control" id="start_date" name="start_date" 
                                       value="{{ now()->startOfYear()->format('Y-m-d') }}">
                            </div>
                            <div class="col-md-3 mb-3">
                                <label for="end_date" class="form-label">إلى تاريخ</label>
                                <input type="date" class="form-control" id="end_date" name="end_date" 
                                       value="{{ now()->format('Y-m-d') }}">
                            </div>
                            <div class="col-md-3 mb-3">
                                <label for="account_type" class="form-label">نوع الحساب</label>
                                <select class="form-select" id="account_type" name="account_type">
                                    <option value="">جميع الأنواع</option>
                                    <option value="asset">الأصول</option>
                                    <option value="liability">الخصوم</option>
                                    <option value="equity">حقوق الملكية</option>
                                    <option value="revenue">الإيرادات</option>
                                    <option value="expense">المصروفات</option>
                                </select>
                            </div>
                            <div class="col-md-3 mb-3">
                                <label for="format" class="form-label">تنسيق التقرير</label>
                                <select class="form-select" id="format" name="format">
                                    <option value="html">عرض على الشاشة</option>
                                    <option value="pdf">PDF</option>
                                    <option value="excel">Excel</option>
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="include_zero_balances" name="include_zero_balances">
                                    <label class="form-check-label" for="include_zero_balances">
                                        تضمين الحسابات ذات الرصيد صفر
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="show_details" name="show_details">
                                    <label class="form-check-label" for="show_details">
                                        عرض التفاصيل
                                    </label>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal للتقرير المخصص -->
<div class="modal fade" id="customReportModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">إنشاء تقرير مخصص</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="customReportForm">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="report_name" class="form-label">اسم التقرير</label>
                            <input type="text" class="form-control" id="report_name" name="report_name" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="report_type" class="form-label">نوع التقرير</label>
                            <select class="form-select" id="report_type" name="report_type" required>
                                <option value="">اختر نوع التقرير</option>
                                <option value="accounts">تقرير الحسابات</option>
                                <option value="transactions">تقرير المعاملات</option>
                                <option value="balances">تقرير الأرصدة</option>
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="custom_start_date" class="form-label">من تاريخ</label>
                            <input type="date" class="form-control" id="custom_start_date" name="start_date" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="custom_end_date" class="form-label">إلى تاريخ</label>
                            <input type="date" class="form-control" id="custom_end_date" name="end_date" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="accounts" class="form-label">الحسابات المحددة</label>
                        <select class="form-select" id="accounts" name="accounts[]" multiple>
                            <!-- سيتم ملؤها بـ JavaScript -->
                        </select>
                        <small class="form-text text-muted">اتركها فارغة لتشمل جميع الحسابات</small>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                <button type="button" class="btn btn-primary" onclick="generateCustomReport()">
                    <i class="fas fa-chart-bar me-2"></i>
                    إنشاء التقرير
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function showCustomReportModal() {
    const modal = new bootstrap.Modal(document.getElementById('customReportModal'));
    modal.show();
}

function generateCustomReport() {
    const form = document.getElementById('customReportForm');
    const formData = new FormData(form);
    
    // هنا يمكن إضافة منطق إنشاء التقرير المخصص
    alert('سيتم إضافة هذه الميزة قريباً');
}

// تطبيق إعدادات التقارير على جميع الروابط
document.addEventListener('DOMContentLoaded', function() {
    const reportLinks = document.querySelectorAll('a[href*="/reports/"]');
    
    reportLinks.forEach(link => {
        link.addEventListener('click', function(e) {
            const form = document.getElementById('reportSettingsForm');
            const formData = new FormData(form);
            const params = new URLSearchParams(formData);
            
            // إضافة المعاملات إلى الرابط
            const url = new URL(this.href);
            params.forEach((value, key) => {
                if (value) {
                    url.searchParams.set(key, value);
                }
            });
            
            this.href = url.toString();
        });
    });
});
</script>
@endpush

@push('styles')
<style>
.card.h-100 {
    transition: transform 0.2s;
}

.card.h-100:hover {
    transform: translateY(-5px);
    box-shadow: 0 4px 8px rgba(0,0,0,0.1);
}

.border-primary {
    border-color: #007bff !important;
}

.border-success {
    border-color: #28a745 !important;
}

.border-info {
    border-color: #17a2b8 !important;
}

.border-warning {
    border-color: #ffc107 !important;
}

.border-danger {
    border-color: #dc3545 !important;
}

.border-secondary {
    border-color: #6c757d !important;
}
</style>
@endpush
