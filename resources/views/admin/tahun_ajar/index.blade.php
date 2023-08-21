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
                        <div class="col-lg-7">
                            <div class="widget-heading">
                                <h5 class="">Tahun Ajar</h5>
                                <a href="javascript:void(0)" class="btn btn-primary btn-sm mt-3" data-toggle="modal" data-target="#tambah_tahun_ajar"><span data-feather="home"></span> Tambah</a>
                            </div>
                            <div class="table-responsive mt-3">
                                <table id="datatable-table" class="table table-bordered table-striped text-center text-nowrap">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Nama</th>
                                            <th>Periode</th>
                                            <th>Status</th>
                                            <th>Opsi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($tahun_ajar as $tahun)
                                            <tr>
                                                <td>{{ $tahun->id }}</td>
                                                <td>{{ $tahun->nama }}</td>
                                                <td>{{ $tahun->periode_awal }} s/d {{ $tahun->periode_akhir }}</td>
                                                <td>
                                                    @if ($tahun->status == 1)
                                                        Aktif
                                                    @else
                                                        Tidak Aktif
                                                    @endif
                                                </td>
                                                <td>
                                                    <a href="javascript:void(0)" data-toggle="modal" data-target="#edit_tahun" data-id_tahun="{{ $tahun->id }}" data-nama_tahun="{{ $tahun->nama }}" data-periode_awal="{{ $tahun->periode_awal }}" data-periode_akhir="{{ $tahun->periode_akhir }}" data-status="{{ $tahun->status }}"  class="btn btn-primary btn-sm edit-tahun">
                                                        <i data-feather="edit"></i>
                                                    </a>
                                                    
                                                    <a href="{{ url('/admin/hapus_tahun_ajar') }}/{{ $tahun->id }}" class="btn btn-danger btn-sm btn-hapus">
                                                        <i data-feather="x-circle"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="col-lg-5 d-flex">
                            <img src="{{ url('assets/img') }}/kelas.svg" class="align-middle" alt="" style="width: 100%;">
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
<div class="modal fade" id="tambah_tahun_ajar" tabindex="-1" role="dialog" aria-labelledby="tambah_tahun_ajarLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <form action="{{ url('/admin/tambah_tahun_ajar') }}" method="POST">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="tambah_tahun_ajarLabel">Tambah Tahun Ajar</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        x
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="nama">Nama</label>
                        <input type="text" name="nama" id="nama" required class="form-control">
                    </div>
                    
                    <div class="form-group">
                        <label for="periode_awal">Periode Awal</label>
                        <input type="date" class="form-control"  name="periode_awal" id="periode_awal" required>
                    </div>
                    <div class="form-group">
                        <label for="periode_akhir">Periode Akhir</label>
                        <input type="date" class="form-control"  name="periode_akhir" id="periode_akhir" required>
                    </div> 
                    <div class="form-group">
                        <label for="status">Status</label>
                        <select name="status" id="status" required class="form-control">
                            <option value="1">Aktif</option>
                            <option value="0">Tidak Aktif</option>
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
<!-- Modal edit -->
<div class="modal fade" id="edit_tahun" tabindex="-1" role="dialog" aria-labelledby="edit_tahunLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <form action="{{ url('/admin/edit_tahun') }}" method="POST">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="edit_tahunLabel">Edit Tahun</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        x
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="">Nama</label>
                        <input type="hidden" name="id_tahun" id="id_tahun" class="form-control">
                        <input type="text" name="nama" id="nama" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="periode_awal">Periode Awal</label>
                        <input type="text" name="periode_awal" id="periode_awal" required class="form-control datepicker">
                    </div>
                    <div class="form-group">
                        <label for="periode_akhir">Periode Akhir</label>
                        <input type="text" name="periode_akhir" id="periode_akhir" required class="form-control datepicker">
                    </div> 
                    <div class="form-group">
                        <label for="status">Status</label>
                        <select name="status" id="status" required class="form-control">
                            <option value="1">Aktif</option>
                            <option value="0">Tidak Aktif</option>
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
        $("#datatable-table").DataTable({
            scrollY: "300px",
            scrollX: !0,
            scrollCollapse: !0,
            paging: !0,
            oLanguage: {
                oPaginate: {
                    sPrevious:
                        '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-left"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>',
                    sNext:
                        '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-right"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg>',
                },
                sInfo: "tampilkan halaman _PAGE_ dari _PAGES_",
                sSearch:
                    '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-search"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>',
                sSearchPlaceholder: "Cari Data...",
                sLengthMenu: "Hasil :  _MENU_",
            },
            stripeClasses: [],
            lengthMenu: [[-1, 5, 10, 25, 50], ["All", 5, 10, 25, 50]],
        });


        $(".btn-hapus").on("click", function (e) {
            e.preventDefault();
            var t = $(this).attr("href");
            swal({
                title: "Yakin dihapus?",
                text: "Data yang berkaitan dengan tahun ajar ini juga akan dihapus!",
                type: "warning",
                showCancelButton: true,
                cancelButtonText: "Tidak",
                confirmButtonText: "Ya, hapus",
                padding: "2em",
            }).then(function (e) {
                e.value && (document.location.href = t);
            });
        });

        $('#edit_tahun').on('show.bs.modal', function(event) {
            var button = $(event.relatedTarget);
            var namaTahun = button.data('nama_tahun');
            var idTahun = button.data('id_tahun');
            var periodeAwal = button.data('periode_awal');
            var periodeAkhir = button.data('periode_akhir');
            var status = button.data('status');
            var modal = $(this);
            modal.find('#id_tahun').val(idTahun);
            modal.find('#nama').val(namaTahun);
            modal.find('#periode_awal').val(periodeAwal);
            modal.find('#periode_akhir').val(periodeAkhir);
            modal.find('#status').val(status);

        });
    

        $('.datepicker').datepicker({
            format: 'yyyy-mm-dd', // Format tanggal yang diinginkan (misal: yyyy-mm-dd)
            autoclose: true // Menutup datepicker setelah memilih tanggal
        });
    }
    )
</script>

    
@endsection