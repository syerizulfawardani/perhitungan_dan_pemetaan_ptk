<aside class="left-sidebar">
    <div>
        <div class="brand-logo d-flex align-items-center justify-content-between">
            <a href="{{ route('dashboard') }}" class="text-nowrap logo-img">
                <h3 class="mb-0">SIMETA-PTK</h3>
            </a>
            <div class="close-btn d-xl-none d-block sidebartoggler cursor-pointer" id="sidebarCollapse">
                <i class="ti ti-x fs-8"></i>
            </div>
        </div>
        <nav class="sidebar-nav scroll-sidebar" data-simplebar="">
            <ul id="sidebarnav">
                <li class="nav-small-cap">
                    <i class="ti ti-dots nav-small-cap-icon fs-4"></i>
                    <span class="hide-menu">HOME</span>
                </li>
                <li class="sidebar-item">
                    <a class="sidebar-link @if(request()->is('dashboard')) active @endif"
                       href="{{ route('dashboard') }}" aria-expanded="false">
                        <span><i class="ti ti-layout-dashboard"></i></span>
                        <span class="hide-menu">Dashboard</span>
                    </a>
                </li>

                @role('admin')
                <li class="nav-small-cap">
                    <i class="ti ti-dots nav-small-cap-icon fs-4"></i>
                    <span class="hide-menu">MASTER DATA</span>
                </li>
                <li class="sidebar-item">
                    <a class="sidebar-link @if(request()->is('dashboard/kecamatan*')) active @endif"
                       href="{{ route('kecamatan') }}" aria-expanded="false">
                        <span><i class="ti ti-map"></i></span>
                        <span class="hide-menu">Kecamatan</span>
                    </a>
                </li>
                <li class="sidebar-item">
                    <a class="sidebar-link @if(request()->is('dashboard/sekolah*')) active @endif"
                       href="{{ route('sekolah') }}" aria-expanded="false">
                        <span><i class="ti ti-school"></i></span>
                        <span class="hide-menu">Sekolah</span>
                    </a>
                </li>
                @endrole

                @role('operator_sekolah')
                <li class="nav-small-cap">
                    <i class="ti ti-dots nav-small-cap-icon fs-4"></i>
                    <span class="hide-menu">SEKOLAH</span>
                </li>
                <li class="sidebar-item">
                    <a class="sidebar-link @if(request()->is('dashboard/sekolah-saya*')) active @endif"
                       href="{{ route('sekolah.my') }}" aria-expanded="false">
                        <span><i class="ti ti-school"></i></span>
                        <span class="hide-menu">Sekolah Saya</span>
                    </a>
                </li>
                @endrole

                <li class="nav-small-cap">
                    <i class="ti ti-dots nav-small-cap-icon fs-4"></i>
                    <span class="hide-menu">PTK</span>
                </li>
                @role('admin')
                <li class="sidebar-item">
                    <a class="sidebar-link @if(request()->is('dashboard/data-ptk*')) active @endif"
                       href="{{ route('data-ptk') }}" aria-expanded="false">
                        <span><i class="ti ti-users"></i></span>
                        <span class="hide-menu">Data PTK</span>
                    </a>
                </li>
                @endrole
                <li class="sidebar-item">
                    <a class="sidebar-link @if(request()->is('dashboard/pengajuan-ptk*')) active @endif"
                       href="{{ route('pengajuan-ptk.index') }}" aria-expanded="false">
                        <span><i class="ti ti-file-description"></i></span>
                        <span class="hide-menu">Pengajuan PTK</span>
                    </a>
                </li>
            </ul>
        </nav>
    </div>
</aside>
