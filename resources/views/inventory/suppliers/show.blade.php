@extends('layouts.master')

@section('title', 'Supplier Details')

@section('content')
<div class="row">
    <div class="col-xl-8">
        <div class="card">
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-md-6">
                        <h4 class="card-title">{{ $supplier->name }}</h4>
                        <span class="badge {{ $supplier->is_active ? 'bg-success' : 'bg-secondary' }}">
                            {{ $supplier->is_active ? 'Active' : 'Inactive' }}
                        </span>
                    </div>
                    <div class="col-md-6 text-end">
                        @can('update', $supplier)
                        <a href="{{ route('suppliers.edit', $supplier) }}" class="btn btn-primary">
                            <i class="mdi mdi-pencil"></i> Edit Supplier
                        </a>
                        @endcan
                        <a href="{{ route('suppliers.index') }}" class="btn btn-secondary">
                            <i class="mdi mdi-arrow-left"></i> Back to Suppliers
                        </a>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="table-responsive">
                            <table class="table table-borderless">
                                <tbody>
                                    <tr>
                                        <th scope="row">Supplier Name:</th>
                                        <td>{{ $supplier->name }}</td>
                                    </tr>
                                    <tr>
                                        <th scope="row">Contact Person:</th>
                                        <td>{{ $supplier->contact_person ?: 'Not specified' }}</td>
                                    </tr>
                                    <tr>
                                        <th scope="row">Email:</th>
                                        <td>
                                            @if($supplier->email)
                                                <a href="mailto:{{ $supplier->email }}">{{ $supplier->email }}</a>
                                            @else
                                                Not provided
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <th scope="row">Phone:</th>
                                        <td>
                                            @if($supplier->phone)
                                                <a href="tel:{{ $supplier->phone }}">{{ $supplier->phone }}</a>
                                            @else
                                                Not provided
                                            @endif
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="table-responsive">
                            <table class="table table-borderless">
                                <tbody>
                                    <tr>
                                        <th scope="row">Address:</th>
                                        <td>{{ $supplier->address ?: 'Not provided' }}</td>
                                    </tr>
                                    <tr>
                                        <th scope="row">Created:</th>
                                        <td>{{ $supplier->created_at->format('M d, Y H:i') }}</td>
                                    </tr>
                                    <tr>
                                        <th scope="row">Last Updated:</th>
                                        <td>{{ $supplier->updated_at->format('M d, Y H:i') }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Purchases -->
        <div class="card">
            <div class="card-body">
                <h4 class="card-title mb-4">Recent Purchases</h4>
                <div class="table-responsive">
                    <table class="table table-centered table-nowrap mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Purchase #</th>
                                <th>Total Amount</th>
                                <th>Status</th>
                                <th>Date</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($supplier->purchases as $purchase)
                            <tr>
                                <td><a href="#" class="text-body fw-bold">{{ $purchase->purchase_number }}</a></td>
                                <td>${{ number_format($purchase->total_amount, 2) }}</td>
                                <td><span class="badge bg-success">{{ ucfirst($purchase->status) }}</span></td>
                                <td>{{ $purchase->purchase_date->format('M d, Y') }}</td>
                                <td><a href="#" class="btn btn-sm btn-outline-primary">View</a></td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="text-center py-3">
                                    <span class="text-muted">No purchases found for this supplier.</span>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-4">
        <!-- Statistics Card -->
        <div class="card">
            <div class="card-body">
                <h4 class="card-title mb-4">Statistics</h4>
                <div class="text-center">
                    <div class="mb-3">
                        <h2 class="text-primary">{{ $supplier->purchases->count() }}</h2>
                        <p class="text-muted mb-0">Total Purchases</p>
                    </div>
                    <div class="mb-3">
                        <h2 class="text-success">
                            ${{ number_format($supplier->purchases->sum('total_amount'), 2) }}
                        </h2>
                        <p class="text-muted mb-0">Total Spent</p>
                    </div>
                    <div>
                        <h2 class="text-info">{{ $supplier->stocks->count() }}</h2>
                        <p class="text-muted mb-0">Stock Transactions</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Stock Movements -->
        <div class="card">
            <div class="card-body">
                <h4 class="card-title mb-4">Recent Stock Movements</h4>
                <div class="list-group">
                    @forelse($supplier->stocks->take(5) as $stock)
                    <div class="list-group-item">
                        <div class="d-flex w-100 justify-content-between">
                            <h6 class="mb-1">{{ $stock->product->name }}</h6>
                            <small class="text-muted">{{ $stock->transaction_date->format('M d') }}</small>
                        </div>
                        <small class="text-{{ $stock->type === 'in' ? 'success' : 'danger' }}">
                            {{ $stock->type === 'in' ? '+' : '-' }}{{ $stock->quantity }} {{ $stock->product->unit }}
                        </small>
                    </div>
                    @empty
                    <div class="text-center py-3">
                        <span class="text-muted">No stock movements found.</span>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
