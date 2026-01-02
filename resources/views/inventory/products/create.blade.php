@extends('layouts.master')

@section('title', 'Add Product')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-md-6">
                        <h4 class="card-title">Add New Product</h4>
                    </div>
                    <div class="col-md-6 text-end">
                        <a href="{{ route('products.index') }}" class="btn btn-secondary">
                            <i class="mdi mdi-arrow-left"></i> Back to Products
                        </a>
                    </div>
                </div>

                <form method="POST" action="{{ route('products.store') }}">
                    @csrf

                    <div class="row">
                        <div class="col-md-8">
                            <div class="row">
                                <!-- Basic Information -->
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="name" class="form-label">Product Name *</label>
                                        <input type="text" class="form-control @error('name') is-invalid @enderror"
                                               id="name" name="name" value="{{ old('name') }}" required>
                                        @error('name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="sku" class="form-label">SKU *</label>
                                        <input type="text" class="form-control @error('sku') is-invalid @enderror"
                                               id="sku" name="sku" value="{{ old('sku') }}" required>
                                        @error('sku')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="barcode" class="form-label">Barcode</label>
                                        <input type="text" class="form-control @error('barcode') is-invalid @enderror"
                                               id="barcode" name="barcode" value="{{ old('barcode') }}">
                                        @error('barcode')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="category" class="form-label">Category</label>
                                        <input type="text" class="form-control @error('category') is-invalid @enderror"
                                               id="category" name="category" value="{{ old('category') }}">
                                        @error('category')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <div class="mb-3">
                                        <label for="description" class="form-label">Description</label>
                                        <textarea class="form-control @error('description') is-invalid @enderror"
                                                  id="description" name="description" rows="3">{{ old('description') }}</textarea>
                                        @error('description')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <!-- Pricing Information -->
                            <div class="card border">
                                <div class="card-body">
                                    <h5 class="card-title">Pricing & Stock</h5>

                                    <div class="mb-3">
                                        <label for="cost_price" class="form-label">Cost Price *</label>
                                        <div class="input-group">
                                            <span class="input-group-text">$</span>
                                            <input type="number" step="0.01" class="form-control @error('cost_price') is-invalid @enderror"
                                                   id="cost_price" name="cost_price" value="{{ old('cost_price') }}" required>
                                        </div>
                                        @error('cost_price')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label for="selling_price" class="form-label">Selling Price *</label>
                                        <div class="input-group">
                                            <span class="input-group-text">$</span>
                                            <input type="number" step="0.01" class="form-control @error('selling_price') is-invalid @enderror"
                                                   id="selling_price" name="selling_price" value="{{ old('selling_price') }}" required>
                                        </div>
                                        @error('selling_price')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label for="unit" class="form-label">Unit *</label>
                                        <select class="form-select @error('unit') is-invalid @enderror" id="unit" name="unit" required>
                                            <option value="">Select Unit</option>
                                            <option value="pcs" {{ old('unit') == 'pcs' ? 'selected' : '' }}>Pieces (pcs)</option>
                                            <option value="kg" {{ old('unit') == 'kg' ? 'selected' : '' }}>Kilograms (kg)</option>
                                            <option value="liter" {{ old('unit') == 'liter' ? 'selected' : '' }}>Liters (L)</option>
                                            <option value="meter" {{ old('unit') == 'meter' ? 'selected' : '' }}>Meters (m)</option>
                                        </select>
                                        @error('unit')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label for="stock_quantity" class="form-label">Initial Stock Quantity *</label>
                                        <input type="number" class="form-control @error('stock_quantity') is-invalid @enderror"
                                               id="stock_quantity" name="stock_quantity" value="{{ old('stock_quantity', 0) }}" required>
                                        @error('stock_quantity')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label for="min_stock_level" class="form-label">Minimum Stock Level *</label>
                                        <input type="number" class="form-control @error('min_stock_level') is-invalid @enderror"
                                               id="min_stock_level" name="min_stock_level" value="{{ old('min_stock_level', 10) }}" required>
                                        @error('min_stock_level')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12">
                            <button type="submit" class="btn btn-primary">
                                <i class="mdi mdi-content-save"></i> Create Product
                            </button>
                            <a href="{{ route('products.index') }}" class="btn btn-secondary">Cancel</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
