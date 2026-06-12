<?php

namespace App\Http\Controllers;

use App\Models\BidangPTK;
use App\Models\DataPTK;
use App\Models\JabatanPTK;
use App\Models\KategoriPTK;
use App\Models\PangkatPTK;
use App\Models\Sekolah;
use Illuminate\Http\Request;

class DataPTKController extends Controller
{
    public function index()
    {
        $dataPtk = DataPTK::with(['sekolah.kecamatan', 'bidang', 'jabatan', 'kategori', 'pangkat_golongan'])
            ->latest()
            ->get();

        return view('dashboard.data-ptk.index', compact('dataPtk'));
    }

    public function create()
    {
        $kategori = KategoriPTK::orderBy('jenis_kategori')->get();
        $jabatan  = JabatanPTK::orderBy('nama_jabatan')->get();
        $bidang   = BidangPTK::orderBy('nama_bidang_sertifikasi')->get();
        $pangkat  = PangkatPTK::orderBy('id')->get();
        $sekolah  = Sekolah::with('kecamatan')->orderBy('nama_sekolah')->get();

        return view('dashboard.data-ptk.create', compact('kategori', 'bidang', 'jabatan', 'pangkat', 'sekolah'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'sekolah_id'          => 'nullable|exists:sekolah,id',
            'kategori_id'         => 'required|exists:kategori_ptk,id',
            'nama_ptk'            => 'required|string|max:255',
            'tmt_pengangkatan'    => 'nullable|date',
            'jabatan_id'          => 'required|exists:jabatan_ptk,id',
            'bidang_id'           => 'required|exists:bidang_studi_sertifikasi,id',
            'pangkat_golongan_id' => 'required|exists:golongan_ptk,id',
        ]);

        DataPTK::create($validated);

        return redirect()->route('data-ptk')->with('success', 'Data PTK berhasil disimpan.');
    }

    public function show($id)
    {
        $ptk = DataPTK::with(['sekolah.kecamatan', 'kategori', 'jabatan', 'bidang', 'pangkat_golongan'])
            ->findOrFail($id);

        return view('dashboard.data-ptk.show', compact('ptk'));
    }

    public function edit($id)
    {
        $ptk      = DataPTK::findOrFail($id);
        $kategori = KategoriPTK::orderBy('jenis_kategori')->get();
        $jabatan  = JabatanPTK::orderBy('nama_jabatan')->get();
        $bidang   = BidangPTK::orderBy('nama_bidang_sertifikasi')->get();
        $pangkat  = PangkatPTK::orderBy('id')->get();
        $sekolah  = Sekolah::with('kecamatan')->orderBy('nama_sekolah')->get();

        return view('dashboard.data-ptk.edit', compact('ptk', 'kategori', 'jabatan', 'bidang', 'pangkat', 'sekolah'));
    }

    public function update(Request $request, $id)
    {
        $ptk = DataPTK::findOrFail($id);

        $validated = $request->validate([
            'sekolah_id'          => 'nullable|exists:sekolah,id',
            'kategori_id'         => 'required|exists:kategori_ptk,id',
            'nama_ptk'            => 'required|string|max:255',
            'tmt_pengangkatan'    => 'nullable|date',
            'jabatan_id'          => 'required|exists:jabatan_ptk,id',
            'bidang_id'           => 'required|exists:bidang_studi_sertifikasi,id',
            'pangkat_golongan_id' => 'required|exists:golongan_ptk,id',
        ]);

        $ptk->update($validated);

        return redirect()->route('data-ptk')->with('success', 'Data PTK berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $ptk = DataPTK::findOrFail($id);
        $ptk->delete();

        return redirect()->route('data-ptk')->with('success', 'Data PTK berhasil dihapus.');
    }
}
