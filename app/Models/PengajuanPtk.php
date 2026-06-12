<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PengajuanPtk extends Model
{
    protected $table = 'pengajuan_ptk';

    protected $fillable = [
        'nama_ptk',
        'kategori_id',
        'bidang_id',
        'jabatan_id',
        'pangkat_golongan_id',
        'tmt_pengangkatan',
        'alasan_pengajuan',
        'status',
        'catatan_admin',
        'operator_id',
        'diproses_oleh',
        'diproses_at',
    ];

    protected $casts = [
        'tmt_pengangkatan' => 'date',
        'diproses_at'      => 'datetime',
    ];

    // Konstanta status
    const STATUS_MENUNGGU  = 'menunggu';
    const STATUS_PROSES    = 'proses';
    const STATUS_DISETUJUI = 'disetujui';
    const STATUS_DITOLAK   = 'ditolak';

    public static array $statusConfig = [
        'menunggu'  => ['label' => 'Menunggu',  'class' => 'bg-secondary',  'icon' => 'ti-clock'],
        'proses'    => ['label' => 'Diproses',  'class' => 'bg-warning',    'icon' => 'ti-loader'],
        'disetujui' => ['label' => 'Disetujui', 'class' => 'bg-success',    'icon' => 'ti-circle-check'],
        'ditolak'   => ['label' => 'Ditolak',   'class' => 'bg-danger',     'icon' => 'ti-circle-x'],
    ];

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
