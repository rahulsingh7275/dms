<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name', 'DMS') }} - @yield('title')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: #f8fafc;
        }
        .navbar-brand {
            font-weight: 700;
        }
        .card {
            box-shadow: 0 10px 30px rgba(15, 23, 42, 0.08);
        }
    </style>
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-light bg-white border-bottom mb-4">
    <div class="container">
        <a class="navbar-brand" href="{{ route('home') }}">DMS</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNav" aria-controls="mainNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="mainNav">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item"><a class="nav-link" href="{{ route('home') }}">Home</a></li>
                @auth
                    <li class="nav-item"><a class="nav-link" href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('indexes.index') }}">Indexes</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('deeds.index') }}">Upload Scanned Copy</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('metadata.index') }}">Metadata</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('verifications.index') }}">Verifications</a></li>
                    @if(auth()->user()->isAdmin())
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" role="button" data-bs-toggle="dropdown" aria-expanded="false">Masters</a>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="{{ route('states.index') }}">States</a></li>
                                <li><a class="dropdown-item" href="{{ route('districts.index') }}">Districts</a></li>
                                <li><a class="dropdown-item" href="{{ route('offices.index') }}">Vault Offices</a></li>
                            </ul>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" role="button" data-bs-toggle="dropdown" aria-expanded="false">Admin</a>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="{{ route('admin.users.index') }}">Users</a></li>
                                <li><a class="dropdown-item" href="{{ route('admin.instruments.index') }}">Instruments</a></li>
                                <li><a class="dropdown-item" href="{{ route('admin.instrument-types.index') }}">Instrument Types</a></li>
                            </ul>
                        </li>
                    @endif
                @endauth
            </ul>
            <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                @guest
                    <li class="nav-item"><a class="nav-link" href="{{ route('login') }}">Login</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('register') }}">Register</a></li>
                @else
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" role="button" data-bs-toggle="dropdown" aria-expanded="false">{{ auth()->user()->name }}</a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><span class="dropdown-item-text">{{ auth()->user()->role?->label }}</span></li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <form action="{{ route('logout') }}" method="post" class="m-0">
                                    @csrf
                                    <button class="dropdown-item" type="submit">Logout</button>
                                </form>
                            </li>
                        </ul>
                    </li>
                @endguest
            </ul>
        </div>
    </div>
</nav>

<main class="container mb-5">
    @if(session('status'))
        <div class="alert alert-success">{{ session('status') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif
    @yield('content')
</main>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
