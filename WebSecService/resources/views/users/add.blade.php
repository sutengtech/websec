@extends('layouts.master')
@section('title', 'Add New User')
@section('content')
<div class="d-flex justify-content-center">
  <div class="card m-4 col-sm-6">
    <div class="card-header">
      <h5 class="card-title">Add New User</h5>
    </div>
    <div class="card-body">
      <form action="{{ route('users_save_new') }}" method="post">
        {{ csrf_field() }}
        <div class="form-group">
        @foreach($errors->all() as $error)
          <div class="alert alert-danger">
            <strong>Error!</strong> {{$error}}
          </div>
        @endforeach
        <div class="form-group mb-3">
          <label for="name" class="form-label">Name:</label>
          <input type="text" class="form-control" placeholder="Full name" name="name" value="{{ old('name') }}" required>
        </div>
        <div class="form-group mb-3">
          <label for="email" class="form-label">Email:</label>
          <input type="email" class="form-control" placeholder="Email address" name="email" value="{{ old('email') }}" required>
        </div>
        <div class="form-group mb-3">
          <label for="password" class="form-label">Password:</label>
          <input type="password" class="form-control" placeholder="Password" name="password" required>
        </div>
        <div class="form-group mb-3">
          <label for="password_confirmation" class="form-label">Password Confirmation:</label>
          <input type="password" class="form-control" placeholder="Confirm Password" name="password_confirmation" required>
        </div>
        <div class="form-group mb-3">
          <label for="role" class="form-label">Role:</label>
          <select class="form-select" name="role" required>
            <option value="">Select a role</option>
            @foreach($roles as $role)
              <option value="{{ $role->name }}">{{ $role->name }}</option>
            @endforeach
          </select>
        </div>
        <div class="form-group mb-3">
          <button type="submit" class="btn btn-primary">Create User</button>
          <a href="{{ route('users') }}" class="btn btn-secondary">Cancel</a>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection 