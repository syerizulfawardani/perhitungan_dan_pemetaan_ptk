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
<<<<<<< HEAD
        return $this->hasMany(DataPtk::class, 'kategori_id');
=======
        return $this->hasMany(DataPTK::class, 'kategori_ptk');
>>>>>>> 06420b9fa31baacbcb99e256547b07247af52080
    }
}
