<button
    type="button"
    data-theme-toggle
    class="shrink-0 inline-flex items-center justify-center rounded-lg p-2 text-zinc-500 dark:text-zinc-400 hover:bg-zinc-100 dark:hover:bg-zinc-700 hover:text-zinc-900 dark:hover:text-zinc-100 transition-colors cursor-pointer"
    aria-label="{{ __('ui.theme.aria_label') }}"
    aria-pressed="false"
    data-label-switched-light="{{ __('ui.theme.switched_light') }}"
    data-label-switched-dark="{{ __('ui.theme.switched_dark') }}"
    data-label-switched-system="{{ __('ui.theme.switched_system') }}"
    data-label-current-light="{{ __('ui.theme.current_light') }}"
    data-label-current-dark="{{ __('ui.theme.current_dark') }}"
    data-label-current-system="{{ __('ui.theme.current_system') }}"
>
    <svg data-theme-icon="light" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" />
    </svg>
    <svg data-theme-icon="dark" class="w-5 h-5 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" />
    </svg>
</button>
