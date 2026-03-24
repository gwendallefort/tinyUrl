<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    @include('partials.seo', [
        'title' => config('app.name') . ' - 500 Server Error',
        'description' => 'Something went wrong. Please try again later.',
        'noindex' => true,
        'nofollow' => true,
    ])

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />
    @include('partials.favicon')

    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @endif
</head>
<body class="bg-gray-50 dark:bg-zinc-900 text-zinc-900 dark:text-zinc-100 min-h-screen flex flex-col antialiased" style="font-family: 'Instrument Sans', ui-sans-serif, system-ui, sans-serif;">

    <div class="flex-1 w-full flex items-center justify-center p-4">
        <div class="w-full max-w-md text-center">

        {{-- Logo + app name --}}
        <a href="{{ url('/') }}" class="inline-flex items-center gap-2 text-zinc-900 dark:text-zinc-100 hover:opacity-80 transition-opacity mb-10">
            @include('components/logo')
            <span class="text-xl font-semibold tracking-tight">{{ config('app.name') }}</span>
        </a>

        {{-- Card --}}
        <div class="bg-white dark:bg-zinc-800 rounded-xl shadow-sm border border-zinc-200 dark:border-zinc-700 p-10">

            <p class="text-7xl font-semibold text-zinc-200 dark:text-zinc-700 leading-none select-none mb-6">500</p>

            <h1 class="text-xl font-semibold text-zinc-900 dark:text-zinc-100 mb-2">Server error</h1>
            <p class="text-sm text-zinc-500 dark:text-zinc-400 mb-6">
                Something went wrong on our side while processing your request.
            </p>

            <a
                href="{{ url('/') }}"
                class="inline-flex items-center gap-2 bg-zinc-900 dark:bg-zinc-100 text-white dark:text-zinc-900 text-sm font-medium px-5 py-2.5 rounded-lg hover:opacity-90 transition-opacity"
            >
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                    <path fill-rule="evenodd" d="M9.707 14.707a1 1 0 01-1.414 0L3.586 10l4.707-4.707a1 1 0 011.414 1.414L7.414 9H15a1 1 0 010 2H7.414l2.293 2.293a1 1 0 010 1.414z" clip-rule="evenodd" />
                </svg>
                Back to home
            </a>
        </div>
    </div>
    </div>

</body>
</html>
