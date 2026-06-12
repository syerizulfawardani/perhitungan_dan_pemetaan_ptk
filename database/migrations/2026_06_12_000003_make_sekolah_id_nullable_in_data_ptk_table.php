<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('data_ptk', function (Blueprint $table) {
            $table->unsignedBigInteger('sekolah_id')->nullable()->change();
            $table->unsignedBigInteger('pengajuan_ptk_id')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('data_ptk', function (Blueprint $table) {
            $table->unsignedBigInteger('sekolah_id')->nullable(false)->change();
            $table->unsignedBigInteger('pengajuan_ptk_id')->nullable(false)->change();
        });
    }
};
