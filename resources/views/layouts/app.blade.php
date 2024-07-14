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
    <script src='https://cdn.jsdelivr.net/npm/fullcalendar/index.global.min.js'></script>
    <script src="assets/js/core/scripts.min.js"></script>

    <script src="{{ asset('/assets/js/scripts.js') }}"></script>
    <script src="{{ asset('../resources/js/app.js') }}"></script>
    <link href="{{ asset('/assets/css/sass/main.css') }}" rel="stylesheet" />
    
</head>

<body class="{{ $class ?? '' }}">

@guest
    @yield('content')
@endguest

@auth
    <div class="vms-layout">
        <div class="vms-navbar">
            <div>
                <div class="hubers-navbar-logo-items">
                    <div class="hubers-navbar-logo">
                        <a href="/">
                            <img style="height: 40px;text-align: center;" src="https://bugagency.tech/wp-content/uploads/assets/logo_main.png" alt="" />
                            VMS
                        </a>
                    </div>
                    <div class="hubers-navbar-hamburger-notifications">
                        <div class="hamburger-menu">
                            <span></span>
                            <span></span>
                            <span></span>
                        </div>
                    </div>
                </div>

                <div class="hubers-navbar-links">
                    <ul>
                        <li><a class="{{ Request::is(['dashboard']) ? 'active' : '' }}" href="{{route('dashboard.index')}}">Dashboard</a></li>
                        <li><a class="{{ Request::is(['reservations','reservations/*']) ? 'active' : '' }}" href="{{route('reservations.index')}}">Rezervimet</a></li>
                        <li><a class="{{ Request::is(['expenses','expenses/*']) ? 'active' : '' }}" href="{{route('expenses.index')}}">Shpenzimet</a></li>
                        <li><a class="{{ Request::is(['payments','payments/*']) ? 'active' : '' }}" href="{{route('payments.index')}}">Pagesat</a></li>
                        <li><a class="{{ Request::is(['menus','menus/*']) ? 'active' : '' }}" href="{{route('menus.index')}}">Menut</a></li>
                        <li><a class="{{ Request::is(['clients','clients/*']) ? 'active' : '' }}" href="{{route('clients.index')}}">Klientat</a></li>
                        <li><a class="{{ Request::is(['users','users/*']) ? 'active' : '' }}" href="{{route('users.index')}}">Përdoruesit</a></li>
                        <li><a class="{{ Request::is(['venues','venues/*']) ? 'active' : '' }}" href="{{route('venues.index')}}">Sallat</a></li>
                        @role('super-admin') 
                            <li><a class="{{ Request::is(['reports','reports-generated']) ? 'active' : '' }}" href="{{route('reports.index')}}">Raportet</a></li>
                            <li><a class="{{ Request::is(['logs','logs/*']) ? 'active' : '' }}" href="{{route('logs.index')}}">Aktiviteti</a></li>
                        @endrole

                        @role('system-admin')
                            <li><a class="{{ Request::is(['locations','locations/*']) ? 'active' : '' }}" href="{{route('locations.index')}}">Locations</a></li>
                        @endrole
                        <li><a class="{{ Request::is(['supports-tickets','supports-tickets/*']) ? 'active' : '' }}" href="{{route('support-tickets.index')}}">Support Tickets</a></li>

                        <li><a class="{{ Request::is(['profile']) ? 'active' : '' }}" href="{{route('profile')}}">Profili</a></li>
                    </ul>
                    <div class="hubers-navbar-logout">
                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-log-out"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/></svg>
                            <button type="submit">
                                Log Out
                            </button>
                        </form>


                    </div>
                </div>
            </div>
            <div class="hubers-navbar-logout">
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-log-out"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/></svg>
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
@stack('js')
@include('sweetalert::alert')
</body>

</html>
