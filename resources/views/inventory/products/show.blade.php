@extends('layouts.master')

@section('title', 'Product Details')

@section('content')
<div class="row">
    <div class="col-xl-8">
        <div class="card">
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-md-6">
                        <h4 class="card-title">{{ $product->name }}</h4>
                        <span class="badge {{ $product->is_active ? 'bg-success' : 'bg-secondary' }}">
                            {{ $product->is_active ? 'Active' : 'Inactive' }}
                        </span>
                    </div>
                    <div class="col-md-6 text-end">
                        @can('update', $product)
                        <a href="{{ route('products.edit', $product) }}" class="btn btn-primary">
                            <i class="mdi mdi-pencil"></i> Edit Product
                        </a>
                        @endcan
                        <a href="{{ route('products.index') }}" class="btn btn-secondary">
                            <i class="mdi mdi-arrow-left"></i> Back to Products
                        </a>
                    </div>
                </div>

                <!-- Product Details -->
                <div class="row">
                    <div class="col-md-6">
                        <table class="table table-borderless">
                            <tr>
                                <th scope="row">Product Name:</th>
                                <td>{{ $product->name }}</td>
                            </tr>
                            <tr>
                                <th scope="row">Brand:</th>
                                <td>{{ $product->brand ? $product->brand->name : 'Not specified' }}</td>
                            </tr>
                            <tr>
                                <th scope="row">SKU:</th>
                                <td>{{ $product->sku }}</td>
                            </tr>
                            <tr>
                                <th scope="row">Barcode:</th>
                                <td>{{ $product->barcode ?: 'Not provided' }}</td>
                            </tr>
                            <tr>
                                <th scope="row">Category:</th>
                                <td>{{ $product->category ? $product->category->name : 'Not specified' }}</td>
                            </tr>
                            <tr>
                                <th scope="row">Unit:</th>
                                <td>{{ $product->unit }}</td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <table class="table table-borderless">
                            <tr>
                                <th scope="row">Cost Price:</th>
                                <td>${{ number_format($product->cost_price, 2) }}</td>
                            </tr>
                            <tr>
                                <th scope="row">Selling Price:</th>
                                <td>${{ number_format($product->selling_price, 2) }}</td>
                            </tr>
                            <tr>
                                <th scope="row">Stock Quantity:</th>
                                <td>
                                    <span class="badge {{ $product->isLowStock() ? 'bg-warning' : 'bg-success' }}">
                                        {{ $product->stock_quantity }} {{ $product->unit }}
                                    </span>
                                    @if($product->isLowStock())
                                    <br><small class="text-danger">Minimum: {{ $product->min_stock_level }}</small>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <th scope="row">Created:</th>
                                <td>{{ $product->created_at->format('M d, Y H:i') }}</td>
                            </tr>
                            <tr>
                                <th scope="row">Last Updated:</th>
                                <td>{{ $product->updated_at->format('M d, Y H:i') }}</td>
                            </tr>
                        </table>
                    </div>
                </div>

                @if($product->description)
                <div class="mt-3">
                    <h6>Description:</h6>
                    <p class="text-muted">{{ $product->description }}</p>
                </div>
                @endif

                <!-- Recent Sales -->
                <div class="mt-4">
                    <h5 class="mb-3">Recent Sales</h5>
                    <div class="table-responsive">
                        <table class="table table-centered table-nowrap">
                            <thead class="table-light">
                                <tr>
                                    <th>Invoice #</th>
                                    <th>Customer</th>
                                    <th>Quantity</th>
                                    <th>Unit Price</th>
                                    <th>Total</th>
                                    <th>Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($product->saleItems->take(10) as $saleItem)
                                <tr>
                                    <td><a href="{{ route('sales.show', $saleItem->sale) }}" class="text-body fw-bold">{{ $saleItem->sale->invoice_number }}</a></td>
                                    <td>{{ $saleItem->sale->customer ? $saleItem->sale->customer->name : 'Walk-in' }}</td>
                                    <td>{{ $saleItem->quantity }} {{ $product->unit }}</td>
                                    <td>${{ number_format($saleItem->unit_price, 2) }}</td>
                                    <td><strong>${{ number_format($saleItem->total_price, 2) }}</strong></td>
                                    <td>{{ $saleItem->sale->sale_date->format('M d, Y') }}</td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="text-center py-3">
                                        <span class="text-muted">No sales found for this product.</span>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-4">
        <!-- Statistics Card -->
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Product Statistics</h5>
                <div class="text-center">
                    <div class="mb-3">
                        <h2 class="text-primary">{{ $product->saleItems->sum('quantity') }}</h2>
                        <p class="text-muted mb-0">Total Sold</p>
                    </div>
                    <div class="mb-3">
                        <h2 class="text-success">${{ number_format($product->saleItems->sum('total_price'), 0) }}</h2>
                        <p class="text-muted mb-0">Total Revenue</p>
                    </div>
                    <div class="mb-3">
                        <h2 class="text-info">{{ $product->stock_quantity }}</h2>
                        <p class="text-muted mb-0">Current Stock</p>
                    </div>
                    <div>
                        <h2 class="text-warning">{{ $product->stocks->count() }}</h2>
                        <p class="text-muted mb-0">Stock Movements</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Stock Movements -->
        <div class="card">
            <div class="card-body">
                <h5 class="card-title mb-4">Recent Stock Movements</h5>
                <div class="list-group">
                    @forelse($product->stocks->take(5) as $stock)
                    <div class="list-group-item">
                        <div class="d-flex w-100 justify-content-between">
                            <h6 class="mb-1">
                                {{ $stock->type === 'in' ? 'Stock In' : 'Stock Out' }}
                                @if($stock->supplier)
                                    from {{ $stock->supplier->name }}
                                @endif
                            </h6>
                            <small class="text-muted">{{ $stock->transaction_date->format('M d') }}</small>
                        </div>
                        <small class="text-{{ $stock->type === 'in' ? 'success' : 'danger' }}">
                            {{ $stock->type === 'in' ? '+' : '-' }}{{ $stock->quantity }} {{ $product->unit }}
                        </small>
                        @if($stock->notes)
                        <br><small class="text-muted">{{ $stock->notes }}</small>
                        @endif
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
