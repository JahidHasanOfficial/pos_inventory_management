@extends('layouts.master')

@section('title', 'Suppliers')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-md-6">
                        <h4 class="card-title">Suppliers</h4>
                    </div>
                    <div class="col-md-6 text-end">
                        @can('create', \App\Models\Supplier::class)
                        <a href="{{ route('suppliers.create') }}" class="btn btn-primary">
                            <i class="mdi mdi-plus"></i> Add Supplier
                        </a>
                        @endcan
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
                                <a href="{{ route('suppliers.index') }}" class="btn btn-outline-secondary">Clear</a>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Suppliers Table -->
                <div class="table-responsive">
                    <table class="table table-centered table-nowrap mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Name</th>
                                <th>Contact Info</th>
                                <th>Address</th>
                                <th>Contact Person</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($suppliers as $supplier)
                            <tr>
                                <td>
                                    <h5 class="font-size-14 mb-1">
                                        <a href="{{ route('suppliers.show', $supplier) }}" class="text-dark">{{ $supplier->name }}</a>
                                    </h5>
                                </td>
                                <td>
                                    @if($supplier->email)
                                        <div><i class="mdi mdi-email"></i> {{ $supplier->email }}</div>
                                    @endif
                                    @if($supplier->phone)
                                        <div><i class="mdi mdi-phone"></i> {{ $supplier->phone }}</div>
                                    @endif
                                </td>
                                <td>
                                    @if($supplier->address)
                                        {{ Str::limit($supplier->address, 50) }}
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td>{{ $supplier->contact_person ?: '-' }}</td>
                                <td>
                                    <span class="badge {{ $supplier->is_active ? 'bg-success' : 'bg-secondary' }}">
                                        {{ $supplier->is_active ? 'Active' : 'Inactive' }}
                                    </span>
                                </td>
                                <td>
                                    <div class="dropdown">
                                        <a href="#" class="dropdown-toggle card-drop" data-bs-toggle="dropdown" aria-expanded="false">
                                            <i class="mdi mdi-dots-horizontal font-size-18"></i>
                                        </a>
                                        <ul class="dropdown-menu dropdown-menu-end">
                                            <li><a href="{{ route('suppliers.show', $supplier) }}" class="dropdown-item">View Details</a></li>
                                            @can('update', $supplier)
                                            <li><a href="{{ route('suppliers.edit', $supplier) }}" class="dropdown-item">Edit</a></li>
                                            @endcan
                                            @can('delete', $supplier)
                                            <li>
                                                <form action="{{ route('suppliers.destroy', $supplier) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="dropdown-item text-danger"
                                                            onclick="return confirm('Are you sure you want to delete this supplier?')">
                                                        Delete
                                                    </button>
                                                </form>
                                            </li>
                                            @endcan
                                        </ul>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="text-center py-4">
                                    <i class="mdi mdi-truck-delivery font-size-48 text-muted"></i>
                                    <h5 class="mt-2">No suppliers found</h5>
                                    <p class="text-muted mb-3">Get started by adding your first supplier.</p>
                                    @can('create', \App\Models\Supplier::class)
                                    <a href="{{ route('suppliers.create') }}" class="btn btn-primary">Add Supplier</a>
                                    @endcan
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                @if($suppliers->hasPages())
                <div class="row mt-3">
                    <div class="col-12">
                        {{ $suppliers->appends(request()->query())->links() }}
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
