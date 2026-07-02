<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("SET SESSION sql_mode = ''");

        // Drop semua FK yang berkaitan
        foreach (['foreign_key_sekolah', 'data_ptk_sekolah_id_foreign',
                  'foreign_key_pengajuan_id', 'data_ptk_pengajuan_ptk_id_foreign'] as $fk) {
            try { DB::statement("ALTER TABLE data_ptk DROP FOREIGN KEY `{$fk}`"); } catch (\Throwable $e) {}
        }

        DB::statement('ALTER TABLE data_ptk MODIFY sekolah_id BIGINT UNSIGNED NULL');
        DB::statement('ALTER TABLE data_ptk MODIFY pengajuan_ptk_id BIGINT UNSIGNED NULL');

        // Re-add FK
        if (Schema::hasTable('sekolah')) {
            try { DB::statement('ALTER TABLE data_ptk ADD CONSTRAINT data_ptk_sekolah_id_foreign FOREIGN KEY (sekolah_id) REFERENCES sekolah(id) ON DELETE SET NULL'); } catch (\Throwable $e) {}
        }
        if (Schema::hasTable('pengajuan_ptk')) {
            try { DB::statement('ALTER TABLE data_ptk ADD CONSTRAINT data_ptk_pengajuan_ptk_id_foreign FOREIGN KEY (pengajuan_ptk_id) REFERENCES pengajuan_ptk(id) ON DELETE SET NULL'); } catch (\Throwable $e) {}
        }
    }

    public function down(): void
    {
        DB::statement("SET SESSION sql_mode = ''");

        foreach (['data_ptk_sekolah_id_foreign', 'data_ptk_pengajuan_ptk_id_foreign'] as $fk) {
            try { DB::statement("ALTER TABLE data_ptk DROP FOREIGN KEY `{$fk}`"); } catch (\Throwable $e) {}
        }

        DB::statement('ALTER TABLE data_ptk MODIFY sekolah_id BIGINT UNSIGNED NOT NULL');
        DB::statement('ALTER TABLE data_ptk MODIFY pengajuan_ptk_id BIGINT UNSIGNED NOT NULL');
    }
};
