@extends('layouts.master')
@section('title', 'Edit Product')
@section('content')

<div class="container mt-4">
    <div class="row mb-3">
        <div class="col">
            <h1>{{ $product->id ? 'Edit' : 'Add' }} Product</h1>
        </div>
    </div>

    <form action="{{route('products_save', $product->id)}}" method="post" enctype="multipart/form-data">
        @csrf
        @foreach($errors->all() as $error)
        <div class="alert alert-danger">
            <strong>Error!</strong> {{$error}}
        </div>
        @endforeach
        
        <div class="card mb-4">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">Product Information</h5>
            </div>
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="code" class="form-label">Code:</label>
                        <input type="text" class="form-control" placeholder="Code" name="code" required value="{{$product->code}}">
                    </div>
                    <div class="col-md-6">
                        <label for="model" class="form-label">Model:</label>
                        <input type="text" class="form-control" placeholder="Model" name="model" required value="{{$product->model}}">
                    </div>
                </div>
                
                <div class="row mb-3">
                    <div class="col">
                        <label for="name" class="form-label">Name:</label>
                        <input type="text" class="form-control" placeholder="Name" name="name" required value="{{$product->name}}">
                    </div>
                </div>
                
                <div class="row mb-3">
                    <div class="col-md-4">
                        <label for="price" class="form-label">Price:</label>
                        <div class="input-group">
                            <span class="input-group-text">$</span>
                            <input type="number" step="0.01" min="0" class="form-control" placeholder="Price" name="price" required value="{{$product->price}}">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <label for="stock" class="form-label">Stock:</label>
                        <input type="number" min="0" class="form-control" placeholder="Available Stock" name="stock" required value="{{$product->stock ?? 0}}">
                    </div>
                    <div class="col-md-4">
                        <label for="photo_file" class="form-label">Product Image:</label>
                        <input type="file" class="form-control" name="photo_file" id="photo_file" accept="image/*" {{ $product->exists ? '' : 'required' }}>
                        @if($product->photo)
                        <div class="mt-2">
                            <p class="text-muted mb-1">Current Image:</p>
                            <img src="{{ asset('images/' . $product->photo) }}" alt="{{ $product->name }}" class="img-thumbnail" style="max-height: 100px;">
                            <input type="hidden" name="photo" value="{{ $product->photo }}">
                        </div>
                        @endif
                    </div>
                </div>
                
                <div class="row mb-3">
                    <div class="col">
                        <label for="description" class="form-label">Description:</label>
                        <textarea class="form-control" rows="4" placeholder="Description" name="description" required>{{$product->description}}</textarea>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="d-flex justify-content-between">
            <a href="{{ route('products_list') }}" class="btn btn-secondary">Cancel</a>
            <button type="submit" class="btn btn-primary">Save Product</button>
        </div>
    </form>
</div>
@endsection
