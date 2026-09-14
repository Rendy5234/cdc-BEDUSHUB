<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;

class Pelatihan extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'pelatihan';

    protected $fillable = [
        'judul',
        'deskripsi',
        'topik',
        'jenis',
        'level',
        'instruktur',
        'tanggal_mulai',
        'tanggal_selesai',
        'jam_mulai',
        'jam_selesai',
        'tempat',
        'kuota',
        'thumbnail',
        'link_materi',
        'link_sertifikat',
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

    public function sisaKuota(): ?int
    {
        if ($this->kuota === null) {
            return null;
        }

        $terisi = $this->pendaftaranPelatihans()
            ->where('status', '!=', 'ditolak')
            ->count();

        return max(0, $this->kuota - $terisi);
    }

    public function skills(): BelongsToMany
    {
        return $this->belongsToMany(Skill::class, 'pelatihan_skill')->withTimestamps();
    }

    public function minat(): BelongsToMany
    {
        return $this->belongsToMany(KategoriMinat::class, 'pelatihan_minat')->withTimestamps();
    }

    protected static function booted(): void
    {
        static::deleting(function (Pelatihan $pelatihan) {
            if ($pelatihan->isForceDeleting()) {
                return;
            }

            $pelatihan->pendaftaranPelatihans->each->delete();
            $pelatihan->pelatihanSkills->each->delete();
        });

        static::restoring(function (Pelatihan $pelatihan) {
            $pelatihan->pendaftaranPelatihans()->onlyTrashed()->get()->each->restore();
            $pelatihan->pelatihanSkills()->onlyTrashed()->get()->each->restore();
        });

        static::forceDeleted(function (Pelatihan $pelatihan) {
            if (filled($pelatihan->thumbnail)) {
                Storage::disk('public')->delete($pelatihan->thumbnail);
            }
        });
    }
}
