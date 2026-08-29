<?php

namespace App\Models;

use App\Support\SoftDeleteTombstone;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ShortUrl extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['user_id', 'title', 'original_url', 'short_code'];

    protected static function booted(): void
    {
        static::creating(function (self $model) {
            $model->uuid ??= (string) Str::uuid();
        });

        static::deleting(function (self $shortUrl) {
            if ($shortUrl->isForceDeleting()) {
                return;
            }

            $shortUrl->forceFill([
                'short_code' => SoftDeleteTombstone::value($shortUrl->id, $shortUrl->short_code),
            ])->saveQuietly();
        });
    }

    public function getRouteKeyName(): string
    {
        return 'uuid';
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function clicksLog(): HasMany
    {
        return $this->hasMany(ShortUrlClick::class);
    }

    public function shortLink(bool $withProtocol = false): string
    {
        if ($withProtocol) {
            return url('/'.$this->short_code);
        } else {
            return preg_replace('~^https?://~i', '', url('/'.$this->short_code)) ?? url('/'.$this->short_code);
        }
    }

    public static function setProtocolIfNotSet(string $original_url): string
    {
        if (! (preg_match('~^http?://~i', $original_url) || preg_match('~^https?://~i', $original_url))) {
            $original_url = 'https://'.$original_url;
        }

        return $original_url;
    }

    public static function shortCodeExists(string $shortCode, ?int $excludeId = null): bool
    {
        $query = self::withTrashed();

        if (DB::connection()->getDriverName() === 'mysql') {
            $query->whereRaw('BINARY short_code = ?', [$shortCode]);
        } else {
            $query->where('short_code', $shortCode);
        }

        if ($excludeId !== null) {
            $query->where('id', '!=', $excludeId);
        }

        return $query->exists();
    }
}
