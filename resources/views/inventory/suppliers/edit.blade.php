@extends('layouts.master')

@section('title', 'Edit Supplier')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-md-6">
                        <h4 class="card-title">Edit Supplier: {{ $supplier->name }}</h4>
                    </div>
                    <div class="col-md-6 text-end">
                        <a href="{{ route('suppliers.show', $supplier) }}" class="btn btn-info">
                            <i class="mdi mdi-eye"></i> View Details
                        </a>
                        <a href="{{ route('suppliers.index') }}" class="btn btn-secondary">
                            <i class="mdi mdi-arrow-left"></i> Back to Suppliers
                        </a>
                    </div>
                </div>

                <form method="POST" action="{{ route('suppliers.update', $supplier) }}">
                    @csrf
                    @method('PUT')

                    <div class="row">
                        <div class="col-md-8">
                            <div class="row">
                                <!-- Basic Information -->
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="name" class="form-label">Supplier Name *</label>
                                        <input type="text" class="form-control @error('name') is-invalid @enderror"
                                               id="name" name="name" value="{{ old('name', $supplier->name) }}" required>
                                        @error('name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="contact_person" class="form-label">Contact Person</label>
                                        <input type="text" class="form-control @error('contact_person') is-invalid @enderror"
                                               id="contact_person" name="contact_person" value="{{ old('contact_person', $supplier->contact_person) }}">
                                        @error('contact_person')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Contact Information -->
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="email" class="form-label">Email Address</label>
                                        <input type="email" class="form-control @error('email') is-invalid @enderror"
                                               id="email" name="email" value="{{ old('email', $supplier->email) }}">
                                        @error('email')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="phone" class="form-label">Phone Number</label>
                                        <input type="text" class="form-control @error('phone') is-invalid @enderror"
                                               id="phone" name="phone" value="{{ old('phone', $supplier->phone) }}">
                                        @error('phone')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <div class="mb-3">
                                        <label for="address" class="form-label">Address</label>
                                        <textarea class="form-control @error('address') is-invalid @enderror"
                                                  id="address" name="address" rows="3">{{ old('address', $supplier->address) }}</textarea>
                                        @error('address')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <!-- Additional Information -->
                            <div class="card border">
                                <div class="card-body">
                                    <h5 class="card-title">Additional Information</h5>

                                    <div class="mb-3">
                                        <label class="form-label">Status</label>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox"
                                                   id="is_active" name="is_active" value="1" {{ old('is_active', $supplier->is_active) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="is_active">
                                                Active Supplier
                                            </label>
                                        </div>
                                        <small class="form-text text-muted">
                                            Inactive suppliers won't appear in dropdowns and searches.
                                        </small>
                                    </div>

                                    <div class="mt-4">
                                        <h6>Supplier Statistics</h6>
                                        <div class="row text-center">
                                            <div class="col-6">
                                                <h4 class="text-primary">{{ $supplier->purchases->count() }}</h4>
                                                <small class="text-muted">Purchases</small>
                                            </div>
                                            <div class="col-6">
                                                <h4 class="text-success">${{ number_format($supplier->purchases->sum('total_amount'), 0) }}</h4>
                                                <small class="text-muted">Total Spent</small>
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
                                <i class="mdi mdi-content-save"></i> Update Supplier
                            </button>
                            <a href="{{ route('suppliers.show', $supplier) }}" class="btn btn-secondary">Cancel</a>

                            @can('delete', $supplier)
                            <div class="float-end">
                                <form action="{{ route('suppliers.destroy', $supplier) }}" method="POST" class="d-inline"
                                      onsubmit="return confirm('Are you sure you want to delete this supplier? This action cannot be undone.')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger">
                                        <i class="mdi mdi-delete"></i> Delete Supplier
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
