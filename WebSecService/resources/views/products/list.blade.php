@extends('layouts.master')
@section('title', 'Products')
@section('content')
<div class="container py-4">
    <!-- Header Section -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="mb-0"><i class="bi bi-shop me-2"></i>Products</h1>
        @can('add_products')
        <a href="{{route('products_edit')}}" class="btn btn-success">
            <i class="bi bi-plus-lg me-1"></i> Add Product
        </a>
        @endcan
    </div>

    <!-- Search Form Section -->
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-light">
            <h5 class="mb-0"><i class="bi bi-search me-2"></i>Search Products</h5>
        </div>
        <div class="card-body">
            <form id="productSearchForm" action="{{ route('products_list') }}" method="GET">
                <div class="row g-3">
                    <div class="col-md-4">
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-text-paragraph"></i></span>
                            <input name="keywords" type="text" class="form-control" placeholder="Search Keywords" value="{{ request()->keywords }}" />
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-currency-dollar"></i></span>
                            <input name="min_price" type="number" step="0.01" class="form-control" placeholder="Min Price" value="{{ request()->min_price }}"/>
                            <input name="max_price" type="number" step="0.01" class="form-control" placeholder="Max Price" value="{{ request()->max_price }}"/>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="d-flex">
                            <select name="order_by" class="form-select me-2">
                                <option value="" {{ request()->order_by==""?"selected":"" }} disabled>Order By</option>
                                <option value="name" {{ request()->order_by=="name"?"selected":"" }}>Name</option>
                                <option value="price" {{ request()->order_by=="price"?"selected":"" }}>Price</option>
                                <option value="stock" {{ request()->order_by=="stock"?"selected":"" }}>Stock</option>
                            </select>
                            <select name="order_direction" class="form-select">
                                <option value="" {{ request()->order_direction==""?"selected":"" }} disabled>Direction</option>
                                <option value="ASC" {{ request()->order_direction=="ASC"?"selected":"" }}>ASC</option>
                                <option value="DESC" {{ request()->order_direction=="DESC"?"selected":"" }}>DESC</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-search me-1"></i> Search
                            </button>
                            <a href="{{ route('products_list') }}" class="btn btn-outline-secondary">
                                <i class="bi bi-x-circle me-1"></i> Reset
                            </a>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Products Grid -->
    <div class="row row-cols-1 row-cols-md-2 g-4">
        @foreach($products as $product)
        <div class="col">
            <div class="card card-dashboard h-100 shadow-sm">
                <div class="row g-0 h-100">
                    <div class="col-md-4 product-image-container">
                        <img src="{{asset("images/$product->photo")}}" class="product-image rounded" alt="{{$product->name}}">
                    </div>
                    <div class="col-md-8">
                        <div class="card-body d-flex flex-column h-100">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <h5 class="card-title fw-bold mb-0">{{$product->name}}</h5>
                                <span class="badge bg-primary rounded-pill">{{$product->code}}</span>
                            </div>
                            
                            <p class="card-text text-truncate-2 mb-2">{{$product->description}}</p>
                            
                            <div class="mb-3">
                                <div class="d-flex align-items-center mb-1">
                                    <i class="bi bi-tag-fill text-primary me-2"></i>
                                    <span class="fw-bold">Model:</span>
                                    <span class="ms-2">{{$product->model}}</span>
                                </div>
                                <div class="d-flex align-items-center mb-1">
                                    <i class="bi bi-currency-dollar text-success me-2"></i>
                                    <span class="fw-bold">Price:</span>
                                    <span class="ms-2 fs-5">${{ number_format($product->price, 2) }}</span>
                                </div>
                                <div class="d-flex align-items-center">
                                    <i class="bi bi-box-seam me-2 
                                        {{ $product->stock > 10 ? 'text-success' : ($product->stock > 0 ? 'text-warning' : 'text-danger') }}"></i>
                                    <span class="fw-bold">Stock:</span>
                                    <span class="ms-2">
                                        @if($product->stock > 10)
                                            <span class="badge bg-success">{{ $product->stock }} Available</span>
                                        @elseif($product->stock > 0)
                                            <span class="badge bg-warning text-dark">Only {{ $product->stock }} Left</span>
                                        @else
                                            <span class="badge bg-danger">Out of Stock</span>
                                        @endif
                                    </span>
                                    
                                    @can('manage_stock')
                                    <button type="button" class="btn btn-sm btn-outline-primary ms-2" data-bs-toggle="modal" data-bs-target="#stockModal{{ $product->id }}">
                                        <i class="bi bi-pencil-square"></i>
                                    </button>
                                    @endcan
                                </div>
                            </div>
                            
                            <div class="mt-auto">
                                <div class="d-flex justify-content-between">
                                    <div>
                                        @can('edit_products')
                                        <a href="{{route('products_edit', $product->id)}}" class="btn btn-sm btn-outline-primary">
                                            <i class="bi bi-pencil me-1"></i> Edit
                                        </a>
                                        @endcan
                                        @can('delete_products')
                                        <a href="{{route('products_delete', $product->id)}}" class="btn btn-sm btn-outline-danger">
                                            <i class="bi bi-trash me-1"></i> Delete
                                        </a>
                                        @endcan
                                    </div>
                                    
                                    @auth
                                        @if(auth()->user()->hasRole('Customer'))
                                        <a href="{{ route('purchase_form', ['product' => $product->id]) }}" 
                                        class="btn btn-primary {{ $product->stock < 1 ? 'disabled' : '' }}">
                                            <i class="bi bi-cart-plus me-1"></i> Purchase
                                        </a>
                                        @endif
                                    @else
                                        <a href="{{ route('login') }}" class="btn btn-outline-primary">
                                            <i class="bi bi-box-arrow-in-right me-1"></i> Login to Purchase
                                        </a>
                                    @endauth
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>
    
    @if(count($products) == 0)
    <div class="alert alert-info mt-3">
        <i class="bi bi-info-circle me-2"></i> No products found matching your search criteria.
    </div>
    @endif
