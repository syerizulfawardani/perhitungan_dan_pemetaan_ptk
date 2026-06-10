@extends('layouts.app')

@section('title', 'Buat Pengajuan PTK')

@section('content')
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
            <i class="ti ti-user-plus text-primary fs-5"></i>
            <span class="fw-semibold">Data Pengajuan PTK</span>
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('pengajuan-ptk.store') }}">
                @csrf

                @include('pengajuan_ptk.partials.form_fields')

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
@endsection
