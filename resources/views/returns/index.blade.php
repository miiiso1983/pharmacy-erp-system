@extends('layouts.app')

@section('title', __('returns.title'))

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h3 class="card-title">
                            <i class="fas fa-undo me-2"></i>
                            {{ __('returns.title') }}
                        </h3>
                        <a href="{{ route('returns.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus me-2"></i>
                            {{ __('returns.add_return') }}
                        </a>
                    </div>
                </div>

                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <!-- Statistics Cards -->
                    <div class="row mb-4">
                        <div class="col-md-3">
                            <div class="card bg-primary text-white">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between">
                                        <div>
                                            <h4>{{ $returns->total() }}</h4>
                                            <p class="mb-0">{{ __('returns.total_returns') }}</p>
                                        </div>
                                        <div class="align-self-center">
                                            <i class="fas fa-undo fa-2x"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-warning text-white">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between">
                                        <div>
                                            <h4>{{ $returns->where('status', 'pending')->count() }}</h4>
                                            <p class="mb-0">{{ __('returns.pending_returns') }}</p>
                                        </div>
                                        <div class="align-self-center">
                                            <i class="fas fa-clock fa-2x"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-success text-white">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between">
                                        <div>
                                            <h4>{{ $returns->where('status', 'approved')->count() }}</h4>
                                            <p class="mb-0">{{ __('returns.approved_returns') }}</p>
                                        </div>
                                        <div class="align-self-center">
                                            <i class="fas fa-check fa-2x"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-danger text-white">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between">
                                        <div>
                                            <h4>{{ $returns->where('status', 'rejected')->count() }}</h4>
                                            <p class="mb-0">{{ __('returns.rejected_returns') }}</p>
                                        </div>
                                        <div class="align-self-center">
                                            <i class="fas fa-times fa-2x"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Returns Table -->
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>{{ __('returns.return_no') }}</th>
                                    <th>{{ __('returns.customer_name') }}</th>
                                    <th>{{ __('returns.item_name') }}</th>
                                    <th>{{ __('returns.return_qty') }}</th>
                                    <th>{{ __('returns.return_value') }}</th>
                                    <th>{{ __('returns.return_reason') }}</th>
                                    <th>{{ __('returns.return_status') }}</th>
                                    <th>{{ __('returns.return_date_col') }}</th>
                                    <th>{{ __('returns.actions') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($returns as $return)
                                    <tr>
                                        <td>{{ $return->return_number }}</td>
                                        <td>{{ $return->customer->name ?? 'غير محدد' }}</td>
                                        <td>{{ $return->item->item_name ?? 'غير محدد' }}</td>
                                        <td>{{ number_format($return->quantity) }}</td>
                                        <td>{{ number_format($return->total_amount, 2) }} {{ __('app.currency') }}</td>
                                        <td>
                                            <span class="badge bg-secondary">
                                                {{ $return->reason_label }}
                                            </span>
                                        </td>
                                        <td>
                                            @switch($return->status)
                                                @case('pending')
                                                    <span class="badge bg-warning">{{ __('returns.pending') }}</span>
                                                    @break
                                                @case('approved')
                                                    <span class="badge bg-success">{{ __('returns.approved') }}</span>
                                                    @break
                                                @case('rejected')
                                                    <span class="badge bg-danger">{{ __('returns.rejected') }}</span>
                                                    @break
                                                @case('processed')
                                                    <span class="badge bg-info">{{ __('returns.processed') }}</span>
                                                    @break
                                            @endswitch
                                        </td>
                                        <td>{{ $return->return_date->format('Y-m-d') }}</td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <a href="{{ route('returns.show', $return) }}" class="btn btn-sm btn-info">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                
                                                @if($return->status === 'pending')
                                                    <a href="{{ route('returns.edit', $return) }}" class="btn btn-sm btn-warning">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    
                                                    <form action="{{ route('returns.approve', $return) }}" method="POST" class="d-inline">
                                                        @csrf
                                                        <button type="submit" class="btn btn-sm btn-success" 
                                                                onclick="return confirm('{{ __('returns.confirm_approve') }}')">
                                                            <i class="fas fa-check"></i>
                                                        </button>
                                                    </form>
                                                    
                                                    <button type="button" class="btn btn-sm btn-danger" 
                                                            data-bs-toggle="modal" 
                                                            data-bs-target="#rejectModal{{ $return->id }}">
                                                        <i class="fas fa-times"></i>
                                                    </button>
                                                    
                                                    <form action="{{ route('returns.destroy', $return) }}" method="POST" class="d-inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-sm btn-outline-danger" 
                                                                onclick="return confirm('{{ __('returns.confirm_delete') }}')">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </form>
                                                @endif
                                            </div>

                                            <!-- Reject Modal -->
                                            <div class="modal fade" id="rejectModal{{ $return->id }}" tabindex="-1">
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
                                                                              placeholder="{{ __('returns.reason_description_placeholder') }}"></textarea>
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
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="9" class="text-center">{{ __('returns.no_returns_found') }}</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="d-flex justify-content-center">
                        {{ $returns->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
