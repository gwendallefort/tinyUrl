<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    @include('partials.theme-init')
    <title>{{ config('app.name') }} - Health</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet" />

    <!-- Styles -->
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>

    <style type="text/tailwindcss">
        @custom-variant dark (&:where(.dark, .dark *));

        @theme {
            --font-sans: 'Figtree', 'ui-sans-serif', 'system-ui', 'sans-serif', "Apple Color Emoji", "Segoe UI Emoji";
        }
    </style>
</head>
<body class="antialiased bg-gray-100 text-gray-900 dark:bg-gray-950 dark:text-gray-100 transition-colors">
<div class="relative flex justify-center items-center min-h-screen bg-gray-100 dark:bg-gray-950 selection:bg-red-500 selection:text-white transition-colors">
    <div class="w-full sm:w-3/4 xl:w-1/2 mx-auto p-6">
        <div class="px-6 py-4 bg-white dark:bg-gray-900 dark:border dark:border-gray-800 rounded-lg shadow-2xl shadow-gray-500/20 dark:shadow-black/30 flex items-center focus:outline focus:outline-2 focus:outline-red-500 transition-colors">
            <div class="relative flex h-3 w-3 group {{ $exception ? 'status-down' : null }}">
                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-green-400 group-[.status-down]:bg-red-600 opacity-75"></span>
                <span class="relative inline-flex rounded-full h-3 w-3 bg-green-400 group-[.status-down]:bg-red-600"></span>
            </div>

            <div class="ml-6">
                <h2 class="text-xl font-semibold text-gray-900 dark:text-gray-100">Application {{ $exception ? 'experiencing problems' : 'up' }}</h2>

                <p class="mt-2 text-gray-500 dark:text-gray-400 text-sm leading-relaxed">
                    HTTP request received.

                    @if (defined('LARAVEL_START'))
                        Response rendered in {{ round((microtime(true) - LARAVEL_START) * 1000) }}ms.
                    @endif
                </p>

                <p class="mt-2 text-gray-500 dark:text-gray-400 text-sm leading-relaxed">
                    Version: v{{ config('app.version') }}
                </p>
            </div>
        </div>
    </div>
</div>
</body>
</html>
