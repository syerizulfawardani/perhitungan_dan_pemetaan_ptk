<?php

namespace App\Imports;

use App\Models\BidangPTK;
use App\Models\DataPTK;
use App\Models\JabatanPTK;
use App\Models\KategoriPTK;
use App\Models\PangkatPTK;
use App\Models\Sekolah;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use PhpOffice\PhpSpreadsheet\Shared\Date as ExcelDate;

class DataPtkImport implements ToModel, WithHeadingRow, WithValidation, SkipsEmptyRows, SkipsOnFailure
{
    use SkipsFailures;

    protected $sekolahMap;
    protected $sekolahByNpsn;
    protected $sekolahKecamatan;
    protected $kategoriMap;
    protected $jabatanMap;
    protected $bidangMap;
    protected $pangkatMap;
    protected $imported = 0;
    protected $skipped = 0;
    protected $existingPTK = [];

    public function __construct()
    {
        $this->sekolahMap = Sekolah::pluck('id', 'nama_sekolah')
            ->mapWithKeys(fn ($id, $nama) => [Str::lower(trim($nama)) => $id]);

        $this->sekolahByNpsn = Sekolah::pluck('id', 'npsn_sekolah')
            ->mapWithKeys(fn ($id, $npsn) => [trim((string) $npsn) => $id]);

        $this->sekolahKecamatan = Sekolah::pluck('kecamatan_id', 'id');

        $this->kategoriMap = KategoriPTK::pluck('id', 'jenis_kategori')
            ->mapWithKeys(fn ($id, $nama) => [Str::lower(trim($nama)) => $id]);

        $this->jabatanMap = JabatanPTK::pluck('id', 'nama_jabatan')
            ->mapWithKeys(fn ($id, $nama) => [Str::lower(trim($nama)) => $id]);

        $this->bidangMap = BidangPTK::pluck('id', 'nama_bidang_sertifikasi')
            ->mapWithKeys(fn ($id, $nama) => [Str::lower(trim($nama)) => $id]);

        $this->pangkatMap = PangkatPTK::pluck('id', 'nama_golongan')
            ->mapWithKeys(fn ($id, $nama) => [Str::lower(trim($nama)) => $id]);

        $this->existingPTK = DataPTK::select('nama_ptk', 'sekolah_id')
            ->get()
            ->mapWithKeys(function ($item) {
                return [
                    Str::lower(trim($item->nama_ptk)) . '-' . $item->sekolah_id => true
                ];
            });
    }

    public function model(array $row)
    {
        // Cocokkan sekolah: utamakan NPSN (akurat), fallback ke nama
        $npsn        = trim((string) ($row['npsn'] ?? ''));
        $namaSekolah = Str::lower(trim($row['nama_sekolah'] ?? ''));
        $sekolahId   = $this->sekolahByNpsn[$npsn]
            ?? $this->sekolahMap[$namaSekolah]
            ?? null;

        $key = Str::lower(trim($row['nama_ptk'])) . '-' . $sekolahId;

        if (isset($this->existingPTK[$key])) {
            $this->skipped++;
            return null;
        }

        $this->existingPTK[$key] = true;
        $this->imported++;

        return new DataPTK([
            'nama_ptk'            => trim($row['nama_ptk']),
            'sekolah_id'          => $sekolahId,
            'kecamatan_id'        => $sekolahId ? ($this->sekolahKecamatan[$sekolahId] ?? null) : null,
            'kategori_id'         => $this->kategoriMap[Str::lower(trim($row['kategori'] ?? ''))] ?? null,
            'jabatan_id'          => $this->jabatanMap[Str::lower(trim($row['jabatan'] ?? ''))] ?? null,
            'bidang_id'           => $this->bidangMap[Str::lower(trim($row['bidang'] ?? ''))] ?? null,
            'pangkat_golongan_id' => $this->pangkatMap[Str::lower(trim($row['pangkat_golongan'] ?? ''))] ?? null,
            'tmt_pengangkatan'    => $this->parseTanggal($row['tmt_pengangkatan'] ?? null),
        ]);
    }

    public function rules(): array
    {
        return [
            'nama_ptk'         => 'required|string|max:255',
            'kategori'         => 'required',
            'jabatan'          => 'required',
            'bidang'           => 'nullable',
            'pangkat_golongan' => 'nullable',
        ];
    }

    protected function parseTanggal($value)
    {
        if ($value === null || $value === '') {
            return null;
        }

        // Excel menyimpan tanggal sebagai angka serial
        if (is_numeric($value)) {
            return ExcelDate::excelToDateTimeObject($value)->format('Y-m-d');
        }

        try {
            return Carbon::parse($value)->format('Y-m-d');
        } catch (\Exception $e) {
            return null;
        }
    }

    public function getImportedCount(): int
    {
        return $this->imported;
    }

    public function getSkippedCount(): int
    {
        return $this->skipped;
    }
}
