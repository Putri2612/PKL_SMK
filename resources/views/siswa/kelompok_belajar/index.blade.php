@extends('template.main')
@section('content')
    @include('template.navbar.siswa')

<style>
    iframe{
        width: 100%;
    }
</style>
    <!--  BEGIN CONTENT AREA  -->
<meta name="csrf-token" content="{{ csrf_token() }}">
<div id="content" class="main-content">
    <div class="layout-px-spacing">
        <div class="row layout-top-spacing">
            <div class="col-lg-12 layout-spacing">
                <div class="widget shadow">
                    <div class="widget-content-area">
                        <h5 class="">{{ $keljar->nama_kelompok   }}</h5>
                        <table class="mt-3">
                            <tr>
                                <th>Guru</th>
                                <th> : {{ $keljar->guru->nama_guru }}</th>
                            </tr>
                            <tr>
                                <th>Kelas</th>
                                <th> : {{  $keljar->kelas->nama_kelas  }}</th>
                            </tr>
                            <tr>
                                <th>Tahun Ajaran</th>
                                <th> : {{  $keljar->tahun_ajar->nama  }}</th>
                            </tr>
                        </table>
                        <hr>
                        
                        <h4>Daftar Anggota Kelompok</h4>
                        <table id="datatable-table" class="table text-center text-nowrap">
                            <tr>
                                <th>NIS</th>
                                <th>Nama</th>
                            </tr>
                            @foreach ($anggota as $a)
                                <tr>
                                    <td>{{ $a['nis_siswa'] }}</td>
                                    <td>{{ $a['nama_siswa'] }}</td>
                                </tr>
                            @endforeach
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @include('template.footer')
</div>
<!--  END CONTENT AREA  -->




<script>

   </script>


    {!! session('pesan') !!}
@endsection