@extends('layouts.app')

@section('title', 'Edit Pengajuan PTK')

@section('content')
<div class="container-fluid">

    <div class="d-flex align-items-center gap-3 mb-4">
        <a href="{{ route('pengajuan-ptk.show', $pengajuanPtk) }}" class="btn btn-sm btn-outline-secondary">
            <i class="ti ti-arrow-left"></i>
        </a>
        <div>
            <h4 class="mb-0 fw-semibold">Edit Pengajuan PTK</h4>
            <p class="text-muted mb-0 small">{{ $pengajuanPtk->nama_ptk }}</p>
        </div>
    </div>

    @if ($pengajuanPtk->status === 'ditolak' && $pengajuanPtk->catatan_admin)
        <div class="alert alert-warning d-flex gap-2 mb-4">
            <i class="ti ti-alert-triangle fs-5 flex-shrink-0 mt-1"></i>
            <div>
                <strong>Pengajuan sebelumnya ditolak.</strong>
                <p class="mb-0 mt-1">{{ $pengajuanPtk->catatan_admin }}</p>
                <p class="mb-0 text-muted small mt-1">Perbaiki data di bawah lalu ajukan kembali.</p>
            </div>
        </div>
    @endif

    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show">
            <strong><i class="ti ti-alert-circle me-1"></i>Terdapat kesalahan input:</strong>
            <ul class="mb-0 mt-2 ps-3">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="card">
        <div class="card-header d-flex align-items-center gap-2">
            <i class="ti ti-pencil text-warning fs-5"></i>
            <span class="fw-semibold">Data Pengajuan PTK</span>
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('pengajuan-ptk.update', $pengajuanPtk) }}">
                @csrf @method('PUT')

                @include('pengajuan_ptk.partials.form_fields')

                <div class="d-flex justify-content-end gap-2 mt-4 pt-3 border-top">
                    <a href="{{ route('pengajuan-ptk.show', $pengajuanPtk) }}" class="btn btn-outline-secondary">
                        Batal
                    </a>
                    <button type="submit" class="btn btn-primary px-4">
                        <i class="ti ti-device-floppy me-1"></i>Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>

</div>
@endsection
