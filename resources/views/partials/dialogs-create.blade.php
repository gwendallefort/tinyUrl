<dialog
    id="create-modal"
    class="m-auto w-full max-w-lg rounded-xl bg-white dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 shadow-xl p-6 backdrop:bg-black/50 open:flex open:flex-col"
>
    <div class="flex items-center justify-between mb-5">
        <h3 class="text-base font-semibold text-zinc-900 dark:text-zinc-100">New short URL</h3>
        <button
            type="button"
            onclick="document.getElementById('create-modal').close()"
            class="p-1 rounded-md text-zinc-400 hover:text-zinc-600 dark:hover:text-zinc-200 hover:bg-zinc-100 dark:hover:bg-zinc-700 transition-colors cursor-pointer"
        >
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>
    </div>

    @if ($errors->createUrl->any())
        <div class="mb-4 rounded-lg bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 px-4 py-3 text-sm text-red-700 dark:text-red-400">
            {{ $errors->createUrl->first() }}
        </div>
    @endif

    <form method="POST" action="{{ route('short-urls.store') }}" class="space-y-4">
        @csrf

        {{-- Title --}}
        <div>
            <label for="create-title" class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1.5">
                Title <span class="text-zinc-400 dark:text-zinc-500 font-normal">(optional)</span>
            </label>
            <input
                id="create-title"
                name="title"
                type="text"
                value="{{ old('title') }}"
                placeholder="My awesome link"
                autocomplete="off"
                class="w-full rounded-lg border border-zinc-300 dark:border-zinc-600 bg-white dark:bg-zinc-900 px-3 py-2 text-sm text-zinc-900 dark:text-zinc-100 placeholder-zinc-400 dark:placeholder-zinc-500 focus:outline-none focus:ring-2 focus:ring-zinc-900 dark:focus:ring-zinc-100 focus:border-transparent transition"
            >
        </div>

        {{-- Original URL --}}
        <div>
            <label for="create-original-url" class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1.5">
                Destination URL <span class="text-red-500">*</span>
            </label>
            <input
                id="create-original-url"
                name="original_url"
                type="text"
                value="{{ old('original_url') }}"
                placeholder="example.com/very-long-url"
                required
                autocomplete="off"
                class="w-full rounded-lg border border-zinc-300 dark:border-zinc-600 bg-white dark:bg-zinc-900 px-3 py-2 text-sm text-zinc-900 dark:text-zinc-100 placeholder-zinc-400 dark:placeholder-zinc-500 focus:outline-none focus:ring-2 focus:ring-zinc-900 dark:focus:ring-zinc-100 focus:border-transparent transition @error('original_url', 'createUrl') border-red-400 dark:border-red-500 @enderror"
            >
        </div>

        {{-- Custom alias --}}
        <div>
            <div class="flex items-center justify-between gap-3 mb-1.5">
                <label for="create-short-code" class="block text-sm font-medium text-zinc-700 dark:text-zinc-300">
                    Custom alias <span class="text-zinc-400 dark:text-zinc-500 font-normal">(optional - auto-generated if empty)</span>
                </label>
                <button
                    type="button"
                    id="suggest-short-code-btn"
                    data-suggest-url="{{ route('short-urls.suggest') }}"
                    class="inline-flex items-center gap-1.5 rounded-md px-2 py-1 text-xs font-medium text-zinc-600 dark:text-zinc-300 hover:bg-zinc-100 dark:hover:bg-zinc-700 transition-colors cursor-pointer disabled:opacity-50 disabled:cursor-not-allowed"
                >
                    <svg id="suggest-short-code-icon" class="w-3.5 h-3.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.813 15.904L9 18.75l-.813-2.846a4.5 4.5 0 00-3.09-3.09L2.25 12l2.846-.813a4.5 4.5 0 003.09-3.09L9 5.25l.813 2.846a4.5 4.5 0 003.09 3.09L15.75 12l-2.846.813a4.5 4.5 0 00-3.091 3.091zM18.259 8.715L18 9.75l-.259-1.035a3.375 3.375 0 00-2.455-2.456L14.25 6l1.036-.259a3.375 3.375 0 002.455-2.456L18 2.25l.259 1.035a3.375 3.375 0 002.456 2.456L21.75 6l-1.035.259a3.375 3.375 0 00-2.456 2.456zM16.894 20.785L16.5 21.75l-.394-.965a1.5 1.5 0 00-1.175-1.176L14.25 19.5l.965-.394a1.5 1.5 0 001.176-1.176l.394-.965.394.965a1.5 1.5 0 001.176 1.176l.965.394-.965.394a1.5 1.5 0 00-1.176 1.176z" />
                    </svg>
                    <svg id="suggest-short-code-spinner" class="hidden w-3.5 h-3.5 shrink-0" viewBox="0 0 24 24" fill="#000000" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                        <path
                            id="suggest-short-code-spinner-path"
                            class="anim-logo"
                            pathLength="1"
                            d="M14.84,14.82a3.73,3.73,0,1,0-.2-5.46L9.36,14.64a3.73,3.73,0,1,1-.2-5.46Z"
                            fill="none"
                            stroke="currentColor"
                            stroke-linecap="round"
                            stroke-width="3"
                        />
                    </svg>
                    <span id="suggest-short-code-label">Suggest</span>
                </button>
            </div>
            <div class="flex rounded-lg border border-zinc-300 dark:border-zinc-600 overflow-hidden focus-within:ring-2 focus-within:ring-zinc-900 dark:focus-within:ring-zinc-100 focus-within:border-transparent transition @error('short_code', 'createUrl') border-red-400 dark:border-red-500 @enderror">
                <span class="flex items-center px-3 bg-zinc-50 dark:bg-zinc-700 text-sm text-zinc-500 dark:text-zinc-400 border-r border-zinc-300 dark:border-zinc-600 shrink-0">
                    {{ parse_url(url('/'), PHP_URL_HOST) }}/
                </span>
                <input
                    id="create-short-code"
                    name="short_code"
                    type="text"
                    value="{{ old('short_code') }}"
                    placeholder="my-alias"
                    autocomplete="off"
                    class="flex-1 bg-white dark:bg-zinc-900 px-3 py-2 text-sm text-zinc-900 dark:text-zinc-100 placeholder-zinc-400 dark:placeholder-zinc-500 focus:outline-none"
                >
            </div>
            <div id="suggest-short-code-results" class="mt-2 flex flex-wrap gap-2" hidden></div>
            <p id="suggest-short-code-error" class="mt-1.5 text-xs text-red-600 dark:text-red-400" hidden></p>
            <p class="mt-1.5 text-xs text-zinc-400 dark:text-zinc-500 flex items-center gap-1">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5 shrink-0" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                </svg>
                Aliases are case-sensitive - ABC and abc are different links.
            </p>
        </div>

        <div class="flex items-center gap-3 pt-1">
            <button
                type="submit"
                class="inline-flex items-center rounded-lg bg-zinc-900 dark:bg-zinc-100 px-4 py-2 text-sm font-medium text-white dark:text-zinc-900 hover:bg-zinc-700 dark:hover:bg-zinc-300 focus:outline-none focus:ring-2 focus:ring-zinc-900 dark:focus:ring-zinc-100 focus:ring-offset-2 transition-colors cursor-pointer"
            >
                Create short URL
            </button>
            <button
                type="button"
                onclick="document.getElementById('create-modal').close()"
                class="inline-flex items-center rounded-lg border border-zinc-300 dark:border-zinc-600 px-4 py-2 text-sm font-medium text-zinc-700 dark:text-zinc-300 hover:bg-zinc-50 dark:hover:bg-zinc-700 transition-colors cursor-pointer"
            >
                Cancel
            </button>
        </div>
    </form>
</dialog>
