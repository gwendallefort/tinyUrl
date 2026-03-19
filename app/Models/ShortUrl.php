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

    public function shortLink(bool $withProtocol = false): string
    {
        if ($withProtocol) {
            return url('/' . $this->short_code);
        } else {
            return ltrim(url('/' . $this->short_code), 'http://');
        }
    }

    public static function setProtocolIfNotSet(string $original_url): string
    {
        if (!(preg_match('~^http?://~i', $original_url) || preg_match('~^https?://~i', $original_url))) {
            $original_url = 'http://' . $original_url;
        }

        return $original_url;
    }
}
