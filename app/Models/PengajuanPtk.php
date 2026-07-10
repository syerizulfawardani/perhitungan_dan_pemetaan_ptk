<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class PengajuanPtk extends Model
{
    protected $table = 'pengajuan_ptk';

    protected $fillable = ['nomor_pengajuan', 'kategori_id', 'bidang_id', 'jabatan_id', 'pangkat_golongan_id', 'tmt_pengangkatan', 'alasan_pengajuan', 'status', 'catatan_admin', 'operator_id', 'diproses_oleh', 'diproses_at'];

    protected $casts = [
        'tmt_pengangkatan' => 'date',
        'diproses_at' => 'datetime',
    ];

    // Konstanta status
    const STATUS_MENUNGGU = 'menunggu';
    const STATUS_PROSES = 'proses';
    const STATUS_DISETUJUI = 'disetujui';
    const STATUS_DITOLAK = 'ditolak';

    public static array $statusConfig = [
        'menunggu' => ['label' => 'Menunggu', 'class' => 'bg-secondary', 'icon' => 'ti-clock'],
        'proses' => ['label' => 'Diproses', 'class' => 'bg-warning', 'icon' => 'ti-loader'],
        'disetujui' => ['label' => 'Disetujui', 'class' => 'bg-success', 'icon' => 'ti-circle-check'],
        'ditolak' => ['label' => 'Ditolak', 'class' => 'bg-danger', 'icon' => 'ti-circle-x'],
    ];

    public static function generateNomor()
    {
        $tahun = date('Y');
        $bulan = date('n');
        $bulanRomawi = self::getBulanRomawi($bulan);
        $prefix = 'PTK';

        return DB::transaction(function () use ($prefix, $tahun, $bulan, $bulanRomawi) {
            // Ambil semua nomor urut yang SEDANG AKTIF di bulan & tahun ini
            $usedNumbers = self::whereYear('created_at', $tahun)->whereMonth('created_at', $bulan)->lockForUpdate()->pluck('nomor_pengajuan')->map(fn($nomor) => (int) explode('/', $nomor)[1])->sort()->values()->toArray();

            // Cari nomor urut terkecil yang belum dipakai
            $nextUrut = 1;
            foreach ($usedNumbers as $usedNumber) {
                if ($nextUrut < $usedNumber) {
                    break; // Ketemu gap, gunakan nomor ini
                }
                $nextUrut = $usedNumber + 1;
            }

            return sprintf('%s/%04d/%s/%s', $prefix, $nextUrut, $bulanRomawi, $tahun);
        });
    }

    private static function getBulanRomawi($bulan)
    {
        $romawi = [
            1 => 'I',
            2 => 'II',
            3 => 'III',
            4 => 'IV',
            5 => 'V',
            6 => 'VI',
            7 => 'VII',
            8 => 'VIII',
            9 => 'IX',
            10 => 'X',
            11 => 'XI',
            12 => 'XII',
        ];

        return $romawi[$bulan];
    }

    /**
     * Preview nomor pengajuan (untuk ditampilkan di form)
     * Nomor sebenarnya akan digenerate ulang saat submit
     */
    public static function previewNomor()
    {
        $tahun = date('Y');
        $bulan = date('n');
        $bulanRomawi = self::getBulanRomawi($bulan);
        $prefix = 'PTK';

        // Cari nomor terakhir tanpa lock (hanya untuk preview)
        $usedNumbers = self::whereYear('created_at', $tahun)
            ->whereMonth('created_at', $bulan)
            ->pluck('nomor_pengajuan')
            ->map(fn($nomor) => (int) explode('/', $nomor)[1])
            ->sort()
            ->values()
            ->toArray();

        $nextUrut = 1;
        foreach ($usedNumbers as $usedNumber) {
            if ($nextUrut < $usedNumber) {
                break;
            }
            $nextUrut = $usedNumber + 1;
        }

        return sprintf('%s/%04d/%s/%s', $prefix, $nextUrut, $bulanRomawi, $tahun);
    }

    // ── Relasi ──────────────────────────────────────────────

    public function kategori()
    {
        return $this->belongsTo(KategoriPTK::class, 'kategori_id');
    }

    public function jabatan()
    {
        return $this->belongsTo(JabatanPTK::class, 'jabatan_id');
    }

    public function golongan()
    {
        return $this->belongsTo(GolonganPTK::class, 'pangkat_golongan_id');
    }

    public function bidang()
    {
        return $this->belongsTo(BidangPTK::class, 'bidang_id');
    }

    public function operator()
    {
        return $this->belongsTo(User::class, 'operator_id');
    }

    public function diprosesOleh()
    {
        return $this->belongsTo(User::class, 'diproses_oleh');
    }

    public function dataPtk()
    {
        return $this->hasOne(DataPTK::class, 'pengajuan_ptk_id');
    }

    // ── Scopes ───────────────────────────────────────────────

    public function scopeMenunggu($query)
    {
        return $query->where('status', self::STATUS_MENUNGGU);
    }

    public function scopeByOperator($query, int $userId)
    {
        return $query->where('operator_id', $userId);
    }
}
