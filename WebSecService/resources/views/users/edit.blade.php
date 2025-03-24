@extends('layouts.master')
@section('title', $user->exists ? 'Edit User' : 'Add User')
@section('content')
<div class="d-flex justify-content-center">
    <div class="row m-4 col-sm-8">
        <form action="{{route('users_save', $user->id)}}" method="post">
            {{ csrf_field() }}
            @foreach($errors->all() as $error)
            <div class="alert alert-danger">
            <strong>Error!</strong> {{$error}}
            </div>
            @endforeach

            <div class="row mb-2">
                <div class="col-12">
                    <label for="name" class="form-label">Name:</label>
                    <input type="text" class="form-control" id="name" placeholder="name" name="name" required value="{{$user->name}}">
                </div>
            </div>
            <div class="row mb-2">
                <div class="col-12">
                    <label for="email" class="form-label">Email:</label>
                    <input type="email" class="form-control" id="email" placeholder="email" name="email" required value="{{$user->email}}">
                </div>
            </div>
            @if(auth()->user()->hasPermissionTo('admin_users'))
            <div class="row mb-2">
                <div class="col-12">
                    <label for="roles" class="form-label">Roles:</label>
                    @foreach($roles as $role)
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" value="{{$role->name}}" id="{{$role->name}}" name="roles[]" {{in_array($role->name, $user->roles->pluck('name')->toArray()) ? 'checked' : ''}}>
                        <label class="form-check-label" for="{{$role->name}}">{{$role->name}}</label>
                    </div>
                    @endforeach
                </div>
            </div>
            <div class="row mb-2">
                <div class="col-12">
                    <label for="permissions" class="form-label">Permissions:</label>
                    @foreach($permissions as $permission)
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" value="{{$permission->name}}" id="{{$permission->name}}" name="permissions[]" {{$user->hasPermissionTo($permission->name) ? 'checked' : ''}}>
                        <label class="form-check-label" for="{{$permission->name}}">{{$permission->display_name}}</label>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif

            <button type="submit" class="btn btn-primary">Submit</button>
        </form>
    </div>
</div>
@endsection