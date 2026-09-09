<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Lowongan extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'lowongan';

    protected $fillable = [
        'perusahaan_id',
        'judul',
        'deskripsi',
        'kualifikasi',
        'tipe_pekerjaan',
        'lokasi',
        'gaji_min',
        'gaji_max',
        'tanggal_berakhir',
        'status',
    ];

    protected $casts = [
        'gaji_min' => 'integer',
        'gaji_max' => 'integer',
        'tanggal_berakhir' => 'date',
    ];

    public function perusahaan(): BelongsTo
    {
        return $this->belongsTo(Perusahaan::class);
    }

    public function lowonganSkills(): HasMany
    {
        return $this->hasMany(LowonganSkill::class);
    }

    public function lamarans(): HasMany
    {
        return $this->hasMany(Lamaran::class);
    }

    public function skills(): BelongsToMany
    {
        return $this->belongsToMany(Skill::class, 'lowongan_skill')->withPivot('level_min')->withTimestamps();
    }
}
