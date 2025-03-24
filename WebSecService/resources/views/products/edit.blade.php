@extends('layouts.master')
@section('title', $product->exists ? 'Edit Product' : 'Add Product')
@section('content')

<form action="{{route('products_save', $product->id)}}" method="post">
    {{ csrf_field() }}
    @foreach($errors->all() as $error)
    <div class="alert alert-danger">
    <strong>Error!</strong> {{$error}}
    </div>
    @endforeach
    <div class="row mb-2">
        <div class="col-6">
            <label for="code" class="form-label">Code:</label>
            <input type="text" class="form-control" id="code" placeholder="Code" name="code" required value="{{$product->code}}">
        </div>
        <div class="col-6">
            <label for="model" class="form-label">Model:</label>
            <input type="text" class="form-control" id="model" placeholder="Model" name="model" required value="{{$product->model}}">
        </div>
    </div>
    <div class="row mb-2">
        <div class="col">
            <label for="name" class="form-label">Name:</label>
            <input type="text" class="form-control" id="name" placeholder="Name" name="name" required value="{{$product->name}}">
        </div>
    </div>
    <div class="row mb-2">
        <div class="col-6">
            <label for="price" class="form-label">Price:</label>
            <input type="number" class="form-control" id="price" placeholder="Price" name="price" required value="{{$product->price}}" step="0.01">
        </div>
        <div class="col-6">
            <label for="stock" class="form-label">Stock:</label>
            <input type="number" class="form-control" id="stock" placeholder="Stock" name="stock" required value="{{$product->stock ?? 0}}">
        </div>
    </div>
    <div class="row mb-2">
        <div class="col-6">
            <label for="photo" class="form-label">Photo:</label>
            <input type="text" class="form-control" id="photo" placeholder="Photo" name="photo" required value="{{$product->photo}}">
        </div>
    </div>
    <div class="row mb-2">
        <div class="col">
            <label for="description" class="form-label">Description:</label>
            <textarea class="form-control" id="description" placeholder="Description" name="description" required>{{$product->description}}</textarea>
        </div>
    </div>
    <button type="submit" class="btn btn-primary">Submit</button>
</form>
@endsection