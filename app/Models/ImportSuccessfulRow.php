<?php

namespace App\Models;

use Filament\Actions\Imports\Models\Import;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ImportSuccessfulRow extends Model
{
    protected $casts = [
        'data' => 'array',
    ];

    protected $guarded = [];

    public function import(): BelongsTo
    {
        return $this->belongsTo(Import::class);
    }
}
