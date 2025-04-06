@extends('layouts.master')
@section('title', 'Edit User')
@section('content')
<script>
document.addEventListener('DOMContentLoaded', function() {
  document.getElementById('clean_permissions').addEventListener('click', function(e) {
    document.getElementById('permissions').selectedIndex = -1;
    document.getElementById('permissions').value = [];
    e.preventDefault();
    return false;
  });
  
  document.getElementById('clean_roles').addEventListener('click', function(e) {
    document.getElementById('roles').selectedIndex = -1;
    document.getElementById('roles').value = [];
    e.preventDefault();
    return false;
  });
});
</script>

<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm rounded-3 overflow-hidden">
                <div class="card-body p-0">
                    <!-- Header -->
                    <div class="bg-gradient-primary p-4 position-relative">
                        <div class="d-flex justify-content-between align-items-center position-relative z-index-1">
                            <div>
                                <h5 class="fw-bold text-white mb-1">Edit User Profile</h5>
                                <p class="text-white text-opacity-75 mb-0 small">Update user information and access rights</p>
                            </div>
                        </div>
                        <div class="position-absolute top-0 end-0 h-100 d-none d-md-flex align-items-center pe-4">
                            <i class="bi bi-pencil-square text-white text-opacity-25" style="font-size: 4rem;"></i>
                        </div>
                    </div>

                    <!-- Form -->
                    <div class="p-4">
                        <form action="{{route('users_save', $user->id)}}" method="post">
                            {{ csrf_field() }}
                            @foreach($errors->all() as $error)
                            <div class="alert alert-danger d-flex align-items-center mb-4" role="alert">
                                <i class="bi bi-exclamation-circle-fill me-2 fs-5"></i>
                                <div>{{$error}}</div>
                            </div>
                            @endforeach

                            <div class="mb-4">
                                <label for="name" class="form-label fw-medium">Name</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0"><i class="bi bi-person"></i></span>
                                    <input type="text" class="form-control border-start-0" id="name" placeholder="Full Name" name="name" required value="{{$user->name}}">
                                </div>
                            </div>

                            @can('admin_users')
                            <div class="row">
                                <div class="col-md-6 mb-4">
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <label for="roles" class="form-label fw-medium mb-0">Roles</label>
                                        <button id="clean_roles" class="btn btn-sm btn-link text-decoration-none p-0">
                                            <i class="bi bi-x-circle me-1 small"></i>Reset
                                        </button>
                                    </div>
                                    <select multiple class="form-select border" id="roles" name="roles[]" size="5" style="border-radius: 0.5rem;">
                                        @foreach($roles as $role)
                                        <option value='{{$role->name}}' {{$role->taken ? 'selected' : ''}}>
                                            {{$role->name}}
                                        </option>
                                        @endforeach
                                    </select>
                                    <div class="form-text small">
                                        <i class="bi bi-info-circle me-1"></i>Hold Ctrl/Cmd to select multiple roles
                                    </div>
                                </div>

                                <div class="col-md-6 mb-4">
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <label for="permissions" class="form-label fw-medium mb-0">Direct Permissions</label>
                                        <button id="clean_permissions" class="btn btn-sm btn-link text-decoration-none p-0">
                                            <i class="bi bi-x-circle me-1 small"></i>Reset
                                        </button>
                                    </div>
                                    <select multiple class="form-select border" id="permissions" name="permissions[]" size="5" style="border-radius: 0.5rem;">
                                        @foreach($permissions as $permission)
                                        <option value='{{$permission->name}}' {{$permission->taken ? 'selected' : ''}}>
                                            {{$permission->display_name}}
                                        </option>
                                        @endforeach
                                    </select>
                                    <div class="form-text small">
                                        <i class="bi bi-info-circle me-1"></i>These permissions will be added directly to the user
                                    </div>
                                </div>
                            </div>
                            @endcan

                            <div class="d-flex justify-content-between mt-2">
                                <a href="javascript:history.back()" class="btn btn-light rounded-pill px-4">
                                    <i class="bi bi-arrow-left me-1"></i> Back
                                </a>
                                <button type="submit" class="btn btn-primary rounded-pill px-4">
                                    <i class="bi bi-check2 me-1"></i> Save Changes
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
