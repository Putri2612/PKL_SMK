<!--  BEGIN SIDEBAR  -->
<div class="sidebar-wrapper sidebar-theme">

    <nav id="sidebar">
        <div class="profile-info">

            <div class="user-info">
           
                <h6 class="">{{ $siswa->nama_siswa }}</h6>
                <p class="">SMKN 1 BRONDONG</p>
            </div>
        </div>
        <div class="shadow-bottom"></div>
        <ul class="list-unstyled menu-categories" id="accordionExample">
            <li class="menu {{ ($menu['menu'] == 'dashboard') ? 'active' : ''; }}">
                <a href="{{ url("/siswa") }}" aria-expanded="{{ ($menu['expanded'] == 'dashboard') ? 'true' : 'false'; }}" class="dropdown-toggle">
                    <div class="">
                        <span data-feather="airplay"></span>
                        <span>Dashboard</span>
                    </div>
                </a>
            </li>
            <li class="menu menu-heading">
                <div class="heading">
                    <span data-feather="minus"></span>
                    <span>Siswa Menu</span>
                </div>
            </li>
            
            <li class="menu {{ ($menu['menu'] == 'dudi') ? 'active' : ''; }}">
                <a href="{{ url("/siswa/dudi") }}" aria-expanded="{{ ($menu['expanded'] == 'dudi') ? 'true' : 'false'; }}" class="dropdown-toggle">
                    <div class="">
                        <span data-feather="book-open"></span>
                        <span>Informasi PKL</span>
                    </div>
                </a>
            </li>
            <li class="menu {{ ($menu['menu'] == 'daftar') ? 'active' : ''; }}">
                <a href="{{ url("/siswa/daftar") }}" aria-expanded="{{ ($menu['expanded'] == 'daftar') ? 'true' : 'false'; }}" class="dropdown-toggle">
                    <div class="">
                        <span data-feather="book"></span>
                        <span>Pendaftaran PKL</span>
                    </div>
                </a>
            </li>
            <li class="menu {{ ($menu['menu'] == 'logbook') ? 'active' : ''; }}">
                <a href="{{ url("/siswa/logbook") }}" aria-expanded="{{ ($menu['expanded'] == 'logbook') ? 'true' : 'false'; }}" class="dropdown-toggle">
                    <div class="">
                        <span data-feather="book-open"></span>
                        <span>Logbook</span>
                    </div>
                </a>
            </li>
            <li class="menu {{ ($menu['menu'] == 'monitoring') ? 'active' : ''; }}">
                <a href="{{ url("/siswa/monitoring") }}" aria-expanded="{{ ($menu['expanded'] == 'monitoring') ? 'true' : 'false'; }}" class="dropdown-toggle">
                    <div class="">
                        <span data-feather="cast"></span>
                        <span>Monitoring</span>
                    </div>
                </a>
            </li>
            <li class="menu {{ ($menu['menu'] == 'catatan') ? 'active' : ''; }}">
                <a href="{{ url("/siswa/catatan") }}" aria-expanded="{{ ($menu['expanded'] == 'catatan') ? 'true' : 'false'; }}" class="dropdown-toggle">
                    <div class="">
                        <span data-feather="book"></span>
                        <span>Catatan DU/DI</span>
                    </div>
                </a>
            </li>
            <li class="menu {{ ($menu['menu'] == 'nilai') ? 'active' : ''; }}">
                <a href="{{ url("/siswa/nilai") }}" aria-expanded="{{ ($menu['expanded'] == 'nilai') ? 'true' : 'false'; }}" class="dropdown-toggle">
                    <div class="">
                        <span data-feather="cast"></span>
                        <span>Nilai PKL</span>
                    </div>
                </a>
            </li>
            <li class="menu menu-heading">
                <div class="heading">
                    <span data-feather="minus"></span>
                    <span>USER MENU</span>
                </div>
            </li>
            <li class="menu {{ ($menu['menu'] == 'profile') ? 'active' : ''; }}">
                <a href="{{ url("/siswa/profile") }}" aria-expanded="{{ ($menu['expanded'] == 'profile') ? 'true' : 'false'; }}" class="dropdown-toggle">
                    <div class="">
                        <span data-feather="user"></span>
                        <span>Profile</span>
                    </div>
                </a>
            </li>
            
        </ul>

    </nav>

</div>
<!--  END SIDEBAR  -->