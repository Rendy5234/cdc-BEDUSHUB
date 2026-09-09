<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

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
        return $this->belongsTo(Institusi::class);
    }

    public function jurusan(): BelongsTo
    {
        return $this->belongsTo(Jurusan::class);
    }

    public function prodi(): BelongsTo
    {
        return $this->belongsTo(ProgramStudi::class, 'prodi_id');
    }
}
