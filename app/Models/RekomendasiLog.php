<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class RekomendasiLog extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'rekomendasi_log';

    protected $fillable = [
        'user_id',
        'tipe_item',
        'item_id',
        'skor',
        'alasan',
        'aksi',
    ];

    protected $casts = [
        'item_id' => 'integer',
        'skor' => 'float',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
