@extends('layouts.master')

@section('title', 'Point of Sale')

@section('content')
<div class="row">
    <!-- Product Search & Cart -->
    <div class="col-xl-8">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title mb-4">Point of Sale</h4>

                <!-- Product Search -->
                <div class="row mb-4">
                    <div class="col-md-6">
                        <div class="input-group">
                            <input type="text" id="productSearch" class="form-control" placeholder="Scan barcode or enter SKU..."
                                   autofocus>
                            <button class="btn btn-outline-secondary" type="button" id="searchBtn">
                                <i class="mdi mdi-magnify"></i>
                            </button>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <select id="customerSelect" class="form-select">
                            <option value="">Walk-in Customer</option>
                            @foreach($customers as $customer)
                            <option value="{{ $customer->id }}">{{ $customer->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <!-- Cart Table -->
                <div class="table-responsive">
                    <table class="table table-centered table-nowrap" id="cartTable">
                        <thead class="table-light">
                            <tr>
                                <th>Product</th>
                                <th>Qty</th>
                                <th>Unit Price</th>
                                <th>Discount</th>
                                <th>Total</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody id="cartBody">
                            <tr id="emptyCart">
                                <td colspan="6" class="text-center py-4">
                                    <i class="mdi mdi-cart-outline font-size-48 text-muted"></i>
                                    <h5 class="mt-2">Cart is empty</h5>
                                    <p class="text-muted">Scan or search for products to add to cart</p>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Checkout Panel -->
    <div class="col-xl-4">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title mb-4">Checkout</h4>

                <!-- Order Summary -->
                <div class="table-responsive">
                    <table class="table table-borderless">
                        <tr>
                            <td>Subtotal:</td>
                            <td class="text-end" id="subtotal">$0.00</td>
                        </tr>
                        <tr>
                            <td>Tax (0%):</td>
                            <td class="text-end">
                                <input type="number" id="taxRate" class="form-control form-control-sm d-inline-block w-50" value="0" min="0" max="100" step="0.01">
                                <span id="taxAmount">$0.00</span>
                            </td>
                        </tr>
                        <tr>
                            <td>Discount:</td>
                            <td class="text-end">
                                <input type="number" id="discountAmount" class="form-control form-control-sm d-inline-block w-75" value="0" min="0" step="0.01">
                            </td>
                        </tr>
                        <tr class="border-top">
                            <td><strong>Total:</strong></td>
                            <td class="text-end"><strong id="totalAmount">$0.00</strong></td>
                        </tr>
                    </table>
                </div>

                <!-- Payment -->
                <div class="mt-4">
                    <h5 class="mb-3">Payment</h5>
                    <div class="mb-3">
                        <select id="paymentMethod" class="form-select">
                            <option value="cash">Cash</option>
                            <option value="card">Card</option>
                            <option value="online">Online</option>
                        </select>
                    </div>
                    <div class="input-group mb-3">
                        <span class="input-group-text">$</span>
                        <input type="number" id="paidAmount" class="form-control" placeholder="Amount paid" min="0" step="0.01">
                    </div>
                    <div class="mb-3">
                        <strong>Change: <span id="changeAmount">$0.00</span></strong>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="d-grid gap-2">
                    <button type="button" id="completeSaleBtn" class="btn btn-success btn-lg" disabled>
                        <i class="mdi mdi-check"></i> Complete Sale
                    </button>
                    <button type="button" id="clearCartBtn" class="btn btn-outline-secondary">
                        <i class="mdi mdi-refresh"></i> Clear Cart
                    </button>
                    <a href="{{ route('sales.index') }}" class="btn btn-outline-primary">
                        <i class="mdi mdi-arrow-left"></i> Back to Sales
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Product Quick Add Modal -->
<div class="modal fade" id="productModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add Product to Cart</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div id="productInfo"></div>
                <div class="mb-3">
                    <label for="quantity" class="form-label">Quantity</label>
                    <input type="number" id="quantity" class="form-control" value="1" min="1">
                </div>
                <div class="mb-3">
                    <label for="customPrice" class="form-label">Custom Price (optional)</label>
                    <input type="number" id="customPrice" class="form-control" step="0.01" min="0">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" id="addToCartBtn" class="btn btn-primary">Add to Cart</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    let cart = [];
    let currentProduct = null;

    // Product search
    $('#productSearch').on('keypress', function(e) {
        if (e.which === 13) { // Enter key
            searchProduct($(this).val());
        }
    });

    $('#searchBtn').on('click', function() {
        searchProduct($('#productSearch').val());
    });

    function searchProduct(identifier) {
        if (!identifier) return;

        $.get("{{ route('sales.getProduct') }}", { identifier: identifier })
            .done(function(response) {
                if (response.success) {
                    currentProduct = response.product;
                    showProductModal(currentProduct);
                } else {
                    showAlert('error', response.message);
                }
            })
            .fail(function() {
                showAlert('error', 'Failed to search product');
            });

        $('#productSearch').val('');
    }

    function showProductModal(product) {
        $('#productInfo').html(`
            <div class="card">
                <div class="card-body">
                    <h6>${product.name}</h6>
                    <p class="text-muted mb-1">SKU: ${product.sku}</p>
                    <p class="text-muted mb-1">Stock: ${product.stock_quantity} ${product.unit}</p>
                    <p class="text-primary fw-bold">Price: $${product.selling_price}</p>
                </div>
            </div>
        `);
        $('#quantity').val(1);
        $('#customPrice').val('');
        $('#productModal').modal('show');
    }

    // Add to cart
    $('#addToCartBtn').on('click', function() {
        if (!currentProduct) return;

        const quantity = parseInt($('#quantity').val()) || 1;
        const customPrice = parseFloat($('#customPrice').val()) || currentProduct.selling_price;

        addToCart(currentProduct, quantity, customPrice);
        $('#productModal').modal('hide');
        currentProduct = null;
    });

    function addToCart(product, quantity, price) {
        // Check if product already in cart
        const existingIndex = cart.findIndex(item => item.id === product.id);
        if (existingIndex >= 0) {
            cart[existingIndex].quantity += quantity;
        } else {
            cart.push({
                id: product.id,
                name: product.name,
                sku: product.sku,
                unit: product.unit,
                quantity: quantity,
                unit_price: price,
                discount: 0,
                total: quantity * price
            });
        }

        updateCartDisplay();
        updateTotals();
    }

    function updateCartDisplay() {
        const tbody = $('#cartBody');
        tbody.empty();

        if (cart.length === 0) {
            tbody.html(`
                <tr id="emptyCart">
                    <td colspan="6" class="text-center py-4">
                        <i class="mdi mdi-cart-outline font-size-48 text-muted"></i>
                        <h5 class="mt-2">Cart is empty</h5>
                        <p class="text-muted">Scan or search for products to add to cart</p>
                    </td>
                </tr>
            `);
            return;
        }

        cart.forEach((item, index) => {
            tbody.append(`
                <tr>
                    <td>
                        <strong>${item.name}</strong><br>
                        <small class="text-muted">${item.sku}</small>
                    </td>
                    <td>
                        <input type="number" class="form-control form-control-sm quantity-input"
                               value="${item.quantity}" min="1" data-index="${index}">
                    </td>
                    <td>$${item.unit_price.toFixed(2)}</td>
                    <td>
                        <input type="number" class="form-control form-control-sm discount-input"
                               value="${item.discount}" min="0" step="0.01" data-index="${index}">
                    </td>
                    <td><strong>$${(item.total - item.discount).toFixed(2)}</strong></td>
                    <td>
                        <button class="btn btn-sm btn-outline-danger remove-item" data-index="${index}">
                            <i class="mdi mdi-delete"></i>
                        </button>
                    </td>
                </tr>
            `);
        });
    }

    // Cart item events
    $(document).on('change', '.quantity-input', function() {
        const index = $(this).data('index');
        const newQty = parseInt($(this).val()) || 1;
        cart[index].quantity = newQty;
        cart[index].total = newQty * cart[index].unit_price;
        updateTotals();
        updateCartDisplay();
    });

    $(document).on('change', '.discount-input', function() {
        const index = $(this).data('index');
        const discount = parseFloat($(this).val()) || 0;
        cart[index].discount = discount;
        updateTotals();
    });

    $(document).on('click', '.remove-item', function() {
        const index = $(this).data('index');
        cart.splice(index, 1);
        updateCartDisplay();
        updateTotals();
    });

    // Totals calculation
    function updateTotals() {
        const subtotal = cart.reduce((sum, item) => sum + item.total, 0);
        const taxRate = parseFloat($('#taxRate').val()) || 0;
        const taxAmount = subtotal * (taxRate / 100);
        const discountAmount = parseFloat($('#discountAmount').val()) || 0;
        const total = subtotal + taxAmount - discountAmount;

        $('#subtotal').text('$' + subtotal.toFixed(2));
        $('#taxAmount').text('$' + taxAmount.toFixed(2));
        $('#totalAmount').text('$' + total.toFixed(2));

        // Enable/disable complete sale button
        $('#completeSaleBtn').prop('disabled', cart.length === 0);
    }

    // Tax and discount changes
    $('#taxRate, #discountAmount').on('change', updateTotals);

    // Payment calculation
    $('#paidAmount').on('input', function() {
        const paid = parseFloat($(this).val()) || 0;
        const total = parseFloat($('#totalAmount').text().replace('$', '')) || 0;
        const change = Math.max(0, paid - total);
        $('#changeAmount').text('$' + change.toFixed(2));
    });

    // Complete sale
    $('#completeSaleBtn').on('click', function() {
        const total = parseFloat($('#totalAmount').text().replace('$', '')) || 0;
        const paid = parseFloat($('#paidAmount').val()) || 0;

        if (paid < total) {
            showAlert('error', 'Paid amount must be at least the total amount');
            return;
        }

        const saleData = {
            customer_id: $('#customerSelect').val() || null,
            items: cart.map(item => ({
                product_id: item.id,
                quantity: item.quantity,
                unit_price: item.unit_price,
                discount: item.discount
            })),
            tax_amount: $('#taxRate').val(),
            discount_amount: $('#discountAmount').val(),
            payment_method: $('#paymentMethod').val(),
            paid_amount: paid,
            _token: '{{ csrf_token() }}'
        };

        $.post("{{ route('pos.store') }}", saleData)
            .done(function(response) {
                if (response.success) {
                    showAlert('success', 'Sale completed successfully!');
                    // Reset cart and form
                    cart = [];
                    updateCartDisplay();
                    updateTotals();
                    $('#paidAmount').val('');
                    $('#changeAmount').text('$0.00');
                    $('#customerSelect').val('');

                    // Show receipt or redirect
                    setTimeout(() => {
                        window.location.href = "{{ route('sales.show', ':id') }}".replace(':id', response.sale.id);
                    }, 1500);
                } else {
                    showAlert('error', response.message);
                }
            })
            .fail(function(xhr) {
                const response = xhr.responseJSON;
                showAlert('error', response?.message || 'Failed to complete sale');
            });
    });

    // Clear cart
    $('#clearCartBtn').on('click', function() {
        if (confirm('Are you sure you want to clear the cart?')) {
            cart = [];
            updateCartDisplay();
            updateTotals();
        }
    });

    function showAlert(type, message) {
        // Simple alert - you can replace with a proper notification system
        alert(message);
    }
});
</script>
@endpush
