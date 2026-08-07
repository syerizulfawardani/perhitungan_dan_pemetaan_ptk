<x-layouts.app>

    @php $isAdmin = Auth::user()->hasRole('admin'); @endphp

    {{-- Page Header --}}
    <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-2 mb-4">
        <div>
            <h4 class="fw-semibold mb-1">Selamat Datang, {{ Auth::user()->name }}</h4>
            <p class="text-muted mb-0 small">
                {{ now()->translatedFormat('l, d F Y') }} &middot;
                @if ($isAdmin)
                    <span class="badge bg-primary bg-opacity-10 text-primary"></span>
                @else
                    <span class="badge bg-info bg-opacity-10 text-info">Operator Sekolah</span>
                @endif
            </p>
        </div>
    </div>

    {{-- ── Stat Cards ────────────────────────────────────────── --}}
    @if ($isAdmin)
        {{-- Admin: data global, 5 card --}}
        <div class="row row-cols-2 row-cols-xl-5 g-3 mb-4">
            <div class="col">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body d-flex align-items-center gap-3">
                        <div class="rounded-circle d-flex align-items-center justify-content-center bg-primary bg-opacity-10" style="width:52px;height:52px;flex-shrink:0;">
                            <i class="ti ti-users fs-4 text-primary"></i>
                        </div>
                        <div>
                            <div class="fw-bold fs-3">{{ number_format($totalPtk) }}</div>
                            <div class="text-muted small">Total PTK</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body d-flex align-items-center gap-3">
                        <div class="rounded-circle d-flex align-items-center justify-content-center bg-success bg-opacity-10" style="width:52px;height:52px;flex-shrink:0;">
                            <i class="ti ti-school fs-4 text-success"></i>
                        </div>
                        <div>
                            <div class="fw-bold fs-3">{{ number_format($totalSekolah) }}</div>
                            <div class="text-muted small">Total Sekolah</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body d-flex align-items-center gap-3">
                        <div class="rounded-circle d-flex align-items-center justify-content-center bg-secondary bg-opacity-10" style="width:52px;height:52px;flex-shrink:0;">
                            <i class="ti ti-map-pin fs-4 text-secondary"></i>
                        </div>
                        <div>
                            <div class="fw-bold fs-3">{{ number_format($totalKecamatan) }}</div>
                            <div class="text-muted small">Total Kecamatan</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body d-flex align-items-center gap-3">
                        <div class="rounded-circle d-flex align-items-center justify-content-center bg-info bg-opacity-10" style="width:52px;height:52px;flex-shrink:0;">
                            <i class="ti ti-file-description fs-4 text-info"></i>
                        </div>
                        <div>
                            <div class="fw-bold fs-3">{{ number_format($totalPengajuan) }}</div>
                            <div class="text-muted small">Total Pengajuan</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body d-flex align-items-center gap-3">
                        <div class="rounded-circle d-flex align-items-center justify-content-center bg-warning bg-opacity-10" style="width:52px;height:52px;flex-shrink:0;">
                            <i class="ti ti-clock fs-4 text-warning"></i>
                        </div>
                        <div>
                            <div class="fw-bold fs-3">{{ number_format($pengajuanMenunggu) }}</div>
                            <div class="text-muted small">Perlu Diproses</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @else
        {{-- Operator: data scoped ke sekolah miliknya, 4 card --}}
        <div class="row row-cols-2 row-cols-xl-4 g-3 mb-4">
            <div class="col">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body d-flex align-items-center gap-3">
                        <div class="rounded-circle d-flex align-items-center justify-content-center bg-primary bg-opacity-10" style="width:52px;height:52px;flex-shrink:0;">
                            <i class="ti ti-users fs-4 text-primary"></i>
                        </div>
                        <div>
                            <div class="fw-bold fs-3">{{ number_format($totalPtk) }}</div>
                            <div class="text-muted small">PTK Sekolah Saya</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body d-flex align-items-center gap-3">
                        <div class="rounded-circle d-flex align-items-center justify-content-center bg-success bg-opacity-10" style="width:52px;height:52px;flex-shrink:0;">
                            <i class="ti ti-school fs-4 text-success"></i>
                        </div>
                        <div>
                            <div class="fw-bold fs-3">{{ number_format($totalSekolah) }}</div>
                            <div class="text-muted small">Sekolah Saya</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body d-flex align-items-center gap-3">
                        <div class="rounded-circle d-flex align-items-center justify-content-center bg-info bg-opacity-10" style="width:52px;height:52px;flex-shrink:0;">
                            <i class="ti ti-file-description fs-4 text-info"></i>
                        </div>
                        <div>
                            <div class="fw-bold fs-3">{{ number_format($totalPengajuan) }}</div>
                            <div class="text-muted small">Pengajuan Saya</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body d-flex align-items-center gap-3">
                        <div class="rounded-circle d-flex align-items-center justify-content-center bg-warning bg-opacity-10" style="width:52px;height:52px;flex-shrink:0;">
                            <i class="ti ti-clock fs-4 text-warning"></i>
                        </div>
                        <div>
                            <div class="fw-bold fs-3">{{ number_format($pengajuanMenunggu) }}</div>
                            <div class="text-muted small">Perlu Diproses</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    {{-- ── Chart + PTK per Kategori (isi sama, datanya sudah discope di controller) ── --}}
    <div class="row g-3 mb-4">
        <div class="col-12 col-lg-8">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white border-bottom py-3">
                    <h6 class="mb-0 fw-semibold">{{ $isAdmin ? 'Pengajuan PTK' : 'Pengajuan Saya' }} per Bulan (12 Bulan Terakhir)</h6>
                </div>
                <div class="card-body">
                    <div id="chartPengajuan"></div>
                </div>
            </div>
        </div>
        <div class="col-12 col-lg-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white border-bottom py-3">
                    <h6 class="mb-0 fw-semibold">PTK per Kategori</h6>
                </div>
                <div class="card-body">
                    @forelse ($ptkPerKategori as $kat)
                        <div class="mb-3">
                            <div class="d-flex justify-content-between mb-1">
                                <span class="small fw-medium">{{ $kat->jenis_kategori }}</span>
                                <span class="small text-muted fw-semibold">{{ $kat->data_ptk_count }}</span>
                            </div>
                            <div class="progress" style="height:6px;">
                                <div class="progress-bar bg-primary"
                                     style="width:{{ $totalPtk > 0 ? round($kat->data_ptk_count / $totalPtk * 100) : 0 }}%">
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-4">
                            <i class="ti ti-chart-bar fs-1 text-muted d-block mb-2"></i>
                            <p class="text-muted small mb-0">Belum ada data PTK</p>
                        </div>
                    @endforelse

                    <hr class="my-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="text-muted small">Total PTK</span>
                        <strong class="fs-5">{{ number_format($totalPtk) }}</strong>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ── Pengajuan Terbaru ──────────────────────────────────── --}}
    <div class="row g-3">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-bottom d-flex align-items-center justify-content-between py-3">
                    <h6 class="mb-0 fw-semibold">
                        {{ $isAdmin ? 'Pengajuan PTK Terbaru' : 'Pengajuan PTK Terbaru Saya' }}
                    </h6>
                    <a href="{{ route('pengajuan-ptk.index') }}" class="btn btn-sm btn-outline-primary">
                        Lihat Semua
                    </a>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-white">
                                <tr>
                                    <th class="ps-4 py-3 small fw-semibold text-muted">#</th>
                                    <th class="py-3 small fw-semibold text-muted">Nomor Pengajuan</th>
                                    <th class="py-3 small fw-semibold text-muted">Kategori</th>
                                    @if ($isAdmin)
                                        <th class="py-3 small fw-semibold text-muted">Diajukan Oleh</th>
                                    @endif
                                    <th class="py-3 small fw-semibold text-muted">Tanggal</th>
                                    <th class="py-3 small fw-semibold text-muted">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($pengajuanTerbaru as $i => $pgj)
                                    @php $cfg = \App\Models\PengajuanPtk::$statusConfig[$pgj->status] ?? ['label' => $pgj->status, 'class' => 'bg-secondary', 'icon' => ''] @endphp
                                    <tr>
                                        <td class="ps-4 text-muted small">{{ $i + 1 }}</td>
                                        <td class="fw-semibold">{{ $pgj->nomor_pengajuan }}</td>
                                        <td>
                                            <span class="badge bg-light text-dark border small">
                                                {{ $pgj->kategori->jenis_kategori ?? '-' }}
                                            </span>
                                        </td>
                                        @if ($isAdmin)
                                            <td class="small">{{ $pgj->operator->name ?? '-' }}</td>
                                        @endif
                                        <td class="small text-muted">{{ $pgj->created_at->translatedFormat('d M Y') }}</td>
                                        <td>
                                            <span class="badge {{ $cfg['class'] }}">
                                                <i class="ti {{ $cfg['icon'] }} me-1"></i>{{ $cfg['label'] }}
                                            </span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="{{ $isAdmin ? 6 : 5 }}" class="text-center py-5 text-muted">
                                            <i class="ti ti-inbox fs-1 d-block mb-2"></i>
                                            Belum ada pengajuan PTK
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
    const chartData = @json($pengajuanPerBulan);
    new ApexCharts(document.querySelector('#chartPengajuan'), {
        chart: { type: 'bar', height: 280, toolbar: { show: false }, fontFamily: 'inherit' },
        series: [
            { name: 'Menunggu',  data: chartData.map(d => d.menunggu) },
            { name: 'Diproses',  data: chartData.map(d => d.proses) },
            { name: 'Disetujui', data: chartData.map(d => d.disetujui) },
            { name: 'Ditolak',   data: chartData.map(d => d.ditolak) },
        ],
        xaxis: { categories: chartData.map(d => d.bulan), labels: { style: { fontSize: '11px' } } },
        colors: ['#539BFF', '#FFAE1F', '#13DEB9', '#FA896B'],
        plotOptions: { bar: { borderRadius: 3, columnWidth: '55%' } },
        dataLabels: { enabled: false },
        legend: { position: 'top' },
        grid: { borderColor: '#f0f0f0', strokeDashArray: 4 },
    }).render();
    </script>
    @endpush

</x-layouts.app>
