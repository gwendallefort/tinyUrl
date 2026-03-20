<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreShortUrlRequest;
use App\Http\Requests\UpdateShortUrlRequest;
use App\Models\ShortUrl;
use Illuminate\Support\Str;

class ShortUrlController extends Controller
{
    public function redirect(string $code)
    {
        $shortUrl = ShortUrl::whereRaw('BINARY short_code = ?', [$code])->firstOrFail();
        $this->acknowledgeClicksAsync($shortUrl);

        return redirect($shortUrl->original_url);
    }

    public function store(StoreShortUrlRequest $request)
    {
        $data = $request->validated();
        $data['user_id']      = auth()->id();
        $data['short_code']   = $data['short_code'] ?: Str::random(6);
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

    private function acknowledgeClicksAsync(ShortUrl $shortUrl): void
    {
        dispatch(fn () => $shortUrl->increment('clicks'))->afterResponse();
    }
}
