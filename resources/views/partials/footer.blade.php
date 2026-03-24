<footer class="mt-auto shrink-0 border-t border-zinc-200 dark:border-zinc-700 py-8">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 flex flex-col sm:flex-row items-center justify-between gap-3">
        <div class="flex flex-row items-center">
            <a href="{{ url('/') }}" class="flex items-center gap-2 text-sm font-semibold text-zinc-900 dark:text-zinc-100">
                @include('components/logo')
                {{ config('app.name') }}
            </a>
        </div>
        <p class="text-xs text-zinc-400 dark:text-zinc-500 text-center sm:text-right flex flex-wrap items-center justify-center sm:justify-end gap-x-2 gap-y-1">
            <span>&copy; {{ date('Y') }} {{ config('app.name') }}</span>
            <span class="text-zinc-300 dark:text-zinc-600" aria-hidden="true">·</span>
            <span>v{{ config('app.version') }}</span>
            <span class="text-zinc-300 dark:text-zinc-600" aria-hidden="true">·</span>
            <a href="mailto:contact@lig.re" class="text-zinc-500 hover:text-zinc-700 dark:text-zinc-400 dark:hover:text-zinc-200 underline-offset-2 hover:underline">contact@lig.re</a>
        </p>
    </div>
</footer>
