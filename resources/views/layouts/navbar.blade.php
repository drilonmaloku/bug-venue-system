<div class="vms-navbar">
    <div>
        <div class="hubers-navbar-logo-items">
            <div class="hubers-navbar-logo" style="display: flex; justify-content:space-between">
                <a href="/">
                    <img style="height: 40px;text-align: center;" src="https://bugagency.tech/wp-content/uploads/assets/logo_main.png" alt="" />
                    VMS
                </a>
            </div>

            <div class="hubers-navbar-hamburger-notifications">
                       <span id="notificationButton">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-bell"><path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"/><path d="M13.73 21a2 2 0 0 1-3.46 0"/></svg>
                            <span id="notificationBadge">
                                0
                            </span>
                        </span>
                @include('pages.notifications.notifications-popup')
                <div class="hamburger-menu">
                    <span></span>
                    <span></span>
                    <span></span>
                </div>
            </div>
        </div>

        <div class="hubers-navbar-links">
             @role('kitchen')
                <ul>
                    <li>
                        <a class="{{ Request::is(['kitchen','kitchen/*']) ? 'active' : '' }}" href="{{route('kitchen.index')}}">
                            <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" fill="#000000" width="24" height="24" viewBox="0 -5.59 122.88 122.88" version="1.1" id="Layer_1" style="enable-background:new 0 0 122.88 111.69" xml:space="preserve">
                                <g>
                                <path d="M25.66,19.3c-0.4,0.4-0.93,0.6-1.45,0.6c-0.53,0-1.05-0.2-1.45-0.6c-0.4-0.4-0.6-0.93-0.6-1.45c0-0.53,0.2-1.05,0.6-1.45 c0.73-0.73,0.94-1.39,0.86-2.02c-0.08-0.69-0.47-1.43-0.9-2.22c-0.74-1.39-1.54-2.87-1.79-4.67c-0.26-1.82,0.04-3.91,1.51-6.45 L22.45,1c0.28-0.47,0.73-0.79,1.22-0.93c0.5-0.14,1.06-0.08,1.55,0.2c0.49,0.28,0.82,0.74,0.96,1.24c0.14,0.5,0.08,1.06-0.2,1.55 c-0.9,1.57-1.07,2.87-0.9,4.01c0.18,1.15,0.73,2.18,1.25,3.14c0.77,1.43,1.48,2.76,1.57,4.23C28,15.95,27.45,17.51,25.66,19.3 L25.66,19.3z M14.09,42.76h94.51c0.38,0,0.75,0.08,1.09,0.22c0.33,0.14,0.62,0.33,0.88,0.57c0.02,0.01,0.04,0.03,0.06,0.05 c0.52,0.52,0.84,1.23,0.84,2.02v6.37h9.36c0.57,0,1.08,0.23,1.45,0.6l0,0c0.37,0.37,0.6,0.89,0.6,1.45c0,0.57-0.23,1.08-0.6,1.45 l-0.03,0.03c-0.37,0.35-0.87,0.57-1.42,0.57h-9.36v42.1c0,1.82-0.37,3.56-1.03,5.15c-0.69,1.65-1.69,3.14-2.93,4.38 c-1.24,1.24-2.73,2.25-4.38,2.93c-1.59,0.66-3.33,1.03-5.15,1.03H24.73c-1.82,0-3.56-0.37-5.15-1.03 c-1.65-0.69-3.14-1.69-4.38-2.94c-1.24-1.24-2.25-2.73-2.94-4.38c-0.66-1.59-1.03-3.33-1.03-5.15V56.11H2.06 c-0.57,0-1.08-0.23-1.45-0.6C0.23,55.13,0,54.62,0,54.05c0-0.57,0.23-1.08,0.6-1.45l0,0c0.37-0.37,0.89-0.6,1.45-0.6h9.18v-6.37 c0-0.38,0.08-0.75,0.22-1.09c0.15-0.35,0.36-0.67,0.62-0.93C12.59,43.09,13.31,42.76,14.09,42.76L14.09,42.76z M107.35,46.88h-92 v51.32c0,1.27,0.25,2.47,0.71,3.58c0.48,1.15,1.18,2.18,2.05,3.05c0.87,0.87,1.9,1.57,3.05,2.05c1.1,0.46,2.31,0.71,3.58,0.71 h73.25c1.26,0,2.47-0.25,3.57-0.71c1.14-0.48,2.18-1.18,3.05-2.04c0.87-0.87,1.57-1.9,2.04-3.05c0.46-1.1,0.71-2.31,0.71-3.57 V46.88L107.35,46.88z M20.06,53.85c0-0.57,0.23-1.08,0.6-1.45c0.37-0.37,0.89-0.6,1.45-0.6c0.57,0,1.08,0.23,1.45,0.6 c0.37,0.37,0.6,0.89,0.6,1.45v27.09c0,0.57-0.23,1.08-0.6,1.45c-0.37,0.37-0.89,0.6-1.45,0.6c-0.57,0-1.08-0.23-1.45-0.6 l-0.03-0.03c-0.35-0.37-0.57-0.87-0.57-1.42V53.85L20.06,53.85z M40.3,25.44c-3.54,0.51-6.72,1.16-9.54,1.93 c-2.83,0.78-5.32,1.69-7.46,2.73c-1.5,0.73-2.83,1.52-3.98,2.37c-0.81,0.59-1.53,1.22-2.17,1.87h88.43 c-0.59-0.62-1.27-1.21-2.03-1.77c-1.11-0.81-2.4-1.56-3.86-2.24c-2.08-0.98-4.5-1.82-7.21-2.56c-2.73-0.74-5.75-1.35-9.01-1.86 c-1.48-0.23-2.98-0.44-4.49-0.63l-0.02,0c-1.49-0.19-2.99-0.35-4.51-0.5c-1.49-0.14-3-0.27-4.54-0.37 c-1.52-0.1-3.03-0.18-4.53-0.24c-0.57-0.02-1.07-0.27-1.43-0.65c-0.36-0.38-0.57-0.9-0.55-1.47l0-0.01 c0.01-0.33,0.1-0.64,0.25-0.91c0.15-0.28,0.37-0.51,0.63-0.7c0.32-0.24,0.62-0.51,0.88-0.82c0.26-0.3,0.48-0.64,0.66-0.99 c0.18-0.35,0.32-0.72,0.41-1.13c0.09-0.39,0.14-0.79,0.14-1.21c0-0.7-0.14-1.36-0.39-1.96c-0.26-0.62-0.64-1.19-1.11-1.66 c-0.47-0.47-1.04-0.86-1.66-1.11c-0.6-0.25-1.26-0.39-1.96-0.39c-0.7,0-1.36,0.14-1.96,0.39c-0.63,0.26-1.19,0.64-1.66,1.12 c-0.47,0.47-0.86,1.04-1.11,1.66l0,0c-0.25,0.6-0.39,1.26-0.39,1.96c0,0.41,0.05,0.81,0.13,1.19c0.09,0.39,0.23,0.77,0.4,1.11 c0.18,0.35,0.4,0.69,0.65,0.99c0.26,0.31,0.55,0.59,0.87,0.82c0.45,0.34,0.73,0.83,0.8,1.35c0.07,0.52-0.05,1.07-0.39,1.52 c-0.2,0.26-0.44,0.46-0.71,0.6c-0.26,0.13-0.55,0.21-0.84,0.22l-0.06,0l-0.04,0c-1.37,0.03-2.77,0.08-4.18,0.15 c-1.39,0.07-2.8,0.16-4.25,0.28c-1.4,0.11-2.79,0.24-4.15,0.4C42.99,25.07,41.64,25.24,40.3,25.44L40.3,25.44z M21.51,26.4 c2.34-1.13,5.03-2.12,8.08-2.96c3.03-0.84,6.41-1.53,10.13-2.07c1.39-0.2,2.79-0.38,4.2-0.54c1.43-0.16,2.84-0.3,4.26-0.41 c0.87-0.07,1.78-0.13,2.71-0.19c0.64-0.04,1.3-0.07,1.96-0.1c-0.25-0.55-0.45-1.13-0.59-1.72c-0.16-0.68-0.25-1.39-0.25-2.12 c0-1.25,0.25-2.44,0.7-3.53c0.47-1.13,1.16-2.15,2.01-3c0.85-0.85,1.87-1.54,3-2c1.09-0.45,2.28-0.7,3.53-0.7s2.44,0.25,3.53,0.7 c1.13,0.47,2.15,1.16,3,2c0.85,0.85,1.54,1.87,2.01,3c0.45,1.09,0.7,2.28,0.7,3.53c0,0.74-0.09,1.46-0.26,2.16 c-0.15,0.63-0.37,1.24-0.65,1.82c0.76,0.05,1.5,0.1,2.22,0.16c0.97,0.07,1.98,0.16,3.02,0.26c1.55,0.15,3.09,0.32,4.64,0.52 c1.55,0.19,3.09,0.41,4.62,0.65c3.47,0.54,6.66,1.2,9.54,1.98c2.9,0.79,5.51,1.71,7.79,2.78c2.38,1.11,4.41,2.39,6.05,3.85 c1.66,1.47,2.92,3.12,3.77,4.97c0.07,0.14,0.13,0.29,0.17,0.46c0.04,0.16,0.06,0.32,0.06,0.49c0,0.57-0.23,1.08-0.6,1.45 l-0.03,0.03c-0.37,0.35-0.87,0.57-1.42,0.57H13.09c-0.04,0-0.07,0-0.1-0.01c-0.13-0.01-0.27-0.03-0.39-0.06 c-0.14-0.04-0.28-0.09-0.42-0.16c-0.03-0.01-0.06-0.03-0.09-0.05c-0.47-0.27-0.8-0.7-0.95-1.19c-0.15-0.49-0.12-1.03,0.13-1.51 c0.01-0.03,0.03-0.06,0.05-0.09c0.98-1.81,2.31-3.47,4.01-4.97C17.02,28.92,19.08,27.58,21.51,26.4L21.51,26.4z M81.61,16.39 c0.4,0.4,0.6,0.93,0.6,1.45c0,0.53-0.2,1.05-0.6,1.45c-0.4,0.4-0.93,0.6-1.45,0.6c-0.53,0-1.05-0.2-1.45-0.6 c-1.79-1.79-2.34-3.35-2.25-4.85c0.09-1.47,0.81-2.8,1.57-4.23c1-1.88,2.15-4.02,0.35-7.15l-0.02-0.04 c-0.27-0.48-0.32-1.03-0.18-1.52c0.14-0.51,0.47-0.96,0.96-1.24c0.49-0.28,1.05-0.34,1.55-0.2c0.51,0.14,0.96,0.47,1.24,0.96 c1.47,2.54,1.77,4.63,1.51,6.44c-0.26,1.8-1.05,3.29-1.79,4.67l0,0C80.85,13.65,80.16,14.94,81.61,16.39L81.61,16.39z M97.11,16.39 c0.4,0.4,0.6,0.93,0.6,1.45c0,0.53-0.2,1.05-0.6,1.45c-0.4,0.4-0.93,0.6-1.45,0.6c-0.53,0-1.05-0.2-1.45-0.6 c-1.79-1.79-2.34-3.35-2.25-4.85c0.09-1.47,0.81-2.8,1.57-4.23c0.51-0.96,1.07-1.99,1.25-3.14c0.18-1.14,0.01-2.44-0.9-4.01 c-0.28-0.49-0.34-1.05-0.2-1.55c0.14-0.51,0.47-0.96,0.96-1.24c0.49-0.28,1.05-0.34,1.55-0.2c0.5,0.14,0.96,0.47,1.24,0.96 c1.47,2.54,1.77,4.63,1.51,6.44c-0.26,1.8-1.05,3.29-1.79,4.67c-0.42,0.79-0.82,1.53-0.9,2.22C96.17,15.01,96.38,15.66,97.11,16.39 L97.11,16.39z M41.16,19.3c-0.4,0.4-0.93,0.6-1.45,0.6c-0.53,0-1.05-0.2-1.45-0.6c-0.4-0.4-0.6-0.93-0.6-1.45 c0-0.53,0.2-1.05,0.6-1.45c1.46-1.46,0.77-2.75-0.04-4.25c-0.74-1.39-1.54-2.87-1.79-4.68c-0.26-1.82,0.04-3.91,1.51-6.45 c0.28-0.49,0.74-0.82,1.25-0.95c0.51-0.13,1.06-0.08,1.55,0.21c0.49,0.28,0.82,0.74,0.95,1.25c0.13,0.51,0.08,1.06-0.21,1.55 c-1.8,3.12-0.65,5.27,0.35,7.14l0,0c0.77,1.43,1.48,2.76,1.57,4.23C43.5,15.95,42.94,17.51,41.16,19.3L41.16,19.3z"/>
                                </g>
                                </svg>
                            {{ 'Kitchen' }}
                        </a>
                    </li>
                @endrole
                @unlessrole('kitchen')
                    <li>
                        <a class="{{ Request::is(['dashboard']) ? 'active' : '' }}" href="{{route('dashboard.index')}}">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-monitor"><rect x="2" y="3" width="20" height="14" rx="2" ry="2"/><line x1="8" y1="21" x2="16" y2="21"/><line x1="12" y1="17" x2="12" y2="21"/></svg>
                                {{__('dashboard.dashboard')}}
                        </a>
                    </li>
                    <li class="has-submenu">
                        <a class="{{ Request::is(['reservations','reservations/*']) ? 'active' : '' }}" href="{{route('reservations.index')}}">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-calendar"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                            {{__('dashboard.reservations')}}
                        </a>
                        <span class="submenu-opener submenu-opener-icon">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevron-down"><polyline points="6 9 12 15 18 9"/></svg>
                                </span>
                        <ul class="hubers-submenu">
                            <li>
                                <a class="{{ Request::is(['clients','clients/*']) ? 'active' : '' }}" href="{{route('clients.index')}}">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-users"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                                    {{__('dashboard.clients')}}
                                </a>
                            </li>
                            <li>
                                <a class="{{ Request::is(['venues','venues/*']) ? 'active' : '' }}" href="{{route('venues.index')}}">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-home"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
                                    {{__('dashboard.venues')}}
                                </a>
                            </li>
                            <li>
                                <a class="{{ Request::is(['menus','menus/*']) ? 'active' : '' }}" href="{{route('menus.index')}}">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-menu"><line x1="3" y1="12" x2="21" y2="12"/><line x1="3" y1="6" x2="21" y2="6"/><line x1="3" y1="18" x2="21" y2="18"/></svg>
                                    {{__('dashboard.menus')}}
                                </a>
                            </li>
                            <li>
                                <a class="{{ Request::is(['contract',]) ? 'active' : '' }}" href="{{route('location-settings.contract')}}">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-file-text"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/><polyline points="10 9 9 9 8 9"/></svg>
                                    {{__('dashboard.contract')}}
                                </a>
                            </li>
                            <li>
                                <a class="{{ Request::is(['decors',]) ? 'active' : '' }}" href="{{route('decors.index')}}">
                                    <svg xmlns="http://www.w3.org/2000/svg" version="1.0" width="24" height="24" viewBox="0 0 1242.000000 1280.000000" preserveAspectRatio="xMidYMid meet">
                                        <g transform="translate(0.000000,1280.000000) scale(0.100000,-0.100000)" fill="#000000" stroke="none">
                                            <path d="M4800 12797 c0 -2 -7 -54 -15 -117 -8 -62 -14 -114 -12 -115 1 -1 45 -8 96 -14 335 -42 805 -161 1158 -292 625 -233 1223 -579 1728 -999 681 -566 1216 -1268 1560 -2045 272 -614 430 -1255 486 -1965 14 -185 14 -652 0 -840 -75 -964 -355 -1824 -848 -2610 -377 -599 -884 -1128 -1438 -1497 -603 -402 -1246 -641 -1965 -729 -205 -26 -688 -26 -885 -1 -362 46 -703 136 -1011 267 -891 379 -1535 1125 -1718 1990 -43 206 -51 284 -51 525 1 229 6 281 45 470 129 616 530 1185 1066 1517 401 248 856 361 1359 337 93 -4 171 -6 173 -4 9 9 -173 20 -328 19 -195 -1 -305 -12 -490 -50 -265 -55 -569 -179 -790 -324 -631 -415 -1041 -1120 -1078 -1854 -33 -657 171 -1268 602 -1806 81 -101 282 -306 386 -394 731 -620 1779 -905 2828 -771 1258 162 2393 850 3204 1945 584 788 957 1744 1072 2745 41 359 50 855 21 1195 -148 1742 -982 3248 -2369 4278 -306 226 -579 393 -936 571 -579 288 -1163 471 -1807 565 -24 3 -43 5 -43 3z"/>
                                            <path d="M10753 10229 c-295 -16 -439 -74 -656 -264 -124 -108 -242 -254 -330 -407 -15 -26 -27 -50 -27 -55 0 -5 96 -19 213 -31 468 -50 757 -90 757 -103 0 -10 -173 -95 -627 -309 -156 -73 -283 -136 -283 -141 0 -16 94 -87 310 -232 284 -190 327 -211 441 -215 343 -14 949 343 1732 1021 l138 120 -57 51 c-214 192 -414 317 -661 415 -307 121 -606 168 -950 150z"/>
                                            <path d="M8160 9481 c-344 -78 -697 -278 -971 -551 -202 -201 -327 -388 -444 -661 l-33 -76 111 -36 c1002 -323 1775 -423 2080 -271 71 36 128 99 223 250 106 168 229 379 248 426 l15 36 -122 6 c-67 3 -268 10 -447 16 -344 11 -460 18 -460 29 0 10 257 166 562 342 147 85 267 156 267 159 1 12 -221 153 -304 194 -272 133 -528 181 -725 137z"/>
                                            <path d="M3736 7983 c-67 -230 -52 -470 46 -729 70 -187 140 -281 274 -370 74 -49 230 -122 240 -112 2 2 -19 76 -46 163 -59 189 -90 295 -85 295 4 0 113 -114 195 -205 133 -146 152 -166 158 -160 10 9 41 236 41 300 1 96 -46 179 -178 319 -127 133 -333 303 -558 460 -69 48 -82 54 -87 39z"/>
                                            <path d="M4553 6663 c9 -2 25 -2 35 0 9 3 1 5 -18 5 -19 0 -27 -2 -17 -5z"/>
                                            <path d="M4618 6653 c6 -2 18 -2 25 0 6 3 1 5 -13 5 -14 0 -19 -2 -12 -5z"/>
                                            <path d="M4678 6643 c6 -2 18 -2 25 0 6 3 1 5 -13 5 -14 0 -19 -2 -12 -5z"/>
                                            <path d="M4620 6548 c0 -6 23 -109 51 -230 27 -121 49 -227 47 -235 -2 -11 -248 285 -289 349 -14 20 -26 -5 -46 -92 -26 -115 -22 -267 9 -355 63 -180 233 -402 412 -537 81 -61 264 -158 300 -158 18 0 19 8 12 218 -14 417 -47 632 -122 789 -41 85 -57 99 -219 185 -140 75 -155 81 -155 66z"/>
                                            <path d="M990 6049 c-240 -15 -530 -54 -827 -111 l-162 -31 26 -51 c129 -257 372 -500 666 -667 119 -67 306 -147 427 -182 152 -44 398 -22 590 53 126 49 232 110 192 110 -18 0 -618 335 -627 350 -8 13 28 13 427 -6 187 -8 342 -13 344 -11 9 9 -229 340 -295 410 -73 78 -195 119 -408 137 -131 11 -171 11 -353 -1z"/>
                                            <path d="M7786 6030 c-194 -23 -391 -73 -555 -142 -53 -22 -100 -44 -105 -49 -10 -9 154 -242 312 -444 284 -363 564 -640 785 -773 31 -18 88 -45 129 -59 62 -22 84 -25 148 -20 107 8 619 156 626 182 2 6 -88 82 -198 170 -110 88 -258 207 -327 265 -124 104 -126 105 -93 108 36 4 174 -16 560 -79 134 -21 245 -39 247 -39 8 0 -37 146 -66 214 -64 152 -192 335 -288 413 -124 101 -371 193 -641 238 -113 19 -422 27 -534 15z"/>
                                            <path d="M2595 5485 c-144 -44 -312 -103 -342 -119 -23 -12 -12 -20 254 -194 153 -99 300 -197 328 -216 l50 -36 -38 0 c-39 0 -386 39 -591 66 -60 7 -111 12 -113 10 -8 -8 87 -174 144 -250 86 -116 224 -244 309 -288 178 -93 487 -157 754 -158 250 0 485 48 679 141 l104 49 -104 106 c-57 57 -105 109 -107 113 -6 16 -396 378 -502 466 -117 96 -312 231 -399 275 -161 81 -254 88 -426 35z"/>
                                            <path d="M10275 5168 c-49 -3 -115 -11 -145 -17 -83 -17 -251 -65 -248 -71 2 -3 82 -81 178 -172 291 -275 406 -390 394 -394 -11 -5 -283 56 -648 146 -114 28 -211 48 -214 45 -14 -13 123 -467 164 -546 59 -115 200 -193 464 -258 281 -68 611 -99 1148 -108 l373 -6 -5 34 c-37 230 -72 342 -157 509 -137 270 -329 486 -592 664 -201 136 -305 172 -522 178 -55 2 -140 0 -190 -4z"/>
                                            <path d="M6092 4441 c-13 -32 -8 -920 6 -1041 43 -383 121 -640 229 -760 54 -60 130 -100 369 -194 218 -85 292 -111 301 -103 3 4 -33 174 -81 379 -97 421 -102 448 -89 448 10 0 184 -180 446 -463 l138 -147 20 47 c65 153 93 299 94 488 0 138 -2 157 -28 236 -64 195 -272 478 -490 667 -240 208 -505 350 -805 432 -93 25 -104 26 -110 11z"/>
                                            <path d="M4940 3641 c-607 -214 -1015 -419 -1227 -615 -83 -77 -129 -144 -149 -218 -17 -65 -33 -571 -17 -581 5 -3 140 81 299 187 159 106 311 205 337 221 l48 28 -16 -39 c-9 -22 -88 -179 -175 -351 -88 -172 -160 -315 -160 -319 0 -10 182 5 278 22 102 18 260 71 340 114 199 106 439 453 552 800 68 206 85 315 85 550 0 194 -6 261 -23 259 -4 0 -81 -26 -172 -58z"/>
                                            <path d="M7810 2153 c0 -5 9 -62 20 -128 25 -159 77 -523 86 -605 l7 -65 -23 20 c-13 11 -138 164 -278 340 -140 176 -259 324 -263 329 -11 12 -56 -87 -129 -284 -108 -290 -112 -339 -35 -496 145 -298 595 -741 1256 -1237 l36 -27 61 118 c113 218 160 402 169 663 8 239 -18 423 -92 646 -69 206 -148 327 -287 440 -145 118 -280 198 -443 263 -83 33 -85 33 -85 23z"/>
                                            <path d="M2918 1819 c-105 -11 -156 -36 -240 -116 -140 -135 -306 -427 -462 -816 -84 -208 -252 -694 -243 -703 13 -14 191 -26 302 -21 360 16 719 162 1025 416 170 142 245 264 310 506 31 118 60 395 41 395 -4 0 -124 -66 -267 -146 -289 -163 -394 -218 -401 -210 -6 6 -14 -7 224 377 100 163 186 304 189 313 6 14 -14 16 -197 15 -112 -1 -239 -5 -281 -10z"/>
                                        </g>
                                    </svg>
                                    {{__('dashboard.decors')}}
                                </a>
                            </li>
                            <li>
                                <a class="{{ Request::is(['collaborators',]) ? 'active' : '' }}" href="{{route('collaborators.index')}}">
                                    <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" fill="#000000" height="24" width="24" version="1.1" viewBox="0 0 297 297" enable-background="new 0 0 297 297">
                                        <g>
                                            <path d="M91.304,68.029c18.756,0,34.015-15.259,34.015-34.015S110.06,0,91.304,0S57.289,15.259,57.289,34.015   S72.548,68.029,91.304,68.029z"/>
                                            <path d="M229.66,68.029c18.756,0,34.015-15.259,34.015-34.015S248.416,0,229.66,0s-34.015,15.259-34.015,34.015   S210.904,68.029,229.66,68.029z"/>
                                            <path d="m228.04,82.5c-19.002,0-34.405,15.404-34.405,34.405v171.552c0,4.718 3.825,8.543 8.543,8.543h39.625c4.718,0 8.543-3.825 8.543-8.543v-81.409l11.582-31.749c0.342-0.938 0.517-1.93 0.517-2.928v-55.465c0.001-19.002-15.403-34.406-34.405-34.406z"/>
                                            <path d="m128.664,84.513l-.057-.019-9.708-2.979c-1.531-0.471-3.167,0.34-3.718,1.85l-20.414,56.011c-1.178,3.231-5.748,3.231-6.925,0l-20.415-56.011c-0.445-1.22-1.596-1.985-2.83-1.985-0.292,0-0.59,0.043-0.884,0.133l-9.7,2.976c-12.401,4.132-20.687,15.629-20.687,28.629v59.251c0,0.588 0.172,1.163 0.495,1.655l21.3,32.415v82.017c0,1.665 1.35,3.015 3.015,3.015h66.335c1.665,0 3.015-1.35 3.015-3.015v-82.016l21.301-32.415c0.323-0.492 0.495-1.067 0.495-1.655v-59.406c0-12.944-8.318-24.422-20.618-28.451z"/>
                                            <path d="m99.119,80.218c-0.786-0.856-1.935-1.287-3.097-1.287h-8.67c-1.162,0-2.311,0.431-3.098,1.287-1.217,1.326-1.393,3.241-0.53,4.738l4.635,6.987-2.17,18.302 4.272,11.365c0.417,1.143 2.033,1.143 2.45,0l4.272-11.365-2.17-18.302 4.634-6.987c0.866-1.497 0.689-3.412-0.528-4.738z"/>
                                        </g>
                                    </svg>
                                    Collaborators
                                </a>
                            </li>


                        </ul>
                    </li>
                    <li>
                        <a class="{{ Request::is(['payments','payments/*']) ? 'active' : '' }}" href="{{route('payments.index')}}">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-credit-card"><rect x="1" y="4" width="22" height="16" rx="2" ry="2"/><line x1="1" y1="10" x2="23" y2="10"/></svg>
                            {{__('dashboard.payments')}}
                        </a>
                    </li>
                    <li>
                        <a class="{{ Request::is(['expenses','expenses/*']) ? 'active' : '' }}" href="{{route('expenses.index')}}">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-dollar-sign"><line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg>
                            {{__('dashboard.expenses')}}
                        </a>
                    </li>
                    <li>
                        <div class="hubers-menu-item submenu-opener">
                                   <span class="{{ Request::is(['logs','logs/*']) ? 'active' : '' }}">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-settings"><circle cx="12" cy="12" r="3"/><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z"/></svg>
                                        {{__('dashboard.settings')}}
                                    </span>
                        </div>
                        <span class="submenu-opener submenu-opener-icon">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevron-down"><polyline points="6 9 12 15 18 9"/></svg>
                                </span>
                        <ul class="hubers-submenu">
                            <li>
                                <a class="{{ Request::is(['users','users/*']) ? 'active' : '' }}" href="{{route('users.index')}}">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-users"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                                    {{__('dashboard.users')}}
                                </a>
                            </li>
                            <li>
                                <a class="{{ Request::is(['supports-tickets','supports-tickets/*']) ? 'active' : '' }}" href="{{route('support-tickets.index')}}">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-server"><rect x="2" y="2" width="20" height="8" rx="2" ry="2"/><rect x="2" y="14" width="20" height="8" rx="2" ry="2"/><line x1="6" y1="6" x2="6.01" y2="6"/><line x1="6" y1="18" x2="6.01" y2="18"/></svg>
                                    {{__('dashboard.support_tickets')}}
                                </a>
                            </li>
                            @role(['super-admin', 'system-admin'])
                            <li>
                                <a class="{{ Request::is(['logs','logs/*']) ? 'active' : '' }}" href="{{ route('logs.index') }}">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-activity"><polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/></svg>
                                    {{__('dashboard.logs')}}
                                </a>
                            </li>
                            @endrole
                            @role(['super-admin','system-admin'])
                                <li>
                                    <a class="{{ Request::is(['reservations.import.page',]) ? 'active' : '' }}" href="{{ route('reservations.import.page') }}">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-upload"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="17 8 12 3 7 8"/><line x1="12" y1="3" x2="12" y2="15"/></svg>
                                        Import
                                    </a>
                                </li>
                            @endrole
                        </ul>
                    </li>


                    @role(['super-admin', 'system-admin'])
                    <li>
                        <a class="{{ Request::is(['reports','reports-generated']) ? 'active' : '' }}" href="{{ route('reports.index') }}">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-pie-chart"><path d="M21.21 15.89A10 10 0 1 1 8 2.83"/><path d="M22 12A10 10 0 0 0 12 2v10z"/></svg>
                            {{__('dashboard.reports')}}
                        </a>
                    </li>
                    @endrole
                @endunlessrole

                    <li>
                        <a class="{{ Request::is(['profile']) ? 'active' : '' }}" href="{{route('profile')}}">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-user"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                            {{__('dashboard.profile')}}
                        </a>
                    </li>


                    @role('system-admin')
                        <li>
                            <a class="{{ Request::is(['locations','locations/*']) ? 'active' : '' }}" href="{{route('locations.index')}}">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-map-pin"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
                                {{__('dashboard.locations')}}
                            </a>
                        </li>
                    @endrole

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