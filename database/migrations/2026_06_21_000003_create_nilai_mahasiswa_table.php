<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('nilai_mahasiswa', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('mata_kuliah_id')->constrained('mata_kuliah')->cascadeOnDelete();
            $table->decimal('nilai_angka', 5, 2);
            $table->string('grade', 2);
            $table->unsignedTinyInteger('semester_lulus');
            $table->timestamps();
            $table->unique(['user_id', 'mata_kuliah_id'], 'nilai_mhs_mk_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('nilai_mahasiswa');
    }
};
