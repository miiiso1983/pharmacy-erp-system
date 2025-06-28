@extends('layouts.app')

@section('title', __('items.low_stock_items') . ' - ' . __('app.name'))

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">{{ __('app.home') }}</a></li>
    <li class="breadcrumb-item"><a href="{{ route('items.index') }}">{{ __('items.title') }}</a></li>
    <li class="breadcrumb-item active">{{ __('items.low_stock_items') }}</li>
@endsection

@section('content')
<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>
                <i class="fas fa-exclamation-triangle me-2 text-warning"></i>
                {{ __('items.low_stock_items') }}
            </h2>
            <div>
                <a href="{{ route('items.create') }}" class="btn btn-primary me-2">
                    <i class="fas fa-plus me-2"></i>
                    {{ __('items.add_item') }}
                </a>
                <a href="{{ route('items.index') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-list me-2"></i>
                    {{ __('items.all_items') }}
                </a>
            </div>
        </div>
    </div>
</div>

<!-- تنبيه مهم -->
<div class="alert alert-warning alert-dismissible fade show mb-4" role="alert">
    <i class="fas fa-exclamation-triangle me-2"></i>
    <strong>تنبيه:</strong> هذه المنتجات وصلت إلى الحد الأدنى للمخزون أو أقل. يرجى إعادة تعبئة المخزون فوراً.
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>

<!-- إحصائيات سريعة -->
<div class="row mb-4">
    <div class="col-lg-3 col-md-6 mb-3">
        <div class="card bg-warning text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h4 class="mb-0">{{ $lowStockCount ?? 0 }}</h4>
                        <p class="mb-0">{{ __('items.low_stock_items') }}</p>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-exclamation-triangle fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6 mb-3">
        <div class="card bg-danger text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h4 class="mb-0">{{ $outOfStockCount ?? 0 }}</h4>
                        <p class="mb-0">{{ __('items.out_of_stock') }}</p>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-times-circle fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6 mb-3">
        <div class="card bg-info text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h4 class="mb-0">{{ number_format($totalValue ?? 0, 0) }}</h4>
                        <p class="mb-0">{{ __('app.iqd') }} {{ __('items.total_value') }}</p>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-money-bill-wave fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6 mb-3">
        <div class="card bg-secondary text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h4 class="mb-0">{{ $criticalCount ?? 0 }}</h4>
                        <p class="mb-0">{{ __('items.critical_stock') }}</p>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-skull-crossbones fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- فلاتر البحث -->
