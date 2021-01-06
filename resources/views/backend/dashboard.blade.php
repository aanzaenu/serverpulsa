@extends('backend.layout.app')
@section('css')
@endsection
@section('content')
    <!-- Start Content-->
    <div class="container-fluid">
    
        <!-- start page title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box">
                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="{{ route('admin.home') }}">{{ env('APP_NAME', 'Admin') }}</a></li>
                            <li class="breadcrumb-item active">{{ $pagetitle }}</li>
                        </ol>
                    </div>
                    <h4 class="page-title">{{ $pagetitle }}</h4>
                </div>
            </div>
            <div class="col-12">
                <div class="d-block mx-auto text-center">
                    <h4>Hi, {{ Auth::user()->name }}</h4>
                    @if (is_admin())
                        <p class="text-danger">Script tidak akan berjalan apabila Terminal ID tidak sesuai dengan Terminal ID pada aplikasi OTOMAX</p>                        
                    @endif
                </div>                
            </div>
        </div>     
        <!-- end page title --> 
        
    </div> <!-- container -->
@endsection

@section('script')
@endsection
