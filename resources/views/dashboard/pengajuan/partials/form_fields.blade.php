@php $old = $pengajuanPtk ?? null; @endphp

<div class="row g-3">

    {{-- Nomor Pengajuan (otomatis) --}}
    <div class="col-12">
        <label class="form-label fw-semibold">
            Nomor Pengajuan
        </label>
        @if ($old)
            {{-- Mode EDIT: tampilkan nomor yang sudah ada --}}
            <div class="input-group">
                <span class="input-group-text bg-primary text-white">
                    <i class="ti ti-hash"></i>
                </span>
                <input type="text" class="form-control bg-light fw-medium" value="{{ $old->nomor_pengajuan }}" readonly>
                <span class="input-group-text bg-light text-muted">
                    <i class="ti ti-lock"></i>
                </span>
            </div>
        @else
            {{-- Mode CREATE: tampilkan preview nomor --}}
            <div class="input-group">
                <span class="input-group-text bg-primary text-white">
                    <i class="ti ti-hash"></i>
                </span>
                <input type="text" class="form-control bg-light fw-medium" value="{{ $previewNomor ?? '-' }}"
                    readonly>
                <span class="input-group-text bg-light text-success">
                    <i class="ti ti-sparkles"></i> Otomatis
                </span>
            </div>
            <div class="form-text">
                <i class="ti ti-info-circle me-1"></i>Nomor dibuat otomatis saat pengajuan disimpan
            </div>
        @endif
    </div>

    {{-- Nama Sekolah (otomatis dari akun operator, tidak bisa diubah) --}}
    <div class="col-md-8">
        <label class="form-label fw-semibold">
            Nama Sekolah
        </label>
        <div class="input-group">
            <span class="input-group-text bg-secondary text-white">
                <i class="ti ti-school"></i>
            </span>
            <input type="text" class="form-control bg-light" value="{{ $sekolahOperator?->nama_sekolah ?? '-' }}"
                readonly>
        </div>
    </div>

    {{-- Tanggal Pengajuan --}}
    <div class="col-md-4">
        <label class="form-label fw-semibold">
            Tanggal Pengajuan
        </label>
        <div class="input-group">
            <span class="input-group-text bg-secondary text-white">
                <i class="ti ti-calendar"></i>
            </span>
            <input type="date" name="tmt_pengangkatan" class="form-control bg-light"
                value="{{ old('tmt_pengangkatan', $old?->tmt_pengangkatan?->format('Y-m-d') ?? now()->format('Y-m-d')) }}"
                readonly>
        </div>
    </div>

    {{-- Kategori --}}
    <div class="col-md-6">
        <label class="form-label fw-semibold">
            Kategori PTK <span class="text-danger">*</span>
        </label>
        <select name="kategori_id" id="kategori_id" class="form-select @error('kategori_id') is-invalid @enderror">
            <option value="">-- Pilih Kategori --</option>
            @foreach ($kategoris as $kat)
                <option value="{{ $kat->id }}"
                    {{ old('kategori_id', $old?->kategori_id) == $kat->id ? 'selected' : '' }}>
                    {{ $kat->jenis_kategori }}
                </option>
            @endforeach
        </select>
        @error('kategori_id')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    {{-- Bidang Studi Sertifikasi --}}

    @php
        $selectKategori = old('kategori_id', $old?->kategori_id)
    @endphp

    <div class="col-md-6">
        <label class="form-label fw-semibold">
            Bidang Studi <span class="text-danger">*</span>
        </label>
        <select name="bidang_id" id="bidang_id" class="form-select @error('bidang_id') is-invalid @enderror">
            <option value="">-- Pilih Bidang --</option>
            @foreach ($bidangs as $b)
                <option value="{{ $b->id }}" data-kategori = "{{ $b->kategori_id }}"
                    {{ old('bidang_id', $old?->bidang_id) == $b->id ? 'selected' : '' }}>
                    {{ $b->nama_bidang_sertifikasi }}
                </option>
            @endforeach
        </select>
        @error('bidang_id')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    {{-- Alasan Pengajuan --}}
    <div class="col-12">
        <label class="form-label fw-semibold">
            Alasan Pengajuan <span class="text-danger">*</span>
            <span class="text-muted fw-normal small">(minimal 20 karakter)</span>
        </label>

        <textarea name="alasan_pengajuan" rows="6" class="form-control @error('alasan_pengajuan') is-invalid @enderror"
            placeholder="Jelaskan kondisi yang menjadi dasar pengajuan kebutuhan PTK...">{{ old('alasan_pengajuan', $old?->alasan_pengajuan) }}</textarea>

        @error('alasan_pengajuan')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
        @enderror

        <div class="alert alert-info mt-3 mb-0">
            <strong><i class="ti ti-info-circle me-1"></i>Petunjuk Pengisian</strong>
            <ul class="mb-0 mt-2">
                <li>Jelaskan jumlah rombongan belajar (kelas) yang dimiliki sekolah.</li>
                <li>Jelaskan jumlah peserta didik pada setiap jenjang atau kelas.</li>
                <li>Jelaskan kondisi kekurangan PTK yang terjadi.</li>
                <li>Sampaikan dampak terhadap proses pembelajaran apabila kebutuhan PTK tidak dipenuhi.</li>
                <li>Tambahkan informasi pendukung lain yang dianggap perlu.</li>
            </ul>
        </div>

        <div class="form-text mt-2" id="charCount">
            0 karakter
        </div>
    </div>
    {{-- Lampiran Pendukung --}}
    <div class="col-12 mt-4">

        <div class="card border shadow-sm">

            <div class="card-header bg-light d-flex justify-content-between align-items-center">

                <div>
                    <i class="ti ti-paperclip me-2"></i>
                    <strong>Lampiran Pendukung</strong>
                </div>

                <button type="button" class="btn btn-sm btn-primary" id="btnTambahLampiran">

                    <i class="ti ti-plus"></i>

                    Tambah Lampiran

                </button>

            </div>

            <div class="card-body">

                <div id="lampiranContainer">

                    <div class="row g-3 lampiran-item">

                        <div class="col-md-3">

                            <label class="form-label">
                                Jenis Lampiran
                            </label>

                            <select name="jenis_lampiran[]" class="form-select">

                                <option value="">Pilih</option>
                                <option value="Dokumen Pendukung">
                                    Dokumen Pendukung
                                </option>
                            </select>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">
                                Upload File
                            </label>
                            <input type="file" name="lampiran[]" class="form-control" accept=".pdf,.jpg,.jpeg,.png">
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">
                                Keterangan
                            </label>
                            <input type="text" name="keterangan_lampiran[]" class="form-control"
                                placeholder="Opsional">
                        </div>

                        <div class="col-md-1 d-flex align-items-end">
                            <button type="button" class="btn btn-outline-danger btnHapusLampiran">
                                <i class="ti ti-trash"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>

