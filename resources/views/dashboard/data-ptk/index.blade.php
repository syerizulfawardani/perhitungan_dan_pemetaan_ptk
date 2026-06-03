<x-layouts.app>
    <div class="container-fluid">
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-body d-flex flex-column flex-md-row justify-content-between align-items-center gap-3">
                <div class="d-flex align-items-center gap-3">
                    <div class="d-flex align-items-center justify-content-center rounded-circle bg-primary bg-opacity-10 text-primary" style="width: 56px; height:56px;">
                        <i class="ti ti-users fs-3"></i>
                    </div>

                    <div>
                        <h4 class="mb-1 fw-bold">Data Pendidik & Tenaga Kependidikan</h4>
                        <p class="text-muted mb-0">
                            Kelola data pendidik dan tenaga kependidikan terkait.
                        </p>
                    </div>
                </div>

                <a href="{{ route('data-ptk.create') }}" class="btn btn-primary px-4">
                    <i class="ti ti-plus me-1"></i>
                    Tambah Data PTK
                </a>
            </div>
        </div>
    </div>
</x-layouts.app>
