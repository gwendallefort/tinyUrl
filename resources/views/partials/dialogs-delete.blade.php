<dialog
    id="delete-modal"
    class="m-auto w-full max-w-md rounded-xl bg-white dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 shadow-xl p-6 backdrop:bg-black/50 open:flex open:flex-col"
    onclick="if (event.target === this) this.close()"
>
    <div class="contents" onclick="event.stopPropagation()">
    <h3 class="text-base font-semibold text-zinc-900 dark:text-zinc-100 mb-2">Delete short URL?</h3>
    <p class="text-sm text-zinc-500 dark:text-zinc-400 mb-1">
        You are about to delete:
    </p>
    <p id="delete-url-label" class="text-sm font-medium text-zinc-900 dark:text-zinc-100 mb-5 truncate"></p>
    <p class="text-sm text-zinc-500 dark:text-zinc-400 mb-5">This cannot be undone.</p>

    <form id="delete-form" method="POST" action="">
        @csrf
        @method('DELETE')

        <div class="flex items-center gap-3">
            <button
                type="submit"
                class="inline-flex items-center rounded-lg bg-red-600 px-4 py-2 text-sm font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-600 focus:ring-offset-2 transition-colors cursor-pointer"
            >
                Delete
            </button>
            <button
                type="button"
                onclick="document.getElementById('delete-modal').close()"
                class="inline-flex items-center rounded-lg border border-zinc-300 dark:border-zinc-600 px-4 py-2 text-sm font-medium text-zinc-700 dark:text-zinc-300 hover:bg-zinc-50 dark:hover:bg-zinc-700 transition-colors cursor-pointer"
            >
                Cancel
            </button>
        </div>
    </form>
    </div>
</dialog>
