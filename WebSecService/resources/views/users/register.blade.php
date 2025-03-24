@extends('layouts.master')
@section('title', 'Register')
@section('content')
<div class="d-flex justify-content-center">
    <div class="row m-4 col-sm-8">
        <h2>Customer Registration</h2>
        <p class="text-muted">This form is for customers only. Employee accounts must be created by an administrator.</p>
        <form action="{{route('do_register')}}" method="post">
            {{ csrf_field() }}
            @foreach($errors->all() as $error)
            <div class="alert alert-danger">
            <strong>Error!</strong> {{$error}}
            </div>
            @endforeach

            <div class="row mb-2">
                <div class="col-12">
                    <label for="name" class="form-label">Name:</label>
                    <input type="text" class="form-control" id="name" placeholder="name" name="name" required value="{{old('name')}}">
                </div>
            </div>
            <div class="row mb-2">
                <div class="col-12">
                    <label for="email" class="form-label">Email:</label>
                    <input type="email" class="form-control" id="email" placeholder="email" name="email" required value="{{old('email')}}">
                </div>
            </div>
            <div class="row mb-2">
                <div class="col-12">
                    <label for="password" class="form-label">Password:</label>
                    <input type="password" class="form-control" id="password" placeholder="password" name="password" required>
                </div>
            </div>
            <div class="row mb-2">
                <div class="col-12">
                    <label for="password_confirmation" class="form-label">Password Confirmation:</label>
                    <input type="password" class="form-control" id="password_confirmation" placeholder="Confirmation" name="password_confirmation" required>
                </div>
            </div>

            <button type="submit" class="btn btn-primary">Register</button>
        </form>
    </div>
</div>
@endsection