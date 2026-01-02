@extends('layouts.master')

@section('title', 'Dashboard')

@section('content')
<div class="row">
    <div class="col-xl-12">
        <div class="row">
            <div class="col-xl-12">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title mb-4">Welcome to POS & Inventory Management System</h4>
                        <p class="card-title-desc">Here's what's happening with your store today.</p>

                        <div class="row">
                            <!-- Total Sales Today -->
                            <div class="col-md-3">
                                <div class="card bg-primary text-white">
                                    <div class="card-body">
                                        <div class="d-flex align-items-center">
                                            <div class="flex-grow-1">
                                                <p class="text-white-50 mb-1">Today's Sales</p>
                                                <h4 class="mb-0 text-white">
                                                    ${{ number_format(\App\Models\Sale::whereDate('sale_date', today())->sum('total_amount'), 2) }}
                                                </h4>
                                            </div>
                                            <div class="avatar-sm">
                                                <span class="avatar-title bg-white bg-opacity-25 rounded-circle">
                                                    <i class="mdi mdi-cart text-white font-size-24"></i>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Total Products -->
                            <div class="col-md-3">
                                <div class="card bg-success text-white">
                                    <div class="card-body">
                                        <div class="d-flex align-items-center">
                                            <div class="flex-grow-1">
                                                <p class="text-white-50 mb-1">Total Products</p>
                                                <h4 class="mb-0 text-white">{{ \App\Models\Product::count() }}</h4>
                                            </div>
                                            <div class="avatar-sm">
                                                <span class="avatar-title bg-white bg-opacity-25 rounded-circle">
                                                    <i class="mdi mdi-package-variant-closed text-white font-size-24"></i>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Low Stock Items -->
                            <div class="col-md-3">
                                <div class="card bg-warning text-white">
                                    <div class="card-body">
                                        <div class="d-flex align-items-center">
                                            <div class="flex-grow-1">
                                                <p class="text-white-50 mb-1">Low Stock Items</p>
                                                <h4 class="mb-0 text-white">{{ \App\Models\Product::where('stock_quantity', '<=', \DB::raw('min_stock_level'))->count() }}</h4>
                                            </div>
                                            <div class="avatar-sm">
                                                <span class="avatar-title bg-white bg-opacity-25 rounded-circle">
                                                    <i class="mdi mdi-alert text-white font-size-24"></i>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Total Customers -->
                            <div class="col-md-3">
                                <div class="card bg-info text-white">
                                    <div class="card-body">
                                        <div class="d-flex align-items-center">
                                            <div class="flex-grow-1">
                                                <p class="text-white-50 mb-1">Total Customers</p>
                                                <h4 class="mb-0 text-white">{{ \App\Models\Customer::count() }}</h4>
                                            </div>
                                            <div class="avatar-sm">
                                                <span class="avatar-title bg-white bg-opacity-25 rounded-circle">
                                                    <i class="mdi mdi-account-group text-white font-size-24"></i>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Recent Sales Table -->
                        <div class="row mt-4">
                            <div class="col-xl-8">
                                <div class="card">
                                    <div class="card-body">
                                        <h4 class="card-title mb-4">Recent Sales</h4>
                                        <div class="table-responsive">
                                            <table class="table table-centered table-nowrap mb-0">
                                                <thead class="table-light">
                                                    <tr>
                                                        <th>Invoice #</th>
                                                        <th>Customer</th>
                                                        <th>Total</th>
                                                        <th>Status</th>
                                                        <th>Date</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach(\App\Models\Sale::with('customer')->latest()->take(5)->get() as $sale)
                                                    <tr>
                                                        <td><a href="#" class="text-body fw-bold">{{ $sale->invoice_number }}</a></td>
                                                        <td>{{ $sale->customer ? $sale->customer->name : 'Walk-in' }}</td>
                                                        <td>${{ number_format($sale->total_amount, 2) }}</td>
                                                        <td><span class="badge bg-success">{{ ucfirst($sale->status) }}</span></td>
                                                        <td>{{ $sale->sale_date->format('M d, Y') }}</td>
                                                    </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-xl-4">
                                <div class="card">
                                    <div class="card-body">
                                        <h4 class="card-title mb-4">Low Stock Alert</h4>
                                        <div class="list-group">
                                            @foreach(\App\Models\Product::where('stock_quantity', '<=', \DB::raw('min_stock_level'))->take(5)->get() as $product)
                                            <a href="{{ route('products.show', $product) }}" class="list-group-item list-group-item-action">
                                                <div class="d-flex w-100 justify-content-between">
                                                    <h6 class="mb-1">{{ $product->name }}</h6>
                                                    <small class="text-muted">{{ $product->stock_quantity }} left</small>
                                                </div>
                                                <small class="text-warning">Min: {{ $product->min_stock_level }}</small>
                                            </a>
                                            @endforeach
                                            @if(\App\Models\Product::where('stock_quantity', '<=', \DB::raw('min_stock_level'))->count() == 0)
                                            <div class="text-center py-4">
                                                <i class="mdi mdi-check-circle text-success font-size-48"></i>
                                                <p class="text-muted mt-2">All products are well stocked!</p>
                                            </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
