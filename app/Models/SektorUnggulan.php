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
}
