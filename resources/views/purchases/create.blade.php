@extends('layouts.master')

@section('title', 'New Purchase')

@section('content')
<div class="row">
    <!-- Purchase Form & Items -->
    <div class="col-xl-8">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title mb-4">New Purchase Order</h4>

                <!-- Supplier Selection -->
                <div class="row mb-4">
                    <div class="col-md-6">
                        <label for="supplierSelect" class="form-label">Supplier *</label>
                        <select id="supplierSelect" class="form-select" required>
                            <option value="">Select Supplier</option>
                            @foreach($suppliers as $supplier)
                            <option value="{{ $supplier->id }}">{{ $supplier->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label for="paymentMethod" class="form-label">Payment Method *</label>
                        <select id="paymentMethod" class="form-select" required>
                            <option value="">Select Payment Method</option>
                            <option value="cash">Cash</option>
                            <option value="card">Card</option>
                            <option value="online">Online Transfer</option>
                            <option value="credit">Credit</option>
                        </select>
                    </div>
                </div>

                <!-- Product Search -->
                <div class="row mb-4">
                    <div class="col-md-8">
                        <div class="input-group">
                            <input type="text" id="productSearch" class="form-control" placeholder="Search product by name, SKU..."
                                   autofocus>
                            <button class="btn btn-outline-secondary" type="button" id="searchBtn">
                                <i class="mdi mdi-magnify"></i>
                            </button>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="input-group">
                            <span class="input-group-text">Tax Rate (%)</span>
                            <input type="number" id="taxRate" class="form-control" value="0" min="0" max="100" step="0.01">
                        </div>
                    </div>
                </div>

                <!-- Items Table -->
                <div class="table-responsive">
                    <table class="table table-centered table-nowrap" id="purchaseTable">
                        <thead class="table-light">
                            <tr>
                                <th>Product</th>
                                <th>Qty</th>
                                <th>Unit Cost</th>
                                <th>Selling Price</th>
                                <th>Total</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody id="purchaseBody">
                            <tr id="emptyPurchase">
                                <td colspan="6" class="text-center py-4">
                                    <i class="mdi mdi-package-variant-closed font-size-48 text-muted"></i>
                                    <h5 class="mt-2">No items added</h5>
                                    <p class="text-muted">Search and add products to create purchase order</p>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Summary Panel -->
    <div class="col-xl-4">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title mb-4">Purchase Summary</h4>

                <!-- Order Summary -->
                <div class="table-responsive">
                    <table class="table table-borderless">
                        <tr>
                            <td>Subtotal:</td>
                            <td class="text-end" id="subtotal">$0.00</td>
                        </tr>
                        <tr>
                            <td>Tax (0%):</td>
                            <td class="text-end" id="taxAmount">$0.00</td>
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
                    <h5 class="mb-3">Payment Details</h5>
                    <div class="mb-3">
                        <label for="paidAmount" class="form-label">Amount Paid</label>
                        <div class="input-group">
                            <span class="input-group-text">$</span>
                            <input type="number" id="paidAmount" class="form-control" placeholder="0.00" min="0" step="0.01">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="notes" class="form-label">Notes</label>
                        <textarea id="notes" class="form-control" rows="3" placeholder="Purchase notes..."></textarea>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="d-grid gap-2">
                    <button type="button" id="completePurchaseBtn" class="btn btn-success btn-lg" disabled>
                        <i class="mdi mdi-check"></i> Complete Purchase
                    </button>
                    <button type="button" id="clearPurchaseBtn" class="btn btn-outline-secondary">
                        <i class="mdi mdi-refresh"></i> Clear All
                    </button>
                    <a href="{{ route('purchases.index') }}" class="btn btn-outline-primary">
                        <i class="mdi mdi-arrow-left"></i> Back to Purchases
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Product Selection Modal -->
<div class="modal fade" id="productModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add Product to Purchase</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div id="productInfo"></div>
                <div class="mb-3">
                    <label for="purchaseQty" class="form-label">Quantity *</label>
                    <input type="number" id="purchaseQty" class="form-control" value="1" min="1">
                </div>
                <div class="mb-3">
                    <label for="unitCost" class="form-label">Unit Cost *</label>
                    <div class="input-group">
                        <span class="input-group-text">$</span>
                        <input type="number" id="unitCost" class="form-control" step="0.01" min="0">
                    </div>
                </div>
                <div class="mb-3">
                    <label for="sellingPrice" class="form-label">Selling Price (Optional)</label>
                    <div class="input-group">
                        <span class="input-group-text">$</span>
                        <input type="number" id="sellingPrice" class="form-control" step="0.01" min="0">
                    </div>
                    <small class="form-text text-muted">Update product selling price</small>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" id="addToPurchaseBtn" class="btn btn-primary">Add to Purchase</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    let purchaseItems = [];
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

    function searchProduct(query) {
        if (!query) return;

        // Simple search - you could enhance this with AJAX
        // For now, we'll show a modal with product selection
        // In a real implementation, this would search the products array
        $('#productSearch').val('');
        alert('Product search functionality - select from available products in the modal');
        // You could implement a product selection modal here
    }

    // For demo purposes, let's add a simple way to add products
    // In production, you'd want a proper product search/modal

    // Calculate totals
    function updateTotals() {
        const subtotal = purchaseItems.reduce((sum, item) => sum + item.total, 0);
        const taxRate = parseFloat($('#taxRate').val()) || 0;
        const taxAmount = subtotal * (taxRate / 100);
        const discountAmount = parseFloat($('#discountAmount').val()) || 0;
        const total = subtotal + taxAmount - discountAmount;

        $('#subtotal').text('$' + subtotal.toFixed(2));
        $('#taxAmount').text('$' + taxAmount.toFixed(2));
        $('#totalAmount').text('$' + total.toFixed(2));

        // Enable/disable complete purchase button
        $('#completePurchaseBtn').prop('disabled', purchaseItems.length === 0 || !$('#supplierSelect').val());
    }

    // Tax and discount changes
    $('#taxRate, #discountAmount').on('change', updateTotals);

    // Complete purchase
    $('#completePurchaseBtn').on('click', function() {
        const supplierId = $('#supplierSelect').val();
        const paymentMethod = $('#paymentMethod').val();
        const paidAmount = parseFloat($('#paidAmount').val()) || 0;
        const taxRate = parseFloat($('#taxRate').val()) || 0;
        const discountAmount = parseFloat($('#discountAmount').val()) || 0;
        const notes = $('#notes').val();

        if (!supplierId) {
            alert('Please select a supplier');
            return;
        }

        if (!paymentMethod) {
            alert('Please select a payment method');
            return;
        }

        if (purchaseItems.length === 0) {
            alert('Please add at least one item');
            return;
        }

        const purchaseData = {
            supplier_id: supplierId,
            items: purchaseItems.map(item => ({
                product_id: item.id,
                quantity: item.quantity,
                unit_cost: item.unit_cost,
                selling_price: item.selling_price
            })),
            tax_amount: taxRate,
            discount_amount: discountAmount,
            payment_method: paymentMethod,
            paid_amount: paidAmount,
            notes: notes,
            _token: '{{ csrf_token() }}'
        };

        $.post("{{ route('purchases.store') }}", purchaseData)
            .done(function(response) {
                if (response.success) {
                    alert('Purchase completed successfully!');
                    // Reset form
                    purchaseItems = [];
                    updatePurchaseDisplay();
                    updateTotals();
                    $('#supplierSelect, #paymentMethod, #paidAmount, #notes').val('');
                } else {
                    alert('Error: ' + response.message);
                }
            })
            .fail(function(xhr) {
                const response = xhr.responseJSON;
                alert('Error: ' + (response?.message || 'Failed to complete purchase'));
            });
    });

    // Clear purchase
    $('#clearPurchaseBtn').on('click', function() {
        if (confirm('Are you sure you want to clear the purchase?')) {
            purchaseItems = [];
            updatePurchaseDisplay();
            updateTotals();
        }
    });

    function updatePurchaseDisplay() {
        const tbody = $('#purchaseBody');
        tbody.empty();

        if (purchaseItems.length === 0) {
            tbody.html(`
                <tr id="emptyPurchase">
                    <td colspan="6" class="text-center py-4">
                        <i class="mdi mdi-package-variant-closed font-size-48 text-muted"></i>
                        <h5 class="mt-2">No items added</h5>
                        <p class="text-muted">Search and add products to create purchase order</p>
                    </td>
                </tr>
            `);
            return;
        }

        purchaseItems.forEach((item, index) => {
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
                    <td>
                        <input type="number" class="form-control form-control-sm cost-input"
                               value="${item.unit_cost}" min="0" step="0.01" data-index="${index}">
                    </td>
                    <td>
                        <input type="number" class="form-control form-control-sm price-input"
                               value="${item.selling_price || ''}" min="0" step="0.01" placeholder="Optional" data-index="${index}">
                    </td>
                    <td><strong>$${(item.total).toFixed(2)}</strong></td>
                    <td>
                        <button class="btn btn-sm btn-outline-danger remove-item" data-index="${index}">
                            <i class="mdi mdi-delete"></i>
                        </button>
                    </td>
                </tr>
            `);
        });
    }

    // Item quantity/cost changes
    $(document).on('change', '.quantity-input', function() {
        const index = $(this).data('index');
        const newQty = parseInt($(this).val()) || 1;
        purchaseItems[index].quantity = newQty;
        purchaseItems[index].total = newQty * purchaseItems[index].unit_cost;
        updateTotals();
    });

    $(document).on('change', '.cost-input', function() {
        const index = $(this).data('index');
        const newCost = parseFloat($(this).val()) || 0;
        purchaseItems[index].unit_cost = newCost;
        purchaseItems[index].total = purchaseItems[index].quantity * newCost;
        updateTotals();
    });

    $(document).on('change', '.price-input', function() {
        const index = $(this).data('index');
        purchaseItems[index].selling_price = parseFloat($(this).val()) || null;
    });

    $(document).on('click', '.remove-item', function() {
        const index = $(this).data('index');
        purchaseItems.splice(index, 1);
        updatePurchaseDisplay();
        updateTotals();
    });

    // Supplier selection change
    $('#supplierSelect').on('change', function() {
        updateTotals(); // Recheck if complete button should be enabled
    });

    // For demo purposes, add a sample product button
    $('#completePurchaseBtn').before(`
        <button type="button" id="addSampleBtn" class="btn btn-outline-info mb-2">
            <i class="mdi mdi-plus"></i> Add Sample Product
        </button>
    `);

    $('#addSampleBtn').on('click', function() {
        // Add a sample product for testing
        const sampleProducts = [
            { id: 1, name: 'Wireless Mouse', sku: 'WM-001', unit_cost: 15.50, selling_price: 29.99 },
            { id: 2, name: 'Mechanical Keyboard', sku: 'KB-002', unit_cost: 45.00, selling_price: 89.99 },
            { id: 3, name: 'USB Flash Drive 32GB', sku: 'USB-003', unit_cost: 8.75, selling_price: 19.99 }
        ];

        const randomProduct = sampleProducts[Math.floor(Math.random() * sampleProducts.length)];

        purchaseItems.push({
            id: randomProduct.id,
            name: randomProduct.name,
            sku: randomProduct.sku,
            quantity: 1,
            unit_cost: randomProduct.unit_cost,
            selling_price: randomProduct.selling_price,
            total: randomProduct.unit_cost
        });

        updatePurchaseDisplay();
        updateTotals();
    });
});
</script>
@endpush
