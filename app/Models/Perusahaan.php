<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

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
}
