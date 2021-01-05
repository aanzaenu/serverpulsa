@extends('backend.layout.app')
@section('css')
    <link href="{{asset_url('backend/libs/animate.css/animate.css.min.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{asset_url('backend/libs/bootstrap-datepicker/bootstrap-datepicker.min.css')}}" rel="stylesheet" type="text/css" />
@endsection
@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="page-title-box">
                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="{{ route('admin.home') }}">{{ env('APP_NAME', 'Laravel') }}</a></li>
                            <li class="breadcrumb-item active">{{ $pagetitle }}</li>
                        </ol>
                    </div>
                    <h4 class="page-title">{{ $pagetitle }}</h4>
                </div>
            </div>
        </div>
        @include('backend.layout.allert')
        <div class="row">
            <div class="col-sm-12">
                <div class="card-box">
                    <div class="d-block w-100 position-relative overflow-hidden">
                        <div class="d-block text-center">
                            <marquee>
                                <h2> Perhatian !! Harap cocokan serial number untuk proses deposit. hati2 member yg claim double deposit</h2>
                            </marquee>
                        </div>
                        <div class="d-block text-center">
                            <h4>Pulsa Sekarang: Rp.{{ number_format($saldo->value) }}</h4>
                        </div>
                        <div class="d-block text-center">
                            @php
                                $last = strtotime($lastupdate->value);
                                $lastupdates = date('d M Y h:i', $last);
                            @endphp
                            <h6>Last Update: {{ $lastupdates }}</h6>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-12">
                <div class="card-box">
                    <div class="d-block w-100 mb-1">
                        <div class="row">
                            <div class="col-12">
                                <form class="" method="GET" action="{{ route('admin.'.$uri.'.search') }}">
                                    <div class="row">
                                        <div class="col-lg-3">
                                        </div>
                                        <div class="col-lg-9 mb-3">
                                            <div class="input-group">
                                                <input type="text" name="query" class="form-control" placeholder="Cari Sesuatu" value="{{ request()->get('query') }}"/>
                                                <input type="text" name="from" placeholder="Dari tanggal" class="form-control" data-provide="datepicker" value="{{ request()->get('from') }}">
                                                <input type="text" name="to" placeholder="Sampai tanggal" class="form-control" data-provide="datepicker" value="{{ request()->get('to') }}">
                                                @if (is_admin() || is_subadmin())
                                                    <select name="operator" class="custom-select">
                                                        <option value="">Semua Operator</option>
                                                        @foreach ($users as $user)
                                                            <option value="{{ $user->id }}" {{ request()->get('operator') == $user->id ? 'selected' : '' }}>{{ $user->name }}</option>
                                                        @endforeach
                                                    </select>
                                                    <select name="terminal" class="custom-select">
                                                        <option value="">Semua Terminal</option>
                                                        @foreach ($terminals as $terminal)
                                                            <option value="{{ $terminal->terminal_id }}" {{ request()->get('terminal') == $terminal->terminal_id ? 'selected' : '' }}>{{ $terminal->name }}</option>
                                                        @endforeach
                                                    </select>
                                                @endif
                                                <div class="input-group-append">
                                                    <button class="btn btn-amdbtn waves-effect waves-light" type="submit">Cari/Filter</button>
                                                    <button type="button" class="btn btn-dark waves-effect waves-light download">Download</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    @if($lists->total() > 0)
                    <div class="table-responsive">
                        <table class="table mytable table-hover mb-0">
                            <thead>
                                <tr>                                   
                                    <?php 
                                        $uris = url()->current();
                                        $order_by = request()->get('orderby');
                                        $order = request()->get('order');
                                        $urut = 'asc';
                                        if(!empty($order))
                                        {
                                            if($order == 'asc')
                                            {
                                                $urut = 'desc';
                                            }else{
                                                $urut = 'asc';
                                            }
                                        }
                                        $kueri = '';
                                        if(!empty(request()->get('query')))
                                        {
                                            $kueri = 'query='.request()->get('query').'&';
                                        }
                                        $from = '';
                                        if(!empty(request()->get('to')))
                                        {
                                            $from = 'from='.request()->get('from').'&';
                                        }
                                        $to = '';
                                        if(!empty(request()->get('to')))
                                        {
                                            $to = 'to='.request()->get('to').'&';
                                        }
                                        $operator = '';
                                        if(!empty(request()->get('operator')))
                                        {
                                            $operator = 'operator='.request()->get('operator').'&';
                                        }
                                        $terminal = '';
                                        if(!empty(request()->get('terminal')))
                                        {
                                            $terminal = 'terminal='.request()->get('terminal').'&';
                                        }
                                    ;?>
                                    <th class="sorting @if($order_by =='code') @if($order == 'asc') sorting_asc @else sorting_desc @endif @endif">
                                        <a class="text-dark" href="{{ route('admin.'.$uri.'.search').'?'.$kueri.$from.$to.$operator.$terminal.'&orderby=code&order='.$urut }}">
                                            Kode
                                        </a>
                                    </th>
                                    <th class="sorting @if($order_by =='sender') @if($order == 'asc') sorting_asc @else sorting_desc @endif @endif">
                                        <a class="text-dark" href="{{ route('admin.'.$uri.'.search').'?'.$kueri.$from.$to.$operator.$terminal.'orderby=sender&order='.$urut }}">
                                            Pengirim
                                        </a>
                                    </th>
                                    <th>
                                        Pesan
                                    </th>
                                    <th class="sorting @if($order_by =='status') @if($order == 'asc') sorting_asc @else sorting_desc @endif @endif">
                                        <a class="text-dark" href="{{ route('admin.'.$uri.'.search').'?'.$kueri.$from.$to.$operator.$terminal.'orderby=status&order='.$urut }}">
                                            Status
                                        </a>
                                    </th>
                                    <th>
                                        Operator
                                    </th>
                                    <th>Terminal</th>
                                    <th class="sorting @if($order_by =='tanggal') @if($order == 'asc') sorting_asc @else sorting_desc @endif @endif">
                                        <a class="text-dark" href="{{ route('admin.'.$uri.'.search').'?'.$kueri.$from.$to.$operator.$terminal.'orderby=tanggal&order='.$urut }}">
                                            Waktu
                                        </a>
                                    </th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($lists as $key=>$list)
                                <tr class="rows-{{ $list->id }}">                                
                                    <td>{{ $list->code }}</td>
                                    <td>{{ $list->sender }}</td>
                                    <td>{{ $list->message }}</td>
                                    <td class="text-center">
                                        @if ($list->status == 1)
                                            <span class="rounded px-2 badge badge-success" style="white-space: nowrap">
                                                Done
                                            </span>
                                        @else
                                            <span class="rounded px-2 badge-sm btn-danger" style="white-space: nowrap">
                                                Belum Diproses
                                            </span>
                                        @endif
                                    </td>
                                    <td>{{ $list->operator }}</td>
                                    <td>{{ $list->terminal }}</td>
                                    <td>{{ $list->tanggal }}</td>
                                    <td>
                                        <button type="button" class="btn btn-{{ $list->image && $list->status == 1 ? 'success' : 'primary' }} btn-amdbtn btn-sm upload" data-id="{{ $list->id }}" data-code="{{ $list->code }}" data-status="{{ $list->status }}" data-image="{{ $list->image ? asset_url($list->image) : '' }}">
                                            {{ $list->image && $list->status == 1 ? 'Detail' : 'Edit' }}
                                        </button>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @else
                    <div class="d-block text-center">
                        <h3>Data tidak ditemukan</h3>
                    </div>
                    @endif
                </div>
            </div>
        </div>
        @if($lists->total() > 0)
        <div class="row">
            <div class="col-sm-12">
                {{ $lists->withQueryString()->links() }}
            </div>
        </div>
        @endif
        <div class="row">
            <div class="col-12">
                <div class="card-box">
                    <div class="d-block w-100">
                        <h5>Total : {{ number_format($lists->total()) }} {{ $lists->total() > 1 ? 'Reports' : 'Report' }}</h5>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @include('backend.layout.ajaxmodal')
    <div class="modal fade" id="upload" tabindex="-1" role="dialog" aria-labelledby="uploadLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
          <div class="modal-content">
              <form method="POST" action="{{ route('inbox.apdet') }}" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="id" value="0"/>
                <div class="modal-header">
                  <h5 class="modal-title d-block w-100 text-center" id="uploadLabel">Modal title</h5>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>Status</label>
                        <select name="status" class="custom-select">
                            <option value="0">Belum diproses</option>
                            <option value="1">Done</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Bukti</label>
                        <div class="custom-file fbukti">
                            <input type="file" name="file" class="custom-file-input" id="customFile">
                            <label class="custom-file-label" for="customFile">Choose file</label>
                        </div>
                        <div class="bukti d-noe"></div>
                    </div>
                </div>
                <div class="modal-footer">
                  <button type="button" class="btn btn-secondary btn-sm tutup">Close</button>
                  <button type="submit" class="btn btn-primary btn-sm update">Update</button>
                </div>
            </form>
          </div>
        </div>
    </div>
    <div class="modal fade" id="download" tabindex="-1" role="dialog" aria-labelledby="downloadLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
          <div class="modal-content">
              <form method="POST" action="{{ route('inbox.unduh') }}" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="id" value="0"/>
                <div class="modal-header">
                  <h5 class="modal-title d-block w-100 text-center" id="downloadLabel">Download</h5>
                </div>
                <div class="modal-body">
                    @if (is_admin() || is_subadmin())                        
                        <div class="form-group">
                            <label>User</label>
                            <select name="op" class="custom-select">
                                <option value="">Semua User</option>
                                @foreach ($users as $user)
                                    <option value="{{ $user->id }}">{{ $user->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Terminal</label>
                            <select name="terminal" class="custom-select">
                                <option value="">Semua Terminal</option>
                                @foreach ($terminals as $terminal)
                                    <option value="{{ $terminal->terminal_id }}">{{ $terminal->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    @else
                        <input type="hidden" name="op" value="{{ Auth::user()->id }}"/>
                        <input type="hidden" name="terminal" value="{{ Auth::user()->terminal }}"/>
                    @endif
                    <div class="form-row">
                        <div class="col">
                            <input type="text" name="from" placeholder="Dari tanggal" class="form-control" data-provide="datepicker" />
                            <small id="emailHelp" class="form-text text-muted">Boleh dikosongkan</small>
                        </div>
                        <div class="col">
                            <input type="text" name="to" placeholder="Sampai tanggal" class="form-control" data-provide="datepicker" />
                            <small id="emailHelp" class="form-text text-muted">Boleh dikosongkan</small>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                  <button type="button" class="btn btn-secondary btn-sm tutup">Close</button>
                  <button type="submit" class="btn btn-primary btn-sm download">Download</button>
                </div>
            </form>
          </div>
        </div>
    </div>
@endsection
@section('script')
    <script src="{{asset_url('backend/libs/bootstrap-datepicker/bootstrap-datepicker.min.js')}}"></script>
    <script type="application/javascript">
        $(window).on('load', function(){
            var onModal = false;
            setInterval(function(){
                if(!onModal)
                {
                    window.location.reload();
                }
            }, 60000);
            $('.upload').on('click', function(){
                onModal = true;
                var id = $(this).data('id');
                var code = $(this).data('code');
                var status = $(this).data('status');
                var image = $(this).data('image');
                $('select[name="status"]').val(status);
                $('input[name="id"]').val(id);
                <?php  if(is_cs()){;?>
                    if(status == 1)
                    {
                        $('select[name="status"]').attr('disabled', true);
                    }else{
                        $('select[name="status"]').removeAttr('disabled');
                    }
                    <?php }
                ;?>
                if(!image)
                {
                    $('.fbukti').removeClass('d-none');
                    $('.bukti').addClass('d-none');
                    $('.bukti').html('');
                }else{
                    $('.fbukti').addClass('d-none');
                    $('.bukti').removeClass('d-none');
                    var img = '<a href="'+image+'" target="_blank"><img src="'+image+'" class="d-block w-100"/></a>';
                    $('.bukti').html(img);
                }
                if(status == 1 && image)
                {
                    $('.update').addClass('d-none');
                }else{
                    $('.update').removeClass('d-none');
                }
                $('#uploadLabel').html('#'+ code);
                $('#upload').modal({
                    backdrop: 'static'
                });
            });
            $('.tutup').on('click', function(){
                $('#upload').modal('hide');
                $('#download').modal('hide');
                onModal = false;
            });
            $(".custom-file-input").on("change", function() {
                var fileName = $(this).val().split("\\").pop();
                $(this).siblings(".custom-file-label").addClass("selected").html(fileName);
            });
            $('.download').on('click', function(){
                onModal = true;
                $('#download').modal({
                    backdrop: 'static'
                });
            });
        });
    </script>
@endsection
