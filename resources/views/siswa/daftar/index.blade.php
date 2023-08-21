@extends('template.main')
@section('content')
    @include('template.navbar.siswa')


    <!--  BEGIN CONTENT AREA  -->
    <div id="content" class="main-content">
        <div class="layout-px-spacing">

            <div class="row layout-top-spacing">
                <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 layout-spacing">
                    <div class="widget widget-five">
                        <div class="widget-content">
                            <div class="header">
                                <div class="header-body">
                                    <h5 class="info-heading">{{ $siswa->nama_siswa }}</h5>
                                    @if (!$daftarPKL)
                                        <p class="info-text">Anda belum melakukan pendaftaran PKL. Silahkan melakukan pendaftaran!!</p>
                                        <a href="javascript:void(0)" class="btn btn-primary btn-sm mt-3" data-toggle="modal" data-target="#daftarPkl">
                                            <i data-feather="user-plus"></i> Daftar PKL
                                        </a>
                                    @elseif ($daftarPKL && $guruNIP)
                                        <p class="info-text">Anda telah selesai mendaftarkan diri!!</p>
                                        <a href="javascript:void(0)" class="btn btn-primary btn-sm mt-3" data-toggle="modal" data-target="#lihatGuruModal">
                                            Lihat Guru Pembimbing
                                        </a>
                                    
                                        <!-- Modal Lihat Guru Pembimbing -->
                                        <div class="modal fade" id="lihatGuruModal" tabindex="-1" role="dialog" aria-labelledby="lihatGuruModalLabel" aria-hidden="true">
                                            <div class="modal-dialog" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="lihatGuruModalLabel">Guru Pembimbing Lapangan Anda</h5>
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        @if ($guruNIP)
                                                            <p>Guru Pembimbing Lapangan Anda</p>
                                                            <p>Nama: {{ $dataGuru->nama_guru }}</p>
                                                            <p>No Telp: {{ $dataGuru->no_telp }}</p>
                                                        @else
                                                            <p>Belum masa pemberitahuan guru pembimbing. Silahkan kembali lagi nanti!</p>
                                                        @endif
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @else
                                        <p>Anda telah selesai mendaftarkan diri, namun belum ada pemberitahuan guru pembimbing.</p>
                                    @endif
                                
                                    </div>
                                </div>
                            </div>
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

<!-- Modal Tambah -->
<div class="modal fade" id="daftarPkl" tabindex="-1" role="dialog" aria-labelledby="daftarPklLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <form action="{{ url("siswa/daftar_pkl") }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="tambah_siswaLabel">Pendaftaran PKL</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        x
                    </button>
                </div>
                <div class="modal-body">
                        <div class="form-group">
                            <label for="">NISN</label>
                            <input type="text" name="siswa_nisn" id="siswa_nisn" value="{{ $siswa->nisn}}" class="form-control" readonly>
                            <input type="hidden" name="tahun_ajar_id" id="tahun_ajar_id" value="{{ $siswa->tahun_ajar_id}}" class="form-control" readonly>
                        </div>
                        <div class="form-group">
                            <label for="">Nama siswa</label>
                            <input type="text" name="nama_siswa" id="nama_siswa" value="{{ $siswa->nama_siswa}}" class="form-control" readonly>
                        </div>
                        <div class="form-group">
                            <label for="">Kelas / Program Keahlian</label>
                            <input type="text" name="kelas" id="kelas" value="{{ $siswa->kelas->nama_kelas}} / {{ $siswa->jurusan->nama_jurusan}} " class="form-control" readonly>
                        </div>
                        <div class="form-group">
                            <label for="">Du/Di tempat PKL</label>
                            <select id="searchDudi" class="form-control" name="dudi_id" placeholder="Cari Du/Di...">
                                <option value="" disabled selected>Pilih Du/Di</option>
                                @foreach ($dudi as $d)
                                    <option value="{{ $d->id }}">{{ $d->nama_dudi }}</option>
                                @endforeach
                            </select>
                        </div>                        
                                  
                        <div class="form-group">
                            <label for="">Surat Balasan</label>
                            <input type="file" name="surat_balasan" id="surat_balasan" class="form-control-file">
                        </div>                        
                    </div>
                    <div class="modal-footer">
                        <button type="reset" value="reset" class="btn" data-dismiss="modal"><i class="flaticon-cancel-12"></i> Cancel</button>
                        <button type="submit" class="btn btn-primary">Save</button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<!--Lihat Guru Pembimbing-->

    {!! session('pesan') !!}
    
@endsection
<script>
    document.getElementById("searchDudi").addEventListener("input", function() {
        var input = this.value.toLowerCase();
        var options = document.querySelectorAll("#dudiOptions option");
        
        options.forEach(function(option) {
            var optionText = option.value.toLowerCase();
            
            if (optionText.indexOf(input) !== -1) {
                option.style.display = "block";
            } else {
                option.style.display = "none";
            }
        });
    });

    // Fungsi untuk menangani pemilihan dari datalist
    document.getElementById("searchDudi").addEventListener("change", function() {
        var selectedOption = document.querySelector("#dudiOptions option[value='" + this.value + "']");
        if (selectedOption) {
            var selectedDudiId = selectedOption.getAttribute("data-id");
            document.getElementById("selectedDudiId").value = selectedDudiId;
        }
    });
</script>



