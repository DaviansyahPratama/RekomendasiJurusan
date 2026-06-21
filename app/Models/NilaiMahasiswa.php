<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class NilaiMahasiswa extends Model
{
    protected $table = 'nilai_mahasiswa';

    protected $fillable = [
        'user_id',
        'mata_kuliah_id',
        'nilai_angka',
        'grade',
        'semester_lulus',
    ];

    protected function casts(): array
    {
        return [
            'nilai_angka' => 'decimal:2',
        ];
    }

    public static function hitungGrade(float $nilai): string
    {
        return match (true) {
            $nilai >= 85 => 'A',
            $nilai >= 70 => 'B',
            $nilai >= 60 => 'C',
            $nilai >= 50 => 'D',
            default => 'E',
        };
    }

    public static function bobotGrade(string $grade): int
    {
        return match ($grade) {
            'A' => 30,
            'B' => 20,
            'C' => 10,
            default => 0,
        };
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function mataKuliah(): BelongsTo
    {
        return $this->belongsTo(MataKuliah::class);
    }
}
