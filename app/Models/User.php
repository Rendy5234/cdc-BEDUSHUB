<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable implements FilamentUser
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, SoftDeletes;

    public const ROLE_ADMIN = 'admin';
    public const ROLE_PERUSAHAAN = 'perusahaan';
    public const ROLE_SISWA = 'siswa';
    public const ROLE_MAHASISWA = 'mahasiswa';
    public const ROLE_ALUMNI = 'alumni';

    public const ROLES = [
        self::ROLE_ADMIN,
        self::ROLE_PERUSAHAAN,
        self::ROLE_SISWA,
        self::ROLE_MAHASISWA,
        self::ROLE_ALUMNI,
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function canAccessPanel(Panel $panel): bool
    {
        return match ($panel->getId()) {
            'admin' => $this->role === self::ROLE_ADMIN,
            'perusahaan' => $this->role === self::ROLE_PERUSAHAAN,
            'siswa' => in_array($this->role, [self::ROLE_SISWA, self::ROLE_MAHASISWA, self::ROLE_ALUMNI], true),
            default => false,
        };
    }

    public function isAdmin(): bool
    {
        return $this->role === self::ROLE_ADMIN;
    }

    public function isPerusahaan(): bool
    {
        return $this->role === self::ROLE_PERUSAHAAN;
    }

    public function isSiswa(): bool
    {
        return $this->role === self::ROLE_SISWA;
    }

    public function isMahasiswa(): bool
    {
        return $this->role === self::ROLE_MAHASISWA;
    }

    public function isAlumni(): bool
    {
        return $this->role === self::ROLE_ALUMNI;
    }

    public function isStudentOrAlumni(): bool
    {
        return in_array($this->role, [self::ROLE_SISWA, self::ROLE_MAHASISWA, self::ROLE_ALUMNI], true);
    }

    public function profile(): HasOne
    {
        return $this->hasOne(Profile::class);
    }

    public function skills(): BelongsToMany
    {
        return $this->belongsToMany(Skill::class, 'user_skills')->withPivot('level')->withTimestamps();
    }

    public function minat(): BelongsToMany
    {
        return $this->belongsToMany(KategoriMinat::class, 'user_minat')->withTimestamps();
    }

    public function userSkills(): HasMany
    {
        return $this->hasMany(UserSkill::class);
    }

    public function userMinat(): HasMany
    {
        return $this->hasMany(UserMinat::class);
    }

    public function perusahaan(): HasOne
    {
        return $this->hasOne(Perusahaan::class);
    }

    public function lamarans(): HasMany
    {
        return $this->hasMany(Lamaran::class);
    }

    public function pendaftaranPelatihan(): HasMany
    {
        return $this->hasMany(PendaftaranPelatihan::class);
    }

    public function tracerStudies(): HasMany
    {
        return $this->hasMany(TracerStudy::class);
    }

    public function rekomendasiLogs(): HasMany
    {
        return $this->hasMany(RekomendasiLog::class);
    }

    public function notifikasis(): HasMany
    {
        return $this->hasMany(Notifikasi::class);
    }

    public function asesmenJawabans(): HasMany
    {
        return $this->hasMany(AsesmenJawaban::class);
    }

    protected static function booted(): void
    {
        static::deleting(function (User $user) {
            if ($user->isForceDeleting()) {
                return; // FK cascadeOnDelete pada perusahaan.user_id menangani hard delete
            }

            $user->perusahaan?->delete();
        });

        static::restoring(function (User $user) {
            $user->perusahaan()->onlyTrashed()->first()?->restore();
        });
    }
}
