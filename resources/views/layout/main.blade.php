@include('layout.header')
{{-- Start Header --}}
@stack('style-section')
<style>
    .notify-popup {
        position: absolute;
        right: 0;
        padding: 10px 30px 10px 10px;
        margin-top: 10px;
        border-radius: 7px;
        z-index: 1;
    }

    .swal2-container {
        z-index: 999999999 !important;
    }

    @media (max-width: 1024px) {
        .lolo {
            display: none;
        }
    }
</style>

<body class="app sidebar-mini light-mode default-sidebar is-expanded">

    <!---Global-loader-->
    <div id="global-loader">
        <img src="{{ url('assets/images/svgs/loader.svg') }}" alt="loader">
    </div>
    {{-- End Theme Settings --}}
    <div class="page is-expanded">
        <div class="page-main">
            {{-- Start Side Bar --}}
            @include('layout.sidebar')
            {{-- End Side Bar --}}
            <div class="app-content main-content">
                @if ($message = Session::get('success'))
                    <div class="alert notify-popup alert-success mb-2" id="alert-success-message" role="alert">
                        <strong>Success! </strong> {{ $message }}
                    </div>
                @endif
                @if ($message = Session::get('error'))
                    <div class="alert notify-popup alert-danger mb-2" id="alert-error-message" role="alert">
                        <strong>Error! </strong> {{ $message }}
                    </div>
                @endif
                <div class="side-app">
                    <!--app header-->
                    <div class="app-header header top-header">
                        <div class="container-fluid">
                            <div class="d-flex">
                                <a class="header-brand" href="{{ route('admin.dashboard') }}">
                                    <img src="{{ url('assets/images/logo-commish.png') }}"
                                        class="header-brand-img desktop-lgo" alt="Commish">
                                    <img src="{{ url('assets/images/logo-commish.png') }}"
                                        class="header-brand-img dark-logo" alt="Commish">
                                    <img src="{{ url('assets/images/commish-favicon.png') }}"
                                        class="header-brand-img mobile-logo" alt="Commish">
                                    <img src="{{ url('assets/images/commish-favicon.png') }}"
                                        class="header-brand-img darkmobile-logo" alt="Commish">
                                </a>
                                <div class="dropdown side-nav">
                                    <div class="app-sidebar__toggle" data-toggle="sidebar">
                                        <a class="open-toggle" href="#">
                                            <svg class="header-icon mt-1" xmlns="http://www.w3.org/2000/svg"
                                                width="24" height="24" viewBox="0 0 24 24" fill="none"
                                                stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                                stroke-linejoin="round">
                                                <line x1="3" y1="12" x2="21" y2="12">
                                                </line>
                                                <line x1="3" y1="6" x2="21" y2="6">
                                                </line>
                                                <line x1="3" y1="18" x2="21" y2="18">
                                                </line>
                                            </svg>
                                        </a>
                                        <a class="close-toggle" href="#">
                                            <svg class="header-icon mt-1" xmlns="http://www.w3.org/2000/svg"
                                                height="24" viewBox="0 0 24 24" width="24">
                                                <path d="M0 0h24v24H0V0z" fill="none" />
                                                <path
                                                    d="M19 6.41L17.59 5 12 10.59 6.41 5 5 6.41 10.59 12 5 17.59 6.41 19 12 13.41 17.59 19 19 17.59 13.41 12 19 6.41z" />
                                            </svg>
                                        </a>
                                    </div>
                                </div>
                                <div class="d-flex align-items-center justify-content-center mx-auto">
                                    <p class="h6 mb-0" style="color: red;  font-size: 20px;">
                                        @if (session('use_old_database'))
                                            Currently connected to the archived database.
                                        @else
                                            Currently connected to the live database.
                                        @endif
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!--/app header--> 
                    @yield('page_title_con')
                    @yield('body')
                </div>
            </div>

            {{-- Start Footer --}}
            @include('layout.footer_bar')
            {{-- End Footer --}}
        </div>
    </div>
    @include('layout.footer')
    {{-- Start Footer --}}
    @stack('script-section')
    @if ($message = Session::get('success'))
        <script>
            setTimeout(function() {
                document.getElementById('alert-success-message').style.display = 'none'
            }, 10000);
        </script>
    @endif
    @if ($message = Session::get('error'))
        <script>
            setTimeout(function() {
                document.getElementById('alert-error-message').style.display = 'none'
            }, 10000);
        </script>
    @endif
    @yield('scripts')
    @yield('reports_script')
</body>

</html>

@yield('modal-section')