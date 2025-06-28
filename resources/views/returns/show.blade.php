@extends('layouts.app')

@section('title', __('returns.return_details'))

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h3 class="card-title">
                            <i class="fas fa-eye me-2"></i>
                            {{ __('returns.return_details') }} - {{ $return->return_number }}
                        </h3>
                        <div>
                            @if($return->status === 'pending')
                                <a href="{{ route('returns.edit', $return) }}" class="btn btn-warning me-2">
                                    <i class="fas fa-edit me-2"></i>
                                    {{ __('returns.edit_return') }}
                                </a>
                            @endif
                            <a href="{{ route('returns.index') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left me-2"></i>
                                {{ __('returns.back_to_returns') }}
                            </a>
                        </div>
                    </div>
                </div>

                <div class="card-body">
                    <div class="row">
                        <!-- Return Information -->
                        <div class="col-md-8">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="card-title mb-0">{{ __('returns.return_information') }}</h5>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <table class="table table-borderless">
                                                <tr>
                                                    <td><strong>{{ __('returns.return_number') }}:</strong></td>
                                                    <td>{{ $return->return_number }}</td>
                                                </tr>
                                                <tr>
                                                    <td><strong>{{ __('returns.return_date') }}:</strong></td>
                                                    <td>{{ $return->return_date->format('Y-m-d') }}</td>
                                                </tr>
                                                <tr>
                                                    <td><strong>{{ __('returns.customer') }}:</strong></td>
                                                    <td>{{ $return->customer->name ?? 'غير محدد' }}</td>
                                                </tr>
                                                <tr>
                                                    <td><strong>{{ __('returns.order_id') }}:</strong></td>
                                                    <td>{{ $return->order->order_number ?? 'غير محدد' }}</td>
                                                </tr>
                                            </table>
                                        </div>
                                        <div class="col-md-6">
                                            <table class="table table-borderless">
                                                <tr>
                                                    <td><strong>{{ __('returns.item') }}:</strong></td>
                                                    <td>{{ $return->item->item_name ?? 'غير محدد' }}</td>
                                                </tr>
                                                <tr>
                                                    <td><strong>{{ __('returns.quantity') }}:</strong></td>
                                                    <td>{{ number_format($return->quantity) }}</td>
                                                </tr>
                                                <tr>
                                                    <td><strong>{{ __('returns.unit_price') }}:</strong></td>
                                                    <td>{{ number_format($return->unit_price, 2) }} {{ __('app.currency') }}</td>
                                                </tr>
                                                <tr>
                                                    <td><strong>{{ __('returns.total_amount') }}:</strong></td>
                                                    <td>{{ number_format($return->total_amount, 2) }} {{ __('app.currency') }}</td>
                                                </tr>
                                            </table>
                                        </div>
                                    </div>

                                    <div class="row mt-3">
                                        <div class="col-12">
                                            <table class="table table-borderless">
                                                <tr>
                                                    <td width="150"><strong>{{ __('returns.reason') }}:</strong></td>
                                                    <td>
                                                        <span class="badge bg-secondary">{{ $return->reason_label }}</span>
                                                    </td>
                                                </tr>
                                                @if($return->reason_description)
                                                <tr>
                                                    <td><strong>{{ __('returns.reason_description') }}:</strong></td>
                                                    <td>{{ $return->reason_description }}</td>
                                                </tr>
                                                @endif
                                                @if($return->notes)
                                                <tr>
                                                    <td><strong>{{ __('returns.notes') }}:</strong></td>
                                                    <td>{{ $return->notes }}</td>
                                                </tr>
                                                @endif
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Status and Actions -->
                        <div class="col-md-4">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="card-title mb-0">{{ __('returns.status') }}</h5>
                                </div>
                                <div class="card-body text-center">
                                    @switch($return->status)
                                        @case('pending')
                                            <div class="mb-3">
                                                <span class="badge bg-warning fs-6 p-3">
                                                    <i class="fas fa-clock me-2"></i>
                                                    {{ __('returns.pending') }}
                                                </span>
                                            </div>
                                            
                                            <!-- Action Buttons for Pending Returns -->
                                            <div class="d-grid gap-2">
                                                <form action="{{ route('returns.approve', $return) }}" method="POST">
                                                    @csrf
                                                    <button type="submit" class="btn btn-success w-100" 
                                                            onclick="return confirm('{{ __('returns.confirm_approve') }}')">
                                                        <i class="fas fa-check me-2"></i>
                                                        {{ __('returns.approve_return') }}
                                                    </button>
                                                </form>
                                                
                                                <button type="button" class="btn btn-danger w-100" 
                                                        data-bs-toggle="modal" data-bs-target="#rejectModal">
                                                    <i class="fas fa-times me-2"></i>
                                                    {{ __('returns.reject_return') }}
                                                </button>
                                            </div>
                                            @break
                                            
                                        @case('approved')
                                            <div class="mb-3">
                                                <span class="badge bg-success fs-6 p-3">
                                                    <i class="fas fa-check me-2"></i>
                                                    {{ __('returns.approved') }}
                                                </span>
                                            </div>
                                            @if($return->processedBy)
                                                <p class="text-muted">
                                                    {{ __('returns.processed_by') }}: {{ $return->processedBy->name }}
                                                </p>
                                            @endif
                                            @break
                                            
                                        @case('rejected')
                                            <div class="mb-3">
                                                <span class="badge bg-danger fs-6 p-3">
                                                    <i class="fas fa-times me-2"></i>
                                                    {{ __('returns.rejected') }}
                                                </span>
                                            </div>
                                            @if($return->processedBy)
                                                <p class="text-muted">
                                                    {{ __('returns.processed_by') }}: {{ $return->processedBy->name }}
                                                </p>
                                            @endif
                                            @break
                                            
                                        @case('processed')
                                            <div class="mb-3">
                                                <span class="badge bg-info fs-6 p-3">
                                                    <i class="fas fa-cog me-2"></i>
                                                    {{ __('returns.processed') }}
                                                </span>
                                            </div>
                                            @if($return->processedBy)
                                                <p class="text-muted">
                                                    {{ __('returns.processed_by') }}: {{ $return->processedBy->name }}
                                                </p>
                                            @endif
                                            @break
                                    @endswitch

                                    <!-- Timestamps -->
                                    <hr>
                                    <small class="text-muted">
                                        <div>{{ __('app.created_at') }}: {{ $return->created_at->format('Y-m-d H:i') }}</div>
                                        <div>{{ __('app.updated_at') }}: {{ $return->updated_at->format('Y-m-d H:i') }}</div>
                                    </small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Reject Modal -->
@if($return->status === 'pending')
<div class="modal fade" id="rejectModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">{{ __('returns.reject_return') }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('returns.reject', $return) }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">{{ __('returns.reason_description') }}</label>
                        <textarea name="rejection_reason" class="form-control" rows="3" 
                                  placeholder="{{ __('returns.reason_description_placeholder') }}" required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        {{ __('app.cancel') }}
                    </button>
                    <button type="submit" class="btn btn-danger">
                        {{ __('returns.reject') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endif
@endsection
