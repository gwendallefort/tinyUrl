<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @include('partials.theme-init')

    @include('partials.seo', [
        'title' => config('app.name') . ' - Verify email',
        'description' => config('seo.auth_description'),
        'noindex' => true,
    ])

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />
    @include('partials.favicon')

    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @endif
</head>
<body class="relative bg-gray-50 dark:bg-zinc-900 text-zinc-900 dark:text-zinc-100 min-h-screen antialiased" style="font-family: 'Instrument Sans', ui-sans-serif, system-ui, sans-serif;">

    <div class="fixed top-4 right-4 z-20">
        @include('partials.theme-toggle')
    </div>

    <dialog
        open
        class="m-auto w-full max-w-lg rounded-xl bg-white dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 shadow-xl p-6 backdrop:bg-black/50 open:flex open:flex-col"
    >
        <div class="flex items-center justify-between mb-5">
            <div class="flex items-center gap-2">
                @include('components/logo')
                <h1 class="text-base font-semibold text-zinc-900 dark:text-zinc-100">Verify your email</h1>
            </div>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button
                    type="submit"
                    class="p-1 rounded-md text-zinc-400 hover:text-zinc-600 dark:hover:text-zinc-200 hover:bg-zinc-100 dark:hover:bg-zinc-700 transition-colors cursor-pointer"
                    title="Log out"
                >
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </form>
        </div>

        <p class="text-sm text-zinc-500 dark:text-zinc-400 mb-4">
            We sent a verification link to your email address. Click it to activate your account.
        </p>

        @if (session('status') === 'verification-link-sent')
            <div class="mb-4 rounded-lg bg-emerald-50 dark:bg-emerald-900/20 border border-emerald-200 dark:border-emerald-800 px-4 py-3 text-sm text-emerald-700 dark:text-emerald-400">
                A fresh verification link has been sent.
            </div>
        @endif

        <form method="POST" action="{{ route('verification.send') }}" class="flex items-center gap-3 pt-1">
            @csrf
            <button
                type="submit"
                class="inline-flex items-center rounded-lg bg-zinc-900 dark:bg-zinc-100 px-4 py-2 text-sm font-medium text-white dark:text-zinc-900 hover:bg-zinc-700 dark:hover:bg-zinc-300 focus:outline-none focus:ring-2 focus:ring-zinc-900 dark:focus:ring-zinc-100 focus:ring-offset-2 transition-colors cursor-pointer"
            >
                Resend verification email
            </button>

            <a
                href="{{ route('home') }}"
                class="inline-flex items-center rounded-lg border border-zinc-300 dark:border-zinc-600 px-4 py-2 text-sm font-medium text-zinc-700 dark:text-zinc-300 hover:bg-zinc-50 dark:hover:bg-zinc-700 transition-colors cursor-pointer"
            >
                Back to home
            </a>
        </form>
    </dialog>

</body>
</html>
