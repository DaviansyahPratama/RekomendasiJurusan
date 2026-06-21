<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'nim',
        'role',
        'semester_aktif',
        'ipk',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'ipk' => 'decimal:2',
        ];
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isMahasiswa(): bool
    {
        return $this->role === 'mahasiswa';
    }

    public function nilaiMahasiswa(): HasMany
    {
        return $this->hasMany(NilaiMahasiswa::class);
    }

    public function minat(): BelongsToMany
    {
        return $this->belongsToMany(Minat::class, 'minat_mahasiswa');
    }

    public function pengambilanMataKuliah(): HasMany
    {
        return $this->hasMany(PengambilanMataKuliah::class);
    }

    public function rekomendasiMataKuliah(): HasMany
    {
        return $this->hasMany(RekomendasiMataKuliah::class);
    }

    public function totalSksLulus(): int
    {
        return (int) $this->nilaiMahasiswa()
            ->whereIn('grade', ['A', 'B', 'C'])
            ->join('mata_kuliah', 'nilai_mahasiswa.mata_kuliah_id', '=', 'mata_kuliah.id')
            ->sum('mata_kuliah.sks');
    }
}
