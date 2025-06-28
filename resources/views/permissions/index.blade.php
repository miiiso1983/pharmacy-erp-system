@extends('layouts.app')

@section('title', __('messages.permissions') . ' - ' . __('messages.system_name'))

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">{{ __('navigation.dashboard') }}</a></li>
    <li class="breadcrumb-item active">إدارة الصلاحيات</li>
@endsection

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <!-- Header -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h1 class="h3 mb-0">
                        <i class="fas fa-shield-alt me-2"></i>
                        إدارة الصلاحيات والأدوار
                    </h1>
                    <p class="text-muted">تحكم في صلاحيات المستخدمين والأدوار في النظام</p>
                </div>
                <div>
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createRoleModal">
                        <i class="fas fa-plus me-2"></i>
                        إضافة دور جديد
                    </button>
                </div>
            </div>

            <!-- إحصائيات سريعة -->
            <div class="row mb-4">
                <div class="col-md-3">
                    <div class="card bg-primary text-white">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h4>{{ $stats['total_permissions'] }}</h4>
                                    <p class="mb-0">إجمالي الصلاحيات</p>
                                </div>
                                <div class="align-self-center">
                                    <i class="fas fa-key fa-2x"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-success text-white">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h4>{{ $stats['total_roles'] }}</h4>
                                    <p class="mb-0">إجمالي الأدوار</p>
                                </div>
                                <div class="align-self-center">
                                    <i class="fas fa-users-cog fa-2x"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-info text-white">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h4>{{ $stats['users_with_roles'] }}</h4>
                                    <p class="mb-0">مستخدمون لديهم أدوار</p>
                                </div>
                                <div class="align-self-center">
                                    <i class="fas fa-user-check fa-2x"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-warning text-white">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h4>{{ $stats['users_with_direct_permissions'] }}</h4>
                                    <p class="mb-0">صلاحيات مباشرة</p>
                                </div>
                                <div class="align-self-center">
                                    <i class="fas fa-user-shield fa-2x"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- تبويبات -->
            <ul class="nav nav-tabs" id="permissionTabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="roles-tab" data-bs-toggle="tab" data-bs-target="#roles" type="button" role="tab">
                        <i class="fas fa-users-cog me-2"></i>الأدوار
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="permissions-tab" data-bs-toggle="tab" data-bs-target="#permissions" type="button" role="tab">
                        <i class="fas fa-key me-2"></i>الصلاحيات
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="matrix-tab" data-bs-toggle="tab" data-bs-target="#matrix" type="button" role="tab">
                        <i class="fas fa-table me-2"></i>مصفوفة الصلاحيات
                    </button>
                </li>
            </ul>

            <div class="tab-content" id="permissionTabsContent">
                <!-- تبويب الأدوار -->
                <div class="tab-pane fade show active" id="roles" role="tabpanel">
                    <div class="card">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>الدور</th>
                                            <th>الوصف</th>
                                            <th>عدد الصلاحيات</th>
                                            <th>عدد المستخدمين</th>
                                            <th>الإجراءات</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($roles as $role)
                                        <tr>
                                            <td>
                                                <span class="badge bg-primary">{{ $role['name'] }}</span>
                                            </td>
                                            <td>{{ $role['description'] ?? 'لا يوجد وصف' }}</td>
                                            <td>
                                                <span class="badge bg-info">{{ $role['permissions_count'] ?? 0 }}</span>
                                            </td>
                                            <td>
                                                <span class="badge bg-success">{{ $role['users_count'] ?? 0 }}</span>
                                            </td>
                                            <td>
                                                <div class="btn-group" role="group">
                                                    <button class="btn btn-sm btn-outline-primary" onclick="editRole({{ $role['id'] }})" title="تعديل">
                                                        <i class="fas fa-edit"></i>
                                                    </button>
                                                    <button class="btn btn-sm btn-outline-info" onclick="viewRolePermissions({{ $role['id'] }})" title="عرض الصلاحيات">
                                                        <i class="fas fa-eye"></i>
                                                    </button>
                                                    <button class="btn btn-sm btn-outline-secondary" onclick="copyRole({{ $role['id'] }})" title="نسخ الدور">
                                                        <i class="fas fa-copy"></i>
                                                    </button>
                                                    @if($role['name'] !== 'super_admin')
                                                    <button class="btn btn-sm btn-outline-danger" onclick="deleteRole({{ $role['id'] }})" title="حذف">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="5" class="text-center py-4">
                                                <i class="fas fa-users-slash fa-3x text-muted mb-3"></i>
                                                <p class="text-muted">لا توجد أدوار في النظام</p>
                                                <button class="btn btn-primary" onclick="createDefaultRoles()">
                                                    <i class="fas fa-plus me-2"></i>
                                                    إنشاء الأدوار الافتراضية
                                                </button>
                                            </td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- تبويب الصلاحيات -->
                <div class="tab-pane fade" id="permissions" role="tabpanel">
                    <div class="card">
                        <div class="card-body">
                            @foreach($groupedPermissions as $category => $group)
                            <div class="permission-category mb-4">
                                <h5 class="text-primary mb-3">
                                    <i class="fas fa-folder me-2"></i>
                                    {{ $group['name'] }}
                                    <span class="badge bg-secondary ms-2">{{ count($group['permissions']) }}</span>
                                </h5>
                                <div class="row">
                                    @foreach($group['permissions'] as $permission)
                                    <div class="col-md-4 col-lg-3 mb-2">
                                        <div class="card border-light">
                                            <div class="card-body p-2">
                                                <small class="text-muted">{{ $permission['name'] }}</small>
                                                <br>
                                                <strong>{{ $permission['description'] }}</strong>
                                            </div>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- تبويب مصفوفة الصلاحيات -->
                <div class="tab-pane fade" id="matrix" role="tabpanel">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h5 class="mb-0">
                                <i class="fas fa-table me-2"></i>
                                مصفوفة الصلاحيات
                            </h5>
                            <div>
                                <button class="btn btn-sm btn-outline-primary" onclick="updatePermissionMatrix()">
                                    <i class="fas fa-sync me-1"></i>
                                    تحديث
                                </button>
                                <button class="btn btn-sm btn-outline-success" onclick="exportMatrix()">
                                    <i class="fas fa-download me-1"></i>
                                    تصدير
                                </button>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive permission-matrix-container">
                                <table class="table table-sm table-bordered" id="permissionMatrix">
                                    <thead class="table-dark">
                                        <tr>
                                            <th style="min-width: 250px;">الصلاحية</th>
                                            @foreach($roles as $role)
                                            <th class="text-center" style="min-width: 100px;">
                                                <div class="d-flex flex-column align-items-center">
                                                    <span class="badge bg-primary mb-1">{{ $role['name'] }}</span>
                                                    <small class="text-light">{{ $role['users_count'] }} مستخدم</small>
                                                </div>
                                            </th>
                                            @endforeach
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($groupedPermissions as $category => $group)
                                        <tr class="table-secondary">
                                            <td colspan="{{ count($roles) + 1 }}">
                                                <strong>
                                                    <i class="fas fa-folder me-2"></i>
                                                    {{ $group['name'] }}
                                                    <span class="badge bg-dark ms-2">{{ count($group['permissions']) }}</span>
                                                </strong>
                                            </td>
                                        </tr>
                                        @foreach($group['permissions'] as $permission)
                                        <tr>
                                            <td>
                                                <div>
                                                    <strong>{{ $permission['description'] }}</strong>
                                                    <br>
                                                    <small class="text-muted">{{ $permission['name'] }}</small>
                                                </div>
                                            </td>
                                            @foreach($roles as $role)
                                            <td class="text-center">
                                                @php
                                                    // التحقق من وجود الصلاحية للدور
                                                    $hasPermission = false;
                                                    if (isset($role['permissions'])) {
                                                        $hasPermission = in_array($permission['name'], $role['permissions']);
                                                    }
                                                @endphp
                                                @if($hasPermission)
                                                    <i class="fas fa-check-circle text-success" title="مسموح"></i>
                                                @else
                                                    <i class="fas fa-times-circle text-danger" title="غير مسموح"></i>
                                                @endif
                                            </td>
                                            @endforeach
                                        </tr>
                                        @endforeach
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                            <!-- إحصائيات المصفوفة -->
                            <div class="row mt-3">
                                <div class="col-md-12">
                                    <div class="card matrix-stats">
                                        <div class="card-body">
                                            <h6 class="card-title">إحصائيات المصفوفة:</h6>
                                            <div class="row text-center">
                                                <div class="col-md-3">
                                                    <h5 class="text-primary">{{ count($roles) }}</h5>
                                                    <small class="text-muted">إجمالي الأدوار</small>
                                                </div>
                                                <div class="col-md-3">
                                                    @php
                                                        $totalPermissions = 0;
                                                        foreach($groupedPermissions as $group) {
                                                            $totalPermissions += count($group['permissions']);
                                                        }
                                                    @endphp
                                                    <h5 class="text-info">{{ $totalPermissions }}</h5>
                                                    <small class="text-muted">إجمالي الصلاحيات</small>
                                                </div>
                                                <div class="col-md-3">
                                                    <h5 class="text-success">{{ count($groupedPermissions) }}</h5>
                                                    <small class="text-muted">فئات الصلاحيات</small>
                                                </div>
                                                <div class="col-md-3">
                                                    @php
                                                        $totalAssignments = 0;
                                                        foreach($roles as $role) {
                                                            if (isset($role['permissions'])) {
                                                                $totalAssignments += count($role['permissions']);
                                                            }
                                                        }
                                                    @endphp
                                                    <h5 class="text-warning">{{ $totalAssignments }}</h5>
                                                    <small class="text-muted">إجمالي التخصيصات</small>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal إنشاء دور جديد -->
