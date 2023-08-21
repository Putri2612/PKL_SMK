@extends('template.main')
@section('content')
    @include('template.navbar.guru')


    <!--  BEGIN CONTENT AREA  -->
    <div id="content" class="main-content">
        <div class="layout-px-spacing">
            <div class="row layout-top-spacing">
                <div class="col-lg-12 layout-spacing">
                    <div class="widget shadow p-3" style="min-height: 500px;">
                        <div class="row">
                            <div class="col-lg-7">
                                <div class="widget-heading">
                                    @foreach ($tahun_ajar as $nama_tahun_ajar)
                                        <h5 class="">Materi - {{ $nama_tahun_ajar }}</h5>
                                    @endforeach
                                    {{-- <a href="{{ url('/guru/materi/create') }}" class="btn btn-primary btn-sm mt-3"><span
                                        data-feather="book-open"></span> Tambah Materi</a> --}}
                                </div>
                                <div class="table-responsive mt-3">
                                    <table id="datatable-table" class="table text-center text-nowrap">
                                        <thead>
                                            <tr>
                                                <th>Mata Pelajaran</th>
                                                <th>Opsi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            {{-- @foreach ($materi as $m)
                                                <tr>
                                                    <td>{{ $m->mapel->nama_mapel }}</td>
                                                    <td>
                                                        <a href="{{ url('/guru/materi_select/' . $m->mapel_id) }}"
                                                            class="btn btn-primary btn-sm">Pilih</a>
                                                    </td>
                                                </tr>
                                            @endforeach --}}
                                            @foreach ($guru_mapel as $gm)
                                            <tr>
                                                <td>{{ $gm->mapel->nama_mapel }}</td>
                                                    <td>
                                                        <a href="{{ url('/guru/materi_select/' . $gm->mapel_id) }}"
                                                            class="btn btn-primary btn-sm">Pilih</a>
                                                    </td>
                                                </tr>
                                            @endforeach
                                            
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="col-lg-5 d-flex">
                                <img src="{{ url('assets/img') }}/materi.svg" class="align-middle" alt=""
                                    style="width: 100%;">
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
        $(document).ready(function(){$(".btn-hapus").on("click",function(e){var t=$(this);e.preventDefault(),swal({title:"yakin di hapus?",text:"data yang tidak bisa di kembalikan!",type:"warning",showCancelButton:!0,cancelButtonText:"tidak",confirmButtonText:"ya, hapus",padding:"2em"}).then(function(e){e.value&&t.parent("form").submit()})}),$("#datatable-table").DataTable({scrollY:"300px",scrollX:!0,scrollCollapse:!0,paging:!0,oLanguage:{oPaginate:{sPrevious:'<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-left"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>',sNext:'<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-right"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg>'},sInfo:"tampilkan halaman _PAGE_ dari _PAGES_",sSearch:'<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-search"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>',sSearchPlaceholder:"Cari Data...",sLengthMenu:"Hasil :  _MENU_"},stripeClasses:[],lengthMenu:[[-1,5,10,25,50],["All",5,10,25,50]]})});
    </script>
    
    {!! session('pesan') !!}
@endsection
