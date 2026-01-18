@extends('layouts.master')

@section('title', 'Category Details')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-md-6">
                        <h4 class="card-title">{{ $category->name }}</h4>
                    </div>
                    <div class="col-md-6 text-end">
                        @if(auth()->user()->isAdmin())
                        <a href="{{ route('categories.edit', $category) }}" class="btn btn-primary">
                            <i class="mdi mdi-pencil"></i> Edit
                        </a>
                        @endif
                        <a href="{{ route('categories.index') }}" class="btn btn-secondary">
                            <i class="mdi mdi-arrow-left"></i> Back to Categories
                        </a>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-8">
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <tr>
                                    <th width="200">Name</th>
                                    <td>{{ $category->name }}</td>
                                </tr>
                                @if($category->description)
                                <tr>
                                    <th>Description</th>
                                    <td>{{ $category->description }}</td>
                                </tr>
                                @endif
                                @if($category->icon)
                                <tr>
                                    <th>Icon</th>
                                    <td><i class="{{ $category->icon }}"></i> {{ $category->icon }}</td>
                                </tr>
                                @endif
                                @if($category->color)
                                <tr>
                                    <th>Color</th>
                                    <td><span class="badge" style="background-color: {{ $category->color }};">{{ $category->color }}</span></td>
                                </tr>
                                @endif
                                <tr>
                                    <th>Status</th>
                                    <td>
                                        <span class="badge {{ $category->is_active ? 'bg-success' : 'bg-secondary' }}">
                                            {{ $category->is_active ? 'Active' : 'Inactive' }}
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Products</th>
                                    <td><span class="badge bg-info">{{ $category->products->count() }}</span></td>
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
