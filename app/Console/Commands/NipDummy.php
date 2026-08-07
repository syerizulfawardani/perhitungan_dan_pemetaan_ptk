<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\DataPTK;

class NipDummy extends Command
{
    protected $signature = 'ptk:generate-nip';

    protected $description = 'Generate NIP untuk seluruh Data PTK yang belum memiliki NIP';

    public function handle()
    {
        $ptks = DataPTK::whereNull('nip')
            ->orWhere('nip', '')
            ->get();

        foreach ($ptks as $ptk) {

            $ptk->nip = $this->generateNip();

            $ptk->save();

            $this->info("{$ptk->nama_ptk} -> {$ptk->nip}");
        }

        $this->info('');
        $this->info("Selesai. {$ptks->count()} NIP berhasil dibuat.");

        return self::SUCCESS;
    }

    private function generateNip(): string
    {
        do {

            $tanggalLahir = fake()->dateTimeBetween(
                '1965-01-01',
                '1998-12-31'
            )->format('Ymd');

            $tahunMasuk = fake()->numberBetween(2000, 2024);

            $bulanMasuk = str_pad(
                fake()->numberBetween(1, 12),
                2,
                '0',
                STR_PAD_LEFT
            );

            $urutan = str_pad(
                fake()->numberBetween(1, 9999),
                4,
                '0',
                STR_PAD_LEFT
            );

            $nip = $tanggalLahir .
                   $tahunMasuk .
                   $bulanMasuk .
                   '01' .
                   $urutan;

        } while (DataPTK::where('nip', $nip)->exists());

        return $nip;
    }
}
