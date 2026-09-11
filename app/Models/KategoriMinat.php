<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class KategoriMinat extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'kategori_minat';

    protected $fillable = [
        'nama',
        'deskripsi',
    ];

    public function userMinats(): HasMany
    {
        return $this->hasMany(UserMinat::class);
    }

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'user_minat')->withTimestamps();
    }

    public function skills(): BelongsToMany
    {
        return $this->belongsToMany(Skill::class, 'kategori_minat_skill')->withTimestamps();
    }

    public function lowongans(): BelongsToMany
    {
        return $this->belongsToMany(Lowongan::class, 'lowongan_minat')->withTimestamps();
    }

    public function pelatihans(): BelongsToMany
    {
        return $this->belongsToMany(Pelatihan::class, 'pelatihan_minat')->withTimestamps();
    }
}
