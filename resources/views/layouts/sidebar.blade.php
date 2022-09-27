<aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">
    <div class="app-brand demo">
        <a href="{{ route('admin.home') }}" class="app-brand-link">
            <span class="app-brand-logo demo">
                <img src="{{ asset('img/logo-white.png') }}" class="h-100" alt=""
                    data-app-light-img="logo-white.png" data-app-dark-img="logo.png">
            </span>
            <span class="app-brand-text demo menu-text fw-bold ms-2">Course Evaluation</span>
        </a>

        <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto">
            <i class="bx menu-toggle-icon d-none d-xl-block fs-4 align-middle"></i>
            <i class="bx bx-x d-block d-xl-none bx-sm align-middle"></i>
        </a>
    </div>

    <div class="menu-divider mt-0"></div>

    <div class="menu-inner-shadow"></div>

    <ul class="menu-inner py-1">
        <!-- Dashboards -->
        <li class="menu-item">
            <a href="{{ route('admin.home') }}" class="menu-link">
                <i class="menu-icon tf-icons bx bx-home-circle"></i>
                <div>Dashboards</div>
            </a>
        </li>

        <li class="menu-header small text-uppercase"><span class="menu-header-text">Data Master</span></li>

        <li class="menu-item">
            <a href="javascript:void(0);" class="menu-link menu-toggle">
                <i class="menu-icon tf-icons bx bx-box"></i>
                <div>Data Master</div>
            </a>

            <ul class="menu-sub">
                <li class="menu-item">
                    <a href="{{ route('admin.mahasiswa.index') }}" class="menu-link">
                        <div>Mahasiswa</div>
                    </a>
                </li>
                <li class="menu-item">
                    <a href="{{ route('admin.matkul.index') }}" class="menu-link">
                        <div>Mata Kuliah</div>
                    </a>
                </li>
            </ul>
        </li>
        <li class="menu-header small text-uppercase"><span class="menu-header-text">Data Course Evaluation</span></li>
        <li class="menu-item @if (request()->routeIs('admin.cpl.index') ||
            request()->routeIs('admin.cpmk.index') ||
            request()->routeIs('admin.cpmk.kelola') ||
            request()->routeIs('admin.cpmk.cpl')) open @endif">
            <a href="" class="menu-link menu-toggle">
                <i class="menu-icon tf-icons bx bx-layout"></i>
                <div>CPL - CPMK</div>
            </a>
            <ul class="menu-sub">
                <li class="menu-item @if (request()->routeIs('admin.cpl.index')) active @endif">
                    <a href="{{ route('admin.cpl.index') }}" class="menu-link">
                        <div>Data CPL</div>
                    </a>
                </li>
                <li class="menu-item @if (request()->routeIs('admin.cpmk.index') ||
                    request()->routeIs('admin.cpmk.kelola') ||
                    request()->routeIs('admin.cpmk.cpl')) active @endif">
                    <a href="{{ route('admin.cpmk.index') }}" class="menu-link">
                        <div>Data CPMK</div>
                    </a>
                </li>
            </ul>
        </li>
        <li class="menu-item @if (request()->routeIs('admin.ce.matriks')) active @endif">
            <a href="{{ route('admin.ce.matriks') }}" class="menu-link">
                <i class="menu-icon tf-icons bx bx-food-menu"></i>
                <div>Matriks CPL-CPMK</div>
            </a>
        </li>
    </ul>
</aside>
