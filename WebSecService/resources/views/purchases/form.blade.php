@extends('layouts.master')

@section('title', 'Purchase Product')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0"><i class="bi bi-cart-check me-2"></i>Purchase Product</h2>
        <a href="{{ route('products_list') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left me-1"></i> Back to Products
        </a>
    </div>
    
    @if(session('error'))
    <div class="alert alert-danger d-flex align-items-center" role="alert">
        <i class="bi bi-exclamation-triangle-fill flex-shrink-0 me-2"></i>
        <div>{{ session('error') }}</div>
    </div>
    @endif
    
    <div class="row g-4">
        <!-- Product Details Card -->
        <div class="col-lg-5">
            <div class="card card-dashboard shadow-sm h-100" style="border-radius: 10px; border: none;">
                <div class="card-header bg-light py-3" style="border-bottom: none; border-radius: 10px 10px 0 0;">
                    <h5 class="mb-0 text-primary"><i class="bi bi-info-circle me-2"></i>Product Information</h5>
                </div>
                
                <div class="card-body p-0">
                    <div class="product-image-container" style="height: 250px;">
                        @if($product->photo)
                        <img src="{{asset("images/$product->photo")}}" class="product-image rounded-top" alt="{{$product->name}}">
                        @else
                        <div class="d-flex align-items-center justify-content-center h-100 text-muted">
                            <i class="bi bi-image fs-1"></i>
                        </div>
                        @endif
                    </div>
                    
                    <div class="p-4">
                        <h4 class="mb-3 fw-bold">{{ $product->name }}</h4>
                        
                        <div class="mb-1 d-flex align-items-center">
                            <i class="bi bi-upc-scan text-primary me-2"></i>
                            <span class="fw-bold me-2">Code:</span>
                            <span>{{ $product->code }}</span>
                        </div>
                        
                        <div class="mb-1 d-flex align-items-center">
                            <i class="bi bi-tag-fill text-primary me-2"></i>
                            <span class="fw-bold me-2">Model:</span>
                            <span>{{ $product->model }}</span>
                        </div>
                        
                        <div class="mb-1 d-flex align-items-center">
                            <i class="bi bi-currency-dollar text-success me-2"></i>
                            <span class="fw-bold me-2">Price:</span>
                            <span class="fs-5 fw-bold">${{ number_format($product->price, 2) }}</span>
                        </div>
                        
                        <div class="mb-3 d-flex align-items-center">
                            <i class="bi bi-box-seam {{ $product->stock > 10 ? 'text-success' : ($product->stock > 0 ? 'text-warning' : 'text-danger') }} me-2"></i>
                            <span class="fw-bold me-2">Available:</span>
                            @if($product->stock > 10)
                                <span class="badge bg-success">{{ $product->stock }} in stock</span>
                            @elseif($product->stock > 0)
                                <span class="badge bg-warning text-dark">Only {{ $product->stock }} left</span>
                            @else
                                <span class="badge bg-danger">Out of stock</span>
                            @endif
                        </div>
                        
                        <div class="mt-3">
                            <h6 class="fw-bold"><i class="bi bi-file-text me-1"></i> Description</h6>
                            <p>{{ $product->description }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Purchase Form Card -->
        <div class="col-lg-7">
            <div class="card card-dashboard shadow-sm" style="border-radius: 10px; border: none;">
                <div class="card-header bg-light py-3" style="border-bottom: none; border-radius: 10px 10px 0 0;">
                    <h5 class="mb-0 text-primary"><i class="bi bi-credit-card me-2"></i>Complete Your Purchase</h5>
                </div>
                
                <div class="card-body p-4">
                    @if(auth()->user()->credit < $product->price)
                    <div class="alert alert-danger d-flex align-items-start mb-4" role="alert">
                        <div class="p-2 bg-danger bg-opacity-10 rounded me-3">
                            <i class="bi bi-exclamation-triangle-fill text-danger fs-3"></i>
                        </div>
                        <div>
                            <h5 class="alert-heading">Insufficient Credit</h5>
                            <p class="mb-0">You don't have enough credit to purchase this product. Your current balance is <strong>${{ number_format(auth()->user()->credit, 2) }}</strong>, but you need <strong>${{ number_format($product->price, 2) }}</strong>.</p>
                            <hr>
                            <p class="mb-0">Please contact an employee to add credit to your account.</p>
                        </div>
                    </div>
                    @elseif($product->stock < 1)
                    <div class="alert alert-warning d-flex align-items-start mb-4" role="alert">
                        <div class="p-2 bg-warning bg-opacity-10 rounded me-3">
                            <i class="bi bi-exclamation-triangle-fill text-warning fs-3"></i>
                        </div>
                        <div>
                            <h5 class="alert-heading">Out of Stock</h5>
                            <p class="mb-0">This product is currently out of stock. Please check back later or browse our other products.</p>
                        </div>
                    </div>
                    @endif
                    
                    <form action="{{ route('do_purchase', ['product' => $product->id]) }}" method="POST">
                        @csrf
                        
                        <div class="row g-4">
                            <div class="col-md-6">
                                <div class="card bg-light border-0">
                                    <div class="card-body">
                                        <h6 class="mb-3 fw-bold"><i class="bi bi-wallet2 me-1"></i> Your Account Balance</h6>
                                        <div class="d-flex align-items-center">
                                            <div class="p-2 rounded-circle bg-success bg-opacity-10 me-3">
                                                <i class="bi bi-cash-stack text-success fs-3"></i>
                                            </div>
                                            <div>
                                                <small class="text-muted d-block">Available Credit</small>
                                                <span class="fs-4 fw-bold text-success">${{ number_format(auth()->user()->credit, 2) }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="card bg-light border-0">
                                    <div class="card-body">
                                        <h6 class="mb-3 fw-bold"><i class="bi bi-calculator me-1"></i> Order Summary</h6>
                                        <div id="balanceInfo">
                                            <div class="d-flex justify-content-between mb-2">
                                                <span>Price per unit:</span>
                                                <span>${{ number_format($product->price, 2) }}</span>
                                            </div>
                                            <div class="d-flex justify-content-between mb-2">
                                                <span>Quantity:</span>
                                                <span id="summaryQuantity">1</span>
                                            </div>
                                            <hr>
                                            <div class="d-flex justify-content-between fw-bold">
                                                <span>Total Cost:</span>
                                                <span id="summaryTotal">${{ number_format($product->price, 2) }}</span>
                                            </div>
                                            <div class="d-flex justify-content-between text-muted mt-2" id="remainingCreditContainer">
                                                <small>Remaining Credit:</small>
                                                <small id="remainingCredit">${{ number_format(auth()->user()->credit - $product->price, 2) }}</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="mt-4">
                            <label for="quantity" class="form-label fw-bold">Quantity</label>
                            <div class="input-group">
                                <button type="button" class="btn btn-outline-secondary" id="decrementBtn">
                                    <i class="bi bi-dash"></i>
                                </button>
                                <input type="number" class="form-control text-center" id="quantity" name="quantity" value="1" min="1" max="{{ $product->stock }}" {{ $product->stock < 1 ? 'disabled' : '' }}>
                                <button type="button" class="btn btn-outline-secondary" id="incrementBtn">
                                    <i class="bi bi-plus"></i>
                                </button>
                            </div>
                            <div class="form-text">Select quantity (max: {{ $product->stock }})</div>
                        </div>
                        
                        <div class="d-grid gap-2 mt-4">
                            <button type="submit" class="btn btn-primary" id="purchaseButton" {{ (auth()->user()->credit < $product->price || $product->stock < 1) ? 'disabled' : '' }}>
                                <i class="bi bi-bag-check me-1"></i> Complete Purchase
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const quantityInput = document.getElementById('quantity');
        const incrementBtn = document.getElementById('incrementBtn');
        const decrementBtn = document.getElementById('decrementBtn');
        const purchaseButton = document.getElementById('purchaseButton');
        const summaryQuantity = document.getElementById('summaryQuantity');
        const summaryTotal = document.getElementById('summaryTotal');
        const remainingCredit = document.getElementById('remainingCredit');
        
        const price = {{ $product->price }};
        const creditBalance = {{ auth()->user()->credit }};
        const maxStock = {{ $product->stock }};
        
        // Function to update totals
        function updateTotals() {
            const quantity = parseInt(quantityInput.value) || 1;
            const totalPrice = quantity * price;
            const formattedTotal = totalPrice.toFixed(2);
            const remaining = creditBalance - totalPrice;
            const formattedRemaining = remaining.toFixed(2);
            
            // Update display
            summaryQuantity.textContent = quantity;
            summaryTotal.textContent = '$' + formattedTotal;
            remainingCredit.textContent = '$' + formattedRemaining;
            
            // Check if user has enough credit and update button state
            if (creditBalance < totalPrice || quantity > maxStock || quantity < 1) {
                purchaseButton.disabled = true;
                
                // Highlight insufficient credit
                if (creditBalance < totalPrice) {
                    remainingCredit.classList.add('text-danger');
                    remainingCredit.classList.add('fw-bold');
                } else {
                    remainingCredit.classList.remove('text-danger');
                    remainingCredit.classList.remove('fw-bold');
                }
            } else {
                purchaseButton.disabled = false;
                remainingCredit.classList.remove('text-danger');
                remainingCredit.classList.remove('fw-bold');
            }
        }
        
        // Event listeners
        quantityInput.addEventListener('input', updateTotals);
        
        incrementBtn.addEventListener('click', function() {
            const currentValue = parseInt(quantityInput.value) || 0;
            if (currentValue < maxStock) {
                quantityInput.value = currentValue + 1;
                updateTotals();
            }
        });
        
        decrementBtn.addEventListener('click', function() {
            const currentValue = parseInt(quantityInput.value) || 2;
            if (currentValue > 1) {
                quantityInput.value = currentValue - 1;
                updateTotals();
            }
        });
        
        // Initial update
        updateTotals();
    });
</script>
@endsection 