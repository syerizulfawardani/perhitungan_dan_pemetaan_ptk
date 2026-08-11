<?php

namespace App\Http\Controllers;

use App\Exports\DataPtkExport;
use App\Imports\DataPtkImport;
use App\Models\BidangPTK;
use App\Models\DataPTK;
use App\Models\JabatanPTK;
use App\Models\Kecamatan;
use App\Models\KategoriPTK;
use App\Models\PangkatPTK;
use App\Models\Sekolah;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class DataPTKController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->search;
        $kategori = $request->kategori; // "Pendidik" | "Tenaga Kependidikan"
        $sekolahs = Sekolah::orderBy('nama_sekolah')->get();

        $dataPtk = DataPTK::with(['kategori', 'jabatan', 'pangkat_golongan', 'sekolah'])
            ->when($search, function ($query) use ($search) {
                $query->where('nama_ptk', 'like', "%{$search}%")->orWhere('jabatan_id', 'like', "%{$search}%");
            })
            ->when($kategori, function ($query) use ($kategori) {
                $query->whereHas('kategori', function ($q) use ($kategori) {
                    $q->where('jenis_kategori', $kategori);
                });
            })
            ->orderBy('id')
            ->paginate(10)
            ->withQueryString();

        return view('dashboard.data-ptk.index', compact('dataPtk', 'sekolahs'));
    }

    public function create()
    {
        $kategori = KategoriPTK::orderBy('jenis_kategori')->get();
        $jabatan = JabatanPTK::orderBy('nama_jabatan')->get();
        $bidang = BidangPTK::orderBy('nama_bidang_sertifikasi')->get();
        $pangkat = PangkatPTK::orderBy('id')->get();
        $sekolah = Sekolah::with('kecamatan')->orderBy('nama_sekolah')->get();
        $kecamatan = Kecamatan::orderBy('nama_kecamatan')->get();

        return view('dashboard.data-ptk.create', compact('kategori', 'bidang', 'jabatan', 'pangkat', 'sekolah', 'kecamatan'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate(
            [
                'sekolah_id' => 'nullable|exists:sekolah,id',
                'kecamatan_id' => 'nullable|exists:kecamatan,id',
                'kategori_id' => 'required|exists:kategori_ptk,id',
                'nama_ptk' => 'required|string|max:255',
                'tmt_pengangkatan' => 'nullable|date',
                'jabatan_id' => 'required|exists:jabatan_ptk,id',
                'bidang_id' => 'required|exists:bidang_studi_sertifikasi,id',
                'pangkat_golongan_id' => 'required|exists:golongan_ptk,id',
            ],
            [
                'kategori_id.required' => 'Please fill out this field',
                'nama_ptk.required' => 'Please fill out this field',
                'jabatan_id.required' => 'Please fill out this field',
                'bidang_id.required' => 'Please fill out this field',
                'pangkat_golongan_id.required' => 'Please fill out this field',
            ],
        );

        // Jika kecamatan tidak diisi manual, ambil dari sekolah yang dipilih
        if (empty($validated['kecamatan_id']) && !empty($validated['sekolah_id'])) {
            $sekolah = Sekolah::find($validated['sekolah_id']);
            $validated['kecamatan_id'] = $sekolah?->kecamatan_id;
        }

        DataPTK::create($validated);

        return redirect()->route('data-ptk')->with('success', 'Data PTK Berhasil Ditambahkan.');
    }

    public function show($id)
    {
        $ptk = DataPTK::with(['sekolah.kecamatan', 'kategori', 'jabatan', 'bidang', 'pangkat_golongan'])->findOrFail($id);

        return view('dashboard.data-ptk.show', compact('ptk'));
    }

    public function edit($id)
    {
        $ptk = DataPTK::findOrFail($id);
        $kategori = KategoriPTK::orderBy('jenis_kategori')->get();
        $jabatan = JabatanPTK::orderBy('nama_jabatan')->get();
        $bidang = BidangPTK::orderBy('nama_bidang_sertifikasi')->get();
        $pangkat = PangkatPTK::orderBy('id')->get();
        $sekolah = Sekolah::with('kecamatan')->orderBy('nama_sekolah')->get();
        $kecamatan = Kecamatan::orderBy('nama_kecamatan')->get();

        return view('dashboard.data-ptk.edit', compact('ptk', 'kategori', 'jabatan', 'bidang', 'pangkat', 'sekolah', 'kecamatan'));
    }

    public function update(Request $request, $id)
    {
        $ptk = DataPTK::findOrFail($id);

        $validated = $request->validate(
            [
                'sekolah_id' => 'nullable|exists:sekolah,id',
                'kecamatan_id' => 'nullable|exists:kecamatan,id',
                'kategori_id' => 'required|exists:kategori_ptk,id',
                'nama_ptk' => 'required|string|max:255',
                'tmt_pengangkatan' => 'nullable|date',
                'jabatan_id' => 'required|exists:jabatan_ptk,id',
                'bidang_id' => 'required|exists:bidang_studi_sertifikasi,id',
                'pangkat_golongan_id' => 'required|exists:golongan_ptk,id',
            ],
            [
                'kategori_id.required' => 'Please fill out this field',
                'nama_ptk.required' => 'Please fill out this field',
                'jabatan_id.required' => 'Please fill out this field',
                'bidang_id.required' => 'Please fill out this field',
                'pangkat_golongan_id.required' => 'Please fill out this field',
            ],
        );

        if (empty($validated['kecamatan_id']) && !empty($validated['sekolah_id'])) {
            $sekolah = Sekolah::find($validated['sekolah_id']);
            $validated['kecamatan_id'] = $sekolah?->kecamatan_id;
        }

        $ptk->update($validated);

        return redirect()->route('data-ptk')->with('success', 'Data PTK berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $ptk = DataPTK::findOrFail($id);
        $ptk->delete();

        return redirect()->route('data-ptk')->with('success', 'Data PTK berhasil dihapus.');
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:xlsx,xls,csv|max:5120',
        ]);

        $import = new DataPtkImport();

        Excel::import($import, $request->file('file'));

        $failures = $import->failures();

        $berhasil = $import->getImportedCount();
        $dilewati = $import->getSkippedCount();
        $gagal = $failures->count();

        if ($gagal > 0) {
            $pesan = $failures
                ->map(function ($failure) {
                    return "Baris {$failure->row()}: " . implode(', ', $failure->errors());
                })
                ->implode(' | ');

            return back()->with('warning', "Import selesai. {$berhasil} data berhasil diimport, {$dilewati} data dilewati karena sudah ada, {$gagal} data gagal. {$pesan}");
        }

        return back()->with('success', "Import selesai. {$berhasil} data berhasil diimport, {$dilewati} data dilewati karena sudah ada.");
    }

    public function export(Request $request)
    {
        $sekolahId = $request->sekolah_id;

        $namaFile = 'data-ptk.xlsx';

        if ($sekolahId) {
            $sekolah = Sekolah::find($sekolahId);

            if ($sekolah) {
                $slug = str($sekolah->nama_sekolah)->slug('_');
                $namaFile = 'PTK_' . $slug . '.xlsx';
            }
        }

        return Excel::download(new DataPtkExport($sekolahId), $namaFile);
    }
}
