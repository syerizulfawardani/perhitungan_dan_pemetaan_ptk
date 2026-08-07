<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PengajuanPTKLampiran extends Model
{
    protected $table = 'pengajuan_ptk_lampiran';

    protected $fillable = [
        'pengajuan_ptk_id',
        'jenis_lampiran',
        'nama_file',
        'path_file',
        'mime_type',
        'ukuran_file',
        'keterangan',
    ];


    public function pengajuan()
    {
        return $this->belongsTo(PengajuanPtk::class, 'pengajuan_ptk_id');
    }
}
