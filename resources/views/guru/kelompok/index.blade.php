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
                                <h5 class=""> Daftar Kelompok PKL</h5>
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
                                            <th>Tahun Ajaran</th>
                                            
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
                                                {{ $kelompok->tahun_ajar->nama }}
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


<script>

    $(document).ready(function() {
        $("#datatable-table").DataTable({scrollY:"300px",scrollX:!0,scrollCollapse:!0,paging:!0,oLanguage:{oPaginate:{sPrevious:'<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-left"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>',sNext:'<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-right"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg>'},sInfo:"tampilkan halaman _PAGE_ dari _PAGES_",sSearch:'<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-search"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>',sSearchPlaceholder:"Cari Data...",sLengthMenu:"Hasil :  _MENU_"},stripeClasses:[],lengthMenu:[[-1,5,10,25,50],["All",5,10,25,50]]});
       
        $("#tbody-dudi").on("click","tr td button",function(){$(this).parents("tr").remove()}),
        $(".custom-file-input").on("change",function(){var a=$(this).val().split("\\").pop();$(this).next(".custom-file-label").addClass("selected").html(a)});
    })
</script>





@endsection