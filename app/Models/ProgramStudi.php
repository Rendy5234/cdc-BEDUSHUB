<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProgramStudi extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'program_studi';

    protected $fillable = [
        'fakultas_id',
        'nama',
        'kode',
        'jenjang',
    ];

    public function fakultas(): BelongsTo
    {
        return $this->belongsTo(Fakultas::class)->withTrashed();
    }

    public function profiles(): HasMany
    {
        return $this->hasMany(Profile::class, 'prodi_id');
    }
}
