<?php

namespace App\Http\Requests;

use App\Models\ShortUrl;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;

abstract class ShortUrlRequest extends FormRequest
{
    protected function commonRules(?int $excludeId = null): array
    {
        return [
            'title' => 'nullable|string|max:255',
            'original_url' => [
                'required', 'max:2048', 'regex:/^.+\..{2,}$/',
                function ($attribute, $value, $fail) {
                    $shortCode = $this->input('short_code') ?: $this->route('shortUrl')?->short_code;
                    if (! $shortCode) {
                        return;
                    }
                    $normalized = ShortUrl::setProtocolIfNotSet($value);
                    if (rtrim($normalized, '/') === rtrim(url('/'.$shortCode), '/')) {
                        $fail(trans('validation_short_url.original_url.loop'));
                    }
                },
            ],
            'short_code' => [
                'nullable', 'string', 'min:2', 'max:20', 'alpha_dash',
                // avoid duplicate
                function ($attribute, $value, $fail) use ($excludeId) {
                    $query = ShortUrl::query();

                    if (DB::connection()->getDriverName() === 'mysql') {
                        $query->whereRaw('BINARY short_code = ?', [$value]);
                    } else {
                        $query->where('short_code', $value);
                    }

                    if ($excludeId !== null) {
                        $query->where('id', '!=', $excludeId);
                    }

                    if ($query->exists()) {
                        $fail(trans('validation_short_url.short_code.taken'));
                    }
                },
                // keep some reserved urls
                function ($attribute, $value, $fail) {
                    $routeSegments = collect(Route::getRoutes())
                        ->map(fn ($route) => explode('/', ltrim($route->uri(), '/'))[0])
                        ->filter(fn ($segment) => $segment && ! str_starts_with($segment, '{'))
                        ->unique()
                        ->values()
                        ->toArray();

                    $reserved = array_unique(array_merge(
                        $routeSegments,
                        config('short_url.reserved_codes', [])
                    ));

                    if (in_array($value, $reserved)) {
                        $fail(trans('validation_short_url.short_code.taken'));
                    }
                },
            ],
        ];
    }

    public function messages(): array
    {
        return trans('validation_short_url');
    }
}
