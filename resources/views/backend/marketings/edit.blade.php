@extends('backend.layout.app')
@section('css')
    <link href="{{asset('backend/libs/select2/select2.min.css')}}" rel="stylesheet" type="text/css" />
    <style>
        .assets-image{
            width: 100%;
            height: 200px;
            background-position: center;
            background-size: cover;
            background-repeat: no-repeat;
        }
    </style>
@endsection
@section('content')
    <div class="container-fluid">
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
        </div>
        @include('backend.layout.allert')
        <form method="POST" action="{{ route('admin.'.$uri.'.update', $row) }}" enctype="multipart/form-data">
            <div class="row">
                <div class="col-sm-9">
                    <div class="card-box">
                        <div class="row">
                            <div class="col-md-12">
                                @csrf
                                @method('PUT')
                                <div class="form-group">
                                    <label for="name">Nama</label>
                                    <input type="text" class="form-control @if($errors->has('name')) is-invalid @endif" id="name"  name="name"  placeholder="A'an Zaenu Romli" value="{{ $row->name }}">                                    
                                    @error('name')
                                        <div class="invalid-feedback" role="feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label for="branch">Kantor Cabang</label>
                                    <select id="branch" name="branch" class="form-control @if($errors->has('branch')) is-invalid @endif"  data-toggle="select2">
                                        <option value="">Pilih Kantor Cabang</option>
                                        @foreach ($branches as $branch)
                                            <option value="{{ $branch->id }}" @if($row->branches()->first()->id == $branch->id) selected @endif >{{ $branch->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('branch')
                                        <div class="invalid-feedback" role="feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label for="phone">Nomor Telepon</label>
                                    <input type="text" class="form-control @if($errors->has('phone')) is-invalid @endif" id="phone"  name="phone"  placeholder="08562919141" value="{{ $row->phone }}">                                    
                                    @error('phone')
                                        <div class="invalid-feedback" role="feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                    <span class="help-block">
                                        <small>Cukup satu Nomor Telepon saja. Hanya angka!</small>
                                    </span>
                                </div>
                                <div class="form-group">
                                    <label for="whatsapp">Nomor Whatsapp</label>
                                    <input type="text" class="form-control @if($errors->has('whatsapp')) is-invalid @endif" id="whatsapp"  name="whatsapp"  placeholder="+628562919141" value="{{ $row->whatsapp }}">                                    
                                    @error('whatsapp')
                                        <div class="invalid-feedback" role="feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label for="email">Email</label>
                                    <input type="email" class="form-control @if($errors->has('email')) is-invalid @endif" id="email"  name="email"  placeholder="aan.zaenu@gmail.com" value="{{ $row->email }}">                                    
                                    @error('email')
                                        <div class="invalid-feedback" role="feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                                <div class="form-row">
                                    <div class="form-group col-md-6">
                                        <label for="map" class="col-form-label pt-0">Koordinat Lokasi Alamat</label>
                                        <div class="d-block w-100 ">
                                            <div id="map" class="d-block w-100 mb-2 border rounded @if($errors->has('lat') || $errors->has('long')) border-danger @endif" style="height:305px"></div>
                                        </div>
                                        <span class="help-block">
                                            <small>Geser pin merah/ketik lokasi di kolom pencarian untuk menentukan koordinat lokasi.</small>
                                        </span>
                                        @if($errors->has('lat') || $errors->has('long'))
                                            <div class="text-danger mb-2" role="feedback">
                                                Koordinat Lokasi tidak boleh kosong
                                            </div>
                                        @endif
                                        <div class="input-group">
                                            <input type="text" class="form-control" placeholder="Cari Lokasi" name="inputsearchmap">
                                            <div class="input-group-append">
                                                <button class="btn btn-dark waves-effect waves-light searchmap" type="button">Cari</button>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group col-md-6">                                        
                                        <div class="form-group">
                                            <label for="lat">Latitude</label>
                                            <input type="text" id="lat" name="lat" class="form-control lat @if($errors->has('lat')) is-invalid @endif" placeholder="134" value="{{ $row->lat }}">
                                            @error('lat')
                                                <div class="invalid-feedback" role="feedback">
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                        </div>
                                        <div class="form-group">
                                            <label for="long">Longitude</label>
                                            <input type="text" id="long" name="long" class="form-control long @if($errors->has('long')) is-invalid @endif" placeholder="134" value="{{ $row->long }}">
                                            @error('long')
                                                <div class="invalid-feedback" role="feedback">
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                        </div>
                                        <div class="form-group">
                                            <label for="address">Alamat Lengkap</label>
                                            <textarea type="text" class="form-control @if($errors->has('address')) is-invalid @endif" id="address"  name="address"  placeholder="Jl.Cendrawasih No.148, Manukan, Condong Catur, Sleman, Yogyakarta" rows="3">{{ $row->address }}</textarea>
                                            @error('address')
                                                <div class="invalid-feedback" role="feedback">
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-3">
                    <div class="card-box">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="address">Gambar</label>
                                </div>
                                <div class="col-md-12">
                                    <div class="card-box product-box p-0 mb-1 bg-dark @if($errors->has('image')) border border-danger rounded @endif">
                                        <div class="product-action">
                                            <button type="button" class="btn btn-amdbtn btn-xs waves-effect waves-light edit-image">
                                                <i class="mdi mdi-pencil"></i>
                                            </button>
                                        </div>
                                        <div class="assets-image" style="background-image: url({{ asset($row->image) }})"></div>
                                        <input type="file" name="image" accept="image/*" class="d-none fileimage"/>
                                    </div>
                                    @error('image')
                                        <div class="col-md-12">
                                            <div class="d-block text-danger mb-2 " role="feedback">
                                                {{ $message }}
                                            </div>
                                        </div>
                                    @enderror
                                </div>
                                <button type="submit" class="btn btn-amdbtn waves-effect waves-light">Simpan</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection
@section('script')
<script src="{{asset('backend/libs/select2/select2.min.js')}}"></script>
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDsucrEdmswqYrw0f6ej3bf4M4suDeRgNA"></script>
<script>
    $(document).ready(function(){
        $('select[data-toggle="select2"]').select2();
        $(document).on('click', '.edit-image', function(){
            var fileimage = $('input[type="file"]');
            fileimage.trigger("click");
        });
        $(document).on('change', 'input[type="file"]', function(){
            var val = window.URL.createObjectURL(this.files[0]);
            $('.assets-image').css('background-image', 'url('+val+')');
        });
        var geocoder;
        var clat = {{ (!empty($row->lat)) ? $row->lat : -3.421464 }};
        var clong = {{ (!empty($row->long)) ? $row->long : 112.951445 }};
        var map = new google.maps.Map(document.getElementById('map'), {
            zoom: 5,
            center: new google.maps.LatLng(clat, clong),
            mapTypeId: google.maps.MapTypeId.ROADMAP
        });
        var rlat = {{ (!empty($row->lat)) ? $row->lat : -7.781921 }};
        var rlong = {{ (!empty($row->long)) ? $row->long : 110.364678 }};
        var latLng = new google.maps.LatLng(rlat, rlong);
        
        var marker = new google.maps.Marker({
            position : latLng,
            title : 'lokasi',
            map : map,
            draggable : true
        });
        @if(!empty($row->lat) && !empty($row->long))
            updateMarkerPosition(latLng);
        @endif
        google.maps.event.addListener(marker, 'drag', function() {
            updateMarkerPosition(marker.getPosition());
        });
        $('.searchmap').on('click', function(){
            var find = $('input[name="inputsearchmap"]').val();
            if(find !== '')
            {
                getCoordinates(find);
            }
        });
        function updateMarkerPosition(latLng) {
            $('.lat').val([latLng.lat()]);
            $('.long').val([latLng.lng()]);
        };
        function getCoordinates(address)
        {
            if(!geocoder)
            {
                geocoder = new google.maps.Geocoder();
            };
            var geocoderRequest = {
                address: address
            };
            geocoder.geocode(geocoderRequest, function(results, status)
            {
                if (status == google.maps.GeocoderStatus.OK)
                {
                    map.setCenter(results[0].geometry.location);
                    map.setZoom(15);
                    $('.lat').val(results[0].geometry.location.lat());
                    $('.long').val(results[0].geometry.location.lng());
                    if (!marker)
                    {
                        marker = new google.maps.Marker({
                            map: map,
                            draggable : true
                        });
                    };
                    google.maps.event.addListener(marker, 'drag', function()
                    {
                        updateMarkerPosition(marker.getPosition());
                    });
                    marker.setPosition(results[0].geometry.location);
                }
            });
        };
    });
</script>
@endsection
