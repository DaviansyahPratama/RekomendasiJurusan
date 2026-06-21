<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('rekomendasi_mata_kuliah', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('mata_kuliah_id')->constrained('mata_kuliah')->cascadeOnDelete();
            $table->decimal('skor', 8, 2);
            $table->boolean('direkomendasikan')->default(false);
            $table->timestamps();
            $table->unique(['user_id', 'mata_kuliah_id'], 'rekomendasi_mk_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('rekomendasi_mata_kuliah');
    }
};
