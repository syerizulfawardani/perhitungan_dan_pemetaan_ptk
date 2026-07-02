<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("SET SESSION sql_mode = ''");

        // Drop FK jabatan dan pangkat_golongan (coba semua kemungkinan nama)
        foreach (['foreign_key_jabatan_id', 'data_ptk_jabatan_id_foreign',
                  'foreign_key_pangkat_golongan_id', 'foreign_key_golongan_id',
                  'data_ptk_pangkat_golongan_id_foreign'] as $fk) {
            try { DB::statement("ALTER TABLE data_ptk DROP FOREIGN KEY `{$fk}`"); } catch (\Throwable $e) {}
        }

        DB::statement('ALTER TABLE data_ptk MODIFY jabatan_id BIGINT UNSIGNED NULL');
        DB::statement('ALTER TABLE data_ptk MODIFY pangkat_golongan_id BIGINT UNSIGNED NULL');
    }

    public function down(): void
    {
        DB::statement("SET SESSION sql_mode = ''");
        DB::statement('ALTER TABLE data_ptk MODIFY jabatan_id BIGINT UNSIGNED NOT NULL');
        DB::statement('ALTER TABLE data_ptk MODIFY pangkat_golongan_id BIGINT UNSIGNED NOT NULL');
    }
};
