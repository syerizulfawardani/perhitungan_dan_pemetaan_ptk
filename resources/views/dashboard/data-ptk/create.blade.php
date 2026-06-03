<x-layouts.app>
    <div class="container-fluid">
        <div class="card shadow-sm">
            <div class="card-header">
                <h3 class="card-title p-4 rounded mb-0 text-white bg-primary">Tambah Pendidik & Tenaga Kependidikan</h3>
            </div>

            <div class="card-body bg-light">
                <form action=" method"POST>
                    @csrf

                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors as $err )
                                    <li>{{ $err }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <div class="mb-3">
                        <label class="form-label">Nama Pendidik & Tenaga Kependidikan</label>
                        <input type="text" class="form-control" name="nama_ptk">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Kategori</label>
                        <select name="kategori_id" class="form-select">
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
                            @foreach ($jabatan as $j )
                                <option value="{{ $j->id }}">{{ $j->nama_jabatan}}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-labek">Bidang</label>
                        <select name="bidang_id" class="form-select">
                            <option value="">--Bidang--</option>
                            @foreach ($bidang as $b )
                            <option value="{{ $b->id }}">{{ $b->nama_bidang_sertifikasi }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Pangkat Golongan</label>
                        <select name="pangkat_golongan_id" class="form-select">
                            <option value="">--Pangkat Golongan--</option>
                            @foreach ($pangkat as $p )
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
</x-layouts.app>
