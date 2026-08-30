const LOCALE_STORAGE_KEY = 'storage-locale';
const LOCALE_COOKIE = 'locale';
const AVAILABLE = ['en', 'fr'];

function readCookie(name) {
    const match = document.cookie.match(new RegExp('(?:^|; )' + name.replace(/([.$?*|{}()[\]\\/+^])/g, '\\$1') + '=([^;]*)'));
    return match ? decodeURIComponent(match[1]) : null;
}

function writeLocaleCookie(locale) {
    const maxAge = 60 * 60 * 24 * 365;
    const secure = window.location.protocol === 'https:' ? '; Secure' : '';
    document.cookie = `${LOCALE_COOKIE}=${encodeURIComponent(locale)}; path=/; max-age=${maxAge}; SameSite=Lax${secure}`;
}

function normalizeLocale(value) {
    return AVAILABLE.includes(value) ? value : null;
}

function currentLocale() {
    return (
        normalizeLocale(localStorage.getItem(LOCALE_STORAGE_KEY))
        || normalizeLocale(readCookie(LOCALE_COOKIE))
        || normalizeLocale(document.documentElement.lang?.slice(0, 2))
        || 'en'
    );
}

function otherLocale(locale) {
    return locale === 'fr' ? 'en' : 'fr';
}

function syncLocaleToggleUi() {
    const locale = currentLocale();
    const next = otherLocale(locale);

    document.querySelectorAll('[data-locale-toggle]').forEach((btn) => {
        btn.setAttribute('data-locale-current', locale);
        btn.setAttribute('aria-label', btn.dataset.labelSwitch?.replace(':locale', next.toUpperCase()) || `Switch to ${next}`);
        btn.setAttribute('title', btn.dataset.labelCurrent?.replace(':locale', locale.toUpperCase()) || `Language: ${locale}`);
        const label = btn.querySelector('[data-locale-label]');
        if (label) {
            label.textContent = locale.toUpperCase();
        }
    });
}

function setLocale(locale) {
    const next = normalizeLocale(locale);
    if (!next) {
        return;
    }

    localStorage.setItem(LOCALE_STORAGE_KEY, next);
    writeLocaleCookie(next);
    window.location.reload();
}

export function initLocale() {
    const stored = normalizeLocale(localStorage.getItem(LOCALE_STORAGE_KEY));
    const cookie = normalizeLocale(readCookie(LOCALE_COOKIE));

    if (stored && stored !== cookie) {
        writeLocaleCookie(stored);
        if (!sessionStorage.getItem('locale-syncing')) {
            sessionStorage.setItem('locale-syncing', '1');
            window.location.reload();
            return;
        }
    }
    sessionStorage.removeItem('locale-syncing');

    if (!stored && cookie) {
        localStorage.setItem(LOCALE_STORAGE_KEY, cookie);
    }

    document.querySelectorAll('[data-locale-toggle]').forEach((btn) => {
        btn.addEventListener('click', () => {
            setLocale(otherLocale(currentLocale()));
        });
    });

    syncLocaleToggleUi();
}
