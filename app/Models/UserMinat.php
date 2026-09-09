<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserMinat extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'user_minat';

    protected $fillable = [
        'user_id',
        'kategori_minat_id',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function kategoriMinat(): BelongsTo
    {
        return $this->belongsTo(KategoriMinat::class);
    }
}
