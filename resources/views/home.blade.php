@extends('layouts.app')

@section('content')
<div class="px-4">
    @include('layouts.allert')
</div>
<div class="table-responsive px-4">
    @if ($lists->total() > 0)        
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th scope="col">Kode</th>
                    <th scope="col">Pengirim</th>
                    <th scope="col">Pesan</th>
                    <th scope="col">Status</th>
                    <th scope="col">Operator</th>
                    <th scope="col">Waktu</th>
                    <th scope="col">Tool</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($lists as $list)
                    <tr>
                        <th scope="row">{{ $list->code }}</th>
                        <td>{{ $list->sender }}</td>
                        <td>{{ $list->message }}</td>
                        <td>
                            @if ($list->status == 0)
                                <span class="badge badge-danger">Belum diproses</span>
                            @else
                                <span class="badge badge-success">Done</span>                                
                            @endif
                        </td>
                        <td>{{ $list->operator }}</td>
                        <td>{{ $list->tanggal }}</td>
                        <td>
                            <button type="button" class="btn btn-{{ $list->image && $list->status == 1 ? 'success' : 'primary' }} btn-sm upload" data-id="{{ $list->id }}" data-code="{{ $list->code }}" data-status="{{ $list->status }}" data-image="{{ $list->image ? asset($list->image) : '' }}">{{ $list->image && $list->status == 1 ? 'Detail' : 'Edit' }}</button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <div class="d-block w-100 text-center">
            <h4 class="font-weight-bold">No Data</h4>
        </div>
    @endif
</div>
@if ($lists->total() > 0)
<div class="d-block w-100 px-4">
    {{ $lists->withQueryString()->links() }}
</div>    
@endif
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
@endsection
@section('script')
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js" integrity="sha512-bLT0Qm9VnAYZDflyKcBaQ2gg0hSYNQrJ8RilYldYQ1FxQYoCLtUjuuRuZo+fjqhx/qtq/1itJ0C2ejDxltZVFg==" crossorigin="anonymous"></script>
    <script>
        $(window).on('load', function(){
            var onUpload = false;
            setInterval(function(){
                if(!onUpload)
                {
                    window.location.reload();
                }
            }, 60000);
            $('.upload').on('click', function(){
                onUpload = true;
                var id = $(this).data('id');
                var code = $(this).data('code');
                var status = $(this).data('status');
                var image = $(this).data('image');
                $('select[name="status"]').val(status);
                $('input[name="id"]').val(id);
                if(status == 1)
                {
                    $('select[name="status"]').attr('disabled', true);
                }else{
                    $('select[name="status"]').removeAttr('disabled');
                }
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
                onUpload = false;
            });
            $(".custom-file-input").on("change", function() {
                var fileName = $(this).val().split("\\").pop();
                $(this).siblings(".custom-file-label").addClass("selected").html(fileName);
            });
        });
    </script>
@endsection
