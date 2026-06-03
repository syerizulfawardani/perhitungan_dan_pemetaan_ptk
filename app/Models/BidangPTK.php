<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BidangPTK extends Model
{
    protected $table = 'bidang_studi_sertifikasi';

    public function data_ptk()
    {
        return $this->hasMany(DataPTK::class);
    }
}
