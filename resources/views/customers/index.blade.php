@extends('layouts.master')

@section('title', 'Customers')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-md-6">
                        <h4 class="card-title">Customers</h4>
                    </div>
                    <div class="col-md-6 text-end">
                        <a href="{{ route('customers.create') }}" class="btn btn-primary">
                            <i class="mdi mdi-plus"></i> Add Customer
                        </a>
                    </div>
                </div>

                <!-- Search -->
                <div class="row mb-3">
                    <div class="col-md-12">
                        <form method="GET" class="row g-3">
                            <div class="col-md-4">
                                <input type="text" name="search" class="form-control" placeholder="Search by name, email, phone..." value="{{ request('search') }}">
                            </div>
                            <div class="col-md-2">
                                <button type="submit" class="btn btn-outline-primary">Search</button>
                                <a href="{{ route('customers.index') }}" class="btn btn-outline-secondary">Clear</a>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Customers Table -->
                <div class="table-responsive">
                    <table class="table table-centered table-nowrap mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Name</th>
                                <th>Contact Info</th>
                                <th>Address</th>
                                <th>Total Purchases</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($customers as $customer)
                            <tr>
                                <td>
                                    <h5 class="font-size-14 mb-1">
                                        <a href="{{ route('customers.show', $customer) }}" class="text-dark">{{ $customer->name }}</a>
                                    </h5>
                                </td>
                                <td>
                                    @if($customer->email)
                                        <div><i class="mdi mdi-email"></i> {{ $customer->email }}</div>
                                    @endif
                                    @if($customer->phone)
                                        <div><i class="mdi mdi-phone"></i> {{ $customer->phone }}</div>
                                    @endif
                                </td>
                                <td>
                                    @if($customer->address)
                                        {{ Str::limit($customer->address, 50) }}
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td>${{ number_format($customer->total_purchases, 2) }}</td>
                                <td>
                                    <span class="badge {{ $customer->is_active ? 'bg-success' : 'bg-secondary' }}">
                                        {{ $customer->is_active ? 'Active' : 'Inactive' }}
                                    </span>
                                </td>
                                <td>
                                    <div class="dropdown">
                                        <a href="#" class="dropdown-toggle card-drop" data-bs-toggle="dropdown" aria-expanded="false">
                                            <i class="mdi mdi-dots-horizontal font-size-18"></i>
                                        </a>
                                        <ul class="dropdown-menu dropdown-menu-end">
                                            <li><a href="{{ route('customers.show', $customer) }}" class="dropdown-item">View Details</a></li>
                                            <li><a href="{{ route('customers.edit', $customer) }}" class="dropdown-item">Edit</a></li>
                                            <li>
                                                <form action="{{ route('customers.destroy', $customer) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="dropdown-item text-danger"
                                                            onclick="return confirm('Are you sure you want to delete this customer?')">
                                                        Delete
                                                    </button>
                                                </form>
                                            </li>
                                        </ul>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="text-center py-4">
                                    <i class="mdi mdi-account-group font-size-48 text-muted"></i>
                                    <h5 class="mt-2">No customers found</h5>
                                    <p class="text-muted mb-3">Get started by adding your first customer.</p>
                                    <a href="{{ route('customers.create') }}" class="btn btn-primary">Add Customer</a>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                @if($customers->hasPages())
                <div class="row mt-3">
                    <div class="col-12">
                        {{ $customers->appends(request()->query())->links() }}
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
