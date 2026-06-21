<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('minat_mahasiswa', function (Blueprint $table) {
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('minat_id')->constrained('minat')->cascadeOnDelete();
            $table->primary(['user_id', 'minat_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('minat_mahasiswa');
    }
};
