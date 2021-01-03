@extends('backend.layout.app')
@section('css')
    <link href="{{asset_url('backend/libs/select2/select2.min.css')}}" rel="stylesheet" type="text/css" />
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
        <form method="POST" action="{{ route('admin.'.$uri.'.store') }}">
            <div class="row">
                <div class="col-sm-12">
                    <div class="card-box">
                        <div class="row">
                            <div class="col-md-12">
                                @csrf
                                @method('POST')
                                <div class="form-group">
                                    <label for="name">Nama</label>
                                    <input type="text" class="form-control @if($errors->has('name')) is-invalid @endif" id="name"  name="name"  placeholder="Rumah" value="{{ old('name') }}">                                    
                                    @error('name')
                                        <div class="invalid-feedback" role="feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label for="status">Status</label>
                                    <select id="statuss" name="status" class="form-control @if($errors->has('status')) is-invalid @endif"  data-toggle="select2">
                                        <option value="0">Non Aktif</option>
                                        <option value="1" @if (old('status') == 1) selected @endif>Aktif</option>
                                    </select>
                                    @error('status')
                                        <div class="invalid-feedback" role="feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label for="description">Deskripsi</label>
                                    <textarea class="form-control @if($errors->has('description')) is-invalid @endif" id="description"  name="description"  placeholder="Berisi tentang listing rumah" rows="3">{{ old('description') }}</textarea>
                                    @error('description')
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
<script src="{{asset_url('backend/libs/select2/select2.min.js')}}"></script>
<script>
    $(document).ready(function(){
        $('select[data-toggle="select2"]').select2();
    });
</script>
@endsection
