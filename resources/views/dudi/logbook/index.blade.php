@extends('template.main')
@section('content')
@include('template.navbar.dudi')

<!--  BEGIN CONTENT AREA  -->
<div id="content" class="main-content">
    <div class="layout-px-spacing">
        <div class="row layout-top-spacing">
            <div class="col-lg-12 layout-spacing">
                <div class="widget shadow p-3">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="widget-heading">
                                <h5 class="">Logbook</h5>
                            </div>
                            <div class="table-responsive mt-4">
                                <table id="datatable-table" class="table text-center text-nowrap">
                                    <thead>
                                        <tr>
                                            <th>Hari/Tanggal</th>
                                            <th>Nama Siswa</th>
                                            <th>Jenis Pekerjaan</th>
                                            <th>Spesifikasi</th>
                                            <th>Permasalahan</th>
                                            <th>Penanganan</th>
                                            <th>Alat dan Bahan</th>
                                            <th>Status</th>
                                            <th>Validasi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php $no = 1; ?>
                                        @foreach ($logbook as $l)
                                            <tr>
                                                <td>{{ date('l', strtotime($l->tanggal)) }} / {{  $l->tanggal }}</td>
                                                <td>{{  $l->siswa->nama_siswa }}</td>
                                                <td>{{  $l->jenis_pekerjaan }}</td>
                                                <td>{{  $l->spesifikasi }}</td>
                                                <td>{{  $l->masalah }}</td>
                                                <td>{{  $l->penanganan }}</td>
                                                <td>{{  $l->alat_bahan }}</td>
                                                <td> <?php
                                                    $statusText = "";
                                                    if ($l->status == 2) {
                                                        $statusText = "Menunggu";
                                                    } elseif ($l->status == 1) {
                                                        $statusText = "OK";
                                                    } elseif ($l->status == 0) {
                                                        $statusText = "Ditolak";
                                                    }
                                                    echo $statusText;
                                                    ?></td>
                                                <td>
                                                    <?php if ($l->status == 2): ?>
                                                    <a href="javascript:void(0)" data-toggle="modal" data-target="#edit_logbook" data-logbook="{{ $l->id_logbook }}" class="btn btn-primary btn-sm edit-logbook">
                                                        <i data-feather="edit"></i>
                                                    </a>
                                                    <?php endif; ?>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                       
                    </div>
                </div>
            </div>
        </div>
    </div>
    @include('template.footer')
</div>
<!--  END CONTENT AREA  -->

 
<!-- Modal edit -->
<div class="modal fade" id="edit_logbook" tabindex="-1" role="dialog" aria-labelledby="edit_logbookLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <form action="{{ url("/dudi/edit_logbook") }}" method="POST">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="edit_logbookLabel">Edit Logbook</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        x
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <input type="hidden" name="id_logbook" id="id_logbook" value="{{ old('id_logbook') }}" class="form-control">
                    </div>  
                    <div class="form-group">
                        <label for="">Validasi</label>
                        <select name="status" id="active" class="form-control">
                            <option value="1">OK</option>
                            <option value="0">Ditolak</option>
                            <option value="2">Menunggu</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="reset" value="reset" class="btn" data-dismiss="modal"><i class="flaticon-cancel-12"></i> Cancel</button>
                    <button type="submit" class="btn btn-primary">Save</button>
                </div>
            </div>
        </form>
    </div>
</div>


<!-- END MODAL -->

{!! session('pesan'); !!}

<script>

    $(document).ready(function() {
        $("#datatable-table").DataTable({scrollY:"300px",scrollX:!0,scrollCollapse:!0,paging:!0,oLanguage:{oPaginate:{sPrevious:'<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-left"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>',sNext:'<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-right"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg>'},sInfo:"tampilkan halaman _PAGE_ dari _PAGES_",sSearch:'<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-search"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>',sSearchPlaceholder:"Cari Data...",sLengthMenu:"Hasil :  _MENU_"},stripeClasses:[],lengthMenu:[[-1,5,10,25,50],["All",5,10,25,50]]});
        
        $(".edit-logbook").click(function(){var a=$(this).data("logbook");$.ajax({type:"get",data:{id_logbook:a},dataType:"json",async:!0,url:"{{ route('ajaxlogbook') }}",success:function(a){$("#id_logbook").val(a.id_logbook),$("#active").val(a.status)}})}),
        $(".custom-file-input").on("change",function(){var a=$(this).val().split("\\").pop();$(this).next(".custom-file-label").addClass("selected").html(a)});
    });
</script>


@endsection