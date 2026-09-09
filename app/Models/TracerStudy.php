<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class TracerStudy extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'tracer_study';

    protected $fillable = [
        'user_id',
        'status_pekerjaan',
        'nama_perusahaan',
        'posisi',
        'bidang_pekerjaan',
        'penghasilan',
        'relevansi_pekerjaan',
        'kepuasan_pendidikan',
    ];

    protected $casts = [
        'relevansi_pekerjaan' => 'integer',
        'kepuasan_pendidikan' => 'integer',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
