function openEditDialog(id, title, originalUrl, shortCode) {
    document.getElementById('edit-url-id-field').value = id;
    document.getElementById('edit-title').value = title;
    document.getElementById('edit-original-url').value = originalUrl;
    document.getElementById('edit-short-code').value = shortCode;
    document.getElementById('edit-form').action = '/b/short-urls/' + id;
    document.getElementById('edit-modal').showModal();
}

function openDeleteDialog(id, label) {
    document.getElementById('delete-url-label').textContent = label;
    document.getElementById('delete-form').action = 'b/short-urls/' + id;
    document.getElementById('delete-modal').showModal();
}

function openQrDialog(imageUrl, shortPathLabel) {
    const img = document.getElementById('qr-modal-image');
    const download = document.getElementById('qr-modal-download');
    document.getElementById('qr-modal-label').textContent = shortPathLabel;
    img.src = imageUrl;
    download.href = imageUrl;
    document.getElementById('qr-modal').showModal();
}

function copyToClipboard(text, btn) {
    const markCopied = () => {
        btn.dataset.copied = '';
        setTimeout(() => delete btn.dataset.copied, 2000);
    };

    if (navigator.clipboard) {
        navigator.clipboard.writeText(text).then(markCopied);
    } else {
        const textarea = document.createElement('textarea');
        textarea.value = text;
        textarea.style.cssText = 'position:fixed;opacity:0';
        document.body.appendChild(textarea);
        textarea.select();
        document.execCommand('copy');
        document.body.removeChild(textarea);
        markCopied();
    }
}

function initSuggestShortCode() {
    const button = document.getElementById('suggest-short-code-btn');
    if (!button) {
        return;
    }

    const i18n = (window.__dashboardI18n && window.__dashboardI18n.suggest) || {
        label: 'Suggest',
        loading: 'Suggesting…',
        missingContext: 'Enter a destination URL or title first.',
        empty: 'No available suggestions were returned. Try a clearer title or URL.',
        unavailable: 'Unable to suggest aliases right now. Please try again.',
    };

    const results = document.getElementById('suggest-short-code-results');
    const error = document.getElementById('suggest-short-code-error');
    const label = document.getElementById('suggest-short-code-label');
    const icon = document.getElementById('suggest-short-code-icon');
    const spinner = document.getElementById('suggest-short-code-spinner');
    const spinnerPath = document.getElementById('suggest-short-code-spinner-path');
    const shortCodeInput = document.getElementById('create-short-code');
    const titleInput = document.getElementById('create-title');
    const urlInput = document.getElementById('create-original-url');

    const setLoading = (loading) => {
        button.disabled = loading;
        label.textContent = loading ? i18n.loading : i18n.label;
        icon.classList.toggle('hidden', loading);
        spinner.classList.toggle('hidden', !loading);
        if (spinnerPath) {
            spinnerPath.classList.remove('anim-logo', 'anim-logo2');
            if (loading) {
                spinnerPath.classList.add(Math.random() < 0.5 ? 'anim-logo' : 'anim-logo2');
            }
        }
    };

    const showError = (message) => {
        error.textContent = message;
        error.hidden = false;
        results.hidden = true;
        results.innerHTML = '';
    };

    const renderSuggestions = (suggestions) => {
        error.hidden = true;
        error.textContent = '';
        results.innerHTML = '';

        if (!suggestions.length) {
            showError(i18n.empty);
            return;
        }

        suggestions.forEach((code) => {
            const chip = document.createElement('button');
            chip.type = 'button';
            chip.textContent = code;
            chip.className = 'inline-flex items-center rounded-md border border-zinc-300 dark:border-zinc-600 bg-zinc-50 dark:bg-zinc-700/60 px-2.5 py-1 text-xs font-mono text-zinc-700 dark:text-zinc-200 hover:bg-zinc-100 dark:hover:bg-zinc-700 transition-colors cursor-pointer';
            chip.addEventListener('click', () => {
                shortCodeInput.value = code;
                shortCodeInput.focus();
            });
            results.appendChild(chip);
        });

        results.hidden = false;
    };

    button.addEventListener('click', async () => {
        const title = titleInput.value.trim();
        const originalUrl = urlInput.value.trim();

        if (!title && !originalUrl) {
            showError(i18n.missingContext);
            return;
        }

        setLoading(true);

        try {
            const { data } = await window.axios.post(button.dataset.suggestUrl, {
                title,
                original_url: originalUrl || null,
            });

            renderSuggestions(data.suggestions || []);
        } catch (err) {
            const message = err.response?.data?.message
                || err.response?.data?.errors?.original_url?.[0]
                || i18n.unavailable;
            showError(message);
        } finally {
            setLoading(false);
        }
    });
}

document.addEventListener('DOMContentLoaded', initSuggestShortCode);

window.openEditDialog = openEditDialog;
window.openDeleteDialog = openDeleteDialog;
window.openQrDialog = openQrDialog;
window.copyToClipboard = copyToClipboard;
