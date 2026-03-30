<footer class="mt-auto shrink-0 border-t border-zinc-200 dark:border-zinc-700 py-8 bg-white dark:bg-zinc-800/40">
    <div class="text-xs text-zinc-400 dark:text-zinc-500 max-w-5xl mx-auto px-4 sm:px-6 flex flex-col sm:flex-row items-center justify-between gap-3">
        <div class="flex flex-row items-center">
            <a href="{{ url('/') }}" class="site-logo-a flex items-center gap-2 text-sm font-semibold text-zinc-900 dark:text-zinc-100">
                @include('components/logo', ['pathClass' => 'site-logo-path'])
                {{ config('app.name') }}
            </a>
        </div>
            <a href="mailto:contact@lig.re" class="text-zinc-500 hover:text-zinc-700 dark:text-zinc-400 dark:hover:text-zinc-200 underline-offset-2 hover:underline">contact@lig.re</a>
            <span>&copy; {{ date('Y') }} {{ config('app.name') }}</span>

    </div>
</footer>
