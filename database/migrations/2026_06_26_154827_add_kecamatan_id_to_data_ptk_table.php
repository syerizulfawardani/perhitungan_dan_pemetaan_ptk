<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("SET SESSION sql_mode=''");
        DB::statement("ALTER TABLE data_ptk ADD COLUMN kecamatan_id BIGINT UNSIGNED NULL");
        DB::statement("ALTER TABLE data_ptk ADD CONSTRAINT fk_data_ptk_kecamatan FOREIGN KEY (kecamatan_id) REFERENCES kecamatan(id) ON DELETE SET NULL");
    }

    public function down(): void
    {
        DB::statement("SET SESSION sql_mode=''");
        DB::statement("ALTER TABLE data_ptk DROP FOREIGN KEY fk_data_ptk_kecamatan");
        DB::statement("ALTER TABLE data_ptk DROP COLUMN kecamatan_id");
    }
};
