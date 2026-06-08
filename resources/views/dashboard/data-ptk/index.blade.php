<x-layouts.app>
    <div class="container-fluid">
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-body d-flex flex-column flex-md-row justify-content-between align-items-center gap-3">
                <div class="d-flex align-items-center gap-3">
<<<<<<< HEAD
                    <div class="d-flex align-items-center justify-content-center rounded-circle bg-primary bg-opacity-10 text-primary"
                        style="width: 56px; height:56px">
=======
                    <div class="d-flex align-items-center justify-content-center rounded-circle bg-primary bg-opacity-10 text-primary" style="width: 56px; height:56px;">
>>>>>>> 06420b9fa31baacbcb99e256547b07247af52080
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
<<<<<<< HEAD

        <div class="card border-0 shadow-sm">
            <div class="card-body p-0">
                <div class="table-responsive">
=======
        <div class="card border-0 shadow-sm">
            <div class="card-body p-0">
                <div class="table-reponsive">
>>>>>>> 06420b9fa31baacbcb99e256547b07247af52080
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
                                            <a href="" class="btn btn-info btn-sm" title="Detail">
                                                <i class="ti ti-eye"></i>
                                            </a>

                                            <a href="" class="btn btn-warning btn-sm" title="Edit">
                                                <i class="ti ti-edit"></i>
                                            </a>

<<<<<<< HEAD
                                            <form action="" method="POST"
                                                onsubmit="return confirm('Yakin ingin menghapus data ini?')">
=======
                                            <form action="" method="POST" onsubmit="return confirm('Yakin Ingin Menghapus Data Ini?')">
>>>>>>> 06420b9fa31baacbcb99e256547b07247af52080
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

                                            <h6 class="fw-semibold mb-1">
<<<<<<< HEAD
                                                Data sekolah tidak ditemukan
                                            </h6>

                                            <span class="small">
                                                Silahkan tambahkan data sekolah terlebih dahulu.
=======
                                                Data Sekolah Tidak Ditemukan
                                            </h6>

                                            <span class="small">
                                                Silahkan Tambahkan Data Sekolah Terlebih Dahulu.
>>>>>>> 06420b9fa31baacbcb99e256547b07247af52080
                                            </span>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-layouts.app>
