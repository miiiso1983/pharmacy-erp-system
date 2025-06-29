@extends('super-admin.layout')

@section('title', 'عزل البيانات')

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h1 class="h2 mb-0">
                    <i class="fas fa-shield-alt me-3"></i>
                    عزل البيانات بين التراخيص
                </h1>
                <p class="text-muted">مراقبة وإدارة عزل البيانات لضمان الأمان</p>
            </div>
            <div>
                <button class="btn btn-primary btn-custom" onclick="validateIsolation()">
                    <i class="fas fa-search me-2"></i>
                    فحص العزل
                </button>
            </div>
        </div>
    </div>
</div>

<!-- إحصائيات سريعة -->
<div class="row mb-4">
    <div class="col-lg-3 col-md-6">
        <div class="stat-card">
            <div class="stat-icon 
                @if($report['system_health'] === 'good') success
                @elseif($report['system_health'] === 'warning') warning
                @else danger
                @endif
            ">
                <i class="fas fa-shield-check"></i>
            </div>
            <div class="stat-number 
                @if($report['system_health'] === 'good') text-success
                @elseif($report['system_health'] === 'warning') text-warning
                @else text-danger
                @endif
            ">
                @if($report['system_health'] === 'good') ممتاز
                @elseif($report['system_health'] === 'warning') تحذير
                @else خطر
                @endif
            </div>
            <div class="stat-label">حالة عزل البيانات</div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6">
        <div class="stat-card">
            <div class="stat-icon primary">
                <i class="fas fa-certificate"></i>
            </div>
            <div class="stat-number text-primary">{{ $report['total_licenses'] }}</div>
            <div class="stat-label">إجمالي التراخيص</div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6">
        <div class="stat-card">
            <div class="stat-icon info">
                <i class="fas fa-exclamation-triangle"></i>
            </div>
            <div class="stat-number text-info">
                {{ collect($report['licenses_data'])->sum(function($license) { return count($license['issues']); }) }}
            </div>
            <div class="stat-label">المشاكل المكتشفة</div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6">
        <div class="stat-card">
            <div class="stat-icon secondary">
                <i class="fas fa-clock"></i>
            </div>
            <div class="stat-number text-secondary">{{ \Carbon\Carbon::parse($report['timestamp'])->diffForHumans() }}</div>
            <div class="stat-label">آخر فحص</div>
        </div>
    </div>
</div>

<!-- أدوات الإدارة -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-tools me-2"></i>
                    أدوات إدارة عزل البيانات
                </h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3 mb-3">
                        <div class="d-grid">
                            <button class="btn btn-outline-primary" onclick="validateIsolation()">
                                <i class="fas fa-search me-2"></i>
                                فحص العزل
                            </button>
                        </div>
                    </div>
                    <div class="col-md-3 mb-3">
                        <div class="d-grid">
                            <button class="btn btn-outline-warning" onclick="fixIsolationIssues()">
                                <i class="fas fa-wrench me-2"></i>
                                إصلاح المشاكل
                            </button>
                        </div>
                    </div>
                    <div class="col-md-3 mb-3">
                        <div class="d-grid">
                            <button class="btn btn-outline-danger" onclick="cleanupLeakedData()">
                                <i class="fas fa-broom me-2"></i>
                                تنظيف البيانات
                            </button>
                        </div>
                    </div>
                    <div class="col-md-3 mb-3">
                        <div class="d-grid">
                            <button class="btn btn-outline-info" onclick="testIsolation()">
                                <i class="fas fa-vial me-2"></i>
                                اختبار العزل
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- تفاصيل التراخيص -->
<div class="card">
    <div class="card-header">
        <h5 class="card-title mb-0">
            <i class="fas fa-list me-2"></i>
            تفاصيل عزل البيانات للتراخيص
        </h5>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>مفتاح الترخيص</th>
                        <th>العميل</th>
                        <th>حالة العزل</th>
                        <th>عدد المستخدمين</th>
                        <th>عدد العملاء</th>
                        <th>عدد المنتجات</th>
                        <th>عدد المخازن</th>
                        <th>المشاكل</th>
                        <th>الإجراءات</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($report['licenses_data'] as $license)
                        <tr>
                            <td><code>{{ $license['license_key'] }}</code></td>
                            <td>{{ $license['client_name'] }}</td>
                            <td>
                                @if($license['isolation_status'] === 'good')
                                    <span class="badge bg-success">ممتاز</span>
                                @elseif($license['isolation_status'] === 'warning')
                                    <span class="badge bg-warning">تحذير</span>
                                @else
                                    <span class="badge bg-danger">خطر</span>
                                @endif
                            </td>
                            <td>{{ $license['data_counts']['users'] ?? 0 }}</td>
                            <td>{{ $license['data_counts']['customers'] ?? 0 }}</td>
                            <td>{{ $license['data_counts']['items'] ?? 0 }}</td>
                            <td>{{ $license['data_counts']['warehouses'] ?? 0 }}</td>
                            <td>
                                @if(count($license['issues']) > 0)
                                    <span class="badge bg-danger">{{ count($license['issues']) }}</span>
                                @else
                                    <span class="badge bg-success">0</span>
                                @endif
                            </td>
                            <td>
                                <div class="btn-group" role="group">
                                    <button type="button" class="btn btn-sm btn-outline-primary" 
                                            onclick="viewLicenseDetails({{ $license['license_id'] }})">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    @if(count($license['issues']) > 0)
                                        <button type="button" class="btn btn-sm btn-outline-warning"
                                                onclick="fixLicenseIssues({{ $license['license_id'] }})">
                                            <i class="fas fa-wrench"></i>
                                        </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal تفاصيل الترخيص -->