<div class="modal fade" id="createRoleModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">إنشاء دور جديد</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="createRoleForm">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="role_name" class="form-label">اسم الدور</label>
                        <input type="text" class="form-control" id="role_name" name="name" required>
                    </div>
                    <div class="mb-3">
                        <label for="role_description" class="form-label">الوصف</label>
                        <textarea class="form-control" id="role_description" name="description" rows="3"></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">الصلاحيات</label>
                        <div class="border rounded p-3" style="max-height: 300px; overflow-y: auto;">
                            @foreach($groupedPermissions as $category => $group)
                            <div class="mb-3">
                                <h6 class="text-primary">{{ $group['name'] }}</h6>
                                @foreach($group['permissions'] as $permission)
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" 
                                           id="new_permission_{{ $permission['id'] }}" 
                                           name="permissions[]" 
                                           value="{{ $permission['name'] }}">
                                    <label class="form-check-label" for="new_permission_{{ $permission['id'] }}">
                                        {{ $permission['description'] }}
                                    </label>
                                </div>
                                @endforeach
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                    <button type="submit" class="btn btn-primary">إنشاء الدور</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
.permission-category {
    border-left: 4px solid #007bff;
    padding-left: 1rem;
}

.table th {
    background-color: #f8f9fa;
}

.nav-tabs .nav-link {
    color: #495057;
}

