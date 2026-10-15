<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserInteraction extends Model
{
    protected $fillable = [
        'user_id',
        'item_type',
        'item_id',
        'action',
        'session_id',
        'source',
        'metadata',
        'event_at',
    ];

    protected $casts = [
        'metadata' => 'array',
        'event_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
