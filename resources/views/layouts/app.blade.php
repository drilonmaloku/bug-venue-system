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
        <!-- Font Awesome Icons -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"  crossorigin="anonymous" referrerpolicy="no-referrer" />

        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
        <script src="https://code.jquery.com/jquery-3.3.1.min.js" integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8=" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
        <script src='https://cdn.jsdelivr.net/npm/fullcalendar/index.global.min.js'></script>
        <script src="assets/js/core/scripts.min.js"></script>
        <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.10/css/select2.min.css" rel="stylesheet" />
        <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.10/js/select2.min.js"></script>

        <script src="{{ asset('/assets/js/scripts.js') }}"></script>
        <link href="{{ asset('/assets/css/sass/main.css') }}" rel="stylesheet" />
        <link href="{{ asset('/assets/css/v2.css') }}" rel="stylesheet" />


    </head>

    <body class="{{ $class ?? '' }}">

        @guest
            @yield('content')
        @endguest

        @auth
            <div class="vms-layout">
                @include('layouts.navbar')
                <div class="hubers-body-content">
                    <header class="hubers-page-header">
                        <h5 class="mb-0">@yield('header')</h5>
                        @yield('header-actions')

                    </header>
                    @yield('content')
                </div>
            </div>
            <div class="languages_option">
                <a class="<?php if(app()->getLocale() == 'sq') { echo 'active';} ?>" href="{{ url('locale/sq') }}" >SQ</a>
                <a class="<?php if(app()->getLocale() == 'en') { echo 'active';} ?>" href="{{ url('locale/en') }}" >EN</a>
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
