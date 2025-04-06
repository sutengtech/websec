@extends('layouts.master')

@section('title', 'My Purchases')

@section('content')
<div class="container mt-4">
    <h1>My Purchases</h1>
    
    @if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
    @endif
    
    @if(session('error'))
    <div class="alert alert-danger">
        {{ session('error') }}
    </div>
    @endif
    
    <div class="card">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">Purchase History</h5>
        </div>
        <div class="card-body">
            @if(count($purchases) > 0)
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Product</th>
                            <th>Quantity</th>
                            <th>Price Paid</th>
                            <th>Purchase Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($purchases as $purchase)
                        <tr>
                            <td>{{ $purchase->product->name }}</td>
                            <td>{{ $purchase->quantity }}</td>
                            <td>${{ number_format($purchase->total_price, 2) }}</td>
                            <td>{{ $purchase->created_at->format('M d, Y H:i') }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @else
            <div class="alert alert-info">
                You haven't made any purchases yet.
            </div>
            @endif
        </div>
    </div>
    
    <div class="mt-3">
        <a href="{{ route('products_list') }}" class="btn btn-primary">Browse Products</a>
    </div>
</div>
@endsection 