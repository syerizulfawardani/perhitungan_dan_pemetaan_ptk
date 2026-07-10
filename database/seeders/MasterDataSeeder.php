<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class MasterDataSeeder extends Seeder
{
    public function run(): void
    {
        $now = now();

        // 1. Kabupaten
        $this->upsertList('kabupaten', 'nama_kabupaten', ['Ketapang'], $now);
        $kabupatenId = DB::table('kabupaten')->where('nama_kabupaten', 'Ketapang')->value('id');

        // 2. Kecamatan (mengikuti kabupaten Ketapang)
        $kecamatan = [
            'Air Upas',
            'Benua Kayong',
            'Delta Pawan',
            'Hulu Sungai',
            'Jelai Hulu',
            'Kendawangan',
            'Manis Mata',
            'Marau',
            'Matan Hilir Selatan',
            'Matan Hilir Utara',
            'Muara Pawan',
            'Nanga Tayap',
            'Pemahan',
            'Sandai',
            'Simpang Dua',
            'Simpang Hulu',
            'Singkup',
            'Sungai Laur',
            'Sungai Melayu Rayak',
            'Tumbang Titi',
        ];
        $hasTsKec = Schema::hasColumn('kecamatan', 'created_at');
        foreach ($kecamatan as $nama) {
            $data = ['kabupaten_id' => $kabupatenId];
            if ($hasTsKec) {
                $data['created_at'] = $now;
                $data['updated_at'] = $now;
            }
            DB::table('kecamatan')->updateOrInsert(['nama_kecamatan' => $nama], $data);
        }

        // 3. Kategori PTK
        $this->upsertList('kategori_ptk', 'jenis_kategori', [
            'Pendidik',
            'Tenaga Kependidikan',
        ], $now);

        // 4. Pangkat / Golongan
        $this->upsertList('golongan_ptk', 'nama_golongan', [
            'I/c',
            'I/d',
            'II/a',
            'II/b',
            'II/c',
            'II/d',
            'III/a',
            'III/b',
            'III/c',
            'III/d',
            'IV',
            'IV/a',
            'IV/b',
            'IV/c',
            'IX',
            'XI',
        ], $now);

        // 5. Jabatan PTK
        $this->upsertList('jabatan_ptk', 'nama_jabatan', [
            'Guru Agama Budha',
            'Guru Agama Hindu',
            'Guru Agama Islam',
            'Guru Agama Katolik',
            'Guru Agama Kristen',
            'Guru Bahasa Indonesia',
            'Guru Bahasa Inggris',
            'Guru Bimbingan Konseling',
            'Guru IPA',
            'Guru IPS',
            'Guru Kelas',
            'Guru Matematika',
            'Guru Muatan Lokal Lainnya',
            'Guru PPKN',
            'Guru Penjasorkes',
            'Guru Prakarya Dan Kewirausahaan',
            'Guru Sejarah',
            'Guru Seni Budaya',
            'Guru Seni Rupa',
            'Guru TIK',
            'Instruktur',
            'Kepala Sekolah',
            'Penjaga Sekolah',
            'Pesuruh/Office Boy',
            'Petugas Keamanan',
            'Pustakawan',
            'Tenaga Administrasi Sekolah',
            'Tidak diisi',
            'Tukang Kebun',
            'Tutor',
        ], $now);

        // 6. Bidang Studi Sertifikasi
        $this->upsertList('bidang_studi_sertifikasi', 'nama_bidang_sertifikasi', [
            'Administrasi Perkantoran',
            'Agribisnis Aneka Ternak',
            'Agribisnis Tanaman Pangan&amp;Hortikultura',
            'Agribisnis Tanaman Perkebunan',
            'Ahli Teknik Informatika dan Komputer',
            'Akuntansi',
            'Akuntansi dan Perbankan',
            'Bahasa Daerah (Jawa, Sunda)',
            'Bahasa Indonesia',
            'Bahasa Indonesia (dan Sastra)',
            'Bahasa Inggris',
            'Bahasa Jepang',
            'Bahasa dan Sastra Indonesia',
            'Bangunan Gedung',
            'Bimbingan dan Konseling',
            'Bimbingan dan Konseling (Konselor)',
            'Biologi',
            'Bisnis dan Manajemen',
            'Broadcasting',
            'Ekonomi',
            'Fisika',
            'Geografi',
            'Guru Kelas PAUD',
            'Guru Kelas SD/MI',
            'Guru Kelas SDLB',
            'Ilmu Pengetahuan Alam (IPA)',
            'Ilmu Pengetahuan Sosial (IPS)',
            'Informatika',
            'Kehutanan',
            'Kependidikan Dasar',
            'Kependidikan Kepelatihan',
            'Kimia',
            'Lainnya',
            'Listrik/Teknik Listrik',
            'Manajemen Perkantoran',
            'Matematika',
            'Nautika Kapal Niaga',
            'Pendidikan Agama Budha',
            'Pendidikan Agama Hindu',
            'Pendidikan Agama Islam',
            'Pendidikan Agama Katholik',
            'Pendidikan Agama Kong hu chu',
            'Pendidikan Agama Kristen',
            'Pendidikan Agama Kristen Protestan',
            'Pendidikan Akuntansi',
            'Pendidikan Anak Prasekolah dan Pendidika',
            'Pendidikan Bahasa Indonesia',
            'Pendidikan Bahasa Inggris',
            'Pendidikan Bahasa dan Sastra Indonesia',
            'Pendidikan Dasar',
            'Pendidikan Ekonomi',
            'Pendidikan Geografi',
            'Pendidikan Ilmu Pengetahuan Alam (IPA)',
            'Pendidikan Ilmu Pengetahuan Sosial (IPS)',
            'Pendidikan Jasmani (OR dan kesehatan)',
            'Pendidikan Jasmani dan Kesehatan',
            'Pendidikan Kewarganegaraan (PKn)',
            'Pendidikan Kewarganegaraan (Pkn)',
            'Pendidikan Kimia',
            'Pendidikan Matematika',
            'Pendidikan Olahraga dan Kesehatan',
            'Pendidikan Pancasila dan Kewarganegaraan',
            'Pendidikan Sejarah',
            'Pendidikan Sejarah Perjuangan Bangsa',
            'Pendidikan Seni',
            'Pendidikan Seni Drama, Tari dan Musik',
            'Pendidikan Seni Rupa',
            'Pengetahuan Alam (IPA terpadu, Fisika)',
            'Pengolahan Hasil Pertanian',
            'Pengolahan Hasil Pertanian Pangan',
            'Pertanian',
            'Rekayasa Perangkat Lunak',
            'Sejarah',
            'Seni Budaya',
            'Sosiologi',
            'Teknik Informatika',
            'Teknik Informatika Komputer',
            'Teknik Instalasi Tenaga Listrik',
            'Teknik Konstruksi',
            'Teknik Tenaga Listrik',
            'Teknologi Informasi dan Komunikasi (TIK)',
            'Usaha Jasa Pariwisata',
            'lainnya',
        ], $now);
    }

    /**
     * Upsert daftar nilai ke sebuah tabel master (idempotent).
     * Timestamps hanya diisi kalau kolomnya ada.
     */
    private function upsertList(string $table, string $column, array $values, $now): void
    {
        $hasTs = Schema::hasColumn($table, 'created_at');
        foreach ($values as $value) {
            $data = $hasTs ? ['created_at' => $now, 'updated_at' => $now] : [];
            DB::table($table)->updateOrInsert([$column => $value], $data);
        }
    }
}
