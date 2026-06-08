<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JabatanPTK extends Model
{
    protected $table = "jabatan_ptk";

    public function data_ptk()
    {
<<<<<<< HEAD
        return $this->hasMany(DataPtk::class);
=======
        return $this->hasMany(DataPTK::class);
>>>>>>> 06420b9fa31baacbcb99e256547b07247af52080
    }
}
