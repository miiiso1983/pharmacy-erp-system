@extends('super-admin.layout')

@section('title', 'إدارة المستخدمين')

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h1 class="h2 mb-0">
                    <i class="fas fa-users me-3"></i>
                    إدارة المستخدمين
                </h1>
                <p class="text-muted">إدارة جميع مستخدمي النظام</p>
            </div>
            <div>
                <button class="btn btn-primary btn-custom" data-bs-toggle="modal" data-bs-target="#addUserModal">
                    <i class="fas fa-user-plus me-2"></i>
                    إضافة مستخدم جديد
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
                <i class="fas fa-users"></i>
            </div>
            <div class="stat-number text-primary">{{ $users->total() }}</div>
            <div class="stat-label">إجمالي المستخدمين</div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6">
        <div class="stat-card">
            <div class="stat-icon success">
                <i class="fas fa-user-check"></i>
            </div>
            <div class="stat-number text-success">{{ $users->where('is_account_active', true)->count() }}</div>
            <div class="stat-label">المستخدمين النشطين</div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6">
        <div class="stat-card">
            <div class="stat-icon warning">
                <i class="fas fa-user-shield"></i>
            </div>
            <div class="stat-number text-warning">{{ $users->where('user_role', 'admin')->count() }}</div>
            <div class="stat-label">المديرين</div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6">
        <div class="stat-card">
            <div class="stat-icon info">
                <i class="fas fa-user-clock"></i>
            </div>
            <div class="stat-number text-info">{{ $users->where('created_at', '>=', now()->subDays(30))->count() }}</div>
            <div class="stat-label">جدد هذا الشهر</div>
        </div>
    </div>
</div>

<!-- فلاتر البحث -->
<div class="card mb-4">
    <div class="card-body">
        <div class="row">
            <div class="col-md-4">
                <label class="form-label">البحث</label>
                <input type="text" class="form-control" placeholder="البحث بالاسم أو الإيميل..." id="searchUsers">
            </div>
            <div class="col-md-3">
                <label class="form-label">الدور</label>
                <select class="form-select" id="filterRole">
                    <option value="">جميع الأدوار</option>
                    <option value="super_admin">Super Admin</option>
                    <option value="admin">مدير</option>
                    <option value="manager">مدير مخزن</option>
                    <option value="user">مستخدم عادي</option>
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label">الحالة</label>
                <select class="form-select" id="filterStatus">
                    <option value="">جميع الحالات</option>
                    <option value="active">نشط</option>
                    <option value="inactive">غير نشط</option>
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label">&nbsp;</label>
                <button class="btn btn-outline-secondary w-100" onclick="resetFilters()">
                    <i class="fas fa-undo me-1"></i>إعادة تعيين
                </button>
            </div>
        </div>
    </div>
</div>

<!-- جدول المستخدمين -->
<div class="card">
    <div class="card-header">
        <h5 class="card-title mb-0">
            <i class="fas fa-list me-2"></i>
            قائمة المستخدمين
        </h5>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>المستخدم</th>
                        <th>الدور</th>
                        <th>الترخيص</th>
                        <th>تاريخ التسجيل</th>
                        <th>آخر دخول</th>
                        <th>الحالة</th>
                        <th>الإجراءات</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($users as $user)
                        <tr>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="avatar-circle me-3">
                                        <i class="fas fa-user"></i>
                                    </div>
                                    <div>
                                        <strong>{{ $user->name }}</strong><br>
                                        <small class="text-muted">{{ $user->email }}</small>
                                        @if($user->phone)
                                            <br><small class="text-muted">{{ $user->phone }}</small>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span class="badge 
                                    @if($user->user_role === 'super_admin') bg-danger
                                    @elseif($user->user_role === 'admin') bg-primary
                                    @elseif($user->user_role === 'manager') bg-warning
                                    @else bg-secondary
                                    @endif
                                ">
                                    @if($user->user_role === 'super_admin') Super Admin
                                    @elseif($user->user_role === 'admin') مدير
                                    @elseif($user->user_role === 'manager') مدير مخزن
                                    @else مستخدم
                                    @endif
                                </span>
                            </td>
                            <td>
                                @if($user->license)
                                    <small>
                                        <strong>{{ $user->license->client_name }}</strong><br>
                                        <code>{{ $user->license->license_key }}</code>
                                    </small>
                                @else
                                    <span class="text-muted">غير مرتبط</span>
                                @endif
                            </td>
                            <td>{{ $user->created_at->format('Y-m-d') }}</td>
                            <td>
                                @if($user->last_login_at)
                                    {{ $user->last_login_at->diffForHumans() }}
                                @else
                                    <span class="text-muted">لم يسجل دخول</span>
                                @endif
                            </td>
                            <td>
                                @if($user->is_account_active)
                                    <span class="badge bg-success">نشط</span>
                                @else
                                    <span class="badge bg-danger">غير نشط</span>
                                @endif
                            </td>
                            <td>
                                <div class="btn-group" role="group">
                                    <button type="button" class="btn btn-sm btn-outline-primary" 
                                            onclick="viewUser({{ $user->id }})">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    @if($user->user_role !== 'super_admin')
                                        <button type="button" class="btn btn-sm btn-outline-warning"
                                                onclick="editUser({{ $user->id }})">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        @if($user->is_account_active)
                                            <button type="button" class="btn btn-sm btn-outline-secondary"
                                                    onclick="deactivateUser({{ $user->id }})">
                                                <i class="fas fa-user-slash"></i>
                                            </button>
                                        @else
                                            <button type="button" class="btn btn-sm btn-outline-success"
                                                    onclick="activateUser({{ $user->id }})">
                                                <i class="fas fa-user-check"></i>
                                            </button>
                                        @endif
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
            {{ $users->links() }}
        </div>
    </div>
