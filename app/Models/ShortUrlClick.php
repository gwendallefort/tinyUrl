<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ShortUrlClick extends Model
{
    protected $guarded = [];

    protected $casts = [
        'headers' => 'array',
        'clicked_at' => 'datetime',
    ];

    public function shortUrl(): BelongsTo
    {
        return $this->belongsTo(ShortUrl::class);
    }
}
