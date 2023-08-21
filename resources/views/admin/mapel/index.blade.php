@extends('template.main')
@section('content')
    @include('template.navbar.admin')


    <!--  BEGIN CONTENT AREA  -->
<div id="content" class="main-content">
    <div class="layout-px-spacing">
        <div class="row layout-top-spacing">
            <div class="col-lg-12 layout-spacing">
                <div class="widget shadow p-3" style="min-height: 450px;">
                    <div class="row">
                        <div class="col-lg-7">
                            <div class="widget-heading">
                                <h5 class="">Mata Pelajaran</h5>
                                <a href="javascript:void(0)" class="btn btn-primary btn-sm mt-3" data-toggle="modal" data-target="#tambah_mapel"><span data-feather="book-open"></span> Tambah</a>
                            </div>
                            <div class="table-responsive mt-3">
                                <table id="datatable-table" class="table table-bordered table-striped text-center text-nowrap">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Mapel</th>
                                            <th>Tahun Ajar</th>
                                            <th>Opsi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($mapel as $m)
                                            <tr>
                                                <td><?= $loop->iteration; ?></td>
                                                <td>{{  $m->nama_mapel }}</td>
                                                <td>{{ $m->tahun_ajar->nama }}</td>
                                                <td>
                                                    <a href="javascript:void(0)" data-toggle="modal" data-target="#edit_mapel" data-id_mapel="{{ $m->id }}" data-nama_mapel="{{ $m->nama_mapel }}" data-tahun-ajar-id="{{ $m->tahun_ajar->id }}" class="btn btn-primary btn-sm edit-mapel">
                                                        <i data-feather="edit"></i>
                                                    </a>
                                                    <a href="{{ url('/admin/hapus_mapel') }}/{{ $m->id }}" class="btn btn-danger btn-sm btn-hapus">
                                                        <i data-feather="trash"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="col-lg-5 d-flex">
                            <img src="{{ url('assets/img') }}/mapel.svg" class="align-middle" alt="" style="width: 100%;">
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
<div class="modal fade" id="tambah_mapel" tabindex="-1" role="dialog" aria-labelledby="tambah_mapelLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <form action="{{ url('/admin/tambah_mapel') }}" method="POST">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="tambah_mapelLabel">Tambah Mapel</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        x
                    </button>
                </div>
                <div class="modal-body">
                    <a href="javascript:void(0)" class="btn btn-success mb-3 tambah-baris-mapel">tambah baris</a>
                    <table id class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Nama Mapel</th>
                                <th>Tahun Ajar</th>
                                <th>opsi</th>
                            </tr>
                        </thead>
                        <tbody id="tbody-mapel">
                            <tr>
                                <td><input type="text" name="nama_mapel[]" required style="border: none; background: transparent; width: 100%; height: 100%;"></td>
                                <td><select name="tahun_ajar_id[]" required style="border: none; background: transparent; width: 100%; height: 100%;">
                                    <option value="">pilih</option>
                                    @foreach ($tahun_ajar as $t)
                                        <option value="{{ $t->id }}">{{  $t->nama }}</option>
                                    @endforeach
                                </select></td>
                                <td></td>
                            </tr>
                        </tbody>
                    </table>
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
<div class="modal fade" id="edit_mapel" tabindex="-1" role="dialog" aria-labelledby="edit_mapelLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <form action="{{ url('/admin/edit_mapel') }}" method="POST">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="edit_mapelLabel">Edit Mapel</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        x
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="">Nama Mapel</label>
                        <input type="hidden" name="id" id="id_mapel" class="form-control">
                        <input type="text" name="nama_mapel" id="nama_mapel" required class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="">Tahun Ajaran</label>
                        <select name="tahun_ajar_id" id="tahun_ajar_id" class="form-control">
                            <option value="">pilih</option>
                            @foreach ($tahun_ajar as $ta)
                            <option value="{{ $ta->id }}" {{ $ta->id == $m->tahun_ajar->id ? 'selected' : '' }}>
                                {{  $ta->nama }}
                            </option>
                             @endforeach
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
{!! session('pesan') !!}
<script>

    $(document).ready(function() {
        // MAPEL
        $('.tambah-baris-mapel').click(function() {
            const mapel = `
                <tr>
                    <td><input type="text" name="nama_mapel[]" required style="border: none; background: transparent; width: 100%; height: 50px;"></td>
                    <td>
                                    <select name="tahun_ajar_id[]" required style="border: none; background: transparent; width: 100%; height: 100%;">
                                        <option value="">pilih</option>
                                        @foreach ($tahun_ajar as $t)
                                            <option value="{{ $t->id }}">{{  $t->nama }}</option>
                                        @endforeach
                                    </select>
                                </td>
                    <td>
                    
                    <td>
                    <button class="btn btn-danger">
                        <svg viewBox="0 0 24 24" width="24" height="24" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round" class="css-i6dzq1"><circle cx="12" cy="12" r="10"></circle><line x1="15" y1="9" x2="9" y2="15"></line><line x1="9" y1="9" x2="15" y2="15"></line></svg>
                    </button>
                    </td>
                </tr>
           `;

            $('#tbody-mapel').append(mapel)
        });
        $("#datatable-table").DataTable({scrollY:"300px",scrollX:!0,scrollCollapse:!0,paging:!0,oLanguage:{oPaginate:{sPrevious:'<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-left"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>',sNext:'<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-right"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg>'},sInfo:"tampilkan halaman _PAGE_ dari _PAGES_",sSearch:'<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-search"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>',sSearchPlaceholder:"Cari Data...",sLengthMenu:"Hasil :  _MENU_"},stripeClasses:[],lengthMenu:[[-1,5,10,25,50],["All",5,10,25,50]]}),$("#tbody-mapel").on("click","tr td button",function(){$(this).parents("tr").remove()}),
        $(".btn-hapus").on("click",function(e){e.preventDefault();var t=$(this).attr("href");swal({title:"yakin di hapus?",text:"data yang berkaitan dengan data mapel ini juga akan di hapus!",type:"warning",showCancelButton:!0,cancelButtonText:"tidak",confirmButtonText:"ya, hapus",padding:"2em"}).then(function(e){e.value&&(document.location.href=t)})});
        $('#edit_mapel').on('show.bs.modal', function(event) {
            var button = $(event.relatedTarget);
            var tahunAjarID = button.data('tahun-ajar-id');
            var namaMapel = button.data('nama_mapel');
            var idMapel = button.data('id_mapel');
            var modal = $(this);
            modal.find('#id_mapel').val(idMapel);
            modal.find('#tahun_ajar_id').val(tahunAjarID);
            modal.find('#nama_mapel').val(namaMapel);
        });
    })
</script>

    
@endsection