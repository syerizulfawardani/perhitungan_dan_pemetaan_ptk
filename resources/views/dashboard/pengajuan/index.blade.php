<x-layouts.app>
<div class="container-fluid">

    {{-- Header --}}
    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <h4 class="mb-0 fw-semibold">Pengajuan PTK</h4>
            <p class="text-muted mb-0 small">Permintaan pengusulan Pendidik dan Tenaga Kependidikan</p>
        </div>
        @can('menage pengajuan')
        <a href="{{ route('pengajuan-ptk.create') }}" class="btn btn-primary d-flex align-items-center gap-2">
            <i class="ti ti-plus fs-5"></i> Buat Pengajuan
        </a>
        @endcan
    </div>

    {{-- Alert --}}
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            <i class="ti ti-circle-check me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show">
            <i class="ti ti-alert-circle me-2"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- Stats Cards (admin only) --}}
    @role('admin')
    @if ($stats)
    <div class="row g-3 mb-4">
        @foreach ([
            ['key'=>'menunggu',  'label'=>'Menunggu',  'color'=>'secondary', 'icon'=>'ti-clock'],
            ['key'=>'proses',    'label'=>'Diproses',  'color'=>'warning',   'icon'=>'ti-loader'],
            ['key'=>'disetujui', 'label'=>'Disetujui', 'color'=>'success',   'icon'=>'ti-circle-check'],
            ['key'=>'ditolak',   'label'=>'Ditolak',   'color'=>'danger',    'icon'=>'ti-circle-x'],
        ] as $s)
        <div class="col-sm-6 col-xl-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body d-flex align-items-center gap-3">
                    <div class="bg-{{ $s['color'] }} bg-opacity-15 rounded p-3">
                        <i class="ti {{ $s['icon'] }} fs-3 text-{{ $s['color'] }}"></i>
                    </div>
                    <div>
                        <p class="text-muted small mb-0">{{ $s['label'] }}</p>
                        <h3 class="fw-bold mb-0">{{ $stats[$s['key']] }}</h3>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>
    @endif
    @endrole

    {{-- Filter --}}
    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('pengajuan-ptk.index') }}" class="row g-3 align-items-end">
                <div class="col-md-4">
                    <label class="form-label small fw-semibold">Nama Sekolah</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="ti ti-search"></i></span>
                        <input type="text" name="search" class="form-control"
                               placeholder="Cari Nama Sekolah..." value="{{ request('search') }}">
                    </div>
                </div>
                <div class="col-md-3">
                    <label class="form-label small fw-semibold">Kategori</label>
                    <select name="kategori" class="form-select">
                        <option value="">Semua Kategori</option>
                        @foreach ($kategoris as $kat)
                            <option value="{{ $kat->id }}" {{ request('kategori') == $kat->id ? 'selected' : '' }}>
                                {{ $kat->jenis_kategori }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label small fw-semibold">Status</label>
                    <select name="status" class="form-select">
                        <option value="">Semua Status</option>
                        @foreach (\App\Models\PengajuanPtk::$statusConfig as $val => $cfg)
                            <option value="{{ $val }}" {{ request('status') == $val ? 'selected' : '' }}>
                                {{ $cfg['label'] }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3 d-flex gap-2">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="ti ti-filter me-1"></i>Filter
                    </button>
                    <a href="{{ route('pengajuan-ptk.index') }}" class="btn btn-outline-secondary">
                        <i class="ti ti-refresh"></i>
                    </a>
                </div>
            </form>
        </div>
    </div>

    {{-- Tabel --}}
    <div class="card">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-4" style="width:50px">#</th>
                            <th>Sekolah</th>
                            <th>Kategori</th>
                            <th>Tanggal Pengajuan</th>
                            @role('admin')
                            <th>Diajukan Oleh</th>
                            @endrole
                            <th>Status</th>
                            <th class="text-center pe-4">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($pengajuans as $item)
                            @php $cfg = \App\Models\PengajuanPtk::$statusConfig[$item->status] ?? null @endphp
                            <tr>
                                <td class="ps-4 text-muted small">
                                    {{ $pengajuans->firstItem() + $loop->index }}
                                </td>
                                <td class="fw-semibold">{{ $item->nama_ptk }}</td>
                                <td>
                                    <span class="badge bg-light text-dark border small">
                                        {{ $item->kategori->jenis_kategori ?? '-' }}
                                    </span>
                                </td>
                                <td class="text-muted small">
                                    {{ $item->tmt_pengangkatan?->format('d M Y') }}
                                </td>
                                @role('admin')
                                <td class="small">{{ $item->operator->name ?? '-' }}</td>
                                @endrole
                                <td>
                                    @if ($cfg)
                                        <span class="badge {{ $cfg['class'] }}">
                                            <i class="ti {{ $cfg['icon'] }} me-1"></i>{{ $cfg['label'] }}
                                        </span>
                                    @endif
                                </td>
                                <td class="text-center pe-4">
                                    <div class="d-flex justify-content-center gap-1">
                                        <a href="{{ route('pengajuan-ptk.show', $item) }}"
                                           class="btn btn-sm btn-outline-info" title="Detail">
                                            <i class="ti ti-eye"></i>
                                        </a>
                                        @role("operator_sekolah")
                                        @if (in_array($item->status, ['menunggu', 'ditolak']))
                                            <a href="{{ route('pengajuan-ptk.edit', $item) }}"
                                               class="btn btn-sm btn-outline-warning" title="Edit">
                                                <i class="ti ti-pencil"></i>
                                            </a>
                                            <form action="{{ route('pengajuan-ptk.destroy', $item) }}"
                                                  method="POST"
                                                  onsubmit="return confirm('Yakin hapus pengajuan ini?')">
                                                @csrf @method('DELETE')
                                                <button class="btn btn-sm btn-outline-danger" title="Hapus">
                                                    <i class="ti ti-trash"></i>
                                                </button>
                                            </form>
                                        @endif
                                        @endrole
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="text-center py-5 text-muted">
                                    <i class="ti ti-inbox fs-1 d-block mb-2"></i>
                                    Belum ada data pengajuan PTK
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="card-footer bg-white border-top">
                <div class="d-flex flex-column flex-md-row justify-content-between align-items-center gap-3">
                    <div class="text-muted small">
                        Menampilkan
                        <strong>{{ $pengajuans->firstItem() ?? 0 }}</strong>
                        -
                        <strong>{{ $pengajuans->lastItem() ?? 0 }}</strong>
                        dari
                        <strong>{{ $pengajuans->total() }}</strong>
                        data.
                    </div>

                    <div>
                        {{ $pengajuans->links('pagination::bootstrap-5') }}
                    </div>
                </div>
            </div
        </div>
    </div>

</div>
</x-layouts.app>
