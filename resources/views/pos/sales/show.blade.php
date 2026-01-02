@extends('layouts.master')

@section('title', 'Sale Details')

@section('content')
<div class="row">
    <div class="col-xl-8">
        <div class="card">
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-md-6">
                        <h4 class="card-title">Sale #{{ $sale->invoice_number }}</h4>
                        <span class="badge {{ $sale->status === 'completed' ? 'bg-success' : ($sale->status === 'cancelled' ? 'bg-danger' : 'bg-warning') }}">
                            {{ ucfirst($sale->status) }}
                        </span>
                    </div>
                    <div class="col-md-6 text-end">
                        @if($sale->status === 'completed')
                        <button onclick="window.print()" class="btn btn-info">
                            <i class="mdi mdi-printer"></i> Print Receipt
                        </button>
                        @endif
                        <a href="{{ route('sales.index') }}" class="btn btn-secondary">
                            <i class="mdi mdi-arrow-left"></i> Back to Sales
                        </a>
                    </div>
                </div>

                <!-- Sale Details -->
                <div class="row">
                    <div class="col-md-6">
                        <table class="table table-borderless">
                            <tr>
                                <th>Invoice Number:</th>
                                <td>{{ $sale->invoice_number }}</td>
                            </tr>
                            <tr>
                                <th>Customer:</th>
                                <td>{{ $sale->customer ? $sale->customer->name : 'Walk-in Customer' }}</td>
                            </tr>
                            <tr>
                                <th>Cashier:</th>
                                <td>{{ $sale->user->name }}</td>
                            </tr>
                            <tr>
                                <th>Sale Date:</th>
                                <td>{{ $sale->sale_date->format('M d, Y H:i') }}</td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <table class="table table-borderless">
                            <tr>
                                <th>Payment Method:</th>
                                <td>{{ ucfirst($sale->payment_method) }}</td>
                            </tr>
                            <tr>
                                <th>Total Amount:</th>
                                <td><strong class="text-primary">${{ number_format($sale->total_amount, 2) }}</strong></td>
                            </tr>
                            <tr>
                                <th>Paid Amount:</th>
                                <td>${{ number_format($sale->paid_amount, 2) }}</td>
                            </tr>
                            <tr>
                                <th>Change:</th>
                                <td>${{ number_format($sale->change_amount, 2) }}</td>
                            </tr>
                        </table>
                    </div>
                </div>

                <!-- Sale Items -->
                <div class="mt-4">
                    <h5 class="mb-3">Items Sold</h5>
                    <div class="table-responsive">
                        <table class="table table-centered table-nowrap">
                            <thead class="table-light">
                                <tr>
                                    <th>Product</th>
                                    <th>SKU</th>
                                    <th>Quantity</th>
                                    <th>Unit Price</th>
                                    <th>Discount</th>
                                    <th>Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($sale->saleItems as $item)
                                <tr>
                                    <td>
                                        <strong>{{ $item->product->name }}</strong>
                                    </td>
                                    <td>{{ $item->product->sku }}</td>
                                    <td>{{ $item->quantity }} {{ $item->product->unit }}</td>
                                    <td>${{ number_format($item->unit_price, 2) }}</td>
                                    <td>${{ number_format($item->discount, 2) }}</td>
                                    <td><strong>${{ number_format($item->total_price, 2) }}</strong></td>
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
                            <h6>Order Summary</h6>
                            <table class="table table-sm table-borderless mb-0">
                                <tr>
                                    <td>Subtotal:</td>
                                    <td class="text-end">${{ number_format($sale->subtotal, 2) }}</td>
                                </tr>
                                <tr>
                                    <td>Tax:</td>
                                    <td class="text-end">${{ number_format($sale->tax_amount, 2) }}</td>
                                </tr>
                                <tr>
                                    <td>Discount:</td>
                                    <td class="text-end">-${{ number_format($sale->discount_amount, 2) }}</td>
                                </tr>
                                <tr class="border-top">
                                    <td><strong>Total:</strong></td>
                                    <td class="text-end"><strong>${{ number_format($sale->total_amount, 2) }}</strong></td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-4">
        <!-- Print Receipt -->
        <div class="card" id="receipt" style="display: none;">
            <div class="card-body text-center">
                <h5>RECEIPT</h5>
                <p class="mb-1"><strong>{{ config('app.name') }}</strong></p>
                <p class="mb-1">{{ $sale->invoice_number }}</p>
                <p class="mb-1">{{ $sale->sale_date->format('M d, Y H:i') }}</p>
                <p class="mb-3">Cashier: {{ $sale->user->name }}</p>

                <div class="text-start mb-3">
                    @foreach($sale->saleItems as $item)
                    <div class="d-flex justify-content-between mb-1">
                        <span>{{ Str::limit($item->product->name, 20) }}</span>
                        <span>{{ $item->quantity }} x ${{ number_format($item->unit_price, 2) }}</span>
                    </div>
                    @endforeach
                </div>

                <hr>
                <div class="d-flex justify-content-between mb-1">
                    <span>Subtotal:</span>
                    <span>${{ number_format($sale->subtotal, 2) }}</span>
                </div>
                @if($sale->tax_amount > 0)
                <div class="d-flex justify-content-between mb-1">
                    <span>Tax:</span>
                    <span>${{ number_format($sale->tax_amount, 2) }}</span>
                </div>
                @endif
                @if($sale->discount_amount > 0)
                <div class="d-flex justify-content-between mb-1">
                    <span>Discount:</span>
                    <span>-${{ number_format($sale->discount_amount, 2) }}</span>
                </div>
                @endif
                <div class="d-flex justify-content-between">
                    <strong>Total:</strong>
                    <strong>${{ number_format($sale->total_amount, 2) }}</strong>
                </div>
                <div class="d-flex justify-content-between mt-2">
                    <span>Paid:</span>
                    <span>${{ number_format($sale->paid_amount, 2) }}</span>
                </div>
                <div class="d-flex justify-content-between">
                    <span>Change:</span>
                    <span>${{ number_format($sale->change_amount, 2) }}</span>
                </div>

                <p class="mt-3 mb-0">Thank you for your business!</p>
            </div>
        </div>

        <!-- Actions -->
        @if($sale->status === 'completed')
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Actions</h5>
                <div class="d-grid gap-2">
                    <button onclick="window.print()" class="btn btn-outline-primary">
                        <i class="mdi mdi-printer"></i> Print Receipt
                    </button>
                    <button onclick="emailReceipt()" class="btn btn-outline-info">
                        <i class="mdi mdi-email"></i> Email Receipt
                    </button>
                </div>
            </div>
        </div>
        @endif
    </div>
</div>
@endsection

@push('scripts')
<script>
function emailReceipt() {
    // Implement email functionality
    alert('Email functionality would be implemented here');
}
</script>
@endpush
