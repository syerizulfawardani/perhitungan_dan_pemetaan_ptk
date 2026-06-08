<?php

namespace App\Http\Controllers;

use App\Models\BidangPTK;
use App\Models\DataPTK;
use App\Models\JabatanPTK;
use App\Models\KategoriPTK;
use App\Models\PangkatPTK;
use Illuminate\Http\Request;

class DataPTKController extends Controller
{
    public function index()
    {
        $dataPtk = DataPTK::with(['bidang', 'jabatan', 'kategori', 'pangkat_golongan'])->latest()->get();

        return view('dashboard.data-ptk.index', compact('dataPtk'));
    }

    public function create()
    {
        $kategori = KategoriPTK::all();
        $jabatan = JabatanPTK::all();
        $bidang = BidangPTK::all();
        $pangkat = PangkatPTK::all();

        return view('dashboard.data-ptk.create', compact('kategori', 'bidang', 'jabatan', 'pangkat'));
    }

    public function store(Request $request)
    {
        $validate = $request->validate([
            'kategori_id' => 'required|exists:kategori_ptk,id',
            'nama_ptk' => 'required|string',
            'tmt_pengangkatan' => 'date',
            'jabatan_id' => 'required|exists:jabatan_ptk,id',
            'bidang_id' => 'required|exists:bidang_studi_sertifikasi,id',
            'pangkat_golongan_id' => 'required|exists:golongan_ptk,id',
        ]);

        DataPTK::create($validate);

        return redirect()->route('data-ptk')->with('success', 'Data Berhasil Disimpan');
    }

    public function show($id)
    {
        $ptk = DataPTK::findOrFail($id);

        return view('dashboard.data-ptk.show', compact('ptk'));
    }

    public function edit($id)
    {
        $ptk = DataPTK::findOrFail($id);

        $kategori = KategoriPTK::all();
        $jabatan = JabatanPTK::all();
        $bidang = BidangPTK::all();
        $pangkat = PangkatPTK::all();

        return view('dashboard.data-ptk.edit', compact('ptk', 'kategori', 'jabatan', 'bidang', 'pangkat'));
    }

    public function update(Request $request, $id)
    {
        $ptk = DataPTK::findOrFail($id);
        $validate = $request->validate([
            'kategori_id' => 'required|exists:kategori_ptk,id',
            'nama_ptk' => 'required|string',
            'tmt_pengangkatan' => 'date',
            'jabatan_id' => 'required|exists:jabatan_ptk,id',
            'bidang_id' => 'required|exists:bidang_studi_sertifikasi,id',
            'pangkat_golongan_id' => 'required|exists:golongan_ptk,id',
        ]);

        $ptk->update($validate);

        return redirect()->route('data-ptk')->with('success', 'Data PTK Berhasil di Perbaharui');
    }

    public function destroy($id)
    {
        $ptk = DataPTK::findOrFail($id);

        $ptk->delete();

        return redirect()->route('data-ptk')->with('success', 'Data PTK Berhasil di Hapus');
    }
}
