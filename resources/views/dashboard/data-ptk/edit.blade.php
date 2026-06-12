<x-layouts.app>
    <div class="container-fluid">

        {{-- Breadcrumb --}}
        <div class="d-flex align-items-center justify-content-between mb-4">
            <div>
                <h4 class="mb-1 fw-semibold">Edit PTK</h4>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}" class="text-muted text-decoration-none">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('data-ptk') }}" class="text-muted text-decoration-none">Data PTK</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('data-ptk.show', $ptk->id) }}" class="text-muted text-decoration-none">Detail</a></li>
                        <li class="breadcrumb-item active">Edit</li>
                    </ol>
                </nav>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('data-ptk.show', $ptk->id) }}" class="btn btn-light d-flex align-items-center gap-1">
                    <i class="ti ti-arrow-left fs-5"></i>
                    <span class="d-none d-sm-inline">Kembali</span>
                </a>
            </div>
        </div>

        <form action="{{ route('data-ptk.update', $ptk->id) }}" method="POST" id="formEdit">
            @csrf
            @method('PUT')

            <div class="row g-4">

                {{-- Sidebar Preview --}}
                <div class="col-12 col-lg-4">
                    <div class="card shadow-sm">
                        <div class="card-body d-flex flex-column align-items-center text-center p-4">

                            {{-- Avatar --}}
                            <div class="position-relative mb-3">
                                <div class="rounded-circle d-flex align-items-center justify-content-center bg-warning bg-opacity-10 border border-warning border-opacity-25"
                                    style="width: 100px; height: 100px;">
                                    <span class="text-warning fw-bold" id="avatarPreview" style="font-size: 2rem;">
                                        {{ strtoupper(substr($ptk->nama_ptk, 0, 2)) }}
                                    </span>
                                </div>
                                <span class="position-absolute bottom-0 end-0 badge bg-warning rounded-circle p-1" title="Mode Edit">
                                    <i class="ti ti-pencil" style="font-size: 10px;"></i>
                                </span>
                            </div>

                            <h6 class="fw-bold mb-1" id="namaPreview">{{ $ptk->nama_ptk }}</h6>
                            <span class="badge bg-warning bg-opacity-10 text-warning px-3 py-2 rounded-pill mb-1 small">
                                Mode Edit
                            </span>
                            <p class="text-muted small mb-0">Perubahan belum tersimpan</p>

                            <hr class="w-100 my-3">

                            {{-- Info ringkas --}}
                            <div class="w-100 text-start">
                                <div class="d-flex align-items-center gap-2 mb-2">
                                    <i class="ti ti-info-circle text-muted fs-5"></i>
                                    <span class="text-muted small">Data saat ini</span>
                                </div>
                                <ul class="list-unstyled mb-0 ps-1">
                                    <li class="mb-2 small">
                                        <span class="text-muted">Jabatan:</span>
                                        <span class="fw-medium ms-1">{{ $ptk->jabatan->nama_jabatan ?? '-' }}</span>
                                    </li>
                                    <li class="mb-2 small">
                                        <span class="text-muted">Kategori:</span>
                                        <span class="fw-medium ms-1">{{ $ptk->kategori->jenis_kategori ?? '-' }}</span>
                                    </li>
                                    <li class="mb-2 small">
                                        <span class="text-muted">Golongan:</span>
                                        <span class="fw-medium ms-1">{{ $ptk->pangkat_golongan->nama_golongan ?? '-' }}</span>
                                    </li>
                                    <li class="small">
                                        <span class="text-muted">Terakhir diubah:</span>
                                        <span class="fw-medium ms-1">{{ $ptk->updated_at?->translatedFormat('d M Y') ?? '-' }}</span>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    {{-- Tips Card --}}
                    <div class="card shadow-sm mt-4 border-start border-4 border-info">
                        <div class="card-body py-3">
                            <div class="d-flex gap-2 align-items-start">
                                <i class="ti ti-bulb text-info fs-5 mt-1 flex-shrink-0"></i>
                                <div>
                                    <div class="fw-semibold small mb-1">Tips</div>
                                    <p class="text-muted small mb-0">
                                        Pastikan semua data sudah benar sebelum menyimpan. Kamu bisa tambah kategori baru
                                        langsung dari tombol di atas form.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Form Utama --}}
                <div class="col-12 col-lg-8">
                    <div class="card shadow-sm">
                        <div class="card-header bg-white border-bottom d-flex align-items-center justify-content-between py-3">
                            <div class="d-flex align-items-center gap-2">
                                <span class="bg-warning bg-opacity-10 text-warning rounded p-1 d-flex">
                                    <i class="ti ti-pencil fs-5"></i>
                                </span>
                                <h5 class="mb-0 fw-semibold">Ubah Data PTK</h5>
                            </div>
                            <button type="button" class="btn btn-sm btn-outline-info d-flex align-items-center gap-1"
                                data-bs-toggle="modal" data-bs-target="#modalKategori">
                                <i class="ti ti-plus fs-6"></i> Kategori
                            </button>
                        </div>

                        <div class="card-body p-4">

                            @if ($errors->any())
                                <div class="alert alert-danger alert-dismissible fade show d-flex gap-2 align-items-start" role="alert">
                                    <i class="ti ti-alert-circle fs-5 flex-shrink-0 mt-1"></i>
                                    <div>
                                        <div class="fw-semibold mb-1">Terdapat kesalahan input:</div>
                                        <ul class="mb-0 ps-3">
                                            @foreach ($errors->all() as $err)
                                                <li class="small">{{ $err }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                    <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
                                </div>
                            @endif

                            {{-- Sekolah --}}
                            <div class="mb-4">
                                <label class="form-label fw-medium">
                                    Sekolah <span class="text-muted small">(opsional)</span>
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0">
                                        <i class="ti ti-school text-muted"></i>
                                    </span>
                                    <select name="sekolah_id" class="form-select border-start-0 @error('sekolah_id') is-invalid @enderror">
                                        <option value="">-- Pilih Sekolah --</option>
                                        @foreach ($sekolah as $s)
                                            <option value="{{ $s->id }}" {{ old('sekolah_id', $ptk->sekolah_id) == $s->id ? 'selected' : '' }}>
                                                {{ $s->nama_sekolah }}
                                                @if($s->kecamatan) ({{ $s->kecamatan->nama_kecamatan }}) @endif
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('sekolah_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="form-text">Pilih sekolah tempat PTK bertugas untuk pemetaan kecamatan.</div>
                            </div>

                            {{-- Nama --}}
                            <div class="mb-4">
                                <label class="form-label fw-medium">
                                    Nama Pendidik & Tenaga Kependidikan
                                    <span class="text-danger">*</span>
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0">
                                        <i class="ti ti-user text-muted"></i>
                                    </span>
                                    <input type="text"
                                        class="form-control border-start-0 ps-0 @error('nama_ptk') is-invalid @enderror"
                                        name="nama_ptk"
                                        id="inputNama"
                                        value="{{ old('nama_ptk', $ptk->nama_ptk) }}"
                                        placeholder="Masukkan nama lengkap">
                                    @error('nama_ptk')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            {{-- Kategori --}}
                            <div class="mb-4">
                                <label class="form-label fw-medium">
                                    Kategori
                                    <span class="text-danger">*</span>
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0">
                                        <i class="ti ti-tag text-muted"></i>
                                    </span>
                                    <select name="kategori_id" id="kategori_id"
                                        class="form-select border-start-0 @error('kategori_id') is-invalid @enderror">
                                        <option value="">-- Pilih Kategori --</option>
                                        @foreach ($kategori as $k)
                                            <option value="{{ $k->id }}"
                                                {{ old('kategori_id', $ptk->kategori_id) == $k->id ? 'selected' : '' }}>
                                                {{ $k->jenis_kategori }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('kategori_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            {{-- TMT Pengangkatan --}}
                            <div class="mb-4">
                                <label class="form-label fw-medium">Terhitung Mulai Tanggal (TMT)</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0">
                                        <i class="ti ti-calendar text-muted"></i>
                                    </span>
                                    <input type="date"
                                        class="form-control border-start-0 ps-0 @error('tmt_pengangkatan') is-invalid @enderror"
                                        name="tmt_pengangkatan"
                                        value="{{ old('tmt_pengangkatan', $ptk->tmt_pengangkatan ? \Carbon\Carbon::parse($ptk->tmt_pengangkatan)->format('Y-m-d') : '') }}">
                                    @error('tmt_pengangkatan')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            {{-- Jabatan --}}
                            <div class="mb-4">
                                <label class="form-label fw-medium">Jabatan</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0">
                                        <i class="ti ti-briefcase text-muted"></i>
                                    </span>
                                    <select name="jabatan_id"
                                        class="form-select border-start-0 @error('jabatan_id') is-invalid @enderror">
                                        <option value="">-- Pilih Jabatan --</option>
                                        @foreach ($jabatan as $j)
                                            <option value="{{ $j->id }}"
                                                {{ old('jabatan_id', $ptk->jabatan_id) == $j->id ? 'selected' : '' }}>
                                                {{ $j->nama_jabatan }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('jabatan_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            {{-- Bidang --}}
                            <div class="mb-4">
                                <label class="form-label fw-medium">Bidang Sertifikasi</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0">
                                        <i class="ti ti-school text-muted"></i>
                                    </span>
                                    <select name="bidang_id"
                                        class="form-select border-start-0 @error('bidang_id') is-invalid @enderror">
                                        <option value="">-- Pilih Bidang --</option>
                                        @foreach ($bidang as $b)
                                            <option value="{{ $b->id }}"
                                                {{ old('bidang_id', $ptk->bidang_id) == $b->id ? 'selected' : '' }}>
                                                {{ $b->nama_bidang_sertifikasi }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('bidang_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            {{-- Pangkat Golongan --}}
                            <div class="mb-4">
                                <label class="form-label fw-medium">Pangkat / Golongan</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0">
                                        <i class="ti ti-award text-muted"></i>
                                    </span>
                                    <select name="pangkat_golongan_id"
                                        class="form-select border-start-0 @error('pangkat_golongan_id') is-invalid @enderror">
                                        <option value="">-- Pilih Pangkat Golongan --</option>
                                        @foreach ($pangkat as $p)
                                            <option value="{{ $p->id }}"
                                                {{ old('pangkat_golongan_id', $ptk->pangkat_golongan_id) == $p->id ? 'selected' : '' }}>
                                                {{ $p->nama_golongan }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('pangkat_golongan_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                        </div>

                        {{-- Footer Aksi --}}
                        <div class="card-footer bg-light border-top d-flex align-items-center justify-content-between flex-wrap gap-2 px-4 py-3">
                            <a href="{{ route('data-ptk.show', $ptk->id) }}"
                                class="btn btn-light d-flex align-items-center gap-2">
                                <i class="ti ti-x fs-5"></i> Batal
                            </a>
                            <button type="submit" class="btn btn-warning d-flex align-items-center gap-2 px-4">
                                <i class="ti ti-device-floppy fs-5"></i> Simpan Perubahan
                            </button>
                        </div>
                    </div>
                </div>

            </div>
        </form>
    </div>

    {{-- Modal Tambah Kategori --}}
    <div class="modal fade" id="modalKategori" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <form id="formKategori">
                    @csrf
                    <div class="modal-header border-bottom">
                        <div class="d-flex align-items-center gap-2">
                            <span class="bg-info bg-opacity-10 text-info rounded p-1 d-flex">
                                <i class="ti ti-tag fs-5"></i>
                            </span>
                            <h5 class="modal-title mb-0 fw-semibold">Tambah Kategori Baru</h5>
                        </div>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body p-4">
                        <div class="mb-3">
                            <label class="form-label fw-medium">Jenis Kategori <span class="text-danger">*</span></label>
                            <input type="text" name="jenis_kategori" class="form-control"
                                placeholder="Contoh: Guru Tetap, Tendik, dsb." required>
                            <div class="invalid-feedback"></div>
                        </div>
                    </div>
                    <div class="modal-footer border-top">
                        <button class="btn btn-light d-flex align-items-center gap-1" type="button" data-bs-dismiss="modal">
                            <i class="ti ti-x fs-5"></i> Batal
                        </button>
                        <button type="submit" class="btn btn-info d-flex align-items-center gap-1">
                            <i class="ti ti-check fs-5"></i> Simpan Kategori
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        // Live preview nama di sidebar
        document.getElementById('inputNama').addEventListener('input', function () {
            const val = this.value.trim();
            const preview = document.getElementById('namaPreview');
            const avatar = document.getElementById('avatarPreview');

            preview.textContent = val || 'Nama PTK';
            avatar.textContent = val ? val.substring(0, 2).toUpperCase() : '--';
        });

        // Tambah Kategori via AJAX
        document.getElementById('formKategori').addEventListener('submit', async function (e) {
            e.preventDefault();
            const form = e.target;
            const input = form.querySelector('[name="jenis_kategori"]');
            const errorBox = form.querySelector('.invalid-feedback');

            input.classList.remove('is-invalid');
            errorBox.textContent = '';

            try {
                const res = await fetch("{{ route('kategori.store') }}", {
                    method: 'POST',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json',
                    },
                    body: new FormData(form),
                });

                if (res.ok) {
                    const { data } = await res.json();
                    const select = document.getElementById('kategori_id');
                    if (select) {
                        select.add(new Option(data.jenis_kategori, data.id, true, true));
                    }
                    form.reset();
                    bootstrap.Modal.getInstance(document.getElementById('modalKategori')).hide();
                } else if (res.status === 422) {
                    const { errors } = await res.json();
                    input.classList.add('is-invalid');
                    errorBox.textContent = errors.jenis_kategori?.[0] ?? 'Input tidak valid';
                }
            } catch (err) {
                console.error(err);
            }
        });
    </script>
    @endpush
</x-layouts.app>
