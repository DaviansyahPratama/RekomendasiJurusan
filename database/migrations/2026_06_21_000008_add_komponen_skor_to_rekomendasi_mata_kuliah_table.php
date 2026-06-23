<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('rekomendasi_mata_kuliah', function (Blueprint $table) {
            $table->unsignedTinyInteger('skor_minat')->default(0)->after('skor');
            $table->unsignedTinyInteger('skor_nilai')->default(0)->after('skor_minat');
        });
    }

    public function down(): void
    {
        Schema::table('rekomendasi_mata_kuliah', function (Blueprint $table) {
            $table->dropColumn(['skor_minat', 'skor_nilai']);
        });
    }
};
