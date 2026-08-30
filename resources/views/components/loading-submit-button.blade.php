@props([
    'buttonId',
    'label' => null,
    'loadingLabel' => null,
])

@php
    $label ??= __('ui.actions.submit');
    $loadingLabel ??= __('ui.actions.sending');
@endphp

<button
    type="submit"
    id="{{ $buttonId }}"
    {{ $attributes->merge(['class' => 'inline-flex items-center justify-center rounded-lg bg-zinc-900 dark:bg-zinc-100 px-4 py-2 text-sm font-medium text-white dark:text-zinc-900 hover:bg-zinc-700 dark:hover:bg-zinc-300 focus:outline-none focus:ring-2 focus:ring-zinc-900 dark:focus:ring-zinc-100 focus:ring-offset-2 transition-colors cursor-pointer']) }}
>
    <span class="js-btn-label">{{ $label }}</span>
    <span class="js-btn-loading hidden items-center gap-2 flex" aria-hidden="true">
        {{--
        <svg class="h-4 w-4 animate-spin" viewBox="0 0 24 24" fill="none">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"></path>
        </svg>
        --}}
        <svg
            class="h-4 w-4"
            fill="#000000"
            width="24px"
            height="24px"
            viewBox="0 0 24 24"
            xmlns="http://www.w3.org/2000/svg"
        >
            <path
                id="js-btn-loading-path"
                class="anim-logo"
                pathLength="1"
                d="M14.84,14.82a3.73,3.73,0,1,0-.2-5.46L9.36,14.64a3.73,3.73,0,1,1-.2-5.46Z"
                fill="none"
                stroke="currentColor"
                stroke-linecap="round"
                stroke-width="3"
            />
        </svg>
        {{ $loadingLabel }}
    </span>
</button>

@once
    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', () => {
                document.querySelectorAll('[data-loading-submit-form]').forEach((form) => {
                    if (form.dataset.loadingSubmitBound === '1') return;
                    form.dataset.loadingSubmitBound = '1';

                    form.addEventListener('submit', () => {
                        const buttonId = form.getAttribute('data-loading-submit-button');
                        if (!buttonId) return;
                        const btn = document.getElementById(buttonId);
                        if (!btn) return;

                        btn.disabled = true;
                        btn.classList.add('opacity-70', 'cursor-not-allowed');
                        btn.setAttribute('aria-busy', 'true');

                        const label = btn.querySelector('.js-btn-label');
                        const loading = btn.querySelector('.js-btn-loading');
                        const path = btn.querySelector('#js-btn-loading-path');
                        path.classList.add(Math.random() < 0.5 ? 'anim-logo' : 'anim-logo2');
                        if (label) label.classList.add('hidden');
                        if (loading) loading.classList.remove('hidden');
                    }, { once: true });
                });
            });
        </script>
    @endpush
@endonce

