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
        $dataPtk = DataPtk::with(['bidang', 'jabatan', 'kategori', 'pangkat_golongan'])->latest()->get();

        return view('dashboard.data-ptk.index', compact('dataPtk'));
    }

    public function create()
    {
        $kategori = KategoriPTK::all();
        $jabatan = JabatanPTK::all();
        $bidang = BidangPTK::all();
        $pangkat = PanggkatPTK::all();

        return view('dashboard.data-ptk.create', compact('kategori', 'jabatan', 'bidang', 'pangkat'));
    }

    public function store(Request $request)
    {
        $validate = $request->validate([
            'kategori_id' => 'required|exists:kategori_ptk,id',
            'nama_ptk' => "required|string",
            'tmt_pengangkatan' => 'date',
            'jabatan_id' => 'required|exists:jabatan_ptk,id',
            'bidang_id' => 'required|exists:bidang_studi_sertifikasi,id',
            'pangkat_golongan_id' => "required|exists:golongan_ptk,id",
        ]);

        DataPtk::create($validate);

        return redirect()->route('data-ptk')->with('success', 'Data berhasil disimpan');
    }
}
