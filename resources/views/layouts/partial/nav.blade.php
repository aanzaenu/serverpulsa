<!-- Topbar Start -->
<div class="navbar-custom p-0">
    <div class="container-lg">    
        <!-- LOGO -->
        <div class="logo-box pl-2 pl-lg-0">
            <a href="{{ route('home') }}" class="logo logo-dark">
                <span class="logo-sm">
                    <img src="{{asset_url('assets/images/logo.png')}}" alt="" class="d-inline-block w-100">
                    <!-- <span class="logo-lg-text-light">UBold</span> -->
                </span>
                <span class="logo-lg">
                    <img src="{{asset_url('assets/images/logo.png')}}" alt="" class="d-inline-block w-100">
                    <!-- <span class="logo-lg-text-light">U</span> -->
                </span>
            </a>
    
            <a href="{{ route('home') }}" class="logo logo-light">
                <span class="logo-sm">
                    <img src="{{asset_url('assets/images/logo.png')}}" alt="" class="d-inline-block w-100">
                </span>
                <span class="logo-lg">
                    <img src="{{asset_url('assets/images/logo.png')}}" alt="" class="d-inline-block w-100">
                </span>
            </a>
        </div>
        <ul class="list-unstyled topnav-menu float-right mb-0">    
            <li class="d-none d-lg-inline-block">
                <a class="nav-link arrow-none waves-effect text-white" href="{{ route('home') }}">
                    Home
                </a>
            </li>
        </ul>
    
        <ul class="list-unstyled topnav-menu topnav-menu-left m-l-auto d-block d-md-none float-right">
            <li class="float-right">
                <button class="button-menu-mobile waves-effect waves-light">
                    <i class="fe-menu"></i>
                </button>
            </li>

            <li class="float-right">
                <!-- Mobile menu toggle (Horizontal Layout)-->
                <a class="navbar-toggle nav-link" data-toggle="collapse" data-target="#topnav-menu-content">
                    <div class="lines">
                        <span class="bg-white"></span>
                        <span class="bg-white"></span>
                        <span class="bg-white"></span>
                    </div>
                </a>
                <!-- End mobile menu toggle-->
            </li>
        </ul>
        <div class="clearfix"></div>
    </div>
</div>
@include('layouts.partial.menu')
<!-- end Topbar -->