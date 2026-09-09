<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Pelatihan extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'pelatihan';

    protected $fillable = [
        'judul',
        'deskripsi',
        'topik',
        'level',
        'instruktur',
        'tanggal_mulai',
        'tanggal_selesai',
        'kuota',
        'status',
    ];

    protected $casts = [
        'tanggal_mulai' => 'date',
        'tanggal_selesai' => 'date',
        'kuota' => 'integer',
    ];

    public function pelatihanSkills(): HasMany
    {
        return $this->hasMany(PelatihanSkill::class);
    }

    public function pendaftaranPelatihans(): HasMany
    {
        return $this->hasMany(PendaftaranPelatihan::class);
    }

    public function skills(): BelongsToMany
    {
        return $this->belongsToMany(Skill::class, 'pelatihan_skill')->withTimestamps();
    }
}
