@extends('layouts.app')

@section('title', 'دليل الحسابات - ' . __('messages.system_name'))

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">{{ __('navigation.dashboard') }}</a></li>
    <li class="breadcrumb-item"><a href="{{ route('finance.dashboard') }}">النظام المالي</a></li>
    <li class="breadcrumb-item active">دليل الحسابات</li>
@endsection

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0">
                <i class="fas fa-list me-2"></i>
                دليل الحسابات
            </h1>
            <p class="text-muted">إدارة شاملة لجميع الحسابات المالية في النظام</p>
        </div>
        <div>
            <a href="{{ route('finance.accounts.create') }}" class="btn btn-primary">
                <i class="fas fa-plus me-2"></i>
                إضافة حساب جديد
            </a>
        </div>
    </div>

    <!-- إحصائيات سريعة -->
    <div class="row mb-4">
        <div class="col-md-2 mb-3">
            <div class="card text-center">
                <div class="card-body">
                    <h5 class="card-title text-primary">{{ $accountStats['total_accounts'] }}</h5>
                    <p class="card-text small">إجمالي الحسابات</p>
                </div>
            </div>
        </div>
        <div class="col-md-2 mb-3">
            <div class="card text-center">
                <div class="card-body">
                    <h5 class="card-title text-success">{{ $accountStats['active_accounts'] }}</h5>
                    <p class="card-text small">الحسابات النشطة</p>
                </div>
            </div>
        </div>
        <div class="col-md-2 mb-3">
            <div class="card text-center">
                <div class="card-body">
                    <h5 class="card-title text-info">{{ $accountStats['assets'] }}</h5>
                    <p class="card-text small">الأصول</p>
                </div>
            </div>
        </div>
        <div class="col-md-2 mb-3">
            <div class="card text-center">
                <div class="card-body">
                    <h5 class="card-title text-warning">{{ $accountStats['liabilities'] }}</h5>
                    <p class="card-text small">الخصوم</p>
                </div>
            </div>
        </div>
        <div class="col-md-2 mb-3">
            <div class="card text-center">
                <div class="card-body">
                    <h5 class="card-title text-secondary">{{ $accountStats['equity'] }}</h5>
                    <p class="card-text small">حقوق الملكية</p>
                </div>
            </div>
        </div>
        <div class="col-md-2 mb-3">
            <div class="card text-center">
                <div class="card-body">
                    <h5 class="card-title text-danger">{{ $accountStats['expenses'] }}</h5>
                    <p class="card-text small">المصروفات</p>
                </div>
            </div>
        </div>
    </div>

    <!-- فلاتر البحث -->
    <div class="card mb-4">
        <div class="card-header">
            <h5 class="card-title mb-0">
                <i class="fas fa-filter me-2"></i>
                فلاتر البحث
            </h5>
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('finance.accounts.index') }}">
                <div class="row">
                    <div class="col-md-3 mb-3">
                        <label for="search" class="form-label">البحث</label>
                        <input type="text" class="form-control" id="search" name="search" 
                               value="{{ request('search') }}" placeholder="اسم الحساب أو الرمز">
                    </div>
                    <div class="col-md-2 mb-3">
                        <label for="account_type" class="form-label">نوع الحساب</label>
                        <select class="form-select" id="account_type" name="account_type">
                            <option value="">جميع الأنواع</option>
                            <option value="asset" {{ request('account_type') == 'asset' ? 'selected' : '' }}>أصول</option>
                            <option value="liability" {{ request('account_type') == 'liability' ? 'selected' : '' }}>خصوم</option>
                            <option value="equity" {{ request('account_type') == 'equity' ? 'selected' : '' }}>حقوق ملكية</option>
                            <option value="revenue" {{ request('account_type') == 'revenue' ? 'selected' : '' }}>إيرادات</option>
                            <option value="expense" {{ request('account_type') == 'expense' ? 'selected' : '' }}>مصروفات</option>
                        </select>
                    </div>
                    <div class="col-md-3 mb-3">
                        <label for="account_category" class="form-label">تصنيف الحساب</label>
                        <select class="form-select" id="account_category" name="account_category">
                            <option value="">جميع التصنيفات</option>
                            <option value="current_assets" {{ request('account_category') == 'current_assets' ? 'selected' : '' }}>أصول متداولة</option>
                            <option value="fixed_assets" {{ request('account_category') == 'fixed_assets' ? 'selected' : '' }}>أصول ثابتة</option>
                            <option value="current_liabilities" {{ request('account_category') == 'current_liabilities' ? 'selected' : '' }}>خصوم متداولة</option>
                            <option value="long_term_liabilities" {{ request('account_category') == 'long_term_liabilities' ? 'selected' : '' }}>خصوم طويلة الأجل</option>
                        </select>
                    </div>
                    <div class="col-md-2 mb-3">
                        <label for="is_active" class="form-label">الحالة</label>
                        <select class="form-select" id="is_active" name="is_active">
                            <option value="">جميع الحالات</option>
                            <option value="1" {{ request('is_active') == '1' ? 'selected' : '' }}>نشط</option>
                            <option value="0" {{ request('is_active') == '0' ? 'selected' : '' }}>غير نشط</option>
                        </select>
                    </div>
                    <div class="col-md-2 mb-3 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary me-2">
                            <i class="fas fa-search me-1"></i>
                            بحث
                        </button>
                        <a href="{{ route('finance.accounts.index') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-times me-1"></i>
                            إلغاء
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- جدول الحسابات -->
    <div class="card">
        <div class="card-header">
            <h5 class="card-title mb-0">
                <i class="fas fa-table me-2"></i>
                قائمة الحسابات
                <span class="badge bg-primary ms-2">{{ $accounts->total() }}</span>
            </h5>
        </div>
        <div class="card-body">
            @if($accounts->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>رمز الحساب</th>
                                <th>اسم الحساب</th>
                                <th>النوع</th>
                                <th>التصنيف</th>
                                <th>الحساب الأب</th>
                                <th>الرصيد الحالي</th>
                                <th>طبيعة الرصيد</th>
                                <th>الحالة</th>
                                <th>الإجراءات</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($accounts as $account)
                            <tr>
                                <td>
                                    <code>{{ $account->account_code }}</code>
                                </td>
                                <td>
                                    <div>
                                        <strong>{{ $account->account_name }}</strong>
                                        @if($account->account_name_en)
                                            <br><small class="text-muted">{{ $account->account_name_en }}</small>
                                        @endif
                                    </div>
                                </td>
                                <td>
                                    @switch($account->account_type)
                                        @case('asset')
                                            <span class="badge bg-info">أصول</span>
                                            @break
                                        @case('liability')
                                            <span class="badge bg-warning">خصوم</span>
                                            @break
                                        @case('equity')
                                            <span class="badge bg-secondary">حقوق ملكية</span>
                                            @break
                                        @case('revenue')
                                            <span class="badge bg-success">إيرادات</span>
                                            @break
                                        @case('expense')
                                            <span class="badge bg-danger">مصروفات</span>
                                            @break
                                    @endswitch
                                </td>
                                <td>
                                    <small>
                                        @switch($account->account_category)
                                            @case('current_assets')
                                                أصول متداولة
                                                @break
                                            @case('fixed_assets')
                                                أصول ثابتة
                                                @break
                                            @case('current_liabilities')
                                                خصوم متداولة
                                                @break
                                            @case('long_term_liabilities')
                                                خصوم طويلة الأجل
                                                @break
                                            @case('capital')
                                                رأس المال
                                                @break
                                            @case('retained_earnings')
                                                أرباح محتجزة
                                                @break
                                            @case('sales_revenue')
                                                إيرادات المبيعات
                                                @break
                                            @case('other_revenue')
                                                إيرادات أخرى
                                                @break
                                            @case('cost_of_goods_sold')
                                                تكلفة البضاعة المباعة
                                                @break
                                            @case('operating_expenses')
                                                مصروفات تشغيلية
                                                @break
                                            @case('financial_expenses')
                                                مصروفات مالية
                                                @break
                                            @default
                                                {{ $account->account_category }}
                                        @endswitch
                                    </small>
                                </td>
                                <td>
                                    @if($account->parentAccount)
                                        <small>{{ $account->parentAccount->account_name }}</small>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td>
                                    <strong>{{ number_format($account->current_balance, 2) }}</strong>
                                    <small class="text-muted">د.ع</small>
                                </td>
                                <td>
                                    @if($account->balance_type == 'debit')
                                        <span class="badge bg-primary">مدين</span>
                                    @else
                                        <span class="badge bg-success">دائن</span>
                                    @endif
                                </td>
                                <td>
                                    @if($account->is_active)
                                        <span class="badge bg-success">نشط</span>
                                    @else
                                        <span class="badge bg-secondary">غير نشط</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('finance.accounts.show', $account->id) }}" 
                                           class="btn btn-sm btn-outline-info" title="عرض">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('finance.accounts.edit', $account->id) }}" 
                                           class="btn btn-sm btn-outline-primary" title="تعديل">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        @if(!$account->is_system_account)
                                        <button type="button" class="btn btn-sm btn-outline-danger" 
                                                onclick="confirmDelete({{ $account->id }})" title="حذف">
                                            <i class="fas fa-trash"></i>
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
                    {{ $accounts->appends(request()->query())->links() }}
                </div>
            @else
                <div class="text-center py-5">
                    <i class="fas fa-list fa-3x text-muted mb-3"></i>
                    <h5 class="text-muted">لا توجد حسابات</h5>
                    <p class="text-muted">ابدأ بإنشاء أول حساب في دليل الحسابات</p>
                    <a href="{{ route('finance.accounts.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus me-2"></i>
                        إضافة حساب جديد
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Modal تأكيد الحذف -->
<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">تأكيد الحذف</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                هل أنت متأكد من حذف هذا الحساب؟ هذا الإجراء لا يمكن التراجع عنه.
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                <form id="deleteForm" method="POST" style="display: inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">حذف</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function confirmDelete(accountId) {
    const form = document.getElementById('deleteForm');
    form.action = `/finance/accounts/${accountId}`;
    
    const modal = new bootstrap.Modal(document.getElementById('deleteModal'));
    modal.show();
}
</script>
@endpush
