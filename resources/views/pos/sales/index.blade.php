@extends('layouts.master')

@section('title', 'Sales')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-md-6">
                        <h4 class="card-title">Sales</h4>
                    </div>
                    <div class="col-md-6 text-end">
                        @can('create', \App\Models\Sale::class)
                        <a href="{{ route('pos.index') }}" class="btn btn-primary">
                            <i class="mdi mdi-plus"></i> New Sale
                        </a>
                        @endcan
                    </div>
                </div>

                <!-- Filters -->
                <div class="row mb-3">
                    <div class="col-md-12">
                        <form method="GET" class="row g-3">
                            <div class="col-md-2">
                                <select name="status" class="form-select">
                                    <option value="">All Status</option>
                                    <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                                    <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <select name="customer_id" class="form-select">
                                    <option value="">All Customers</option>
                                    @foreach($customers as $customer)
                                    <option value="{{ $customer->id }}" {{ request('customer_id') == $customer->id ? 'selected' : '' }}>
                                        {{ $customer->name }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-2">
                                <input type="date" name="date_from" class="form-control" value="{{ request('date_from') }}">
                            </div>
                            <div class="col-md-2">
                                <input type="date" name="date_to" class="form-control" value="{{ request('date_to') }}">
                            </div>
                            <div class="col-md-2">
                                <button type="submit" class="btn btn-outline-primary">Filter</button>
                                <a href="{{ route('sales.index') }}" class="btn btn-outline-secondary">Clear</a>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Sales Table -->
                <div class="table-responsive">
                    <table class="table table-centered table-nowrap mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Invoice #</th>
                                <th>Customer</th>
                                <th>Cashier</th>
                                <th>Total Amount</th>
                                <th>Payment</th>
                                <th>Status</th>
                                <th>Date</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($sales as $sale)
                            <tr>
                                <td>
                                    <a href="{{ route('sales.show', $sale) }}" class="text-body fw-bold">
                                        {{ $sale->invoice_number }}
                                    </a>
                                </td>
                                <td>{{ $sale->customer ? $sale->customer->name : 'Walk-in' }}</td>
                                <td>{{ $sale->user->name }}</td>
                                <td><strong>${{ number_format($sale->total_amount, 2) }}</strong></td>
                                <td>
                                    <span class="badge bg-info">{{ ucfirst($sale->payment_method) }}</span>
                                </td>
                                <td>
                                    <span class="badge {{ $sale->status === 'completed' ? 'bg-success' : ($sale->status === 'cancelled' ? 'bg-danger' : 'bg-warning') }}">
                                        {{ ucfirst($sale->status) }}
                                    </span>
                                </td>
                                <td>{{ $sale->sale_date->format('M d, Y') }}</td>
                                <td>
                                    <div class="dropdown">
                                        <a href="#" class="dropdown-toggle card-drop" data-bs-toggle="dropdown" aria-expanded="false">
                                            <i class="mdi mdi-dots-horizontal font-size-18"></i>
                                        </a>
                                        <ul class="dropdown-menu dropdown-menu-end">
                                            <li><a href="{{ route('sales.show', $sale) }}" class="dropdown-item">View Details</a></li>
                                            @if($sale->status === 'completed')
                                            <li>
                                                <form action="{{ route('sales.cancel', $sale) }}" method="POST" class="d-inline"
                                                      onsubmit="return confirm('Are you sure you want to cancel this sale?')">
                                                    @csrf
                                                    @method('PATCH')
                                                    <button type="submit" class="dropdown-item text-danger">
                                                        Cancel Sale
                                                    </button>
                                                </form>
                                            </li>
                                            @endif
                                        </ul>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="8" class="text-center py-4">
                                    <i class="mdi mdi-cart font-size-48 text-muted"></i>
                                    <h5 class="mt-2">No sales found</h5>
                                    <p class="text-muted mb-3">Start making sales to see them here.</p>
                                    @can('create', \App\Models\Sale::class)
                                    <a href="{{ route('pos.index') }}" class="btn btn-primary">Create First Sale</a>
                                    @endcan
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                @if($sales->hasPages())
                <div class="row mt-3">
                    <div class="col-12">
                        {{ $sales->appends(request()->query())->links() }}
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
