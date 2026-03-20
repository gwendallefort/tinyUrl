<x-layouts.auth>
    <x-slot name="title">{{ config('app.name') }} - Reset password</x-slot>

    <h1 class="text-2xl font-semibold text-zinc-900 dark:text-zinc-100 mb-1">Forgot your password?</h1>
    <p class="text-sm text-zinc-500 dark:text-zinc-400 mb-6">
        No problem. Enter your email and we'll send you a reset link.
    </p>

    @if (session('status'))
        <div class="mb-5 p-3 bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-lg text-sm text-green-700 dark:text-green-400">
            {{ session('status') }}
        </div>
    @endif

    <form id="forgot-password-form" method="POST" action="{{ route('password.email') }}" class="space-y-5">
        @csrf

        {{-- Email --}}
        <div>
            <label for="email" class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1.5">
                Email address
            </label>
            <input
                id="email"
                type="email"
                name="email"
                value="{{ old('email') }}"
                required
                autofocus
                autocomplete="username"
                placeholder="you@example.com"
                class="w-full px-3.5 py-2.5 text-sm bg-white dark:bg-zinc-900 border rounded-lg outline-none transition-colors
                    {{ $errors->has('email') ? 'border-red-400 dark:border-red-600' : 'border-zinc-300 dark:border-zinc-600 focus:border-zinc-500 dark:focus:border-zinc-400' }}
                    text-zinc-900 dark:text-zinc-100 placeholder-zinc-400 dark:placeholder-zinc-500"
            >
            @error('email')
                <p class="mt-1.5 text-xs text-red-600 dark:text-red-400">{{ $message }}</p>
            @enderror
        </div>

        {{-- Submit --}}
        <button
            id="forgot-password-submit"
            type="submit"
            class="w-full px-4 py-2.5 text-sm font-medium text-white bg-zinc-900 dark:bg-zinc-100 dark:text-zinc-900 rounded-lg hover:bg-zinc-700 dark:hover:bg-white transition-colors cursor-pointer disabled:opacity-60 disabled:cursor-not-allowed"
        >
            <span id="forgot-password-submit-text">Send reset link</span>
            <span id="forgot-password-submit-loading" class="hidden items-center gap-2">
                <svg class="h-4 w-4 animate-spin" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-90" fill="currentColor" d="M4 12a8 8 0 0 1 8-8v4a4 4 0 0 0-4 4H4z"></path>
                </svg>
                Sending...
            </span>
        </button>
    </form>

    <p class="mt-6 text-center text-sm text-zinc-500 dark:text-zinc-400">
        Remembered it?
        <a href="{{ route('login') }}" class="font-medium text-zinc-900 dark:text-zinc-100 hover:underline underline-offset-4 transition-colors">
            Back to log in
        </a>
    </p>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const form = document.getElementById('forgot-password-form');
            const submitButton = document.getElementById('forgot-password-submit');
            const submitText = document.getElementById('forgot-password-submit-text');
            const loadingIndicator = document.getElementById('forgot-password-submit-loading');

            if (!form || !submitButton || !submitText || !loadingIndicator) {
                return;
            }

            form.addEventListener('submit', function () {
                submitButton.disabled = true;
                submitButton.setAttribute('aria-busy', 'true');
                submitText.classList.add('hidden');
                loadingIndicator.classList.remove('hidden');
                loadingIndicator.classList.add('inline-flex');
            });
        });
    </script>
</x-layouts.auth>
