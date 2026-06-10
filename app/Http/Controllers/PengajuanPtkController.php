<?php

namespace App\Http\Controllers;

use App\Models\BidangPTK;
use App\Models\GolonganPtk;
use App\Models\JabatanPtk;
use App\Models\KategoriPtk;
use App\Models\PengajuanPtk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PengajuanPtkController extends Controller
{
    // ── Index ────────────────────────────────────────────────

    public function index(Request $request)
    {
        $query = PengajuanPtk::with(['kategori', 'jabatan', 'golongan', 'bidang', 'operator']);

        // Operator hanya lihat miliknya sendiri
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
        $kategoris  = KategoriPtk::orderBy('jenis_kategori')->get();

        // Hitung statistik untuk admin
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
        $kategoris = KategoriPtk::orderBy('jenis_kategori')->get();
        $jabatans  = JabatanPtk::orderBy('nama_jabatan')->get();
        $golongans = GolonganPtk::orderBy('id')->get();
        $bidangs   = BidangPTK::orderBy('nama_bidang_sertifikasi')->get();

        return view('pengajuan_ptk.create', compact('kategoris', 'jabatans', 'golongans', 'bidangs'));
    }

    // ── Store ────────────────────────────────────────────────

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_ptk'           => 'required|string|max:255',
            'kategori_id'        => 'required|exists:kategori_ptk,id',
            'jabatan_id'         => 'required|exists:jabatan_ptk,id',
            'pangkat_golongan_id'=> 'required|exists:golongan_ptk,id',
            'bidang_id'          => 'required|exists:bidang_studi_sertifikasi,id',
            'tmt_pengangkatan'   => 'required|date',
            'alasan_pengajuan'   => 'required|string|min:20',
        ]);

        $validated['operator_id']  = Auth::id();
        $validated['diproses_oleh'] = Auth::id(); // akan diupdate saat admin proses
        $validated['status']        = PengajuanPtk::STATUS_MENUNGGU;
        $validated['catatan_admin'] = '';

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

        return view('pengajuan_ptk.show', compact('pengajuanPtk'));
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

        $kategoris = KategoriPtk::orderBy('jenis_kategori')->get();
        $jabatans  = JabatanPtk::orderBy('nama_jabatan')->get();
        $golongans = GolonganPtk::orderBy('id')->get();
        $bidangs   = BidangPTK::orderBy('nama_bidang_sertifikasi')->get();

        return view('pengajuan_ptk.edit', compact('pengajuanPtk', 'kategoris', 'jabatans', 'golongans', 'bidangs'));
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
            'nama_ptk'           => 'required|string|max:255',
            'kategori_id'        => 'required|exists:kategori_ptk,id',
            'jabatan_id'         => 'required|exists:jabatan_ptk,id',
            'pangkat_golongan_id'=> 'required|exists:golongan_ptk,id',
            'bidang_id'          => 'required|exists:bidang_studi_sertifikasi,id',
            'tmt_pengangkatan'   => 'required|date',
            'alasan_pengajuan'   => 'required|string|min:20',
        ]);

        // Jika sebelumnya ditolak, reset ke menunggu agar admin review ulang
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
