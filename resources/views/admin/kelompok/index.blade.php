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
                                    {{-- @foreach ($tahun_ajar as $nama_tahun_ajar)
                                        <h5 class="">Kelompok Belajar - {{ $nama_tahun_ajar }}</h5>
                                    @endforeach --}}
                                    <h5 class="">Kelompok PKL</h5>
                                </div>
                                <div class="table-responsive mt-3">
                                    <table id="datatable-table" class="table table-bordered table-striped text-center text-nowrap">
                                        <thead>
                                            <tr>
                                                <th>Jurusan</th>
                                                <th>Opsi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($jurusan as $j)
                                                <tr>
                                                    <td><?= $j->nama_jurusan ?></td> 
                                                    <td>
                                                        <a href="{{ url('/admin/kelompok/' . $j->id) }}"
                                                            class="btn btn-primary btn-sm"><span
                                                                data-feather="eye"></span></a>                                                               
                                                        
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

@endsection