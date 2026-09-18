<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;

class Perusahaan extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'perusahaan';

    protected $fillable = [
        'user_id',
        'nama',
        'bidang_usaha',
        'alamat',
        'kecamatan',
        'no_telp',
        'logo',
        'deskripsi',
        'status_kerjasama',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function lowongans(): HasMany
    {
        return $this->hasMany(Lowongan::class);
    }

    protected static function booted(): void
    {
        static::deleting(function (Perusahaan $perusahaan) {
            if ($perusahaan->isForceDeleting()) {
                $perusahaan->lowongans()->withTrashed()->get()->each->forceDelete();

                return;
            }

            $perusahaan->lowongans->each->delete();
        });

        static::restoring(function (Perusahaan $perusahaan) {
            $perusahaan->lowongans()->onlyTrashed()->get()->each->restore();
        });

        static::updated(function (Perusahaan $perusahaan) {
            $logoLama = $perusahaan->getOriginal('logo');

            if (filled($logoLama) && $logoLama !== $perusahaan->logo) {
                Storage::disk('public')->delete($logoLama);
            }
        });

        static::forceDeleted(function (Perusahaan $perusahaan) {
            if (filled($perusahaan->logo)) {
                Storage::disk('public')->delete($perusahaan->logo);
            }
        });
    }
}
