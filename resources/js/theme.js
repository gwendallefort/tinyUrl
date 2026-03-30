const THEME_STORAGE_KEY = 'lig.re-theme';

function showThemeTooltip(btn, themeName) {
    if (!btn) return;

    const tooltip = document.createElement('span');
    tooltip.textContent = `Switched to ${themeName} theme`;
    tooltip.className = 'pointer-events-none absolute z-50 rounded bg-gray-900 px-2 py-1 text-xs text-white shadow dark:bg-gray-100 dark:text-gray-900';
    tooltip.setAttribute('role', 'status');
    tooltip.setAttribute('aria-live', 'polite');

    const rect = btn.getBoundingClientRect();
    tooltip.style.left = `${window.scrollX + rect.left + rect.width / 2}px`;
    tooltip.style.top = `${window.scrollY + rect.top - 8}px`;
    tooltip.style.transform = 'translate(-50%, 200%)';

    document.body.appendChild(tooltip);
    window.setTimeout(() => tooltip.remove(), 1400);
}

function applyStoredOrSystemTheme() {
    const root = document.documentElement;
    const stored = localStorage.getItem(THEME_STORAGE_KEY);
    if (stored === 'light') {
        root.classList.remove('dark');
    } else if (stored === 'dark') {
        root.classList.add('dark');
    } else {
        root.classList.toggle('dark', window.matchMedia('(prefers-color-scheme: dark)').matches);
    }
}

function cycleColorScheme(btn) {
    const root = document.documentElement;
    const stored = localStorage.getItem(THEME_STORAGE_KEY);
    let switchedTo = 'light';

    if (stored === 'light') {
        localStorage.setItem(THEME_STORAGE_KEY, 'dark');
        root.classList.add('dark');
        switchedTo = 'dark';
    } else if (stored === 'dark') {
        localStorage.setItem(THEME_STORAGE_KEY, 'system');
        root.classList.toggle('dark', window.matchMedia('(prefers-color-scheme: dark)').matches);
        switchedTo = 'system';
    } else {
        localStorage.setItem(THEME_STORAGE_KEY, 'light');
        root.classList.remove('dark');
        switchedTo = 'light';
    }

    syncThemeToggleUi();
    showThemeTooltip(btn, switchedTo);
}

function syncThemeToggleUi() {
    document.querySelectorAll('[data-theme-toggle]').forEach((btn) => {
        const dark = document.documentElement.classList.contains('dark');
        const stored = localStorage.getItem(THEME_STORAGE_KEY);
        const system = stored !== 'light' && stored !== 'dark';

        btn.setAttribute('aria-pressed', dark ? 'true' : 'false');
        btn.setAttribute(
            'title',
            system
                ? 'Current theme: system'
                : dark
                  ? 'Current theme: dark'
                  : 'Current theme: light',
        );

        const sunIcon = btn.querySelector('[data-theme-icon="light"]');
        const moonIcon = btn.querySelector('[data-theme-icon="dark"]');
        const systemIcon = btn.querySelector('[data-theme-icon="system"]');
        if (sunIcon) sunIcon.classList.toggle('hidden', dark);
        if (moonIcon) moonIcon.classList.toggle('hidden', !dark);
        if (systemIcon) systemIcon.classList.add('hidden');
    });
}

export function initTheme() {
    applyStoredOrSystemTheme();

    const mq = window.matchMedia('(prefers-color-scheme: dark)');
    mq.addEventListener('change', () => {
        const s = localStorage.getItem(THEME_STORAGE_KEY);
        if (s !== 'light' && s !== 'dark') {
            document.documentElement.classList.toggle('dark', mq.matches);
            syncThemeToggleUi();
        }
    });

    document.querySelectorAll('[data-theme-toggle]').forEach((btn) => {
        btn.addEventListener('click', () => cycleColorScheme(btn));
    });

    syncThemeToggleUi();
}
