<footer class="mt-auto shrink-0 border-t border-zinc-200 dark:border-zinc-700 py-8">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 flex flex-col sm:flex-row items-center justify-between gap-3">
        <div class="flex flex-row items-center">
            <a href="{{ url('/') }}" class="flex items-center gap-2 text-sm font-semibold text-zinc-900 dark:text-zinc-100">
                @include('components/logo')
                {{ config('app.name') }}
            </a>
        </div>
        <p class="text-xs text-zinc-400 dark:text-zinc-500">
            &copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved. v{{ config('app.version') }}
            {{-- &middot; v{{ config('app.version') }} --}}
        </p>
    </div>
</footer>
