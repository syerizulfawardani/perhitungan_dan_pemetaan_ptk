<?php

namespace App\Http\Controllers;

use App\Models\DataPTK;
use App\Models\Kecamatan;
use App\Models\KategoriPTK;
use App\Models\PengajuanPtk;
use App\Models\Sekolah;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $isAdmin    = Auth::user()->hasRole('admin');
        $operatorId = Auth::id();

        // Query dasar pengajuan — admin lihat semua, operator hanya miliknya
        $pengajuanQuery = $isAdmin
            ? PengajuanPtk::query()
            : PengajuanPtk::where('operator_id', $operatorId);

        if ($isAdmin) {
            $totalPtk       = DataPTK::count();
            $totalSekolah   = Sekolah::count();
            $totalKecamatan = Kecamatan::count();
            $ptkPerKategori = KategoriPTK::withCount('data_ptk')
                ->having('data_ptk_count', '>', 0)
                ->orderByDesc('data_ptk_count')
                ->get();
        } else {
            $mySekolah      = Sekolah::where('operator_id', $operatorId)->pluck('id');
            $totalPtk       = DataPTK::whereIn('sekolah_id', $mySekolah)->count();
            $totalSekolah   = $mySekolah->count();
            $totalKecamatan = Kecamatan::whereHas('sekolah', fn($q) => $q->whereIn('id', $mySekolah))->count();
            $ptkPerKategori = KategoriPTK::withCount(['data_ptk' => fn($q) => $q->whereIn('sekolah_id', $mySekolah)])
                ->having('data_ptk_count', '>', 0)
                ->orderByDesc('data_ptk_count')
                ->get();
        }

        $totalPengajuan    = (clone $pengajuanQuery)->count();
        $pengajuanMenunggu = (clone $pengajuanQuery)->where('status', 'menunggu')->count();

        // Chart 12 bulan terakhir
        $pengajuanPerBulan = [];
        for ($i = 11; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $base = (clone $pengajuanQuery)
                ->whereYear('created_at', $date->year)
                ->whereMonth('created_at', $date->month);

            $pengajuanPerBulan[] = [
                'bulan'     => $date->translatedFormat('M Y'),
                'menunggu'  => (clone $base)->where('status', 'menunggu')->count(),
                'proses'    => (clone $base)->where('status', 'proses')->count(),
                'disetujui' => (clone $base)->where('status', 'disetujui')->count(),
                'ditolak'   => (clone $base)->where('status', 'ditolak')->count(),
            ];
        }

        $pengajuanTerbaru = (clone $pengajuanQuery)
            ->with(['kategori', 'operator'])
            ->latest()
            ->take(6)
            ->get();

        return view('dashboard.index', compact(
            'totalPtk',
            'totalSekolah',
            'totalPengajuan',
            'pengajuanMenunggu',
            'totalKecamatan',
            'ptkPerKategori',
            'pengajuanPerBulan',
            'pengajuanTerbaru',
        ));
    }

    public function petaPtk()
    {
        $ptkPerKecamatan = DB::table('kecamatan')
            ->leftJoin('data_ptk', 'data_ptk.kecamatan_id', '=', 'kecamatan.id')
            ->groupBy('kecamatan.id', 'kecamatan.nama_kecamatan')
            ->select('kecamatan.nama_kecamatan', DB::raw('COUNT(data_ptk.id) as total_ptk'))
            ->get()
            ->mapWithKeys(fn($row) => [$row->nama_kecamatan => (int) $row->total_ptk]);

        return view('dashboard.peta-ptk.index', compact('ptkPerKecamatan'));
    }
}
