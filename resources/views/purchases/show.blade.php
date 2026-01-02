@extends('layouts.master')

@section('title', 'Purchase Details')

@section('content')
<div class="row">
    <div class="col-xl-8">
        <div class="card">
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-md-6">
                        <h4 class="card-title">Purchase #{{ $purchase->purchase_number }}</h4>
                        <span class="badge {{ $purchase->status === 'completed' ? 'bg-success' : ($purchase->status === 'cancelled' ? 'bg-danger' : 'bg-warning') }}">
                            {{ ucfirst($purchase->status) }}
                        </span>
                    </div>
                    <div class="col-md-6 text-end">
                        <a href="{{ route('purchases.index') }}" class="btn btn-secondary">
                            <i class="mdi mdi-arrow-left"></i> Back to Purchases
                        </a>
                    </div>
                </div>

                <!-- Purchase Details -->
                <div class="row">
                    <div class="col-md-6">
                        <table class="table table-borderless">
                            <tr>
                                <th>Purchase Number:</th>
                                <td>{{ $purchase->purchase_number }}</td>
                            </tr>
                            <tr>
                                <th>Supplier:</th>
                                <td>{{ $purchase->supplier->name }}</td>
                            </tr>
                            <tr>
                                <th>User:</th>
                                <td>{{ $purchase->user->name }}</td>
                            </tr>
                            <tr>
                                <th>Purchase Date:</th>
                                <td>{{ $purchase->purchase_date->format('M d, Y H:i') }}</td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <table class="table table-borderless">
                            <tr>
                                <th>Payment Method:</th>
                                <td>{{ ucfirst($purchase->payment_method) }}</td>
                            </tr>
                            <tr>
                                <th>Total Amount:</th>
                                <td><strong class="text-primary">${{ number_format($purchase->total_amount, 2) }}</strong></td>
                            </tr>
                            <tr>
                                <th>Paid Amount:</th>
                                <td>${{ number_format($purchase->paid_amount, 2) }}</td>
                            </tr>
                            @if($purchase->notes)
                            <tr>
                                <th>Notes:</th>
                                <td>{{ $purchase->notes }}</td>
                            </tr>
                            @endif
                        </table>
                    </div>
                </div>

                <!-- Purchase Items -->
                <div class="mt-4">
                    <h5 class="mb-3">Purchased Items</h5>
                    <div class="table-responsive">
                        <table class="table table-centered table-nowrap">
                            <thead class="table-light">
                                <tr>
                                    <th>Product</th>
                                    <th>SKU</th>
                                    <th>Quantity</th>
                                    <th>Unit Cost</th>
                                    <th>Selling Price</th>
                                    <th>Total Cost</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($purchase->purchaseItems as $item)
                                <tr>
                                    <td>
                                        <strong>{{ $item->product->name }}</strong>
                                    </td>
                                    <td>{{ $item->product->sku }}</td>
                                    <td>{{ $item->quantity }} {{ $item->product->unit }}</td>
                                    <td>${{ number_format($item->unit_cost, 2) }}</td>
                                    <td>${{ number_format($item->product->selling_price, 2) }}</td>
                                    <td><strong>${{ number_format($item->total_cost, 2) }}</strong></td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Summary -->
                <div class="row mt-4">
                    <div class="col-md-6">
                        <div class="border p-3 rounded">
                            <h6>Purchase Summary</h6>
                            <table class="table table-sm table-borderless mb-0">
                                <tr>
                                    <td>Subtotal:</td>
                                    <td class="text-end">${{ number_format($purchase->subtotal, 2) }}</td>
                                </tr>
                                <tr>
                                    <td>Tax:</td>
                                    <td class="text-end">${{ number_format($purchase->tax_amount, 2) }}</td>
                                </tr>
                                <tr>
                                    <td>Discount:</td>
                                    <td class="text-end">-${{ number_format($purchase->discount_amount, 2) }}</td>
                                </tr>
                                <tr class="border-top">
                                    <td><strong>Total:</strong></td>
                                    <td class="text-end"><strong>${{ number_format($purchase->total_amount, 2) }}</strong></td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-4">
        <!-- Statistics -->
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Purchase Statistics</h5>
                <div class="text-center">
                    <div class="mb-3">
                        <h2 class="text-primary">{{ $purchase->purchaseItems->sum('quantity') }}</h2>
                        <p class="text-muted mb-0">Total Items</p>
                    </div>
                    <div class="mb-3">
                        <h2 class="text-success">${{ number_format($purchase->subtotal, 0) }}</h2>
                        <p class="text-muted mb-0">Cost Value</p>
                    </div>
                    <div>
                        <h2 class="text-info">{{ $purchase->purchaseItems->count() }}</h2>
                        <p class="text-muted mb-0">Different Products</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Actions -->
        @if($purchase->status === 'completed')
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Actions</h5>
                <div class="d-grid gap-2">
                    <button onclick="window.print()" class="btn btn-outline-primary">
                        <i class="mdi mdi-printer"></i> Print Purchase Order
                    </button>
                    <div class="mt-3">
                        <form action="{{ route('purchases.cancel', $purchase) }}" method="POST" class="d-inline"
                              onsubmit="return confirm('Are you sure you want to cancel this purchase? This will reduce inventory stock.')">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="btn btn-danger btn-sm">
                                <i class="mdi mdi-cancel"></i> Cancel Purchase
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        @endif
    </div>
</div>
@endsection
