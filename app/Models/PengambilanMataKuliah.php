<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PengambilanMataKuliah extends Model
{
    protected $table = 'pengambilan_mata_kuliah';

    protected $fillable = [
        'user_id',
        'mata_kuliah_id',
        'semester_ambil',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function mataKuliah(): BelongsTo
    {
        return $this->belongsTo(MataKuliah::class);
    }
}
