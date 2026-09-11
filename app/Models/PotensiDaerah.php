<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class PotensiDaerah extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'potensi_daerah';

    protected $fillable = [
        'sektor_id',
        'nama',
        'lokasi_kecamatan',
        'deskripsi',
        'peluang_usaha',
        'kebutuhan_skill',
    ];

    protected $casts = [
        'kebutuhan_skill' => 'array',
    ];

    public function sektor(): BelongsTo
    {
        return $this->belongsTo(SektorUnggulan::class, 'sektor_id');
    }

    public function skills(): BelongsToMany
    {
        return $this->belongsToMany(Skill::class, 'potensi_daerah_skill')->withTimestamps();
    }
}
