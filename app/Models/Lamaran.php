<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;

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
        'ijazah',
        'surat_lamaran',
        'pas_foto',
        'dokumen_pendukung',
    ];

    protected $casts = [
        'dokumen_pendukung' => 'array',
    ];

    public function lowongan(): BelongsTo
    {
        return $this->belongsTo(Lowongan::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    protected static function booted(): void
    {
        $fileFields = ['cv', 'ijazah', 'surat_lamaran', 'pas_foto'];

        static::updated(function (Lamaran $lamaran) use ($fileFields): void {
            foreach ($fileFields as $field) {
                $lama = $lamaran->getOriginal($field);

                if (filled($lama) && $lama !== $lamaran->{$field}) {
                    Storage::disk('public')->delete($lama);
                }
            }

            $dokumenLama = $lamaran->getOriginal('dokumen_pendukung');

            if (is_string($dokumenLama)) {
                $dokumenLama = json_decode($dokumenLama, true);
            }

            collect($dokumenLama ?? [])
                ->diff(collect($lamaran->dokumen_pendukung ?? []))
                ->each(fn ($path) => Storage::disk('public')->delete($path));
        });

        static::forceDeleted(function (Lamaran $lamaran) use ($fileFields): void {
            foreach ($fileFields as $field) {
                if (filled($lamaran->{$field})) {
                    Storage::disk('public')->delete($lamaran->{$field});
                }
            }

            collect($lamaran->dokumen_pendukung ?? [])
                ->each(fn ($path) => Storage::disk('public')->delete($path));
        });
    }
}
