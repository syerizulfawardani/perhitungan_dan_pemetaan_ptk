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
    // Total keseluruhan (tetap pakai query lama, tidak berubah)
    $ptkPerKecamatan = DB::table('kecamatan')
        ->leftJoin('data_ptk', 'data_ptk.kecamatan_id', '=', 'kecamatan.id')
        ->groupBy('kecamatan.id', 'kecamatan.nama_kecamatan')
        ->select('kecamatan.nama_kecamatan', DB::raw('COUNT(data_ptk.id) as total_ptk'))
        ->get()
        ->mapWithKeys(fn($row) => [$row->nama_kecamatan => (int) $row->total_ptk]);

    // Breakdown per jenjang (PAUD, SD, SMP)
    $ptkPaud = $this->getPtkPerJenjang('PAUD');
    $ptkSd   = $this->getPtkPerJenjang('SD');
    $ptkSmp  = $this->getPtkPerJenjang('SMP');

    // Jumlah sekolah per kecamatan per jenjang (untuk popup peta)
    $sekolahPerKecamatan = DB::table('kecamatan')
        ->leftJoin('sekolah', 'sekolah.kecamatan_id', '=', 'kecamatan.id')
        ->groupBy('kecamatan.id', 'kecamatan.nama_kecamatan')
        ->select(
            'kecamatan.nama_kecamatan',
            DB::raw("SUM(CASE WHEN sekolah.jenjang_sekolah = 'PAUD' THEN 1 ELSE 0 END) as paud"),
            DB::raw("SUM(CASE WHEN sekolah.jenjang_sekolah = 'SD' THEN 1 ELSE 0 END) as sd"),
            DB::raw("SUM(CASE WHEN sekolah.jenjang_sekolah = 'SMP' THEN 1 ELSE 0 END) as smp")
        )
        ->get()
        ->mapWithKeys(fn($row) => [$row->nama_kecamatan => [
            'paud' => (int) $row->paud,
            'sd'   => (int) $row->sd,
            'smp'  => (int) $row->smp,
        ]]);

    return view('dashboard.peta-ptk.index', compact(
        'ptkPerKecamatan', 'ptkPaud', 'ptkSd', 'ptkSmp', 'sekolahPerKecamatan'
    ));
}

    private function getPtkPerJenjang(string $jenjang)
    {
        return DB::table('kecamatan')
            ->leftJoin('sekolah', function ($join) use ($jenjang) {
                $join->on('sekolah.kecamatan_id', '=', 'kecamatan.id')
                    ->where('sekolah.jenjang_sekolah', '=', $jenjang);
            })
            ->leftJoin('data_ptk', 'data_ptk.sekolah_id', '=', 'sekolah.id')
            ->groupBy('kecamatan.id', 'kecamatan.nama_kecamatan')
            ->select('kecamatan.nama_kecamatan', DB::raw('COUNT(data_ptk.id) as total_ptk'))
            ->get()
            ->mapWithKeys(fn($row) => [$row->nama_kecamatan => (int) $row->total_ptk]);
    }
}
