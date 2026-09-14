<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class PelatihanSkill extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'pelatihan_skill';

    protected $fillable = [
        'pelatihan_id',
        'skill_id',
    ];

    public function pelatihan(): BelongsTo
    {
        return $this->belongsTo(Pelatihan::class)->withTrashed();
    }

    public function skill(): BelongsTo
    {
        return $this->belongsTo(Skill::class);
    }
}
