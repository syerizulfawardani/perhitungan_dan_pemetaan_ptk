<x-layouts.app>
    <div class="container-fluid">
        <div class="card shadow-sm">
            <div class="card-header d-flex align-items-center justify-content-between bg-primary text-white">
                <h3 class="card-title mb-0 text-white rounded">Tambah Pendidik & Tenaga Kependidikan</h3>
                <button type="button" class="btn btn-light" data-bs-toggle="modal" data-bs-target="#modalKategori">
                    <i class="ti ti-plus"></i> Kategori
                </button>
            </div>

            <div class="card-body bg-light">
                <form action="{{ route('data-ptk.store') }}" method="POST">
                    @csrf

                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $err)
                                    <li>{{ $err }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <div class="mb-3">
                        <label class="form-label">Sekolah <span class="text-muted small">(opsional)</span></label>
                        <select name="sekolah_id" id="sekolah_id" class="form-select">
                            <option value="">-- Pilih Sekolah --</option>
                            @foreach ($sekolah as $s)
                                <option value="{{ $s->id }}"
                                    data-kecamatan-id="{{ $s->kecamatan_id }}"
                                    {{ old('sekolah_id') == $s->id ? 'selected' : '' }}>
                                    {{ $s->nama_sekolah }}
                                    @if($s->kecamatan) ({{ $s->kecamatan->nama_kecamatan }}) @endif
                                </option>
                            @endforeach
                        </select>
                        <div class="form-text">Memilih sekolah akan otomatis mengisi kecamatan.</div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Kecamatan <span class="text-danger">*</span></label>
                        <select name="kecamatan_id" id="kecamatan_id" class="form-select">
                            <option value="">-- Pilih Kecamatan --</option>
                            @foreach ($kecamatan as $kec)
                                <option value="{{ $kec->id }}" {{ old('kecamatan_id') == $kec->id ? 'selected' : '' }}>
                                    {{ $kec->nama_kecamatan }}
                                </option>
                            @endforeach
                        </select>
                        <div class="form-text">Kecamatan tempat PTK bertugas (untuk pemetaan).</div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Nama Pendidik & Tenaga Kependidikan</label>
                        <input type="text" class="form-control" name="nama_ptk"
                            placeholder="Masukkan nama pendidik & tenaga kependidikan">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Kategori</label>
                        {{-- {{ dd($kategori->toArray()) }} --}}
                        <select name="kategori_id" id="kategori_id" class="form-select">
                            <option value="">-- Kategori --</option>
                            @foreach ($kategori as $k)
                                <option value="{{ $k->id }}">{{ $k->jenis_kategori }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Terhitung Mulai Tanggal</label>
                        <input type="date" class="form-control" name="tmt_pengangkatan">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Jabatan</label>
                        <select name="jabatan_id" class="form-select">
                            <option value="">-- Jabatan --</option>
                            @foreach ($jabatan as $j)
                                <option value="{{ $j->id }}">{{ $j->nama_jabatan }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Bidang</label>
                        <select name="bidang_id" class="form-select">
                            <option value="">-- Bidang --</option>
                            @foreach ($bidang as $b)
                                <option value="{{ $b->id }}">{{ $b->nama_bidang_sertifikasi }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Pangkat Golongan</label>
                        <select name="pangkat_golongan_id" class="form-select">
                            <option value="">-- Pangkat Golongan --</option>
                            @foreach ($pangkat as $p)
                                <option value="{{ $p->id }}">{{ $p->nama_golongan }}</option>
                            @endforeach
                        </select>
                    </div>

                    <button class="btn btn-primary" type="submit">
                        <i class="ti ti-check"></i> Simpan
                    </button>

                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modalKategori" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form id="formKategori">
                    @csrf

                    <div class="modal-header">
                        <h5 class="modal-title">Tambah Kategori</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Jenis Kategori</label>
                            <input type="text" name="jenis_kategori" class="form-control" required>
                            <div class="invalid-feedback"></div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-info" type="button" data-bs-dismiss="modal">
                            <i class="ti ti-x fs-3"></i> Batal
                        </button>
                        <button type="submit" class="btn btn-primary">
                            <i class="ti ti-check fs-3"></i> Simpan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        // Auto-isi kecamatan saat sekolah dipilih
        document.getElementById('sekolah_id').addEventListener('change', function () {
            const opt = this.options[this.selectedIndex];
            const kecId = opt.dataset.kecamatanId;
            const kecSelect = document.getElementById('kecamatan_id');
            if (kecId) {
                kecSelect.value = kecId;
            } else {
                kecSelect.value = '';
            }
        });

        document.getElementById("formKategori").addEventListener('submit', async function(e) {
        e.preventDefault()
        const form = e.target;
        const input = form.querySelector('[name="jenis_kategori"]')
        const errorBox = form.querySelector('.invalid-feedback')

        input.classList.remove('is-invalid');
        errorBox.textContent = '';

        try {
            const res = await fetch("{{ route('kategori.store') }}", {
                method: "POST",
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                },
                body: new FormData(form),
            });

            if (res.ok) {
                const {data} = await res.json()
                const select = document.getElementById('kategori_id');
                if (select) {
                    select.add(new Option(data.jenis_kategori, data.id, true, true));
                }
                form.reset();
                bootstrap.Modal.getInstance(document.getElementById('modalKategori')).hide();
            } else if (res.status === 422) {
                const {errors} = await res.json();
                input.classList.add('is-invalid');
                errorBox.textContent = errors.jenis_kategori?.[0] ?? 'Input tidak valid';
            }
        } catch (err) {
            console.error(err)
        }
    })
    </script>
    @endpush
</x-layouts.app>
