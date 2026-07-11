<x-layouts.app>
    <div class="container-fluid">
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

                {{-- Action Buttons --}}
                <div class="d-flex gap-2 flex-wrap">

                    {{-- Import Excel: tombol memicu file dialog tersembunyi --}}
                    <form action="{{ route('data-ptk.import') }}" method="POST" enctype="multipart/form-data"
                        id="form-import-ptk" class="m-0">
                        @csrf
                        <input type="file" name="file" id="input-import-ptk" class="d-none" accept=".xlsx,.xls,.csv">
                        <button type="button" class="btn btn-success px-4"
                            onclick="document.getElementById('input-import-ptk').click()">
                            <i class="ti ti-upload me-1"></i> Import Excel
                        </button>
                    </form>

                    {{-- Tambah Data PTK --}}
                    <a href="{{ route('data-ptk.create') }}" class="btn btn-primary px-4">
                        <i class="ti ti-plus me-1"></i>
                        Tambah Data PTK
                    </a>
                </div>
            </div>
            <div class="card-body">

                <form action="{{ route('data-ptk') }}" method="GET">
                    <div class="row g-2">
                        <div class="col-md-4">
                            <input type="text" name="search" class="form-control"
                                placeholder="Cari nama atau email..." value="{{ request('search') }}">
                        </div>

                        <div class="col-md-auto">
                            <button type="submit" class="btn btn-primary">
                                <i class="ti ti-search"></i>
                                Cari
                            </button>
                        </div>

                        <div class="col-md-auto">
                            <a href="{{ route('data-ptk') }}" class="btn btn-light border">
                                Reset
                            </a>
                        </div>
                    </div>
            </div>
            <div class="card border-0 shadow-sm">
                <div class="card-body p-0">
                    <div class="table-reponsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr class="text-center">
                                    <th width="70">No</th>
                                    <th>Nama Pendidik/Tenaga Kependidikan</th>
                                    <th>Jabatan PTK</th>
                                    <th>TMT Pengangkatan</th>
                                    <th width="140">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($dataPtk as $ptk)
                                    <tr>
                                        <td class="text-center fw-semibold">
                                            {{ $loop->iteration }}
                                        </td>
                                        <td>
                                            <div class="text-center fw-semibold text-dark">
                                                {{ $ptk->nama_ptk }}
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            {{ $ptk->jabatan->nama_jabatan ?? '-' }}
                                        </td>
                                        <td class="text-center">
                                            {{ $ptk->tmt_pengangkatan }}
                                        </td>
                                        <td class="text-center">
                                            <div class="d-flex justify-content-center gap-2">
                                                <a href="{{ route('data-ptk.show', $ptk->id) }}"
                                                    class="btn btn-info btn-sm" title="Detail">
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
                                        <td class="text-center py-5" colspan="5">
                                            <div class="d-flex flex-column align-items-center text-muted">
                                                <i class="ti ti-school-off fs-1 mb-2"></i>
                                                <span class="small">
                                                    Silahkan Tambahkan Data Sekolah Terlebih Dahulu.
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
            </script>
        @endpush
</x-layouts.app>
