@extends('template.main')
@section('content')
@include('template.navbar.admin')

<!--  BEGIN CONTENT AREA  -->
<div id="content" class="main-content">
    <div class="layout-px-spacing">
        <div class="row layout-top-spacing">
            <div class="col-lg-12 layout-spacing">
                <div class="widget shadow p-3">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="widget-heading">
                                <h5 class="">Kegiatan Kunjungan</h5>
                            </div>
                            <div class="table-responsive mt-4">
                                <table id="datatable-table" class="table text-center text-nowrap">
                                    <thead>
                                        <tr>
                                            <th>Hari/Tanggal</th>
                                            <th>DU/DI</th>
                                            <th>Catatan Kegiatan</th>
                                            <th>Foto</th>
                                            <th>Status</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php $no = 1; ?>
                                        @foreach ($kunjungan as $k)
                                            <tr>
                                                <td>{{ date('l', strtotime($k->tanggal)) }}  / {{  $k->tanggal }}</td>
                                                <td>{{  $k->dudi->nama_dudi}}</td>
                                                <td>{{  $k->catatan }}</td>
                                                <td>
                                                    @if($k->foto)
                                                    <img src="{{ asset($k->foto) }}" alt="Foto Kunjungan" width="50">
                                                    @else
                                                        Tidak ada foto
                                                    @endif
                                                </td>
                                                <td> <?php
                                                    $statusText = "";
                                                    if ($k->status == 2) {
                                                        $statusText = "Menunggu";
                                                    } elseif ($k->status == 1) {
                                                        $statusText = "OK";
                                                    } elseif ($k->status == 0) {
                                                        $statusText = "Ditolak";
                                                    }
                                                    echo $statusText;
                                                    ?></td>
                                                <td>
                                                        <a href="javascript:void(0)" data-toggle="modal" data-target="#edit_kunjungan" data-kunjungan="{{ $k->id }}" class="btn btn-primary btn-sm edit-kunjungan">
                                                            <i data-feather="edit"></i>
                                                        </a>                                                    

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

<!-- Modal edit -->
<div class="modal fade" id="edit_kunjungan" tabindex="-1" role="dialog" aria-labelledby="edit_kunjunganLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <form action="{{ url("/admin/edit_kunjungan") }}" method="POST">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="edit_kunjunganLabel">Edit Logbook</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        x
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <input type="hidden" name="id" id="id" value="{{ old('id') }}" class="form-control">
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
        
        //$(".edit-kunjungan").click(function(){var a=$(this).data("kunjungan");$.ajax({type:"get",data:{id:a},dataType:"json",async:!0,url:"{{ route('ajaxkunjungan') }}",success:function(a){$("#id").val(a.id),$("#active").val(a.status)}})}),
        $(".edit-kunjungan").click(function() {
        var a = $(this).data("kunjungan");
        $.ajax({
            type: "get",
            data: { id: a },
            dataType: "json",
            async: true,
            url: "/admin/edit_kunjungan", // Perbarui URL sesuai dengan route yang benar
            success: function(a) {
                $("#id").val(a.id);
                $("#active").val(a.status);
            }
        });
    });

        $(".custom-file-input").on("change",function(){var a=$(this).val().split("\\").pop();$(this).next(".custom-file-label").addClass("selected").html(a)});
    });
</script>


@endsection