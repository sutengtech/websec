@extends('layouts.master')
@section('title', 'Change Password')
@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-lg-7">
            <div class="card border-0 shadow-sm rounded-3 overflow-hidden">
                <div class="card-body p-0">
                    <!-- Header -->
                    <div class="bg-gradient-primary p-4 position-relative">
                        <div class="d-flex justify-content-between align-items-center position-relative z-index-1">
                            <div>
                                <h5 class="fw-bold text-white mb-1">Change Password</h5>
                                <p class="text-white text-opacity-75 mb-0 small">Update security credentials for {{ $user->name }}</p>
                            </div>
                        </div>
                        <div class="position-absolute top-0 end-0 h-100 d-none d-md-flex align-items-center pe-4">
                            <i class="bi bi-key text-white text-opacity-25" style="font-size: 4rem;"></i>
                        </div>
                    </div>

                    <!-- Form -->
                    <div class="p-4">
                        <form action="{{route('save_password', $user->id)}}" method="post">
                            {{ csrf_field() }}
                            @foreach($errors->all() as $error)
                            <div class="alert alert-danger d-flex align-items-center mb-4" role="alert">
                                <i class="bi bi-exclamation-circle-fill me-2 fs-5"></i>
                                <div>{{$error}}</div>
                            </div>
                            @endforeach

                            @if(!auth()->user()->hasPermissionTo('admin_users') || auth()->id()==$user->id)
                            <div class="mb-4">
                                <label for="old_password" class="form-label fw-medium">Current Password</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0"><i class="bi bi-lock"></i></span>
                                    <input type="password" class="form-control border-start-0" id="old_password" placeholder="Enter current password" name="old_password" required>
                                </div>
                                <div class="form-text small">
                                    <i class="bi bi-info-circle me-1"></i>Required to verify your identity
                                </div>
                            </div>
                            @endcan

                            <div class="mb-4">
                                <label for="password" class="form-label fw-medium">New Password</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0"><i class="bi bi-shield-lock"></i></span>
                                    <input type="password" class="form-control border-start-0" id="password" placeholder="Enter new password" name="password" required>
                                </div>
                                <div class="form-text small">
                                    <i class="bi bi-shield-check me-1"></i>Use at least 8 characters with a mix of letters, numbers & symbols
                                </div>
                            </div>
                            
                            <div class="mb-4">
                                <label for="password_confirmation" class="form-label fw-medium">Confirm New Password</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0"><i class="bi bi-shield-lock-fill"></i></span>
                                    <input type="password" class="form-control border-start-0" id="password_confirmation" placeholder="Confirm your new password" name="password_confirmation" required>
                                </div>
                            </div>

                            <div class="d-flex justify-content-between mt-4">
                                    <a href="javascript:history.back()" class="btn btn-light rounded-pill px-4">
                                    <i class="bi bi-arrow-left me-1"></i> Back
                                </a>
                                <button type="submit" class="btn btn-primary rounded-pill px-4">
                                    <i class="bi bi-check2 me-1"></i> Update Password
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection