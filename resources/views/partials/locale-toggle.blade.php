<button
    type="button"
    data-locale-toggle
    data-locale-current="{{ app()->getLocale() }}"
    data-label-switch="{{ __('ui.locale.switch_to') }}"
    data-label-current="{{ __('ui.locale.current') }}"
    class="shrink-0 inline-flex items-center justify-center rounded-lg px-2 py-1.5 min-w-[2.5rem] text-xs font-semibold tracking-wide text-zinc-500 dark:text-zinc-400 hover:bg-zinc-100 dark:hover:bg-zinc-700 hover:text-zinc-900 dark:hover:text-zinc-100 transition-colors cursor-pointer"
    aria-label="{{ __('ui.locale.switch_to', ['locale' => app()->getLocale() === 'fr' ? 'EN' : 'FR']) }}"
    title="{{ __('ui.locale.current', ['locale' => strtoupper(app()->getLocale())]) }}"
>
    <span data-locale-label>{{ strtoupper(app()->getLocale()) }} </span>
</button>
