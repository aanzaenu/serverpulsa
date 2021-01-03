@include('backend.layout.head')
<body  data-layout='{"menuPosition": "fixed"}'>
    <div id="wrapper">
        @include('backend.layout.topbar')
        @include('backend.layout.left-sidebar')
        <div class="content-page">
            <div class="content">
                @yield('content')
            </div>
            @include('backend.layout.foot')
        </div>
    </div>
    <script src="{{asset('backend/js/vendor.min.js')}}"></script>
    @yield('script')
    
    <script src="{{asset('backend/js/app.min.js')}}"></script>
    @yield('script-bottom')
</body>
</html>