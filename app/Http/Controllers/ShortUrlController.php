<?php

namespace App\Http\Controllers;

use App\Ai\Agents\ShortCodeCreator;
use App\Http\Requests\StoreShortUrlRequest;
use App\Http\Requests\SuggestShortCodeRequest;
use App\Http\Requests\UpdateShortUrlRequest;
use App\Models\ShortUrl;
use App\Models\ShortUrlClick;
use Endroid\QrCode\Builder\Builder;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\ErrorCorrectionLevel;
use Endroid\QrCode\Writer\PngWriter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;
use Throwable;

class ShortUrlController extends Controller
{
    private const REDIRECT_CACHE_TTL_SECONDS = 300; // 5 min

    private const MAX_SHORT_URLS_PER_DAY = 10;

    public function redirect(Request $request, string $code)
    {
        $shortUrl = Cache::remember(
            $this->redirectCacheKey($code),
            now()->addSeconds(self::REDIRECT_CACHE_TTL_SECONDS),
            fn () => ShortUrl::query()
                ->select(['id', 'short_code', 'original_url'])
                ->where('short_code', $code)
                ->first()
                ?->only(['id', 'short_code', 'original_url'])
        );

        abort_if(! $shortUrl, 404);

        $this->logClickAsync($shortUrl['id'], $request);

        return redirect()->away($shortUrl['original_url'], 302);
    }

    public function suggest(SuggestShortCodeRequest $request)
    {
        $title = trim((string) $request->input('title', ''));
        $originalUrl = trim((string) $request->input('original_url', ''));

        $prompt = collect([
            $title !== '' ? "Title: {$title}" : null,
            $originalUrl !== '' ? "Destination URL: {$originalUrl}" : null,
        ])->filter()->implode("\n");

        try {
            $response = (new ShortCodeCreator)->prompt($prompt);
        } catch (Throwable $exception) {
            Log::warning('Short code suggestion failed.', [
                'error' => $exception->getMessage(),
            ]);

            return response()->json([
                'message' => 'Unable to suggest aliases right now. Please try again.',
            ], 503);
        }

        $suggestions = collect($response['suggestions'] ?? [])
            ->map(fn ($code) => trim((string) $code))
            ->filter(fn ($code) => $this->isValidSuggestedShortCode($code))
            ->unique()
            ->values()
            ->take(5)
            ->all();

        return response()->json(['suggestions' => $suggestions]);
    }

    public function store(StoreShortUrlRequest $request)
    {
        $userId = auth()->id();
        $createdInLast24Hours = ShortUrl::query()
            ->where('user_id', $userId)
            ->where('created_at', '>=', now()->subDay())
            ->count();

        if ($createdInLast24Hours >= self::MAX_SHORT_URLS_PER_DAY) {
            return back()
                ->withErrors([
                    'original_url' => 'You can only create up to '.self::MAX_SHORT_URLS_PER_DAY
                        .' short URLs in a 24-hour period.',
                ], $request->errorBag)
                ->withInput();
        }

        $data = $request->validated();
        $data['user_id'] = $userId;
        $data['short_code'] = $data['short_code'] ?: $this->generateUniqueShortCode();
        $data['original_url'] = ShortUrl::setProtocolIfNotSet($data['original_url']);

        $shortUrl = ShortUrl::create($data);
        Cache::forget($this->redirectCacheKey($shortUrl->short_code));

        return redirect()->route('dashboard')
            ->with('status', 'url-created')
            ->with('created_short_link', $shortUrl->shortLink());
    }

    public function update(UpdateShortUrlRequest $request, ShortUrl $shortUrl)
    {
        $data = $request->validated();
        $data['original_url'] = ShortUrl::setProtocolIfNotSet($data['original_url']);
        $oldShortCode = $shortUrl->short_code;

        $shortUrl->update($data);
        Cache::forget($this->redirectCacheKey($oldShortCode));
        Cache::forget($this->redirectCacheKey($shortUrl->short_code));

        return redirect()->route('dashboard')->with('status', 'url-updated');
    }

    public function qr(ShortUrl $shortUrl)
    {
        abort_if($shortUrl->user_id !== auth()->id(), 403);

        $result = (new Builder(
            writer: new PngWriter,
            validateResult: false,
            data: $shortUrl->shortLink(true),
            encoding: new Encoding('UTF-8'),
            errorCorrectionLevel: ErrorCorrectionLevel::Medium,
            size: 280,
            margin: 10,
        ))->build();

        return response($result->getString(), 200, [
            'Content-Type' => $result->getMimeType(),
            'Content-Disposition' => 'inline; filename="qr-'.$shortUrl->short_code.'.png"',
        ]);
    }

    public function destroy(ShortUrl $shortUrl)
    {
        abort_if($shortUrl->user_id !== auth()->id(), 403);
        $shortCode = $shortUrl->short_code;

        $shortUrl->delete();
        Cache::forget($this->redirectCacheKey($shortCode));

        return redirect()->route('dashboard')->with('status', 'url-deleted');
    }

    private function logClickAsync(int $shortUrlId, Request $request): void
    {
        $headers = $request->headers->all();
        unset($headers['cookie'], $headers['authorization']);

        $clickData = [
            'short_url_id' => $shortUrlId,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'referer' => $request->headers->get('referer'),
            'request_method' => $request->method(),
            'host' => $request->getHost(),
            'path' => $request->path(),
            'query_string' => $request->getQueryString(),
            'accept_language' => $request->headers->get('accept-language'),
            'headers' => $headers,
            'clicked_at' => now(),
        ];

        dispatch(function () use ($shortUrlId, $clickData) {

            try {
                ShortUrl::whereKey($shortUrlId)->increment('clicks');
                ShortUrlClick::create($clickData);
            } catch (Throwable $exception) {
                Log::error('Short URL click tracking failed.', [
                    'short_url_id' => $shortUrlId,
                    'error' => $exception->getMessage(),
                ]);
            }
        })->afterResponse();
    }

    private function generateUniqueShortCode(int $length = 6): string
    {
        do {
            $shortCode = Str::random($length);
        } while (ShortUrl::where('short_code', $shortCode)->exists());

        return $shortCode;
    }

    private function isValidSuggestedShortCode(string $code): bool
    {
        if (! preg_match('/^[A-Za-z0-9_-]{3,20}$/', $code)) {
            return false;
        }

        $query = ShortUrl::query();

        if (DB::connection()->getDriverName() === 'mysql') {
            $query->whereRaw('BINARY short_code = ?', [$code]);
        } else {
            $query->where('short_code', $code);
        }

        if ($query->exists()) {
            return false;
        }

        $routeSegments = collect(Route::getRoutes())
            ->map(fn ($route) => explode('/', ltrim($route->uri(), '/'))[0])
            ->filter(fn ($segment) => $segment && ! str_starts_with($segment, '{'))
            ->unique()
            ->values()
            ->all();

        $reserved = array_unique(array_merge(
            $routeSegments,
            config('short_url.reserved_codes', [])
        ));

        if (in_array($code, $reserved, true)) {
            return false;
        }

        $normalized = strtolower($code);
        foreach (config('short_url.reserved_prefixes', []) as $prefix) {
            if (str_starts_with($normalized, strtolower((string) $prefix))) {
                return false;
            }
        }

        return true;
    }

    private function redirectCacheKey(string $code): string
    {
        return 'short-url:redirect:'.$code;
    }
}
