@extends('layouts.app')

@section('title', 'تفاصيل التحصيل - ' . $collection->collection_number)

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">الرئيسية</a></li>
    <li class="breadcrumb-item"><a href="{{ route('collections.index') }}">التحصيلات</a></li>
    <li class="breadcrumb-item active">{{ $collection->collection_number }}</li>
@endsection

@section('content')
<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>
                <i class="fas fa-money-bill-wave me-2"></i>
                تفاصيل التحصيل: {{ $collection->collection_number }}
            </h2>
            <div>
                <a href="{{ route('collections.document', $collection->id) }}" 
                   class="btn btn-success me-2" target="_blank">
                    <i class="fas fa-download me-2"></i>
                    تحميل المستند
                </a>
                <a href="{{ route('collections.index') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-right me-2"></i>
                    العودة للقائمة
                </a>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- معلومات التحصيل -->
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-info-circle me-2"></i>
                    معلومات التحصيل
                </h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label fw-bold">رقم السند:</label>
                            <p class="mb-0">{{ $collection->collection_number }}</p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label fw-bold">تاريخ التحصيل:</label>
                            <p class="mb-0">{{ $collection->collection_date->format('Y/m/d') }}</p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label fw-bold">المبلغ:</label>
                            <p class="mb-0 text-success fs-5 fw-bold">{{ number_format($collection->amount, 0) }} دينار عراقي</p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label fw-bold">طريقة الدفع:</label>
                            <p class="mb-0">
                                @switch($collection->payment_method)
                                    @case('cash')
                                        <span class="badge bg-success">نقداً</span>
                                        @break
                                    @case('bank_transfer')
                                        <span class="badge bg-primary">تحويل بنكي</span>
                                        @break
                                    @case('check')
                                        <span class="badge bg-warning">شيك</span>
                                        @break
                                    @case('credit_card')
                                        <span class="badge bg-info">بطاقة ائتمان</span>
                                        @break
                                    @default
                                        <span class="badge bg-secondary">{{ $collection->payment_method }}</span>
                                @endswitch
                            </p>
                        </div>
                    </div>
                    @if($collection->reference_number)
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label fw-bold">رقم المرجع:</label>
                            <p class="mb-0">{{ $collection->reference_number }}</p>
                        </div>
                    </div>
                    @endif
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label fw-bold">الحالة:</label>
                            <p class="mb-0">
                                @switch($collection->status)
                                    @case('completed')
                                        <span class="badge bg-success">مكتمل</span>
                                        @break
                                    @case('pending')
                                        <span class="badge bg-warning">معلق</span>
                                        @break
                                    @case('cancelled')
                                        <span class="badge bg-danger">ملغي</span>
                                        @break
                                    @default
                                        <span class="badge bg-secondary">{{ $collection->status }}</span>
                                @endswitch
                            </p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label fw-bold">تم بواسطة:</label>
                            <p class="mb-0">{{ $collection->collectedBy ? $collection->collectedBy->name : 'غير محدد' }}</p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label fw-bold">تاريخ الإنشاء:</label>
                            <p class="mb-0">{{ $collection->created_at->format('Y/m/d H:i') }}</p>
                        </div>
                    </div>
                    @if($collection->notes)
                    <div class="col-12">
                        <div class="mb-3">
                            <label class="form-label fw-bold">ملاحظات:</label>
                            <p class="mb-0">{{ $collection->notes }}</p>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- معلومات العميل -->
    <div class="col-lg-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-user me-2"></i>
                    معلومات العميل
                </h5>
            </div>
            <div class="card-body">
                @if($collection->customer)
                    <div class="mb-3">
                        <label class="form-label fw-bold">الاسم:</label>
                        <p class="mb-0">{{ $collection->customer->name }}</p>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">رمز العميل:</label>
                        <p class="mb-0">{{ $collection->customer->customer_code }}</p>
                    </div>
                    @if($collection->customer->phone)
                    <div class="mb-3">
                        <label class="form-label fw-bold">رقم الهاتف:</label>
                        <p class="mb-0">{{ $collection->customer->phone }}</p>
                    </div>
                    @endif
                    @if($collection->customer->address)
                    <div class="mb-3">
                        <label class="form-label fw-bold">العنوان:</label>
                        <p class="mb-0">{{ $collection->customer->address }}</p>
                    </div>
                    @endif
                    @if($collection->customer->city)
                    <div class="mb-3">
                        <label class="form-label fw-bold">المدينة:</label>
                        <p class="mb-0">{{ $collection->customer->city }}</p>
                    </div>
                    @endif
                @else
                    <p class="text-muted">لا توجد معلومات عميل</p>
                @endif
            </div>
        </div>

        <!-- معلومات الفاتورة -->
        @if($collection->invoice)
        <div class="card mt-3">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-file-invoice me-2"></i>
                    الفاتورة المرتبطة
                </h5>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <label class="form-label fw-bold">رقم الفاتورة:</label>
                    <p class="mb-0">
                        <a href="{{ route('invoices.show', $collection->invoice->id) }}" class="text-decoration-none">
                            {{ $collection->invoice->invoice_number }}
                        </a>
                    </p>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-bold">إجمالي الفاتورة:</label>
                    <p class="mb-0">{{ number_format($collection->invoice->total_amount, 0) }} د.ع</p>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-bold">المبلغ المدفوع:</label>
                    <p class="mb-0">{{ number_format($collection->invoice->paid_amount, 0) }} د.ع</p>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-bold">المبلغ المتبقي:</label>
                    <p class="mb-0 text-danger">{{ number_format($collection->invoice->total_amount - $collection->invoice->paid_amount, 0) }} د.ع</p>
                </div>
            </div>
        </div>
        @endif
    </div>
</div>
@endsection

@push('styles')
<style>
    .card {
        box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
        border: 1px solid rgba(0, 0, 0, 0.125);
    }
    
    .card-header {
        background-color: #f8f9fa;
        border-bottom: 1px solid rgba(0, 0, 0, 0.125);
    }
    
    .form-label.fw-bold {
        color: #495057;
        font-size: 0.9rem;
    }
</style>
@endpush
