<nav class="navbar navbar-expand-lg navbar-dark bg-dark shadow-sm">
    <div class="container-fluid">
        <a class="navbar-brand d-flex align-items-center" href="{{ url('/') }}">
            <i class="bi bi-shield-lock-fill me-2 text-primary"></i>
            <span class="fw-bold">WebSecService</span>
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNavDropdown">
            <ul class="navbar-nav me-auto">
                <li class="nav-item">
                    <a class="nav-link d-flex align-items-center" href="{{ url('/') }}">
                        <i class="bi bi-house-door me-2"></i> Home
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link d-flex align-items-center" href="./even">Even Numbers</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link d-flex align-items-center" href="./prime">Prime Numbers</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link d-flex align-items-center" href="./multable">Multiplication Table</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link d-flex align-items-center" href="{{ route('products_list') }}">
                        <i class="bi bi-shop me-2"></i> Products
                    </a>
                </li>
                @auth
                    @if(auth()->user()->hasRole('Admin') && auth()->user()->can('show_users'))
                    <li class="nav-item">
                        <a class="nav-link d-flex align-items-center" href="{{ route('users') }}">
                            <i class="bi bi-people me-2"></i> Users
                        </a>
                    </li>
                    @endif
                    @if(auth()->user()->hasRole('Employee') && auth()->user()->can('show_users'))
                    <li class="nav-item">
                        <a class="nav-link d-flex align-items-center" href="{{ route('users') }}">
                            <i class="bi bi-person-badge me-2"></i> Customers
                        </a>
                    </li>
                    @endif
                @endauth
            </ul>
            <ul class="navbar-nav">
                @auth
                <li class="nav-item">
                    <a class="nav-link d-flex align-items-center" href="{{ route('profile') }}">
                        <i class="bi bi-person-circle me-2"></i> 
                        <span class="d-none d-md-inline">{{ auth()->user()->name }}</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link d-flex align-items-center text-danger ms-lg-2" href="{{ route('do_logout') }}">
                        <i class="bi bi-box-arrow-right me-2"></i>
                        <span class="d-none d-md-inline">Logout</span>
                    </a>
                </li>
                @else
                <li class="nav-item">
                    <a class="nav-link d-flex align-items-center" href="{{ route('login') }}">
                        <i class="bi bi-box-arrow-in-right me-2"></i> Login
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link d-flex align-items-center" href="{{ route('register') }}">
                        <i class="bi bi-person-plus me-2"></i> Register
                    </a>
                </li>
                @endauth
            </ul>
        </div>
    </div>
</nav>
