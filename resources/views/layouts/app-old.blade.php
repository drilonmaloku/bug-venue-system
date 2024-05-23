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
    <link href="assets/css/nucleo-svg.css" rel="stylesheet" />
    <!-- CSS Files -->
    <link id="pagestyle" href="/assets/css/argon-dashboard.css" rel="stylesheet" />
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
                        <Link :href="route('staff.index')">
                        <img style="height: 70px;text-align: center;" src="https://hubers-events.com/logo.png" alt="" />
                        </Link>
                    </div>
                    <div class="hubers-navbar-links">
                        <ul>
                            <li><a href="#">Dashboard</a></li>
                        </ul>
                    </div>
                </div>
                <div class="hubers-navbar-logout">
                    <VueFeather type="log-out" class="mr-2" />
                    <form @submit.prevent="logout">
                        <button type="submit">
                            Log Out
                        </button>
                    </form>


                </div>
            </div>
            <div class="hubers-body-content">
                <header class="hubers-page-header">
                    @yield('header')
                </header>
                <main>
                    @yield('content')
                </main>
            </div>
        </div>

    @endauth

    <!--   Core JS Files   -->
    <script src="assets/js/core/popper.min.js"></script>
    <script src="assets/js/core/bootstrap.min.js"></script>
    <script src="assets/js/plugins/perfect-scrollbar.min.js"></script>
    <script src="assets/js/plugins/smooth-scrollbar.min.js"></script>
    <script>
        var win = navigator.platform.indexOf('Win') > -1;
        if (win && document.querySelector('#sidenav-scrollbar')) {
            var options = {
                damping: '0.5'
            }
            Scrollbar.init(document.querySelector('#sidenav-scrollbar'), options);
        }
    </script>
    <!-- Github buttons -->
    <script async defer src="https://buttons.github.io/buttons.js"></script>
    <!-- Control Center for Soft Dashboard: parallax effects, scripts for the example pages etc -->
    <script src="assets/js/argon-dashboard.js"></script>
    @stack('js');
</body>

</html>
