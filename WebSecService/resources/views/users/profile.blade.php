@extends('layouts.master')
@section('title', 'Profile')
@section('content')
<div class="row mt-2">
    <div class="col col-12">
        <h1>Profile</h1>
    </div>
</div>
<div class="card mt-2">
    <div class="card-body">
        <table class="table table-striped">
            <tr><th width="20%">Name</th><td>{{auth()->user()->name}}</td></tr>
            <tr><th>Email</th><td>{{auth()->user()->email}}</td></tr>
            <tr><th>Credit</th><td>${{auth()->user()->credit}}</td></tr>
            <tr>
                <th>Roles</th>
                <td>
                    @foreach(auth()->user()->roles as $role)
                        <span class="badge bg-primary">{{$role->name}}</span>
                    @endforeach
                </td>
            </tr>
            <tr>
                <th>Permissions</th>
                <td>
                    @foreach(auth()->user()->getAllPermissions() as $permission)
                        <span class="badge bg-success">{{$permission->name}}</span>
                    @endforeach
                </td>
            </tr>
        </table>
        @if(auth()->check() && auth()->user()->hasRole('Admin'))
            <a class="btn btn-primary" href="{{route('edit_password', auth()->user()->id)}}">Change Password</a>
        @endif
    </div>
</div>
@endsection