.nav-tabs .nav-link.active {
    color: #007bff;
    font-weight: 600;
}

.card {
    box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
}

#permissionMatrix {
    font-size: 0.875rem;
}

#permissionMatrix th {
    position: sticky;
    top: 0;
    background: #343a40 !important;
    z-index: 10;
}

#permissionMatrix td {
    vertical-align: middle;
}

#permissionMatrix .table-secondary {
    background-color: #e9ecef !important;
}

.btn-group .btn {
    border-radius: 0.25rem;
    margin-right: 2px;
}

.btn-group .btn:last-child {
    margin-right: 0;
}

.permission-matrix-container {
    max-height: 600px;
    overflow-y: auto;
}

.matrix-stats {
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    border-radius: 10px;
    padding: 1rem;
}

.matrix-stats h5 {
    color: #495057;
    font-weight: 600;
}

.matrix-stats small {
    color: #6c757d;
}
</style>
@endpush

@push('scripts')
<script>
// تعديل الدور
function editRole(roleId) {
    // إظهار مؤشر التحميل
    const loadingAlert = document.createElement('div');
    loadingAlert.className = 'alert alert-info';
    loadingAlert.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>جاري تحميل بيانات الدور...';
    document.body.appendChild(loadingAlert);

    fetch(`/permissions/roles/${roleId}/permissions`)
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.json();
        })
        .then(data => {
            document.body.removeChild(loadingAlert);
            console.log('Role data received:', data); // Debug
            if (data.success) {
                showEditRoleModal(data.role, data.permissions);
            } else {
                alert('حدث خطأ أثناء تحميل بيانات الدور: ' + (data.message || 'خطأ غير معروف'));
            }
        })
        .catch(error => {
            document.body.removeChild(loadingAlert);
            console.error('Error:', error);
            alert('حدث خطأ أثناء تحميل بيانات الدور: ' + error.message);
        });
}

