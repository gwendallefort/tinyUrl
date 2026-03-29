<x-layouts.auth>
    <x-slot name="title">{{ config('app.name') }} - Log in</x-slot>

    <h1 class="text-2xl font-semibold text-zinc-900 dark:text-zinc-100 mb-1">Welcome back</h1>
    <p class="text-sm text-zinc-500 dark:text-zinc-400 mb-6">Log in to your account to continue</p>

    @if (is_string(session('url.intended')) && str_contains(session('url.intended'), '/email/verify/'))
        <div class="mb-4 p-3 bg-amber-50 dark:bg-amber-900/20 border border-amber-200 dark:border-amber-800 rounded-lg text-sm text-amber-900 dark:text-amber-200">
            You opened a link to verify a <strong>new</strong> email address. Sign in with the email address <strong>already on your account</strong> (not the new one).
        </div>
    @endif

    {{-- Session status (e.g. after password reset) --}}
    @if (session('status'))
        <div class="mb-4 p-3 bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-lg text-sm text-green-700 dark:text-green-400">
            {{ session('status') }}
        </div>
    @endif

    <form method="POST" action="{{ route('login') }}" class="space-y-5">
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
                class="w-full px-3.5 py-2.5 text-sm bg-white dark:bg-zinc-900 border rounded-lg transition-colors
                    {{ $errors->has('email') ? 'border-red-400 dark:border-red-600 focus:border-red-400 dark:focus:border-red-500' : 'border-zinc-300 dark:border-zinc-600 focus:border-zinc-500 dark:focus:border-zinc-400' }}
                    text-zinc-900 dark:text-zinc-100 placeholder-zinc-400 dark:placeholder-zinc-500"
            >
            @error('email')
                <p class="mt-1.5 text-xs text-red-600 dark:text-red-400">{{ $message }}</p>
            @enderror
        </div>

        {{-- Password --}}
        <div>
            <div class="flex items-center justify-between mb-1.5">
                <label for="password" class="block text-sm font-medium text-zinc-700 dark:text-zinc-300">
                    Password
                </label>
                @if (Route::has('password.request'))
                    <a href="{{ route('password.request') }}" class="text-xs text-zinc-500 dark:text-zinc-400 hover:text-zinc-700 dark:hover:text-zinc-200 transition-colors">
                        Forgot password?
                    </a>
                @endif
            </div>
            <x-password-input
                id="password"
                name="password"
                autocomplete="current-password"
                :hasError="$errors->has('password')"
                inputClass="px-3.5 py-2.5"
            />
            @error('password')
                <p class="mt-1.5 text-xs text-red-600 dark:text-red-400">{{ $message }}</p>
            @enderror
        </div>

        {{-- Remember me --}}
        <label for="remember" class="inline-flex items-center gap-3 cursor-pointer select-none group">
            <input
                id="remember"
                type="checkbox"
                name="remember"
                class="size-[1.125rem] shrink-0 rounded-md border-2 border-zinc-300 bg-white shadow-sm transition-colors cursor-pointer
                    accent-zinc-900 hover:border-zinc-400
                    checked:border-zinc-900 checked:bg-white
                    focus:outline-none focus-visible:ring-2 focus-visible:ring-zinc-400/90 dark:focus-visible:ring-zinc-500 focus-visible:ring-offset-2 focus-visible:ring-offset-white dark:focus-visible:ring-offset-zinc-800
                    dark:border-zinc-500 dark:bg-zinc-900 dark:accent-zinc-100 dark:hover:border-zinc-400
                    dark:checked:border-zinc-100 dark:checked:bg-zinc-900"
            >
            <span class="text-sm text-zinc-600 dark:text-zinc-400 leading-snug transition-colors group-hover:text-zinc-800 dark:group-hover:text-zinc-200">
                Remember me
            </span>
        </label>

        {{-- Submit --}}
        <button
            type="submit"
            class="w-full px-4 py-2.5 text-sm font-medium text-white bg-zinc-900 dark:bg-zinc-100 dark:text-zinc-900 rounded-lg hover:bg-zinc-700 dark:hover:bg-white transition-colors cursor-pointer"
        >
            Log in
        </button>
    </form>

    @if (Route::has('register'))
        <p class="mt-6 text-center text-sm text-zinc-500 dark:text-zinc-400">
            Don't have an account?
            <a href="{{ route('register') }}" class="font-medium text-zinc-900 dark:text-zinc-100 hover:underline underline-offset-4 transition-colors">
                Create one
            </a>
        </p>
    @endif
</x-layouts.auth>
