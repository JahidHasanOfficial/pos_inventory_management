@extends('layouts.master')

@section('title', 'Purchases')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-md-6">
                        <h4 class="card-title">Purchases</h4>
                    </div>
                    <div class="col-md-6 text-end">
                        <a href="{{ route('purchases.create') }}" class="btn btn-primary">
                            <i class="mdi mdi-plus"></i> New Purchase
                        </a>
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
                                <select name="supplier_id" class="form-select">
                                    <option value="">All Suppliers</option>
                                    @foreach($suppliers as $supplier)
                                    <option value="{{ $supplier->id }}" {{ request('supplier_id') == $supplier->id ? 'selected' : '' }}>
                                        {{ $supplier->name }}
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
                                <a href="{{ route('purchases.index') }}" class="btn btn-outline-secondary">Clear</a>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Purchases Table -->
                <div class="table-responsive">
                    <table class="table table-centered table-nowrap mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Purchase #</th>
                                <th>Supplier</th>
                                <th>User</th>
                                <th>Total Amount</th>
                                <th>Payment</th>
                                <th>Status</th>
                                <th>Date</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($purchases as $purchase)
                            <tr>
                                <td>
                                    <a href="{{ route('purchases.show', $purchase) }}" class="text-body fw-bold">
                                        {{ $purchase->purchase_number }}
                                    </a>
                                </td>
                                <td>{{ $purchase->supplier->name }}</td>
                                <td>{{ $purchase->user->name }}</td>
                                <td><strong>${{ number_format($purchase->total_amount, 2) }}</strong></td>
                                <td>
                                    <span class="badge bg-info">{{ ucfirst($purchase->payment_method) }}</span>
                                </td>
                                <td>
                                    <span class="badge {{ $purchase->status === 'completed' ? 'bg-success' : ($purchase->status === 'cancelled' ? 'bg-danger' : 'bg-warning') }}">
                                        {{ ucfirst($purchase->status) }}
                                    </span>
                                </td>
                                <td>{{ $purchase->purchase_date->format('M d, Y') }}</td>
                                <td>
                                    <div class="dropdown">
                                        <a href="#" class="dropdown-toggle card-drop" data-bs-toggle="dropdown" aria-expanded="false">
                                            <i class="mdi mdi-dots-horizontal font-size-18"></i>
                                        </a>
                                        <ul class="dropdown-menu dropdown-menu-end">
                                            <li><a href="{{ route('purchases.show', $purchase) }}" class="dropdown-item">View Details</a></li>
                                            @if($purchase->status === 'completed')
                                            <li>
                                                <form action="{{ route('purchases.cancel', $purchase) }}" method="POST" class="d-inline"
                                                      onsubmit="return confirm('Are you sure you want to cancel this purchase?')">
                                                    @csrf
                                                    @method('PATCH')
                                                    <button type="submit" class="dropdown-item text-danger">
                                                        Cancel Purchase
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
                                    <i class="mdi mdi-cart-plus font-size-48 text-muted"></i>
                                    <h5 class="mt-2">No purchases found</h5>
                                    <p class="text-muted mb-3">Start making purchases to see them here.</p>
                                    <a href="{{ route('purchases.create') }}" class="btn btn-primary">Create First Purchase</a>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                @if($purchases->hasPages())
                <div class="row mt-3">
                    <div class="col-12">
                        {{ $purchases->appends(request()->query())->links() }}
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
