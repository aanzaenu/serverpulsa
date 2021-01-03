@extends('backend.layout.app')
@section('css')
<link href="{{asset('backend/libs/select2/select2.min.css')}}" rel="stylesheet" type="text/css" />
<link href="{{asset('backend/libs/flatpickr/flatpickr.min.css')}}" rel="stylesheet" type="text/css" />
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
        <form method="POST" action="{{ route('admin.'.$uri.'.store') }}" enctype="multipart/form-data">
            <div class="row">
                <div class="col-sm-9">
                    <div class="card-box">
                        <div class="row">
                            <div class="col-md-12">
                                @csrf
                                @method('POST')
                                <div class="form-row">
                                    <div class="form-group col-md-4">
                                        <label for="code_location" class="col-form-label pt-0">Kode Assets</label>
                                        <select id="code_location" name="code_location" class="custom-select @if($errors->has('code_location')) is-invalid @endif" data-toggle="select2">
                                            <option value="">Pilih Kode Asset</option>
                                            @foreach ($branches as $branch)
                                                <option value="{{ $branch->code_location }}" {{ ($branch->code_location == old('code_location')) ? 'selected' : '' }} >{{ $branch->code_location }}</option>
                                            @endforeach
                                        </select>
                                        @error('code_location')
                                            <div class="invalid-feedback" role="feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label for="code_type" class="col-form-label pt-0">Tipe Assets</label>
                                        <select id="code_type" name="code_type" class="custom-select @if($errors->has('code_type')) is-invalid @endif" data-toggle="select2">
                                            <option value="0" @if (old('code_type') == '0') selected @endif>KC</option>
                                            <option value="S" @if (old('code_type') == 'S') selected @endif>KCS</option>
                                            <option value="K" @if (old('code_type') == 'K') selected @endif>KCK</option>
                                            <option value="P" @if (old('code_type') == 'P') selected @endif>KCP</option>
                                            <option value="C" @if (old('code_type') == 'C') selected @endif>KCC</option>
                                        </select>
                                        @error('code_type')
                                            <div class="invalid-feedback" role="feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                    <div class="form-group col-md-4 @if($errors->has('code_year')) is-invalid @endif">
                                        <label for="code_year" class="col-form-label pt-0">Nomor Assets</label>
                                        <div class="input-group @if($errors->has('code_year')) is-invalid @endif">
                                            <div class="input-group-prepend">
                                                <div class="input-group-text">{{ date('ym') }}</div>
                                            </div>
                                            <input type="text" class="form-control @if($errors->has('code_year')) is-invalid @endif" id="code_year" name="code_year" placeholder="10" value="{{ old('code_year') }}">
                                        </div>                                        
                                        @error('code_year')
                                            <div class="invalid-feedback" role="feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="asset_type">Jenis Produk</label>
                                    <select id="asset_type" name="asset_type" class="custom-select @if($errors->has('asset_type')) is-invalid @endif" data-toggle="select2">
                                        <option value="">Pilih Jenis Produk</option>
                                        <option value="Konsumer" @if (old('asset_type') == 'Konsumer') selected @endif>Konsumer</option>
                                        <option value="Komersil" @if (old('asset_type') == 'Komersil') selected @endif>Komersil</option>
                                        <option value="Pasif" @if (old('asset_type') == 'Pasif') selected @endif>Pasif</option>
                                        <option value="Syariah" @if (old('asset_type') == 'Syariah') selected @endif>Syariah</option>
                                    </select>
                                    @error('asset_type')
                                        <div class="invalid-feedback" role="feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label for="rec">Nomor Rekening Debitur</label>
                                    <input type="text" id="rec" name="rec" class="form-control @if($errors->has('rec')) is-invalid @endif" placeholder="9999999999" value="{{ old('rec') }}">
                                    @error('rec')
                                        <div class="invalid-feedback" role="feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                                <div class="form-row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="price">Harga</label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <div class="input-group-text">
                                                        Rp.
                                                    </div>
                                                </div>
                                                <input type="text" id="price" name="price" class="form-control @if($errors->has('price')) is-invalid @endif" placeholder="100.000.000" value="{{ old('price') }}" data-toggle="input-mask" data-mask-format="000.000.000.000.000" data-reverse="true" />
                                                @error('price')
                                                    <div class="invalid-feedback" role="feedback">
                                                        {{ $message }}
                                                    </div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="price_sale">Harga Diskon</label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <div class="input-group-text">
                                                        Rp.
                                                    </div>
                                                </div>
                                                <input type="text" id="price_sale" name="price_sale" class="form-control @if($errors->has('price_sale')) is-invalid @endif" placeholder="99.999.999" value="{{ old('price_sale') }}" data-toggle="input-mask" data-mask-format="000.000.000.000.000" data-reverse="true" />
                                                @error('price_sale')
                                                    <div class="invalid-feedback" role="feedback">
                                                        {{ $message }}
                                                    </div>
                                                @enderror
                                            </div>
                                            <span class="help-block">
                                                <small>Kosongkan bila tidak ada diskon/sale.</small>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="house_category">Kategori Produk</label>
                                    <select id="house_category" name="house_category" class="custom-select @if($errors->has('house_category')) is-invalid @endif" data-toggle="select2">
                                        <option value="">Pilih Kategori Produk</option>
                                        @foreach ($categories as $category)
                                            <option value="{{ $category->id }}" @if (old('house_category') ==  $category->id) selected @endif>{{ $category->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('house_category')
                                        <div class="invalid-feedback" role="feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-box">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="house_type">Tipe Rumah</label>
                                    <select id="house_type" name="house_type" class="custom-select @if($errors->has('house_type')) is-invalid @endif" data-toggle="select2">
                                        <option value="">Pilih Tipe Rumah</option>
                                        <option value="21" @if (old('house_type') == 21) selected @endif>21</option>
                                        <option value="27" @if (old('house_type') == 27) selected @endif>27</option>
                                        <option value="36" @if (old('house_type') == 36) selected @endif>36</option>
                                        <option value="45" @if (old('house_type') == 45) selected @endif>45</option>
                                        <option value="70" @if (old('house_type') == 70) selected @endif>70</option>
                                        <option value="90" @if (old('house_type') == 90) selected @endif>90</option>
                                        <option value="115" @if (old('house_type') == 115) selected @endif>115</option>
                                        <option value="135" @if (old('house_type') == 135) selected @endif>135</option>
                                        <option value="175" @if (old('house_type') == 175) selected @endif>175</option>
                                    </select>
                                    @error('house_type')
                                        <div class="invalid-feedback" role="feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                                <div class="form-group mb-3">
                                    <label for="house_street">Lebar Jalan Depan</label>
                                    <div class="input-group input-group-merge">
                                        <input type="text" id="house_street" name="house_street" class="form-control @if($errors->has('house_street')) is-invalid @endif" placeholder="50" value="{{ old('house_street') }}">
                                        <div class="input-group-append">
                                            <div class="input-group-text">
                                                M
                                            </div>
                                        </div>
                                        @error('house_street')
                                            <div class="invalid-feedback" role="feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="form-group mb-3">
                                    <label for="house_wide">Luas Tanah</label>
                                    <div class="input-group input-group-merge">
                                        <input type="text" id="house_wide" name="house_wide" class="form-control @if($errors->has('house_wide')) is-invalid @endif" placeholder="50" data-toggle="input-mask" data-mask-format="000.000.000.000.000" data-reverse="true" value="{{ old('house_wide') }}">
                                        <div class="input-group-append">
                                            <div class="input-group-text">
                                                M²
                                            </div>
                                        </div>
                                        @error('house_wide')
                                            <div class="invalid-feedback" role="feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="form-group mb-3">
                                    <label for="house_building">Luas Bangunan</label>
                                    <div class="input-group input-group-merge">
                                        <input type="text" id="house_building" name="house_building" class="form-control @if($errors->has('house_building')) is-invalid @endif" placeholder="50" data-toggle="input-mask" data-mask-format="000.000.000.000.000" data-reverse="true" value="{{ old('house_building') }}">
                                        <div class="input-group-append">
                                            <div class="input-group-text">
                                                M²
                                            </div>
                                        </div>
                                        @error('house_building')
                                            <div class="invalid-feedback" role="feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-box">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="doc_type">Tipe Dokumen</label>
                                    <select id="doc_type" name="doc_type" class="custom-select @if($errors->has('doc_type')) is-invalid @endif" data-toggle="select2">
                                        <option value="">Pilih Tipe Dokumen</option>
                                        <option value="SHM" @if (old('doc_type') == 'SHM') selected @endif>SHM</option>
                                        <option value="SHGB" @if (old('doc_type') == 'SHGB') selected @endif>SHGB</option>
                                        <option value="SHGP" @if (old('doc_type') == 'SHGP') selected @endif>SHGP</option>
                                        <option value="HPL" @if (old('doc_type') == 'HPL') selected @endif>HPL</option>
                                        <option value="HGU" @if (old('doc_type') == 'HGU') selected @endif>HGU</option>
                                    </select>
                                    @error('doc_type')
                                        <div class="invalid-feedback" role="feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label for="doc_no">Nomor Dokumen</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text">
                                                No.
                                            </div>
                                        </div>
                                        <input type="text" id="doc_no" name="doc_no" class="form-control @if($errors->has('doc_no')) is-invalid @endif" placeholder="025" value="{{ old('doc_no') }}">
                                        @error('doc_no')
                                            <div class="invalid-feedback" role="feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="doc_name">Atas Nama Dokumen</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text">
                                                A/N
                                            </div>
                                        </div>
                                        <input type="text" id="doc_name" name="doc_name" class="form-control @if($errors->has('doc_name')) is-invalid @endif" placeholder="Aan Zaenu Romli" value="{{ old('doc_name') }}">
                                        @error('doc_name')
                                            <div class="invalid-feedback" role="feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-box">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="sales">Sales</label>
                                    <select id="sales" name="sales" class="custom-select @if($errors->has('sales')) is-invalid @endif" data-toggle="select2">
                                        <option value="">Pilih Sales</option>
                                        @foreach ($marketings as $sales)
                                            <option value="{{ $sales->id }}" {{ (old('sales') == $sales->id) ? 'selected' : '' }}>{{ $sales->name.' - '.$sales->branches()->first()->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('sales')
                                        <div class="invalid-feedback" role="feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label for="branch">Penanggung Jawab / Kantor Cabang</label>
                                    <select id="branch" name="branch" class="custom-select @if($errors->has('branch')) is-invalid @endif" data-toggle="select2">
                                        <option value="">Pilih Kantor Cabang</option>
                                        @foreach ($branches as $branch)
                                            <option value="{{ $branch->id }}" {{ (old('branch') == $branch->id) ? 'selected' : '' }}>{{ $branch->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('branch')
                                        <div class="invalid-feedback" role="feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-box">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="sold_method">Cara Penjualan</label>
                                    <select id="sold_method" name="sold_method" class="custom-select @if($errors->has('sold_method')) is-invalid @endif" data-toggle="select2">
                                        <option value="">Pilih Cara Penjualan</option>
                                        <option value="1" @if (old('sold_method') == 1) selected @endif>Penjualan Langsung</option>
                                        <option value="2" @if (old('sold_method') == 2) selected @endif>Lelang</option>
                                    </select>
                                    @error('sold_method')
                                        <div class="invalid-feedback" role="feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                                <div class="penjualan-langsung @if (!empty(old('sold_method')) && old('sold_method') == 1) @else d-none @endif">
                                    <div class="form-group">
                                        <label for="bank_location">Bank BTN</label>
                                        <select id="bank_location" name="bank_location" class="custom-select @if($errors->has('bank_location')) is-invalid @endif" data-toggle="select2">
                                            <option value="">Pilih Kantor Cabang</option>
                                            @foreach ($branches as $branch)
                                                <option value="{{ $branch->id }}" {{ (old('bank_location') == $branch->id) ? 'selected' : '' }}>{{ $branch->name }}</option>
                                            @endforeach
                                        </select>
                                        @error('bank_location')
                                            <div class="invalid-feedback" role="feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label for="sold_note">Keterangan Penjualan</label>
                                        <select id="sold_note" name="sold_note" class="custom-select @if($errors->has('sold_note')) is-invalid @endif" data-toggle="select2">
                                            <option value="">Pilih Keterangan Penjualan</option>
                                            <option value="1" @if (old('sold_note') == 1) selected @endif>CESSIE</option>
                                            <option value="2" @if (old('sold_note') == 2) selected @endif>SKM</option>
                                            <option value="3" @if (old('sold_note') == 3) selected @endif>Penjualan Bersama</option>
                                            <option value="4" @if (old('sold_note') == 4) selected @endif>Lainnya</option>
                                        </select>
                                        @error('sold_note')
                                            <div class="invalid-feedback" role="feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="lelang @if (!empty(old('sold_method')) && old('sold_method') == 2) @else d-none @endif">
                                    <div class="form-group">
                                        <label for="auction_location">KPKNL</label>
                                        <select id="auction_location" name="auction_location" class="custom-select @if($errors->has('auction_location')) is-invalid @endif" data-toggle="select2">
                                            <option value="">Pilih Kantor Cabang</option>
                                            @foreach ($kpknl as $kpk)
                                                <option value="{{ $kpk->id }}" {{ (old('auction_location') == $kpk->id) ? 'selected' : '' }}>{{ $kpk->name.' - '.$kpk->address }}</option>
                                            @endforeach
                                        </select>
                                        @error('auction_location')
                                            <div class="invalid-feedback" role="feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label for="auction_time">Estimasi Tanggal Lelang</label>
                                        <div class="input-group">
                                            <input type="text" id="auction_time" name="auction_time" class="form-control @if($errors->has('auction_time')) is-invalid @endif" placeholder="2020-10-05 12:12:00" value="{{ old('auction_time') }}" data-toggle="waktu">
                                            <div class="input-group-append">
                                                <div class="input-group-text">
                                                    <i class="fa fa-clock"></i>
                                                </div>
                                            </div>
                                            @error('auction_time')
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
                    <div class="card-box">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="address">Alamat (Perumahan/Jalan)</label>
                                    <input type="text" id="address" name="address" class="form-control @if($errors->has('address')) is-invalid @endif" placeholder="Jl.Cendrawasih No.148, Manukan, Condong Catur, Sleman, Yogyakarta" value="{{ old('address') }}">
                                    @error('address')
                                        <div class="invalid-feedback" role="feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label for="address_no">Nomor Rumah</label>
                                    <input type="text" id="address_no" name="address_no" class="form-control @if($errors->has('address_no')) is-invalid @endif" placeholder="134" value="{{ old('address_no') }}">
                                    @error('address_no')
                                        <div class="invalid-feedback" role="feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                                <div class="form-row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="address_rt">RT</label>
                                            <input type="text" id="address_rt" name="address_rt" class="form-control @if($errors->has('address_rt')) is-invalid @endif" placeholder="134" value="{{ old('address_rt') }}">
                                            @error('address_rt')
                                                <div class="invalid-feedback" role="feedback">
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="address_rw">RW</label>
                                            <input type="text" id="address_rw" name="address_rw" class="form-control @if($errors->has('address_rw')) is-invalid @endif" placeholder="15" value="{{ old('address_rw') }}">
                                            @error('address_rw')
                                                <div class="invalid-feedback" role="feedback">
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="address_prov">Provinsi</label>
                                    <select id="address_prov" name="address_prov" class="custom-select @if($errors->has('address_prov')) is-invalid @endif" data-toggle="select2">
                                        <option value="">Pilih Provinsi</option>
                                        @foreach ($provinsi as $prov)
                                            <option value="{{ $prov->code }}" @if (old('address_prov') == $prov->code) selected @endif>{{ $prov->name }}</option>
                                        @endforeach                                        
                                    </select>
                                    @error('address_prov')
                                        <div class="invalid-feedback" role="feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label for="address_kab">Kabupaten/Kota</label>
                                    <select id="address_kab" name="address_kab" class="custom-select @if($errors->has('address_kab')) is-invalid @endif" data-toggle="select2">
                                        <option value="">Pilih Kabupaten/Kota</option>
                                    </select>
                                    @error('address_kab')
                                        <div class="invalid-feedback" role="feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label for="address_kec">Kecamatan</label>
                                    <select id="address_kec" name="address_kec" class="custom-select @if($errors->has('address_kec')) is-invalid @endif" data-toggle="select2">
                                        <option value="">Pilih Kecamatan</option>
                                    </select>
                                    @error('address_kec')
                                        <div class="invalid-feedback" role="feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                                <div class="form-row">
                                    <div class="form-group col-md-8">
                                        <label for="map" class="col-form-label pt-0">Koordinat Lokasi</label>
                                        <div class="d-block w-100 ">
                                            <div id="map" class="d-block w-100 mb-2 border rounded @if($errors->has('address_lat') || $errors->has('address_long')) border-danger @endif" style="height:300px"></div>
                                        </div>
                                        <span class="help-block">
                                            <small>Geser pin merah/ketik lokasi di kolom pencarian untuk menentukan koordinat lokasi.</small>
                                        </span>
                                        @if($errors->has('address_lat') || $errors->has('address_long'))
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
                                    <div class="form-group col-md-4">                                        
                                        <div class="form-group">
                                            <label for="address_lat">Latitude</label>
                                            <input type="text" id="address_lat" name="address_lat" class="form-control lat @if($errors->has('address_lat')) is-invalid @endif" placeholder="134" value="{{ old('address_lat') }}">
                                            @error('address_lat')
                                                <div class="invalid-feedback" role="feedback">
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                        </div>
                                        <div class="form-group">
                                            <label for="address_long">Longitude</label>
                                            <input type="text" id="address_long" name="address_long" class="form-control long @if($errors->has('address_long')) is-invalid @endif" placeholder="134" value="{{ old('address_long') }}">
                                            @error('address_long')
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
                    <div class="card-box">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="address">Gambar Assets</label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group text-right">
                                    <button type="button" class="btn btn-amdbtn btn-xs waves-effect waves-light add-image">
                                        <i class="mdi mdi-plus mr-1"></i>
                                        Tambah Gambar
                                    </button>
                                </div>
                            </div>
                            @error('image')
                                <div class="col-md-12">
                                    <div class="d-block text-danger" role="feedback">
                                        Dibutuhkan setidaknya satu Gambar Assets.
                                    </div>
                                </div>
                            @enderror
                        </div>
                        <div class="row container-image">
                            @if (!empty(old('image')))
                                @foreach (old('image') as $key=>$item)
                                    <div class="col-md-6 col-xl-3 list-images">
                                        <div class="card-box product-box p-0 bg-dark @if($errors->has('image.'.$key)) border border-danger rounded @endif">
                                            <div class="product-action">
                                                <button type="button" class="btn btn-amdbtn btn-xs waves-effect waves-light edit-image">
                                                    <i class="mdi mdi-pencil"></i>
                                                </button>
                                                <button type="button" class="btn btn-amdbtn btn-xs waves-effect waves-light delete-image">
                                                    <i class="mdi mdi-close"></i>
                                                </button>
                                            </div>
                                            <div class="assets-image" style="background-image: url({{ old('image.'.$key) }})">
                                            </div>
                                            <input type="file" name="image[]" accept="image/*" class="d-none fileimage"/>
                                        </div>
                                    </div>                                    
                                @endforeach
                            @endif
                        </div>
                    </div>
                </div>
                <div class="col-sm-3">
                    <div class="card-box">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="publish">Dipublikasikan</label>
                                    <select id="publish" name="publish" class="custom-select">
                                        <option value="0" @if (old('publish') == 0) selected @endif>Tidak</option>
                                        <option value="1" @if (old('publish') == 1) selected @endif>Ya</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="status">Status</label>
                                    <select name="status" class="custom-select">
                                        <option value="0" @if (old('status') == 0) selected @endif>OPEN</option>
                                        <option value="1" @if (old('status') == 1) selected @endif>TERJUAL</option>
                                        <option value="2" @if (old('status') == 2) selected @endif>PENDING (OPEN)</option>
                                        <option value="3" @if (old('status') == 3) selected @endif>PENDING (TERJUAL)</option>
                                        <option value="4" @if (old('status') == 4) selected @endif>REJECT</option>
                                        <option value="5" @if (old('status') == 5) selected @endif>RESTRUK</option>
                                    </select>
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
<script src="{{asset('backend/libs/jquery-mask-plugin/jquery-mask-plugin.min.js')}}"></script>
<script src="{{asset('backend/libs/select2/select2.min.js')}}"></script>
<script src="{{asset('backend/libs/flatpickr/flatpickr.min.js')}}"></script>
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDsucrEdmswqYrw0f6ej3bf4M4suDeRgNA"></script>

<script>
    $(document).ready(function(){
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $(document).on('click', '.container-image .list-images .edit-image', function(){
            var parent = $(this).closest('.list-images');
            var fileimage = $(parent).find('input[type="file"]');
            fileimage.trigger("click");
        });
        $(document).on('change', '.container-image .list-images input[type="file"]', function(){
            var parent = $(this).closest('.list-images');
            var val = window.URL.createObjectURL(this.files[0]);
            parent.find('.assets-image').css('background-image', 'url('+val+')');
        });
        $(document).on('click', '.container-image .list-images .delete-image', function(){
            $(this).closest('.list-images').remove();
        });
        $('.add-image').on('click', function(){
            var htm = '';
            htm += '<div class="col-md-6 col-xl-3 list-images">';
            htm += '<div class="card-box product-box p-0 bg-dark">';
            htm += '<div class="product-action">';
            htm += '<button type="button" class="btn btn-amdbtn btn-xs waves-effect waves-light edit-image">';
            htm += '<i class="mdi mdi-pencil"></i>';
            htm += '</button>';
            htm += '<button type="button" class="btn btn-amdbtn btn-xs waves-effect waves-light delete-image">';
            htm += '<i class="mdi mdi-close"></i>';
            htm += '</button>';
            htm += '</div>';
            htm += '<div class="assets-image" style="background-image: url({{ asset('backend/images/placeholder.png') }})">';
            htm += '</div>';
            htm += '<input type="file" name="image[]" accept="image/*" class="d-none fileimage"/>';
            htm += '</div>';
            htm += '</div>';
            $('.container-image').append(htm);
        });
        $('select[data-toggle="select2"]').select2();
        $('input[data-toggle="waktu"]').flatpickr({
            enableTime: true,
            dateFormat: "Y-m-d H:i:00"
        });
        $('[data-toggle="input-mask"]').each(function (idx, obj) {
            var maskFormat = $(obj).data("maskFormat");
            var reverse = $(obj).data("reverse");
            if (reverse != null)
                $(obj).mask(maskFormat, {'reverse': reverse});
            else
                $(obj).mask(maskFormat);
        });
        $('select[name="sold_method"]').change(function(){
            var value = $(this).val();
            if(value == 1)
            {
                $('.penjualan-langsung').removeClass('d-none');
                $('.lelang').addClass('d-none');
            }else if(value == 2){
                $('.penjualan-langsung').addClass('d-none');
                $('.lelang').removeClass('d-none');
            }else{
                $('.penjualan-langsung').addClass('d-none');
                $('.lelang').addClass('d-none');
            }
        });
        $('select[name="address_prov"]').change(function(){
            var value = $(this).val();
            if(value !== '')
            {
                var array = {
                    'from': 'prov',
                    'val': value
                };
                ajaxlocation(array, false);
            }else{
                $('select[name="address_kab"]').empty();
                $('select[name="address_kab"]').html('<option value="">Pilih Kabupaten/Kota</option>');
                $('select[name="address_kec"]').empty();
                $('select[name="address_kec"]').html('<option value="">Pilih Kecamatan</option>');
                $('select[name="address_kab"]').select2();
                $('select[name="address_kec"]').select2();
            }
        });
        $('select[name="address_kab"]').change(function(){
            var value = $(this).val();
            if(value !== '')
            {
                var array = {
                    'from': 'kab',
                    'val': value
                };
                ajaxlocation(array, false);
            }else{
                $('select[name="address_kec"]').empty();
                $('select[name="address_kec"]').html('<option value="">Pilih Kecamatan</option>');
                $('select[name="address_kec"]').select2();
            }
        });
        @if(!empty(old('address_prov')) && empty(old('address_kab')))
            var v = "{{ old('address_prov') }}";
            var arr = {
                'from': 'prov',
                'val': v
            };
            ajaxlocation(arr, false);
        @endif
        @if (!empty(old('address_kab')))
            var v = "{{ old('address_prov') }}";
            var sel = "{{ old('address_kab') }}";
            var arr = {
                'from': 'prov',
                'val': v,
                'sel': sel
            };
            ajaxlocation(arr, true);
            @if (empty(old('address_kec')))
                var arrs = {
                    'from': 'kab',
                    'val': sel
                };
                ajaxlocation(arrs, true);
            @endif
        @endif
        @if (!empty(old('address_kec')))
            var v = "{{ old('address_kab') }}";
            var sel = "{{ old('address_kec') }}";
            var arr = {
                'from': 'kab',
                'val': v,
                'sel': sel
            };
            ajaxlocation(arr, true);
        @endif
        function ajaxlocation(array, onload)
        {
            var data = {
                '_token': $('input[name="_token"]').val(),
                'val': array['val'],
                'from': array['from'],
            };
            $.ajax({
                type: 'POST',
                data: data,
                url: "{{ route('admin.'.$uri.'.ajaxlocation') }}",
                success: function(response){
                    if(response.status)
                    {
                        if(array['from'] == 'prov')
                        {
                            $('select[name="address_kab"]').empty();
                            $('select[name="address_kab"]').html('<option value="">Pilih Kabupaten/Kota</option>');
                            $('select[name="address_kec"]').empty();
                            $('select[name="address_kec"]').html('<option value="">Pilih Kecamatan</option>');
                            $('select[name="address_kab"]').select2("destroy");
                            $('select[name="address_kec"]').select2("destroy");
                            for(var i = 0; i < response.kabupaten.length;i++)
                            {
                                var htm = '<option value="'+response.kabupaten[i].code+'">'+response.kabupaten[i].name+'</option>';
                                $('select[name="address_kab"]').append(htm);
                            }
                            if(onload){
                                $('select[name="address_kab"]').val(array['sel']);
                            }
                            $('select[name="address_kab"]').select2();
                            $('select[name="address_kec"]').select2();
                        }else if(array['from'] == 'kab')
                        {
                            $('select[name="address_kec"]').empty();
                            $('select[name="address_kec"]').html('<option value="">Pilih Kecamatan</option>');
                            $('select[name="address_kec"]').select2("destroy");
                            for(var i = 0; i < response.kecamatan.length;i++)
                            {
                                var htm = '<option value="'+response.kecamatan[i].code+'">'+response.kecamatan[i].name+'</option>';
                                $('select[name="address_kec"]').append(htm);
                            }
                            if(onload){
                                $('select[name="address_kec"]').val(array['sel']);
                            }
                            $('select[name="address_kec"]').select2();
                        }
                    }else{
                        var modal = $('#danger-alert-modal');
                        modal.find('.judul').html('Error');
                        modal.find('.konten').html('Terjadi kesalahan saat memproses data');  
                    }
                },
                error: function(error){
                    var modal = $('#danger-alert-modal');
                    modal.find('.judul').html('Error');
                    modal.find('.konten').html('Terjadi kesalahan saat memproses data');                    
                }
            });
        }
        var geocoder;
        var clat = {{ (!empty(old('address_lat'))) ? old('address_lat') : -3.421464 }};
        var clong = {{ (!empty(old('address_long'))) ? old('address_long') : 112.951445 }};
        var map = new google.maps.Map(document.getElementById('map'), {
            zoom: 5,
            center: new google.maps.LatLng(clat, clong),
            mapTypeId: google.maps.MapTypeId.ROADMAP
        });
        var rlat = {{ (!empty(old('address_lat'))) ? old('address_lat') : -7.781921 }};
        var rlong = {{ (!empty(old('address_long'))) ? old('address_long') : 110.364678 }};
        var latLng = new google.maps.LatLng(rlat, rlong);
        
        var marker = new google.maps.Marker({
            position : latLng,
            title : 'lokasi',
            map : map,
            draggable : true
        });
        @if(!empty(old('address_lat')) && !empty(old('address_long')))
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
