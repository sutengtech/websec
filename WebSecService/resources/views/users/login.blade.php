@extends('layouts.master')
@section('title', 'Login')
@section('content')
<div class="container py-4">
  <div class="row justify-content-center">
    <div class="col-md-7 col-lg-5">
      <div class="card shadow-sm" style="border-radius: 10px; border: none;">
        <div class="card-header bg-light text-center py-3" style="border-radius: 10px 10px 0 0; border-bottom: none;">
          <h4 class="mb-0 text-primary"><i class="bi bi-box-arrow-in-right me-2"></i>Account Login</h4>
        </div>
        <div class="card-body px-4 py-4">
          @foreach($errors->all() as $error)
          <div class="alert alert-danger py-2" role="alert">
            <i class="bi bi-exclamation-triangle-fill me-2"></i>
            <small>{{$error}}</small>
          </div>
          @endforeach
          
          <form action="{{route('do_login')}}" method="post">
            {{ csrf_field() }}
            
            <div class="form-group mb-3">
              <label for="email" class="form-label"><i class="bi bi-envelope me-1"></i>Email Address</label>
              <input type="email" class="form-control" id="email" placeholder="Enter your email" name="email" value="{{ old('email') }}" required autofocus>
            </div>
            
            <div class="form-group mb-4">
              <label for="password" class="form-label"><i class="bi bi-key me-1"></i>Password</label>
              <input type="password" class="form-control" id="password" placeholder="Enter your password" name="password" required>
            </div>
            
            <div class="d-grid mt-4 mb-2">
              <button type="submit" class="btn btn-primary">
                <i class="bi bi-door-open me-1"></i>Login
              </button>
            </div>
            
            <div class="text-center mt-3">
              <small class="text-muted">Don't have an account?</small>
              <a href="{{ route('register') }}" class="ms-1 link-primary">Register</a>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
