@extends('template.main')
@section('content')
    @include('template.navbar.dudi')

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
                                <p class="">{{ $dudi->nama_dudi }}</p>
                            </div>
                            <div class="user-info-list" style="margin-top: -10px;">
                                <div class="text-center">
                                    <p>Mitra SMKN 1 Brondong</p>
                                    <ul class="contacts-block list-unstyled" style="margin-top: -5px;">
                                        <li class="contacts-block__item">
                                            <span data-feather="mail"></span>
                                            <br>
                                            {{ $dudi->email }}
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
                            <form action="{{ url("/dudi/edit_profile/" . $dudi->id) }}" method="post" enctype="multipart/form-data">
                                @csrf
                                <div class="form-group">
                                    <label for="">Nama</label>
                                    <input type="text" name="nama_dudi" id="nama_dudi" value="{{ old('nama_dudi', $dudi->nama_dudi) }}" class="form-control" required>
                                </div>
                                <div class="form-group">
                                    <label for="">Nomer Telp</label>
                                    <input type="text" name="no_telp" id="no_telp" value="{{ old('no_telp', $dudi->no_telp) }}" class="form-control" required>
                                </div>
                                <div class="form-group">
                                    <label for="">Alamat</label>
                                    <input type="text" name="alamat" id="alamat" value="{{ old('alamat', $dudi->alamat) }}" class="form-control" required>
                                </div>
                                <div class="form-group">
                                    <label for="">Email</label>
                                    <input type="email" name="email" id="email" value="{{ $dudi->email }}" class="form-control" required readonly>
                                </div>
                                
                                <button type="submit" class="btn btn-primary mt-3">Save</button>
                            </form>
                        </div>
                    </div>

                    <div class="skills layout-spacing">
                        <div class="widget-content widget-content-area">
                            <h3 class="">Password</h3>
                            <form action="{{ url("/dudi/edit_password/" . $dudi->id) }}" method="post">
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