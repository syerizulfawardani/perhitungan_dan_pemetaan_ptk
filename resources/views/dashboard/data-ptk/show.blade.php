<x-layouts.app>
    <div class="container-fluid">

        {{-- Header Card --}}
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-dark d-flex align-items-center justify-content-between">
                <h5 class="mb-0 text-white fw-semibold">Detail Data Pendidik & Tenaga Kependidikan</h5>
                <a href="{{ route('data-ptk') }}" class="btn btn-light btn-sm d-flex align-items-center gap-1">
                    <i class="ti ti-arrow-left fs-5"></i>
                    <span>Kembali</span>
                </a>
            </div>
        </div>

        <div class="row g-4">

            {{-- Profile Card --}}
            <div class="col-12 col-lg-4">
                <div class="card shadow-sm h-100">
                    <div class="card-body d-flex flex-column align-items-center text-center p-4">

                        {{-- Avatar --}}
                        <div class="position-relative mb-3">
                            <div class="rounded-circle d-flex align-items-center justify-content-center bg-primary bg-opacity-10 border border-primary border-opacity-25"
                                style="width: 100px; height: 100px;">
                                <span class="text-primary fw-bold" style="font-size: 2rem;">
                                    {{ strtoupper(substr($ptk->nama_ptk, 0, 2)) }}
                                </span>
                            </div>
                            <span class="position-absolute bottom-0 end-0 badge bg-success rounded-circle p-1" title="Aktif">
                                <i class="ti ti-check" style="font-size: 10px;"></i>
                            </span>
                        </div>

                        <h5 class="fw-bold mb-1">{{ $ptk->nama_ptk }}</h5>
                        <span class="badge bg-primary bg-opacity-10 text-primary px-3 py-2 rounded-pill mb-1">
                            {{ $ptk->jabatan->nama_jabatan ?? '-' }}
                        </span>
                        <p class="text-muted small mb-4">{{ $ptk->kategori->jenis_kategori ?? '-' }}</p>

                        <hr class="w-100 my-2">

                        {{-- Quick Stats --}}
                        <div class="row w-100 text-center g-0">
                            <div class="col-6 border-end py-2">
                                <div class="text-muted small mb-1">Bidang</div>
                                <div class="fw-semibold small">{{ $ptk->bidang->nama_bidang_sertifikasi ?? '-' }}</div>
                            </div>
                            <div class="col-6 py-2">
                                <div class="text-muted small mb-1">Golongan</div>
                                <div class="fw-semibold small">{{ $ptk->pangkatGolongan->nama_golongan ?? '-' }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Detail Info --}}
            <div class="col-12 col-lg-8">
                <div class="card shadow-sm">
                    <div class="card-header bg-white border-bottom d-flex align-items-center gap-2 py-3">
                        <span class="bg-primary bg-opacity-10 text-primary rounded p-1 d-flex">
                            <i class="ti ti-id-badge-2 fs-5"></i>
                        </span>
                        <h5 class="mb-0 fw-semibold">Informasi Lengkap</h5>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover table-bordered mb-0">
                                <thead class="table-light">

                                </thead>
                                <tbody>
                                    <tr>
                                        <td class="ps-4 py-3 text-muted">
                                            <div class="d-flex align-items-center gap-2">
                                                <i class="ti ti-user text-primary fs-5"></i>
                                                Nama Lengkap
                                            </div>
                                        </td>
                                        <td class="py-3 fw-medium">{{ $ptk->nama_ptk }}</td>
                                    </tr>
                                     <tr>
                                        <td class="ps-4 py-3 text-muted">
                                            <div class="d-flex align-items-center gap-2">
                                                <i class="ti ti-user text-primary fs-5"></i>
                                                Nomor Induk Pegawai
                                            </div>
                                        </td>
                                        <td class="py-3 fw-medium">{{ $ptk->nip ?? '-' }}</td>
                                    </tr>
                                    <tr>
                                        <td class="ps-4 py-3 text-muted">
                                            <div class="d-flex align-items-center gap-2">
                                                <i class="ti ti-tag text-primary fs-5"></i>
                                                Kategori
                                            </div>
                                        </td>
                                        <td class="py-3">
                                            <span class="badge bg-info bg-opacity-10 text-info px-3 py-2 rounded-pill">
                                                {{ $ptk->kategori->jenis_kategori ?? '-' }}
                                            </span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="ps-4 py-3 text-muted">
                                            <div class="d-flex align-items-center gap-2">
                                                <i class="ti ti-calendar text-primary fs-5"></i>
                                                TMT Pengangkatan
                                            </div>
                                        </td>
                                        <td class="py-3 fw-medium">
                                            @if($ptk->tmt_pengangkatan)
                                                {{ \Carbon\Carbon::parse($ptk->tmt_pengangkatan)->translatedFormat('d F Y') }}
                                                <span class="ms-2 badge bg-light text-secondary small">
                                                    {{ \Carbon\Carbon::parse($ptk->tmt_pengangkatan)->diffForHumans() }}
                                                </span>
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="ps-4 py-3 text-muted">
                                            <div class="d-flex align-items-center gap-2">
                                                <i class="ti ti-briefcase text-primary fs-5"></i>
                                                Jabatan
                                            </div>
                                        </td>
                                        <td class="py-3">
                                            <span class="badge bg-warning bg-opacity-10 text-warning px-3 py-2 rounded-pill">
                                                {{ $ptk->jabatan->nama_jabatan ?? '-' }}
                                            </span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="ps-4 py-3 text-muted">
                                            <div class="d-flex align-items-center gap-2">
                                                <i class="ti ti-school text-primary fs-5"></i>
                                                Bidang Studi
                                            </div>
                                        </td>
                                        <td class="py-3 fw-medium">{{ $ptk->bidang->nama_bidang_sertifikasi ?? '-' }}</td>
                                    </tr>
                                    <tr>
                                        <td class="ps-4 py-3 text-muted">
                                            <div class="d-flex align-items-center gap-2">
                                                <i class="ti ti-award text-primary fs-5"></i>
                                                Pangkat / Golongan
                                            </div>
                                        </td>
                                        <td class="py-3">
                                            <span class="badge bg-success bg-opacity-10 text-success px-3 py-2 rounded-pill">
                                                {{ $ptk->pangkat_golongan->nama_golongan ?? '-' }}
                                            </span>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                {{-- Timestamps --}}
                <div class="card shadow-sm mt-4">
                    <div class="card-header bg-white border-bottom d-flex align-items-center gap-2 py-3">
                        <span class="bg-secondary bg-opacity-10 text-secondary rounded p-1 d-flex">
                            <i class="ti ti-clock fs-5"></i>
                        </span>
                        <h5 class="mb-0 fw-semibold">Riwayat Catatan</h5>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-6">
                                <div class="d-flex align-items-start gap-2">
                                    <div class="bg-primary bg-opacity-10 rounded p-2 d-flex mt-1">
                                        <i class="ti ti-plus text-primary fs-5"></i>
                                    </div>
                                    <div>
                                        <div class="text-muted small">Dibuat pada</div>
                                        <div class="fw-semibold small">
                                            {{ $ptk->created_at?->translatedFormat('d M Y, H:i') ?? '-' }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="d-flex align-items-start gap-2">
                                    <div class="bg-warning bg-opacity-10 rounded p-2 d-flex mt-1">
                                        <i class="ti ti-edit text-warning fs-5"></i>
                                    </div>
                                    <div>
                                        <div class="text-muted small">Diperbarui pada</div>
                                        <div class="fw-semibold small">
                                            {{ $ptk->updated_at?->translatedFormat('d M Y, H:i') ?? '-' }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Action Buttons --}}
                <div class="d-flex gap-2 mt-4 flex-wrap">
                    <a href="{{ route('data-ptk.edit', $ptk->id) }}" class="btn btn-warning flex-fill flex-sm-grow-0 d-flex align-items-center justify-content-center gap-2">
                        <i class="ti ti-pencil fs-5"></i> Edit Data
                    </a>
                    <form action="{{ route('data-ptk.destroy', $ptk->id) }}" method="POST"
                        class="js-delete-form"
                        data-confirm-text="Data PTK ini akan dihapus permanen dan tidak dapat dikembalikan.">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger d-flex align-items-center gap-2">
                            <i class="ti ti-trash fs-5"></i> Hapus
                        </button>
                    </form>
                    <a href="{{ route('data-ptk') }}" class="btn btn-light ms-auto d-flex align-items-center gap-2">
                        <i class="ti ti-list fs-5"></i> Semua PTK
                    </a>
                </div>

            </div>
        </div>
    </div>

</x-layouts.app>
