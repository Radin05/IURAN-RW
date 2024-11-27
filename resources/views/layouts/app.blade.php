<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    {{-- <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">MyApp</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav">
                    @auth
                        <center>
                            @if (Auth::user()->role === 'superadmin')
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('superadmin.admins.index') }}">Akun Admin</a>
                                </li>
                            @elseif(Auth::user()->role === 'admin')
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('manage.users') }}">Manage Users</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('user.cashes') }}">Manage Cash</a>
                                </li>
                            @elseif(Auth::user()->role === 'user')
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('user.cash.total') }}">View Total Cash</a>
                                </li>
                            @endif
                        </center>
                </div>
                <div class="nav-item">
                    <a class="nav-link" href="{{ route('logout') }}"
                        onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Logout</a>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                        @csrf
                    </form>
                </div>
            @endauth
            </ul>
        </div>
        </div>
    </nav> --}}
    
    <div class="container mt-4">
        @yield('content')
    </div>
</body>

</html>
