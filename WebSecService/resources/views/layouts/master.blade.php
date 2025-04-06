<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Basic Website - @yield('title')</title>
    <link href="{{ asset('css/bootstrap.min.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        .card-dashboard {
            transition: transform 0.3s, box-shadow 0.3s;
            border-radius: 10px;
            overflow: hidden;
            height: 100%;
        }
        .card-dashboard:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0,0,0,0.1);
        }
        .card-stat {
            border-left: 4px solid;
        }
        .text-truncate-2 {
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
        
        .product-image-container {
            height: 100%;
            position: relative;
            overflow: hidden;
            background-color: #f8f9fa;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .product-image {
            width: 100%;
            height: 100%;
            object-fit: contain;
            padding: 0.5rem;
        }
        
        /* Notification Styles */
        .bg-light-success {
            background-color: rgba(25, 135, 84, 0.1);
            border-left: 4px solid #198754;
        }
        
        .bg-light-danger {
            background-color: rgba(220, 53, 69, 0.1);
            border-left: 4px solid #dc3545;
        }
        
        .bg-light-warning {
            background-color: rgba(255, 193, 7, 0.1);
            border-left: 4px solid #ffc107;
        }
        
        .bg-light-info {
            background-color: rgba(13, 202, 240, 0.1);
            border-left: 4px solid #0dcaf0;
        }
        
        @media (max-width: 767.98px) {
            .product-image-container {
                height: 200px;
            }
        }
    </style>
    <script src="{{ asset('js/bootstrap.bundle.min.js') }}"></script>
</head>
<body>
    @include('layouts.menu')
    <div class="container">
        @if(session('success'))
        <div class="card shadow-sm border-0 mt-3">
            <div class="card-body d-flex align-items-center bg-light-success">
                <div class="me-3 text-success">
                    <i class="bi bi-check-circle-fill fs-4"></i>
                </div>
                <div class="flex-grow-1">
                    <h6 class="mb-0 fw-semibold">Success</h6>
                    <p class="mb-0">{{ session('success') }}</p>
                </div>
                <button type="button" class="btn-close" onclick="this.parentElement.parentElement.style.display='none'"></button>
            </div>
        </div>
        @endif
        
        @if(session('error'))
        <div class="card shadow-sm border-0 mt-3">
            <div class="card-body d-flex align-items-center bg-light-danger">
                <div class="me-3 text-danger">
                    <i class="bi bi-exclamation-circle-fill fs-4"></i>
                </div>
                <div class="flex-grow-1">
                    <h6 class="mb-0 fw-semibold">Error</h6>
                    <p class="mb-0">{{ session('error') }}</p>
                </div>
                <button type="button" class="btn-close" onclick="this.parentElement.parentElement.style.display='none'"></button>
            </div>
        </div>
        @endif
        
        @if(session('warning'))
        <div class="card shadow-sm border-0 mt-3">
            <div class="card-body d-flex align-items-center bg-light-warning">
                <div class="me-3 text-warning">
                    <i class="bi bi-exclamation-triangle-fill fs-4"></i>
                </div>
                <div class="flex-grow-1">
                    <h6 class="mb-0 fw-semibold">Warning</h6>
                    <p class="mb-0">{{ session('warning') }}</p>
                </div>
                <button type="button" class="btn-close" onclick="this.parentElement.parentElement.style.display='none'"></button>
            </div>
        </div>
        @endif
        
        @if(session('info'))
        <div class="card shadow-sm border-0 mt-3">
            <div class="card-body d-flex align-items-center bg-light-info">
                <div class="me-3 text-info">
                    <i class="bi bi-info-circle-fill fs-4"></i>
                </div>
                <div class="flex-grow-1">
                    <h6 class="mb-0 fw-semibold">Information</h6>
                    <p class="mb-0">{{ session('info') }}</p>
                </div>
                <button type="button" class="btn-close" onclick="this.parentElement.parentElement.style.display='none'"></button>
            </div>
        </div>
        @endif
        
        @yield('content')
    </div>
    
    <script>
        // Auto-dismiss notifications after 5 seconds
        document.addEventListener('DOMContentLoaded', function() {
            setTimeout(function() {
                const notifications = document.querySelectorAll('.card.shadow-sm.border-0.mt-3');
                notifications.forEach(function(notification) {
                    notification.style.transition = 'opacity 0.5s ease';
                    notification.style.opacity = '0';
                    setTimeout(function() {
                        notification.style.display = 'none';
                    }, 500);
                });
            }, 5000);
        });
    </script>
</body>
</html>
