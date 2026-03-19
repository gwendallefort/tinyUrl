<?php

namespace App\Http\Controllers;

use App\Models\ShortUrl;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class ShortUrlController extends Controller
{
    public function redirect(string $code)
    {
        $shortUrl = ShortUrl::whereRaw('BINARY short_code = ?', [$code])->firstOrFail();
        $shortUrl->increment('clicks');

        return redirect($shortUrl->original_url);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title'        => 'nullable|string|max:255',
            'original_url' => ['required', 'max:2048'],
            'short_code'   => [
                'nullable', 'string', 'max:20', 'alpha_dash',
                function ($attribute, $value, $fail) {
                    if (ShortUrl::whereRaw('BINARY short_code = ?', [$value])->exists()) {
                        $fail('This alias is already taken. Please choose another.');
                    }
                },
            ],
        ], [
            'short_code.alpha_dash' => 'The custom alias may only contain letters, numbers, dashes, and underscores.',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator, 'createUrl')
                ->withInput();
        }

        $data = $validator->validated();
        $data['user_id']      = auth()->id();
        $data['short_code']   = $data['short_code'] ?: Str::random(6);

        $shortUrl = ShortUrl::create($data);

        return redirect()->route('home')
            ->with('status', 'url-created')
            ->with('created_short_link', $shortUrl->shortLink());
    }

    public function update(Request $request, ShortUrl $shortUrl)
    {
        abort_if($shortUrl->user_id !== auth()->id(), 403);

        $validator = Validator::make($request->all(), [
            'title'        => 'nullable|string|max:255',
            'original_url' => ['required', 'max:2048'],
            'short_code'   => [
                'required', 'string', 'max:20', 'alpha_dash',
                function ($attribute, $value, $fail) use ($shortUrl) {
                    if (ShortUrl::whereRaw('BINARY short_code = ?', [$value])->where('id', '!=', $shortUrl->id)->exists()) {
                        $fail('This alias is already taken. Please choose another.');
                    }
                },
            ],
        ], [
            'short_code.alpha_dash' => 'The alias may only contain letters, numbers, dashes, and underscores.',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator, 'editUrl')
                ->withInput()
                ->with('edit_id', $shortUrl->id);
        }

        $data = $validator->validated();

        $shortUrl->update($data);

        return redirect()->route('home')->with('status', 'url-updated');
    }

    public function destroy(ShortUrl $shortUrl)
    {
        abort_if($shortUrl->user_id !== auth()->id(), 403);

        $shortUrl->delete();

        return redirect()->route('home')->with('status', 'url-deleted');
    }
}
