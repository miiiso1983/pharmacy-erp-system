@extends('layouts.app')

@section('title', __('returns.create_return'))

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h3 class="card-title">
                            <i class="fas fa-plus me-2"></i>
                            {{ __('returns.create_return') }}
                        </h3>
                        <a href="{{ route('returns.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left me-2"></i>
                            {{ __('returns.back_to_returns') }}
                        </a>
                    </div>
                </div>

                <div class="card-body">
                    @if($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('returns.store') }}" method="POST">
                        @csrf
                        
                        <div class="row">
                            <!-- Order Selection -->
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="order_id" class="form-label">{{ __('returns.select_order') }} <span class="text-danger">*</span></label>
                                    <select name="order_id" id="order_id" class="form-select" required>
                                        <option value="">{{ __('returns.select_order') }}</option>
                                        @foreach($orders as $order)
                                            <option value="{{ $order->id }}" {{ old('order_id') == $order->id ? 'selected' : '' }}>
                                                {{ $order->order_number }} - {{ $order->customer->name ?? 'غير محدد' }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <!-- Customer Selection -->
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="customer_id" class="form-label">{{ __('returns.select_customer') }} <span class="text-danger">*</span></label>
                                    <select name="customer_id" id="customer_id" class="form-select" required>
                                        <option value="">{{ __('returns.select_customer') }}</option>
                                        @foreach($customers as $customer)
                                            <option value="{{ $customer->id }}" {{ old('customer_id') == $customer->id ? 'selected' : '' }}>
                                                {{ $customer->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <!-- Item Selection -->
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="item_id" class="form-label">{{ __('returns.select_item') }} <span class="text-danger">*</span></label>
                                    <select name="item_id" id="item_id" class="form-select" required>
                                        <option value="">{{ __('returns.select_item') }}</option>
                                        @foreach($items as $item)
                                            <option value="{{ $item->id }}" {{ old('item_id') == $item->id ? 'selected' : '' }}>
                                                {{ $item->item_name }} - {{ $item->item_code }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <!-- Return Date -->
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="return_date" class="form-label">{{ __('returns.return_date') }} <span class="text-danger">*</span></label>
                                    <input type="date" name="return_date" id="return_date" class="form-control" 
                                           value="{{ old('return_date', date('Y-m-d')) }}" required>
                                    <small class="form-text text-muted">{{ __('returns.return_date_help') }}</small>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <!-- Quantity -->
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="quantity" class="form-label">{{ __('returns.quantity') }} <span class="text-danger">*</span></label>
                                    <input type="number" name="quantity" id="quantity" class="form-control" 
                                           value="{{ old('quantity') }}" min="1" step="1" 
                                           placeholder="{{ __('returns.quantity_placeholder') }}" required>
                                    <small class="form-text text-muted">{{ __('returns.quantity_help') }}</small>
                                </div>
                            </div>

                            <!-- Unit Price -->
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="unit_price" class="form-label">{{ __('returns.unit_price') }} <span class="text-danger">*</span></label>
                                    <input type="number" name="unit_price" id="unit_price" class="form-control" 
                                           value="{{ old('unit_price') }}" min="0" step="0.01" 
                                           placeholder="{{ __('returns.unit_price_placeholder') }}" required>
                                    <small class="form-text text-muted">{{ __('returns.unit_price_help') }}</small>
                                </div>
                            </div>

                            <!-- Total Amount (Auto-calculated) -->
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="total_amount" class="form-label">{{ __('returns.total_amount') }}</label>
                                    <input type="text" id="total_amount" class="form-control" readonly 
                                           placeholder="0.00">
                                    <small class="form-text text-muted">يتم حسابه تلقائياً</small>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <!-- Return Reason -->
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="reason" class="form-label">{{ __('returns.reason') }} <span class="text-danger">*</span></label>
                                    <select name="reason" id="reason" class="form-select" required>
                                        <option value="">{{ __('returns.select_reason') }}</option>
                                        <option value="damaged" {{ old('reason') == 'damaged' ? 'selected' : '' }}>{{ __('returns.damaged') }}</option>
                                        <option value="expired" {{ old('reason') == 'expired' ? 'selected' : '' }}>{{ __('returns.expired') }}</option>
                                        <option value="wrong_item" {{ old('reason') == 'wrong_item' ? 'selected' : '' }}>{{ __('returns.wrong_item') }}</option>
                                        <option value="customer_request" {{ old('reason') == 'customer_request' ? 'selected' : '' }}>{{ __('returns.customer_request') }}</option>
                                        <option value="other" {{ old('reason') == 'other' ? 'selected' : '' }}>{{ __('returns.other') }}</option>
                                    </select>
                                    <small class="form-text text-muted">{{ __('returns.reason_help') }}</small>
                                </div>
                            </div>

                            <!-- Reason Description -->
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="reason_description" class="form-label">{{ __('returns.reason_description') }}</label>
                                    <textarea name="reason_description" id="reason_description" class="form-control" rows="3" 
                                              placeholder="{{ __('returns.reason_description_placeholder') }}">{{ old('reason_description') }}</textarea>
                                    <small class="form-text text-muted">{{ __('returns.reason_description_help') }}</small>
                                </div>
                            </div>
                        </div>

                        <!-- Notes -->
                        <div class="mb-3">
                            <label for="notes" class="form-label">{{ __('returns.notes') }}</label>
                            <textarea name="notes" id="notes" class="form-control" rows="3" 
                                      placeholder="{{ __('returns.notes_placeholder') }}">{{ old('notes') }}</textarea>
                        </div>

                        <!-- Submit Buttons -->
                        <div class="d-flex justify-content-end gap-2">
                            <a href="{{ route('returns.index') }}" class="btn btn-secondary">
                                {{ __('app.cancel') }}
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-2"></i>
                                {{ __('returns.save_return') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const quantityInput = document.getElementById('quantity');
    const unitPriceInput = document.getElementById('unit_price');
    const totalAmountInput = document.getElementById('total_amount');

    function calculateTotal() {
        const quantity = parseFloat(quantityInput.value) || 0;
        const unitPrice = parseFloat(unitPriceInput.value) || 0;
        const total = quantity * unitPrice;
        totalAmountInput.value = total.toFixed(2);
    }

    quantityInput.addEventListener('input', calculateTotal);
    unitPriceInput.addEventListener('input', calculateTotal);
});
</script>
@endsection
