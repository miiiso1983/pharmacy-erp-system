@extends('layouts.app')

@section('title', 'نقل البضائع بين المخازن')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">الرئيسية</a></li>
    <li class="breadcrumb-item"><a href="{{ route('warehouses.index') }}">المخازن</a></li>
    <li class="breadcrumb-item active">نقل البضائع</li>
@endsection

@section('content')
<div class="container-fluid">
    <div class="row">
        <!-- نموذج النقل -->
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-exchange-alt me-2"></i>
                        نقل البضائع بين المخازن
                    </h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('warehouses.process-transfer') }}" id="transferForm">
                        @csrf
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="from_warehouse_id" class="form-label">المخزن المصدر</label>
                                    <select class="form-select" id="from_warehouse_id" name="from_warehouse_id" required>
                                        <option value="">اختر المخزن المصدر</option>
                                        @foreach($warehouses as $warehouse)
                                            <option value="{{ $warehouse->id }}" {{ old('from_warehouse_id') == $warehouse->id ? 'selected' : '' }}>
                                                {{ $warehouse->name }} - {{ $warehouse->city }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('from_warehouse_id')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="to_warehouse_id" class="form-label">المخزن الهدف</label>
                                    <select class="form-select" id="to_warehouse_id" name="to_warehouse_id" required>
                                        <option value="">اختر المخزن الهدف</option>
                                        @foreach($warehouses as $warehouse)
                                            <option value="{{ $warehouse->id }}" {{ old('to_warehouse_id') == $warehouse->id ? 'selected' : '' }}>
                                                {{ $warehouse->name }} - {{ $warehouse->city }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('to_warehouse_id')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="item_id" class="form-label">المنتج المراد نقله</label>
                                    <select class="form-select" id="item_id" name="item_id" required disabled>
                                        <option value="">اختر المخزن المصدر أولاً</option>
                                    </select>
                                    @error('item_id')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="quantity" class="form-label">الكمية</label>
                                    <div class="input-group">
                                        <input type="number" class="form-control" id="quantity" name="quantity" 
                                               min="1" step="1" value="{{ old('quantity') }}" required>
                                        <span class="input-group-text" id="unit-display">وحدة</span>
                                    </div>
                                    <small class="text-muted" id="available-quantity"></small>
                                    @error('quantity')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="notes" class="form-label">ملاحظات</label>
                            <textarea class="form-control" id="notes" name="notes" rows="3" 
                                      placeholder="أدخل أي ملاحظات حول عملية النقل">{{ old('notes') }}</textarea>
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('warehouses.index') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-right me-2"></i>
                                العودة
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-exchange-alt me-2"></i>
                                تنفيذ النقل
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- معلومات مساعدة -->
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h6 class="mb-0">
                        <i class="fas fa-info-circle me-2"></i>
                        تعليمات النقل
                    </h6>
                </div>
                <div class="card-body">
                    <div class="alert alert-info">
                        <h6>خطوات النقل:</h6>
                        <ol class="mb-0">
                            <li>اختر المخزن المصدر</li>
                            <li>اختر المخزن الهدف</li>
                            <li>حدد المنتج المراد نقله</li>
                            <li>أدخل الكمية المطلوبة</li>
                            <li>أضف ملاحظات إذا لزم الأمر</li>
                            <li>اضغط تنفيذ النقل</li>
                        </ol>
                    </div>

                    <div class="alert alert-warning">
                        <h6>تنبيهات مهمة:</h6>
                        <ul class="mb-0">
                            <li>تأكد من توفر الكمية في المخزن المصدر</li>
                            <li>لا يمكن النقل من مخزن إلى نفسه</li>
                            <li>سيتم تسجيل عملية النقل تلقائياً</li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- إحصائيات سريعة -->
            <div class="card mt-3">
                <div class="card-header">
                    <h6 class="mb-0">
                        <i class="fas fa-chart-pie me-2"></i>
                        إحصائيات سريعة
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-6">
                            <h4 class="text-primary">{{ $warehouses->count() }}</h4>
                            <small>إجمالي المخازن</small>
                        </div>
                        <div class="col-6">
                            <h4 class="text-success">{{ $warehouses->where('status', 'active')->count() }}</h4>
                            <small>المخازن النشطة</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const fromWarehouseSelect = document.getElementById('from_warehouse_id');
    const itemSelect = document.getElementById('item_id');
    const quantityInput = document.getElementById('quantity');
    const unitDisplay = document.getElementById('unit-display');
    const availableQuantityDisplay = document.getElementById('available-quantity');

    fromWarehouseSelect.addEventListener('change', function() {
        const warehouseId = this.value;
        
        if (warehouseId) {
            // تفعيل قائمة المنتجات
            itemSelect.disabled = false;
            itemSelect.innerHTML = '<option value="">جاري التحميل...</option>';
            
            // جلب منتجات المخزن
            fetch(`/warehouses/${warehouseId}/items-api`)
                .then(response => response.json())
                .then(data => {
                    itemSelect.innerHTML = '<option value="">اختر المنتج</option>';
                    
                    if (data.length > 0) {
                        data.forEach(item => {
                            const option = document.createElement('option');
                            option.value = item.id;
                            option.textContent = `${item.name} (متوفر: ${item.available_quantity} ${item.unit})`;
                            option.dataset.quantity = item.available_quantity;
                            option.dataset.unit = item.unit;
                            itemSelect.appendChild(option);
                        });
                    } else {
                        itemSelect.innerHTML = '<option value="">لا توجد عناصر متوفرة</option>';
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    itemSelect.innerHTML = '<option value="">حدث خطأ في التحميل</option>';
                });
        } else {
            itemSelect.disabled = true;
            itemSelect.innerHTML = '<option value="">اختر المخزن المصدر أولاً</option>';
        }
    });

    itemSelect.addEventListener('change', function() {
        const selectedOption = this.options[this.selectedIndex];
        
        if (selectedOption.value) {
            const availableQuantity = selectedOption.dataset.quantity;
            const unit = selectedOption.dataset.unit;
            
            unitDisplay.textContent = unit;
            availableQuantityDisplay.textContent = `الكمية المتوفرة: ${availableQuantity} ${unit}`;
            quantityInput.max = availableQuantity;
        } else {
            unitDisplay.textContent = 'وحدة';
            availableQuantityDisplay.textContent = '';
            quantityInput.max = '';
        }
    });
});

function exportWarehouses() {
    // يمكن إضافة وظيفة التصدير هنا
    alert('سيتم إضافة وظيفة التصدير قريباً');
}
</script>
@endpush
