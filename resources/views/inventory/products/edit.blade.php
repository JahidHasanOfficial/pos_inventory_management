@extends('layouts.master')

@section('title', 'Edit Product')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-md-6">
                        <h4 class="card-title">Edit Product: {{ $product->name }}</h4>
                    </div>
                    <div class="col-md-6 text-end">
                        <a href="{{ route('products.show', $product) }}" class="btn btn-info">
                            <i class="mdi mdi-eye"></i> View Details
                        </a>
                        <a href="{{ route('products.index') }}" class="btn btn-secondary">
                            <i class="mdi mdi-arrow-left"></i> Back to Products
                        </a>
                    </div>
                </div>

                <form method="POST" action="{{ route('products.update', $product) }}">
                    @csrf
                    @method('PUT')

                    <div class="row">
                        <div class="col-md-8">
                            <div class="row">
                                <!-- Basic Information -->
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="name" class="form-label">Product Name *</label>
                                        <input type="text" class="form-control @error('name') is-invalid @enderror"
                                               id="name" name="name" value="{{ old('name', $product->name) }}" required>
                                        @error('name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="brand_id" class="form-label">Brand</label>
                                        <select class="form-select @error('brand_id') is-invalid @enderror"
                                                id="brand_id" name="brand_id">
                                            <option value="">Select Brand</option>
                                            @foreach(\App\Models\Brand::active()->ordered()->get() as $brand)
                                            <option value="{{ $brand->id }}" {{ old('brand_id', $product->brand_id) == $brand->id ? 'selected' : '' }}>
                                                {{ $brand->name }}
                                            </option>
                                            @endforeach
                                        </select>
                                        @error('brand_id')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="sku" class="form-label">SKU *</label>
                                        <input type="text" class="form-control @error('sku') is-invalid @enderror"
                                               id="sku" name="sku" value="{{ old('sku', $product->sku) }}" required>
                                        @error('sku')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="barcode" class="form-label">Barcode</label>
                                        <input type="text" class="form-control @error('barcode') is-invalid @enderror"
                                               id="barcode" name="barcode" value="{{ old('barcode', $product->barcode) }}">
                                        @error('barcode')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="category_id" class="form-label">Category</label>
                                        <select class="form-select @error('category_id') is-invalid @enderror"
                                                id="category_id" name="category_id">
                                            <option value="">Select Category</option>
                                            @foreach(\App\Models\ProductCategory::active()->ordered()->get() as $category)
                                            <option value="{{ $category->id }}" {{ old('category_id', $product->category_id) == $category->id ? 'selected' : '' }}>
                                                {{ $category->name }}
                                            </option>
                                            @endforeach
                                        </select>
                                        @error('category_id')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="unit" class="form-label">Unit *</label>
                                        <select class="form-select @error('unit') is-invalid @enderror" id="unit" name="unit" required>
                                            <option value="">Select Unit</option>
                                            <option value="pcs" {{ old('unit', $product->unit) == 'pcs' ? 'selected' : '' }}>Pieces (pcs)</option>
                                            <option value="kg" {{ old('unit', $product->unit) == 'kg' ? 'selected' : '' }}>Kilograms (kg)</option>
                                            <option value="liter" {{ old('unit', $product->unit) == 'liter' ? 'selected' : '' }}>Liters (L)</option>
                                            <option value="meter" {{ old('unit', $product->unit) == 'meter' ? 'selected' : '' }}>Meters (m)</option>
                                        </select>
                                        @error('unit')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <div class="mb-3">
                                        <label for="description" class="form-label">Description</label>
                                        <textarea class="form-control @error('description') is-invalid @enderror"
                                                  id="description" name="description" rows="3">{{ old('description', $product->description) }}</textarea>
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
                                                   id="cost_price" name="cost_price" value="{{ old('cost_price', $product->cost_price) }}" required>
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
                                                   id="selling_price" name="selling_price" value="{{ old('selling_price', $product->selling_price) }}" required>
                                        </div>
                                        @error('selling_price')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label for="stock_quantity" class="form-label">Stock Quantity *</label>
                                        <input type="number" class="form-control @error('stock_quantity') is-invalid @enderror"
                                               id="stock_quantity" name="stock_quantity" value="{{ old('stock_quantity', $product->stock_quantity) }}" required>
                                        @error('stock_quantity')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label for="min_stock_level" class="form-label">Minimum Stock Level *</label>
                                        <input type="number" class="form-control @error('min_stock_level') is-invalid @enderror"
                                               id="min_stock_level" name="min_stock_level" value="{{ old('min_stock_level', $product->min_stock_level) }}" required>
                                        @error('min_stock_level')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">Status</label>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox"
                                                   id="is_active" name="is_active" value="1" {{ old('is_active', $product->is_active) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="is_active">
                                                Active Product
                                            </label>
                                        </div>
                                    </div>

                                    <div class="mt-3">
                                        <h6>Current Statistics</h6>
                                        <div class="row text-center">
                                            <div class="col-6">
                                                <h4 class="text-primary">{{ $product->saleItems->sum('quantity') }}</h4>
                                                <small class="text-muted">Sold</small>
                                            </div>
                                            <div class="col-6">
                                                <h4 class="text-success">${{ number_format($product->saleItems->sum('total_price'), 0) }}</h4>
                                                <small class="text-muted">Revenue</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12">
                            <button type="submit" class="btn btn-primary">
                                <i class="mdi mdi-content-save"></i> Update Product
                            </button>
                            <a href="{{ route('products.show', $product) }}" class="btn btn-secondary">Cancel</a>

                            @can('delete', $product)
                            <div class="float-end">
                                <form action="{{ route('products.destroy', $product) }}" method="POST" class="d-inline"
                                      onsubmit="return confirm('Are you sure you want to delete this product? This action cannot be undone.')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger">
                                        <i class="mdi mdi-delete"></i> Delete Product
                                    </button>
                                </form>
                            </div>
                            @endcan
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
