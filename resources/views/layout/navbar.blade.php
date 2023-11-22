<div class="preloader">
    <div class="lds-ripple">
        <div class="lds-pos"></div>
        <div class="lds-pos"></div>
    </div>
</div>
<nav class="navbar top-navbar navbar-expand-md">
    <div class="navbar-header" data-logobg="skin6">
        <!-- This is for the sidebar toggle which is visible on mobile only -->
        <a class="nav-toggler waves-effect waves-light d-block d-md-none" href="javascript:void(0)"><i
                class="ti-menu ti-close"></i></a>
        <!-- ============================================================== -->
        <!-- Logo -->
        <!-- ============================================================== -->
        <div class="navbar-brand">
            <!-- Logo icon -->
            <a href="index.html">
                <b class="logo-icon">
                    <!-- Dark Logo icon -->
                     <img src="{{ asset('assets/images/poliwangi.png') }}" alt="homepage" class="dark-logo" width="50px" height="40px"/>
                     <!-- Light Logo icon -->
                    {{-- <img src="{{ asset('assets/images/logo-poliwangi') }}" alt="homepage" class="light-logo" /> --}}
                </b>
                <!--End Logo icon -->
                <!-- Logo text -->
                <span class="logo-text">
                    <!-- dark Logo text -->
                    <img src="{{ asset('assets/images/poliwangi-txt.png') }}" alt="homepage" class="dark-logo" width="140px" height="35px"/>
                    {{-- <img src="{{ asset('assets/images/logo-text.png') }}" alt="homepage" class="dark-logo" />
                    <!-- Light Logo text -->
                    <img src="{{ asset('assets/images/logo-light-text.png') }}" class="light-logo" alt="homepage" /> --}}
                </span>
            </a>
        </div>
        <!-- ============================================================== -->
        <!-- End Logo -->
        <!-- ============================================================== -->
        <!-- ============================================================== -->
        <!-- Toggle which is visible on mobile only -->
        <!-- ============================================================== -->
        <a class="topbartoggler d-block d-md-none waves-effect waves-light" href="javascript:void(0)"
            data-toggle="collapse" data-target="#navbarSupportedContent"
            aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation"><i
                class="ti-more"></i></a>
    </div>
    <!-- ============================================================== -->
    <!-- End Logo -->
    <!-- ============================================================== -->
    <div class="navbar-collapse collapse" id="navbarSupportedContent">
        <!-- ============================================================== -->
        <!-- toggle and nav items -->
        <!-- ============================================================== -->
        <ul class="navbar-nav float-left mr-auto ml-3 pl-1">
            <li class="nav-item d-none d-md-block">
                {{-- <a class="nav-link" href="javascript:void(0)">
                    <form>
                        <div class="customize-input">
                            <input class="form-control custom-shadow custom-radius border-0 bg-white"
                                type="search" placeholder="Search" aria-label="Search">
                            <i class="form-control-icon" data-feather="search"></i>
                        </div>
                    </form>
                </a> --}}
            </li>
        </ul>
        <!-- ============================================================== -->
        <!-- Right side toggle and nav items -->
        <!-- ============================================================== -->
        <ul class="navbar-nav float-right">
            <!-- ============================================================== -->
            <!-- User profile and search -->
            <!-- ============================================================== -->
            <li class="nav-item">
                <a class="nav-link">
                    <span class="ml-2 d-none d-lg-inline-block"><span>Hello,{{ session('nama') }}</span> <span
                            class="text-dark"></span>
                </a>
            </li>
            <!-- ============================================================== -->
            <!-- User profile and search -->
            <!-- ============================================================== -->
        </ul>
    </div>
</nav>
