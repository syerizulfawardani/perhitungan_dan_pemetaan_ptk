<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PangkatPTK extends Model
{
    protected $table = "golongan_ptk";

    public function data_ptk()
    {
        return $this->hasMany(DataPTK::class);
    }
}
