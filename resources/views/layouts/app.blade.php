<!DOCTYPE html>
<html lang="{{ config('app.locale') }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">

    <!-- Scripts -->
    <script>
        window.Laravel = {!! json_encode([
            'csrfToken' => csrf_token(),
        ]) !!};
    </script>
    <style>
        .level {
            display: flex;
            align-items: center;
        }
        .flex {
            flex: 1;
        }
        .mr-10 {
            margin-right: 10px;
        }
        [v-cloak] {
            display:  none;
        }
        .new-notification {
            color: red;
        }

        .best-reply {
            color: rgba(0, 0, 0, .2);
        }

        .best-reply:hover,
        .best-reply:focus,
        .best-reply__active,
        .best-reply__active:hover,
        .best-reply__active:focus {
            color: #2ab27b;
        }
    </style>

    @yield('head')
</head>
<body>
    <div id="app">
        <nav class="navbar navbar-default navbar-static-top">
            <div class="container">
                <div class="navbar-header">

                    <!-- Collapsed Hamburger -->
                    <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#app-navbar-collapse">
                        <span class="sr-only">Toggle Navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>

                    <!-- Branding Image -->
                    <a class="navbar-brand" href="{{ url('/') }}">
                        {{ config('app.name', 'Laravel Forum') }}
                    </a>
                </div>

                <div class="collapse navbar-collapse" id="app-navbar-collapse">
                    <!-- Left Side Of Navbar -->
                    <ul class="nav navbar-nav">
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown"
                               role="button" aria-haspopup="true" aria-expanded="false">
                                Channels <span class="caret"></span>
                            </a>

                            <ul class="dropdown-menu">
                                @foreach ($channels as $channel)
                                    <li>
                                        <a href="{{ route('channels.show', $channel->id) }}">
                                            {{ $channel->name }}
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                        </li>
                        <li>
                            <a href="{{ route('all.threads.index') }}?popularity=1">
                                Popular threads
                            </a>
                        </li>
                    </ul>

                    <!-- Right Side Of Navbar -->
                    <ul class="nav navbar-nav navbar-right">
                        <!-- Authentication Links -->
                        @if (Auth::guest())
                            <li><a href="{{ route('login') }}">Login</a></li>
                            <li><a href="{{ route('register') }}">Register</a></li>
                        @else
                            <user-notification
                                route="{{ route('notification.index') }}"
                                read-route="{{ route('notification.read') }}"
                                :new-notifications="{{ auth()->user()->unreadNotifications->count() }}"
                            >
                            </user-notification>

                            <li class="dropdown">
                                <a href="#"
                                    class="dropdown-toggle"
                                    data-toggle="dropdown"
                                    role="button"
                                    aria-expanded="false"
                                >
                                    {{ Auth::user()->name }} <span class="caret"></span>
                                </a>

                                <ul class="dropdown-menu" role="menu">
                                    <li>
                                        <a href="{{ route('profile.show', auth()->user()) }}">
                                            Profile
                                        </a>
                                        <a href="{{ route('logout') }}"
                                            onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                            Logout
                                        </a>

                                        <form id="logout-form"
                                            action="{{ route('logout') }}"
                                            method="POST"
                                            style="display: none;"
                                        >
                                            {{ csrf_field() }}
                                        </form>
                                    </li>
                                </ul>
                            </li>
                        @endif
                    </ul>
                </div>
            </div>
        </nav>

        @yield('content')

        <flash message="{{ session('flash') }}"></flash>
    </div>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}"></script>
    @yield('scripts')
</body>
</html>