@push('scripts')
    <script>
        (function() {
            const ta = document.querySelector('textarea[name="alasan_pengajuan"]');
            const count = document.getElementById('charCount');
            if (!ta || !count) return;

            function update() {
                const n = ta.value.length;
                count.textContent = n + ' karakter';
                count.className = 'form-text ' + (n >= 20 ? 'text-success' : 'text-muted');
            }

            ta.addEventListener('input', update);
            update(); // init saat edit
        })();

        document.addEventListener('DOMContentLoaded', function() {

            const container = document.getElementById('lampiranContainer');

            const btnTambah = document.getElementById('btnTambahLampiran');

            btnTambah.addEventListener('click', function() {

                const item = container.querySelector('.lampiran-item');

                const clone = item.cloneNode(true);

                clone.querySelectorAll('input').forEach(function(input) {

                    input.value = '';

                });

                clone.querySelector('select').selectedIndex = 0;

                container.appendChild(clone);

            });

            container.addEventListener('click', function(e) {

                if (e.target.closest('.btnHapusLampiran')) {

                    if (container.querySelectorAll('.lampiran-item').length > 1) {

                        e.target.closest('.lampiran-item').remove();

                    }

                }
            });
        });

        document.addEventListener('DOMContentLoaded', function () {
            const kategoriSelect = document.getElementById('kategori_id')
            const bidangSelect = document.getElementById('bidang_id')

            function filterBidang() {
                const kategoriId = kategoriSelect.value;

                Array.from(bidangSelect.options).forEach(option => {
                    if (option.value === '') {
                        option.hidden = false;
                        return;
                    }

                    option.hidden = option.dataset.kategori !== kategoriId;
                });

                const selected = bidangSelect.options[bidangSelect.selectedIndex];
                if (selected && selected.hidden) {
                    bidangSelect.value = '';
                }
            }

            kategoriSelect.addEventListener('change', filterBidang);

            filterBidang();
        })
    </script>
@endpush
