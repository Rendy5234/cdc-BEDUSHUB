<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserFeedback extends Model
{
    protected $fillable = [
        'user_id',
        'item_type',
        'item_id',
        'label',
        'rating',
        'comment',
        'event_at',
    ];

    protected $casts = [
        'event_at' => 'datetime',
    ];
}
