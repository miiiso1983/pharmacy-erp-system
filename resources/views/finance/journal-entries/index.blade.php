@extends('layouts.app')

@section('title', 'القيود المحاسبية - ' . __('messages.system_name'))

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">{{ __('navigation.dashboard') }}</a></li>
    <li class="breadcrumb-item"><a href="{{ route('finance.dashboard') }}">النظام المالي</a></li>
    <li class="breadcrumb-item active">القيود المحاسبية</li>
@endsection

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0">
                <i class="fas fa-book me-2"></i>
                القيود المحاسبية
            </h1>
            <p class="text-muted">إدارة شاملة للقيود المحاسبية اليومية</p>
        </div>
        <div>
            <a href="{{ route('finance.journal-entries.create') }}" class="btn btn-primary">
                <i class="fas fa-plus me-2"></i>
                إضافة قيد جديد
            </a>
        </div>
    </div>

    <!-- إحصائيات سريعة -->
    <div class="row mb-4">
        <div class="col-md-2 mb-3">
            <div class="card text-center">
                <div class="card-body">
                    <h5 class="card-title text-primary">{{ $entryStats['total_entries'] }}</h5>
                    <p class="card-text small">إجمالي القيود</p>
                </div>
            </div>
        </div>
        <div class="col-md-2 mb-3">
            <div class="card text-center">
                <div class="card-body">
                    <h5 class="card-title text-success">{{ $entryStats['posted_entries'] }}</h5>
                    <p class="card-text small">القيود المرحلة</p>
                </div>
            </div>
        </div>
        <div class="col-md-2 mb-3">
            <div class="card text-center">
                <div class="card-body">
                    <h5 class="card-title text-warning">{{ $entryStats['draft_entries'] }}</h5>
                    <p class="card-text small">قيود مسودة</p>
                </div>
            </div>
        </div>
        <div class="col-md-2 mb-3">
            <div class="card text-center">
                <div class="card-body">
                    <h5 class="card-title text-danger">{{ $entryStats['cancelled_entries'] }}</h5>
                    <p class="card-text small">قيود ملغية</p>
                </div>
            </div>
        </div>
        <div class="col-md-2 mb-3">
            <div class="card text-center">
                <div class="card-body">
                    <h5 class="card-title text-info">{{ number_format($entryStats['total_amount_posted']) }}</h5>
                    <p class="card-text small">إجمالي المبالغ المرحلة</p>
                </div>
            </div>
        </div>
        <div class="col-md-2 mb-3">
            <div class="card text-center">
                <div class="card-body">
                    <h5 class="card-title text-secondary">{{ $entryStats['entries_this_month'] }}</h5>
                    <p class="card-text small">قيود هذا الشهر</p>
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
            <form method="GET" action="{{ route('finance.journal-entries.index') }}">
                <div class="row">
                    <div class="col-md-3 mb-3">
                        <label for="search" class="form-label">البحث</label>
                        <input type="text" class="form-control" id="search" name="search" 
                               value="{{ request('search') }}" placeholder="رقم القيد أو الوصف">
                    </div>
                    <div class="col-md-2 mb-3">
                        <label for="status" class="form-label">الحالة</label>
                        <select class="form-select" id="status" name="status">
                            <option value="">جميع الحالات</option>
                            <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>مسودة</option>
                            <option value="posted" {{ request('status') == 'posted' ? 'selected' : '' }}>مرحل</option>
                            <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>ملغي</option>
                        </select>
                    </div>
                    <div class="col-md-2 mb-3">
                        <label for="date_from" class="form-label">من تاريخ</label>
                        <input type="date" class="form-control" id="date_from" name="date_from" 
                               value="{{ request('date_from') }}">
                    </div>
                    <div class="col-md-2 mb-3">
                        <label for="date_to" class="form-label">إلى تاريخ</label>
                        <input type="date" class="form-control" id="date_to" name="date_to" 
                               value="{{ request('date_to') }}">
                    </div>
                    <div class="col-md-3 mb-3 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary me-2">
                            <i class="fas fa-search me-1"></i>
                            بحث
                        </button>
                        <a href="{{ route('finance.journal-entries.index') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-times me-1"></i>
                            إلغاء
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- جدول القيود -->
    <div class="card">
        <div class="card-header">
            <h5 class="card-title mb-0">
                <i class="fas fa-table me-2"></i>
                قائمة القيود المحاسبية
                <span class="badge bg-primary ms-2">{{ $entries->total() }}</span>
            </h5>
        </div>
        <div class="card-body">
            @if($entries->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>رقم القيد</th>
                                <th>التاريخ</th>
                                <th>الوصف</th>
                                <th>إجمالي المبلغ</th>
                                <th>الحالة</th>
                                <th>المستخدم</th>
                                <th>تاريخ الترحيل</th>
                                <th>الإجراءات</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($entries as $entry)
                            <tr>
                                <td>
                                    <a href="{{ route('finance.journal-entries.show', $entry->id) }}" class="text-decoration-none">
                                        <code>{{ $entry->entry_number }}</code>
                                    </a>
                                </td>
                                <td>{{ $entry->entry_date->format('d/m/Y') }}</td>
                                <td>
                                    <div>
                                        {{ Str::limit($entry->description, 50) }}
                                        @if($entry->reference_type)
                                            <br><small class="text-muted">مرجع: {{ $entry->reference_type }}</small>
                                        @endif
                                    </div>
                                </td>
                                <td>
                                    <strong>{{ number_format($entry->total_amount, 2) }}</strong>
                                    <small class="text-muted">د.ع</small>
                                </td>
                                <td>
                                    @switch($entry->status)
                                        @case('posted')
                                            <span class="badge bg-success">مرحل</span>
                                            @break
                                        @case('draft')
                                            <span class="badge bg-warning">مسودة</span>
                                            @break
                                        @case('cancelled')
                                            <span class="badge bg-danger">ملغي</span>
                                            @break
                                    @endswitch
                                </td>
                                <td>{{ $entry->creator->name ?? 'غير محدد' }}</td>
                                <td>
                                    @if($entry->posted_at)
                                        {{ $entry->posted_at->format('d/m/Y H:i') }}
                                        <br><small class="text-muted">{{ $entry->poster->name ?? '' }}</small>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('finance.journal-entries.show', $entry->id) }}" 
                                           class="btn btn-sm btn-outline-info" title="عرض">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        @if($entry->status == 'draft')
                                            <a href="{{ route('finance.journal-entries.edit', $entry->id) }}" 
                                               class="btn btn-sm btn-outline-primary" title="تعديل">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form method="POST" action="{{ route('finance.journal-entries.post', $entry->id) }}" 
                                                  style="display: inline;">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-outline-success" 
                                                        title="ترحيل" onclick="return confirm('هل تريد ترحيل هذا القيد؟')">
                                                    <i class="fas fa-check"></i>
                                                </button>
                                            </form>
                                        @elseif($entry->status == 'posted')
                                            <form method="POST" action="{{ route('finance.journal-entries.unpost', $entry->id) }}" 
                                                  style="display: inline;">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-outline-warning" 
                                                        title="إلغاء ترحيل" onclick="return confirm('هل تريد إلغاء ترحيل هذا القيد؟')">
                                                    <i class="fas fa-undo"></i>
                                                </button>
                                            </form>
                                        @endif
                                        @if($entry->status != 'cancelled')
                                            <button type="button" class="btn btn-sm btn-outline-danger" 
                                                    onclick="confirmDelete({{ $entry->id }})" title="حذف">
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
                    {{ $entries->appends(request()->query())->links() }}
                </div>
            @else
                <div class="text-center py-5">
                    <i class="fas fa-book fa-3x text-muted mb-3"></i>
                    <h5 class="text-muted">لا توجد قيود محاسبية</h5>
                    <p class="text-muted">ابدأ بإنشاء أول قيد محاسبي في النظام</p>
                    <a href="{{ route('finance.journal-entries.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus me-2"></i>
                        إضافة قيد جديد
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
                هل أنت متأكد من حذف هذا القيد؟ هذا الإجراء لا يمكن التراجع عنه.
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
function confirmDelete(entryId) {
    const form = document.getElementById('deleteForm');
    form.action = `/finance/journal-entries/${entryId}`;
    
    const modal = new bootstrap.Modal(document.getElementById('deleteModal'));
    modal.show();
}
</script>
@endpush

@push('styles')
<style>
.table th {
    background-color: #f8f9fa;
    border-top: none;
}

code {
    background-color: #f8f9fa;
    padding: 2px 4px;
    border-radius: 3px;
    font-size: 0.875em;
}

.btn-group .btn {
    margin-right: 2px;
}

.btn-group .btn:last-child {
    margin-right: 0;
}
</style>
@endpush
