<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;

class Profile extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'profiles';

    protected $fillable = [
        'user_id',
        'jenis_kelamin',
        'tanggal_lahir',
        'domisili_kecamatan',
        'jenjang',
        'institusi_id',
        'jurusan_id',
        'prodi_id',
        'fakultas_id',
        'tahun_lulus',
        'status',
        'no_telp',
        'foto',
    ];

    protected $casts = [
        'tanggal_lahir' => 'date',
        'tahun_lulus' => 'integer',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function institusi(): BelongsTo
    {
        return $this->belongsTo(Institusi::class)->withTrashed();
    }

    public function jurusan(): BelongsTo
    {
        return $this->belongsTo(Jurusan::class)->withTrashed();
    }

    public function prodi(): BelongsTo
    {
        return $this->belongsTo(ProgramStudi::class, 'prodi_id')->withTrashed();
    }

    public function fakultas(): BelongsTo
    {
        return $this->belongsTo(Fakultas::class)->withTrashed();
    }

    public function userSkills(): HasMany
    {
        return $this->hasMany(UserSkill::class, 'user_id', 'user_id');
    }

    public function userMinat(): HasMany
    {
        return $this->hasMany(UserMinat::class, 'user_id', 'user_id');
    }

    protected static function booted(): void
    {
        static::updated(function (Profile $profile) {
            $fotoLama = $profile->getOriginal('foto');

            if (filled($fotoLama) && $fotoLama !== $profile->foto) {
                Storage::disk('public')->delete($fotoLama);
            }
        });

        static::forceDeleted(function (Profile $profile) {
            if (filled($profile->foto)) {
                Storage::disk('public')->delete($profile->foto);
            }
        });
    }
}
