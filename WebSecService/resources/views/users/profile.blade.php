@extends('layouts.master')
@section('title', 'User Dashboard')
@section('content')
<div class="container py-4">
    <!-- Header Section with User Avatar and Details -->
    <div class="card mb-4 border-0 shadow-sm rounded-3 overflow-hidden">
        <div class="card-body p-0">
            <div class="row g-0">
                <div class="col-md-12">
                    <div class="p-4 bg-light">
                        <div class="d-flex flex-wrap justify-content-between align-items-center">
                            <div class="d-flex align-items-center">
                                <div class="avatar-circle bg-primary text-white me-3" style="width: 64px; height: 64px; font-size: 24px;">
                                    {{ strtoupper(substr($user->name, 0, 1)) }}
                                </div>
                                <div>
                                    <h2 class="fw-bold mb-1">{{ $user->name }}</h2>
                                    <div class="d-flex align-items-center text-muted">
                                        <i class="bi bi-envelope me-2"></i>{{ $user->email }}
                                    </div>
                                    <div class="mt-2">
                    @foreach($user->roles as $role)
                                            <span class="badge rounded-pill {{ $role->name == 'Admin' ? 'bg-danger' : ($role->name == 'Employee' ? 'bg-warning text-dark' : 'bg-info text-dark') }} me-1">
                                                <i class="bi {{ $role->name == 'Admin' ? 'bi-shield-lock' : ($role->name == 'Employee' ? 'bi-person-badge' : 'bi-person') }} me-1 small"></i>
                                                {{$role->name}}
                                            </span>
                    @endforeach
                                    </div>
                                </div>
                            </div>
                            <div class="d-flex mt-3 mt-md-0">
                                @if(auth()->user()->hasPermissionTo('edit_users') || auth()->id() == $user->id)
                                <a href="{{ route('users_edit', $user->id) }}" class="btn btn-outline-primary rounded-pill me-2">
                                    <i class="bi bi-pencil me-1"></i> Edit Profile
                                </a>
                                @endif
                                @if(auth()->user()->hasPermissionTo('admin_users') || auth()->id() == $user->id)
                                <a class="btn btn-outline-secondary rounded-pill" href="{{ route('edit_password', $user->id) }}">
                                    <i class="bi bi-key me-1"></i> Change Password
                                </a>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if($user->hasRole('Employee'))
    <!-- Employee Quick Actions -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow-sm border-0 rounded-3 overflow-hidden">
                <div class="card-body p-0">
                    <div class="bg-gradient-primary p-4 position-relative">
                        <div class="d-flex justify-content-between align-items-center position-relative z-index-1">
                            <div>
                                <h5 class="fw-bold text-white mb-1">Quick Actions</h5>
                                <p class="text-white text-opacity-75 mb-0 small">Employee tools and shortcuts</p>
                            </div>
                        </div>
                        <div class="position-absolute top-0 end-0 h-100 d-none d-md-flex align-items-center pe-4">
                            <i class="bi bi-grid-3x3-gap text-white text-opacity-25" style="font-size: 4rem;"></i>
                        </div>
                    </div>
                    <div class="p-4">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <a href="{{ route('users') }}" class="card h-100 text-decoration-none shadow-sm border-0 hover-card">
                                    <div class="card-body text-center p-4">
                                        <div class="rounded-circle bg-info bg-opacity-10 mx-auto mb-3 d-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                                            <i class="bi bi-people text-info fs-3"></i>
                                        </div>
                                        <h6 class="fw-bold mb-1 text-dark">Manage Customers</h6>
                                        <p class="text-muted small mb-0">View and manage customer accounts</p>
                                    </div>
                                </a>
                            </div>
                            <div class="col-md-6">
                                <a href="{{ route('products_list') }}" class="card h-100 text-decoration-none shadow-sm border-0 hover-card">
                                    <div class="card-body text-center p-4">
                                        <div class="rounded-circle bg-success bg-opacity-10 mx-auto mb-3 d-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                                            <i class="bi bi-box-seam text-success fs-3"></i>
                                        </div>
                                        <h6 class="fw-bold mb-1 text-dark">Manage Products</h6>
                                        <p class="text-muted small mb-0">Edit products and inventory</p>
                                    </div>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif

    @if($user->hasRole('Admin'))
    <!-- Admin Dashboard -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow-sm border-0 rounded-3 overflow-hidden">
                <div class="card-body p-0">
                    <div class="bg-gradient-danger p-4 position-relative">
                        <div class="d-flex justify-content-between align-items-center position-relative z-index-1">
                            <div>
                                <h5 class="fw-bold text-white mb-1">Administration</h5>
                                <p class="text-white text-opacity-75 mb-0 small">System management and configuration</p>
                            </div>
                        </div>
                        <div class="position-absolute top-0 end-0 h-100 d-none d-md-flex align-items-center pe-4">
                            <i class="bi bi-shield text-white text-opacity-25" style="font-size: 4rem;"></i>
                        </div>
                    </div>
                    <div class="p-4">
                        <div class="row g-3">
                            <div class="col-md-4">
                                <a href="{{ route('users') }}" class="card h-100 text-decoration-none shadow-sm border-0 hover-card">
                                    <div class="card-body text-center p-4">
                                        <div class="rounded-circle bg-danger bg-opacity-10 mx-auto mb-3 d-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                                            <i class="bi bi-people-fill text-danger fs-3"></i>
                                        </div>
                                        <h6 class="fw-bold mb-1 text-dark">User Management</h6>
                                        <p class="text-muted small mb-0">Manage all user accounts and roles</p>
                                    </div>
                                </a>
                            </div>
                            <div class="col-md-4">
                                <a href="{{ route('products_list') }}" class="card h-100 text-decoration-none shadow-sm border-0 hover-card">
                                    <div class="card-body text-center p-4">
                                        <div class="rounded-circle bg-success bg-opacity-10 mx-auto mb-3 d-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                                            <i class="bi bi-box-seam text-success fs-3"></i>
                                        </div>
                                        <h6 class="fw-bold mb-1 text-dark">Product Inventory</h6>
                                        <p class="text-muted small mb-0">Manage product catalog and stock</p>
                                    </div>
                                </a>
                            </div>
                            <div class="col-md-4">
                                <a href="{{ route('users_add') }}" class="card h-100 text-decoration-none shadow-sm border-0 hover-card">
                                    <div class="card-body text-center p-4">
                                        <div class="rounded-circle bg-primary bg-opacity-10 mx-auto mb-3 d-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                                            <i class="bi bi-person-plus text-primary fs-3"></i>
                                        </div>
                                        <h6 class="fw-bold mb-1 text-dark">Add New User</h6>
                                        <p class="text-muted small mb-0">Create users with custom roles</p>
                                    </div>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Admin System Stats -->
    <div class="row mb-4">
        <div class="col-md-6">
            <div class="card border-0 shadow-sm rounded-3 overflow-hidden mb-4">
                <div class="card-body p-0">
                    <div class="p-4 bg-dark bg-opacity-10 border-bottom">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h5 class="fw-bold text-dark mb-1"><i class="bi bi-speedometer2 me-2"></i> System Information</h5>
                                <p class="text-muted mb-0 small">Server and application status</p>
                            </div>
                        </div>
                    </div>
                    <div class="p-4">
                        <div class="d-flex justify-content-between mb-3">
                            <div>
                                <div class="text-muted small mb-1">PHP Version</div>
                                <div class="fw-medium">{{ phpversion() }}</div>
                            </div>
                            <div>
                                <div class="text-muted small mb-1">Laravel Version</div>
                                <div class="fw-medium">{{ app()->version() }}</div>
                            </div>
                            <div>
                                <div class="text-muted small mb-1">Environment</div>
                                <div class="fw-medium">{{ app()->environment() }}</div>
                            </div>
                        </div>
                        <div class="alert alert-success bg-success bg-opacity-10 border-0 rounded-3 mb-0">
                            <div class="d-flex align-items-center">
                                <i class="bi bi-check-circle text-success me-2 fs-5"></i>
                                <div>
                                    <div class="fw-medium text-success">System running normally</div>
                                    <div class="text-muted small">All services are operational</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-6">
            <div class="card border-0 shadow-sm rounded-3 overflow-hidden mb-4">
                <div class="card-body p-0">
                    <div class="p-4 bg-dark bg-opacity-10 border-bottom">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h5 class="fw-bold text-dark mb-1"><i class="bi bi-bar-chart me-2"></i> User Statistics</h5>
                                <p class="text-muted mb-0 small">Account distribution by role</p>
                            </div>
                        </div>
                    </div>
                    <div class="p-4">
        <div class="row">
                            <div class="col-4 text-center mb-3">
                                <div class="rounded-circle bg-danger bg-opacity-10 mx-auto mb-2 d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                                    <i class="bi bi-shield-lock text-danger"></i>
                                </div>
                                <div class="fw-bold">Admins</div>
                                <div class="h4 mb-0">{{ \Spatie\Permission\Models\Role::where('name', 'Admin')->first()->users()->count() }}</div>
                            </div>
                            <div class="col-4 text-center mb-3">
                                <div class="rounded-circle bg-warning bg-opacity-10 mx-auto mb-2 d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                                    <i class="bi bi-person-badge text-warning"></i>
                                </div>
                                <div class="fw-bold">Employees</div>
                                <div class="h4 mb-0">{{ \Spatie\Permission\Models\Role::where('name', 'Employee')->first()->users()->count() }}</div>
                            </div>
                            <div class="col-4 text-center mb-3">
                                <div class="rounded-circle bg-info bg-opacity-10 mx-auto mb-2 d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                                    <i class="bi bi-person text-info"></i>
                                </div>
                                <div class="fw-bold">Customers</div>
                                <div class="h4 mb-0">{{ \Spatie\Permission\Models\Role::where('name', 'Customer')->first()->users()->count() }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif

    @if(!$user->hasRole('Customer'))
    <!-- User Permissions -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm rounded-3 overflow-hidden">
                <div class="card-body p-0">
                    <div class="p-4 bg-dark bg-opacity-10 border-bottom">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h5 class="fw-bold text-dark mb-1"><i class="bi bi-shield-lock me-2"></i> User Permissions</h5>
                                <p class="text-muted mb-0 small">Access control settings</p>
                            </div>
                            <div class="rounded-circle bg-secondary bg-opacity-10 p-3">
                                <i class="bi bi-key text-secondary fs-4"></i>
                            </div>
                        </div>
                    </div>
                    <div class="p-4">
                        @if(count($permissions) > 0)
                        <div class="mb-3">
                            <h6 class="fw-bold text-muted mb-3">Assigned Permissions</h6>
                            <div class="d-flex flex-wrap gap-2">
                                @foreach($permissions as $permission)
                                    <span class="badge rounded-pill bg-secondary bg-opacity-10 text-secondary px-3 py-2">
                                        <i class="bi bi-check-circle me-1 small"></i>
                                        {{$permission->display_name ?? $permission->name}}
                                    </span>
                                @endforeach
                            </div>
                        </div>
                        <div class="alert alert-info border-0 bg-info bg-opacity-10 d-flex align-items-center mt-3">
                            <i class="bi bi-info-circle text-info me-2 fs-5"></i>
                            <div>
                                <span class="fw-medium text-info">Access Control</span>
                                <p class="text-muted small mb-0 mt-1">These permissions define what actions this user can perform in the system.</p>
                            </div>
                        </div>
                        @else
                        <div class="text-center text-muted py-3">
                            <i class="bi bi-exclamation-circle fs-4 mb-2 d-block"></i>
                            <p class="mb-0">No specific permissions assigned</p>
                        </div>
                        @endif
                        
                        <div class="d-flex justify-content-end mt-3">
                            @if(auth()->user()->hasPermissionTo('admin_users') && auth()->id() == $user->id)
                            <a href="{{ route('users_edit', $user->id) }}" class="btn btn-outline-primary rounded-pill">
                                <i class="bi bi-pencil me-1"></i> Edit Permissions
                            </a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif

    @if($user->hasRole('Customer'))
        <!-- Customer Dashboard -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm rounded-3 overflow-hidden">
                <div class="card-body p-0">
                    <div class="bg-gradient-primary p-4 position-relative">
                        <div class="d-flex justify-content-between align-items-center position-relative z-index-1">
                            <div>
                                <h5 class="fw-bold text-white mb-1">Customer Dashboard</h5>
                                <p class="text-white text-opacity-75 mb-0 small">Welcome back, {{ $user->name }}</p>
                            </div>
                            <div class="bg-white bg-opacity-10 p-2 rounded-circle">
                                <i class="bi bi-speedometer2 text-white fs-3"></i>
                            </div>
                        </div>
                        <div class="position-absolute top-0 end-0 h-100 d-none d-md-flex align-items-center pe-4">
                            <i class="bi bi-person-circle text-white text-opacity-25" style="font-size: 4rem;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Stats Cards Row -->
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card border-0 shadow-sm rounded-3 overflow-hidden h-100">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between mb-3">
                        <div class="rounded-circle bg-success bg-opacity-10 p-3">
                            <i class="bi bi-wallet2 text-success fs-3"></i>
                        </div>
                        <div class="bg-success bg-opacity-10 px-3 py-1 rounded-pill d-flex align-items-center">
                            <i class="bi bi-currency-dollar text-success me-1 small"></i>
                            <span class="text-success fw-medium small">Funds</span>
                        </div>
                    </div>
                    <h3 class="display-6 fw-bold mb-1">${{ number_format($user->credit, 2) }}</h3>
                    <p class="text-muted mb-0">Available Balance</p>
                    
                    @if(auth()->user()->hasPermissionTo('manage_customer_credit'))
                    <div class="mt-3">
                        <button type="button" class="btn btn-success rounded-pill w-100" data-bs-toggle="modal" data-bs-target="#addCreditModal">
                            <i class="bi bi-plus-circle me-1"></i> Add Credit
                        </button>
                    </div>
                    @endif
                </div>
            </div>
        </div>
        
        <div class="col-md-4">
            <div class="card border-0 shadow-sm rounded-3 overflow-hidden h-100">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between mb-3">
                        <div class="rounded-circle bg-primary bg-opacity-10 p-3">
                            <i class="bi bi-bag-check text-primary fs-3"></i>
                        </div>
                        <div class="bg-primary bg-opacity-10 px-3 py-1 rounded-pill d-flex align-items-center">
                            <i class="bi bi-cart-check text-primary me-1 small"></i>
                            <span class="text-primary fw-medium small">Orders</span>
                        </div>
                    </div>
                    <h3 class="display-6 fw-bold mb-1">{{ count($purchases) }}</h3>
                    <p class="text-muted mb-0">Total Purchases</p>
                    
                    <div class="mt-3">
                        <a href="{{ route('products_list') }}" class="btn btn-primary rounded-pill w-100">
                            <i class="bi bi-shop me-1"></i> Browse Products
                        </a>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-4">
            <div class="card border-0 shadow-sm rounded-3 overflow-hidden h-100">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between mb-3">
                        <div class="rounded-circle bg-danger bg-opacity-10 p-3">
                            <i class="bi bi-cash-stack text-danger fs-3"></i>
                        </div>
                        <div class="bg-danger bg-opacity-10 px-3 py-1 rounded-pill d-flex align-items-center">
                            <i class="bi bi-graph-up-arrow text-danger me-1 small"></i>
                            <span class="text-danger fw-medium small">Spent</span>
                        </div>
                    </div>
                    <h3 class="display-6 fw-bold mb-1">${{ number_format($purchases->sum('total_price'), 2) }}</h3>
                    <p class="text-muted mb-0">Total Amount Spent</p>
                    
                    @php
                        $latestPurchase = $purchases->first();
                    @endphp
                    @if($latestPurchase)
                    <div class="mt-3 d-flex align-items-center">
                        <i class="bi bi-clock-history text-muted me-2"></i>
                        <span class="text-muted small">Last purchase: {{ $latestPurchase->created_at->format('M d, Y') }}</span>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Purchase History Card -->
    <div class="row mb-4">
        <div class="col-md-8">
            <div class="card border-0 shadow-sm rounded-3 overflow-hidden h-100">
                <div class="card-body p-0">
                    <div class="p-4 bg-light border-bottom d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="fw-bold text-primary mb-1">
                                <i class="bi bi-clock-history me-2"></i>Purchase History
                            </h5>
                            <p class="text-muted mb-0 small">Your recent transactions</p>
                        </div>
                        <div class="rounded-pill bg-primary bg-opacity-10 px-3 py-2">
                            <span class="text-primary fw-medium">{{ count($purchases) }} Orders</span>
                        </div>
                    </div>
                    
                    @if(count($purchases) > 0)
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="bg-light">
                                    <tr>
                                        <th class="ps-4">
                                            <i class="bi bi-box-seam text-muted me-1 small"></i> Product
                                        </th>
                                        <th>
                                            <i class="bi bi-123 text-muted me-1 small"></i> Quantity
                                        </th>
                                        <th>
                                            <i class="bi bi-tag text-muted me-1 small"></i> Price
                                        </th>
                                        <th class="pe-4">
                                            <i class="bi bi-calendar3 text-muted me-1 small"></i> Date
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($purchases->take(5) as $purchase)
                                    <tr>
                                        <td class="ps-4">
                                            <div class="d-flex align-items-center">
                                                <div class="rounded-circle bg-light-primary p-2 me-2" style="width: 32px; height: 32px; display: flex; align-items: center; justify-content: center;">
                                                    <i class="bi bi-box text-primary small"></i>
                                                </div>
                                                <span class="fw-medium">{{ $purchase->product->name }}</span>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="badge bg-light text-dark rounded-pill px-3 py-2">
                                                {{ $purchase->quantity }}
                                            </span>
                                        </td>
                                        <td>
                                            <span class="text-success fw-medium">${{ number_format($purchase->total_price, 2) }}</span>
                                        </td>
                                        <td class="pe-4">
                                            <div class="d-flex align-items-center">
                                                <i class="bi bi-clock text-muted me-1 small"></i>
                                                {{ $purchase->created_at->format('M d, Y H:i') }}
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        
                        @if(count($purchases) > 5)
                        <div class="text-center py-3 border-top">
                            <button class="btn btn-sm btn-link" type="button" data-bs-toggle="collapse" data-bs-target="#allPurchases" aria-expanded="false">
                                <i class="bi bi-chevron-down me-1"></i> View All Purchases
                            </button>
                        </div>
                        @endif
            @else
                        <div class="p-4 text-center">
                            <div class="text-muted mb-3">
                                <i class="bi bi-cart-x fs-1"></i>
                            </div>
                            <h5 class="text-muted">No purchase history yet</h5>
                            <p class="text-muted small mb-3">Make your first purchase to view your transaction history</p>
                            <a href="{{ route('products_list') }}" class="btn btn-primary rounded-pill px-4">
                                <i class="bi bi-shop me-1"></i> Browse Products
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
        
        <div class="col-md-4">
            <div class="card border-0 shadow-sm rounded-3 overflow-hidden h-100">
                <div class="card-body p-0">
                    <div class="p-4 bg-light border-bottom">
                        <h5 class="fw-bold text-dark mb-1">
                            <i class="bi bi-shield-lock me-2"></i>Account Access
                        </h5>
                        <p class="text-muted mb-0 small">Your permissions and settings</p>
                    </div>
                    <div class="p-4">
                        <div class="d-flex align-items-center mb-4">
                            <div class="rounded-circle bg-info bg-opacity-10 p-3 me-3">
                                <i class="bi bi-person-badge text-info fs-4"></i>
                            </div>
                            <div>
                                <h6 class="fw-bold mb-1">Account Type</h6>
                                <span class="badge rounded-pill bg-info text-dark">
                                    <i class="bi bi-person me-1 small"></i>
                                    Customer
                                </span>
                            </div>
                        </div>
                        
                        <div class="mb-4">
                            <h6 class="fw-bold text-muted mb-2">
                                <i class="bi bi-key me-1"></i> Your Permissions
                            </h6>
                            @if(count($permissions) > 0)
                                <div class="d-flex flex-wrap gap-2">
                                    @foreach($permissions as $permission)
                                        <span class="badge rounded-pill bg-secondary bg-opacity-10 text-secondary px-3 py-2">
                                            <i class="bi bi-check-circle me-1 small"></i>
                                            {{$permission->display_name ?? $permission->name}}
                                        </span>
                                    @endforeach
                                </div>
                            @else
                                <div class="alert alert-light border-0 mb-0">
                                    <i class="bi bi-info-circle text-muted me-2"></i>
                                    <span class="text-muted">Standard customer permissions</span>
            </div>
            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    @if(count($purchases) > 5)
    <!-- All Purchases (Collapsed) -->
    <div class="collapse mb-4" id="allPurchases">
        <div class="card border-0 shadow-sm rounded-3 overflow-hidden">
            <div class="card-body p-0">
                <div class="p-4 bg-light border-bottom">
                    <h5 class="fw-bold mb-0"><i class="bi bi-bag me-2"></i> All Purchase History</h5>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th class="ps-4">
                                    <i class="bi bi-box-seam text-muted me-1 small"></i> Product
                                </th>
                                <th>
                                    <i class="bi bi-123 text-muted me-1 small"></i> Quantity
                                </th>
                                <th>
                                    <i class="bi bi-tag text-muted me-1 small"></i> Price
                                </th>
                                <th class="pe-4">
                                    <i class="bi bi-calendar3 text-muted me-1 small"></i> Date
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($purchases->skip(5) as $purchase)
                            <tr>
                                <td class="ps-4">
                                    <div class="d-flex align-items-center">
                                        <div class="rounded-circle bg-light-primary p-2 me-2" style="width: 32px; height: 32px; display: flex; align-items: center; justify-content: center;">
                                            <i class="bi bi-box text-primary small"></i>
                                        </div>
                                        <span class="fw-medium">{{ $purchase->product->name }}</span>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge bg-light text-dark rounded-pill px-3 py-2">
                                        {{ $purchase->quantity }}
                                    </span>
                                </td>
                                <td>
                                    <span class="text-success fw-medium">${{ number_format($purchase->total_price, 2) }}</span>
                                </td>
                                <td class="pe-4">
                                    <div class="d-flex align-items-center">
                                        <i class="bi bi-clock text-muted me-1 small"></i>
                                        {{ $purchase->created_at->format('M d, Y H:i') }}
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    @endif
            @endif
</div>

@if(auth()->user()->hasPermissionTo('manage_customer_credit') && $user->hasRole('Customer'))
<!-- Add Credit Modal -->
<div class="modal fade" id="addCreditModal" tabindex="-1" aria-labelledby="addCreditModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <form action="{{ route('add_credit', ['user' => $user->id]) }}" method="POST">
                @csrf
                <div class="modal-header border-bottom-0 pb-0">
                    <h5 class="modal-title fw-bold" id="addCreditModalLabel">
                        <i class="bi bi-wallet2 text-primary me-2"></i>Add Credit
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body pt-0">
                    <p class="text-muted mb-4">Adding credit to <span class="fw-medium">{{ $user->name }}</span>'s account</p>
                    
                    <div class="mb-4">
                        <label for="creditAmount" class="form-label fw-medium">Amount to Add</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0"><i class="bi bi-currency-dollar"></i></span>
                            <button type="button" class="btn btn-light border" onclick="decrementCredit()">
                                <i class="bi bi-dash"></i>
                            </button>
                            <input type="number" class="form-control text-center border-start-0 border-end-0" id="creditAmount" 
                                   name="amount" min="0.01" step="any" value="50" required>
                            <button type="button" class="btn btn-light border" onclick="incrementCredit()">
                                <i class="bi bi-plus"></i>
                            </button>
                        </div>
                    </div>
                    
                    <div class="card bg-light-info border-0 mb-4">
                        <div class="card-body p-3">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <span class="text-muted">Current Balance</span>
                                    <h5 class="mb-0">${{ number_format($user->credit, 2) }}</h5>
                                </div>
                                <div class="text-end">
                                    <span class="text-muted">New Balance</span>
                                    <h5 class="mb-0 text-primary" id="newBalance">${{ number_format($user->credit + 50, 2) }}</h5>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Quick amount buttons -->
                    <div class="text-muted small mb-2">Quick amounts:</div>
                    <div class="d-flex flex-wrap gap-2">
                        <button type="button" class="btn btn-sm btn-outline-primary rounded-pill" onclick="setAmount(10)">$10</button>
                        <button type="button" class="btn btn-sm btn-outline-primary rounded-pill" onclick="setAmount(20)">$20</button>
                        <button type="button" class="btn btn-sm btn-outline-primary rounded-pill" onclick="setAmount(50)">$50</button>
                        <button type="button" class="btn btn-sm btn-outline-primary rounded-pill" onclick="setAmount(100)">$100</button>
                        <button type="button" class="btn btn-sm btn-outline-primary rounded-pill" onclick="setAmount(200)">$200</button>
                    </div>
                </div>
                <div class="modal-footer border-top-0 pt-1">
                    <button type="button" class="btn btn-light rounded-pill" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary rounded-pill px-4">
                        <i class="bi bi-check2 me-1"></i> Add Credit
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endif

<style>
    .avatar-circle {
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 500;
    }
    
    .bg-light-info {
        background-color: rgba(13, 202, 240, 0.1);
    }
    
    .hover-card {
        transition: transform 0.2s, box-shadow 0.2s;
    }
    
    .hover-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.1) !important;
    }
    
    .bg-gradient-primary {
        background: linear-gradient(135deg, #0d6efd 0%, #0dcaf0 100%);
    }
    
    .bg-gradient-danger {
        background: linear-gradient(135deg, #dc3545 0%, #fd7e14 100%);
    }
    
    .z-index-1 {
        z-index: 1;
    }
</style>

<script>
    // JavaScript functions for credit amount handling
    function updateBalance() {
        const creditAmount = parseFloat(document.getElementById('creditAmount').value) || 0;
        const currentBalance = {{ $user->credit }};
        const newTotal = currentBalance + creditAmount;
        
        document.getElementById('newBalance').textContent = '$' + newTotal.toFixed(2);
    }
    
    function incrementCredit() {
        const input = document.getElementById('creditAmount');
        input.value = (parseFloat(input.value) || 0) + 10;
        updateBalance();
    }
    
    function decrementCredit() {
        const input = document.getElementById('creditAmount');
        let newValue = (parseFloat(input.value) || 0) - 10;
        input.value = newValue <= 0 ? 0.01 : newValue.toFixed(2);
        updateBalance();
    }
    
    function setAmount(amount) {
        document.getElementById('creditAmount').value = amount;
        updateBalance();
    }
    
    // Initialize the calculator
    document.addEventListener('DOMContentLoaded', function() {
        const creditInput = document.getElementById('creditAmount');
        if (creditInput) {
            creditInput.addEventListener('input', updateBalance);
            updateBalance();
        }
    });
</script>

@endsection
