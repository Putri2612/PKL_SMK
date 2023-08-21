@extends('template.main')
@section('content')
    @include('template.navbar.kaprog')

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
                                <p class="">{{ $guru->nama_guru }}</p>
                            </div>
                            <div class="user-info-list" style="margin-top: -10px;">
                                <div class="text-center">
                                    @php
                                    $role = ''; // Inisialisasi variabel peran
                                    switch ($guru->role) {
                                        case 2:
                                            $role = 'Kepala Program';
                                            break;
                                        case 3:
                                            $role = 'Guru';
                                            break;
                                        // Tambahkan case lain sesuai kebutuhan
                                        default:
                                            $role = 'Unknown Role';
                                            break;
                                    }
                                @endphp
                                <p>{{ $role }}  {{ $guru->jurusan->nama_jurusan ?? 'Tidak ada jurusan' }}- SMKN 1 Brondong</p>
                                    <ul class="contacts-block list-unstyled" style="margin-top: -5px;">
                                        <li class="contacts-block__item">
                                            <span data-feather="mail"></span>
                                            <br>
                                            {{ $guru->email }}
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
                            <form action="{{ url("/kaprog/edit_profile/" . $guru->nip) }}" method="post" enctype="multipart/form-data">
                                @csrf
                                <div class="form-group">
                                    <label for="">Nama</label>
                                    <input type="text" name="nama_guru" id="nama_guru" value="{{ old('nama_guru', $guru->nama_guru) }}" class="form-control" required>
                                </div>
                                <div class="form-group">
                                    <label for="">Gender</label>
                                    <select name="gender" id="gender" class="form-control" required>
                                        <option value="Laki-laki" {{ (old('gender', $guru->gender) == 'Laki-laki') ? 'selected' : '' }}>Laki-laki</option>
                                        <option value="Perempuan" {{ (old('gender', $guru->gender) == 'Perempuan') ? 'selected' : '' }}>Perempuan</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="">Nomer Telp</label>
                                    <input type="text" name="no_telp" id="no_telp" value="{{ old('no_telp', $guru->no_telp) }}" class="form-control" required>
                                </div>
                                <div class="form-group">
                                    <label for="">Email</label>
                                    <input type="email" name="email" id="email" value="{{ $guru->email }}" class="form-control" required readonly>
                                </div>
                                
                                <button type="submit" class="btn btn-primary mt-3">Save</button>
                            </form>
                        </div>
                    </div>

                    <div class="skills layout-spacing">
                        <div class="widget-content widget-content-area">
                            <h3 class="">Password</h3>
                            <form action="{{ url("/guru/edit_password/" . $guru->nip) }}" method="post">
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

    
@endsection