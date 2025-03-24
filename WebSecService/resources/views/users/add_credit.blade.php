@extends('layouts.master')
@section('title', 'Add Credit')
@section('content')
<div class="d-flex justify-content-center">
    <div class="row m-4 col-sm-8">
        <form action="{{route('users_save_credit', $user->id)}}" method="post">
            {{ csrf_field() }}
            @foreach($errors->all() as $error)
            <div class="alert alert-danger">
            <strong>Error!</strong> {{$error}}
            </div>
            @endforeach

            <div class="row mb-2">
                <div class="col-12">
                    <label for="credit" class="form-label">Credit Amount:</label>
                    <input type="number" class="form-control" id="credit" placeholder="Credit Amount" name="credit" required step="0.01">
                </div>
            </div>

            <button type="submit" class="btn btn-primary">Add Credit</button>
        </form>
    </div>
</div>
@endsection