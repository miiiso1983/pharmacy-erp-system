@extends('layouts.app')

@section('title', 'إدارة المستخدمين')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">لوحة التحكم الإدارية</a></li>
    <li class="breadcrumb-item active">إدارة المستخدمين</li>
@endsection

@section('content')
<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>
                <i class="fas fa-users me-2"></i>
                إدارة المستخدمين
            </h2>
            <div>
                <a href="{{ route('admin.users.create') }}" class="btn btn-success">
                    <i class="fas fa-user-plus me-2"></i>
                    إضافة مستخدم جديد
                </a>
            </div>
        </div>
    </div>
</div>

<!-- فلاتر البحث -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <form method="GET" action="{{ route('admin.users') }}">
                    <div class="row">
                        <div class="col-md-3 mb-3">
                            <label for="search" class="form-label">البحث</label>
                            <input type="text" class="form-control" id="search" name="search" 
                                   value="{{ request('search') }}" placeholder="الاسم أو البريد الإلكتروني...">
                        </div>
                        <div class="col-md-2 mb-3">
                            <label for="role" class="form-label">الدور</label>
                            <select class="form-select" id="role" name="role">
                                <option value="">جميع الأدوار</option>
                                @foreach($userRoles as $key => $value)
                                    <option value="{{ $key }}" {{ request('role') === $key ? 'selected' : '' }}>
                                        {{ $value }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2 mb-3">
                            <label for="status" class="form-label">الحالة</label>
                            <select class="form-select" id="status" name="status">
                                <option value="">جميع الحالات</option>
                                <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>نشط</option>
                                <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>غير نشط</option>
                                <option value="expired" {{ request('status') === 'expired' ? 'selected' : '' }}>منتهي الصلاحية</option>
                            </select>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label for="warehouse" class="form-label">المخزن</label>
                            <select class="form-select" id="warehouse" name="warehouse">
                                <option value="">جميع المخازن</option>
                                @foreach($users->pluck('warehouse.name', 'warehouse.id')->filter()->unique() as $id => $name)
                                    <option value="{{ $id }}" {{ request('warehouse') == $id ? 'selected' : '' }}>
                                        {{ $name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2 mb-3">
                            <label class="form-label">&nbsp;</label>
                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-search me-1"></i>
                                    بحث
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- قائمة المستخدمين -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-list me-2"></i>
                    قائمة المستخدمين ({{ $users->total() }})
                </h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>الاسم</th>
                                <th>البريد الإلكتروني</th>
                                <th>الدور</th>
                                <th>المخزن</th>
                                <th>الهاتف</th>
                                <th>تاريخ الانتهاء</th>
                                <th>الحالة</th>
                                <th>آخر دخول</th>
                                <th>الإجراءات</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($users as $user)
                                <tr class="{{ 
                                    $user->account_expiry_date && $user->account_expiry_date < now() ? 'table-danger' : 
                                    ($user->account_expiry_date && $user->account_expiry_date->diffInDays(now()) <= 7 ? 'table-warning' : '') 
                                }}">
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="avatar-sm me-2">
                                                <div class="avatar-title bg-primary rounded-circle">
                                                    {{ strtoupper(substr($user->name, 0, 1)) }}
                                                </div>
                                            </div>
                                            <div>
                                                <strong>{{ $user->name }}</strong>
                                                @if($user->created_by_admin)
                                                    <br><small class="text-muted">أنشأه: {{ $user->created_by_admin }}</small>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                    <td>{{ $user->email }}</td>
                                    <td>
                                        <span class="badge bg-secondary">
                                            {{ $userRoles[$user->user_role] ?? $user->user_role ?? 'غير محدد' }}
                                        </span>
                                    </td>
                                    <td>
                                        @if($user->warehouse)
                                            <span class="badge bg-info">{{ $user->warehouse->name }}</span>
                                        @else
                                            <span class="text-muted">غير محدد</span>
                                        @endif
                                    </td>
                                    <td>{{ $user->phone ?? 'غير محدد' }}</td>
                                    <td>
                                        @if($user->account_expiry_date)
                                            {{ $user->account_expiry_date->format('Y-m-d') }}
                                            @if($user->account_expiry_date < now())
                                                <br><small class="text-danger">منتهي</small>
                                            @elseif($user->account_expiry_date->diffInDays(now()) <= 7)
                                                <br><small class="text-warning">قريب الانتهاء</small>
                                            @endif
                                        @else
                                            <span class="text-muted">غير محدد</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($user->is_account_active ?? true)
                                            <span class="badge bg-success">نشط</span>
                                        @else
                                            <span class="badge bg-danger">غير نشط</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($user->last_login_at)
                                            {{ $user->last_login_at->diffForHumans() }}
                                        @else
                                            <span class="text-muted">لم يسجل دخول</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <button type="button" class="btn btn-sm btn-outline-primary" 
                                                    data-bs-toggle="modal" data-bs-target="#userModal{{ $user->id }}" title="عرض">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                            
                                            <form method="POST" action="{{ route('admin.users.toggle-status', $user) }}" 
                                                  style="display: inline;">
                                                @csrf
                                                <button type="submit" class="btn btn-sm {{ ($user->is_account_active ?? true) ? 'btn-outline-warning' : 'btn-outline-success' }}" 
                                                        title="{{ ($user->is_account_active ?? true) ? 'إلغاء تفعيل' : 'تفعيل' }}">
                                                    <i class="fas {{ ($user->is_account_active ?? true) ? 'fa-pause' : 'fa-play' }}"></i>
                                                </button>
                                            </form>
                                            
                                            <button type="button" class="btn btn-sm btn-outline-danger" 
                                                    onclick="confirmDelete({{ $user->id }})" title="حذف">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>

                                <!-- Modal تفاصيل المستخدم -->
                                <div class="modal fade" id="userModal{{ $user->id }}" tabindex="-1">
                                    <div class="modal-dialog modal-lg">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">تفاصيل المستخدم: {{ $user->name }}</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <p><strong>الاسم:</strong> {{ $user->name }}</p>
                                                        <p><strong>البريد الإلكتروني:</strong> {{ $user->email }}</p>
                                                        <p><strong>الدور:</strong> {{ $userRoles[$user->user_role] ?? $user->user_role ?? 'غير محدد' }}</p>
                                                        <p><strong>الهاتف:</strong> {{ $user->phone ?? 'غير محدد' }}</p>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <p><strong>المخزن:</strong> {{ $user->warehouse->name ?? 'غير محدد' }}</p>
                                                        <p><strong>القسم:</strong> {{ $user->department ?? 'غير محدد' }}</p>
                                                        <p><strong>تاريخ الإنشاء:</strong> {{ $user->created_at->format('Y-m-d H:i') }}</p>
                                                        <p><strong>تاريخ الانتهاء:</strong> {{ $user->account_expiry_date ? $user->account_expiry_date->format('Y-m-d') : 'غير محدد' }}</p>
                                                    </div>
                                                </div>
                                                @if($user->address)
                                                    <p><strong>العنوان:</strong> {{ $user->address }}</p>
                                                @endif
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إغلاق</button>
                                                <a href="#" class="btn btn-primary">تعديل</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <tr>
                                    <td colspan="9" class="text-center text-muted py-4">
                                        <i class="fas fa-users fa-3x mb-3"></i>
                                        <br>
                                        لا توجد مستخدمين مطابقين لمعايير البحث
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                
                <!-- Pagination -->
                @if($users->hasPages())
                    <div class="d-flex justify-content-center mt-4">
                        {{ $users->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
.avatar-sm {
    width: 40px;
    height: 40px;
}

.avatar-title {
    width: 100%;
    height: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 600;
}

.table-warning {
    background-color: rgba(255, 193, 7, 0.1) !important;
}

.table-danger {
    background-color: rgba(220, 53, 69, 0.1) !important;
}
</style>
@endpush

@push('scripts')
<script>
function confirmDelete(userId) {
    if (confirm('هل أنت متأكد من حذف هذا المستخدم؟ هذا الإجراء لا يمكن التراجع عنه!')) {
        // إنشاء form للحذف
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = `/admin/users/${userId}`;
        
        const csrfToken = document.createElement('input');
        csrfToken.type = 'hidden';
        csrfToken.name = '_token';
        csrfToken.value = '{{ csrf_token() }}';
        
        const methodField = document.createElement('input');
        methodField.type = 'hidden';
        methodField.name = '_method';
        methodField.value = 'DELETE';
        
        form.appendChild(csrfToken);
        form.appendChild(methodField);
        document.body.appendChild(form);
        form.submit();
    }
}
</script>
@endpush
