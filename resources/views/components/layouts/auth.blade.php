<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title ?? config('app.name') . ' - Authentication' }}</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />
    @include('partials.favicon')

    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @endif
</head>
<body class="bg-gray-50 dark:bg-zinc-900 text-zinc-900 dark:text-zinc-100 min-h-screen flex flex-col items-center justify-center p-4 antialiased" style="font-family: 'Instrument Sans', ui-sans-serif, system-ui, sans-serif;">

    <div class="w-full max-w-md">
        <div class="text-center mb-8">
            <a href="{{ url('/') }}" class="inline-flex items-center gap-2 text-zinc-900 dark:text-zinc-100 hover:opacity-80 transition-opacity">
                @include('components/logo')
                <span class="text-xl font-semibold tracking-tight">{{ config('app.name') }}</span>
            </a>
        </div>

        {{-- Card --}}
        <div class="bg-white dark:bg-zinc-800 rounded-xl shadow-sm border border-zinc-200 dark:border-zinc-700 p-8">
            {{ $slot }}
        </div>
    </div>

    @stack('scripts')
</body>
</html>
