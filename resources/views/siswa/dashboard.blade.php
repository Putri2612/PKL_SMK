@extends('template.main')
@section('content')
    @include('template.navbar.siswa')


    <!--  BEGIN CONTENT AREA  -->
    <div id="content" class="main-content">
        <div class="layout-px-spacing">

            {{-- <div class="row layout-top-spacing">
                
                <div class="col-xl-6 col-lg-12 col-md-6 col-sm-12 col-12 layout-spacing">
                    <div class="widget widget-table-one p-3">
                        <div class="widget-heading">
                            <h5 class="">Notifikasi Materi</h5>
                        </div>

                        <div class="widget-content">
                            @if ($notif_materi->count() > 0)
                                @foreach ($notif_materi as $nm)
                                    <a href="{{ url('/siswa/materi/' . $nm->kode) }}">
                                        <div class="transactions-list mt-1">
                                            <div class="t-item">
                                                <div class="t-company-name">
                                                    <div class="t-icon">
                                                        <div class="icon">
                                                            <span data-feather="book"></span>
                                                        </div>
                                                    </div>
                                                    <div class="t-name">
                                                        <h4>{{ $nm->materi->nama_materi }}</h4>
                                                        <p class="meta-date">{{ $nm->materi->mapel->nama_mapel }}</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                @endforeach
                            @else
                                <div class="transactions-list" style="background: #ffeccb; border: 2px dashed #e2a03f;">
                                    <div class="t-item">
                                        <div class="t-company-name">
                                            <div class="t-name">
                                                <h4 style="color: #e2a03f;">Heeemm.. Belum Ada Materi
                                                    <svg viewBox="0 0 24 24" width="24" height="24"
                                                        stroke="currentColor" stroke-width="2" fill="none"
                                                        stroke-linecap="round" stroke-linejoin="round" class="css-i6dzq1">
                                                        <circle cx="12" cy="12" r="10"></circle>
                                                        <line x1="8" y1="15" x2="16" y2="15">
                                                        </line>
                                                        <line x1="9" y1="9" x2="9.01" y2="9">
                                                        </line>
                                                        <line x1="15" y1="9" x2="15.01" y2="9">
                                                        </line>
                                                    </svg>
                                                </h4>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="col-xl-6 col-lg-12 col-md-6 col-sm-12 col-12 layout-spacing">
                    <div class="widget widget-table-one p-3">
                        <div class="widget-heading">
                            <h5 class="">Notifikasi Ujian</h5>
                        </div>

                        <div class="widget-content">
                            @if ($notif_ujian->count() > 0)
                                @foreach ($notif_ujian as $nu)
                                @if ($nu->ujian->jenis === 0)
                                    <a href="{{ url('/siswa/ujian/' . $nu->kode) }}">
                                        <div class="transactions-list mt-1">
                                            <div class="t-item">
                                                <div class="t-company-name">
                                                    <div class="t-icon">
                                                        <div class="icon">
                                                            <span data-feather="cast"></span>
                                                        </div>
                                                    </div>
                                                    <div class="t-name">
                                                        <h4>{{ $nu->ujian->nama }}</h4>
                                                        <p class="meta-date">{{ $nu->ujian->mapel->nama_mapel }}</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                @else
                                    <a href="{{ url('/siswa/ujian_essay/' . $nu->kode) }}">
                                        <div class="transactions-list mt-1">
                                            <div class="t-item">
                                                <div class="t-company-name">
                                                    <div class="t-icon">
                                                        <div class="icon">
                                                            <span data-feather="cast"></span>
                                                        </div>
                                                    </div>
                                                    <div class="t-name">
                                                        <h4>{{ $nu->ujian->nama }}</h4>
                                                        <p class="meta-date">{{ $nu->ujian->mapel->nama_mapel }}</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                @endif
                                    
                                @endforeach
                            @else
                                <div class="transactions-list"
                                    style="background: hsl(355, 82%, 85%); border: 2px dashed #e7515a;">
                                    <div class="t-item">
                                        <div class="t-company-name">
                                            <div class="t-name">
                                                <h4 style="color: #e7515a;">YahoOo.. Tidak ada ujian
                                                    <span data-feather="smile"></span>
                                                </h4>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div> --}}

            <div class="row layout-top-spacing">
                <div class="col-xl-4 col-lg-6 col-md-6 col-sm-6 col-12 layout-spacing">
                    <div class="widget widget-five">
                        <div class="widget-content">
                            <div class="header">
                                <div class="header-body">
                                    <h5 class="info-heading">{{ $siswa->nama_siswa }}</h5>
                                    <p class="info-text">data ini diatur oleh administrator, jika ada perubahan bisa hubungi admin</p>
                                </div>
                            </div>
                            {{-- <div class="w-content">
                                <div class="">
                                    <p class="task-left">
                                        {{ $tugas->count() }}
                                    </p>
                                    @php
                                        $tugas_telat = 0;
                                        $tugas_tidak_telat = 0;
                                    @endphp
                                    @foreach ($tugas as $t)
                                        @if ($t->is_telat === 1)
                                            @php
                                                $tugas_telat++;
                                            @endphp
                                        @endif

                                        @if ($t->is_telat === 0)
                                            @php
                                                $tugas_tidak_telat++;
                                            @endphp
                                        @endif
                                    @endforeach
                                    <p class="task-completed"><span>{{ $tugas_tidak_telat }} Tugas Sukses
                                            Dikerjakan</span></p>
                                    <p class="task-hight-priority"><span>{{ $tugas_telat }} Tugas</span> Terlambat
                                        Dikerjakan</p>
                                </div>
                            </div> --}}
                        </div>
                    </div>

                </div>
            </div>

        </div>
        <div class="footer-wrapper">
         
            <div class="footer-section f-section-2">
                <p class="">SMKN 1 Brondong</p>
            </div>
        </div>
    </div>
    <!--  END CONTENT AREA  -->


    {!! session('pesan') !!}
@endsection
