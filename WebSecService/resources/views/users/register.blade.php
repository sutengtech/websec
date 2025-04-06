@extends('layouts.master')
@section('title', 'Register')
@section('content')
<div class="container py-4">
  <div class="row justify-content-center">
    <div class="col-md-7 col-lg-5">
      <div class="card shadow-sm" style="border-radius: 10px; border: none;">
        <div class="card-header bg-light text-center py-3" style="border-radius: 10px 10px 0 0; border-bottom: none;">
          <h4 class="mb-0 text-primary"><i class="bi bi-person-plus me-2"></i>Create Account</h4>
        </div>
        <div class="card-body px-4 py-4">
          @foreach($errors->all() as $error)
          <div class="alert alert-danger py-2" role="alert">
            <i class="bi bi-exclamation-triangle-fill me-2"></i>
            <small>{{$error}}</small>
          </div>
          @endforeach
          
          <form action="{{route('do_register')}}" method="post">
            {{ csrf_field() }}
            
            <div class="form-group mb-3">
              <label for="name" class="form-label"><i class="bi bi-person me-1"></i>Full Name</label>
              <input type="text" class="form-control" id="name" placeholder="Enter your name" name="name" value="{{ old('name') }}" required autofocus>
              <div class="form-text"><small>Minimum 5 characters</small></div>
            </div>
            
            <div class="form-group mb-3">
              <label for="email" class="form-label"><i class="bi bi-envelope me-1"></i>Email Address</label>
              <input type="email" class="form-control" id="email" placeholder="Enter your email" name="email" value="{{ old('email') }}" required>
            </div>
            
            <div class="form-group mb-3">
              <label for="password" class="form-label"><i class="bi bi-shield-lock me-1"></i>Password</label>
              <input type="password" class="form-control" id="password" placeholder="Create a password" name="password" required>
              <div class="form-text"><small>8+ characters with letters, numbers & symbols</small></div>
            </div>
            
            <div class="form-group mb-4">
              <label for="password_confirmation" class="form-label"><i class="bi bi-shield-check me-1"></i>Confirm Password</label>
              <input type="password" class="form-control" id="password_confirmation" placeholder="Confirm your password" name="password_confirmation" required>
            </div>
            
            <div class="d-grid mt-4 mb-2">
              <button type="submit" class="btn btn-primary">
                <i class="bi bi-check-circle me-1"></i>Register
              </button>
            </div>
            
            <div class="text-center mt-3">
              <small class="text-muted">Already have an account?</small>
              <a href="{{ route('login') }}" class="ms-1 link-primary">Log in</a>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
