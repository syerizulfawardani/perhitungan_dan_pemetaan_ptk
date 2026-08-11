<x-layouts.app>
    <div class="container-fluid">

        {{-- Card 1: Header --}}
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-body d-flex flex-column flex-md-row justify-content-between align-items-center gap-3">
                <div class="d-flex align-items-center gap-3">
                    <div class="d-flex align-items-center justify-content-center rounded-circle bg-primary bg-opacity-10 text-primary"
                        style="width: 56px; height:56px;">
                        <i class="ti ti-users fs-3"></i>
                    </div>

                    <div>
                        <h4 class="mb-1 fw-bold">Data Pendidik & Tenaga Kependidikan</h4>
                        <p class="text-muted mb-0">
                            Kelola data pendidik dan tenaga kependidikan terkait.
                        </p>
                    </div>
                </div>

                <div class="d-flex gap-2">
                    <span class="badge bg-primary bg-opacity-10 text-primary px-3 py-2 fs-6">
                        <i class="ti ti-users me-1"></i>
                        {{ $dataPtk->total() }} Data
                    </span>
                </div>
            </div>
        </div>

        {{-- Card 2: Search & Action --}}
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-body">
                <div class="d-flex flex-column flex-xl-row justify-content-between align-items-start gap-4">

                    {{-- Search --}}
                    <div style="min-width: 280px; max-width: 420px; width: 100%;">
                        <label class="form-label fw-semibold small text-uppercase text-muted mb-2">
                            Pencarian
                        </label>

                        <form action="{{ route('data-ptk') }}" method="GET">
                            <div class="input-group">
                                <span class="input-group-text bg-white">
                                    <i class="ti ti-search text-muted"></i>
                                </span>

                                <input type="text" class="form-control" name="search"
                                    value="{{ request('search') }}" placeholder="Cari nama PTK...">

                                <button class="btn btn-primary">
                                    Cari
                                </button>

                                @if (request('search'))
                                    <a href="{{ route('data-ptk') }}" class="btn btn-light border" title="Reset pencarian">
                                        <i class="ti ti-x"></i>
                                    </a>
                                @endif
                            </div>
                        </form>
                    </div>

                    {{-- Aksi --}}
                    <div class="w-100" style="max-width: 620px;">
                        <label class="form-label fw-semibold small text-uppercase text-muted mb-2 d-block d-xl-block text-xl-end">
                            Aksi
                        </label>

                        <div class="d-flex flex-wrap justify-content-start justify-content-xl-end align-items-center gap-2">

                            {{-- Import file tersembunyi, dipicu dari dalam dropdown --}}
                            <form action="{{ route('data-ptk.import') }}" method="POST" enctype="multipart/form-data"
                                id="form-import-ptk" class="d-none">
                                @csrf
                                <input type="file" name="file" id="input-import-ptk" accept=".xlsx,.xls,.csv">
                            </form>

                            {{-- Dropdown: Import & Export --}}
                            <div class="dropdown">
                                <button class="btn btn-outline-secondary dropdown-toggle" type="button"
                                    data-bs-toggle="dropdown" data-bs-auto-close="outside" aria-expanded="false">
                                    <i class="ti ti-file-spreadsheet me-1"></i>
                                    Import / Export
                                </button>

                                <div class="dropdown-menu dropdown-menu-end p-3 shadow-sm" style="min-width: 300px;">

                                    {{-- Import --}}
                                    <button type="button" class="dropdown-item d-flex align-items-center gap-2 rounded px-2 py-2"
                                        onclick="document.getElementById('input-import-ptk').click()">
                                        <i class="ti ti-upload text-success fs-5"></i>
                                        <div class="text-start">
                                            <div class="fw-semibold">Import Excel</div>
                                            <div class="small text-muted">Unggah data dari file .xlsx / .csv</div>
                                        </div>
                                    </button>

                                    <hr class="dropdown-divider">

                                    {{-- Export --}}
                                    <div class="px-2">
                                        <label class="form-label small fw-semibold text-muted mb-1">
                                            <i class="ti ti-download me-1"></i>Export Excel
                                        </label>

                                        <form action="{{ route('data-ptk.export') }}" method="GET">
                                            <select name="sekolah_id" id="filterSekolah" class="form-select form-select-sm mb-2">
                                                <option value="">-- Semua Sekolah --</option>

                                                @foreach ($sekolahs as $sekolah)
                                                    <option value="{{ $sekolah->id }}"
                                                        {{ request('sekolah_id') == $sekolah->id ? 'selected' : '' }}>
                                                        {{ $sekolah->nama_sekolah }}
                                                    </option>
                                                @endforeach
                                            </select>

                                            <button type="submit" class="btn btn-info text-white btn-sm w-100">
                                                <i class="ti ti-download me-1"></i>
                                                Export Sekarang
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>

                            {{-- Tambah Data --}}
                            <a href="{{ route('data-ptk.create') }}" class="btn btn-primary">
                                <i class="ti ti-plus me-1"></i>
                                Tambah Data
                            </a>

                        </div>
                    </div>

                </div>
            </div>
        </div>

        {{-- Card 3: Tabel --}}
        <div class="card border-0 shadow-sm">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr class="text-center">
                                <th width="60" class="ps-3">No</th>
                                <th class="text-start">Nama Pendidik/Tenaga Kependidikan</th>
                                <th>NIP</th>
                                <th>Jabatan PTK</th>
                                <th>Asal Sekolah</th>
                                <th width="110">Status</th>
                                <th width="140" class="pe-3">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($dataPtk as $ptk)
                                <tr>
                                    <td class="text-center fw-semibold text-muted ps-3">
                                        {{ ($dataPtk->currentPage() - 1) * $dataPtk->perPage() + $loop->iteration }}
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center gap-2">
                                            <div class="d-flex align-items-center justify-content-center rounded-circle bg-primary bg-opacity-10 text-primary fw-bold flex-shrink-0"
                                                style="width: 36px; height: 36px; font-size: 0.85rem;">
                                                {{ strtoupper(substr($ptk->nama_ptk, 0, 1)) }}
                                            </div>
                                            <span class="fw-semibold text-dark">
                                                {{ $ptk->nama_ptk }}
                                            </span>
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        <span class="font-monospace small">{{ $ptk->nip ?? '-' }}</span>
                                    </td>
                                    <td class="text-center">
                                        {{ $ptk->jabatan->nama_jabatan ?? '-' }}
                                    </td>
                                    <td class="text-center">
                                        {{ $ptk->sekolah->nama_sekolah ?? '-' }}
                                    </td>
                                    <td class="text-center">
                                        @if ($ptk->is_active)
                                            <span class="badge rounded-pill bg-success bg-opacity-10 text-success px-3 py-2">
                                                <i class="ti ti-circle-check me-1"></i>Aktif
                                            </span>
                                        @else
                                            <span class="badge rounded-pill bg-danger bg-opacity-10 text-danger px-3 py-2">
                                                <i class="ti ti-circle-x me-1"></i>Tidak Aktif
                                            </span>
                                        @endif
                                    </td>
                                    <td class="text-center pe-3">
                                        <div class="d-flex justify-content-center gap-2">
                                            <a href="{{ route('data-ptk.show', $ptk->id) }}"
                                                class="btn btn-info btn-sm text-white" title="Detail">
                                                <i class="ti ti-eye"></i>
                                            </a>

                                            <a href="{{ route('data-ptk.edit', $ptk->id) }}"
                                                class="btn btn-warning btn-sm" title="Edit">
                                                <i class="ti ti-edit"></i>
                                            </a>
                                            <form action="{{ route('data-ptk.destroy', $ptk->id) }}" method="POST"
                                                class="form-hapus">
                                                @csrf
                                                @method('DELETE')

                                                <button class="btn btn-danger btn-sm" type="submit" title="Hapus">
                                                    <i class="ti ti-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td class="text-center py-5" colspan="7">
                                        <div class="d-flex flex-column align-items-center text-muted">
                                            <i class="ti ti-user-off fs-1 mb-2"></i>
                                            <span class="small">
                                                @if (request('search'))
                                                    Tidak ada data PTK yang cocok dengan pencarian "{{ request('search') }}".
                                                @else
                                                    Belum ada data Pendidik & Tenaga Kependidikan. Silakan tambahkan data terlebih dahulu.
                                                @endif
                                            </span>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="card-footer bg-white border-top">
                <div class="d-flex flex-column flex-md-row justify-content-between align-items-center gap-3">
                    <div class="text-muted small">
                        Menampilkan
                        <strong>{{ $dataPtk->firstItem() ?? 0 }}</strong>
                        -
                        <strong>{{ $dataPtk->lastItem() ?? 0 }}</strong>
                        dari
                        <strong>{{ $dataPtk->total() }}</strong>
                        data.
                    </div>

                    <div>
                        {{ $dataPtk->links('pagination::bootstrap-5') }}
                    </div>
                </div>
            </div>
        </div>

    </div>

    @push('scripts')
        <script>
            // Import Excel: setelah file dipilih, konfirmasi lalu submit otomatis
            document.getElementById('input-import-ptk').addEventListener('change', function() {
                if (this.files.length === 0) return;

                const namaFile = this.files[0].name;
                Swal.fire({
                    title: 'Import data PTK?',
                    text: 'File: ' + namaFile,
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#198754',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Ya, import!',
                    cancelButtonText: 'Batal',
                }).then((result) => {
                    if (result.isConfirmed) {
                        Swal.fire({
                            title: 'Mengimport data...',
                            text: 'Mohon tunggu sebentar.',
                            allowOutsideClick: false,
                            didOpen: () => Swal.showLoading(),
                        });
                        document.getElementById('form-import-ptk').submit();
                    } else {
                        this.value = '';
                    }
                });
            });

            // Konfirmasi hapus data PTK
            document.querySelectorAll('.form-hapus').forEach(function(form) {
                form.addEventListener('submit', function(e) {
                    e.preventDefault();
                    Swal.fire({
                        title: 'Yakin ingin menghapus data ini?',
                        text: 'Data yang dihapus tidak bisa dikembalikan!',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#d33',
                        cancelButtonColor: '#6c757d',
                        confirmButtonText: 'Ya, hapus!',
                        cancelButtonText: 'Batal',
                    }).then(function(result) {
                        if (result.isConfirmed) {
                            form.submit();
                        }
                    });
                });
            });
        </script>
    @endpush
</x-layouts.app>
