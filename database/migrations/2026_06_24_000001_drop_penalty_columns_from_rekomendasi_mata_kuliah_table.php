<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('rekomendasi_mata_kuliah', function (Blueprint $table) {
            $table->dropColumn(['penalty_kesulitan', 'penalty_sks']);
        });
    }

    public function down(): void
    {
        Schema::table('rekomendasi_mata_kuliah', function (Blueprint $table) {
            $table->unsignedTinyInteger('penalty_kesulitan')->default(0)->after('skor_nilai');
            $table->unsignedTinyInteger('penalty_sks')->default(0)->after('penalty_kesulitan');
        });
    }
};
