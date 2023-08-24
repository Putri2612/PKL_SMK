<!--  BEGIN SIDEBAR  -->
<div class="sidebar-wrapper sidebar-theme">

    <nav id="sidebar">
        <div class="profile-info">
            <div class="user-info">
                <h6 class="">{{ $dudi->nama_dudi }}</h6>
                <p class="">MITRA - SMKN 1 BRONDONG</p>
            </div>
        </div>
        <div class="shadow-bottom"></div>
        <ul class="list-unstyled menu-categories" id="accordionExample">
            <li class="menu {{ ($menu['menu'] == 'dashboard') ? 'active' : ''; }}">
                <a href="{{ url("/dudi") }}" aria-expanded="{{ ($menu['expanded'] == 'dashboard') ? 'true' : 'false'; }}" class="dropdown-toggle">
                    <div class="">
                        <span data-feather="airplay"></span>
                        <span>Dashboard</span>
                    </div>
                </a>
            </li>
            <li class="menu menu-heading">
                <div class="heading">
                    <span data-feather="minus"></span>
                    <span>DU/DI MENU</span>
                </div>
            </li>
            <li class="menu {{ ($menu['menu'] == 'kelompok') ? 'active' : ''; }}">
                <a href="{{ url("/dudi/kelompok") }}" aria-expanded="{{ ($menu['expanded'] == 'kelompok') ? 'true' : 'false'; }}" class="dropdown-toggle">
                    <div class="">
                        <span data-feather="book-open"></span>
                        <span>Kelompok PKL</span>
                    </div>
                </a>
            </li>
            
            <li class="menu {{ ($menu['menu'] == 'kegiatan') ? 'active' : ''; }}">
                <a href="#components-kegiatan" data-toggle="collapse" aria-expanded="{{ ($menu['expanded'] == 'daftar') ? 'true' : 'false'; }}" class="dropdown-toggle">
                    <div>
                        <svg viewBox="0 0 24 24" width="24" height="24" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round" class="css-i6dzq1">
                            <path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"></path>
                            <path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"></path>
                        </svg>
                        <span>Kegiatan PKL</span>
                    </div>
                    <div>
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevron-right">
                            <polyline points="9 18 15 12 9 6"></polyline>
                        </svg>
                    </div>
                </a>
                <ul class="collapse submenu list-unstyled {{ ($menu['collapse'] == 'kegiatan') ? 'show' : ''; }}" id="components-kegiatan" data-parent="#accordionExample">
                    <li class="{{ ($menu['sub'] == 'logbook') ? 'active' : ''; }}">
                        <a href="{{ url("/dudi/logbook") }}"> Logbook PKL </a>
                    </li>

                    <li class="{{ ($menu['sub'] == 'catatan') ? 'active' : ''; }}">
                        <a href="{{ url("/dudi/catatan") }}"> Catatan DU/DI </a>
                    </li>
                    <li class="{{ ($menu['sub'] == 'nilai') ? 'active' : ''; }}">
                        <a href="{{ url("/dudi/nilai") }}"> Nilai PKL Siswa</a>
                    </li>
                </ul>
            </li>
            <li class="menu menu-heading">
                <div class="heading">
                    <span data-feather="minus"></span>
                    <span>USER MENU</span>
                </div>
            </li>
            <li class="menu {{ ($menu['menu'] == 'profile') ? 'active' : ''; }}">
                <a href="{{ url("/dudi/profile") }}" aria-expanded="{{ ($menu['expanded'] == 'profile') ? 'true' : 'false'; }}" class="dropdown-toggle">
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