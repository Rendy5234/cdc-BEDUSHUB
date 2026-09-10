<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Fakultas extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'fakultas';

    protected $fillable = [
        'institusi_id',
        'nama',
    ];

    public function institusi(): BelongsTo
    {
        return $this->belongsTo(Institusi::class)->withTrashed();
    }

    public function programStudis(): HasMany
    {
        return $this->hasMany(ProgramStudi::class);
    }

    protected static function booted(): void
    {
        static::deleting(function (Fakultas $fakultas) {
            if ($fakultas->isForceDeleting()) {
                return;
            }

            $fakultas->programStudis->each->delete();
        });

        static::restoring(function (Fakultas $fakultas) {
            $fakultas->programStudis()->onlyTrashed()->get()->each->restore();
        });
    }
}
