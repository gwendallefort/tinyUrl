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
    <svg data-theme-icon="system" class="w-5 h-5 hidden" xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 18 18" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="lg:size-3 size-3.5">
        <path d="M4.5 15.5L9 14.5L13.5 15.5" stroke="currentColor" width="1.5" linecap="round" linejoin="round"></path>
        <path d="M9 11.75V14.5" stroke="currentColor" width="1.5" linecap="round" linejoin="round"></path>
        <path d="M14.25 2.75H3.75C2.64543 2.75 1.75 3.64543 1.75 4.75V9.75C1.75 10.8546 2.64543 11.75 3.75 11.75H14.25C15.3546 11.75 16.25 10.8546 16.25 9.75V4.75C16.25 3.64543 15.3546 2.75 14.25 2.75Z" stroke="currentColor" width="1.5" linecap="round" linejoin="round"></path>
    </svg>
</button>
