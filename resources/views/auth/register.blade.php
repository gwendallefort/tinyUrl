<x-layouts.auth>
    <x-slot name="title">{{ __('seo.titles.register', ['app' => config('app.name')]) }}</x-slot>

    <h1 class="text-2xl font-semibold text-zinc-900 dark:text-zinc-100 mb-1">{{ __('auth.register.title') }}</h1>
    <p class="text-sm text-zinc-500 dark:text-zinc-400 mb-6">{{ __('auth.register.subtitle') }}</p>

    <form
        method="POST"
        action="{{ route('register') }}"
        class="space-y-5"
        data-loading-submit-form
        data-loading-submit-button="register-submit"
    >
        @csrf

        {{-- Email --}}
        <div>
            <label for="email" class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1.5">
                {{ __('auth.fields.email') }}
            </label>
            <input
                id="email"
                type="email"
                name="email"
                value="{{ old('email') }}"
                required
                autofocus
                autocomplete="username"
                placeholder="{{ __('auth.fields.email_placeholder') }}"
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
                {{ __('auth.fields.password') }}
            </label>
            <x-password-input
                id="password"
                name="password"
                autocomplete="new-password"
                placeholder="{{ __('auth.fields.password_placeholder') }}"
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
                {{ __('auth.fields.password_confirmation') }}
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
        <x-loading-submit-button
            buttonId="register-submit"
            :label="__('auth.register.submit')"
            :loadingLabel="__('auth.register.submitting')"
            class="w-full py-2.5 dark:hover:bg-white"
        />
    </form>

    <p class="mt-6 text-center text-sm text-zinc-500 dark:text-zinc-400">
        {{ __('auth.register.have_account') }}
        <a href="{{ route('login') }}" class="font-medium text-zinc-900 dark:text-zinc-100 hover:underline underline-offset-4 transition-colors">
            {{ __('auth.register.login_link') }}
        </a>
    </p>
</x-layouts.auth>
