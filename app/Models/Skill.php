<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Skill extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'skills';

    protected $fillable = [
        'nama',
        'kategori',
    ];

    public function userSkills(): HasMany
    {
        return $this->hasMany(UserSkill::class);
    }

    public function lowonganSkills(): HasMany
    {
        return $this->hasMany(LowonganSkill::class);
    }

    public function pelatihanSkills(): HasMany
    {
        return $this->hasMany(PelatihanSkill::class);
    }

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'user_skills')->withPivot('level')->withTimestamps();
    }

    public function lowongans(): BelongsToMany
    {
        return $this->belongsToMany(Lowongan::class, 'lowongan_skill')->withTimestamps();
    }

    public function pelatihans(): BelongsToMany
    {
        return $this->belongsToMany(Pelatihan::class, 'pelatihan_skill')->withTimestamps();
    }
}
