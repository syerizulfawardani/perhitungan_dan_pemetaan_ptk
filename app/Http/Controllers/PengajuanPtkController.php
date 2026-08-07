<?php

namespace App\Http\Controllers;

use App\Models\BidangPTK;
use App\Models\KategoriPTK;
use App\Models\PengajuanPtk;
use App\Models\Sekolah;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\PengajuanPtkLampiran;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class PengajuanPtkController extends Controller
{
    // ── Index ────────────────────────────────────────────────

    public function index(Request $request)
    {
        $query = PengajuanPtk::with(['kategori', 'jabatan', 'golongan', 'bidang', 'operator']);

        if (Auth::user()->hasRole('operator_sekolah')) {
            $query->where('operator_id', Auth::id());
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('kategori')) {
            $query->where('kategori_id', $request->kategori);
        }

        if ($request->filled('search')) {
            $query->where('nama_ptk', 'like', '%' . $request->search . '%');
        }

        $pengajuans = $query->latest()->paginate(15)->withQueryString();
        $kategoris  = KategoriPTK::orderBy('jenis_kategori')->get();

        $stats = null;
        if (Auth::user()->hasRole('admin')) {
            $stats = [
                'menunggu'  => PengajuanPtk::where('status', 'menunggu')->count(),
                'proses'    => PengajuanPtk::where('status', 'proses')->count(),
                'disetujui' => PengajuanPtk::where('status', 'disetujui')->count(),
                'ditolak'   => PengajuanPtk::where('status', 'ditolak')->count(),
            ];
        }

        return view('dashboard.pengajuan.index', compact('pengajuans', 'kategoris', 'stats'));
    }

    // ── Create ───────────────────────────────────────────────

    public function create()
    {
        $kategoris       = KategoriPTK::orderBy('jenis_kategori')->get();
        $bidangs         = BidangPTK::orderBy('nama_bidang_sertifikasi')->get();
        $sekolahOperator = Auth::user()->sekolah()->first();
        $previewNomor    = PengajuanPtk::previewNomor();

        return view('dashboard.pengajuan.create', compact('kategoris', 'bidangs', 'sekolahOperator', 'previewNomor'));
    }

    // ── Store ────────────────────────────────────────────────

    public function store(Request $request)
    {
        $validated = $request->validate([
            'kategori_id'      => 'required|exists:kategori_ptk,id',
            'bidang_id'        => 'required|exists:bidang_studi_sertifikasi,id',
            'alasan_pengajuan' => 'required|string|min:10',

            // Lampiran
            'jenis_lampiran'       => 'required|array|min:1',
            'jenis_lampiran.*'     => 'required|string|max:100',

            'lampiran'             => 'required|array|min:1',
            'lampiran.*'           => 'required|file|mimes:pdf,jpg,jpeg,png|max:5120',

            'keterangan_lampiran'   => 'nullable|array',
            'keterangan_lampiran.*' => 'nullable|string|max:255',
        ], [
            'kategori_id.required'      => 'Silakan pilih kategori.',
            'bidang_id.required'        => 'Silakan pilih bidang.',
            'alasan_pengajuan.required' => 'Alasan pengajuan wajib diisi.',

            'lampiran.required'         => 'Minimal satu lampiran harus diunggah.',
            'lampiran.*.mimes'          => 'Lampiran harus berupa PDF, JPG, JPEG atau PNG.',
            'lampiran.*.max'            => 'Ukuran maksimal file 5 MB.',
        ]);

        DB::beginTransaction();

        try {

            $pengajuan = PengajuanPtk::create([
                'nomor_pengajuan'   => PengajuanPtk::generateNomor(),
                'kategori_id'       => $validated['kategori_id'],
                'bidang_id'         => $validated['bidang_id'],
                'alasan_pengajuan'  => $validated['alasan_pengajuan'],

                'tmt_pengangkatan'  => now()->toDateString(),
                'operator_id'       => Auth::id(),
                'diproses_oleh'     => Auth::id(),

                'status'            => PengajuanPtk::STATUS_MENUNGGU,
                'catatan_admin'     => '',
            ]);

            foreach ($request->file('lampiran') as $index => $file) {

                $path = $file->store('pengajuan-ptk', 'public');

                PengajuanPtkLampiran::create([
                    'pengajuan_ptk_id' => $pengajuan->id,

                    'jenis_lampiran'   => $request->jenis_lampiran[$index],

                    'nama_file'        => $file->getClientOriginalName(),

                    'path_file'        => $path,

                    'mime_type'        => $file->getMimeType(),

                    'ukuran_file'      => $file->getSize(),

                    'keterangan'       => $request->keterangan_lampiran[$index] ?? null,
                ]);
            }

            DB::commit();

            return redirect()
                ->route('pengajuan-ptk.index')
                ->with('success', 'Pengajuan PTK berhasil diajukan.');
        } catch (\Throwable $e) {

            DB::rollBack();

            if ($request->hasFile('lampiran')) {

                foreach ($request->file('lampiran') as $file) {

                    if (isset($path) && Storage::disk('public')->exists($path)) {
                        Storage::disk('public')->delete($path);
                    }
                }
            }

            return back()
                ->withInput()
                ->with('error', $e->getMessage());
        }
    }

    // ── Show ─────────────────────────────────────────────────

    public function show(PengajuanPtk $pengajuanPtk)
    {
        $this->authorizeAccess($pengajuanPtk);

        $pengajuanPtk->load(['kategori', 'jabatan', 'golongan', 'bidang', 'operator', 'diprosesOleh', 'lampiran']);

        return view('dashboard.pengajuan.show', compact('pengajuanPtk'));
    }

    // ── Edit ─────────────────────────────────────────────────

    public function edit(PengajuanPtk $pengajuanPtk)
    {
        $this->authorizeAccess($pengajuanPtk);

        abort_if(
            !in_array($pengajuanPtk->status, [PengajuanPtk::STATUS_MENUNGGU, PengajuanPtk::STATUS_DITOLAK]),
            403,
            'Pengajuan yang sedang/sudah diproses tidak dapat diedit.'
        );

        $kategoris       = KategoriPTK::orderBy('jenis_kategori')->get();
        $bidangs         = BidangPTK::orderBy('nama_bidang_sertifikasi')->get();
        $sekolahOperator = Sekolah::where('operator_id', $pengajuanPtk->operator_id)->first();

        return view('dashboard.pengajuan.edit', compact('pengajuanPtk', 'kategoris', 'bidangs', 'sekolahOperator'));
    }

    // ── Update ───────────────────────────────────────────────

    public function update(Request $request, PengajuanPtk $pengajuanPtk)
    {
        $this->authorizeAccess($pengajuanPtk);

        abort_if(
            !in_array($pengajuanPtk->status, [PengajuanPtk::STATUS_MENUNGGU, PengajuanPtk::STATUS_DITOLAK]),
            403
        );

        $validated = $request->validate([
            'kategori_id'      => 'required|exists:kategori_ptk,id',
            'bidang_id'        => 'required|exists:bidang_studi_sertifikasi,id',
            'alasan_pengajuan' => 'required|string|min:10',
        ], [
            'kategori_id.required'      => 'Please fill out this field',
            'bidang_id.required'        => 'Please fill out this field',
            'alasan_pengajuan.required' => 'Please fill out this field',
        ]);

        $sekolah = Sekolah::where('operator_id', $pengajuanPtk->operator_id)->first();
        $validated['nama_ptk'] = $sekolah?->nama_sekolah ?? $pengajuanPtk->nama_ptk;

        if ($pengajuanPtk->status === PengajuanPtk::STATUS_DITOLAK) {
            $validated['status']        = PengajuanPtk::STATUS_MENUNGGU;
            $validated['catatan_admin'] = '';
        }

        $pengajuanPtk->update($validated);

        return redirect()
            ->route('pengajuan-ptk.show', $pengajuanPtk)
            ->with('success', 'Pengajuan PTK berhasil diperbarui.');
    }

    // ── Destroy ──────────────────────────────────────────────

    public function destroy(PengajuanPtk $pengajuanPtk)
    {
        $this->authorizeAccess($pengajuanPtk);

        abort_if(
            $pengajuanPtk->status === PengajuanPtk::STATUS_PROSES,
            403,
            'Pengajuan sedang diproses, tidak dapat dihapus.'
        );

        $pengajuanPtk->delete();

        return redirect()
            ->route('pengajuan-ptk.index')
            ->with('success', 'Pengajuan PTK berhasil dihapus.');
    }

    // ── Admin: ubah status ───────────────────────────────────

    public function updateStatus(Request $request, PengajuanPtk $pengajuanPtk)
    {
        abort_if(!Auth::user()->hasRole('admin'), 403);

        $request->validate(
            [
                'status'        => 'required|in:proses,disetujui,ditolak',
                'catatan_admin' => 'nullable|required_if:status,ditolak|string|max:500',
            ],
            [
                'catatan_admin.required_if' => 'Catatan penolakan wajib diisi ketika status ditolak.',
            ],
            [
                'catatan_admin' => 'catatan admin',
            ]
        );

        $pengajuanPtk->update([
            'status'        => $request->status,
            'catatan_admin' => $request->catatan_admin ?? '',
            'diproses_oleh' => Auth::id(),
            'diproses_at'   => now(),
        ]);

        return back()->with('success', 'Status pengajuan berhasil diperbarui.');
    }

    // ── Private helpers ──────────────────────────────────────

    private function authorizeAccess(PengajuanPtk $pengajuanPtk): void
    {
        if (Auth::user()->hasRole('operator_sekolah')) {
            abort_if($pengajuanPtk->operator_id !== Auth::id(), 403);
        }
    }
}
