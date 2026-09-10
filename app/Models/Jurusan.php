<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Jurusan extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'jurusan';

    protected $fillable = [
        'institusi_id',
        'nama',
        'kode',
    ];

    public function institusi(): BelongsTo
    {
        return $this->belongsTo(Institusi::class)->withTrashed();
    }

    public function profiles(): HasMany
    {
        return $this->hasMany(Profile::class);
    }
}
