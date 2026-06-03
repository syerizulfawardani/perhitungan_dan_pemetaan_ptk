<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JabatanPTK extends Model
{
    protected $table = "jabatan_ptk";

    public function data_ptk()
    {
        return $this->hasMany(DataPtk::class);
    }
}
