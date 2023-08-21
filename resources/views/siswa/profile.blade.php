@extends('template.main')
@section('content')
    @include('template.navbar.siswa')

    <div id="content" class="main-content">
        <div class="layout-px-spacing">
            <div class="row layout-spacing">
                <!-- Content -->
                <div class="col-xl-4 col-lg-6 col-md-5 col-sm-12 layout-top-spacing">
                    <div class="user-profile layout-spacing">
                        <div class="widget-content widget-content-area">
                            <div class="d-flex justify-content-between">
                                <h3 class="">My Profile</h3>
                                <a href="javascript:void(0);" class="mt-2 edit-profile">
                                    <span data-feather="edit-3"></span>
                                </a>
                            </div>
                            <div class="text-center user-info">
                                <p class="">{{ $siswa->nama_siswa }}</p>
                            </div>
                            <div class="user-info-list" style="margin-top: -10px;">
                                <div class="text-center">
                                    <p>{{ $siswa->jurusan->nama_jurusan ?? 'Tidak ada jurusan' }} - SMKN 1 Brondong</p>
                                    <ul class="contacts-block list-unstyled" style="margin-top: -5px;">
                                      
                                        <li class="contacts-block__item">
                                            <span data-feather="mail"></span>
                                            <br>
                                            {{ $siswa->email }}
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

                <div class="col-xl-8 col-lg-6 col-md-7 col-sm-12 layout-top-spacing">

                    <div class="skills layout-spacing ">
                        <div class="widget-content widget-content-area">
                            <h3 class="">Update Profile</h3>
                            <form action="{{ url("/siswa/edit_profile/" . $siswa->nisn) }}" method="post" enctype="multipart/form-data">
                                @csrf
                                <div class="form-group">
                                    <label for="">NISN</label>
                                    <input type="text" name="nisn" id="nisn" value="{{ $siswa->nisn }}" class="form-control" required readonly>
                                </div>
                                <div class="form-group">
                                    <label for="">Nama</label>
                                    <input type="text" name="nama_siswa" id="nama_siswa" value="{{ old('nama_siswa', $siswa->nama_siswa) }}" class="form-control" required>
                                </div>
                                <div class="form-group">
                                    <label for="">Jenis Kelamin</label>
                                    <select name="jenis_kelamin" id="jenis_kelamin" class="form-control" required>
                                        <option value="Laki-laki" {{ (old('jenis_kelamin', $siswa->jenis_kelamin) == 'Laki-laki') ? 'selected' : '' }}>Laki-laki</option>
                                        <option value="Perempuan" {{ (old('jenis_kelamin', $siswa->jenis_kelamin) == 'Perempuan') ? 'selected' : '' }}>Perempuan</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="">Golongan Darah</label>
                                    <select name="golongan_darah" id="golongan_darah" class="form-control" required>
                                        <option value="Tidak Tahu" {{ (old('golongan_darah', $siswa->golongan_darah) == 'tidak_tahu') ? 'selected' : '' }}>Tidak Tahu</option>
                                        <option value="A" {{ (old('golongan_darah', $siswa->golongan_darah) == 'A') ? 'selected' : '' }}>A</option>
                                        <option value="O" {{ (old('golongan_darah', $siswa->golongan_darah) == 'O') ? 'selected' : '' }}>O</option>
                                        <option value="B" {{ (old('golongan_darah', $siswa->golongan_darah) == 'B') ? 'selected' : '' }}>B</option>
                                        <option value="AB" {{ (old('golongan_darah', $siswa->golongan_darah) == 'AB') ? 'selected' : '' }}>AB</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="">Tahun Ajar</label>
                                    <select name="tahun_ajar_id" id="tahun_ajar_id" class="form-control" required>
                                        @foreach($tahun_ajar as $tahunAjar)
                                        <option value="{{ $tahunAjar->id }}" {{ (old('tahun_ajar_id', $siswa->tahun_ajar_id) == $tahunAjar->id) ? 'selected' : '' }}>
                                            {{ $tahunAjar->nama }}
                                        </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="">Nomer Telp</label>
                                    <input type="text" name="no_telp" id="no_telp" value="{{ old('no_telp', $siswa->no_telp) }}" class="form-control" required>
                                </div>
                                <div class="form-group">
                                    <label for="">Alamat</label>
                                    <input type="text" name="alamat" id="alamat" value="{{ old('alamat', $siswa->alamat) }}" class="form-control" required>
                                </div>
                                <div class="form-group">
                                    <label for="">Nama Ayah</label>
                                    <input type="text" name="nama_ayah" id="nama_ayah" value="{{ old('nama_ayah', $siswa->nama_ayah) }}" class="form-control" required>
                                </div>
                                <div class="form-group">
                                    <label for="">Nama Ibu</label>
                                    <input type="text" name="nama_ibu" id="nama_ibu" value="{{ old('nama_ibu', $siswa->nama_ibu) }}" class="form-control" required>
                                </div>
                                <div class="form-group">
                                    <label for="">Pekerjaan Wali</label>
                                    <input type="text" name="pekerjaan_wali" id="pekerjaan_wali" value="{{ old('pekerjaan_wali', $siswa->pekerjaan_wali) }}" class="form-control" required>
                                </div>
                                <div class="form-group">
                                    <label for="">Alamat Wali</label>
                                    <input type="text" name="alamat_wali" id="alamat_wali" value="{{ old('alamat_wali', $siswa->alamat_wali) }}" class="form-control" required>
                                </div>
    
                                
                                <button type="submit" class="btn btn-primary mt-3">Save</button>
                            </form>
                        </div>
                    </div>

                    <div class="skills layout-spacing">
                        <div class="widget-content widget-content-area">
                            <h3 class="">Password</h3>
                            <form action="{{ url("/siswa/edit_password/" . $siswa->id) }}" method="post">
                                @csrf
                                <div class="form-group">
                                    <label for="">Current Password</label>
                                    <input type="password" name="current_password" class="form-control" required>
                                </div>
                                <div class="form-group">
                                    <label for="">New Password</label>
                                    <input type="password" name="password" class="form-control" required>
                                </div>
                                <button type="submit" class="btn btn-primary">Save</button>
                            </form>
                        </div>
                    </div>

                </div>

            </div>
        </div>
        @include('template.footer')
    </div>
    <!--  END CONTENT AREA  -->
    {!! session('pesan') !!}

    <script>
        function previewImg(){var e=document.querySelector("#customFile");const t=document.querySelector(".custom-file-label"),o=document.querySelector(".img-user");t.textContent=e.files[0].name;const n=new FileReader;n.readAsDataURL(e.files[0]),n.onload=function(e){o.src=e.target.result}}
    </script>
@endsection