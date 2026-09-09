<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class AsesmenSoal extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'asesmen_soal';

    protected $fillable = [
        'asesmen_id',
        'pertanyaan',
        'tipe_jawaban',
        'opsi',
    ];

    protected $casts = [
        'opsi' => 'array',
    ];

    public function asesmen(): BelongsTo
    {
        return $this->belongsTo(Asesmen::class);
    }

    public function asesmenJawabans(): HasMany
    {
        return $this->hasMany(AsesmenJawaban::class, 'soal_id');
    }
}
