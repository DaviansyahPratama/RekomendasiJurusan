<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MataKuliah extends Model
{
    protected $table = 'mata_kuliah';

    protected $fillable = [
        'kode_mk',
        'nama_mk',
        'sks',
        'semester',
        'kategori',
        'tingkat_kesulitan',
        'deskripsi',
    ];

    public function prasyarat(): BelongsToMany
    {
        return $this->belongsToMany(
            MataKuliah::class,
            'prasyarat',
            'mata_kuliah_id',
            'prasyarat_id'
        );
    }

    public function dibutuhkanUntuk(): BelongsToMany
    {
        return $this->belongsToMany(
            MataKuliah::class,
            'prasyarat',
            'prasyarat_id',
            'mata_kuliah_id'
        );
    }

    public function nilaiMahasiswa(): HasMany
    {
        return $this->hasMany(NilaiMahasiswa::class);
    }

    public function pengambilan(): HasMany
    {
        return $this->hasMany(PengambilanMataKuliah::class);
    }

    public function rekomendasi(): HasMany
    {
        return $this->hasMany(RekomendasiMataKuliah::class);
    }
}
