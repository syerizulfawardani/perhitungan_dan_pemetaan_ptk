<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PanggkatPTK extends Model
{
    protected $table = "golongan_ptk";

    public function data_ptk()
    {
        return $this->hasMany(DataPtk::class);
    }
}
