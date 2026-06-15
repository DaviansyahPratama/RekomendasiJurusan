<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('recommendations', function (Blueprint $table) {
            $table->id();

            $table->foreignId('student_id')->constrained()->cascadeOnDelete();
            $table->foreignId('major_id')->constrained()->cascadeOnDelete();

            $table->integer('score');
            $table->text('explanation');

            $table->timestamps();

            $table->unique(['student_id', 'major_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('recommendations');
    }
};

