<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Institusi extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'institusi';

    protected $fillable = [
        'nama',
        'jenis',
        'alamat',
        'kecamatan',
        'status',
    ];

    public function jurusans(): HasMany
    {
        return $this->hasMany(Jurusan::class);
    }

    public function fakultas(): HasMany
    {
        return $this->hasMany(Fakultas::class);
    }

    public function profiles(): HasMany
    {
        return $this->hasMany(Profile::class);
    }
}
