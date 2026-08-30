{{-- Blocks dashboard interaction but keeps nav clickable --}}
<div class="fixed left-0 right-0 bottom-0 top-14 z-50 bg-black/40 backdrop-blur-[1px] flex items-start justify-center px-4 py-10">
    <div class="w-full max-w-lg rounded-xl bg-white dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 shadow-xl p-6">
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-base font-semibold text-zinc-900 dark:text-zinc-100">{{ __('auth.verify.title') }}</h2>
        </div>

        @if (session('status') === 'verification-link-expired')
            <div class="mb-4 rounded-xl border border-amber-200 dark:border-amber-800 bg-amber-50 dark:bg-amber-900/20 px-4 py-3 text-sm text-amber-800 dark:text-amber-200" role="alert">
                <span class="font-semibold">{{ __('auth.verify.expired_title') }}</span>
                <br>
                {{ __('auth.verify.expired_body_1') }}
                <br>
                {{ __('auth.verify.expired_body_2') }}
            </div>
        @endif

        <p class="text-sm text-zinc-500 dark:text-zinc-400 mb-4">
            {{ __('auth.verify.dashboard_block') }}
        </p>

        @if (session('status') === 'verification-link-sent')
            <div class="mb-4 rounded-lg bg-emerald-50 dark:bg-emerald-900/20 border border-emerald-200 dark:border-emerald-800 px-4 py-3 text-sm text-emerald-700 dark:text-emerald-400">
                {{ __('auth.verify.link_sent') }}
            </div>
        @endif

        <div class="flex flex-col sm:flex-row sm:items-center gap-3">
            <form
                id="resend-verification-form"
                method="POST"
                action="{{ route('verification.send') }}"
                data-loading-submit-form
                data-loading-submit-button="resend-verification-button"
            >
                @csrf
                <x-loading-submit-button
                    buttonId="resend-verification-button"
                    :label="__('auth.verify.resend')"
                    :loadingLabel="__('auth.verify.resending')"
                    class="w-full sm:w-auto"
                />
            </form>
            <a
                href="{{ route('profile') }}"
                class="w-full sm:w-auto inline-flex items-center justify-center rounded-lg border border-zinc-300 dark:border-zinc-600 px-4 py-2 text-sm font-medium text-zinc-700 dark:text-zinc-300 hover:bg-zinc-50 dark:hover:bg-zinc-700 transition-colors cursor-pointer"
            >
                {{ __('auth.verify.go_profile') }}
            </a>
        </div>
    </div>
</div>
