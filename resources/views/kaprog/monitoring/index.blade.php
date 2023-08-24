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
                                <h5 class="">Monitoring</h5>
                                <a href="javascript:void(0)" class="btn btn-primary btn-sm mt-3" data-toggle="modal" data-target="#tambah_monitoring">
                                    <i data-feather="user-plus"></i> Tambah
                                </a>
                            </div>
                            <div class="table-responsive mt-4">
                                <table id="datatable-table" class="table text-center text-nowrap">
                                    <thead>
                                        <tr>
                                            <th>Monitoring/Tanggal</th>
                                            <th>Nama Siswa</th>
                                            <th>Catatan</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php $no = 1; ?>
                                        @foreach ($monitoring as $m)
                                            <tr>
                                                <td>{{  $m->ke }} / {{  $m->tanggal }}</td>
                                                <td>{{  $m->siswa->nama_siswa}}</td>
                                                <td>{{  $m->catatan }}</td>
                                                <td>
                                                    <a href="javascript:void(0)" data-toggle="modal" data-target="#edit_monitoring" data-monitoring="{{ $m->id }}" class="btn btn-primary btn-sm edit-monitoring">
                                                        <i data-feather="edit"></i>
                                                    </a>
                                                    <a href="{{ url("/kaprog/hapus_monitoring") }}/{{ $m->id }}" class="btn btn-danger btn-sm btn-hapus">
                                                        <i data-feather="x-circle"></i>
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
<!-- Modal Tambah -->
<div class="modal fade" id="tambah_monitoring" tabindex="-1" role="dialog" aria-labelledby="tambah_monitoringLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <form action="{{ url("kaprog/tambah_monitoring") }}" method="POST">
            @csrf
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="tambah_monitoringLabel">Tambah Monitoring</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        x
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="">Monitoring Ke-</label>
                        <input type="hidden" name="guru_nip" id="guru_nip" value="{{$guru->nip}}" class="form-control" required>
                        <input type="number" name="ke" id="ke" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="">Tanggal</label>
                        <input type="date" name="tanggal" id="tanggal"  class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="guru_id">Siswa</label>
                        <select name="siswa_nisn" id="siswa_nisn" class="form-control">
                            <option value="">pilih</option>
                            @foreach ($siswa as $s)
                                <option value="{{ $s->siswa_nisn }}">{{  $s->siswa->nama_siswa }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="">Catatan</label>
                        <textarea name="catatan" id="catatan" class="form-control" style="width: 100%;" required></textarea>
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
<div class="modal fade" id="edit_monitoring" tabindex="-1" role="dialog" aria-labelledby="edit_monitoringLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <form action="{{ url("/kaprog/edit_monitoring") }}" method="POST">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="edit_monitoringLabel">Edit Monitoring</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        x
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="">Monitoring Ke-</label>
                        <input type="hidden" name="id" id="id" value="{{old('id')}}" class="form-control" required>
                        <input type="number" name="ke" id="edit_ke" class="form-control" value="{{ old('ke') }}" required>
                    </div>
                    <div class="form-group">
                        <label for="">Tanggal</label>
                        <input type="date" name="tanggal" id="edit_tanggal" value="{{ old('tanggal') }}" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="guru_id">Siswa</label>
                        <select name="siswa_nisn" id="edit_siswa_nisn" class="form-control">
                            <option value="">pilih</option>
                            @foreach ($siswa as $s)
                                <option value="{{ $s->siswa_nisn }}">{{  $s->siswa->nama_siswa }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="">Catatan</label>
                        <textarea name="catatan" id="edit_catatan" class="form-control" value="{{ old('catatan') }}" style="width: 100%;" required></textarea>
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
        
        $(".edit-monitoring").click(function(){var a=$(this).data("monitoring");$.ajax({type:"get",data:{id:a},dataType:"json",async:!0,url:"{{ route('ajaxmonitoring') }}",success:function(a){$("#id").val(a.id),$("#edit_ke").val(a.ke),$("#edit_tanggal").val(a.tanggal),$("#edit_siswa_nisn").val(a.siswa_nisn),$("#edit_catatan").val(a.catatan)}})}),
        $(".btn-hapus").on("click",function(a){a.preventDefault();var t=$(this).attr("href");swal({title:"yakin di hapus?",text:"data yang berkaitan dengan data monitoring ini juga akan di hapus!",type:"warning",showCancelButton:!0,cancelButtonText:"tidak",confirmButtonText:"ya, hapus",padding:"2em"}).then(function(a){a.value&&(document.location.href=t)})}),
        $(".custom-file-input").on("change",function(){var a=$(this).val().split("\\").pop();$(this).next(".custom-file-label").addClass("selected").html(a)});
    });
</script>


@endsection