</div>

<!-- Stock Update Modals -->
@can('manage_stock')
    @foreach($products as $product)
    <div class="modal fade" id="stockModal{{ $product->id }}" tabindex="-1" aria-labelledby="stockModalLabel{{ $product->id }}" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="{{ route('products_save', $product->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title" id="stockModalLabel{{ $product->id }}">
                            <i class="bi bi-box-seam me-2"></i> Update Stock: {{ $product->name }}
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <!-- Hidden fields to preserve product data -->
                        <input type="hidden" name="code" value="{{ $product->code }}">
                        <input type="hidden" name="name" value="{{ $product->name }}">
                        <input type="hidden" name="model" value="{{ $product->model }}">
                        <input type="hidden" name="description" value="{{ $product->description }}">
                        <input type="hidden" name="price" value="{{ $product->price }}">
                        <input type="hidden" name="photo" value="{{ $product->photo }}">
                        
                        <div class="mb-3">
                            <label for="stockInput{{ $product->id }}" class="form-label">Stock Quantity:</label>
                            <div class="input-group">
                                <button type="button" class="btn btn-outline-secondary" onclick="decrementStock('stockInput{{ $product->id }}')">
                                    <i class="bi bi-dash"></i>
                                </button>
                                <input type="number" class="form-control text-center" id="stockInput{{ $product->id }}" name="stock" min="0" value="{{ $product->stock }}">
                                <button type="button" class="btn btn-outline-secondary" onclick="incrementStock('stockInput{{ $product->id }}')">
                                    <i class="bi bi-plus"></i>
                                </button>
                            </div>
                            <small class="form-text text-muted">Current stock: {{ $product->stock }}</small>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check-lg me-1"></i> Save Changes
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endforeach
@endcan

<script>
    function incrementStock(inputId) {
        const input = document.getElementById(inputId);
        input.value = parseInt(input.value) + 1;
    }
    
    function decrementStock(inputId) {
        const input = document.getElementById(inputId);
        const newValue = parseInt(input.value) - 1;
        input.value = newValue < 0 ? 0 : newValue;
    }
</script>
@endsection