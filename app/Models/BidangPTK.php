<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BidangPTK extends Model
{
    protected $table = 'bidang_studi_sertifikasi';

    protected $fillable = [
        'nama_bidang_sertifikasi',
        'kategori_id',
    ];

    public function data_ptk()
    {
        return $this->hasMany(DataPTK::class);
    }

    public function kategori()
    {
        return $this->belongsTo(KategoriPTK::class, 'kategori_id');
    }
}
