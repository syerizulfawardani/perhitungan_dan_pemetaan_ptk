<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Column already exists from original schema — handled by migration 000003
    }

    public function down(): void
    {
        Schema::table('data_ptk', function (Blueprint $table) {
            $table->dropForeign(['pengajuan_ptk_id']);
            $table->dropColumn('pengajuan_ptk_id');
        });
    }
};
