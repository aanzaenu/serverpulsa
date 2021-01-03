<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8" />
    <title>@yield('title') - {{ config('app.name', 'Asset Management Division BTN') }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta content="Powerfull dashboard Admin" name="description" />
    <meta content="aanzr.io" name="author" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <link rel="shortcut icon" href="{{ asset('assets/images/favicon.png') }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">
        
    <link href="{{asset('backend/css/icons.min.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{asset('backend/css/bootstrap.min.css')}}" rel="stylesheet" type="text/css" id="bs-default-stylesheet" />
    <link href="{{asset('backend/css/app.min.css')}} " rel="stylesheet" type="text/css" id="app-default-stylesheet" />
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
</head>
<body class="@yield('bodyclass')">
    <div id="app">
        <main class="py-4">
            @yield('content')
        </main>
    </div>
    <footer class="footer footer-alt">
        <script>document.write(new Date().getFullYear())</script> &copy; Asset Management Division BTN
    </footer>
    <!-- Scripts -->
    <script src="{{asset('backend/js/vendor.min.js')}}"></script>
    <script src="{{asset('backend/js/app.min.js')}}"></script>
    <script src="{{ asset('js/app.js') }}" defer></script>
    @yield('script')
</body>
</html>
