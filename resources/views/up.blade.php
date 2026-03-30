<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    @include('partials.theme-init')
    <title>{{ config('app.name') }} - Health</title>

    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @endif
</head>
<body class="min-h-screen bg-gray-50 dark:bg-zinc-900 text-zinc-900 dark:text-zinc-100 antialiased" style="font-family: 'Instrument Sans', ui-sans-serif, system-ui, sans-serif;">
    <main class="min-h-screen flex items-center justify-center px-4">
        <div class="w-full max-w-xl rounded-2xl border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-800 p-8 shadow-sm">
            <div class="flex items-center gap-3">
                <span class="inline-flex h-3 w-3 rounded-full {{ $exception ? 'bg-red-500' : 'bg-green-500' }}"></span>
                <h1 class="text-2xl font-semibold tracking-tight">
                    {{ $exception ? 'Health check failed' : 'Application is up' }}
                </h1>
            </div>

            <p class="mt-3 text-sm text-zinc-500 dark:text-zinc-400">
                {{ $exception ? 'A health check exception was reported.' : 'All health checks passed successfully.' }}
            </p>
            <p class="mt-2 text-sm text-zinc-500 dark:text-zinc-400">
                Response time: {{ number_format($responseTimeMs, 2) }} ms
            </p>
            <p class="mt-1 text-sm text-zinc-500 dark:text-zinc-400">
                Version: v{{ config('app.version') }}
            </p>

            @if ($exception)
                <div class="mt-6 rounded-lg border border-red-200 dark:border-red-900/60 bg-red-50 dark:bg-red-950/30 p-4">
                    <p class="text-xs font-medium uppercase tracking-wide text-red-700 dark:text-red-300">Exception</p>
                    <p class="mt-2 text-sm text-red-800 dark:text-red-200 break-words">{{ $exception }}</p>
                </div>
            @endif
        </div>
    </main>
</body>
</html>
