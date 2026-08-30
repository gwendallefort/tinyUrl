<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    @include('partials.theme-init')
    @include('partials.seo', [
        'title' => __('seo.titles.profile', ['app' => config('app.name')]),
        'description' => __('seo.default_description'),
        'noindex' => true,
    ])

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />
    @include('partials.favicon')

    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @endif
</head>
<body class="bg-gray-50 dark:bg-zinc-900 text-zinc-900 dark:text-zinc-100 min-h-screen flex flex-col antialiased" style="font-family: 'Instrument Sans', ui-sans-serif, system-ui, sans-serif;">

    @include('partials/nav')

    {{-- Content --}}
    <main class="flex-1 w-full max-w-2xl mx-auto px-4 sm:px-6 py-12 space-y-6">

        <div class="mb-8">
            <h1 class="text-2xl font-semibold text-zinc-900 dark:text-zinc-100">{{ __('profile.title') }}</h1>
            <p class="mt-1 text-sm text-zinc-500 dark:text-zinc-400">{{ __('profile.subtitle') }}</p>
        </div>

        {{-- Log out --}}
        <div class="bg-white dark:bg-zinc-800 rounded-xl border border-zinc-200 dark:border-zinc-700 p-6">
            <h2 class="text-base font-medium text-zinc-900 dark:text-zinc-100 mb-1">{{ __('profile.logout.title') }}</h2>
            <p class="text-sm text-zinc-500 dark:text-zinc-400 mb-6">{{ __('profile.logout.body') }}</p>

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="inline-flex items-center rounded-lg border border-zinc-300 dark:border-zinc-600 px-4 py-2 text-sm font-medium text-zinc-700 dark:text-zinc-300 hover:bg-zinc-50 dark:hover:bg-zinc-700 focus:outline-none focus:ring-2 focus:ring-zinc-900 dark:focus:ring-zinc-100 focus:ring-offset-2 transition-colors cursor-pointer">
                    {{ __('profile.logout.button') }}
                </button>
            </form>
        </div>

        {{-- Profile information --}}
        <div class="bg-white dark:bg-zinc-800 rounded-xl border border-zinc-200 dark:border-zinc-700 p-6">
            <h2 class="text-base font-medium text-zinc-900 dark:text-zinc-100 mb-1">{{ __('profile.info.title') }}</h2>
            <p class="text-sm text-zinc-500 dark:text-zinc-400 mb-6">{{ __('profile.info.subtitle') }}</p>

            @if (session('status') === 'profile-updated')
                <div class="mb-4 rounded-lg bg-emerald-50 dark:bg-emerald-900/20 border border-emerald-200 dark:border-emerald-800 px-4 py-3 text-sm text-emerald-700 dark:text-emerald-400">
                    {{ __('profile.flash.updated') }}
                </div>
            @endif

            @if (session('status') === 'email-change-pending')
                <div class="mb-4 rounded-lg bg-sky-50 dark:bg-sky-900/20 border border-sky-200 dark:border-sky-800 px-4 py-3 text-sm text-sky-800 dark:text-sky-200">
                    {!! __('profile.flash.email_change_pending', ['email' => auth()->user()->pending_email]) !!}
                </div>
            @endif

            @if (auth()->user()->pending_email && session('status') !== 'email-change-pending')
                <div class="mb-4 rounded-lg bg-amber-50 dark:bg-amber-900/20 border border-amber-200 dark:border-amber-800 px-4 py-3 text-sm text-amber-900 dark:text-amber-200">
                    {!! __('profile.flash.email_change_pending_reminder', ['email' => auth()->user()->pending_email]) !!}
                </div>
            @endif

            <form
                id="profile-information-form"
                method="POST"
                action="{{ route('profile.update-information') }}"
                class="space-y-4"
                data-loading-submit-form
                data-loading-submit-button="profile-information-submit"
            >
                @csrf
                @method('PUT')

                {{-- Email --}}
                <div>
                    <label for="email" class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1.5">{{ __('auth.fields.email') }}</label>
                    <input
                        id="email"
                        name="email"
                        type="email"
                        value="{{ old('email', auth()->user()->email) }}"
                        required
                        autocomplete="email"
                        class="w-full rounded-lg border border-zinc-300 dark:border-zinc-600 bg-white dark:bg-zinc-900 px-3 py-2 text-sm text-zinc-900 dark:text-zinc-100 placeholder-zinc-400 dark:placeholder-zinc-500 focus:outline-none focus:ring-2 focus:ring-zinc-900 dark:focus:ring-zinc-100 focus:border-transparent transition @error('email', 'updateProfileInformation') border-red-400 dark:border-red-500 @enderror"
                    >
                    @error('email', 'updateProfileInformation')
                        <p class="mt-1.5 text-xs text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <div class="pt-1">
                    <x-loading-submit-button
                        buttonId="profile-information-submit"
                        :label="__('profile.info.save')"
                        :loadingLabel="__('profile.info.saving')"
                    />
                </div>
            </form>
        </div>

        {{-- Update password --}}
        <div class="bg-white dark:bg-zinc-800 rounded-xl border border-zinc-200 dark:border-zinc-700 p-6">
            <h2 class="text-base font-medium text-zinc-900 dark:text-zinc-100 mb-1">{{ __('profile.password.title') }}</h2>
            <p class="text-sm text-zinc-500 dark:text-zinc-400 mb-6">{{ __('profile.password.subtitle') }}</p>

            @if (session('status') === 'password-updated')
                <div class="mb-4 rounded-lg bg-emerald-50 dark:bg-emerald-900/20 border border-emerald-200 dark:border-emerald-800 px-4 py-3 text-sm text-emerald-700 dark:text-emerald-400">
                    {{ __('profile.flash.password_updated') }}
                </div>
            @endif

            <form
                method="POST"
                action="{{ route('profile.update-password') }}"
                class="space-y-4"
                data-loading-submit-form
                data-loading-submit-button="update-password-submit"
            >
                @csrf
                @method('PUT')

                {{-- Current password --}}
                <div>
                    <label for="current_password" class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1.5">{{ __('profile.password.current') }}</label>
                    <x-password-input
                        id="current_password"
                        name="current_password"
                        autocomplete="current-password"
                        :hasError="$errors->updatePassword->has('current_password')"
                        inputClass="px-3 py-2 focus:outline-none focus:ring-2 focus:ring-zinc-900 dark:focus:ring-zinc-100 focus:border-transparent transition"
                        normalBorderClass="border-zinc-300 dark:border-zinc-600"
                        errorBorderClass="border-red-400 dark:border-red-500"
                    />
                    @error('current_password', 'updatePassword')
                        <p class="mt-1.5 text-xs text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                {{-- New password --}}
                <div>
                    <label for="password" class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1.5">{{ __('profile.password.new') }}</label>
                    <x-password-input
                        id="password"
                        name="password"
                        autocomplete="new-password"
                        :hasError="$errors->updatePassword->has('password')"
                        inputClass="px-3 py-2 focus:outline-none focus:ring-2 focus:ring-zinc-900 dark:focus:ring-zinc-100 focus:border-transparent transition"
                        normalBorderClass="border-zinc-300 dark:border-zinc-600"
                        errorBorderClass="border-red-400 dark:border-red-500"
                    />
                    @error('password', 'updatePassword')
                        <p class="mt-1.5 text-xs text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Confirm new password --}}
                <div>
                    <label for="password_confirmation" class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1.5">{{ __('profile.password.confirm') }}</label>
                    <x-password-input
                        id="password_confirmation"
                        name="password_confirmation"
                        autocomplete="new-password"
                        inputClass="px-3 py-2 focus:outline-none focus:ring-2 focus:ring-zinc-900 dark:focus:ring-zinc-100 focus:border-transparent transition"
                        normalBorderClass="border-zinc-300 dark:border-zinc-600"
                    />
                </div>

                {{-- Submit --}}
                <x-loading-submit-button
                    buttonId="update-password-submit"
                    :label="__('profile.password.submit')"
                    :loadingLabel="__('profile.password.submitting')"
                    class="py-2.5 dark:hover:bg-white"
                />
            </form>
        </div>

        {{-- Delete account --}}
        <div class="bg-white dark:bg-zinc-800 rounded-xl border border-red-200 dark:border-red-900 p-6">
            <h2 class="text-base font-medium text-red-600 dark:text-red-400 mb-1">{{ __('profile.delete.title') }}</h2>
            <p class="text-sm text-zinc-500 dark:text-zinc-400 mb-6">{{ __('profile.delete.body') }}</p>

            @if ($errors->deleteAccount->any())
                <div class="mb-4 rounded-lg bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 px-4 py-3 text-sm text-red-700 dark:text-red-400">
                    {{ $errors->deleteAccount->first('password') }}
                </div>
            @endif

            <button
                type="button"
                onclick="document.getElementById('delete-modal').showModal()"
                class="inline-flex items-center rounded-lg bg-red-600 px-4 py-2 text-sm font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-600 focus:ring-offset-2 transition-colors cursor-pointer"
            >
                {{ __('profile.delete.button') }}
            </button>

            {{-- Confirmation dialog --}}
            <dialog
                id="delete-modal"
                class="m-auto w-full max-w-md rounded-xl bg-white dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 shadow-xl p-6 backdrop:bg-black/50 open:flex open:flex-col"
            >
                <h3 class="text-base font-semibold text-zinc-900 dark:text-zinc-100 mb-2">{{ __('profile.delete.confirm_title') }}</h3>
                <p class="text-sm text-zinc-500 dark:text-zinc-400 mb-5">
                    {{ __('profile.delete.confirm_body') }}
                </p>

                <form method="POST" action="{{ route('profile.destroy') }}" class="space-y-4">
                    @csrf
                    @method('DELETE')

                    <div>
                        <label for="delete_password" class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1.5">{{ __('auth.fields.password') }}</label>
                        <x-password-input
                            id="delete_password"
                            name="password"
                            autocomplete="current-password"
                            inputClass="px-3 py-2 focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent transition"
                            normalBorderClass="border-zinc-300 dark:border-zinc-600"
                        />
                    </div>

                    <div class="flex items-center gap-3 pt-1">
                        <button
                            type="submit"
                            class="inline-flex items-center rounded-lg bg-red-600 px-4 py-2 text-sm font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-600 focus:ring-offset-2 transition-colors cursor-pointer"
                        >
                            {{ __('profile.delete.confirm_submit') }}
                        </button>
                        <button
                            type="button"
                            onclick="document.getElementById('delete-modal').close()"
                            class="inline-flex items-center rounded-lg border border-zinc-300 dark:border-zinc-600 px-4 py-2 text-sm font-medium text-zinc-700 dark:text-zinc-300 hover:bg-zinc-50 dark:hover:bg-zinc-700 focus:outline-none focus:ring-2 focus:ring-zinc-900 focus:ring-offset-2 transition-colors cursor-pointer"
                        >
                            {{ __('ui.actions.cancel') }}
                        </button>
                    </div>
                </form>
            </dialog>
        </div>

    </main>

    @stack('scripts')
</body>
</html>
