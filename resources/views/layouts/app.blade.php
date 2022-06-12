
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('page')</title>

    <link rel="shortcut icon" href="{{ asset('imgs/tns-icon.svg') }}" type="image/x-icon">

    <!-- Styles -->
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <link rel="stylesheet" href="{{ asset('css/custom.css') }}">

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.6.0.js" crossorigin="anonymous"
        integrity="sha256-H+K7U5CnXl1h5ywQfKtSj8PCmoN9aaq30gDh27Xc0jk="></script>

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js"
        integrity="sha384-7+zCNj/IqJ95wo16oMtfsKbZ9ccEh31eOz1HGyDuCQ6wgnyJNSYdrPa03rtR1zdB"
        crossorigin="anonymous"></script>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.min.js"
        integrity="sha384-QJHtvGhmr9XOIpI6YVutG+2QOK9T+ZnN4kzFN1RtK3zEFEIsxhlmWl5/YESvpZ13"
        crossorigin="anonymous"></script>

    <script src="{{ asset('js/app.js') }}" defer></script>
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <!-- Charting library -->
    <script src="https://unpkg.com/chart.js@^2.9.3/dist/Chart.min.js"></script>
    
    <!-- Chartisan -->
    <script src="https://unpkg.com/@chartisan/chartjs@^2.1.0/dist/chartisan_chartjs.umd.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.7.1/chart.min.js"
        integrity="sha512-QSkVNOCYLtj73J4hbmVoOV6KVZuMluZlioC+trLpewV8qMjsWqlIQvkn1KGX2StWvPMdWGBqim1xlC8krl1EKQ=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>

    <script src="{{ asset('js/custom.js') }}"></script>

    @livewireStyles
</head>
<body>
    <div class="d-flex" id="body-divider">
        <div id="sidebar" class="shadow-sm">
            @include('plugins.sidebar')
        </div>
        <div class="flex-grow-1" id="context-body">
            <header class="container-fluid" style="height: 90px;">
                <div class="row">
                    <div class="col-sm d-flex align-items-end fs-lg fg-marine-light x-mobile">
                        <strong>
                            @php
                                $path   =   explode("/", request()->path());
                                echo ucfirst($path[0]);
                            @endphp
                        </strong>
                    </div>
                    <div class="col-auto" id="profile">
                        @php
                            $user   =   auth()->user();
                        @endphp
                        <div class="dropdown">
                            <button class="btn btn-lg p-2 shadow dropdown-toggle btn-cheese shadow" href="#" id="profile-link" role="button"
                                data-bs-toggle="dropdown" aria-expanded="false" data-bs-auto-close="true">
                                <b class="">
                                    {{ Str::ucfirst($user->first_name[0] . $user->last_name[0]) }}
                                </b>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end shadow pb-0" aria-labelledby="profile-link" id="profile-link-body">
                                <li class="right">
                                    <span class="dropdown-item-text">
                                        <b>{{ Str::ucfirst($user->first_name . ' ' . $user->last_name) }}</b><br>
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
                                <li class="right">
                                    <span class="dropdown-item-text">
                                        <small class="text-muted">
                                            v{{ config('app.ver') }}
                                        </small>
                                    </span>
                                </li>
                                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                    @csrf
                                </form>
                            </ul>
                        </div>
                        
                        <script>
                            var popoverTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="popover"]'))
                            var popoverList = popoverTriggerList.map(function (popoverTriggerEl) {
                                return new bootstrap.Popover(popoverTriggerEl)
                            });
                            var dropdownElementList = [].slice.call(document.querySelectorAll('.dropdown-toggle'));
                            var dropdownList = dropdownElementList.map(function (dropdownToggleEl) {
                                return new bootstrap.Dropdown(dropdownToggleEl);
                            });

                            $(document).ready(function() {
                                $("body").on("click", "#profile-link", function() {
                                    $("#profile-link-body").toggle("show");
                                });
                            });
                        </script>
                    </div>
                </div>
            </header>
            <main class="">
                @yield('content')
            </main>
        </div>
    </div>
    
    <script>
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
          return new bootstrap.Tooltip(tooltipTriggerEl)
        });
    </script>

    @livewireScripts
</body>
</html>