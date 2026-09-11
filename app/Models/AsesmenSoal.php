<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class AsesmenSoal extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'asesmen_soal';

    protected $fillable = [
        'asesmen_id',
        'skill_id',
        'kategori_minat_id',
        'pertanyaan',
        'tipe_jawaban',
        'opsi',
    ];

    protected $casts = [
        'opsi' => 'array',
    ];

    public function asesmen(): BelongsTo
    {
        return $this->belongsTo(Asesmen::class)->withTrashed();
    }

    public function asesmenJawabans(): HasMany
    {
        return $this->hasMany(AsesmenJawaban::class, 'soal_id');
    }

    public function skill(): BelongsTo
    {
        return $this->belongsTo(Skill::class)->withTrashed();
    }

    public function kategoriMinat(): BelongsTo
    {
        return $this->belongsTo(KategoriMinat::class)->withTrashed();
    }

    protected static function booted(): void
    {
        static::deleting(function (AsesmenSoal $asesmenSoal) {
            if ($asesmenSoal->isForceDeleting()) {
                return;
            }

            $asesmenSoal->asesmenJawabans->each->delete();
        });

        static::restoring(function (AsesmenSoal $asesmenSoal) {
            $asesmenSoal->asesmenJawabans()->onlyTrashed()->get()->each->restore();
        });
    }
}
