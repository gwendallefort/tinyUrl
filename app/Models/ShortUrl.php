<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ShortUrl extends Model
{
    protected $fillable = ['user_id', 'title', 'original_url', 'short_code'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function shortLink(): string
    {
        return url('/' . $this->short_code);
    }
}
