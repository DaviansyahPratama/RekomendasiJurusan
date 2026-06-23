<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RekomendasiMataKuliah extends Model
{
    protected $table = 'rekomendasi_mata_kuliah';

    protected $fillable = [
        'user_id',
        'mata_kuliah_id',
        'skor',
        'skor_minat',
        'skor_nilai',
        'direkomendasikan',
    ];

    protected function casts(): array
    {
        return [
            'skor' => 'decimal:2',
            'direkomendasikan' => 'boolean',
        ];
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
