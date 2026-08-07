<x-layouts.app>
    <div class="container-fluid">

        {{-- ── Header ── --}}
        <div class="d-flex align-items-center justify-content-between mb-4">
            <div>
                <a href="{{ route('sekolah.my') }}" class="text-muted text-decoration-none small mb-1 d-inline-flex align-items-center gap-1">
                    <i class="ti ti-arrow-left"></i> Kembali ke Sekolah Saya
                </a>
                <h4 class="mb-0 fw-semibold mt-1">Data PTK — {{ $sekolah->nama_sekolah }}</h4>
                <p class="text-muted mb-0 small">NPSN: {{ $sekolah->npsn_sekolah }}</p>
            </div>
        </div>

        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        {{-- ── Filter & Search ── --}}
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-body">
                <form method="GET" action="{{ route('sekolah.data-ptk', $sekolah) }}" class="row g-2">
                    <div class="col-md-6">
                        <div class="input-group">
                            <span class="input-group-text bg-white"><i class="ti ti-search"></i></span>
                            <input type="text" name="search" value="{{ request('search') }}"
                                class="form-control" placeholder="Cari nama atau NIP...">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <select name="kategori" class="form-select">
                            <option value="">Semua Kategori</option>
                            @foreach ($kategoris as $kategori)
                                <option value="{{ $kategori->id }}" @selected(request('kategori') == $kategori->id)>
                                    {{ $kategori->jenis_kategori }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3 d-flex gap-2">
                        <button type="submit" class="btn btn-primary flex-grow-1">
                            <i class="ti ti-filter me-1"></i> Filter
                        </button>
                        @if (request('search') || request('kategori'))
                            <a href="{{ route('sekolah.data-ptk', $sekolah) }}" class="btn btn-outline-secondary">
                                <i class="ti ti-x"></i>
                            </a>
                        @endif
                    </div>
                </form>
            </div>
        </div>

        {{-- ── Tabel Data PTK ── --}}
        <div class="card border-0 shadow-sm">
            @if ($dataPtk->isEmpty())
                <div class="card-body text-center py-5">
                    <i class="ti ti-user-off text-muted" style="font-size:3rem"></i>
                    <h5 class="mt-3 text-muted">Tidak Ada Data</h5>
                    <p class="text-muted small mb-0">
                        @if (request('search') || request('kategori'))
                            Tidak ditemukan data yang cocok dengan filter.
                        @else
                            Belum ada PTK yang terdaftar di sekolah ini.
                        @endif
                    </p>
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th style="width:40px">#</th>
                                <th>Nama PTK</th>
                                <th>NIP</th>
                                <th>Kategori</th>
                                <th>Jabatan</th>
                                <th>Bidang</th>
                                <th>Pangkat/Golongan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($dataPtk as $i => $ptk)
                                <tr>
                                    <td class="text-muted">{{ $dataPtk->firstItem() + $i }}</td>
                                    <td>
                                        <div class="d-flex align-items-center gap-2">
                                            <div class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0"
                                                style="width:32px;height:32px;background:#e3eaff;font-size:.7rem;font-weight:700;color:#3d5af1">
                                                {{ strtoupper(substr($ptk->nama_ptk, 0, 1)) }}
                                            </div>
                                            <span class="fw-medium">{{ $ptk->nama_ptk }}</span>
                                        </div>
                                    </td>
                                    <td class="text-muted">{{ $ptk->nip ?? '-' }}</td>
                                    <td>
                                        <span class="badge {{ $ptk->kategori_id == 1 ? 'bg-primary' : 'bg-success' }}-subtle {{ $ptk->kategori_id == 1 ? 'text-primary' : 'text-success' }}">
                                            {{ $ptk->kategori?->jenis_kategori ?? '-' }}
                                        </span>
                                    </td>
                                    <td>{{ $ptk->jabatan?->nama_jabatan ?? '-' }}</td>
                                    <td>{{ $ptk->bidang?->nama_bidang ?? '-' }}</td>
                                    <td>{{ $ptk->pangkat_golongan?->nama_pangkat_golongan ?? '-' }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="card-footer bg-transparent border-top py-3">
                    {{ $dataPtk->links() }}
                </div>
            @endif
        </div>

    </div>
</x-layouts.app>
