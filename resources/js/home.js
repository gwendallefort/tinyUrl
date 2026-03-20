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

window.openEditDialog = openEditDialog;
window.openDeleteDialog = openDeleteDialog;
window.copyToClipboard = copyToClipboard;
