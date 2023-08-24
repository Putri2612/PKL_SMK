@extends('template.main')
@section('content')
@include('template.navbar.guru')

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
                                <a href="javascript:void(0)" class="btn btn-primary btn-sm mt-3" data-toggle="modal" data-target="#tambah_kunjungan">
                                    <i data-feather="user-plus"></i> Tambah
                                </a>
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
                                                    <?php if ($k->status == 2): ?>
                                                        <a href="javascript:void(0)" data-toggle="modal" data-target="#edit_kunjungan" data-kunjungan="{{ $k->id }}" class="btn btn-primary btn-sm edit-kunjungan">
                                                            <i data-feather="edit"></i>
                                                        </a>
                                                        <a href="{{ url("/kaprog/hapus_kunjungan") }}/{{ $k->id }}" class="btn btn-danger btn-sm btn-hapus">
                                                            <i data-feather="x-circle"></i>
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
<div class="modal fade" id="tambah_kunjungan" tabindex="-1" role="dialog" aria-labelledby="tambah_kunjunganLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <form action="{{ url("kaprog/tambah_kunjungan") }}" method="POST" enctype="multipart/form-data">
            @csrf
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="tambah_kunjunganLabel">Tambah Kunjungan</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        x
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="">Tanggal</label>
                        <input type="hidden" name="guru_nip" id="guru_nip" value="{{$guru->nip}}" class="form-control" required>
                        <input type="date" name="tanggal" id="tanggal"  class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="dudi_id">DU/DI</label>
                        <select name="dudi_id" id="dudi_id" class="form-control">
                            <option value="">pilih</option>
                            @foreach ($dudi as $d)
                                <option value="{{ $d->dudi_id }}">{{  $d->dudi->nama_dudi }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="">Catatan</label>
                        <textarea name="catatan" id="catatan" class="form-control" style="width: 100%;" required></textarea>
                    </div>
                    <div class="form-group">
                        <label for="foto">Foto</label>
                        <input type="file" name="foto" id="foto" class="form-control" accept="image/*">
                        <small class="form-text text-muted">Upload a photo</small>
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
<div class="modal fade" id="edit_kunjungan" tabindex="-1" role="dialog" aria-labelledby="edit_kunjunganLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <form action="{{ url("/kaprog/edit_kunjungan") }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="edit_kunjunganLabel">Edit Kunjungan</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        x
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="">Tanggal</label>
                        <input type="hidden" name="id" id="id" value="{{old('id')}}" class="form-control" required>
                        <input type="date" name="tanggal" id="edit_tanggal" value="{{ old('tanggal') }}" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="dudi_id">DU/DI</label>
                        <select name="dudi_id" id="edit_dudi_id" class="form-control">
                            <option value="">pilih</option>
                            @foreach ($dudi as $d)
                                <option value="{{ $d->dudi_id }}">{{  $d->dudi->nama_dudi }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="">Catatan</label>
                        <textarea name="catatan" id="edit_catatan" class="form-control" value="{{ old('catatan') }}" style="width: 100%;" required></textarea>
                    </div>
                    <div class="form-group">
                        <label for="edit_foto">Foto</label>
                        <input type="file" name="edit_foto" id="edit_foto" class="form-control" accept="image/*">
                        <small class="form-text text-muted">Upload a new photo (optional)</small>
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
        
        $(".edit-kunjungan").click(function(){var a=$(this).data("kunjungan");$.ajax({type:"get",data:{id:a},dataType:"json",async:!0,url:"{{ route('ajaxkunjungan') }}",success:function(a){$("#id").val(a.id),$("#edit_tanggal").val(a.tanggal),$("#edit_dudi_id").val(a.dudi_id),$("#edit_catatan").val(a.catatan)}})}),
        $(".btn-hapus").on("click",function(a){a.preventDefault();var t=$(this).attr("href");swal({title:"yakin di hapus?",text:"data yang berkaitan dengan data kunjungan ini juga akan di hapus!",type:"warning",showCancelButton:!0,cancelButtonText:"tidak",confirmButtonText:"ya, hapus",padding:"2em"}).then(function(a){a.value&&(document.location.href=t)})}),
        $(".custom-file-input").on("change",function(){var a=$(this).val().split("\\").pop();$(this).next(".custom-file-label").addClass("selected").html(a)});
    });
</script>


@endsection