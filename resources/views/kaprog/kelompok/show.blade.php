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
                                <h5 class="">Kelompok PKL - {{ $tahun_ajar -> nama }}</h5>
                            </div>
                                {{-- <a href="{{ url("/kaprog/ekspor_kelompok") }}" class="btn btn-warning btn-sm mt-3" target="_blank">
                                    <i data-feather="file-text"></i> Ekspor Excel
                                </a> --}}
                            </div>
                            <div class="table-responsive mt-4">
                                <table id="datatable-table" class="table text-nowrap">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>DUDI</th>
                                            <th>Kelompok Siswa</th>
                                            <th>Guru Pembimbing</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    @php $no = 1; @endphp
                                    @foreach ($kelompokData as $kelompok)
                                        <tr>
                                            <td>{{ $no++ }}</td>
                                            <td>{{ $kelompok->dudi->nama_dudi }} - {{ $kelompok->dudi->alamat}} </td>
                                            <td>
                                                <ul>
                                                    @foreach ($kelompok->siswa as $siswa)
                                                        <li>{{ $siswa->siswa_nisn }} - {{ $siswa->siswa->nama_siswa }}</li>
                                                    @endforeach
                                                </ul>
                                            </td>
                                            <td>
                                                @if ($kelompok->guru)
                                                    {{ $kelompok->guru->nama_guru }}
                                                @else
                                                    Belum ada guru pembimbing
                                                @endif
                                            </td>
                                            <td>
                                                <a href="javascript:void(0)" data-toggle="modal" data-target="#edit_kelompok" data-kelompok="{{ $kelompok->id_kelompok }}" class="btn btn-primary btn-sm edit-kelompok">
                                                    <i data-feather="edit"></i> Edit Guru Pembimbing
                                                </a>                                                                                                                                           
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                </table>
                            </div>
                        </div>
                        <a href="{{ url("/kaprog/kelompok") }}" class="btn btn-primary mt-3">Kembali</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @include('template.footer')
</div>
<!--  END CONTENT AREA  -->


<!-- Modal edit -->
<div class="modal fade" id="edit_kelompok" tabindex="-1" role="dialog" aria-labelledby="edit_kelompokLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <form action="{{ url("/kaprog/edit_kelompok") }}" method="POST">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="edit_kelompokLabel">Edit Guru Pembimbing</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    
                    <!-- Form fields for editing guru pembimbing -->
                    <div class="form-group">
                        <label for="guru_id">Pilih Guru Pembimbing:</label>
                        <input type="hidden" name="id_kelompok" id="id_kelompok" value="{{ old('id_kelompok') }}" class="form-control">
                        <select name="guru_nip" id="guru_nip" class="form-control">
                            <option value="">pilih</option>
                            @foreach ($guruList as $g)
                                <option value="{{ $g->nip }}">{{  $g->nama_guru }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save changes</button>
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
       
        $("#tbody-dudi").on("click","tr td button",function(){$(this).parents("tr").remove()}),
        $(".edit-kelompok").click(function(){var a=$(this).data("kelompok");$.ajax({type:"get",data:{id_kelompok:a},dataType:"json",async:!0,url:"{{ route('ajaxkelompok') }}",success:function(a){$("#id_kelompok").val(a.id_kelompok),$("#guru_nip").val(a.guru_nip)}})}),
        // $(".btn-hapus").on("click",function(a){a.preventDefault();var t=$(this).attr("href");swal({title:"yakin di hapus?",text:"data yang berkaitan dengan data DU/DI ini juga akan di hapus!",type:"warning",showCancelButton:!0,cancelButtonText:"tidak",confirmButtonText:"ya, hapus",padding:"2em"}).then(function(a){a.value&&(document.location.href=t)})}),
        $(".custom-file-input").on("change",function(){var a=$(this).val().split("\\").pop();$(this).next(".custom-file-label").addClass("selected").html(a)});
    })
</script>





@endsection