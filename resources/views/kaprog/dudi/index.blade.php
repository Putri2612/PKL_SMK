@extends('template.main')
@section('content')
@include('template.navbar.kaprog')

<!--  BEGIN CONTENT AREA  -->
<div id="content" class="main-content">
    <div class="layout-px-spacing">
        <div class="row layout-top-spacing">
            <div class="col-lg-12 layout-spacing">
                <div class="widget shadow p-3">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="widget-heading">
                                <h5 class="">DU/DI</h5>
                                <a href="javascript:void(0)" class="btn btn-primary btn-sm mt-3" data-toggle="modal" data-target="#tambah_dudi">
                                    <i data-feather="user-plus"></i> Tambah
                                </a>
                                <a href="javascript:void(0)" class="btn btn-success btn-sm mt-3" data-toggle="modal" data-target="#import_dudi">
                                    <i data-feather="file-text"></i> Impor Excel
                                </a>
                                <a href="{{ url("/kaprog/ekspor_dudi") }}" class="btn btn-warning btn-sm mt-3" target="_blank">
                                    <i data-feather="file-text"></i> Ekspor Excel
                                </a>
                            </div>
                            <div class="table-responsive mt-4">
                                <table id="datatable-table" class="table text-center text-nowrap">
                                    <thead>
                                        <tr>
                                            <th>Nama DU/DI</th>
                                            <th>No Telp</th>
                                            <th>Email</th>
                                            <th>Alamat</th>
                                            <th>Jurusan</th>
                                            <th>Opsi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php $no = 1; ?>
                                        @foreach ($dudi as $d)
                                            <tr>
                                                <td>{{  $d->nama_dudi }}</td>
                                                <td>{{  $d->no_telp }}</td>
                                                <td>{{  $d->email }}</td>
                                                <td>{{  $d->alamat }}</td>
                                                <td>{{  $d->jurusan->nama_jurusan }}</td>
                                                <td>
                                                    <a href="javascript:void(0)" data-toggle="modal" data-target="#edit_dudi" data-dudi="{{ $d->id }}" class="btn btn-primary btn-sm edit-dudi">
                                                        <i data-feather="edit"></i>
                                                    </a>
                                                    <a href="{{ url("/kaprog/hapus_dudi") }}/{{ $d->id }}" class="btn btn-danger btn-sm btn-hapus">
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
<div class="modal fade" id="tambah_dudi" tabindex="-1" role="dialog" aria-labelledby="tambah_dudiLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <form action="{{ url("kaprog/tambah_dudi") }}" method="POST">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="tambah_dudiLabel">Tambah DU/DI</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        x
                    </button>
                </div>
                <div class="modal-body">
                    <a href="javascript:void(0)" class="btn btn-success mb-3 tambah-baris-dudi">tambah baris</a>
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Nama DU/DI</th>
                                <th>No HP</th>
                                <th>Email</th>
                                <th>Alamat</th>
                                <th>Jurusan</th>
                                <th>Opsi</th>
                            </tr>
                        </thead>
                        <tbody id="tbody-dudi">
                            <tr>
                                <td><input type="text" name="nama_dudi[]" required style="border: none; background: transparent; width: 100%; height: 100%;"></td>
                                <td><input type="text" name="no_telp[]" required style="border: none; background: transparent; width: 100%; height: 100%;"></td>
                                <td><input type="text" name="email[]" required style="border: none; background: transparent; width: 100%; height: 100%;"></td>
                                <td><input type="text" name="alamat[]" required style="border: none; background: transparent; width: 100%; height: 100%;"></td>
                                <td>
                                    <select name="jurusan_id[]" required style="border: none; background: transparent; width: 100%; height: 100%;">
                                        <option value="">pilih</option>
                                        @foreach ($jurusan as $j)
                                            <option value="{{ $j->id }}">{{  $j->nama_jurusan }}</option>
                                        @endforeach
                                    </select>
                                </td>
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
<div class="modal fade" id="edit_dudi" tabindex="-1" role="dialog" aria-labelledby="edit_dudiLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <form action="{{ url("/kaprog/edit_dudi") }}" method="POST">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="edit_dudiLabel">Edit DU/DI</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        x
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="">Nama DU/DI</label>
                        <input type="hidden" name="id" id="id_dudi" value="{{ old('id') }}" class="form-control">
                        <input type="text" name="nama_dudi" id="nama_dudi" value="{{ old('nama_dudi') }}" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="">No Telp</label>
                        <input type="text" name="no_telp" id="no_telp" value="{{ old('no_telp') }}" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="">Email</label>
                        <input type="email" name="email" id="email" value="{{ old('email') }}" class="form-control" required>
                        @error('email')
                            <div class="invalid-feedback" style="display: block;">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="">Alamat</label>
                        <input type="text" name="alamat" id="alamat" value="{{ old('alamat') }}" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="">Jurusan</label>
                        <select name="jurusan_id" id="jurusan_id" class="form-control">
                            <option value="">pilih</option>
                            @foreach ($jurusan as $j)
                                <option value="{{ $j->id }}">{{  $j->nama_jurusan }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="">Active</label>
                        <select name="is_active" id="active" class="form-control">
                            <option value="1">Yes</option>
                            <option value="0">No</option>
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

<!-- Modal IMport -->
<div class="modal fade" id="import_dudi" tabindex="-1" role="dialog" aria-labelledby="import_dudiLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <form action="{{ url("/kaprog/impor_dudi") }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="import_dudiLabel">Import DU/DI Excel</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        x
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="">Download Template</label>
                        <br>
                        <a href="{{ url("/kaprog/impor_dudi") }}" target="_blank" class="btn btn-primary">
                            <svg viewBox="0 0 24 24" width="24" height="24" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round" class="css-i6dzq1">
                                <polyline points="8 17 12 21 16 17"></polyline>
                                <line x1="12" y1="12" x2="12" y2="21"></line>
                                <path d="M20.88 18.09A5 5 0 0 0 18 9h-1.26A8 8 0 1 0 3 16.29"></path>
                            </svg>
                        </a>
                    </div>
                    <div class="form-group">
                        <label for="">File Excel</label>
                        <div class="custom-file mb-4">
                            <input type="file" name="file" class="custom-file-input" id="customFile" accept=".xls, .xlsx">
                            <label class="custom-file-label" for="customFile">Choose file</label>
                        </div>
                        <p><br>note : jangan ubah bagian header di file excel</p>
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
        $('.tambah-baris-dudi').click(function() {
            const dudi = `
            <tr>
                <td><input type="text" name="nama_dudi[]" required style="border: none; background: transparent; width: 100%; height: 100%;"></td>
                <td><input type="text" name="no_telp[]" required style="border: none; background: transparent; width: 100%; height: 100%;"></td>
                <td><input type="text" name="email[]" required style="border: none; background: transparent; width: 100%; height: 100%;"></td>
                <td><input type="text" name="alamat[]" required style="border: none; background: transparent; width: 100%; height: 100%;"></td>
                <td>
                    <select name="jurusan_id[]" required style="border: none; background: transparent; width: 100%; height: 100%;">
                        <option value="">pilih</option>
                            @foreach ($jurusan as $j)
                                <option value="{{ $j->id }}">{{  $j->nama_jurusan }}</option>
                            @endforeach
                    </select>
                </td>            
                <td>
                    <button class="btn btn-danger">
                        <svg viewBox="0 0 24 24" width="24" height="24" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round" class="css-i6dzq1"><circle cx="12" cy="12" r="10"></circle><line x1="15" y1="9" x2="9" y2="15"></line><line x1="9" y1="9" x2="15" y2="15"></line></svg>
                    </button>
                </td>
            </tr>
           `;

            $('#tbody-dudi').append(dudi)
        });
        $("#tbody-dudi").on("click","tr td button",function(){$(this).parents("tr").remove()}),
        $(".edit-dudi").click(function(){var a=$(this).data("dudi");$.ajax({type:"get",data:{id:a},dataType:"json",async:!0,url:"{{ route('ajaxdudi') }}",success:function(a){$("#id_dudi").val(a.id),$("#nama_dudi").val(a.nama_dudi),$("#no_telp").val(a.no_telp),$("#email").val(a.email),$("#alamat").val(a.alamat),$("#jurusan_id").val(a.jurusan_id),$("#active").val(a.is_active)}})}),
        $(".btn-hapus").on("click",function(a){a.preventDefault();var t=$(this).attr("href");swal({title:"yakin di hapus?",text:"data yang berkaitan dengan data DU/DI ini juga akan di hapus!",type:"warning",showCancelButton:!0,cancelButtonText:"tidak",confirmButtonText:"ya, hapus",padding:"2em"}).then(function(a){a.value&&(document.location.href=t)})}),
        $(".custom-file-input").on("change",function(){var a=$(this).val().split("\\").pop();$(this).next(".custom-file-label").addClass("selected").html(a)});
    })
</script>

@if(old('email')) 
    <script>
        $('#edit_dudi').modal('show');
        $("#no_telp").val("{{ old('no_telp') }}");
        $("#alamat").val("{{ old('alamat') }}");
        $("#jurusan_id").val("{{ old('jurusan_id') }}");
        $("#active").val("{{ old('is_active') }}");
    </script>
@endif





@endsection