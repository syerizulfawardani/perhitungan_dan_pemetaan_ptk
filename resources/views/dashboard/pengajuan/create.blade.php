<x-layouts.app>
<div class="container-fluid">

    <div class="d-flex align-items-center gap-3 mb-4">
        <a href="{{ route('pengajuan-ptk.index') }}" class="btn btn-sm btn-outline-secondary">
            <i class="ti ti-arrow-left"></i>
        </a>
        <div>
            <h4 class="mb-0 fw-semibold">Buat Pengajuan PTK</h4>
            <p class="text-muted mb-0 small">Isi data PTK yang akan diajukan</p>
        </div>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show">
            <strong><i class="ti ti-alert-circle me-1"></i>Terdapat kesalahan input:</strong>
            <p class="mb-0 mt-2">{{ $errors->first() }}</p>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white border-bottom d-flex align-items-center gap-2 py-3">
            <i class="ti ti-user-plus text-primary fs-5"></i>
            <span class="fw-semibold">Data Pengajuan PTK</span>
        </div>
        <div class="card-body p-4">
            <form method="POST" action="{{ route('pengajuan-ptk.store') }}">
                @csrf
                @include('dashboard.pengajuan.partials.form_fields')

                <div class="d-flex justify-content-end gap-2 mt-4 pt-3 border-top">
                    <a href="{{ route('pengajuan-ptk.index') }}" class="btn btn-outline-secondary">
                        Batal
                    </a>
                    <button type="submit" class="btn btn-primary px-4">
                        <i class="ti ti-send me-1"></i>Ajukan
                    </button>
                </div>
            </form>
        </div>
    </div>

</div>
</x-layouts.app>
