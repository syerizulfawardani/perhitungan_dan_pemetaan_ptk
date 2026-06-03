<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KategoriPTK extends Model
{
    protected $table = 'katagori_ptk';

    public function data_ptk()
    {
        return $this->hasMany(DataPtk::class);
    }
}
