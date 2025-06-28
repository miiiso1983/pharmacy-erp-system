@extends('layouts.app')

@section('title', 'المنتجات الدوائية - ' . __('messages.system_name'))

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">{{ __('navigation.dashboard') }}</a></li>
    <li class="breadcrumb-item"><a href="{{ route('regulatory-affairs.dashboard') }}">الشؤون التنظيمية</a></li>
    <li class="breadcrumb-item active">المنتجات الدوائية</li>
@endsection

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0">
                <i class="fas fa-pills me-2"></i>
                المنتجات الدوائية
            </h1>
            <p class="text-muted">تسجيل وإدارة المنتجات الدوائية والأجهزة الطبية</p>
        </div>
        <div>
            <a href="{{ route('regulatory-affairs.products.create') }}" class="btn btn-primary">
                <i class="fas fa-plus me-2"></i>
                إضافة منتج جديد
            </a>
        </div>
    </div>

    <!-- إحصائيات سريعة -->
    <div class="row mb-4">
        <div class="col-lg-2 col-md-4 col-sm-6 mb-3">
            <div class="card bg-primary text-white">
                <div class="card-body text-center">
                    <h4 class="mb-1">{{ number_format($productStats['total_products']) }}</h4>
                    <small>إجمالي المنتجات</small>
                </div>
            </div>
        </div>
        <div class="col-lg-2 col-md-4 col-sm-6 mb-3">
            <div class="card bg-success text-white">
                <div class="card-body text-center">
                    <h4 class="mb-1">{{ number_format($productStats['registered_products']) }}</h4>
                    <small>منتجات مسجلة</small>
                </div>
            </div>
        </div>
        <div class="col-lg-2 col-md-4 col-sm-6 mb-3">
            <div class="card bg-info text-white">
                <div class="card-body text-center">
                    <h4 class="mb-1">{{ number_format($productStats['medicines']) }}</h4>
                    <small>أدوية</small>
                </div>
            </div>
        </div>
        <div class="col-lg-2 col-md-4 col-sm-6 mb-3">
            <div class="card bg-warning text-white">
                <div class="card-body text-center">
                    <h4 class="mb-1">{{ number_format($productStats['medical_devices']) }}</h4>
                    <small>أجهزة طبية</small>
                </div>
            </div>
        </div>
        <div class="col-lg-2 col-md-4 col-sm-6 mb-3">
            <div class="card bg-secondary text-white">
                <div class="card-body text-center">
                    <h4 class="mb-1">{{ number_format($productStats['supplements']) }}</h4>
                    <small>مكملات غذائية</small>
                </div>
            </div>
        </div>
        <div class="col-lg-2 col-md-4 col-sm-6 mb-3">
            <div class="card bg-danger text-white">
                <div class="card-body text-center">
                    <h4 class="mb-1">{{ number_format($productStats['expired_products']) }}</h4>
                    <small>منتهية الصلاحية</small>
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
            <form method="GET" action="{{ route('regulatory-affairs.products') }}">
                <div class="row">
                    <div class="col-md-3 mb-3">
                        <label for="search" class="form-label">البحث</label>
                        <input type="text" class="form-control" id="search" name="search" 
                               value="{{ request('search') }}" 
                               placeholder="اسم المنتج، الرمز، أو رقم التسجيل">
                    </div>
                    <div class="col-md-2 mb-3">
                        <label for="product_type" class="form-label">نوع المنتج</label>
                        <select class="form-select" id="product_type" name="product_type">
                            <option value="">جميع الأنواع</option>
                            <option value="medicine" {{ request('product_type') == 'medicine' ? 'selected' : '' }}>دواء</option>
                            <option value="medical_device" {{ request('product_type') == 'medical_device' ? 'selected' : '' }}>جهاز طبي</option>
                            <option value="supplement" {{ request('product_type') == 'supplement' ? 'selected' : '' }}>مكمل غذائي</option>
                            <option value="cosmetic" {{ request('product_type') == 'cosmetic' ? 'selected' : '' }}>مستحضر تجميل</option>
                            <option value="veterinary" {{ request('product_type') == 'veterinary' ? 'selected' : '' }}>بيطري</option>
                        </select>
                    </div>
                    <div class="col-md-2 mb-3">
                        <label for="status" class="form-label">الحالة</label>
                        <select class="form-select" id="status" name="status">
                            <option value="">جميع الحالات</option>
                            <option value="registered" {{ request('status') == 'registered' ? 'selected' : '' }}>مسجل</option>
                            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>معلق</option>
                            <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>مرفوض</option>
                            <option value="expired" {{ request('status') == 'expired' ? 'selected' : '' }}>منتهي الصلاحية</option>
                            <option value="suspended" {{ request('status') == 'suspended' ? 'selected' : '' }}>معلق</option>
                        </select>
                    </div>
                    <div class="col-md-2 mb-3">
                        <label for="company_id" class="form-label">الشركة</label>
                        <select class="form-select" id="company_id" name="company_id">
                            <option value="">جميع الشركات</option>
                            @foreach($companies as $company)
                                <option value="{{ $company->id }}" {{ request('company_id') == $company->id ? 'selected' : '' }}>
                                    {{ $company->company_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3 mb-3 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary me-2">
                            <i class="fas fa-search me-2"></i>
                            بحث
                        </button>
                        <a href="{{ route('regulatory-affairs.products') }}" class="btn btn-secondary">
                            <i class="fas fa-times me-2"></i>
                            إعادة تعيين
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- جدول المنتجات -->
    <div class="card">
        <div class="card-header">
            <h5 class="card-title mb-0">
                <i class="fas fa-list me-2"></i>
                قائمة المنتجات
                <span class="badge bg-primary ms-2">{{ $products->total() }}</span>
            </h5>
        </div>
        <div class="card-body">
            @if($products->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>رمز المنتج</th>
                            <th>اسم المنتج</th>
                            <th>الاسم العلمي</th>
                            <th>الشركة</th>
                            <th>نوع المنتج</th>
                            <th>التركيز</th>
                            <th>تاريخ التسجيل</th>
                            <th>تاريخ الانتهاء</th>
                            <th>الحالة</th>
                            <th>الإجراءات</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($products as $product)
                        <tr>
                            <td>
                                <code>{{ $product->product_code }}</code>
                            </td>
                            <td>
                                <div>
                                    <strong>{{ $product->product_name }}</strong>
                                    @if($product->brand_name)
                                        <br><small class="text-muted">{{ $product->brand_name }}</small>
                                    @endif
                                </div>
                            </td>
                            <td>{{ $product->generic_name }}</td>
                            <td>
                                <div>
                                    <strong>{{ $product->company->company_name }}</strong>
                                    <br><small class="text-muted">{{ $product->company->country }}</small>
                                </div>
                            </td>
                            <td>
                                <span class="badge bg-info">
                                    @switch($product->product_type)
                                        @case('medicine') دواء @break
                                        @case('medical_device') جهاز طبي @break
                                        @case('supplement') مكمل غذائي @break
                                        @case('cosmetic') مستحضر تجميل @break
                                        @case('veterinary') بيطري @break
                                        @default {{ $product->product_type }}
                                    @endswitch
                                </span>
                                <br>
                                <small class="text-muted">
                                    @switch($product->dosage_form)
                                        @case('tablet') أقراص @break
                                        @case('capsule') كبسولات @break
                                        @case('syrup') شراب @break
                                        @case('injection') حقن @break
                                        @case('cream') كريم @break
                                        @case('ointment') مرهم @break
                                        @case('drops') قطرة @break
                                        @case('inhaler') بخاخ @break
                                        @default {{ $product->dosage_form }}
                                    @endswitch
                                </small>
                            </td>
                            <td>
                                @if($product->strength)
                                    <strong>{{ $product->strength }}</strong>
                                    @if($product->pack_size)
                                        <br><small class="text-muted">{{ $product->pack_size }}</small>
                                    @endif
                                @else
                                    <span class="text-muted">غير محدد</span>
                                @endif
                            </td>
                            <td>{{ $product->registration_date->format('d/m/Y') }}</td>
                            <td>
                                @if($product->expiry_date)
                                    <span class="badge bg-{{ $product->getAlertStatus() === 'expired' ? 'danger' : ($product->getAlertStatus() === 'critical' ? 'warning' : 'success') }}">
                                        {{ $product->expiry_date->format('d/m/Y') }}
                                    </span>
                                    @if($product->getDaysUntilExpiry() !== null)
                                        <br><small class="text-muted">
                                            @if($product->getDaysUntilExpiry() < 0)
                                                منتهي منذ {{ abs($product->getDaysUntilExpiry()) }} يوم
                                            @else
                                                باقي {{ $product->getDaysUntilExpiry() }} يوم
                                            @endif
                                        </small>
                                    @endif
                                @else
                                    <span class="text-muted">غير محدد</span>
                                @endif
                            </td>
                            <td>
                                @switch($product->status)
                                    @case('registered')
                                        <span class="badge bg-success">مسجل</span>
                                        @break
                                    @case('pending')
                                        <span class="badge bg-warning">معلق</span>
                                        @break
                                    @case('rejected')
                                        <span class="badge bg-danger">مرفوض</span>
                                        @break
                                    @case('expired')
                                        <span class="badge bg-secondary">منتهي الصلاحية</span>
                                        @break
                                    @case('suspended')
                                        <span class="badge bg-dark">معلق</span>
                                        @break
                                    @default
                                        <span class="badge bg-secondary">{{ $product->status }}</span>
                                @endswitch
                                <br>
                                <small class="text-muted">
                                    @switch($product->prescription_status)
                                        @case('prescription') بوصفة @break
                                        @case('otc') بدون وصفة @break
                                        @case('controlled') مراقب @break
                                        @default {{ $product->prescription_status }}
                                    @endswitch
                                </small>
                            </td>
                            <td>
                                <div class="btn-group" role="group">
                                    <a href="{{ route('regulatory-affairs.products.show', $product->id) }}" class="btn btn-sm btn-outline-primary" title="عرض">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <button type="button" class="btn btn-sm btn-outline-secondary" title="تعديل">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button type="button" class="btn btn-sm btn-outline-danger" title="حذف">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="d-flex justify-content-center mt-4">
                {{ $products->appends(request()->query())->links() }}
            </div>
            @else
            <div class="text-center py-5">
                <i class="fas fa-pills fa-3x text-muted mb-3"></i>
                <h5 class="text-muted">لا توجد منتجات</h5>
                <p class="text-muted">لم يتم العثور على أي منتجات تطابق معايير البحث</p>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
.table th {
    background-color: #f8f9fa;
    border-top: none;
    font-weight: 600;
}

.badge {
    font-size: 0.75em;
}

.btn-group .btn {
    border-radius: 0.25rem;
    margin-right: 2px;
}

.card {
    box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
}

.table-responsive {
    border-radius: 0.375rem;
}

code {
    background-color: #f8f9fa;
    padding: 2px 4px;
    border-radius: 3px;
    font-size: 0.875em;
}
</style>
@endpush
