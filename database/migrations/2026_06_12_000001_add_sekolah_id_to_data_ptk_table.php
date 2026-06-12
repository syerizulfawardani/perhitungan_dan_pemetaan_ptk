<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('data_ptk', 'sekolah_id')) {
            Schema::table('data_ptk', function (Blueprint $table) {
                $table->unsignedBigInteger('sekolah_id')->nullable()->after('id');
                $table->foreign('sekolah_id')->references('id')->on('sekolah')->nullOnDelete();
            });
        }
    }

    public function down(): void
    {
        Schema::table('data_ptk', function (Blueprint $table) {
            $table->dropForeign(['sekolah_id']);
            $table->dropColumn('sekolah_id');
        });
    }
};
