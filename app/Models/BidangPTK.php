<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BidangPTK extends Model
{
    protected $table = 'bidang_studi_sertifikasi';

    public function data_ptk()
    {
<<<<<<< HEAD
        return $this->hasMany(DataPtk::class);
=======
        return $this->hasMany(DataPTK::class);
>>>>>>> 06420b9fa31baacbcb99e256547b07247af52080
    }
}