// عرض صلاحيات الدور
function viewRolePermissions(roleId) {
    fetch(`/permissions/roles/${roleId}/permissions`)
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                showRolePermissionsModal(data.role, data.permissions);
            } else {
                alert('حدث خطأ أثناء تحميل صلاحيات الدور: ' + (data.message || 'خطأ غير معروف'));
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('حدث خطأ أثناء تحميل صلاحيات الدور: ' + error.message);
        });
}

// حذف الدور
function deleteRole(roleId) {
    if (confirm('هل أنت متأكد من حذف هذا الدور؟ لا يمكن التراجع عن هذا الإجراء.')) {
        fetch(`/permissions/roles/${roleId}`, {
            method: 'DELETE',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('تم حذف الدور بنجاح');
                location.reload();
            } else {
                alert(data.message || 'حدث خطأ أثناء حذف الدور');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('حدث خطأ أثناء حذف الدور');
        });
    }
}

// إنشاء دور جديد
document.getElementById('createRoleForm').addEventListener('submit', function(e) {
    e.preventDefault();

    const formData = new FormData(this);
    const data = {
        name: formData.get('name'),
        description: formData.get('description'),
        permissions: Array.from(document.querySelectorAll('input[name="permissions[]"]:checked')).map(cb => cb.value)
    };

    fetch('/permissions/roles', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify(data)
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('تم إنشاء الدور بنجاح');
            location.reload();
        } else {
            alert(data.message || 'حدث خطأ أثناء إنشاء الدور');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('حدث خطأ أثناء إنشاء الدور');
    });
});

// عرض نافذة تعديل الدور
function showEditRoleModal(role, permissions) {
    const modal = document.createElement('div');
    modal.className = 'modal fade';
    modal.innerHTML = `
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">تعديل الدور: ${role.name}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="editRoleForm">
                        <div class="mb-3">
                            <label class="form-label">اسم الدور</label>
                            <input type="text" class="form-control" name="name" value="${role.name}" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">الوصف</label>
                            <textarea class="form-control" name="description" rows="3">${role.description || ''}</textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">الصلاحيات</label>
                            <div class="row">
                                ${permissions.map(permission => `
                                    <div class="col-md-6 mb-2">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="permissions[]"
                                                   value="${permission.name}" ${role.permissions.includes(permission.name) ? 'checked' : ''}>
                                            <label class="form-check-label">${permission.description}</label>
                                        </div>
                                    </div>
                                `).join('')}
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                    <button type="button" class="btn btn-primary" onclick="updateRole(${role.id})">حفظ التغييرات</button>
                </div>
            </div>
        </div>
    `;

    document.body.appendChild(modal);
    const bootstrapModal = new bootstrap.Modal(modal);
    bootstrapModal.show();

    modal.addEventListener('hidden.bs.modal', function() {
        document.body.removeChild(modal);
    });
}

// تحديث الدور
function updateRole(roleId) {
    const form = document.getElementById('editRoleForm');
    const formData = new FormData(form);
    const data = {
        name: formData.get('name'),
        description: formData.get('description'),
        permissions: Array.from(document.querySelectorAll('#editRoleForm input[name="permissions[]"]:checked')).map(cb => cb.value)
    };

    fetch(`/permissions/roles/${roleId}`, {
        method: 'PUT',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify(data)
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('تم تحديث الدور بنجاح');
            location.reload();
        } else {
            alert(data.message || 'حدث خطأ أثناء تحديث الدور');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('حدث خطأ أثناء تحديث الدور');
    });
}

