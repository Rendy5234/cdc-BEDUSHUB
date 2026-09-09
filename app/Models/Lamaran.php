<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Lamaran extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'lamaran';

    protected $fillable = [
        'lowongan_id',
        'user_id',
        'status',
        'cv',
        'catatan',
    ];

    public function lowongan(): BelongsTo
    {
        return $this->belongsTo(Lowongan::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
