<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'TinyUrl') }} &mdash; Shorten your links</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />

    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @endif
</head>
<body class="bg-gray-50 dark:bg-zinc-900 text-zinc-900 dark:text-zinc-100 min-h-screen antialiased" style="font-family: 'Instrument Sans', ui-sans-serif, system-ui, sans-serif;">

    {{-- Header --}}
    <header class="border-b border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-800">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 h-14 flex items-center justify-between">
            <a href="{{ url('/') }}" class="flex items-center gap-2 text-base font-semibold tracking-tight text-zinc-900 dark:text-zinc-100">
                @include('components/logo')
                {{ config('app.name') }}
            </a>

            @if (Route::has('login'))
                <nav class="flex items-center gap-3">
                    @auth
                        <a href="{{ url('/home') }}"
                           class="inline-flex items-center px-4 py-1.5 rounded-lg bg-zinc-900 dark:bg-zinc-100 text-white dark:text-zinc-900 text-sm font-medium hover:bg-zinc-700 dark:hover:bg-white transition-colors">
                            Dashboard
                        </a>
                    @else
                        <a href="{{ route('login') }}"
                           class="text-sm text-zinc-500 dark:text-zinc-400 hover:text-zinc-900 dark:hover:text-zinc-100 transition-colors">
                            Log in
                        </a>
                        @if (Route::has('register'))
                            <a href="{{ route('register') }}"
                               class="inline-flex items-center px-4 py-1.5 rounded-lg bg-zinc-900 dark:bg-zinc-100 text-white dark:text-zinc-900 text-sm font-medium hover:bg-zinc-700 dark:hover:bg-white transition-colors">
                                Get started
                            </a>
                        @endif
                    @endauth
                </nav>
            @endif
        </div>
    </header>

    {{-- Hero --}}
    <section class="py-20 sm:py-28">
        <div class="max-w-2xl mx-auto px-4 sm:px-6 text-center">
            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-zinc-100 dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 text-xs font-medium text-zinc-500 dark:text-zinc-400 mb-6">
                <span class="w-1.5 h-1.5 rounded-full bg-green-500"></span>
                Fast &amp; reliable redirects
            </div>
            <h1 class="text-4xl sm:text-5xl font-semibold tracking-tight leading-tight text-zinc-900 dark:text-zinc-100">
                Short links,<br>big impact
            </h1>
            <p class="mt-5 text-lg text-zinc-500 dark:text-zinc-400 leading-relaxed">
                Turn any long URL into a clean, shareable link in seconds.
                Track your clicks and manage all your links in one place.
            </p>
            <div class="mt-8 flex flex-col sm:flex-row gap-3 justify-center">
                @if (Route::has('register'))
                    <a href="{{ route('register') }}"
                       class="inline-flex items-center justify-center gap-2 px-6 py-2.5 rounded-lg bg-zinc-900 dark:bg-zinc-100 text-white dark:text-zinc-900 text-sm font-medium hover:bg-zinc-700 dark:hover:bg-white transition-colors">
                        Start for free
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                        </svg>
                    </a>
                @endif
                @if (Route::has('login'))
                    <a href="{{ route('login') }}"
                       class="inline-flex items-center justify-center px-6 py-2.5 rounded-lg border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-sm font-medium text-zinc-700 dark:text-zinc-300 hover:bg-zinc-50 dark:hover:bg-zinc-700 transition-colors">
                        Sign in
                    </a>
                @endif
            </div>
        </div>
    </section>

    {{-- URL preview mockup --}}
    <section class="pb-20 sm:pb-28">
        <div class="max-w-2xl mx-auto px-4 sm:px-6">
            <div class="bg-white dark:bg-zinc-800 rounded-2xl border border-zinc-200 dark:border-zinc-700 shadow-sm overflow-hidden">
                <div class="px-4 py-3 border-b border-zinc-100 dark:border-zinc-700 flex items-center gap-2">
                    <span class="w-3 h-3 rounded-full bg-zinc-200 dark:bg-zinc-600"></span>
                    <span class="w-3 h-3 rounded-full bg-zinc-200 dark:bg-zinc-600"></span>
                    <span class="w-3 h-3 rounded-full bg-zinc-200 dark:bg-zinc-600"></span>
                    <span class="ml-2 text-xs text-zinc-400 dark:text-zinc-500 font-mono">{{ request()->getHost() }}</span>
                </div>
                <div class="p-6 sm:p-8">
                    <p class="text-xs font-medium text-zinc-400 dark:text-zinc-500 uppercase tracking-wider mb-3">Your long URL</p>
                    <div class="flex items-center gap-3 p-3 rounded-lg bg-zinc-50 dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-700 font-mono text-sm text-zinc-400 dark:text-zinc-500 overflow-hidden">
                        <svg class="w-4 h-4 shrink-0 text-zinc-300 dark:text-zinc-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/>
                        </svg>
                        <span class="truncate">https://example.com/some/very/long/url/that/is/hard/to/share</span>
                    </div>
                    <div class="flex items-center justify-center my-4">
                        <div class="flex flex-col items-center gap-1">
                            <svg class="w-5 h-5 text-zinc-300 dark:text-zinc-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </div>
                    </div>
                    <p class="text-xs font-medium text-zinc-400 dark:text-zinc-500 uppercase tracking-wider mb-3">Becomes</p>
                    <div class="flex items-center justify-between gap-3 p-3 rounded-lg bg-zinc-900 dark:bg-zinc-100 border border-zinc-900 dark:border-zinc-100">
                        <div class="flex items-center gap-3 font-mono text-sm text-zinc-100 dark:text-zinc-900 overflow-hidden">
                            <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/>
                            </svg>
                            <span class="truncate">{{ request()->getHost() }}/<span class="font-semibold">aB3x9k</span></span>
                        </div>
                        <span class="shrink-0 text-xs text-zinc-400 dark:text-zinc-500 font-medium">Copy</span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- Features --}}
    <section class="py-16 border-t border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-800/40">
        <div class="max-w-5xl mx-auto px-4 sm:px-6">
            <h2 class="text-center text-2xl font-semibold tracking-tight text-zinc-900 dark:text-zinc-100 mb-12">
                Everything you need to share links
            </h2>
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
                <div class="p-6 rounded-xl border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-800">
                    <div class="w-10 h-10 flex items-center justify-center rounded-lg bg-zinc-100 dark:bg-zinc-700 mb-4">
                        <svg class="w-5 h-5 text-zinc-600 dark:text-zinc-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                        </svg>
                    </div>
                    <h3 class="font-semibold text-zinc-900 dark:text-zinc-100 mb-1">Instant shortening</h3>
                    <p class="text-sm text-zinc-500 dark:text-zinc-400 leading-relaxed">Paste a URL and get a short link immediately, no setup required.</p>
                </div>
                <div class="p-6 rounded-xl border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-800">
                    <div class="w-10 h-10 flex items-center justify-center rounded-lg bg-zinc-100 dark:bg-zinc-700 mb-4">
                        <svg class="w-5 h-5 text-zinc-600 dark:text-zinc-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                        </svg>
                    </div>
                    <h3 class="font-semibold text-zinc-900 dark:text-zinc-100 mb-1">Click tracking</h3>
                    <p class="text-sm text-zinc-500 dark:text-zinc-400 leading-relaxed">See how many times your links are clicked and manage them from your dashboard.</p>
                </div>
                <div class="p-6 rounded-xl border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-800">
                    <div class="w-10 h-10 flex items-center justify-center rounded-lg bg-zinc-100 dark:bg-zinc-700 mb-4">
                        <svg class="w-5 h-5 text-zinc-600 dark:text-zinc-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                        </svg>
                    </div>
                    <h3 class="font-semibold text-zinc-900 dark:text-zinc-100 mb-1">Edit anytime</h3>
                    <p class="text-sm text-zinc-500 dark:text-zinc-400 leading-relaxed">Update the destination of any link whenever you need to, without changing the short URL.</p>
                </div>
            </div>
        </div>
    </section>

    {{-- CTA --}}
    <section class="py-20 sm:py-24">
        <div class="max-w-xl mx-auto px-4 sm:px-6 text-center">
            <h2 class="text-2xl sm:text-3xl font-semibold tracking-tight text-zinc-900 dark:text-zinc-100">
                Ready to shorten your first link?
            </h2>
            <p class="mt-3 text-zinc-500 dark:text-zinc-400">
                Create a free account and start managing your links in seconds.
            </p>
            @if (Route::has('register'))
                <a href="{{ route('register') }}"
                   class="mt-6 inline-flex items-center gap-2 px-6 py-2.5 rounded-lg bg-zinc-900 dark:bg-zinc-100 text-white dark:text-zinc-900 text-sm font-medium hover:bg-zinc-700 dark:hover:bg-white transition-colors">
                    Create your account
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                    </svg>
                </a>
            @endif
        </div>
    </section>

    {{-- Footer --}}
    <footer class="border-t border-zinc-200 dark:border-zinc-700 py-8">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 flex flex-col sm:flex-row items-center justify-between gap-3">
            <a href="{{ url('/') }}" class="flex items-center gap-2 text-sm font-semibold text-zinc-900 dark:text-zinc-100">
                @include('components/logo')
                {{ config('app.name') }}
            </a>
            <p class="text-xs text-zinc-400 dark:text-zinc-500">
                &copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.
            </p>
        </div>
    </footer>

</body>
</html>