// عرض نافذة صلاحيات الدور
function showRolePermissionsModal(role, permissions) {
    const modal = document.createElement('div');
    modal.className = 'modal fade';
    modal.innerHTML = `
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">صلاحيات الدور: ${role.name}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        ${role.permissions.map(permission => {
                            const permissionData = permissions.find(p => p.name === permission);
                            return `
                                <div class="col-md-6 mb-2">
                                    <div class="card border-success">
                                        <div class="card-body p-2">
                                            <small class="text-success">${permission}</small><br>
                                            <strong>${permissionData ? permissionData.description : permission}</strong>
                                        </div>
                                    </div>
                                </div>
                            `;
                        }).join('')}
                    </div>
                    ${role.permissions.length === 0 ? '<p class="text-muted text-center">لا توجد صلاحيات مخصصة لهذا الدور</p>' : ''}
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إغلاق</button>
                </div>
            </div>
        </div>
    `;

    document.body.appendChild(modal);
    const bootstrapModal = new bootstrap.Modal(modal);
    bootstrapModal.show();

    modal.addEventListener('hidden.bs.modal', function() {
        document.body.removeChild(modal);
    });
}

// تحديث مصفوفة الصلاحيات
function updatePermissionMatrix() {
    fetch('/permissions/matrix/export')
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                updateMatrixTable(data.matrix, data.roles);
            }
        })
        .catch(error => {
            console.error('Error:', error);
        });
}

// تحديث جدول المصفوفة
function updateMatrixTable(matrix, roles) {
    const tbody = document.querySelector('#matrix tbody');
    if (!tbody) return;

    tbody.innerHTML = matrix.map(row => `
        <tr>
            <td>${row.description}</td>
            ${roles.map(role => `
                <td class="text-center">
                    ${row[role] === 'نعم' ?
                        '<i class="fas fa-check text-success"></i>' :
                        '<i class="fas fa-times text-danger"></i>'
                    }
                </td>
            `).join('')}
        </tr>
    `).join('');
}

// تصدير المصفوفة
function exportMatrix() {
    fetch('/permissions/matrix/export')
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                downloadMatrixAsCSV(data.matrix, data.roles);
            } else {
                alert('حدث خطأ أثناء تصدير المصفوفة');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('حدث خطأ أثناء تصدير المصفوفة');
        });
}

// تحميل المصفوفة كملف CSV
function downloadMatrixAsCSV(matrix, roles) {
    let csv = 'الصلاحية,الوصف,' + roles.join(',') + '\n';

    matrix.forEach(row => {
        const values = [
            row.permission,
            row.description,
            ...roles.map(role => row[role])
        ];
        csv += values.map(value => `"${value}"`).join(',') + '\n';
    });

    const blob = new Blob([csv], { type: 'text/csv;charset=utf-8;' });
    const link = document.createElement('a');
    const url = URL.createObjectURL(blob);
    link.setAttribute('href', url);
    link.setAttribute('download', 'permission_matrix.csv');
    link.style.visibility = 'hidden';
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
}

// نسخ دور
function copyRole(roleId) {
    const roleName = prompt('أدخل اسم الدور الجديد:');
    if (!roleName) return;

    fetch('/permissions/roles/copy', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({
            source_role_id: roleId,
            new_role_name: roleName
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('تم نسخ الدور بنجاح');
            location.reload();
        } else {
            alert(data.message || 'حدث خطأ أثناء نسخ الدور');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('حدث خطأ أثناء نسخ الدور');
    });
}

// إنشاء الأدوار الافتراضية
function createDefaultRoles() {
    if (confirm('هل تريد إنشاء الأدوار الافتراضية؟ سيتم إنشاء أدوار: مدير النظام، مدير، موظف، أمين صندوق')) {
        fetch('/permissions/create-default-roles', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('تم إنشاء الأدوار الافتراضية بنجاح');
                location.reload();
            } else {
                alert(data.message || 'حدث خطأ أثناء إنشاء الأدوار');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('حدث خطأ أثناء إنشاء الأدوار');
        });
    }
}

// تحميل المصفوفة عند تحميل الصفحة
document.addEventListener('DOMContentLoaded', function() {
    // تحديث المصفوفة عند النقر على التبويب
    document.getElementById('matrix-tab').addEventListener('click', function() {
        setTimeout(updatePermissionMatrix, 100);
    });
});
</script>
@endpush
