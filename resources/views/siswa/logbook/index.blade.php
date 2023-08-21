@extends('template.main')
@section('content')
@include('template.navbar.siswa')

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
                                <a href="javascript:void(0)" class="btn btn-primary btn-sm mt-3" data-toggle="modal" data-target="#tambah_logbook">
                                    <i data-feather="user-plus"></i> Tambah
                                </a>
                            </div>
                            <div class="table-responsive mt-4">
                                <table id="datatable-table" class="table text-center text-nowrap">
                                    <thead>
                                        <tr>
                                            <th>Hari/Tanggal</th>
                                            <th>Jenis Pekerjaan</th>
                                            <th>Spesifikasi</th>
                                            <th>Permasalahan</th>
                                            <th>Penanganan</th>
                                            <th>Alat dan Bahan</th>
                                            <th>Status</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php $no = 1; ?>
                                        @foreach ($logbook as $l)
                                            <tr>
                                                <td>{{ date('l', strtotime($l->tanggal)) }} / {{  $l->tanggal }}</td>
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

<!-- MODAL -->
<!-- Modal Tambah -->
<div class="modal fade" id="tambah_logbook" tabindex="-1" role="dialog" aria-labelledby="tambah_logbookLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <form action="{{ url("siswa/tambah_logbook") }}" method="POST">
            @csrf
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="tambah_logbookLabel">Tambah Logbook</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        x
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="">Tanggal</label>
                        <input type="date" name="tanggal" id="tanggal"  class="form-control" required>
                        <input type="hidden" name="siswa_nisn" id="siswa_nisn" value="{{$siswa->nisn}}" class="form-control" required>
                        <input type="hidden" name="id_kelompok" id="id_kelompok" value="{{$kelompok->id_kelompok}}" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="">Jenis Pekerjaan</label>
                        <input type="text" name="jenis_pekerjaan" id="jenis_pekerjaan" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="">Spesifikasi</label>
                        <input type="text" name="spesifikasi" id="spesifikasi"  class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="">Permasalahan</label>
                        <input type="text" name="masalah" id="masalah" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="">Penanganan</label>
                        <input type="text" name="penanganan" id="penanganan" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="">Alat dan Bahan</label>
                        <input type="text" name="alat_bahan" id="alat_bahan" class="form-control" required>
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
 
<!-- Modal edit -->
<div class="modal fade" id="edit_logbook" tabindex="-1" role="dialog" aria-labelledby="edit_logbookLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <form action="{{ url("/siswa/edit_logbook") }}" method="POST">
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
                        <label for="">Tanggal</label>
                        <input type="hidden" name="id_logbook" id="id_logbook" value="{{ old('id_logbook') }}" class="form-control">
                        <input type="date" name="tanggal" id="edit_tanggal" value="{{ old('tanggal') }}" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="">Jenis Pekerjaan</label>
                        <input type="text" name="jenis_pekerjaan" id="edit_jenis_pekerjaan" value="{{ old('jenis_pekerjaan') }}" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="">Spesifikasi</label>
                        <input type="text" name="spesifikasi" id="edit_spesifikasi" value="{{ old('spesifikasi') }}" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="">Permasalahan</label>
                        <input type="text" name="masalah" id="edit_masalah" value="{{ old('masalah') }}" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="">Penanganan</label>
                        <input type="text" name="penanganan" id="edit_penanganan" value="{{ old('penanganan') }}" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="">Alat dan Bahan</label>
                        <input type="text" name="alat_bahan" id="edit_alat_bahan" value="{{ old('alat_bahan') }}" class="form-control" required>
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
        
        $(".edit-logbook").click(function(){var a=$(this).data("logbook");$.ajax({type:"get",data:{id_logbook:a},dataType:"json",async:!0,url:"{{ route('ajaxlogbook') }}",success:function(a){$("#id_logbook").val(a.id_logbook),$("#edit_tanggal").val(a.tanggal),$("#edit_jenis_pekerjaan").val(a.jenis_pekerjaan),$("#edit_spesifikasi").val(a.spesifikasi),$("#edit_masalah").val(a.masalah),$("#edit_penanganan").val(a.penanganan),$("#edit_alat_bahan").val(a.alat_bahan)}})}),
        $(".custom-file-input").on("change",function(){var a=$(this).val().split("\\").pop();$(this).next(".custom-file-label").addClass("selected").html(a)});
    });
</script>


@endsection