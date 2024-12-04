<aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">
    <div class="app-brand demo">
        <a href="{{ route('admin.home') }}" class="app-brand-link">
            <span class="app-brand-logo demo">
                <img src="{{ asset('img/logo-white.png') }}" class="h-100" alt=""
                    data-app-light-img="logo-white.png" data-app-dark-img="logo.png">
            </span>
            <span class="app-brand-text demo menu-text fw-bold ms-2">Implementasi Kurikulum
            </span>
        </a>

        <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto">
            <i class="bx menu-toggle-icon d-none d-xl-block fs-4 align-middle"></i>
            <i class="bx bx-x d-block d-xl-none bx-sm align-middle"></i>
        </a>
    </div>

    <div class="menu-divider mt-0"></div>

    <div class="menu-inner-shadow"></div>

    <ul class="menu-inner py-1">
        @if (Session::get('login') == 'admin')
            <!-- Dashboards -->
            <li class="menu-item @if (request()->routeIs('admin.home')) active @endif"">
                <a href="{{ route('admin.home') }}" class="menu-link">
                    <i class="menu-icon tf-icons bx bx-home-circle"></i>
                    <div>Dashboards</div>
                </a>
            </li>

            <li class="menu-header small text-uppercase"><span class="menu-header-text">Rencana Pembelajaran </span>
            </li>
            <li class="menu-item @if (request()->routeIs('admin.cpl.*')) open @endif">
                <a href="" class="menu-link menu-toggle">
                    <i class="menu-icon tf-icons bx bx-layout"></i>
                    <div>CPL</div>
                </a>
                <ul class="menu-sub">
                    <li class="menu-item @if (request()->routeIs('admin.cpl.index')) active @endif">
                        <a href="{{ route('admin.cpl.index') }}" class="menu-link">
                            <div>CPL Padu</div>
                        </a>
                    </li>
                    <li class="menu-item @if (request()->routeIs('admin.cpl.getBobotCPLPadu')) active @endif">
                        <a href="{{ route('admin.cpl.getBobotCPLPadu') }}" class="menu-link">
                            <div>Data Bobot CPL Padu</div>
                        </a>
                    </li>
                    <li class="menu-item @if (request()->routeIs('admin.cpl.matriksCPLMK')) active @endif">
                        <a href="{{ route('admin.cpl.matriksCPLMK') }}" class="menu-link">
                            <div>Matriks Bobot CPL Padu setiap MK</div>
                        </a>
                    </li>
                    <li class="menu-item @if (request()->routeIs('admin.cpl.matriksCPL')) active @endif">
                        <a href="{{ route('admin.cpl.matriksCPL') }}" class="menu-link">
                            <div>Matriks Bobot setiap CPL</div>
                        </a>
                    </li>
                </ul>
            </li>
            <li class="menu-item @if (request()->routeIs('admin.cpmk.*') || request()->routeIs('admin.subcpmk.*') || request()->routeIs('admin.bobot')) active @endif">
                <a href="{{ route('admin.cpmk.index') }}" class="menu-link"> <i
                        class="menu-icon tf-icons bx bx-food-menu"></i>
                    <div>Data CPMK - Sub CPMK</div>
                </a>
            </li>

            <li class="menu-item @if (request()->routeIs('admin.mkp.*')) active @endif">
                <a href="{{ route('admin.mkp.index') }}" class="menu-link"> <i
                        class="menu-icon tf-icons bx bx-book-alt"></i>
                    <div>Pengkategorian Mata Kuliah Pilihan</div>
                </a>
            </li>

            <li class="menu-header small text-uppercase"><span class="menu-header-text">Hasil Pengukuran CPL</span></li>

            <li class="menu-item @if (request()->routeIs('admin.matkul.index') ||
                    request()->routeIs('admin.mahasiswa.index') ||
                    request()->routeIs('admin.mahasiswa.nilai') ||
                    request()->routeIs('admin.mahasiswa.cpl') ||
                    request()->routeIs('admin.lulusan.index')) open @endif">
                <a href="javascript:void(0);" class="menu-link menu-toggle">
                    <i class="menu-icon tf-icons bx bx-box"></i>
                    <div>Hasil Pengukuran CPL</div>
                </a>

                <ul class="menu-sub">
                    <li class="menu-item @if (request()->routeIs('admin.mahasiswa.index') ||
                            request()->routeIs('admin.mahasiswa.nilai') ||
                            request()->routeIs('admin.mahasiswa.cpl')) active @endif">
                        <a href="{{ route('admin.mahasiswa.index') }}" class="menu-link">
                            <div>Mahasiswa</div>
                        </a>
                    </li>
                    <li class="menu-item  @if (request()->routeIs('admin.matkul.index')) active @endif">
                        <a href="{{ route('admin.matkul.index') }}" class="menu-link">
                            <div>Mata Kuliah</div>
                        </a>
                    </li>
                    <li class="menu-item  @if (request()->routeIs('admin.lulusan.index')) active @endif">
                        <a href="{{ route('admin.lulusan.index') }}" class="menu-link">
                            <div>Lulusan</div>
                        </a>
                    </li>
                </ul>
            </li>

            {{-- <li class="menu-item @if (request()->routeIs('admin.ce.matrikscpl')) active @endif">
            <a href="{{ route('admin.ce.matrikscpl') }}" class="menu-link">
                <i class="menu-icon tf-icons bx bx-food-menu"></i>
                <div>Matriks CPL-Matakuliah</div>
            </a>
        </li>
        <li class="menu-item @if (request()->routeIs('admin.ce.matriks')) active @endif">
            <a href="{{ route('admin.ce.matriks') }}" class="menu-link">
                <i class="menu-icon tf-icons bx bx-food-menu"></i>
                <div>Matriks CPL-CPMK</div>
            </a>
        </li> --}}
        @elseif(Session::get('login') == 'dosen')
            <!-- Dashboards -->
            <li class="menu-item @if (request()->routeIs('dosen.home')) active @endif"">
                <a href="{{ route('dosen.home') }}" class="menu-link">
                    <i class="menu-icon tf-icons bx bx-home-circle"></i>
                    <div>Dashboards</div>
                </a>
            </li>

            <li class="menu-header small text-uppercase"><span class="menu-header-text">Rencana Pembelajaran </span>
            </li>
            <li class="menu-item @if (request()->routeIs('dosen.cpmk.*') || request()->routeIs('dosen.subcpmk.*') || request()->routeIs('dosen.bobot')) active @endif">
                <a href="{{ route('dosen.cpmk.index') }}" class="menu-link"> <i
                        class="menu-icon tf-icons bx bx-food-menu"></i>
                    <div>Data CPMK - Sub CPMK</div>
                </a>
            </li>
            {{-- <li class="menu-header small text-uppercase"><span class="menu-header-text">Hasil Pengukuran</span>
            </li>
            <li class="menu-item @if (request()->routeIs('dosen.matkul.index')) active @endif">
                <a href="{{ route('dosen.matkul.index') }}" class="menu-link"> <i
                        class="menu-icon tf-icons bx bx-food-menu"></i>
                    <div>Mata Kuliah</div>
                </a>
            </li> --}}
        @elseif(Session::get('login') == 'fakultas')
            <!-- Dashboards -->
            <li class="menu-item @if (request()->routeIs('fakultas.home')) active @endif">
                <a href="{{ route('fakultas.home') }}" class="menu-link">
                    <i class="menu-icon tf-icons bx bx-home-circle"></i>
                    <div>Dashboards</div>
                </a>
            </li>

            <li class="menu-header small text-uppercase"><span class="menu-header-text">Rencana Pembelajaran </span>
            </li>
            <li class="menu-item @if (request()->routeIs('fakultas.cpmk.*') ||
                    request()->routeIs('fakultas.subcpmk.*') ||
                    request()->routeIs('fakultas.bobot')) active @endif">
                <a href="{{ route('fakultas.cpmk.index') }}" class="menu-link"> <i
                        class="menu-icon tf-icons bx bx-food-menu"></i>
                    <div>Data CPMK - Sub CPMK</div>
                </a>
            </li>
            <li class="menu-header small text-uppercase"><span class="menu-header-text">Hasil Pengukuran</span>
            </li>
            <li class="menu-item @if (request()->routeIs('fakultas.matkul.index')) active @endif">
                <a href="{{ route('fakultas.matkul.index') }}" class="menu-link"> <i
                        class="menu-icon tf-icons bx bx-food-menu"></i>
                    <div>Mata Kuliah</div>
                </a>
            </li>
        @endif
    </ul>
</aside>
