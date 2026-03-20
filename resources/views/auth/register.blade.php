<x-layouts.auth>
    <x-slot name="title">{{ config('app.name') }} - Create account</x-slot>

    <h1 class="text-2xl font-semibold text-zinc-900 dark:text-zinc-100 mb-1">Create an account</h1>
    <p class="text-sm text-zinc-500 dark:text-zinc-400 mb-6">Start shortening your URLs today</p>

    <form method="POST" action="{{ route('register') }}" class="space-y-5">
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

        {{-- Password --}}
        <div>
            <label for="password" class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1.5">
                Password
            </label>
            <x-password-input
                id="password"
                name="password"
                autocomplete="new-password"
                placeholder="At least 8 characters"
                :hasError="$errors->has('password')"
                inputClass="px-3.5 py-2.5"
            />
            @error('password')
                <p class="mt-1.5 text-xs text-red-600 dark:text-red-400">{{ $message }}</p>
            @enderror
        </div>

        {{-- Confirm Password --}}
        <div>
            <label for="password_confirmation" class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1.5">
                Confirm password
            </label>
            <x-password-input
                id="password_confirmation"
                name="password_confirmation"
                autocomplete="new-password"
                :hasError="$errors->has('password_confirmation')"
                inputClass="px-3.5 py-2.5"
            />
            @error('password_confirmation')
                <p class="mt-1.5 text-xs text-red-600 dark:text-red-400">{{ $message }}</p>
            @enderror
        </div>

        {{-- Submit --}}
        <button
            type="submit"
            class="w-full px-4 py-2.5 text-sm font-medium text-white bg-zinc-900 dark:bg-zinc-100 dark:text-zinc-900 rounded-lg hover:bg-zinc-700 dark:hover:bg-white transition-colors cursor-pointer"
        >
            Create account
        </button>
    </form>

    <p class="mt-6 text-center text-sm text-zinc-500 dark:text-zinc-400">
        Already have an account?
        <a href="{{ route('login') }}" class="font-medium text-zinc-900 dark:text-zinc-100 hover:underline underline-offset-4 transition-colors">
            Log in
        </a>
    </p>
</x-layouts.auth>
