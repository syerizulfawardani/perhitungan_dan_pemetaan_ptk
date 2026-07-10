@php $old = $pengajuanPtk ?? null; @endphp

<div class="row g-3">

    {{-- Nomor Pengajuan (otomatis) --}}
    <div class="col-12">
        <label class="form-label fw-semibold">
            Nomor Pengajuan
        </label>
        @if($old)
            {{-- Mode EDIT: tampilkan nomor yang sudah ada --}}
            <div class="input-group">
                <span class="input-group-text bg-primary text-white">
                    <i class="ti ti-hash"></i>
                </span>
                <input type="text"
                       class="form-control bg-light fw-medium"
                       value="{{ $old->nomor_pengajuan }}"
                       readonly>
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
                <input type="text"
                       class="form-control bg-light fw-medium"
                       value="{{ $previewNomor ?? '-' }}"
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
            <input type="text"
                   class="form-control bg-light"
                   value="{{ $sekolahOperator?->nama_sekolah ?? '-' }}"
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
            <input type="date" name="tmt_pengangkatan"
                   class="form-control bg-light"
                   value="{{ old('tmt_pengangkatan', $old?->tmt_pengangkatan?->format('Y-m-d') ?? now()->format('Y-m-d')) }}"
                   readonly>
        </div>
    </div>

    {{-- Kategori --}}
    <div class="col-md-6">
        <label class="form-label fw-semibold">
            Kategori PTK <span class="text-danger">*</span>
        </label>
        <select name="kategori_id"
                class="form-select @error('kategori_id') is-invalid @enderror">
            <option value="">-- Pilih Kategori --</option>
            @foreach ($kategoris as $kat)
                <option value="{{ $kat->id }}"
                    {{ old('kategori_id', $old?->kategori_id) == $kat->id ? 'selected' : '' }}>
                    {{ $kat->jenis_kategori }}
                </option>
            @endforeach
        </select>
        @error('kategori_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    {{-- Bidang Studi Sertifikasi --}}
    <div class="col-md-6">
        <label class="form-label fw-semibold">
            Bidang Studi <span class="text-danger">*</span>
        </label>
        <select name="bidang_id"
                class="form-select @error('bidang_id') is-invalid @enderror">
            <option value="">-- Pilih Bidang --</option>
            @foreach ($bidangs as $b)
                <option value="{{ $b->id }}"
                    {{ old('bidang_id', $old?->bidang_id) == $b->id ? 'selected' : '' }}>
                    {{ $b->nama_bidang_sertifikasi }}
                </option>
            @endforeach
        </select>
        @error('bidang_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    {{-- Alasan Pengajuan --}}
    <div class="col-12">
        <label class="form-label fw-semibold">
            Alasan Pengajuan <span class="text-danger">*</span>
            <span class="text-muted fw-normal small">(minimal 20 karakter)</span>
        </label>
        <textarea name="alasan_pengajuan" rows="5"
                  class="form-control @error('alasan_pengajuan') is-invalid @enderror"
                  placeholder="Tuliskan alasan dan latar belakang pengajuan PTK ini secara jelas...">{{ old('alasan_pengajuan', $old?->alasan_pengajuan) }}</textarea>
        @error('alasan_pengajuan')<div class="invalid-feedback">{{ $message }}</div>@enderror
        <div class="form-text" id="charCount">0 karakter</div>
    </div>

</div>

@push('scripts')
<script>
(function () {
    const ta    = document.querySelector('textarea[name="alasan_pengajuan"]');
    const count = document.getElementById('charCount');
    if (!ta || !count) return;

    function update() {
        const n = ta.value.length;
        count.textContent = n + ' karakter';
        count.className   = 'form-text ' + (n >= 20 ? 'text-success' : 'text-muted');
    }

    ta.addEventListener('input', update);
    update(); // init saat edit
})();
</script>
@endpush
