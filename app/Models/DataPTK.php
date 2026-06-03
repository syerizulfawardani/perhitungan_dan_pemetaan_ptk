<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DataPTK extends Model
{
    protected $fillable = [
        "nama_ptk", "kategori_id", "tmt_pengangkatan", "jabatan_id", "bidang_id", "pangkat_golongan_id"
    ];
    protected $casts = [
        'tmt_pengangkatan'=> 'date',
    ];

    public function kategori()
    {
        return $this->belongsTo(KategoriPTK::class);
    }

    public function jabatan()
    {
        return $this->belongsTo(JabatanPTK::class);
    }

    public function bidang()
    {
        return $this->belongsTo(BidangPTK::class);
    }

    public function pangkat_golongan()
    {
        return $this->belongsTo(PangkatPTK::class);
    }
}
