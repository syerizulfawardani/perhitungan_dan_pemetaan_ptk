<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KategoriPTK extends Model
{
    protected $table = 'kategori_ptk';

    protected $fillable = [
        'jenis_kategori'
    ];

    public function data_ptk()
    {
        return $this->hasMany(DataPTK::class, 'kategori_id');
    }
}
