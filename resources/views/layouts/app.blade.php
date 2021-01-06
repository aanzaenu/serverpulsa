<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8" />
    <title>{{ $title }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta content="Powerfull dashboard Admin" name="description" />
    <meta content="aanzr.io" name="author" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <link rel="shortcut icon" href="{{ asset_url('assets/images/favicon.png') }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">
        
    <link href="{{asset_url('backend/css/icons.min.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{asset_url('backend/css/bootstrap.min.css')}}" rel="stylesheet" type="text/css" id="bs-default-stylesheet" />
    <link href="{{asset_url('backend/css/custom.css')}} " rel="stylesheet" type="text/css"/>
    @yield('css')
    <link href="{{asset_url('backend/css/app.min.css')}} " rel="stylesheet" type="text/css" id="app-default-stylesheet" />
</head>
<body data-layout-mode="horizontal" @yield('body-extra')>
    <div id="app">
        @include('layouts.partial.nav')
        <main class="pt-4">
            <div class="main pt-3">
                @yield('content')
            </div>
            <div class="foot d-block w-100 bg-white text-dark pt-1 pb-2">
                <div class="container-lg">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="d-block w-100">
                                <div class="page-title-box">
                                    <p class="page-title h5">Tentang Kami</p>
                                </div>
                                <p>Website ini adalah website milik PT. Bank Tabungan Negara (Persero) Tbk, untuk menampilkan portofolio/agunan yang siap di jual atau lelang maupun mekanisme penjualan lainnya. Di dalam website ini di bagi dalam 2 kategori yaitu Rumah Bekas yaitu rumah yang dapat dijual secara sukarela dan Rumah Lelang yaitu rumah yang dijual melalui prosedur lelang di KPKNL ( Kantor Pelayanan Kekayaan Negara dan Lelang) </p>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="d-block w-100">
                                <div class="page-title-box">
                                    <p class="page-title h5">Kontak Kami</p>
                                </div>
                                <address><b>Email: </b> <a href="mailto:rumahmurah@btn.co.id">rumahmurah@btn.co.id</a><br>atau<br>PT. Bank Tabungan Negara (Persero) Tbk<br>Consumer Collection, Recovery and Asset Sales Division (CRSD)<br>Menara BTN Lantai 12 A <br>Jl. Gajah Mada No 1<br>Jakarta 10130<br>Telp : (021) 6336789, 6332666 ext. 1339 </address>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="d-block w-100">
                                <div class="page-title-box">
                                    <p class="page-title h5">Sosial Media</p>
                                </div>
                                <ul class="list-unstyled list-links">
                                    <li>
                                        <a target="_blank" href="https://www.instagram.com/rumahmurahbtn/">
                                            <i class="mdi mdi-instagram" aria-hidden="true"></i> @rumahmurahbtn
                                        </a>
                                    </li>
                                    <li>
                                        <a target="_blank" href="https://www.facebook.com/rumahmurahbtn/">
                                            <i class="mdi mdi-facebook" aria-hidden="true"></i> Rumah Murah BTN
                                        </a>
                                    </li>
                                    <li>
                                        <a target="_blank" href="http://www.btn.co.id">
                                            <i class="mdi mdi-web" aria-hidden="true"></i> www.btn.co.id
                                        </a>
                                    </li>
                                    <li>
                                        <a target="_blank" href="https://twitter.com/rumahmurahbtn/">
                                            <i class="mdi mdi-twitter" aria-hidden="true"></i> @rumahmurahbtn
                                        </a>
                                    </li>
                                    <li>
                                        <a target="_blank" href="https://www.youtube.com/channel/UCWpG0IG6UaUfBy4FWlJfOJA">
                                            <i class="mdi mdi-youtube" aria-hidden="true"></i> officialbankbtn
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
    <footer class="footers text-center py-2">
        <script>document.write(new Date().getFullYear())</script> &copy; {{ env('APP_NAME') }}
    </footer>
    <!-- Scripts -->
    <script src="{{asset_url('backend/js/vendor.min.js')}}"></script>
    <script src="{{asset_url('backend/js/app.min.js')}}"></script>
    @yield('script')
</body>
</html>
