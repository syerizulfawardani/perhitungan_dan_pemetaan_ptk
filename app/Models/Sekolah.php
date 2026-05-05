<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sekolah extends Model
{
    protected $table = "sekolah";
    protected $fillable = [
        'nama_sekolah',
        'npsn_sekolah',
        'alamat_sekolah',
        'kecamatan_id',
        'kabupaten_id',
        'jenjang_sekolah',
        'scope_pengelolaan',
        'operator_id',
    ];
    public function operator()
    {
        return $this->belongsTo(User::class,'operator_id');
    }

    public function kecamatan()
    {
        return $this->belongsTo(Kecamatan::class,'kecamatan_id');
    }

    public function kabupaten()
    {
        return $this->belongsTo(Kabupaten::class,'kabupaten_id');
    }
}
