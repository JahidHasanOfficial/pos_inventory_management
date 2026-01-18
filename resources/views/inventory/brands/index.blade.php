@extends('layouts.master')

@section('title', 'Brands')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-md-6">
                        <h4 class="card-title">Brands</h4>
                    </div>
                    <div class="col-md-6 text-end">
                        @if(auth()->user()->isAdmin())
                        <a href="{{ route('brands.create') }}" class="btn btn-primary">
                            <i class="mdi mdi-plus"></i> Add Brand
                        </a>
                        @endif
                    </div>
                </div>

                <!-- Search -->
                <div class="row mb-3">
                    <div class="col-md-12">
                        <form method="GET" class="row g-3">
                            <div class="col-md-4">
                                <input type="text" name="search" class="form-control" placeholder="Search by name, description, country..." value="{{ request('search') }}">
                            </div>
                            <div class="col-md-3">
                                <select name="status" class="form-control">
                                    <option value="">All Status</option>
                                    <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active</option>
                                    <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Inactive</option>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <button type="submit" class="btn btn-outline-primary">Search</button>
                                <a href="{{ route('brands.index') }}" class="btn btn-outline-secondary">Clear</a>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Brands Table -->
                <div class="table-responsive">
                    <table class="table table-centered table-nowrap mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Name</th>
                                <th>Description</th>
                                <th>Country</th>
                                <th>Website</th>
                                <th>Products</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($brands as $brand)
                            <tr>
                                <td>
                                    <h5 class="font-size-14 mb-1">
                                        <a href="{{ route('brands.show', $brand) }}" class="text-dark">{{ $brand->name }}</a>
                                    </h5>
                                </td>
                                <td>
                                    @if($brand->description)
                                        {{ Str::limit($brand->description, 50) }}
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td>{{ $brand->country ?: '-' }}</td>
                                <td>
                                    @if($brand->website)
                                        <a href="{{ $brand->website_url }}" target="_blank" class="text-primary">
                                            <i class="mdi mdi-open-in-new"></i> Visit
                                        </a>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td>
                                    <span class="badge bg-info">{{ $brand->products->count() }}</span>
                                </td>
                                <td>
                                    <span class="badge {{ $brand->is_active ? 'bg-success' : 'bg-secondary' }}">
                                        {{ $brand->is_active ? 'Active' : 'Inactive' }}
                                    </span>
                                </td>
                                <td>
                                    <div class="dropdown">
                                        <a href="#" class="dropdown-toggle card-drop" data-bs-toggle="dropdown" aria-expanded="false">
                                            <i class="mdi mdi-dots-horizontal font-size-18"></i>
                                        </a>
                                        <ul class="dropdown-menu dropdown-menu-end">
                                            <li><a href="{{ route('brands.show', $brand) }}" class="dropdown-item">View Details</a></li>
                                            @if(auth()->user()->isAdmin())
                                            <li><a href="{{ route('brands.edit', $brand) }}" class="dropdown-item">Edit</a></li>
                                            <li>
                                                <form action="{{ route('brands.destroy', $brand) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="dropdown-item text-danger"
                                                            onclick="return confirm('Are you sure you want to delete this brand?')">
                                                        Delete
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
                                <td colspan="7" class="text-center py-4">
                                    <i class="mdi mdi-label font-size-48 text-muted"></i>
                                    <h5 class="mt-2">No brands found</h5>
                                    <p class="text-muted mb-3">Get started by adding your first brand.</p>
                                    @if(auth()->user()->isAdmin())
                                    <a href="{{ route('brands.create') }}" class="btn btn-primary">Add Brand</a>
                                    @endif
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                @if($brands->hasPages())
                <div class="row mt-3">
                    <div class="col-12">
                        {{ $brands->appends(request()->query())->links() }}
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
