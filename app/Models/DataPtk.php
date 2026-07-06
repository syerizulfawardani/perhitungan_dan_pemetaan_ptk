<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Kecamatan;

class DataPTK extends Model
{
    protected $table = "data_ptk";
    protected $fillable = [
        "sekolah_id",
        "kecamatan_id",
        "pengajuan_ptk_id",
        "nama_ptk",
        "kategori_id",
        "tmt_pengangkatan",
        "jabatan_id",
        "bidang_id",
        "pangkat_golongan_id",
    ];
    protected $casts = [
        'tmt_pengangkatan' => 'date',
    ];

    public function sekolah()
    {
        return $this->belongsTo(Sekolah::class);
    }

    public function kecamatan()
    {
        return $this->belongsTo(Kecamatan::class);
    }

    public function pengajuan()
    {
        return $this->belongsTo(PengajuanPtk::class, 'pengajuan_ptk_id');
    }

    public function kategori()
    {
        return $this->belongsTo(KategoriPTK::class, 'kategori_id');
    }

    public function jabatan()
    {
        return $this->belongsTo(JabatanPTK::class, 'jabatan_id');
    }

    public function bidang()
    {
        return $this->belongsTo(BidangPTK::class, 'bidang_id');
    }

    public function pangkat_golongan()
    {
        return $this->belongsTo(PangkatPTK::class, 'pangkat_golongan_id');
    }
}
