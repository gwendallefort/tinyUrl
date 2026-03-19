function openEditDialog(id, title, originalUrl, shortCode) {
    document.getElementById('edit-title').value = title;
    document.getElementById('edit-original-url').value = originalUrl;
    document.getElementById('edit-short-code').value = shortCode;
    document.getElementById('edit-form').action = '/short-urls/' + id;
    document.getElementById('edit-modal').showModal();
}

function openDeleteDialog(id, label) {
    document.getElementById('delete-url-label').textContent = label;
    document.getElementById('delete-form').action = '/short-urls/' + id;
    document.getElementById('delete-modal').showModal();
}

function copyToClipboard(text, btn) {
    navigator.clipboard.writeText(text).then(() => {
        btn.dataset.copied = '';
        setTimeout(() => delete btn.dataset.copied, 2000);
    });
}

window.openEditDialog = openEditDialog;
window.openDeleteDialog = openDeleteDialog;
window.copyToClipboard = copyToClipboard;
