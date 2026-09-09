<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class AsesmenJawaban extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'asesmen_jawaban';

    protected $fillable = [
        'asesmen_id',
        'soal_id',
        'user_id',
        'jawaban',
        'skor',
    ];

    protected $casts = [
        'skor' => 'integer',
    ];

    public function asesmen(): BelongsTo
    {
        return $this->belongsTo(Asesmen::class);
    }

    public function soal(): BelongsTo
    {
        return $this->belongsTo(AsesmenSoal::class, 'soal_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
