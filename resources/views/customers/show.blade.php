@extends('layouts.app')

@section('title', 'تفاصيل الزبون - ' . $customer->name)

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">{{ __('navigation.dashboard') }}</a></li>
    <li class="breadcrumb-item"><a href="{{ route('customers.index') }}">الزبائن</a></li>
    <li class="breadcrumb-item active">{{ $customer->name }}</li>
@endsection

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0">
                <i class="fas fa-user me-2"></i>
                {{ $customer->name }}
            </h1>
            <p class="text-muted">{{ $customer->customer_code }} - {{ $customer->business_name ?? 'زبون فردي' }}</p>
        </div>
        <div>
            <a href="{{ route('customers.edit', $customer->id) }}" class="btn btn-primary">
                <i class="fas fa-edit me-2"></i>
                تعديل البيانات
            </a>
            <a href="{{ route('customers.index') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left me-2"></i>
                العودة للقائمة
            </a>
        </div>
    </div>

    <div class="row">
        <!-- معلومات الزبون -->
        <div class="col-lg-4 mb-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-info-circle me-2"></i>
                        معلومات الزبون
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-sm-4"><strong>الكود:</strong></div>
                        <div class="col-sm-8">{{ $customer->customer_code }}</div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-sm-4"><strong>النوع:</strong></div>
                        <div class="col-sm-8">
                            @switch($customer->customer_type)
                                @case('retail')
                                    <span class="badge bg-info">تجزئة</span>
                                    @break
                                @case('wholesale')
                                    <span class="badge bg-success">جملة</span>
                                    @break
                                @case('pharmacy')
                                    <span class="badge bg-primary">صيدلية</span>
                                    @break
                            @endswitch
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-sm-4"><strong>الهاتف:</strong></div>
                        <div class="col-sm-8">{{ $customer->phone ?? 'غير محدد' }}</div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-sm-4"><strong>الموبايل:</strong></div>
                        <div class="col-sm-8">{{ $customer->mobile ?? 'غير محدد' }}</div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-sm-4"><strong>البريد:</strong></div>
                        <div class="col-sm-8">{{ $customer->email ?? 'غير محدد' }}</div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-sm-4"><strong>العنوان:</strong></div>
                        <div class="col-sm-8">{{ $customer->address ?? 'غير محدد' }}</div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-sm-4"><strong>المدينة:</strong></div>
                        <div class="col-sm-8">{{ $customer->city ?? 'غير محدد' }}</div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-sm-4"><strong>المنطقة:</strong></div>
                        <div class="col-sm-8">{{ $customer->area ?? 'غير محدد' }}</div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-sm-4"><strong>الحالة:</strong></div>
                        <div class="col-sm-8">
                            @switch($customer->status)
                                @case('active')
                                    <span class="badge bg-success">نشط</span>
                                    @break
                                @case('inactive')
                                    <span class="badge bg-warning">غير نشط</span>
                                    @break
                                @case('blocked')
                                    <span class="badge bg-danger">محظور</span>
                                    @break
                            @endswitch
                        </div>
                    </div>
                    @if($customer->notes)
                    <div class="row mb-3">
                        <div class="col-sm-4"><strong>ملاحظات:</strong></div>
                        <div class="col-sm-8">{{ $customer->notes }}</div>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- المعلومات المالية -->
        <div class="col-lg-8 mb-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-dollar-sign me-2"></i>
                        المعلومات المالية
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="card bg-primary text-white mb-3">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between">
                                        <div>
                                            <h4>{{ number_format($customer->current_balance) }} د.ع</h4>
                                            <p class="mb-0">الرصيد الحالي</p>
                                        </div>
                                        <div class="align-self-center">
                                            <i class="fas fa-balance-scale fa-2x"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card bg-success text-white mb-3">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between">
                                        <div>
                                            <h4>{{ number_format($customer->credit_limit) }} د.ع</h4>
                                            <p class="mb-0">سقف الدين</p>
                                        </div>
                                        <div class="align-self-center">
                                            <i class="fas fa-credit-card fa-2x"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card bg-info text-white mb-3">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between">
                                        <div>
                                            <h4>{{ number_format($customer->total_purchases) }} د.ع</h4>
                                            <p class="mb-0">إجمالي المشتريات</p>
                                        </div>
                                        <div class="align-self-center">
                                            <i class="fas fa-shopping-cart fa-2x"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card bg-warning text-white mb-3">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between">
                                        <div>
                                            <h4>{{ number_format($customer->total_payments) }} د.ع</h4>
                                            <p class="mb-0">إجمالي المدفوعات</p>
                                        </div>
                                        <div class="align-self-center">
                                            <i class="fas fa-hand-holding-usd fa-2x"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- نسبة استخدام سقف الدين -->
                    <div class="mb-3">
                        <label class="form-label">نسبة استخدام سقف الدين</label>
                        <div class="progress" style="height: 25px;">
                            @php
                                $utilization = $customer->getCreditUtilizationPercentage();
                                $progressClass = $utilization > 100 ? 'bg-danger' : ($utilization > 80 ? 'bg-warning' : 'bg-success');
                            @endphp
                            <div class="progress-bar {{ $progressClass }}" 
                                 role="progressbar" 
                                 style="width: {{ min($utilization, 100) }}%">
                                {{ number_format($utilization, 1) }}%
                            </div>
                        </div>
                        @if($utilization > 100)
                            <small class="text-danger">تجاوز سقف الدين بمقدار {{ number_format($customer->current_balance - $customer->credit_limit) }} د.ع</small>
                        @endif
                    </div>

                    <!-- معدلات شهرية -->
                    <div class="row">
                        <div class="col-md-6">
                            <div class="text-center">
                                <h5 class="text-primary">{{ number_format($customer->getMonthlyPurchaseAverage()) }} د.ع</h5>
                                <small class="text-muted">معدل المشتريات الشهري</small>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="text-center">
                                <h5 class="text-success">{{ number_format($customer->getMonthlyCollectionAverage()) }} د.ع</h5>
                                <small class="text-muted">معدل التحصيلات الشهري</small>
                            </div>
                        </div>
                    </div>

                    <!-- معلومات إضافية -->
                    <div class="row mt-3">
                        <div class="col-md-4">
                            <small class="text-muted">مدة السداد:</small>
                            <div>{{ $customer->payment_terms_days }} يوم</div>
                        </div>
                        <div class="col-md-4">
                            <small class="text-muted">آخر شراء:</small>
                            <div>{{ $customer->last_purchase_date ? $customer->last_purchase_date->format('d/m/Y') : 'لا يوجد' }}</div>
                        </div>
                        <div class="col-md-4">
                            <small class="text-muted">آخر دفعة:</small>
                            <div>{{ $customer->last_payment_date ? $customer->last_payment_date->format('d/m/Y') : 'لا يوجد' }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- إحصائيات سريعة -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card bg-light">
                <div class="card-body text-center">
                    <h5>إجمالي المعاملات</h5>
                    <h2 class="text-primary">{{ $customerStats['total_transactions'] ?? 0 }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-light">
                <div class="card-body text-center">
                    <h5>الفواتير غير المدفوعة</h5>
                    <h2 class="text-danger">{{ $customerStats['unpaid_invoices'] ?? 0 }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-light">
                <div class="card-body text-center">
                    <h5>إجمالي المرتجعات</h5>
                    <h2 class="text-warning">{{ number_format($customerStats['total_returns'] ?? 0) }} د.ع</h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-light">
                <div class="card-body text-center">
                    <h5>إجمالي المبيعات</h5>
                    <h2 class="text-success">{{ number_format($customerStats['total_sales'] ?? 0) }} د.ع</h2>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabs للحركات -->
    <div class="card">
        <div class="card-header">
            <ul class="nav nav-tabs card-header-tabs" id="customerTabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="transactions-tab" data-bs-toggle="tab" 
                            data-bs-target="#transactions" type="button" role="tab">
                        <i class="fas fa-list me-2"></i>
                        المعاملات الأخيرة
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="payments-tab" data-bs-toggle="tab" 
                            data-bs-target="#payments" type="button" role="tab">
                        <i class="fas fa-money-bill me-2"></i>
                        المدفوعات الأخيرة
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="unpaid-tab" data-bs-toggle="tab" 
                            data-bs-target="#unpaid" type="button" role="tab">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        الفواتير غير المدفوعة
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="monthly-tab" data-bs-toggle="tab" 
                            data-bs-target="#monthly" type="button" role="tab">
                        <i class="fas fa-chart-bar me-2"></i>
                        الإحصائيات الشهرية
                    </button>
                </li>
            </ul>
        </div>
        <div class="card-body">
            <div class="tab-content" id="customerTabsContent">
                <!-- المعاملات الأخيرة -->
                <div class="tab-pane fade show active" id="transactions" role="tabpanel">
                    @if($recentTransactions->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>رقم المرجع</th>
                                        <th>التاريخ</th>
                                        <th>النوع</th>
                                        <th>المبلغ</th>
                                        <th>حالة الدفع</th>
                                        <th>المتبقي</th>
                                        <th>الإجراءات</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($recentTransactions as $transaction)
                                    <tr>
                                        <td>{{ $transaction->reference_number }}</td>
                                        <td>{{ $transaction->transaction_date->format('d/m/Y') }}</td>
                                        <td>
                                            @if($transaction->transaction_type === 'sale')
                                                <span class="badge bg-success">مبيعات</span>
                                            @elseif($transaction->transaction_type === 'return')
                                                <span class="badge bg-warning">مرتجع</span>
                                            @else
                                                <span class="badge bg-info">تعديل</span>
                                            @endif
                                        </td>
                                        <td>{{ number_format($transaction->total_amount) }} د.ع</td>
                                        <td>
                                            @switch($transaction->payment_status)
                                                @case('paid')
                                                    <span class="badge bg-success">مدفوع</span>
                                                    @break
                                                @case('partial')
                                                    <span class="badge bg-warning">جزئي</span>
                                                    @break
                                                @case('unpaid')
                                                    <span class="badge bg-danger">غير مدفوع</span>
                                                    @break
                                            @endswitch
                                        </td>
                                        <td>{{ number_format($transaction->remaining_amount) }} د.ع</td>
                                        <td>
                                            <button class="btn btn-sm btn-outline-primary" 
                                                    onclick="viewTransaction({{ $transaction->id }})">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="fas fa-receipt fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">لا توجد معاملات</h5>
                        </div>
                    @endif
                </div>

                <!-- المدفوعات الأخيرة -->
                <div class="tab-pane fade" id="payments" role="tabpanel">
                    @if($recentPayments->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>رقم الدفعة</th>
                                        <th>التاريخ</th>
                                        <th>المبلغ</th>
                                        <th>طريقة الدفع</th>
                                        <th>رقم المرجع</th>
                                        <th>ملاحظات</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($recentPayments as $payment)
                                    <tr>
                                        <td>{{ $payment->payment_number }}</td>
                                        <td>{{ $payment->payment_date->format('d/m/Y') }}</td>
                                        <td>{{ number_format($payment->amount) }} د.ع</td>
                                        <td>
                                            @switch($payment->payment_method)
                                                @case('cash')
                                                    <span class="badge bg-success">نقد</span>
                                                    @break
                                                @case('bank_transfer')
                                                    <span class="badge bg-info">حوالة</span>
                                                    @break
                                                @case('check')
                                                    <span class="badge bg-warning">شيك</span>
                                                    @break
                                                @case('credit_card')
                                                    <span class="badge bg-primary">بطاقة</span>
                                                    @break
                                            @endswitch
                                        </td>
                                        <td>{{ $payment->reference_number ?? '-' }}</td>
                                        <td>{{ $payment->notes ?? '-' }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="fas fa-money-bill fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">لا توجد مدفوعات</h5>
                        </div>
                    @endif
                </div>

                <!-- الفواتير غير المدفوعة -->
                <div class="tab-pane fade" id="unpaid" role="tabpanel">
                    @if($unpaidTransactions->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>رقم المرجع</th>
                                        <th>تاريخ الفاتورة</th>
                                        <th>تاريخ الاستحقاق</th>
                                        <th>المبلغ الإجمالي</th>
                                        <th>المدفوع</th>
                                        <th>المتبقي</th>
                                        <th>أيام التأخير</th>
                                        <th>الإجراءات</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($unpaidTransactions as $transaction)
                                    @php
                                        $daysOverdue = $transaction->due_date ? now()->diffInDays($transaction->due_date, false) : 0;
                                    @endphp
                                    <tr class="{{ $daysOverdue > 0 ? 'table-danger' : '' }}">
                                        <td>{{ $transaction->reference_number }}</td>
                                        <td>{{ $transaction->transaction_date->format('d/m/Y') }}</td>
                                        <td>{{ $transaction->due_date ? $transaction->due_date->format('d/m/Y') : '-' }}</td>
                                        <td>{{ number_format($transaction->total_amount) }} د.ع</td>
                                        <td>{{ number_format($transaction->paid_amount) }} د.ع</td>
                                        <td>{{ number_format($transaction->remaining_amount) }} د.ع</td>
                                        <td>
                                            @if($daysOverdue > 0)
                                                <span class="badge bg-danger">{{ $daysOverdue }} يوم</span>
                                            @else
                                                <span class="badge bg-success">في الموعد</span>
                                            @endif
                                        </td>
                                        <td>
                                            <button class="btn btn-sm btn-success" 
                                                    onclick="addPayment({{ $transaction->id }})">
                                                <i class="fas fa-plus"></i>
                                                دفعة
                                            </button>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="fas fa-check-circle fa-3x text-success mb-3"></i>
                            <h5 class="text-success">جميع الفواتير مدفوعة</h5>
                        </div>
                    @endif
                </div>

                <!-- الإحصائيات الشهرية -->
                <div class="tab-pane fade" id="monthly" role="tabpanel">
                    <div class="row">
                        <div class="col-12">
                            <canvas id="monthlyChart" width="400" height="200"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// رسم بياني للإحصائيات الشهرية
const monthlyData = @json($monthlyStats);
const ctx = document.getElementById('monthlyChart').getContext('2d');

new Chart(ctx, {
    type: 'line',
    data: {
        labels: monthlyData.map(item => item.month_name),
        datasets: [{
            label: 'المبيعات',
            data: monthlyData.map(item => item.sales),
            borderColor: 'rgb(75, 192, 192)',
            backgroundColor: 'rgba(75, 192, 192, 0.2)',
            tension: 0.1
        }, {
            label: 'المدفوعات',
            data: monthlyData.map(item => item.payments),
            borderColor: 'rgb(255, 99, 132)',
            backgroundColor: 'rgba(255, 99, 132, 0.2)',
            tension: 0.1
        }]
    },
    options: {
        responsive: true,
        plugins: {
            title: {
                display: true,
                text: 'المبيعات والمدفوعات الشهرية'
            }
        },
        scales: {
            y: {
                beginAtZero: true
            }
        }
    }
});

function viewTransaction(id) {
    // عرض تفاصيل المعاملة
    console.log('View transaction:', id);
}

function addPayment(transactionId) {
    // إضافة دفعة للمعاملة
    window.location.href = `{{ route('customers.payments.create', $customer->id) }}?transaction_id=${transactionId}`;
}
</script>
@endpush
