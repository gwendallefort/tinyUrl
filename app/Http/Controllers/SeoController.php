<?php

namespace App\Http\Controllers;

class SeoController extends Controller
{
    public function robots()
    {
        $sitemap = route('sitemap');
        $body = "User-agent: *\nAllow: /\n\nSitemap: {$sitemap}\n";

        return response($body, 200, [
            'Content-Type' => 'text/plain; charset=UTF-8',
        ]);
    }

    public function sitemap()
    {
        $urls = [url('/')];

        return response()
            ->view('seo.sitemap', ['urls' => $urls], 200)
            ->header('Content-Type', 'application/xml; charset=UTF-8');
    }
}
