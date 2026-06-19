<?php

namespace App\Http\Controllers;

use App\Models\BidangPTK;
use App\Models\KategoriPTK;
use App\Models\PengajuanPtk;
use App\Models\Sekolah;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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

        return view('dashboard.pengajuan.create', compact('kategoris', 'bidangs', 'sekolahOperator'));
    }

    // ── Store ────────────────────────────────────────────────

    public function store(Request $request)
    {
        $validated = $request->validate([
            'kategori_id'      => 'required|exists:kategori_ptk,id',
            'bidang_id'        => 'required|exists:bidang_studi_sertifikasi,id',
            'alasan_pengajuan' => 'required|string|min:10',
        ]);

        $sekolah = Auth::user()->sekolah()->first();

        $validated['nama_ptk']        = $sekolah?->nama_sekolah ?? Auth::user()->name;
        $validated['tmt_pengangkatan'] = now()->toDateString();
        $validated['operator_id']      = Auth::id();
        $validated['diproses_oleh']    = Auth::id();
        $validated['status']           = PengajuanPtk::STATUS_MENUNGGU;
        $validated['catatan_admin']    = '';

        PengajuanPtk::create($validated);

        return redirect()
            ->route('pengajuan-ptk.index')
            ->with('success', 'Pengajuan PTK berhasil diajukan.');
    }

    // ── Show ─────────────────────────────────────────────────

    public function show(PengajuanPtk $pengajuanPtk)
    {
        $this->authorizeAccess($pengajuanPtk);

        $pengajuanPtk->load(['kategori', 'jabatan', 'golongan', 'bidang', 'operator', 'diprosesOleh']);

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

        $request->validate([
            'status'        => 'required|in:proses,disetujui,ditolak',
            'catatan_admin' => 'nullable|required_if:status,ditolak|string|max:500',
        ]);

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
