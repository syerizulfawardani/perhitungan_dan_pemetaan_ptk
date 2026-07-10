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
    protected $sekolahKecamatan;
    protected $kategoriMap;
    protected $jabatanMap;
    protected $bidangMap;
    protected $pangkatMap;

    public function __construct()
    {
        $this->sekolahMap = Sekolah::pluck('id', 'nama_sekolah')
            ->mapWithKeys(fn ($id, $nama) => [Str::lower(trim($nama)) => $id]);

        $this->sekolahKecamatan = Sekolah::pluck('kecamatan_id', 'id');

        $this->kategoriMap = KategoriPTK::pluck('id', 'jenis_kategori')
            ->mapWithKeys(fn ($id, $nama) => [Str::lower(trim($nama)) => $id]);

        $this->jabatanMap = JabatanPTK::pluck('id', 'nama_jabatan')
            ->mapWithKeys(fn ($id, $nama) => [Str::lower(trim($nama)) => $id]);

        $this->bidangMap = BidangPTK::pluck('id', 'nama_bidang_sertifikasi')
            ->mapWithKeys(fn ($id, $nama) => [Str::lower(trim($nama)) => $id]);

        $this->pangkatMap = PangkatPTK::pluck('id', 'nama_golongan')
            ->mapWithKeys(fn ($id, $nama) => [Str::lower(trim($nama)) => $id]);
    }

    public function model(array $row)
    {
        $namaSekolah = Str::lower(trim($row['nama_sekolah'] ?? ''));
        $sekolahId   = $this->sekolahMap[$namaSekolah] ?? null;

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
            'bidang'           => 'required',
            'pangkat_golongan' => 'required',
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
}
