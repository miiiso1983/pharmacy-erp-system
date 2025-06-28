@extends('layouts.app')

@section('title', 'تفاصيل الحساب - ' . $account->account_name)

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="h3 mb-0">
                        <i class="fas fa-file-invoice-dollar text-primary me-2"></i>
                        تفاصيل الحساب
                    </h1>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('finance.index') }}">المالية</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('finance.accounts.index') }}">الحسابات</a></li>
                            <li class="breadcrumb-item active">{{ $account->account_name }}</li>
                        </ol>
                    </nav>
                </div>
                <div>
                    <a href="{{ route('finance.accounts.edit', $account) }}" class="btn btn-warning">
                        <i class="fas fa-edit me-2"></i>
                        تعديل
                    </a>
                    <a href="{{ route('finance.accounts.index') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-arrow-right me-2"></i>
                        العودة
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- معلومات الحساب -->
    <div class="row mb-4">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-info-circle me-2"></i>
                        معلومات الحساب
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr>
                                    <td><strong>رمز الحساب:</strong></td>
                                    <td>{{ $account->account_code }}</td>
                                </tr>
                                <tr>
                                    <td><strong>اسم الحساب:</strong></td>
                                    <td>{{ $account->account_name }}</td>
                                </tr>
                                @if($account->account_name_en)
                                <tr>
                                    <td><strong>الاسم بالإنجليزية:</strong></td>
                                    <td>{{ $account->account_name_en }}</td>
                                </tr>
                                @endif
                                <tr>
                                    <td><strong>نوع الحساب:</strong></td>
                                    <td>
                                        <span class="badge bg-{{ $account->account_type === 'asset' ? 'success' : ($account->account_type === 'liability' ? 'warning' : ($account->account_type === 'equity' ? 'info' : ($account->account_type === 'revenue' ? 'primary' : 'secondary'))) }}">
                                            {{ trans('finance.account_types.' . $account->account_type) }}
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>فئة الحساب:</strong></td>
                                    <td>{{ $account->account_category }}</td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr>
                                    <td><strong>الحساب الأب:</strong></td>
                                    <td>
                                        @if($account->parentAccount)
                                            <a href="{{ route('finance.accounts.show', $account->parentAccount) }}">
                                                {{ $account->parentAccount->account_name }}
                                            </a>
                                        @else
                                            <span class="text-muted">حساب رئيسي</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>مستوى الحساب:</strong></td>
                                    <td>{{ $account->account_level }}</td>
                                </tr>
                                <tr>
                                    <td><strong>نوع الرصيد:</strong></td>
                                    <td>
                                        <span class="badge bg-{{ $account->balance_type === 'debit' ? 'success' : 'danger' }}">
                                            {{ $account->balance_type === 'debit' ? 'مدين' : 'دائن' }}
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>الحالة:</strong></td>
                                    <td>
                                        <span class="badge bg-{{ $account->is_active ? 'success' : 'secondary' }}">
                                            {{ $account->is_active ? 'نشط' : 'غير نشط' }}
                                        </span>
                                    </td>
                                </tr>
                                @if($account->is_system_account)
                                <tr>
                                    <td><strong>حساب النظام:</strong></td>
                                    <td>
                                        <span class="badge bg-info">
                                            <i class="fas fa-shield-alt me-1"></i>
                                            حساب نظام
                                        </span>
                                    </td>
                                </tr>
                                @endif
                            </table>
                        </div>
                    </div>
                    
                    @if($account->description)
                    <div class="row mt-3">
                        <div class="col-12">
                            <h6>الوصف:</h6>
                            <p class="text-muted">{{ $account->description }}</p>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- إحصائيات الحساب -->
        <div class="col-lg-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-chart-bar me-2"></i>
                        إحصائيات الحساب
                    </h5>
                </div>
                <div class="card-body">
                    <div class="stat-item">
                        <div class="stat-label">الرصيد الافتتاحي</div>
                        <div class="stat-value text-info">
                            {{ number_format($accountStats['opening_balance'], 0) }} د.ع
                        </div>
                    </div>
                    
                    <div class="stat-item">
                        <div class="stat-label">الرصيد الحالي</div>
                        <div class="stat-value text-{{ $accountStats['current_balance'] >= 0 ? 'success' : 'danger' }}">
                            {{ number_format($accountStats['current_balance'], 0) }} د.ع
                        </div>
                    </div>
                    
                    <div class="stat-item">
                        <div class="stat-label">إجمالي المدين</div>
                        <div class="stat-value text-success">
                            {{ number_format($accountStats['total_debits'], 0) }} د.ع
                        </div>
                    </div>
                    
                    <div class="stat-item">
                        <div class="stat-label">إجمالي الدائن</div>
                        <div class="stat-value text-danger">
                            {{ number_format($accountStats['total_credits'], 0) }} د.ع
                        </div>
                    </div>
                    
                    <div class="stat-item">
                        <div class="stat-label">عدد المعاملات</div>
                        <div class="stat-value text-primary">
                            {{ number_format($accountStats['transactions_count']) }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- الحسابات الفرعية -->
    @if($account->childAccounts->count() > 0)
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-sitemap me-2"></i>
                        الحسابات الفرعية
                    </h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>رمز الحساب</th>
                                    <th>اسم الحساب</th>
                                    <th>نوع الحساب</th>
                                    <th>الرصيد الحالي</th>
                                    <th>الحالة</th>
                                    <th>الإجراءات</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($account->childAccounts as $childAccount)
                                <tr>
                                    <td>{{ $childAccount->account_code }}</td>
                                    <td>{{ $childAccount->account_name }}</td>
                                    <td>
                                        <span class="badge bg-secondary">
                                            {{ trans('finance.account_types.' . $childAccount->account_type) }}
                                        </span>
                                    </td>
                                    <td class="text-{{ $childAccount->current_balance >= 0 ? 'success' : 'danger' }}">
                                        {{ number_format($childAccount->current_balance, 0) }} د.ع
                                    </td>
                                    <td>
                                        <span class="badge bg-{{ $childAccount->is_active ? 'success' : 'secondary' }}">
                                            {{ $childAccount->is_active ? 'نشط' : 'غير نشط' }}
                                        </span>
                                    </td>
                                    <td>
                                        <a href="{{ route('finance.accounts.show', $childAccount) }}" class="btn btn-sm btn-outline-primary">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- آخر المعاملات -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-history me-2"></i>
                        آخر المعاملات
                    </h5>
                    <a href="{{ route('finance.reports.account-ledger', ['account_id' => $account->id]) }}" class="btn btn-sm btn-outline-primary">
                        <i class="fas fa-book me-1"></i>
                        دفتر الأستاذ الكامل
                    </a>
                </div>
                <div class="card-body">
                    @if($recentTransactions->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>التاريخ</th>
                                    <th>رقم القيد</th>
                                    <th>الوصف</th>
                                    <th>مدين</th>
                                    <th>دائن</th>
                                    <th>الحالة</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($recentTransactions as $transaction)
                                <tr>
                                    <td>{{ $transaction->journalEntry->entry_date->format('Y-m-d') }}</td>
                                    <td>
                                        <a href="{{ route('finance.journal-entries.show', $transaction->journalEntry) }}">
                                            #{{ $transaction->journalEntry->entry_number }}
                                        </a>
                                    </td>
                                    <td>{{ $transaction->description ?: $transaction->journalEntry->description }}</td>
                                    <td class="text-success">
                                        @if($transaction->debit_amount > 0)
                                            {{ number_format($transaction->debit_amount, 0) }} د.ع
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td class="text-danger">
                                        @if($transaction->credit_amount > 0)
                                            {{ number_format($transaction->credit_amount, 0) }} د.ع
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td>
                                        <span class="badge bg-{{ $transaction->journalEntry->status === 'posted' ? 'success' : 'warning' }}">
                                            {{ $transaction->journalEntry->status === 'posted' ? 'مرحل' : 'مسودة' }}
                                        </span>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @else
                    <div class="text-center py-4">
                        <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                        <p class="text-muted">لا توجد معاملات على هذا الحساب</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .stat-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 10px 0;
        border-bottom: 1px solid #f1f3f4;
    }
    
    .stat-item:last-child {
        border-bottom: none;
    }
    
    .stat-label {
        font-weight: 500;
        color: #6c757d;
    }
    
    .stat-value {
        font-weight: 600;
        font-size: 1.1rem;
    }
    
    .card {
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        border: none;
        border-radius: 10px;
    }
    
    .card-header {
        background: #f8f9fa;
        border-bottom: 1px solid #e9ecef;
        border-radius: 10px 10px 0 0 !important;
    }
    
    .table th {
        border-top: none;
        font-weight: 600;
        color: #495057;
    }
    
    .badge {
        font-size: 0.8rem;
    }
</style>
@endpush