<div class="modal fade" id="licenseDetailsModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-info-circle me-2"></i>
                    تفاصيل عزل البيانات
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="licenseDetailsContent">
                <!-- سيتم ملؤها ديناميكياً -->
            </div>
        </div>
    </div>
</div>

<!-- Modal اختبار العزل -->
<div class="modal fade" id="testIsolationModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-vial me-2"></i>
                    اختبار عزل البيانات
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="testIsolationForm">
                    <div class="mb-3">
                        <label class="form-label">الترخيص الأول</label>
                        <select class="form-select" name="license_1" required>
                            <option value="">اختر الترخيص الأول</option>
                            @foreach($report['licenses_data'] as $license)
                                <option value="{{ $license['license_id'] }}">{{ $license['license_key'] }} - {{ $license['client_name'] }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">الترخيص الثاني</label>
                        <select class="form-select" name="license_2" required>
                            <option value="">اختر الترخيص الثاني</option>
                            @foreach($report['licenses_data'] as $license)
                                <option value="{{ $license['license_id'] }}">{{ $license['license_key'] }} - {{ $license['client_name'] }}</option>
                            @endforeach
                        </select>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                <button type="button" class="btn btn-primary" onclick="executeIsolationTest()">بدء الاختبار</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function validateIsolation() {
    showLoading('جاري فحص عزل البيانات...');

    fetch('{{ route("super-admin.data-isolation.validate") }}')
        .then(response => response.json())
        .then(data => {
            hideLoading();
            if (data.status === 'success') {
                showSuccess('تم فحص عزل البيانات بنجاح');
                location.reload();
            } else {
                showError('حدث خطأ أثناء الفحص');
            }
        })
        .catch(error => {
            hideLoading();
            showError('حدث خطأ في الاتصال');
        });
}

function fixIsolationIssues() {
    confirmAction('هل أنت متأكد من إصلاح جميع مشاكل عزل البيانات؟', function() {
        showLoading('جاري إصلاح المشاكل...');
        
        fetch('{{ route("super-admin.data-isolation.fix") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        })
        .then(response => response.json())
        .then(data => {
            hideLoading();
            if (data.status === 'success') {
                showSuccess(data.message);
                location.reload();
            } else {
                showError('حدث خطأ أثناء الإصلاح');
            }
        })
        .catch(error => {
            hideLoading();
            showError('حدث خطأ في الاتصال');
        });
    });
}

function cleanupLeakedData() {
    confirmAction('هل أنت متأكد من تنظيف البيانات المتسربة؟ هذا الإجراء لا يمكن التراجع عنه!', function() {
        showLoading('جاري تنظيف البيانات...');
        
        fetch('{{ route("super-admin.data-isolation.cleanup") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        })
        .then(response => response.json())
        .then(data => {
            hideLoading();
            if (data.status === 'success') {
                showSuccess(data.message);
                location.reload();
            } else {
                showError('حدث خطأ أثناء التنظيف');
            }
        })
        .catch(error => {
            hideLoading();
            showError('حدث خطأ في الاتصال');
        });
    });
}

function testIsolation() {
    const modal = new bootstrap.Modal(document.getElementById('testIsolationModal'));
    modal.show();
}

function executeIsolationTest() {
    const form = document.getElementById('testIsolationForm');
    const formData = new FormData(form);
    
    if (!formData.get('license_1') || !formData.get('license_2')) {
        showError('يرجى اختيار ترخيصين مختلفين');
        return;
    }
    
    if (formData.get('license_1') === formData.get('license_2')) {
        showError('يجب اختيار ترخيصين مختلفين');
        return;
    }
    
    showLoading('جاري اختبار العزل...');
    
    fetch('{{ route("super-admin.data-isolation.test") }}', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        hideLoading();
        if (data.status === 'success') {
            const modal = bootstrap.Modal.getInstance(document.getElementById('testIsolationModal'));
            modal.hide();
            
            const result = data.data.isolation_test === 'passed' ? 'نجح' : 'فشل';
            const icon = data.data.isolation_test === 'passed' ? 'success' : 'error';
            
            Swal.fire({
                title: 'نتيجة اختبار العزل',
                text: `الاختبار: ${result}`,
                icon: icon,
                confirmButtonText: 'موافق'
            });
        } else {
            showError('حدث خطأ أثناء الاختبار');
        }
    })
    .catch(error => {
        hideLoading();
        showError('حدث خطأ في الاتصال');
    });
}

function viewLicenseDetails(licenseId) {
    // عرض تفاصيل الترخيص
    showSuccess('سيتم عرض تفاصيل الترخيص رقم: ' + licenseId);
}

function fixLicenseIssues(licenseId) {
    confirmAction('هل أنت متأكد من إصلاح مشاكل هذا الترخيص؟', function() {
        showLoading('جاري إصلاح المشاكل...');
        
        fetch('{{ route("super-admin.data-isolation.fix") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ license_id: licenseId })
        })
        .then(response => response.json())
        .then(data => {
            hideLoading();
            if (data.status === 'success') {
                showSuccess('تم إصلاح مشاكل الترخيص');
                location.reload();
            } else {
                showError('حدث خطأ أثناء الإصلاح');
            }
        })
        .catch(error => {
            hideLoading();
            showError('حدث خطأ في الاتصال');
        });
    });
}

function showLoading(message) {
    Swal.fire({
        title: message,
        allowOutsideClick: false,
        didOpen: () => {
            Swal.showLoading();
        }
    });
}

function hideLoading() {
    Swal.close();
}
</script>
@endpush
