<!--  BEGIN SIDEBAR  -->
<div class="sidebar-wrapper sidebar-theme">

    <nav id="sidebar">
        <div class="profile-info">
            <div class="user-info">
                <h6 class="">{{ $guru->nama_guru }}</h6>
                <p class="">SMKN 1 BRONDONG</p>
            </div>
        </div>
        <div class="shadow-bottom"></div>
        <ul class="list-unstyled menu-categories" id="accordionExample">
            <li class="menu {{ ($menu['menu'] == 'dashboard') ? 'active' : ''; }}">
                <a href="{{ url("/guru") }}" aria-expanded="{{ ($menu['expanded'] == 'dashboard') ? 'true' : 'false'; }}" class="dropdown-toggle">
                    <div class="">
                        <span data-feather="airplay"></span>
                        <span>Dashboard</span>
                    </div>
                </a>
            </li>
            <li class="menu menu-heading">
                <div class="heading">
                    <span data-feather="minus"></span>
                    <span>KEPALA PROGRAM MENU</span>
                </div>
            </li>
            <li class="menu {{ ($menu['menu'] == 'master') ? 'active' : ''; }}">
                <a href="#components-user" data-toggle="collapse" aria-expanded="{{ ($menu['expanded'] == 'master') ? 'true' : 'false'; }}" class="dropdown-toggle">
                    <div>
                        <svg viewBox="0 0 24 24" width="24" height="24" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round" class="css-i6dzq1">
                            <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                            <circle cx="9" cy="7" r="4"></circle>
                            <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
                            <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                        </svg>
                        <span>User</span>
                    </div>
                    <div>
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevron-right">
                            <polyline points="9 18 15 12 9 6"></polyline>
                        </svg>
                    </div>
                </a>
                <ul class="collapse submenu list-unstyled {{ ($menu['collapse'] == 'master') ? 'show' : ''; }}" id="components-user" data-parent="#accordionExample">
                    <li class="{{ ($menu['sub'] == 'siswa') ? 'active' : ''; }}">
                        <a href="{{ url("/kaprog/peserta") }}"> Daftar Siswa PKL </a>
                    </li>
                    <li class="{{ ($menu['sub'] == 'guru') ? 'active' : ''; }}">
                        <a href="{{ url("/kaprog/guru") }}"> Daftar Guru Pembimbing </a>
                    </li>
                    <li class="{{ ($menu['sub'] == 'dudi') ? 'active' : ''; }}">
                        <a href="{{ url("/kaprog/dudi") }}">Daftar  DU / DI </a>
                    </li>
                </ul>
            </li>
            <li class="menu {{ ($menu['menu'] == 'kelompok') ? 'active' : ''; }}">
                <a href="{{ url("/kaprog/kelompok") }}" aria-expanded="{{ ($menu['expanded'] == 'kelompok') ? 'true' : 'false'; }}" class="dropdown-toggle">
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
                        <a href="{{ url("/kaprog/logbook") }}"> Logbook PKL </a>
                    </li>
                    <li class="{{ ($menu['sub'] == 'monitoring') ? 'active' : ''; }}">
                        <a href="{{ url("/kaprog/monitoring") }}"> Monitoring PKL </a>
                    </li>
                    <li class="{{ ($menu['sub'] == 'kunjungan') ? 'active' : ''; }}">
                        <a href="{{ url("/kaprog/kunjungan") }}"> Kegiatan Kunjungan </a>
                    </li>
                    <li class="{{ ($menu['sub'] == 'catatan') ? 'active' : ''; }}">
                        <a href="{{ url("/kaprog/catatan") }}"> Catatan DU/DI </a>
                    </li>
                    <li class="{{ ($menu['sub'] == 'nilai') ? 'active' : ''; }}">
                        <a href="{{ url("/kaprog/nilai") }}"> Nilai PKL Siswa</a>
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
                <a href="{{ url("/kaprog/profile")  }}" aria-expanded="{{ ($menu['expanded'] == 'profile') ? 'true' : 'false'; }}" class="dropdown-toggle">
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