<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    @include('partials.theme-init')
    @include('partials.seo', [
        'title' => __('seo.titles.dashboard', ['app' => config('app.name')]),
        'description' => __('seo.default_description'),
        'noindex' => true,
    ])

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />
    @include('partials.favicon')

    @php
        $dashboardI18n = [
            'suggest' => [
                'label' => __('dashboard.suggest.label'),
                'loading' => __('dashboard.suggest.loading'),
                'missingContext' => __('dashboard.suggest.missing_context'),
                'empty' => __('dashboard.suggest.empty'),
                'unavailable' => __('dashboard.suggest.unavailable'),
            ],
        ];
    @endphp
    <script>
        window.__dashboardI18n = @json($dashboardI18n);
    </script>

    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/css/dashboard.css', 'resources/js/app.js', 'resources/js/dashboard.js'])
    @endif
</head>
<body class="bg-gray-50 dark:bg-zinc-900 text-zinc-900 dark:text-zinc-100 min-h-screen flex flex-col antialiased" style="font-family: 'Instrument Sans', ui-sans-serif, system-ui, sans-serif; --copied-label: '{{ __('ui.clipboard.copied') }}';">

    {{-- Nav --}}
    @include('partials/nav')

    @php
        $mustVerify = auth()->user() instanceof \Illuminate\Contracts\Auth\MustVerifyEmail;
        $isVerified = ! $mustVerify || auth()->user()->hasVerifiedEmail();
    @endphp

    @if (! $isVerified)
        @include('components/modal-block-unverified-email')
    @endif

    {{-- Content --}}
    <main class="flex-1 w-full max-w-5xl mx-auto px-4 sm:px-6 py-12 {{ ! $isVerified ? 'pointer-events-none select-none opacity-60' : '' }}" aria-hidden="{{ ! $isVerified ? 'true' : 'false' }}">

        {{-- Page header --}}
        <div class="mb-8 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h1 class="text-2xl font-semibold text-zinc-900 dark:text-zinc-100">{{ __('dashboard.title') }}</h1>
            </div>
            <button
                type="button"
                onclick="document.getElementById('create-modal').showModal()"
                class="inline-flex items-center justify-center gap-1.5 rounded-lg bg-zinc-900 dark:bg-zinc-100 px-4 py-2 text-sm font-medium text-white dark:text-zinc-900 hover:bg-zinc-700 dark:hover:bg-zinc-300 focus:outline-none focus:ring-2 focus:ring-zinc-900 dark:focus:ring-zinc-100 focus:ring-offset-2 transition-colors cursor-pointer"
            >
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                {{ __('dashboard.new_url') }}
            </button>
        </div>

        {{-- Flash: email verified --}}
        @php
            $user = auth()->user();
            $verifyNoticeUrl = route('verification.notice');
            $previousUrl = url()->previous();
            $cameFromVerifyNotice = is_string($previousUrl)
                && is_string($verifyNoticeUrl)
                && str_starts_with($previousUrl, $verifyNoticeUrl);
        @endphp
        @if ($user && method_exists($user, 'hasVerifiedEmail') && $user->hasVerifiedEmail() && $cameFromVerifyNotice && empty($user->pending_email))
            <div class="mb-6 rounded-xl border border-emerald-200 dark:border-emerald-800 bg-emerald-50 dark:bg-emerald-900/20 px-4 py-3 text-sm text-emerald-700 dark:text-emerald-400">
                {{ __('dashboard.flash.email_verified') }}
            </div>
        @endif

        @if (session('status') === 'verification-link-expired' && $isVerified)
            <div class="mb-6 rounded-xl border border-amber-200 dark:border-amber-800 bg-amber-50 dark:bg-amber-900/20 px-4 py-3 text-sm text-amber-800 dark:text-amber-200">
                 <span class="font-semibold">{{ __('auth.verify.expired_title') }}</span>
                 <br>
                 {{ __('auth.verify.expired_body_1') }}
                 <br>
                 {{ __('auth.verify.expired_body_2') }}
            </div>
        @endif

        {{-- Flash: newly created URL --}}
        @if (session('status') === 'url-created' && session('created_short_link'))
            <div class="mb-6 rounded-xl border border-emerald-200 dark:border-emerald-800 bg-emerald-50 dark:bg-emerald-900/20 p-4">
                <div class="flex items-start gap-3">
                    <svg class="mt-0.5 w-5 h-5 shrink-0 text-emerald-600 dark:text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-medium text-emerald-800 dark:text-emerald-300">{{ __('dashboard.flash.created') }}</p>
                        <div class="mt-2 flex items-center gap-2">
                            <a
                                id="new-link"
                                href="{{ session('created_short_link') }}"
                                target="_blank"
                                class="text-sm font-mono text-emerald-700 dark:text-emerald-400 hover:underline truncate"
                            >{{ session('created_short_link') }}</a>
                            <button
                                type="button"
                                onclick="copyToClipboard('{{ session('created_short_link') }}', this)"
                                class="shrink-0 inline-flex items-center gap-1 rounded-md border border-emerald-300 dark:border-emerald-700 bg-white dark:bg-zinc-800 px-2.5 py-1 text-xs font-medium text-emerald-700 dark:text-emerald-400 hover:bg-emerald-50 dark:hover:bg-emerald-900/30 transition-colors cursor-pointer"
                            >
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z" />
                                </svg>
                                {{ __('ui.actions.copy') }}
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        {{-- Flash: updated --}}
        @if (session('status') === 'url-updated')
            <div class="mb-6 rounded-xl border border-emerald-200 dark:border-emerald-800 bg-emerald-50 dark:bg-emerald-900/20 px-4 py-3 text-sm text-emerald-700 dark:text-emerald-400">
                {{ __('dashboard.flash.updated') }}
            </div>
        @endif

        {{-- Flash: deleted --}}
        @if (session('status') === 'url-deleted')
            <div class="mb-6 rounded-xl border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-800 px-4 py-3 text-sm text-zinc-500 dark:text-zinc-400">
                {{ __('dashboard.flash.deleted') }}
            </div>
        @endif

        <livewire:dashboard-urls />

    </main>

    @include('partials.dialogs-create')
    @include('partials.dialogs-edit')
    @include('partials.dialogs-delete')
    @include('partials.dialogs-qr')

    <script>
        // Re-open create dialog if there are validation errors
        @if ($errors->createUrl->any())
            document.addEventListener('DOMContentLoaded', () => {
                document.getElementById('create-modal').showModal();
            });
        @endif

        // Re-open edit dialog if there are validation errors
        @if ($errors->editUrl->any() && old('_edit_url_uuid'))
            document.addEventListener('DOMContentLoaded', () => {
                document.getElementById('edit-form').action = '/b/short-urls/{{ old('_edit_url_uuid') }}';
                document.getElementById('edit-url-id-field').value = {!! json_encode(old('_edit_url_uuid', '')) !!};
                document.getElementById('edit-title').value = {!! json_encode(old('title', '')) !!};
                document.getElementById('edit-original-url').value = {!! json_encode(old('original_url', '')) !!};
                document.getElementById('edit-short-code').value = {!! json_encode(old('short_code', '')) !!};
                document.getElementById('edit-modal').showModal();
            });
        @endif
    </script>

    @stack('scripts')

</body>
</html>
