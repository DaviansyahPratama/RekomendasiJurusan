<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Minat extends Model
{
    protected $table = 'minat';

    protected $fillable = [
        'nama_minat',
    ];

    public function mahasiswa(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'minat_mahasiswa');
    }
}
