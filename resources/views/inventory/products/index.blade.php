@extends('layouts.master')

@section('title', 'Products')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-md-6">
                        <h4 class="card-title">Products</h4>
                    </div>
                    <div class="col-md-6 text-end">
                        @can('create', \App\Models\Product::class)
                        <a href="{{ route('products.create') }}" class="btn btn-primary">
                            <i class="mdi mdi-plus"></i> Add Product
                        </a>
                        @endcan
                    </div>
                </div>

                <!-- Filters -->
                <div class="row mb-3">
                    <div class="col-md-12">
                        <form method="GET" class="row g-3">
                            <div class="col-md-4">
                                <input type="text" name="search" class="form-control" placeholder="Search by name, SKU, barcode..." value="{{ request('search') }}">
                            </div>
                            <div class="col-md-2">
                                <select name="category" class="form-select">
                                    <option value="">All Categories</option>
                                    @foreach(\App\Models\Product::distinct()->pluck('category')->filter()->values() as $category)
                                    <option value="{{ $category }}" {{ request('category') == $category ? 'selected' : '' }}>{{ $category }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-2">
                                <select name="filter" class="form-select">
                                    <option value="">All Products</option>
                                    <option value="low_stock" {{ request('filter') == 'low_stock' ? 'selected' : '' }}>Low Stock Only</option>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <button type="submit" class="btn btn-outline-primary">Filter</button>
                                <a href="{{ route('products.index') }}" class="btn btn-outline-secondary">Clear</a>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Products Table -->
                <div class="table-responsive">
                    <table class="table table-centered table-nowrap mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Image</th>
                                <th>Product</th>
                                <th>SKU</th>
                                <th>Category</th>
                                <th>Stock</th>
                                <th>Price</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($products as $product)
                            <tr>
                                <td>
                                    <div class="avatar-sm">
                                        <span class="avatar-title bg-primary rounded-circle">
                                            <i class="mdi mdi-package-variant-closed text-white"></i>
                                        </span>
                                    </div>
                                </td>
                                <td>
                                    <h5 class="font-size-14 mb-1">
                                        <a href="{{ route('products.show', $product) }}" class="text-dark">{{ $product->name }}</a>
                                    </h5>
                                    <p class="text-muted mb-0">{{ Str::limit($product->description, 50) }}</p>
                                </td>
                                <td>{{ $product->sku }}</td>
                                <td>{{ $product->category ?: 'N/A' }}</td>
                                <td>
                                    <span class="badge {{ $product->isLowStock() ? 'bg-warning' : 'bg-success' }}">
                                        {{ $product->stock_quantity }} {{ $product->unit }}
                                    </span>
                                    @if($product->isLowStock())
                                    <br><small class="text-danger">Min: {{ $product->min_stock_level }}</small>
                                    @endif
                                </td>
                                <td>
                                    <div>
                                        <strong>${{ number_format($product->selling_price, 2) }}</strong>
                                        <br><small class="text-muted">Cost: ${{ number_format($product->cost_price, 2) }}</small>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge {{ $product->is_active ? 'bg-success' : 'bg-secondary' }}">
                                        {{ $product->is_active ? 'Active' : 'Inactive' }}
                                    </span>
                                </td>
                                <td>
                                    <div class="dropdown">
                                        <a href="#" class="dropdown-toggle card-drop" data-bs-toggle="dropdown" aria-expanded="false">
                                            <i class="mdi mdi-dots-horizontal font-size-18"></i>
                                        </a>
                                        <ul class="dropdown-menu dropdown-menu-end">
                                            <li><a href="{{ route('products.show', $product) }}" class="dropdown-item">View Details</a></li>
                                            @can('update', $product)
                                            <li><a href="{{ route('products.edit', $product) }}" class="dropdown-item">Edit</a></li>
                                            @endcan
                                            @can('delete', $product)
                                            <li>
                                                <form action="{{ route('products.destroy', $product) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="dropdown-item text-danger"
                                                            onclick="return confirm('Are you sure you want to delete this product?')">
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
                                <td colspan="8" class="text-center py-4">
                                    <i class="mdi mdi-package-variant-closed font-size-48 text-muted"></i>
                                    <h5 class="mt-2">No products found</h5>
                                    <p class="text-muted mb-3">Get started by adding your first product.</p>
                                    @can('create', \App\Models\Product::class)
                                    <a href="{{ route('products.create') }}" class="btn btn-primary">Add Product</a>
                                    @endcan
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                @if($products->hasPages())
                <div class="row mt-3">
                    <div class="col-12">
                        {{ $products->appends(request()->query())->links() }}
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
