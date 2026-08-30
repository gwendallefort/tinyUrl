<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Symfony\Component\HttpFoundation\Response;

class SetLocale
{
    public const COOKIE = 'locale';

    public const STORAGE_KEY = 'storage-locale';

    /**
     * @param  Closure(Request): Response  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $available = config('app.available_locales', ['en', 'fr']);
        $locale = $request->cookie(self::COOKIE);

        if (! is_string($locale) || ! in_array($locale, $available, true)) {
            $locale = $request->getPreferredLanguage($available) ?? config('app.locale', 'en');
        }

        App::setLocale($locale);

        return $next($request);
    }
}
