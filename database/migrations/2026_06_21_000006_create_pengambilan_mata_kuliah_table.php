<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pengambilan_mata_kuliah', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('mata_kuliah_id')->constrained('mata_kuliah')->cascadeOnDelete();
            $table->unsignedTinyInteger('semester_ambil');
            $table->timestamps();
            $table->unique(['user_id', 'mata_kuliah_id', 'semester_ambil'], 'pengambilan_mk_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pengambilan_mata_kuliah');
    }
};
