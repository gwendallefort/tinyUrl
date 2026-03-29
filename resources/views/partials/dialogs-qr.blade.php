<dialog
    id="qr-modal"
    class="m-auto w-full max-w-sm rounded-xl bg-white dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 shadow-xl p-6 backdrop:bg-black/50 open:flex open:flex-col"
    onclick="if (event.target === this) this.close()"
>
    <div class="contents" onclick="event.stopPropagation()">
    <h3 class="text-base font-semibold text-zinc-900 dark:text-zinc-100 mb-1">QR code</h3>
    <p class="text-sm text-zinc-500 dark:text-zinc-400 mb-4">
        Scan to open <span id="qr-modal-label" class="font-mono text-zinc-700 dark:text-zinc-300"></span>
    </p>
    <div class="flex justify-center rounded-lg border border-zinc-200 dark:border-zinc-600 bg-white p-3 mb-4">
        <img id="qr-modal-image" src="" alt="QR code for short URL" width="280" height="280" class="max-w-full h-auto" />
    </div>
    <div class="flex flex-wrap items-center gap-3">
        <a
            id="qr-modal-download"
            href=""
            download
            class="inline-flex items-center rounded-lg bg-zinc-900 dark:bg-zinc-100 px-4 py-2 text-sm font-medium text-white dark:text-zinc-900 hover:bg-zinc-700 dark:hover:bg-zinc-300 focus:outline-none focus:ring-2 focus:ring-zinc-900 dark:focus:ring-zinc-100 focus:ring-offset-2 transition-colors"
        >
            Download PNG
        </a>
        <button
            type="button"
            onclick="document.getElementById('qr-modal').close()"
            class="inline-flex items-center rounded-lg border border-zinc-300 dark:border-zinc-600 px-4 py-2 text-sm font-medium text-zinc-700 dark:text-zinc-300 hover:bg-zinc-50 dark:hover:bg-zinc-700 transition-colors cursor-pointer"
        >
            Close
        </button>
    </div>
    </div>
</dialo g>
