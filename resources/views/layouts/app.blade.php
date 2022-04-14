
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Yortik') }} - @yield('page')</title>

    <link rel="shortcut icon" href="{{ asset('imgs/yortik-icon.svg') }}" type="image/x-icon">

    <!-- Styles -->
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <link rel="stylesheet" href="{{ asset('css/custom.css') }}">

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>
</head>
<body>
    <div class="d-flex">
        <div id="sidebar" class="shadow">
            @include('plugins.sidebar')
        </div>
        <div class="flex-grow-1 p-3">
            <header class="container-fluid">
                <div class="row">
                    <div class="col-sm d-flex align-items-end">
                        {{ Str::ucfirst(request()->path()) }}
                    </div>
                    <div class="col-sm d-flex justify-content-end align-items-center">
                        <div class="search-bar">
                            <input type="text" class="form-control" placeholder="Search...">
                            <i class="bi bi-search search-icon"></i>
                        </div>
                    </div>
                    <div class="col-auto">
                        @php
                            $user   =   auth()->user();
                        @endphp
                        <div class="dropdown">
                            <button class="btn btn-lg p-2 shadow dropdown-toggle btn-rose" href="#" id="profile-link" role="button"
                                data-bs-toggle="dropdown" aria-expanded="false">
                                <b class="pr-2">
                                    {{ Str::ucfirst($user->first_name[0] . $user->last_name[0]) }}
                                </b>
                            </button>
    
                            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="profile-link">
                                <li class="right">
                                    <span class="dropdown-item-text">
                                        <b>{{ Str::ucfirst($user->first_name . ' ' . $user->last_name) }}</b>
                                        <span class="text-muted">
                                            {{ Str::ucfirst($user->role) }}
                                        </span>
                                    </span>
                                </li>
                                <li class="dropdown-divider"></li>
                                <li class="right">
                                    <a class="dropdown-item link-danger" href="{{ route('logout') }}"
                                        onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                        {{ __('Logout') }}
                                    </a>
                                </li>

                                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                    @csrf
                                </form>
                            </ul>
                        </div>
                    </div>
                </div>
            </header>
            <main class="pt-4">
                @yield('content')
            </main>
        </div>
    </div>
</body>
</html>

{{-- <!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
</head>

<body>
    <div id="app">
        <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
            <div class="container-fluid">
                <a class="navbar-brand" href="{{ url('/') }}">
                    {{ config('app.name', 'Laravel') }}
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                    data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
                    aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar -->
                    <ul class="navbar-nav me-auto">
                    </ul>

                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ms-auto">
                        <!-- Authentication Links -->
                        @guest
                            @if (Route::has('login'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                                </li>
                            @endif

                            @if (Route::has('register'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                                </li>
                            @endif
                        @else
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button"
                                    data-bs-toggle="dropdown" aria-expanded="false">
                                    {{ Auth::user()->name }}
                                </a>

                                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                                    <li>
                                        <a class="dropdown-item" href="{{ route('logout') }}"
                                            onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                            {{ __('Logout') }}
                                        </a>
                                    </li>

                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                        @csrf
                                    </form>
                                </ul>
                            </li>
                        @endguest
                    </ul>
                </div>
            </div>
        </nav>

        <main class="py-4">
            @yield('content')
        </main>
    </div>
</body>

</html> --}}
