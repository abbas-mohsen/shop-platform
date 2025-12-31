{{-- resources/views/layouts/app.blade.php --}}
<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>@yield('title', 'Shop Platform')</title>

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Styles / Scripts (Laravel 8 default – keep as you already had it) -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <script src="{{ asset('js/app.js') }}" defer></script>

    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: radial-gradient(circle at top, #f5f7ff 0, #eef2f7 40%, #e3e7ee 100%);
            min-height: 100vh;
        }

        .navbar-brand {
            font-weight: 700;
            letter-spacing: 0.03em;
        }

        .navbar {
            box-shadow: 0 4px 15px rgba(15, 23, 42, 0.06);
        }

        .main-wrapper {
            padding-top: 1.5rem;
            padding-bottom: 2rem;
        }

        .card {
            border-radius: 1rem;
            border: none;
        }

        .card.shadow-soft {
            box-shadow: 0 10px 25px rgba(15, 23, 42, 0.06);
        }

        .btn-primary {
            border-radius: 999px;
            padding-inline: 1.5rem;
            font-weight: 500;
        }

        .btn-outline-primary {
            border-radius: 999px;
            font-weight: 500;
        }

        .badge-status {
            padding: 0.35rem 0.8rem;
            border-radius: 999px;
            font-size: 0.75rem;
        }

        .badge-status.pending { background-color: #fff7e6; color: #b36b00; }
        .badge-status.processing { background-color: #e6f4ff; color: #0050b3; }
        .badge-status.completed { background-color: #e6fffb; color: #006d75; }
        .badge-status.cancelled { background-color: #fff1f0; color: #a8071a; }

        footer.site-footer {
            font-size: 0.85rem;
            color: #6b7280;
        }

        .nav-link.active {
            font-weight: 600;
            position: relative;
        }
        .nav-link.active::after {
            content: '';
            position: absolute;
            left: 0.75rem;
            right: 0.75rem;
            bottom: -0.3rem;
            height: 2px;
            border-radius: 999px;
            background: linear-gradient(90deg, #2563eb, #7c3aed);
        }
    </style>
</head>
<body>
    <div id="app">
        {{-- Top Navbar --}}
        <nav class="navbar navbar-expand-md navbar-light bg-white">
            <div class="container">
                <a class="navbar-brand" href="{{ url('/') }}">
                    Shop Platform
                </a>

                <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                        data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
                        aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>

                @php
                    $cart = session('cart', []);
                    $cartCount = collect($cart)->sum('quantity');
                @endphp

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar -->
                    <ul class="navbar-nav me-auto">
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}"
                               href="{{ route('home') }}">Home</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('products.*') ? 'active' : '' }}"
                               href="{{ route('products.index') }}">Products</a>
                        </li>

                        @auth
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('cart.*') ? 'active' : '' }}"
                                   href="{{ route('cart.index') }}">
                                    Cart
                                    @if($cartCount)
                                        <span class="badge bg-primary ms-1">{{ $cartCount }}</span>
                                    @endif
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('orders.*') ? 'active' : '' }}"
                                   href="{{ route('orders.index') }}">
                                    My Orders
                                </a>
                            </li>
                        @endauth

                        @auth
                            @if(auth()->user()->is_admin ?? false)
                                <li class="nav-item dropdown">
                                    <a class="nav-link dropdown-toggle {{ request()->is('admin/*') ? 'active' : '' }}"
                                       href="#" id="adminDropdown" role="button" data-bs-toggle="dropdown">
                                        Admin
                                    </a>
                                    <ul class="dropdown-menu">
                                        <li>
                                            <a class="dropdown-item" href="{{ route('admin.products.index') }}">
                                                Manage Products
                                            </a>
                                        </li>
                                        <li>
                                            <a class="dropdown-item" href="{{ route('admin.orders.index') }}">
                                                Manage Orders
                                            </a>
                                        </li>
                                    </ul>
                                </li>
                            @endif
                        @endauth
                    </ul>

                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ms-auto">
                        @guest
                            @if (Route::has('login'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                                </li>
                            @endif

                            @if (Route::has('register'))
                                <li class="nav-item">
                                    <a class="btn btn-primary btn-sm ms-2" href="{{ route('register') }}">
                                        {{ __('Register') }}
                                    </a>
                                </li>
                            @endif
                        @else
                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button"
                                   data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    {{ Auth::user()->name }}
                                </a>

                                <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                                    <!-- <a class="dropdown-item" href="{{ route('orders.index') }}">
                                        My Orders
                                    </a> -->

                                    <!-- <div class="dropdown-divider"></div> -->

                                    <a class="dropdown-item" href="{{ route('logout') }}"
                                       onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                        {{ __('Logout') }}
                                    </a>

                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                        @csrf
                                    </form>
                                </div>
                            </li>
                        @endguest
                    </ul>
                </div>
            </div>
        </nav>

        {{-- Main Content --}}
        <main class="main-wrapper">
            <div class="container">
                @yield('content')
            </div>
        </main>

        {{-- Footer --}}
        <footer class="site-footer py-3 mt-4">
            <div class="container d-flex justify-content-between flex-wrap">
                <span>© {{ date('Y') }} Shop Platform</span>
                <span class="text-muted">Made for CSC 400 – Web Programming</span>
            </div>
        </footer>
    </div>
</body>
</html>
