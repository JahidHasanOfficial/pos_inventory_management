@extends('layouts.master')

@section('title', 'Brand Details')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-md-6">
                        <h4 class="card-title">{{ $brand->name }}</h4>
                    </div>
                    <div class="col-md-6 text-end">
                        @if(auth()->user()->isAdmin())
                        <a href="{{ route('brands.edit', $brand) }}" class="btn btn-primary">
                            <i class="mdi mdi-pencil"></i> Edit
                        </a>
                        @endif
                        <a href="{{ route('brands.index') }}" class="btn btn-secondary">
                            <i class="mdi mdi-arrow-left"></i> Back to Brands
                        </a>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-8">
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <tr>
                                    <th width="200">Name</th>
                                    <td>{{ $brand->name }}</td>
                                </tr>
                                @if($brand->description)
                                <tr>
                                    <th>Description</th>
                                    <td>{{ $brand->description }}</td>
                                </tr>
                                @endif
                                @if($brand->website)
                                <tr>
                                    <th>Website</th>
                                    <td><a href="{{ $brand->website_url }}" target="_blank">{{ $brand->website }}</a></td>
                                </tr>
                                @endif
                                @if($brand->country)
                                <tr>
                                    <th>Country</th>
                                    <td>{{ $brand->country }}</td>
                                </tr>
                                @endif
                                <tr>
                                    <th>Status</th>
                                    <td>
                                        <span class="badge {{ $brand->is_active ? 'bg-success' : 'bg-secondary' }}">
                                            {{ $brand->is_active ? 'Active' : 'Inactive' }}
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Products</th>
                                    <td><span class="badge bg-info">{{ $brand->products->count() }}</span></td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