<div class="card mb-4">
    <div class="card-header">
        <h5 class="mb-0">
            <i class="fas fa-filter me-2"></i>
            {{ __('app.filter') }}
        </h5>
    </div>
    <div class="card-body">
        <form method="GET" action="{{ route('items.low-stock') }}">
            <div class="row">
                <div class="col-md-3 mb-3">
                    <label for="category_id" class="form-label">{{ __('items.category') }}</label>
                    <select name="category_id" id="category_id" 
                            class="form-select searchable"
                            placeholder="{{ __('items.all_categories') }}">
                        <option value="">{{ __('items.all_categories') }}</option>
                        @foreach($categories ?? [] as $category)
                            <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3 mb-3">
                    <label for="supplier_id" class="form-label">{{ __('items.supplier') }}</label>
                    <select name="supplier_id" id="supplier_id" 
                            class="form-select searchable"
                            placeholder="{{ __('items.all_suppliers') }}">
                        <option value="">{{ __('items.all_suppliers') }}</option>
                        @foreach($suppliers ?? [] as $supplier)
                            <option value="{{ $supplier->id }}" {{ request('supplier_id') == $supplier->id ? 'selected' : '' }}>
                                {{ $supplier->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3 mb-3">
                    <label for="stock_level" class="form-label">{{ __('items.stock_level') }}</label>
                    <select name="stock_level" id="stock_level" 
                            class="form-select searchable"
                            placeholder="{{ __('app.all') }}">
                        <option value="">{{ __('app.all') }}</option>
                        <option value="out_of_stock" {{ request('stock_level') == 'out_of_stock' ? 'selected' : '' }}>{{ __('items.out_of_stock') }}</option>
                        <option value="critical" {{ request('stock_level') == 'critical' ? 'selected' : '' }}>{{ __('items.critical_stock') }}</option>
                        <option value="low" {{ request('stock_level') == 'low' ? 'selected' : '' }}>{{ __('items.low_stock') }}</option>
                    </select>
                </div>
                <div class="col-md-3 mb-3">
                    <label for="sort_by" class="form-label">{{ __('app.sort_by') }}</label>
                    <select name="sort_by" id="sort_by" 
                            class="form-select searchable"
                            placeholder="{{ __('items.stock_quantity') }}">
                        <option value="stock_quantity" {{ request('sort_by') == 'stock_quantity' ? 'selected' : '' }}>{{ __('items.stock_quantity') }}</option>
                        <option value="name" {{ request('sort_by') == 'name' ? 'selected' : '' }}>{{ __('app.name') }}</option>
                        <option value="category" {{ request('sort_by') == 'category' ? 'selected' : '' }}>{{ __('items.category') }}</option>
                        <option value="price" {{ request('sort_by') == 'price' ? 'selected' : '' }}>{{ __('app.price') }}</option>
                        <option value="last_updated" {{ request('sort_by') == 'last_updated' ? 'selected' : '' }}>{{ __('items.last_updated') }}</option>
                    </select>
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <button type="submit" class="btn btn-primary me-2">
                        <i class="fas fa-search me-2"></i>
                        {{ __('app.search') }}
                    </button>
                    <a href="{{ route('items.low-stock') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-times me-2"></i>
                        {{ __('app.clear') }}
                    </a>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- جدول الأصناف منخفضة المخزون -->
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">
            <i class="fas fa-list me-2"></i>
            {{ __('items.low_stock_items') }}
        </h5>
        <div>
            <button class="btn btn-outline-success btn-sm me-2" onclick="exportToExcel()">
                <i class="fas fa-file-excel me-2"></i>
                {{ __('app.export') }} Excel
            </button>
            <button class="btn btn-outline-danger btn-sm me-2" onclick="exportToPDF()">
                <i class="fas fa-file-pdf me-2"></i>
                {{ __('app.export') }} PDF
            </button>
            <button class="btn btn-outline-warning btn-sm" onclick="generatePurchaseOrder()">
                <i class="fas fa-shopping-cart me-2"></i>
                إنشاء أمر شراء
            </button>
        </div>
    </div>
    <div class="card-body">
        @if(isset($items) && $items->count() > 0)
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead class="table-dark">
                        <tr>
                            <th>
                                <input type="checkbox" id="selectAll" class="form-check-input">
                            </th>
                            <th>{{ __('items.item_code') }}</th>
                            <th>{{ __('items.item_name') }}</th>
                            <th>{{ __('items.category') }}</th>
                            <th>{{ __('items.current_stock') }}</th>
                            <th>{{ __('items.minimum_stock') }}</th>
                            <th>{{ __('items.stock_status') }}</th>
                            <th>{{ __('items.unit_price') }}</th>
                            <th>{{ __('items.total_value') }}</th>
                            <th>{{ __('items.supplier') }}</th>
                            <th>{{ __('items.last_updated') }}</th>
                            <th>{{ __('app.actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($items as $item)
                            @php
                                $stockPercentage = $item->min_stock_level > 0 ? ($item->stock_quantity / $item->min_stock_level) * 100 : 0;
                                $stockStatus = $item->stock_quantity <= 0 ? 'out_of_stock' : 
                                              ($stockPercentage <= 25 ? 'critical' : 
                                              ($stockPercentage <= 50 ? 'low' : 'normal'));
                                $rowClass = $stockStatus == 'out_of_stock' ? 'table-danger' : 
                                           ($stockStatus == 'critical' ? 'table-warning' : 'table-light');
                            @endphp
                            <tr class="{{ $rowClass }}">
                                <td>
                                    <input type="checkbox" name="selected_items[]" value="{{ $item->id }}" class="form-check-input item-checkbox">
                                </td>
                                <td>
                                    <a href="{{ route('items.show', $item->id) }}" class="text-decoration-none fw-bold">
                                        {{ $item->code }}
                                    </a>
                                </td>
                                <td>
                                    <div>
                                        <strong>{{ $item->name }}</strong>
                                        @if($item->brand)
                                            <br><small class="text-muted">{{ $item->brand }}</small>
                                        @endif
                                    </div>
                                </td>
                                <td>
                                    @if($item->category)
                                        <span class="badge bg-secondary">{{ $item->category }}</span>
                                    @else
                                        <span class="text-muted">{{ __('app.not_specified') }}</span>
                                    @endif
                                </td>
                                <td>
                                    <span class="fw-bold {{ $item->stock_quantity <= 0 ? 'text-danger' : ($stockStatus == 'critical' ? 'text-warning' : 'text-info') }}">
                                        {{ number_format($item->stock_quantity, 0) }}
                                    </span>
                                    @if($item->unit)
                                        <small class="text-muted">{{ $item->unit }}</small>
                                    @endif
                                </td>
                                <td>
                                    <span class="text-secondary">{{ number_format($item->min_stock_level, 0) }}</span>
                                    @if($item->unit)
                                        <small class="text-muted">{{ $item->unit }}</small>
                                    @endif
                                </td>
                                <td>
                                    @switch($stockStatus)
                                        @case('out_of_stock')
                                            <span class="badge bg-danger">{{ __('items.out_of_stock') }}</span>
                                            @break
                                        @case('critical')
                                            <span class="badge bg-warning">{{ __('items.critical_stock') }}</span>
                                            @break
                                        @case('low')
                                            <span class="badge bg-info">{{ __('items.low_stock') }}</span>
                                            @break
                                        @default
                                            <span class="badge bg-success">{{ __('items.normal_stock') }}</span>
                                    @endswitch
                                    <br><small class="text-muted">{{ number_format($stockPercentage, 1) }}%</small>
                                </td>
                                <td>
                                    <strong>{{ number_format($item->price, 0) }}</strong>
                                    <small class="text-muted">{{ __('app.iqd') }}</small>
                                </td>
                                <td>
                                    <strong>{{ number_format($item->stock_quantity * $item->price, 0) }}</strong>
                                    <small class="text-muted">{{ __('app.iqd') }}</small>
                                </td>
                                <td>
                                    @if($item->supplier)
                                        <div>
                                            <strong>{{ $item->supplier->name }}</strong>
                                            @if($item->supplier->phone)
                                                <br><small class="text-info">{{ $item->supplier->phone }}</small>
                                            @endif
                                        </div>
                                    @else
                                        <span class="text-muted">{{ __('app.not_specified') }}</span>
                                    @endif
                                </td>
                                <td>
                                    {{ $item->updated_at ? $item->updated_at->format('Y/m/d H:i') : '-' }}
                                </td>
                                <td>
                                    <div class="btn-group btn-group-sm">
                                        <a href="{{ route('items.show', $item->id) }}" 
                                           class="btn btn-outline-primary" title="{{ __('app.view') }}">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        @can('edit_items')
                                            <a href="{{ route('items.edit', $item->id) }}" 
                                               class="btn btn-outline-warning" title="{{ __('app.edit') }}">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                        @endcan
                                        <button class="btn btn-outline-success" 
                                                onclick="addStock({{ $item->id }})" 
                                                title="{{ __('items.add_stock') }}">
                                            <i class="fas fa-plus"></i>
                                        </button>
                                        <button class="btn btn-outline-info" 
                                                onclick="createPurchaseOrder({{ $item->id }})" 
                                                title="{{ __('items.create_purchase_order') }}">
                                            <i class="fas fa-shopping-cart"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if(method_exists($items, 'links'))
                <div class="d-flex justify-content-center mt-4">
                    {{ $items->appends(request()->query())->links() }}
                </div>
            @endif
        @else
            <div class="text-center py-5">
                <i class="fas fa-check-circle fa-3x text-success mb-3"></i>
                <h5 class="text-success">ممتاز! جميع المنتجات لديها مخزون كافي</h5>
                <p class="text-muted">لا توجد منتجات تحتاج إلى إعادة تعبئة المخزون حالياً</p>
                <a href="{{ route('items.index') }}" class="btn btn-primary">
                    <i class="fas fa-list me-2"></i>
                    {{ __('items.view_all_items') }}
                </a>
            </div>
        @endif
    </div>
</div>

<!-- Modal إضافة مخزون -->
<div class="modal fade" id="addStockModal" tabindex="-1" aria-labelledby="addStockModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addStockModalLabel">{{ __('items.add_stock') }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="addStockForm">
                <div class="modal-body">
                    <input type="hidden" id="item_id" name="item_id">
                    <div class="mb-3">
                        <label for="item_name" class="form-label">{{ __('items.item_name') }}</label>
                        <input type="text" class="form-control" id="item_name" readonly>
                    </div>
                    <div class="mb-3">
                        <label for="current_stock" class="form-label">{{ __('items.current_stock') }}</label>
                        <input type="text" class="form-control" id="current_stock" readonly>
                    </div>
                    <div class="mb-3">
                        <label for="add_quantity" class="form-label">{{ __('items.quantity_to_add') }} <span class="text-danger">*</span></label>
                        <input type="number" class="form-control" id="add_quantity" name="add_quantity" min="1" required>
                    </div>
                    <div class="mb-3">
                        <label for="notes" class="form-label">{{ __('app.notes') }}</label>
                        <textarea class="form-control" id="notes" name="notes" rows="3" placeholder="ملاحظات حول إضافة المخزون..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('app.cancel') }}</button>
                    <button type="submit" class="btn btn-success">{{ __('items.add_stock') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function exportToExcel() {
    window.location.href = '{{ route("items.export") }}?low_stock=1&' + new URLSearchParams(window.location.search);
}

function exportToPDF() {
    window.open('{{ route("items.export") }}?format=pdf&low_stock=1&' + new URLSearchParams(window.location.search), '_blank');
}

function generatePurchaseOrder() {
    const selectedItems = [];
    document.querySelectorAll('.item-checkbox:checked').forEach(checkbox => {
        selectedItems.push(checkbox.value);
    });

    if (selectedItems.length === 0) {
        alert('يرجى اختيار المنتجات المراد إنشاء أمر شراء لها');
        return;
    }

    // وظيفة إنشاء أمر الشراء قيد التطوير
    alert('وظيفة إنشاء أمر الشراء قيد التطوير. سيتم إضافتها قريباً.');
}

function addStock(itemId) {
    // جلب بيانات المنتج
    fetch(`/items/${itemId}/details`)
        .then(response => response.json())
        .then(data => {
            document.getElementById('item_id').value = data.id;
            document.getElementById('item_name').value = data.name;
            document.getElementById('current_stock').value = data.stock_quantity + ' ' + (data.unit || '');
            document.getElementById('add_quantity').value = '';
            document.getElementById('notes').value = '';
            
            const modal = new bootstrap.Modal(document.getElementById('addStockModal'));
            modal.show();
        })
        .catch(error => {
            console.error('Error:', error);
            alert('حدث خطأ في جلب بيانات المنتج');
        });
}

function createPurchaseOrder(itemId) {
    if (confirm('هل تريد إنشاء أمر شراء لهذا المنتج؟')) {
        // وظيفة إنشاء أمر الشراء قيد التطوير
        alert('وظيفة إنشاء أمر الشراء قيد التطوير. سيتم إضافتها قريباً.');
    }
}

// تحديد/إلغاء تحديد جميع المنتجات
document.getElementById('selectAll').addEventListener('change', function() {
    const checkboxes = document.querySelectorAll('.item-checkbox');
    checkboxes.forEach(checkbox => {
        checkbox.checked = this.checked;
    });
});

// معالجة نموذج إضافة المخزون
document.getElementById('addStockForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    const itemId = formData.get('item_id');
    
    fetch(`/items/${itemId}/add-stock`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({
            quantity: formData.get('add_quantity'),
            notes: formData.get('notes')
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('تم إضافة المخزون بنجاح');
            location.reload();
        } else {
            alert('حدث خطأ في إضافة المخزون');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('حدث خطأ في إضافة المخزون');
    });
});

// تحديث تلقائي للصفحة كل 5 دقائق
setInterval(function() {
    if (document.visibilityState === 'visible') {
        location.reload();
    }
}, 300000);
</script>
@endpush

@push('styles')
<style>
.table-danger {
    background-color: rgba(220, 53, 69, 0.1);
}

.table-warning {
    background-color: rgba(255, 193, 7, 0.1);
}

.card {
    box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
    border: 1px solid rgba(0, 0, 0, 0.125);
}

.btn-group-sm .btn {
    padding: 0.25rem 0.5rem;
    font-size: 0.875rem;
}

.badge {
    font-size: 0.75rem;
}

.item-checkbox {
    transform: scale(1.2);
}

#selectAll {
    transform: scale(1.3);
}

@media (max-width: 768px) {
    .table-responsive {
        font-size: 0.875rem;
    }
    
    .btn-group-sm .btn {
        padding: 0.125rem 0.25rem;
        font-size: 0.75rem;
    }
}
</style>
@endpush
