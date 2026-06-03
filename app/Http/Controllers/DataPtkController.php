<?php

namespace App\Http\Controllers;

use App\Models\BidangPTK;
use App\Models\DataPtk;
use App\Models\JabatanPTK;
use App\Models\KategoriPTK;
use App\Models\PanggkatPTK;
use Illuminate\Http\Request;

class DataPtkController extends Controller
{
    public function index()
    {
        return view('dashboard.data-ptk.index');
    }

    public function create()
    {
        $kategori = KategoriPTK::all();
        $jabatan = JabatanPTK::all();
        $bidang = BidangPTK::all();
        $pangkat = PanggkatPTK::all();

        return view('dashboard.data-ptk.create', compact('kategori', 'jabatan', 'bidang', 'pangkat'));
    }
}
