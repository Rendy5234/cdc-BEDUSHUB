<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
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
        'benefit',
        'pendidikan',
        'kuota',
        'tanggal_mulai',
        'thumbnail',
    ];

    protected $casts = [
        'gaji_min' => 'integer',
        'gaji_max' => 'integer',
        'tanggal_berakhir' => 'date',
        'kuota' => 'integer',
        'tanggal_mulai' => 'date',
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

    public function minat(): BelongsToMany
    {
        return $this->belongsToMany(KategoriMinat::class, 'lowongan_minat')->withTimestamps();
    }

    /**
     * Lowongan yang boleh tampil ke user:
     * berstatus aktif DAN perusahaannya berstatus kerjasama aktif.
     */
    public function scopeTersediaUntukUser(Builder $query): Builder
    {
        return $query
            ->where('status', 'aktif')
            ->whereHas('perusahaan', fn (Builder $q) => $q->where('status_kerjasama', 'aktif'));
    }
}
