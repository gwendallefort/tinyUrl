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
            <label for="create-short-code" class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1.5">
                Custom alias <span class="text-zinc-400 dark:text-zinc-500 font-normal">(optional — auto-generated if empty)</span>
            </label>
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
