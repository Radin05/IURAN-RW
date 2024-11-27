<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    @php
        use Carbon\Carbon;
    @endphp

    <style>
        .tes {
            display: flex;
            justify-content: space-between;
            padding: 0;
            list-style-type: none;
        }

        .tes-item {
            margin: 0 10px;
            /* Opsional: memberikan jarak antar tombol */
        }
    </style>
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
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
                                <ul class="tes justify-content-between">
                                    <li class="tes-item">
                                        <a class="tes-link" href="{{ route('superadmin.dashboard') }}">Dashboard</a>
                                    </li>
                                    <li class="tes-item">
                                        <a class="tes-link" href="{{ route('superadmin.admins.index') }}">Akun Admin</a>
                                    </li>
                                </ul>
                            @elseif(Auth::user()->role === 'admin')
                                <ul class="tes justify-content-between">
                                    <li class="tes-item">
                                        <a class="tes-link" href="{{ route('admin.dashboard') }}">Dashboard</a>
                                    </li>
                                    <li class="tes-item">
                                        <a class="tes-link" href="{{ route('admin.users.index', ['year' => Carbon::now('Asia/Jakarta')->year, 'month' => Carbon::now('Asia/Jakarta')->month]) }}">Keluarga</a>
                                    </li>
                                </ul>
                            @elseif(Auth::user()->role === 'user')
                                <li class="nav-item">
                                    {{-- <a class="nav-link" href="{{ route('user.cash.total') }}">View Total Cash</a> --}}
                                </li>
                            @endif
                        </center>

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
    </nav>

    @auth
        @if (Auth::user()->role === 'admin' && session()->has('impersonate'))
            <div class="nav-item text-center">
                <div class="alert alert-warning">
                    Anda sedang dalam mode impersonasi.
                    <a href="{{ route('stop.impersonating') }}" class="btn btn-sm btn-danger">Keluar Impersonasi</a>
                </div>
            </div>
        @endif
    @endauth

    <div class="container mt-4">
        @yield('content')
    </div>
</body>

</html>