</div>

<!-- Modal إضافة مستخدم جديد -->
<div class="modal fade" id="addUserModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-user-plus me-2"></i>
                    إضافة مستخدم جديد
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="addUserForm">
                    @csrf
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">الاسم الكامل</label>
                            <input type="text" class="form-control" name="name" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">البريد الإلكتروني</label>
                            <input type="email" class="form-control" name="email" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">كلمة المرور</label>
                            <input type="password" class="form-control" name="password" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">رقم الهاتف</label>
                            <input type="text" class="form-control" name="phone">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">الدور</label>
                            <select class="form-select" name="user_role" required>
                                <option value="user">مستخدم عادي</option>
                                <option value="manager">مدير مخزن</option>
                                <option value="admin">مدير</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">الترخيص</label>
                            <select class="form-select" name="license_id">
                                <option value="">بدون ترخيص</option>
                                <!-- سيتم ملؤها ديناميكياً -->
                            </select>
                        </div>
                        <div class="col-12 mb-3">
                            <label class="form-label">العنوان</label>
                            <textarea class="form-control" name="address" rows="2"></textarea>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                <button type="button" class="btn btn-primary" onclick="saveUser()">حفظ المستخدم</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
.avatar-circle {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    background: linear-gradient(135deg, #667eea, #764ba2);
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
}
</style>
@endpush

@push('scripts')
<script>
function viewUser(id) {
    alert('عرض تفاصيل المستخدم رقم: ' + id);
}

function editUser(id) {
    alert('تعديل المستخدم رقم: ' + id);
}

function activateUser(id) {
    confirmAction('هل أنت متأكد من تفعيل هذا المستخدم؟', function() {
        alert('تم تفعيل المستخدم رقم: ' + id);
    });
}

function deactivateUser(id) {
    confirmAction('هل أنت متأكد من إلغاء تفعيل هذا المستخدم؟', function() {
        alert('تم إلغاء تفعيل المستخدم رقم: ' + id);
    });
}

function saveUser() {
    alert('سيتم حفظ المستخدم الجديد');
    const modal = bootstrap.Modal.getInstance(document.getElementById('addUserModal'));
    modal.hide();
}

function resetFilters() {
    document.getElementById('searchUsers').value = '';
    document.getElementById('filterRole').value = '';
    document.getElementById('filterStatus').value = '';
    filterUsers();
}

function filterUsers() {
    const searchTerm = document.getElementById('searchUsers').value.toLowerCase();
    const roleFilter = document.getElementById('filterRole').value;
    const statusFilter = document.getElementById('filterStatus').value;
    const rows = document.querySelectorAll('tbody tr');
    
    rows.forEach(row => {
        const text = row.textContent.toLowerCase();
        const roleMatch = !roleFilter || row.querySelector('.badge').textContent.includes(roleFilter);
        const statusMatch = !statusFilter || 
            (statusFilter === 'active' && row.textContent.includes('نشط')) ||
            (statusFilter === 'inactive' && row.textContent.includes('غير نشط'));
        
        const visible = text.includes(searchTerm) && roleMatch && statusMatch;
        row.style.display = visible ? '' : 'none';
    });
}

// إضافة مستمعين للأحداث
document.getElementById('searchUsers').addEventListener('input', filterUsers);
document.getElementById('filterRole').addEventListener('change', filterUsers);
document.getElementById('filterStatus').addEventListener('change', filterUsers);
</script>
@endpush
