@extends('layouts.app')

@section('title', 'تفاصيل المستخدم')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <!-- Header -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h1 class="h3 mb-0">تفاصيل المستخدم</h1>
                    <p class="text-muted">عرض معلومات المستخدم التفصيلية</p>
                </div>
                <div>
                    <a href="{{ route('users.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-right me-2"></i>العودة للقائمة
                    </a>
                    @can('users.edit')
                    <a href="{{ route('users.edit', $user->id) }}" class="btn btn-primary">
                        <i class="fas fa-edit me-2"></i>تعديل
                    </a>
                    @endcan
                </div>
            </div>

            <div class="row">
                <!-- معلومات المستخدم الأساسية -->
                <div class="col-lg-8">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">
                                <i class="fas fa-user me-2"></i>المعلومات الأساسية
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-bold">الاسم الكامل:</label>
                                    <p class="form-control-plaintext">{{ $user->name }}</p>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-bold">البريد الإلكتروني:</label>
                                    <p class="form-control-plaintext">{{ $user->email }}</p>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-bold">نوع المستخدم:</label>
                                    <p class="form-control-plaintext">
                                        <span class="badge bg-{{ $user->user_type === 'admin' ? 'danger' : ($user->user_type === 'manager' ? 'warning' : 'info') }}">
                                            {{ $user->user_type === 'admin' ? 'مدير' : ($user->user_type === 'manager' ? 'مدير مساعد' : ($user->user_type === 'employee' ? 'موظف' : 'عميل')) }}
                                        </span>
                                    </p>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-bold">الحالة:</label>
                                    <p class="form-control-plaintext">
                                        <span class="badge bg-{{ $user->status === 'active' ? 'success' : 'secondary' }}">
                                            {{ $user->status === 'active' ? 'نشط' : 'غير نشط' }}
                                        </span>
                                    </p>
                                </div>
                                @if($user->phone)
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-bold">رقم الهاتف:</label>
                                    <p class="form-control-plaintext">{{ $user->phone }}</p>
                                </div>
                                @endif
                                @if($user->company_name)
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-bold">اسم الشركة:</label>
                                    <p class="form-control-plaintext">{{ $user->company_name }}</p>
                                </div>
                                @endif
                                @if($user->address)
                                <div class="col-12 mb-3">
                                    <label class="form-label fw-bold">العنوان:</label>
                                    <p class="form-control-plaintext">{{ $user->address }}</p>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- معلومات إضافية -->
                    @if($user->tax_number || $user->created_at)
                    <div class="card mt-4">
                        <div class="card-header">
                            <h5 class="card-title mb-0">
                                <i class="fas fa-info-circle me-2"></i>معلومات إضافية
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                @if($user->tax_number)
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-bold">الرقم الضريبي:</label>
                                    <p class="form-control-plaintext">{{ $user->tax_number }}</p>
                                </div>
                                @endif
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-bold">تاريخ التسجيل:</label>
                                    <p class="form-control-plaintext">{{ $user->created_at->format('Y-m-d H:i') }}</p>
                                </div>
                                @if($user->updated_at && $user->updated_at != $user->created_at)
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-bold">آخر تحديث:</label>
                                    <p class="form-control-plaintext">{{ $user->updated_at->format('Y-m-d H:i') }}</p>
                                </div>
                                @endif
                                @if(isset($user->last_login_at))
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-bold">آخر تسجيل دخول:</label>
                                    <p class="form-control-plaintext">
                                        {{ $user->last_login_at ? $user->last_login_at->format('Y-m-d H:i') : 'لم يسجل دخول بعد' }}
                                    </p>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                    @endif
                </div>

                <!-- الشريط الجانبي -->
                <div class="col-lg-4">
                    <!-- إحصائيات سريعة -->
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">
                                <i class="fas fa-chart-bar me-2"></i>إحصائيات سريعة
                            </h5>
                        </div>
                        <div class="card-body">
                            @if(isset($user->login_count))
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <span>عدد مرات الدخول:</span>
                                <span class="badge bg-primary">{{ $user->login_count ?? 0 }}</span>
                            </div>
                            @endif
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <span>مدة العضوية:</span>
                                <span class="badge bg-info">{{ $user->created_at->diffForHumans() }}</span>
                            </div>
                            @if($user->user_type === 'customer')
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <span>إجمالي الطلبات:</span>
                                <span class="badge bg-success">{{ $user->orders()->count() ?? 0 }}</span>
                            </div>
                            @endif
                        </div>
                    </div>

                    <!-- الإجراءات -->
                    <div class="card mt-4">
                        <div class="card-header">
                            <h5 class="card-title mb-0">
                                <i class="fas fa-cogs me-2"></i>الإجراءات
                            </h5>
                        </div>
                        <div class="card-body">
                            @can('users.edit')
                            <a href="{{ route('users.edit', $user->id) }}" class="btn btn-primary btn-sm w-100 mb-2">
                                <i class="fas fa-edit me-2"></i>تعديل المستخدم
                            </a>
                            @endcan
                            
                            @if($user->status === 'active')
                                @can('users.edit')
                                <form action="{{ route('users.deactivate', $user->id) }}" method="POST" class="d-inline w-100">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="btn btn-warning btn-sm w-100 mb-2" 
                                            onclick="return confirm('هل أنت متأكد من إلغاء تفعيل هذا المستخدم؟')">
                                        <i class="fas fa-user-slash me-2"></i>إلغاء التفعيل
                                    </button>
                                </form>
                                @endcan
                            @else
                                @can('users.edit')
                                <form action="{{ route('users.activate', $user->id) }}" method="POST" class="d-inline w-100">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="btn btn-success btn-sm w-100 mb-2">
                                        <i class="fas fa-user-check me-2"></i>تفعيل المستخدم
                                    </button>
                                </form>
                                @endcan
                            @endif

                            @can('users.delete')
                            <form action="{{ route('users.destroy', $user->id) }}" method="POST" class="d-inline w-100">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm w-100" 
                                        onclick="return confirm('هل أنت متأكد من حذف هذا المستخدم؟ هذا الإجراء لا يمكن التراجع عنه!')">
                                    <i class="fas fa-trash me-2"></i>حذف المستخدم
                                </button>
                            </form>
                            @endcan
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
.form-control-plaintext {
    background-color: #f8f9fa;
    border: 1px solid #e9ecef;
    border-radius: 0.375rem;
    padding: 0.375rem 0.75rem;
    margin-bottom: 0;
}

.card {
    box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
    border: 1px solid rgba(0, 0, 0, 0.125);
}

.badge {
    font-size: 0.875em;
}
</style>
@endpush
