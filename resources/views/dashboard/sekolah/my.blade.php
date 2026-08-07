<x-layouts.app>
    <div class="container-fluid">

        <div class="d-flex align-items-center justify-content-between mb-4">
            <div>
                <h4 class="mb-0 fw-semibold">Sekolah Saya</h4>
                <p class="text-muted mb-0 small">Profil sekolah yang terdaftar atas akun Anda</p>
            </div>
        </div>

        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if ($sekolah->isEmpty())
            <div class="card border-0 shadow-sm">
                <div class="card-body text-center py-5">
                    <i class="ti ti-school-off" style="font-size:3rem;color:#ccc"></i>
                    <h5 class="mt-3 text-muted">Belum Ada Sekolah</h5>
                    <p class="text-muted small mb-0">Akun Anda belum ditautkan ke sekolah manapun.<br>Hubungi admin
                        untuk penambahan data.</p>
                </div>
            </div>
        @else
            @foreach ($sekolah as $s)
                @php
                    $jenjang = $s->jenjang_sekolah;
                    $gradients = [
                        'SD' => 'linear-gradient(135deg, #1565C0 0%, #42A5F5 100%)',
                        'SMP' => 'linear-gradient(135deg, #2E7D32 0%, #66BB6A 100%)',
                        'PAUD' => 'linear-gradient(135deg, #E65100 0%, #FFA726 100%)',
                    ];
                    $gradient = $gradients[$jenjang] ?? 'linear-gradient(135deg, #37474F 0%, #78909C 100%)';
                    $totalPtk = $s->dataPtk->count();
                @endphp

                <div class="card border-0 shadow-sm mb-4 overflow-hidden">

                    {{-- ── Banner ── --}}
                    <div style="background: {{ $gradient }}; min-height: 160px; position:relative;"
                        class="p-4 d-flex align-items-end">
                        {{-- Dekorasi lingkaran --}}
                        <div
                            style="position:absolute;top:-30px;right:-30px;width:200px;height:200px;border-radius:50%;background:rgba(255,255,255,.08)">
                        </div>
                        <div
                            style="position:absolute;top:20px;right:80px;width:100px;height:100px;border-radius:50%;background:rgba(255,255,255,.06)">
                        </div>

                        <div class="d-flex align-items-end gap-4 w-100">
                            {{-- Ikon sekolah --}}
                            <div class="rounded-3 d-flex align-items-center justify-content-center flex-shrink-0"
                                style="width:90px;height:90px;background:rgba(255,255,255,.2);backdrop-filter:blur(4px);border:2px solid rgba(255,255,255,.3)">
                                <i class="ti ti-school text-white" style="font-size:2.5rem"></i>
                            </div>

                            {{-- Nama & info ringkas --}}
                            <div class="flex-grow-1 pb-1">
                                <div class="d-flex align-items-center gap-2 flex-wrap mb-1">
                                    <span class="badge text-white px-3 py-1 rounded-pill fw-normal"
                                        style="background:rgba(255,255,255,.25);font-size:.75rem">
                                        {{ $jenjang ?: 'Lainnya' }}
                                    </span>
                                    <span class="badge text-white px-3 py-1 rounded-pill fw-normal"
                                        style="background:rgba(255,255,255,.25);font-size:.75rem;text-transform:capitalize">
                                        {{ $s->scope_pengelolaan }}
                                    </span>
                                </div>
                                <h4 class="text-white fw-bold mb-0">{{ $s->nama_sekolah }}</h4>
                                <small class="text-white text-opacity-75">NPSN: {{ $s->npsn_sekolah }}</small>
                            </div>
                        </div>
                    </div>

                    {{-- ── Statistik strip ── --}}
                    <div class="border-bottom">
                        <div class="row g-0 text-center">
                            <div class="col-4 py-3 border-end">
                                <div class="fs-4 fw-bold text-primary">{{ $totalPtk }}</div>
                                <div class="small text-muted">Jumlah PTK</div>
                            </div>
                            <div class="col-4 py-3 border-end">
                                <div class="fs-4 fw-bold text-success">{{ $jenjang ?: '-' }}</div>
                                <div class="small text-muted">Jenjang</div>
                            </div>
                            <div class="col-4 py-3">
                                <div class="fs-5 fw-bold text-warning text-capitalize">{{ $s->scope_pengelolaan }}</div>
                                <div class="small text-muted">Pengelolaan</div>
                            </div>
                        </div>
                    </div>

                    {{-- ── Detail info ── --}}
                    <div class="card-body">
                        <div class="row g-4">
                            {{-- Kolom kiri: Info sekolah --}}
                            <div class="col-md-6">
                                <h6 class="fw-semibold text-uppercase text-muted mb-3"
                                    style="font-size:.7rem;letter-spacing:.08em">
                                    <i class="ti ti-info-circle me-1"></i> Informasi Sekolah
                                </h6>
                                <ul class="list-unstyled mb-0">
                                    <li class="d-flex gap-3 mb-3">
                                        <div class="rounded-2 d-flex align-items-center justify-content-center flex-shrink-0"
                                            style="width:36px;height:36px;background:#f0f4ff">
                                            <i class="ti ti-map-pin text-primary"></i>
                                        </div>
                                        <div>
                                            <div class="small text-muted">Alamat</div>
                                            <div class="fw-medium">{{ $s->alamat_sekolah }}</div>
                                        </div>
                                    </li>
                                    <li class="d-flex gap-3 mb-3">
                                        <div class="rounded-2 d-flex align-items-center justify-content-center flex-shrink-0"
                                            style="width:36px;height:36px;background:#f0fff4">
                                            <i class="ti ti-building-community text-success"></i>
                                        </div>
                                        <div>
                                            <div class="small text-muted">Kecamatan</div>
                                            <div class="fw-medium">{{ $s->kecamatan?->nama_kecamatan ?? '-' }}</div>
                                        </div>
                                    </li>
                                    <li class="d-flex gap-3">
                                        <div class="rounded-2 d-flex align-items-center justify-content-center flex-shrink-0"
                                            style="width:36px;height:36px;background:#fff8f0">
                                            <i class="ti ti-map text-warning"></i>
                                        </div>
                                        <div>
                                            <div class="small text-muted">Kabupaten</div>
                                            <div class="fw-medium">{{ $s->kabupaten?->nama_kabupaten ?? '-' }}</div>
                                        </div>
                                    </li>
                                </ul>
                            </div>

                            {{-- Kolom kanan: Data PTK ringkas --}}
                            <div class="col-md-6">
                                <div class="d-flex align-items-center justify-content-between mb-3">
                                    <h6 class="fw-semibold text-uppercase text-muted mb-0"
                                        style="font-size:.7rem;letter-spacing:.08em">
                                        <i class="ti ti-users me-1"></i>
                                        Data PTK Sekolah
                                    </h6>
                                    <a href="{{ route('sekolah.data-ptk', $s) }}"
                                        class="btn btn-sm btn-outline-primary">
                                        Lihat Semua <i class="ti ti-arrow-right ms-1"></i>
                                    </a>
                                </div>

                                @php
                                    $pendidik = $s->dataPtk->where('kategori_id', 1);
                                    $tenaga = $s->dataPtk->where('kategori_id', 2);
                                @endphp

                                @if ($s->dataPtk->isEmpty())
                                    <div class="rounded-3 p-4 text-center" style="background:#f8f9fa">
                                        <i class="ti ti-user-off text-muted" style="font-size:2rem"></i>
                                        <p class="text-muted small mb-0 mt-2">
                                            Belum ada data PTK yang terdaftar
                                            <br>
                                            di sekolah ini.
                                        </p>
                                    </div>
                                @else
                                    {{-- ==================== PENDIDIK ==================== --}}
                                    <div class="mb-4">

                                        <div class="d-flex justify-content-between align-items-center mb-2">

                                            <h6 class="fw-semibold text-primary mb-0">
                                                <i class="ti ti-school me-1"></i>
                                                Pendidik
                                            </h6>

                                            <span class="badge bg-primary">
                                                {{ $pendidik->count() }}
                                            </span>

                                        </div>

                                        @forelse($pendidik->take(3) as $ptk)
                                            <div class="d-flex align-items-center gap-2 p-2 rounded-2 mb-2"
                                                style="background:#f8f9fa">

                                                <div class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0"
                                                    style="width:34px;height:34px;background:#e3eaff;font-size:.75rem;font-weight:700;color:#3d5af1">

                                                    {{ strtoupper(substr($ptk->nama_ptk, 0, 1)) }}

                                                </div>

                                                <div class="flex-grow-1 overflow-hidden">

                                                    <div class="fw-medium text-truncate">
                                                        {{ $ptk->nama_ptk }}
                                                    </div>

                                                    <small class="text-muted">

                                                        {{ $ptk->jabatan?->nama_jabatan ?? '-' }}

                                                    </small>

                                                </div>

                                            </div>

                                        @empty

                                            <div class="text-muted small fst-italic">
                                                Tidak ada data pendidik.
                                            </div>
                                        @endforelse
                                        @if ($pendidik->count() > 3)
                                            <div class="text-center mt-2">

                                                <small class="text-muted">

                                                    +{{ $pendidik->count() - 3 }} pendidik lainnya

                                                </small>

                                            </div>
                                        @endif

                                    </div>

                                    {{-- ==================== TENAGA KEPENDIDIKAN ==================== --}}

                                    <div>

                                        <div class="d-flex justify-content-between align-items-center mb-2">

                                            <h6 class="fw-semibold text-success mb-0">
                                                <i class="ti ti-briefcase me-1"></i>
                                                Tenaga Kependidikan
                                            </h6>

                                            <span class="badge bg-success">
                                                {{ $tenaga->count() }}
                                            </span>

                                        </div>

                                        @forelse($tenaga->take(3) as $ptk)
                                            <div class="d-flex align-items-center gap-2 p-2 rounded-2 mb-2"
                                                style="background:#f8f9fa">

                                                <div class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0"
                                                    style="width:34px;height:34px;background:#dff5e1;font-size:.75rem;font-weight:700;color:#198754">

                                                    {{ strtoupper(substr($ptk->nama_ptk, 0, 1)) }}

                                                </div>

                                                <div class="flex-grow-1 overflow-hidden">

                                                    <div class="fw-medium text-truncate">
                                                        {{ $ptk->nama_ptk }}
                                                    </div>

                                                    <small class="text-muted">

                                                        {{ $ptk->jabatan?->nama_jabatan ?? '-' }}

                                                    </small>

                                                </div>

                                            </div>

                                        @empty

                                            <div class="text-muted small fst-italic">
                                                Tidak ada tenaga kependidikan.
                                            </div>
                                        @endforelse
                                        @if ($tenaga->count() > 3)
                                            <div class="text-center mt-2">

                                                <small class="text-muted">

                                                    +{{ $tenaga->count() - 3 }} tenaga kependidikan lainnya

                                                </small>

                                            </div>
                                        @endif

                                    </div>
                                @endif

                            </div>
                        </div>
                    </div>

                    {{-- ── Footer ── --}}
                    <div
                        class="card-footer bg-transparent border-top d-flex align-items-center justify-content-between py-3">
                        <small class="text-muted">
                            <i class="ti ti-calendar me-1"></i>
                            Terdaftar:
                            {{ $s->created_at ? \Carbon\Carbon::parse($s->created_at)->translatedFormat('d F Y') : '-' }}
                        </small>
                        <div class="d-flex align-items-center gap-1">
                            <span class="badge bg-success-subtle text-success rounded-pill px-3 py-1">
                                <i class="ti ti-circle-check me-1"></i> Aktif
                            </span>
                        </div>
                    </div>

                </div>
            @endforeach
        @endif

    </div>
</x-layouts.app>
