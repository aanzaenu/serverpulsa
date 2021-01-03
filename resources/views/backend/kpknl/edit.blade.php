@extends('backend.layout.app')
@section('css')
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
        <form method="POST" action="{{ route('admin.'.$uri.'.update', $row) }}">
            <div class="row">
                <div class="col-sm-12">
                    <div class="card-box">
                        <div class="row">
                            <div class="col-md-12">
                                @csrf
                                @method('PUT')
                                <div class="form-group">
                                    <label for="name">Nama</label>
                                    <input type="text" class="form-control @if($errors->has('name')) is-invalid @endif" id="name"  name="name"  placeholder="KPKNL Yogyakarta" value="{{ $row->name }}">                                    
                                    @error('name')
                                        <div class="invalid-feedback" role="feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                                <div class="form-row">
                                    <div class="form-group col-md-6">
                                        <label for="djkn_name" class="col-form-label pt-0">Nama DJKN</label>
                                        <input type="text" class="form-control @if($errors->has('djkn_name')) is-invalid @endif" id="djkn_name"  name="djkn_name"  placeholder="DJKN Yogyakarta" value="{{ $row->djkn_name }}">                                    
                                        @error('djkn_name')
                                            <div class="invalid-feedback" role="feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label for="djkn_id" class="col-form-label pt-0">ID DJKN</label>
                                        <input type="text" class="form-control @if($errors->has('djkn_id')) is-invalid @endif" id="djkn_id"  name="djkn_id"  placeholder="2" value="{{ $row->djkn_id }}">                                    
                                        @error('djkn_id')
                                            <div class="invalid-feedback" role="feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="phone">Nomor Telepon</label>
                                    <input type="text" class="form-control @if($errors->has('phone')) is-invalid @endif" id="phone"  name="phone"  placeholder="08562919141" value="{{ $row->phone }}">                                    
                                    @error('phone')
                                        <div class="invalid-feedback" role="feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label for="fax">Fax</label>
                                    <input type="text" class="form-control @if($errors->has('fax')) is-invalid @endif" id="fax"  name="fax"  placeholder="08562919141" value="{{ $row->fax }}">                                    
                                    @error('fax')
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
                        <button type="submit" class="btn btn-amdbtn waves-effect waves-light">Simpan</button>
                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection
@section('script')
@endsection
