<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="apple-touch-icon" sizes="76x76" href="/img/apple-icon.png">
    <link rel="icon" type="image/png" href="/img/favicon.png">
    <title>
        VMS By Bugagency
    </title>
    <!--     Fonts and icons     -->
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet" />
    <!-- Nucleo Icons -->
    <link href="./assets/css/nucleo-icons.css" rel="stylesheet" />
    <link href="./assets/css/nucleo-svg.css" rel="stylesheet" />
    <!-- Font Awesome Icons -->
    <script src="https://kit.fontawesome.com/42d5adcbca.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.7.1/dist/jquery.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
    <link href="{{ asset('../resources/css/sass/main.css') }}" rel="stylesheet" />
    <link href="http://127.0.0.1:8000/assets/css/sass/main.css" rel="stylesheet" />
    <script src='https://cdn.jsdelivr.net/npm/fullcalendar/index.global.min.js'></script>

</head>

<body class="{{ $class ?? '' }}">

@guest
    @yield('content')
@endguest

@auth
    <div class="hubers-layout">
        <div class="hubers-navbar">
            <div>
                <div class="hubers-navbar-logo">
                    <a href="https://bugagency.tech" target="_blank">
                        <img style="height: 40px;text-align: center;" src="https://bugagency.tech/wp-content/uploads/assets/logo_main.png" alt="" />
                        VMS
                    </a>
                </div>
                <div class="hubers-navbar-links">
                    <ul>
                        <li><a href="{{route('dashboard.index')}}">Dashboard</a></li>
                        <li><a href="{{route('reservations.index')}}">Rezervimet</a></li>
                        <li><a href="{{route('payments.index')}}">Pagesat</a></li>
                        <li><a href="{{route('menus.index')}}">Menut</a></li>
                        <li><a href="{{route('clients.index')}}">Klientat</a></li>
                        <li><a href="{{route('users.index')}}">Perdoruesit</a></li>
                        <li><a href="{{route('reports.index')}}">Raportet</a></li>
                        <li><a href="{{route('venues.index')}}">Sallat</a></li>
                        <li><a href="{{route('logs.index')}}">Aktiviteti</a></li>
                        <li><a href="{{route('profile')}}">Profili</a></li>
                    </ul>
                </div>
            </div>
            <div class="hubers-navbar-logout">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-log-out"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/></svg>
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit">
                        Log Out
                    </button>
                </form>


            </div>
        </div>
        <div class="hubers-body-content">
            <header class="hubers-page-header">
                <h5 class="mb-0">@yield('header')</h5>
                @yield('header-actions')
            </header>

                @yield('content')

        </div>
    </div>

@endauth

<!--   Core JS Files   -->
<script src="assets/js/core/popper.min.js"></script>
<script src="assets/js/plugins/perfect-scrollbar.min.js"></script>
<script src="assets/js/plugins/smooth-scrollbar.min.js"></script>
@stack('js');
</body>

</html>
