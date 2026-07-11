<x-layouts.app>
<div class="container-fluid">

    {{-- Header --}}
    <div class="d-flex align-items-start justify-content-between mb-4">
        <div class="d-flex align-items-center gap-3">
            <a href="{{ route('pengajuan-ptk.index') }}" class="btn btn-sm btn-outline-secondary">
                <i class="ti ti-arrow-left"></i>
            </a>
            <div>
                <h4 class="mb-0 fw-semibold">{{ $pengajuanPtk->nama_ptk }}</h4>
                <p class="text-muted mb-0 small">
                    Diajukan oleh <strong>{{ $pengajuanPtk->operator->name ?? '-' }}</strong>
                    pada {{ $pengajuanPtk->created_at->format('d M Y, H:i') }}
                </p>
            </div>
        </div>
        @php $cfg = \App\Models\PengajuanPtk::$statusConfig[$pengajuanPtk->status] ?? null @endphp
        @if ($cfg)
            <span class="badge {{ $cfg['class'] }} fs-6 px-3 py-2">
                <i class="ti {{ $cfg['icon'] }} me-1"></i>{{ $cfg['label'] }}
            </span>
        @endif
    </div>

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            <i class="ti ti-circle-check me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="row g-4">

        {{-- Kolom Kiri: Data PTK --}}
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white border-bottom fw-semibold d-flex align-items-center gap-2 py-3">
                    <i class="ti ti-user text-primary"></i> Data PTK
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-12">
                            <p class="text-muted small mb-1">Nomor Pengajuan</p>
                            <p class="fw-semibold fs-5 mb-0">{{ $pengajuanPtk->nomor_pengajuan }}</p>
                        </div>
                        <div class="col-md-6">
                            <p class="text-muted small mb-1">Kategori PTK</p>
                            <p class="fw-semibold mb-0">{{ $pengajuanPtk->kategori->jenis_kategori ?? '-' }}</p>
                        </div>
                        <div class="col-12">
                            <p class="text-muted small mb-1">Alasan Pengajuan</p>
                            <div class="bg-light rounded p-3">
                                <p class="mb-0" style="white-space: pre-line">{{ $pengajuanPtk->alasan_pengajuan }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            @if ($pengajuanPtk->status === 'ditolak' && $pengajuanPtk->catatan_admin)
                <div class="alert alert-danger d-flex gap-2">
                    <i class="ti ti-circle-x fs-5 flex-shrink-0 mt-1"></i>
                    <div>
                        <strong>Catatan Admin:</strong>
                        <p class="mb-0 mt-1">{{ $pengajuanPtk->catatan_admin }}</p>
                    </div>
                </div>
            @endif

            <div class="d-flex gap-2">
                @if (in_array($pengajuanPtk->status, ['menunggu', 'ditolak']))
                    <a href="{{ route('pengajuan-ptk.edit', $pengajuanPtk) }}" class="btn btn-warning">
                        <i class="ti ti-pencil me-1"></i>Edit
                    </a>
                    <form action="{{ route('pengajuan-ptk.destroy', $pengajuanPtk) }}" method="POST"
                          class="js-delete-form"
                          data-confirm-text="Pengajuan ini akan dihapus permanen.">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn btn-danger">
                            <i class="ti ti-trash me-1"></i>Hapus
                        </button>
                    </form>
                @endif
                <a href="{{ route('pengajuan-ptk.index') }}" class="btn btn-outline-secondary">
                    <i class="ti ti-arrow-left me-1"></i>Kembali
                </a>
            </div>
        </div>

        {{-- Kolom Kanan: Info & Panel Admin --}}
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white border-bottom fw-semibold d-flex align-items-center gap-2 py-3">
                    <i class="ti ti-info-circle text-primary"></i> Info Pengajuan
                </div>
                <div class="card-body">
                    <dl class="row mb-0 small">
                        <dt class="col-5 text-muted fw-normal">Diajukan oleh</dt>
                        <dd class="col-7 fw-semibold mb-2">{{ $pengajuanPtk->operator->name ?? '-' }}</dd>

                        <dt class="col-5 text-muted fw-normal">Tanggal ajuan</dt>
                        <dd class="col-7 fw-semibold mb-2">{{ $pengajuanPtk->created_at->format('d M Y') }}</dd>

                        <dt class="col-5 text-muted fw-normal">Terakhir diperbarui</dt>
                        <dd class="col-7 fw-semibold mb-2">{{ $pengajuanPtk->updated_at->format('d M Y') }}</dd>

                        @if ($pengajuanPtk->diproses_at && $pengajuanPtk->status !== 'menunggu')
                            <dt class="col-5 text-muted fw-normal">Diproses oleh</dt>
                            <dd class="col-7 fw-semibold mb-2">{{ $pengajuanPtk->diprosesOleh->name ?? '-' }}</dd>

                            <dt class="col-5 text-muted fw-normal">Tgl diproses</dt>
                            <dd class="col-7 fw-semibold mb-0">{{ $pengajuanPtk->diproses_at?->format('d M Y') }}</dd>
                        @endif
                    </dl>
                </div>
            </div>

            @role('admin')
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-bottom fw-semibold d-flex align-items-center gap-2 py-3">
                    <i class="ti ti-settings text-primary"></i> Kelola Status
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('pengajuan-ptk.update-status', $pengajuanPtk) }}">
                        @csrf @method('PATCH')
                        <div class="mb-3">
                            <label class="form-label small fw-semibold">Status Baru</label>
                            <select name="status" id="statusAdmin" class="form-select">
                                <option value="proses"    {{ $pengajuanPtk->status == 'proses'    ? 'selected' : '' }}>Diproses</option>
                                <option value="disetujui" {{ $pengajuanPtk->status == 'disetujui' ? 'selected' : '' }}>Disetujui</option>
                                <option value="ditolak"   {{ $pengajuanPtk->status == 'ditolak'   ? 'selected' : '' }}>Ditolak</option>
                            </select>
                        </div>
                        <div class="mb-3" id="catatanWrap"
                             style="{{ $pengajuanPtk->status == 'ditolak' ? '' : 'display:none' }}">
                            <label class="form-label small fw-semibold">
                                Catatan Penolakan <span class="text-danger">*</span>
                            </label>
                            <textarea name="catatan_admin" rows="3"
                                      class="form-control @error('catatan_admin') is-invalid @enderror"
                                      placeholder="Tulis alasan penolakan...">{{ old('catatan_admin', $pengajuanPtk->catatan_admin) }}</textarea>
                            @error('catatan_admin')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="ti ti-device-floppy me-1"></i>Simpan Status
                        </button>
                    </form>
                </div>
            </div>
            @endrole
        </div>
    </div>

</div>

@push('scripts')
<script>
document.getElementById('statusAdmin')?.addEventListener('change', function () {
    document.getElementById('catatanWrap').style.display = this.value === 'ditolak' ? '' : 'none';
});
</script>
@endpush
</x-layouts.app>
