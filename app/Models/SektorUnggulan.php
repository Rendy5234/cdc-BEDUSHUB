<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class SektorUnggulan extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'sektor_unggulan';

    protected $fillable = [
        'nama',
        'deskripsi',
        'kategori',
        'kecamatan',
        'ikon',
    ];

    public function potensiDaerahs(): HasMany
    {
        return $this->hasMany(PotensiDaerah::class, 'sektor_id');
    }

    protected static function booted(): void
    {
        static::deleting(function (SektorUnggulan $sektor) {
            if ($sektor->isForceDeleting()) {
                return;
            }

            $sektor->potensiDaerahs->each->delete();
        });

        static::restoring(function (SektorUnggulan $sektor) {
            $sektor->potensiDaerahs()->onlyTrashed()->get()->each->restore();
        });
    }
}
