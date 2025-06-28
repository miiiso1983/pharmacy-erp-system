@extends('layouts.app')

@section('title', 'عرض القيد المحاسبي - ' . __('messages.system_name'))

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">{{ __('navigation.dashboard') }}</a></li>
    <li class="breadcrumb-item"><a href="{{ route('finance.dashboard') }}">النظام المالي</a></li>
    <li class="breadcrumb-item"><a href="{{ route('finance.journal-entries.index') }}">القيود المحاسبية</a></li>
    <li class="breadcrumb-item active">القيد {{ $entry->entry_number }}</li>
@endsection

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0">
                <i class="fas fa-eye me-2"></i>
                القيد المحاسبي: {{ $entry->entry_number }}
            </h1>
            <p class="text-muted">عرض تفاصيل القيد المحاسبي</p>
        </div>
        <div>
            @if($entry->status == 'draft')
                <a href="{{ route('finance.journal-entries.edit', $entry->id) }}" class="btn btn-primary me-2">
                    <i class="fas fa-edit me-2"></i>
                    تعديل
                </a>
                <form method="POST" action="{{ route('finance.journal-entries.post', $entry->id) }}" style="display: inline;">
                    @csrf
                    <button type="submit" class="btn btn-success me-2" onclick="return confirm('هل تريد ترحيل هذا القيد؟')">
                        <i class="fas fa-check me-2"></i>
                        ترحيل
                    </button>
                </form>
            @elseif($entry->status == 'posted')
                <form method="POST" action="{{ route('finance.journal-entries.unpost', $entry->id) }}" style="display: inline;">
                    @csrf
                    <button type="submit" class="btn btn-warning me-2" onclick="return confirm('هل تريد إلغاء ترحيل هذا القيد؟')">
                        <i class="fas fa-undo me-2"></i>
                        إلغاء ترحيل
                    </button>
                </form>
            @endif
            <button class="btn btn-outline-primary me-2" onclick="window.print()">
                <i class="fas fa-print me-2"></i>
                طباعة
            </button>
            <a href="{{ route('finance.journal-entries.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left me-2"></i>
                العودة
            </a>
        </div>
    </div>

    <div class="row">
        <!-- معلومات القيد الأساسية -->
        <div class="col-lg-8 mb-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-info-circle me-2"></i>
                        معلومات القيد
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">رقم القيد:</label>
                            <p class="mb-0"><code>{{ $entry->entry_number }}</code></p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">تاريخ القيد:</label>
                            <p class="mb-0">{{ $entry->entry_date->format('d/m/Y') }}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">الحالة:</label>
                            <p class="mb-0">
                                @switch($entry->status)
                                    @case('posted')
                                        <span class="badge bg-success fs-6">مرحل</span>
                                        @break
                                    @case('draft')
                                        <span class="badge bg-warning fs-6">مسودة</span>
                                        @break
                                    @case('cancelled')
                                        <span class="badge bg-danger fs-6">ملغي</span>
                                        @break
                                @endswitch
                            </p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">إجمالي المبلغ:</label>
                            <p class="mb-0"><strong>{{ number_format($entry->total_amount, 2) }} د.ع</strong></p>
                        </div>
                        @if($entry->reference_type)
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">نوع المرجع:</label>
                            <p class="mb-0">{{ $entry->reference_type }}</p>
                        </div>
                        @endif
                        @if($entry->reference_id)
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">رقم المرجع:</label>
                            <p class="mb-0">{{ $entry->reference_id }}</p>
                        </div>
                        @endif
                        <div class="col-12 mb-3">
                            <label class="form-label fw-bold">وصف القيد:</label>
                            <p class="mb-0">{{ $entry->description }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- معلومات إضافية -->
        <div class="col-lg-4 mb-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-user me-2"></i>
                        معلومات المستخدم
                    </h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label fw-bold">أنشأ بواسطة:</label>
                        <p class="mb-0">{{ $entry->creator->name ?? 'غير محدد' }}</p>
                        <small class="text-muted">{{ $entry->created_at->format('d/m/Y H:i') }}</small>
                    </div>
                    @if($entry->posted_at)
                    <div class="mb-3">
                        <label class="form-label fw-bold">رحل بواسطة:</label>
                        <p class="mb-0">{{ $entry->poster->name ?? 'غير محدد' }}</p>
                        <small class="text-muted">{{ $entry->posted_at->format('d/m/Y H:i') }}</small>
                    </div>
                    @endif
                    <div class="mb-3">
                        <label class="form-label fw-bold">آخر تحديث:</label>
                        <p class="mb-0">{{ $entry->updated_at->format('d/m/Y H:i') }}</p>
                    </div>
                </div>
            </div>

            <!-- حالة التوازن -->
            <div class="card mt-3">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-balance-scale me-2"></i>
                        حالة التوازن
                    </h5>
                </div>
                <div class="card-body">
                    @php
                        $totalDebits = $entry->details->sum('debit_amount');
                        $totalCredits = $entry->details->sum('credit_amount');
                        $isBalanced = abs($totalDebits - $totalCredits) < 0.01;
                    @endphp
                    
                    <div class="row">
                        <div class="col-6">
                            <strong>إجمالي المدين:</strong>
                        </div>
                        <div class="col-6 text-end">
                            {{ number_format($totalDebits, 2) }} د.ع
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-6">
                            <strong>إجمالي الدائن:</strong>
                        </div>
                        <div class="col-6 text-end">
                            {{ number_format($totalCredits, 2) }} د.ع
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-6">
                            <strong>الفرق:</strong>
                        </div>
                        <div class="col-6 text-end">
                            <span class="{{ $isBalanced ? 'text-success' : 'text-danger' }}">
                                {{ number_format($totalDebits - $totalCredits, 2) }} د.ع
                            </span>
                        </div>
                    </div>
                    
                    @if($isBalanced)
                        <div class="alert alert-success mt-2 mb-0">
                            <i class="fas fa-check-circle me-2"></i>
                            القيد متوازن
                        </div>
                    @else
                        <div class="alert alert-danger mt-2 mb-0">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            القيد غير متوازن
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- تفاصيل القيد -->
    <div class="card">
        <div class="card-header">
            <h5 class="card-title mb-0">
                <i class="fas fa-list me-2"></i>
                تفاصيل القيد
                <span class="badge bg-primary ms-2">{{ $entry->details->count() }} سطر</span>
            </h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead class="table-light">
                        <tr>
                            <th width="5%">#</th>
                            <th width="15%">رمز الحساب</th>
                            <th width="30%">اسم الحساب</th>
                            <th width="25%">الوصف</th>
                            <th width="12.5%">مدين</th>
                            <th width="12.5%">دائن</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($entry->details as $index => $detail)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>
                                <code>{{ $detail->account->account_code }}</code>
                            </td>
                            <td>
                                <div>
                                    <strong>{{ $detail->account->account_name }}</strong>
                                    @if($detail->account->account_name_en)
                                        <br><small class="text-muted">{{ $detail->account->account_name_en }}</small>
                                    @endif
                                </div>
                            </td>
                            <td>{{ $detail->description ?? '-' }}</td>
                            <td class="text-end">
                                @if($detail->debit_amount > 0)
                                    <strong>{{ number_format($detail->debit_amount, 2) }}</strong>
                                @else
                                    -
                                @endif
                            </td>
                            <td class="text-end">
                                @if($detail->credit_amount > 0)
                                    <strong>{{ number_format($detail->credit_amount, 2) }}</strong>
                                @else
                                    -
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot class="table-secondary">
                        <tr>
                            <th colspan="4">الإجمالي</th>
                            <th class="text-end">{{ number_format($totalDebits, 2) }}</th>
                            <th class="text-end">{{ number_format($totalCredits, 2) }}</th>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
@media print {
    .btn, .card-header, .breadcrumb, nav {
        display: none !important;
    }
    
    .card {
        border: none !important;
        box-shadow: none !important;
    }
    
    .table {
        font-size: 12px;
    }
    
    .badge {
        background-color: #6c757d !important;
        color: white !important;
    }
}

.table th {
    background-color: #f8f9fa;
    border-top: none;
}

.table-bordered th,
.table-bordered td {
    border: 1px solid #dee2e6;
}

.text-end {
    text-align: end !important;
}

code {
    background-color: #f8f9fa;
    padding: 2px 4px;
    border-radius: 3px;
    font-size: 0.875em;
}

.fw-bold {
    font-weight: 600 !important;
}
</style>
@endpush
