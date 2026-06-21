<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Prasyarat extends Model
{
    protected $table = 'prasyarat';

    public $timestamps = false;

    protected $fillable = [
        'mata_kuliah_id',
        'prasyarat_id',
    ];

    public function mataKuliah(): BelongsTo
    {
        return $this->belongsTo(MataKuliah::class, 'mata_kuliah_id');
    }

    public function prasyaratMataKuliah(): BelongsTo
    {
        return $this->belongsTo(MataKuliah::class, 'prasyarat_id');
    }
}
