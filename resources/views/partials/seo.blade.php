@php
    $seoTitle = $title;
    $seoDescription = $description ?? __('seo.default_description');
    $seoCanonical = $canonical ?? url()->current();
    $seoNoindex = $noindex ?? false;
    $seoOgType = $ogType ?? 'website';
    $seoJsonLdWebsite = $jsonLdWebsite ?? false;
    $seoNofollow = $nofollow ?? false;

    $ogImageConfig = config('seo.og_image');
    $seoOgImage = null;
    if ($ogImageConfig) {
        $seoOgImage = str_starts_with($ogImageConfig, 'http://') || str_starts_with($ogImageConfig, 'https://')
            ? $ogImageConfig
            : rtrim(config('app.url'), '/') . '/' . ltrim($ogImageConfig, '/');
    }
@endphp

    <title>{{ $seoTitle }}</title>
    <meta name="description" content="{{ $seoDescription }}">
    <link rel="canonical" href="{{ $seoCanonical }}">

@if (! app()->environment('production'))
    <meta name="robots" content="noindex, nofollow">
@elseif ($seoNoindex)
    <meta name="robots" content="noindex{{ $seoNofollow ? ', nofollow' : '' }}">
@endif

    <meta property="og:type" content="{{ $seoOgType }}">
    <meta property="og:title" content="{{ $seoTitle }}">
    <meta property="og:description" content="{{ $seoDescription }}">
    <meta property="og:url" content="{{ $seoCanonical }}">
    <meta property="og:site_name" content="{{ config('app.name') }}">

@if ($seoOgImage)
    <meta property="og:image" content="{{ $seoOgImage }}">
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:image" content="{{ $seoOgImage }}">
@else
    <meta name="twitter:card" content="summary">
@endif

    <meta name="twitter:title" content="{{ $seoTitle }}">
    <meta name="twitter:description" content="{{ $seoDescription }}">

@if ($seoJsonLdWebsite)
    <script type="application/ld+json">
{!! json_encode([
    '@context' => 'https://schema.org',
    '@type' => 'WebSite',
    'name' => config('app.name'),
    'url' => rtrim(config('app.url'), '/') . '/',
    'description' => $seoDescription,
], JSON_UNESCAPED_SLASHES | JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT) !!}
    </script>
@endif
