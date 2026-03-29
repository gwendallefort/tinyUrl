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

    <form
        id="forgot-password-form"
        method="POST"
        action="{{ route('password.email') }}"
        class="space-y-5"
        data-loading-submit-form
        data-loading-submit-button="forgot-password-submit"
    >
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

        <x-loading-submit-button
            buttonId="forgot-password-submit"
            label="Send reset link"
            loadingLabel="Sending..."
            class="w-full py-2.5 dark:hover:bg-white"
        />
    </form>

    <p class="mt-6 text-center text-sm text-zinc-500 dark:text-zinc-400">
        Remembered it?
        <a href="{{ route('login') }}" class="font-medium text-zinc-900 dark:text-zinc-100 hover:underline underline-offset-4 transition-colors">
            Back to log in
        </a>
    </p>
</x-layouts.auth>
