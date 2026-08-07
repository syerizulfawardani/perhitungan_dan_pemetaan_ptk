<?php

namespace App\Exports;

use App\Models\DataPTK;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;

class DataPtkExport implements FromCollection, WithHeadings, ShouldAutoSize
{
    public function collection()
    {
        return DataPTK::with([
            'sekolah',
            'kategori',
            'jabatan',
            'bidang',
            'pangkat_golongan',
        ])
        ->orderBy('nama_ptk')
        ->get()
        ->map(function ($ptk) {
            return [
                'nama_ptk'            => $ptk->nama_ptk,
                'npsn'                => $ptk->sekolah?->npsn_sekolah,
                'nama_sekolah'        => $ptk->sekolah?->nama_sekolah,
                'kategori'            => $ptk->kategori?->jenis_kategori,
                'jabatan'             => $ptk->jabatan?->nama_jabatan,
                'bidang'              => $ptk->bidang?->nama_bidang_sertifikasi,
                'pangkat_golongan'    => $ptk->pangkat_golongan?->nama_golongan,
                'tmt_pengangkatan'    => optional($ptk->tmt_pengangkatan)->format('Y-m-d'),
            ];
        });
    }

    public function headings(): array
    {
        return [
            'Nama PTK',
            'NPSN',
            'Nama Sekolah',
            'Kategori',
            'Jabatan',
            'Bidang Sertifikasi',
            'Pangkat / Golongan',
            'TMT Pengangkatan',
        ];
    }
}
