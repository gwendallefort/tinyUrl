<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreShortUrlRequest;
use App\Http\Requests\UpdateShortUrlRequest;
use App\Models\ShortUrl;
use App\Models\ShortUrlClick;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class ShortUrlController extends Controller
{
    public function redirect(Request $request, string $code)
    {
        $shortUrl = ShortUrl::query()
            ->select(['id', 'original_url'])
            ->where('short_code', $code)
            ->firstOrFail();

        $this->logClickAsync($shortUrl->id, $request);

        return redirect($shortUrl->original_url);
    }

    public function store(StoreShortUrlRequest $request)
    {
        $data = $request->validated();
        $data['user_id']      = auth()->id();
        $data['short_code']   = $data['short_code'] ?: $this->generateUniqueShortCode();
        $data['original_url'] = ShortUrl::setProtocolIfNotSet($data['original_url']);

        $shortUrl = ShortUrl::create($data);

        return redirect()->route('home')
            ->with('status', 'url-created')
            ->with('created_short_link', $shortUrl->shortLink());
    }

    public function update(UpdateShortUrlRequest $request, ShortUrl $shortUrl)
    {
        $data = $request->validated();
        $data['original_url'] = ShortUrl::setProtocolIfNotSet($data['original_url']);

        $shortUrl->update($data);

        return redirect()->route('home')->with('status', 'url-updated');
    }

    public function destroy(ShortUrl $shortUrl)
    {
        abort_if($shortUrl->user_id !== auth()->id(), 403);

        $shortUrl->delete();

        return redirect()->route('home')->with('status', 'url-deleted');
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
            } catch (\Throwable $exception) {
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
}
