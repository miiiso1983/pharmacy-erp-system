@extends('super-admin.layout')

@section('title', 'إدارة التراخيص')

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h1 class="h2 mb-0">
                    <i class="fas fa-certificate me-3"></i>
                    إدارة التراخيص
                </h1>
                <p class="text-muted">إدارة جميع تراخيص النظام</p>
            </div>
            <div>
                <button class="btn btn-primary btn-custom" data-bs-toggle="modal" data-bs-target="#addLicenseModal">
                    <i class="fas fa-plus me-2"></i>
                    إضافة ترخيص جديد
                </button>
            </div>
        </div>
    </div>
</div>

<!-- إحصائيات سريعة -->
<div class="row mb-4">
    <div class="col-lg-3 col-md-6">
        <div class="stat-card">
            <div class="stat-icon primary">
                <i class="fas fa-certificate"></i>
            </div>
            <div class="stat-number text-primary">{{ $licenses->total() }}</div>
            <div class="stat-label">إجمالي التراخيص</div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6">
        <div class="stat-card">
            <div class="stat-icon success">
                <i class="fas fa-check-circle"></i>
            </div>
            <div class="stat-number text-success">{{ $licenses->where('is_active', true)->count() }}</div>
            <div class="stat-label">التراخيص النشطة</div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6">
        <div class="stat-card">
            <div class="stat-icon warning">
                <i class="fas fa-exclamation-triangle"></i>
            </div>
            <div class="stat-number text-warning">{{ $licenses->where('end_date', '<', now())->count() }}</div>
            <div class="stat-label">التراخيص المنتهية</div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6">
        <div class="stat-card">
            <div class="stat-icon info">
                <i class="fas fa-dollar-sign"></i>
            </div>
            <div class="stat-number text-info">${{ number_format($licenses->sum('license_cost'), 0) }}</div>
            <div class="stat-label">إجمالي الإيرادات</div>
        </div>
    </div>
</div>

<!-- جدول التراخيص -->
<div class="card">
    <div class="card-header">
        <div class="row align-items-center">
            <div class="col">
                <h5 class="card-title mb-0">
                    <i class="fas fa-list me-2"></i>
                    قائمة التراخيص
                </h5>
            </div>
            <div class="col-auto">
                <div class="input-group">
                    <input type="text" class="form-control" placeholder="البحث في التراخيص..." id="searchLicenses">
                    <button class="btn btn-outline-secondary" type="button">
                        <i class="fas fa-search"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>مفتاح الترخيص</th>
                        <th>العميل</th>
                        <th>النوع</th>
                        <th>تاريخ البداية</th>
                        <th>تاريخ الانتهاء</th>
                        <th>الحالة</th>
                        <th>الاستخدام</th>
                        <th>الإجراءات</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($licenses as $license)
                        <tr>
                            <td>
                                <code class="fw-bold">{{ $license->license_key }}</code>
                            </td>
                            <td>
                                <div>
                                    <strong>{{ $license->client_name }}</strong><br>
                                    <small class="text-muted">{{ $license->client_email }}</small>
                                </div>
                            </td>
                            <td>
                                <span class="badge 
                                    @if($license->license_type === 'premium') bg-primary
                                    @elseif($license->license_type === 'full') bg-success
                                    @elseif($license->license_type === 'basic') bg-secondary
                                    @else bg-info
                                    @endif
                                ">
                                    {{ $license->license_type }}
                                </span>
                            </td>
                            <td>{{ $license->start_date->format('Y-m-d') }}</td>
                            <td>
                                <span class="@if($license->end_date < now()) text-danger @endif">
                                    {{ $license->end_date->format('Y-m-d') }}
                                </span>
                            </td>
                            <td>
                                @if($license->is_active)
                                    @if($license->end_date < now())
                                        <span class="badge bg-warning">منتهي</span>
                                    @else
                                        <span class="badge bg-success">نشط</span>
                                    @endif
                                @else
                                    <span class="badge bg-danger">غير نشط</span>
                                @endif
                            </td>
                            <td>
                                @if($license->usage)
                                    <small>
                                        المستخدمين: {{ $license->usage->current_users }}/{{ $license->max_users }}<br>
                                        المخازن: {{ $license->usage->current_warehouses }}/{{ $license->max_warehouses }}
                                    </small>
                                @else
                                    <span class="text-muted">لا توجد بيانات</span>
                                @endif
                            </td>
                            <td>
                                <div class="btn-group" role="group">
                                    <button type="button" class="btn btn-sm btn-outline-primary" 
                                            onclick="viewLicense({{ $license->id }})">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    <button type="button" class="btn btn-sm btn-outline-warning"
                                            onclick="editLicense({{ $license->id }})">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    @if($license->is_active)
                                        <button type="button" class="btn btn-sm btn-outline-secondary"
                                                onclick="deactivateLicense({{ $license->id }})">
                                            <i class="fas fa-pause"></i>
                                        </button>
                                    @else
                                        <button type="button" class="btn btn-sm btn-outline-success"
                                                onclick="activateLicense({{ $license->id }})">
                                            <i class="fas fa-play"></i>
                                        </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        <div class="d-flex justify-content-center mt-4">
            {{ $licenses->links() }}
        </div>
    </div>
