@extends('layouts.master')
@section('title', 'My Purchases')
@section('content')
<div class="row mt-2">
    <div class="col col-10">
        <h1>My Purchases</h1>
    </div>
</div>

@if($purchases->isEmpty())
    <p>No purchases yet.</p>
@else
    <div class="card mt-2">
        <div class="card-body">
            <table class="table">
                <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Product Name</th>
                        <th scope="col">Price</th>
                        <th scope="col">Quantity</th>
                        <th scope="col">Purchased At</th>
                    </tr>
                </thead>
                @foreach($purchases as $purchase)
                <tr>
                    <td scope="col">{{$purchase->id}}</td>
                    <td scope="col">{{$purchase->product->name}}</td>
                    <td scope="col">${{$purchase->price}}</td>
                    <td scope="col">{{$purchase->quantity}}</td>
                    <td scope="col">{{$purchase->created_at}}</td>
                </tr>
                @endforeach
            </table>
        </div>
    </div>
@endif
@endsection