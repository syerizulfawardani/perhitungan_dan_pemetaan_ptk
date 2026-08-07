<aside class="left-sidebar">
    <style>
        .left-sidebar {
            background-color: #0f1f3d !important;
        }

        .left-sidebar .sidebar-link {
            color: rgba(255, 255, 255, 0.75) !important;
        }

        .left-sidebar .sidebar-link i {
            color: rgba(255, 255, 255, 0.75) !important;
        }

        .left-sidebar .sidebar-link:hover {
            background-color: rgba(255, 255, 255, 0.08) !important;
            color: #ffffff !important;
        }

        .left-sidebar .sidebar-link.active,
        .left-sidebar .sidebar-item.selected>.sidebar-link,
        .left-sidebar .sidebar-nav ul .sidebar-item>.sidebar-link.active,
        .left-sidebar .sidebar-nav ul .sidebar-item.selected>.sidebar-link {
            background-color: rgba(255, 255, 255, 0.15) !important;
            color: #ffffff !important;
            border-left: 3px solid rgba(255, 255, 255, 0.8) !important;
        }

        .left-sidebar .sidebar-link.active i,
        .left-sidebar .sidebar-item.selected>.sidebar-link i,
        .left-sidebar .sidebar-link:hover i {
            color: #ffffff !important;
        }

        .left-sidebar .nav-small-cap {
            color: rgba(255, 255, 255, 0.4) !important;
        }

        .left-sidebar .brand-logo h3 {
            color: #ffffff !important;
        }

        .left-sidebar .sidebartoggler i {
            color: #ffffff !important;
        }

        .brand-logo {
            min-height: 80px;
            background-color: #0f1f3d !important;
            border-bottom: 1px solid rgba(255, 255, 255, .08);
        }

        .brand-logo-img {
            width: 50px;
            height: 50px;
            object-fit: contain;
            border-radius: 8px;
            background: #fff;
            padding: 3px;
        }

        .brand-logo h5 {
            font-size: 20px;
            letter-spacing: .5px;
        }

        .brand-subtitle {
            color: #b8bef5;
            font-size: 10px;
            letter-spacing: 1px;
            text-transform: uppercase;
        }
    </style>

    <div>
        <div class="brand-logo d-flex align-items-center justify-content-between px-3">
            <a href="{{ route('dashboard') }}" class="text-decoration-none d-flex align-items-center">

                <img src="{{ asset('template/assets/images/logo_ketapang.jpg') }}" alt="Logo Kabupaten Ketapang"
                    class="brand-logo-img">

                <div class="ms-2">
                    <h5 class="text-white fw-bold mb-0">
                        SIMETA-PTK
                    </h5>

                    <small class="brand-subtitle">
                        DINAS PENDIDIKAN
                    </small>
                </div>
            </a>

            <div class="close-btn d-xl-none d-block sidebartoggler cursor-pointer" id="sidebarCollapse">
                <i class="ti ti-x fs-8 text-white"></i>
            </div>
        </div>
        <nav class="sidebar-nav scroll-sidebar" data-simplebar="">
            <ul id="sidebarnav">
                <li class="nav-small-cap">
                    <i class="ti ti-dots nav-small-cap-icon fs-4"></i>
                    <span class="hide-menu">HOME</span>
                </li>
                <li class="sidebar-item">
                    <a class="sidebar-link @if (request()->is('dashboard')) active @endif"
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
                        <a class="sidebar-link @if (request()->is('dashboard/kecamatan*')) active @endif"
                            href="{{ route('kecamatan') }}" aria-expanded="false">
                            <span><i class="ti ti-map"></i></span>
                            <span class="hide-menu">Kecamatan</span>
                        </a>
                    </li>
                    <li class="sidebar-item">
                        <a class="sidebar-link has-arrow @if (request()->is('dashboard/sekolah*')) active @endif"
                            href="javascript:vid(0)" aria-expanded="false">

                            <span>
                                <i class="ti ti-school"></i>
                            </span>
                            <span class="hide-menu">
                                Sekolah
                            </span>
                        </a>

                        <ul aria-expanded="false"class="collapse first-level @if (request()->is('dashboard/sekolah*')) in @endif">
                            @foreach (['PAUD', 'SD', 'SMP'] as $jenjang)
                                <li class="sidebar-item">
                                    <a href="{{ route('sekolah', ['jenjang' => $jenjang]) }}"
                                        class="sidebar-link @if (request('jenjang') === $jenjang) active @endif">
                                        <div class="round-16 d-flex align-items-center justify-content-center">
                                            <i class="ti ti-circle"></i>
                                        </div>
                                        <span class="hide-menu">{{ $jenjang }}</span>
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </li>


                    <li class="sidebar-item">
                        <a class="sidebar-link @if (request()->is('dashboard/operator*')) active @endif"
                            href="{{ route('operator') }}" aria-expanded="false">
                            <span><i class="ti ti-users"></i></span>
                            <span class="hide-menu">Operator</span>
                        </a>
                    </li>
                @endrole

                @role('operator_sekolah')
                    <li class="nav-small-cap">
                        <i class="ti ti-dots nav-small-cap-icon fs-4"></i>
                        <span class="hide-menu">SEKOLAH</span>
                    </li>
                    <li class="sidebar-item">
                        <a class="sidebar-link @if (request()->is('dashboard/sekolah-saya*')) active @endif"
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
                <li class="sidebar-item">
                    <a class="sidebar-link has-arrow @if (request()->is('dashboard/data-ptk*')) active @endif"
                        href="javascript:void(0)" aria-expanded="false">
                        <span><i class="ti ti-users"></i></span>
                        <span class="hide-menu">Data PTK</span>
                    </a>
                    <ul aria-expanded="false" class="collapse first-level @if (request()->is('dashboard/data-ptk*')) in @endif">
                        <li class="sidebar-item">
                            <a href="{{ route('data-ptk', ['kategori' => 'Pendidik']) }}"
                                class="sidebar-link @if (request('kategori') === 'Pendidik') active @endif">
                                <div class="round-16 d-flex align-items-center justify-content-center">
                                    <i class="ti ti-circle"></i>
                                </div>
                                <span class="hide-menu">Pendidik</span>
                            </a>
                        </li>
                        <li class="sidebar-item">
                            <a href="{{ route('data-ptk', ['kategori' => 'Tenaga Kependidikan']) }}"
                                class="sidebar-link @if (request('kategori') === 'Tenaga Kependidikan') active @endif">
                                <div class="round-16 d-flex align-items-center justify-content-center">
                                    <i class="ti ti-circle"></i>
                                </div>
                                <span class="hide-menu">Tenaga Kependidikan</span>
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="sidebar-item">
                    <a class="sidebar-link @if (request()->is('dashboard/pengajuan-ptk*')) active @endif"
                        href="{{ route('pengajuan-ptk.index') }}" aria-expanded="false">
                        <span><i class="ti ti-file-description"></i></span>
                        <span class="hide-menu">Pengajuan PTK</span>
                    </a>
                </li>

                <li class="nav-small-cap">
                    <i class="ti ti-dots nav-small-cap-icon fs-4"></i>
                    <span class="hide-menu">PEMETAAN</span>
                </li>
                <li class="sidebar-item">
                    <a class="sidebar-link @if (request()->is('dashboard/peta-ptk*')) active @endif"
                        href="{{ route('peta-ptk') }}" aria-expanded="false">
                        <span><i class="ti ti-map-2"></i></span>
                        <span class="hide-menu">Pesebaran PTK</span>
                    </a>
                </li>
            </ul>
        </nav>
    </div>
</aside>