</div>

<!-- Modal إضافة ترخيص جديد -->
<div class="modal fade" id="addLicenseModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-plus me-2"></i>
                    إضافة ترخيص جديد
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="addLicenseForm">
                    @csrf
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">مفتاح الترخيص</label>
                            <input type="text" class="form-control" name="license_key" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">نوع الترخيص</label>
                            <select class="form-select" name="license_type" required>
                                <option value="basic">أساسي</option>
                                <option value="full">كامل</option>
                                <option value="premium">مميز</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">اسم العميل</label>
                            <input type="text" class="form-control" name="client_name" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">بريد العميل</label>
                            <input type="email" class="form-control" name="client_email" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">تاريخ البداية</label>
                            <input type="date" class="form-control" name="start_date" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">تاريخ الانتهاء</label>
                            <input type="date" class="form-control" name="end_date" required>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">عدد المستخدمين</label>
                            <input type="number" class="form-control" name="max_users" value="10" required>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">عدد المخازن</label>
                            <input type="number" class="form-control" name="max_warehouses" value="3" required>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">تكلفة الترخيص</label>
                            <input type="number" class="form-control" name="license_cost" step="0.01" required>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                <button type="button" class="btn btn-primary" onclick="saveLicense()">حفظ الترخيص</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function viewLicense(id) {
    // عرض تفاصيل الترخيص
    alert('عرض تفاصيل الترخيص رقم: ' + id);
}

function editLicense(id) {
    // تعديل الترخيص
    alert('تعديل الترخيص رقم: ' + id);
}

function activateLicense(id) {
    if(confirm('هل أنت متأكد من تفعيل هذا الترخيص؟')) {
        // تفعيل الترخيص
        alert('تم تفعيل الترخيص رقم: ' + id);
    }
}

function deactivateLicense(id) {
    if(confirm('هل أنت متأكد من إلغاء تفعيل هذا الترخيص؟')) {
        // إلغاء تفعيل الترخيص
        alert('تم إلغاء تفعيل الترخيص رقم: ' + id);
    }
}

function saveLicense() {
    // حفظ الترخيص الجديد
    const form = document.getElementById('addLicenseForm');
    const formData = new FormData(form);
    
    // هنا يمكن إضافة AJAX request لحفظ البيانات
    alert('سيتم حفظ الترخيص الجديد');
    
    // إغلاق المودال
    const modal = bootstrap.Modal.getInstance(document.getElementById('addLicenseModal'));
    modal.hide();
}

// البحث في التراخيص
document.getElementById('searchLicenses').addEventListener('input', function() {
    const searchTerm = this.value.toLowerCase();
    const rows = document.querySelectorAll('tbody tr');
    
    rows.forEach(row => {
        const text = row.textContent.toLowerCase();
        row.style.display = text.includes(searchTerm) ? '' : 'none';
    });
});
</script>
@endpush
