<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Asesmen extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'asesmen';

    protected $fillable = [
        'judul',
        'deskripsi',
        'tipe',
    ];

    public function asesmenSoals(): HasMany
    {
        return $this->hasMany(AsesmenSoal::class);
    }

    public function asesmenJawabans(): HasMany
    {
        return $this->hasMany(AsesmenJawaban::class);
    }
}
