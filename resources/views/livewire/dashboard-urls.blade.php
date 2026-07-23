<div>
    {{-- Search --}}
    <div class="mb-6">
        <label for="dashboard-search" class="sr-only">Search short URLs</label>
        <div class="flex gap-2">
            <div class="relative flex-1">
                <svg class="pointer-events-none absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-zinc-400 dark:text-zinc-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35M11 18a7 7 0 100-14 7 7 0 000 14z" />
                </svg>
                <input
                    id="dashboard-search"
                    wire:model.live.debounce.300ms="search"
                    type="search"
                    placeholder="Search..."
                    autocomplete="off"
                    class="w-full rounded-lg border border-zinc-300 dark:border-zinc-600 bg-white dark:bg-zinc-800 pl-9 pr-3 py-2 text-sm text-zinc-900 dark:text-zinc-100 placeholder-zinc-400 dark:placeholder-zinc-500 focus:outline-none focus:ring-2 focus:ring-zinc-900 dark:focus:ring-zinc-100 focus:border-transparent transition"
                >
            </div>
            @if ($search !== '')
                <button
                    type="button"
                    wire:click="clear"
                    class="inline-flex items-center rounded-lg border border-zinc-300 dark:border-zinc-600 bg-white dark:bg-zinc-800 px-4 py-2 text-sm font-medium text-zinc-500 dark:text-zinc-400 hover:bg-zinc-50 dark:hover:bg-zinc-700 transition-colors cursor-pointer"
                >
                    Clear
                </button>
            @endif
        </div>
    </div>

    {{-- URL list --}}
    <div wire:loading.class="opacity-60" class="transition-opacity">
        @if ($shortUrls->isEmpty())
            <div class="bg-white dark:bg-zinc-800 rounded-xl border border-zinc-200 dark:border-zinc-700 p-10 text-center">
                <div class="mx-auto w-12 h-12 flex items-center justify-center rounded-full bg-zinc-100 dark:bg-zinc-700 mb-4">
                    <svg class="w-6 h-6 text-zinc-500 dark:text-zinc-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1" />
                    </svg>
                </div>
                @if ($search !== '')
                    <h2 class="text-base font-medium text-zinc-900 dark:text-zinc-100 mb-1">No matching short URLs</h2>
                    <p class="text-sm text-zinc-500 dark:text-zinc-400 mb-5">Nothing matched “{{ $search }}” in short codes or original URLs.</p>
                    <button
                        type="button"
                        wire:click="clear"
                        class="inline-flex items-center gap-1.5 rounded-lg bg-zinc-900 dark:bg-zinc-100 px-4 py-2 text-sm font-medium text-white dark:text-zinc-900 hover:bg-zinc-700 dark:hover:bg-zinc-300 transition-colors cursor-pointer"
                    >
                        Clear search
                    </button>
                @else
                    <h2 class="text-base font-medium text-zinc-900 dark:text-zinc-100 mb-1">No short URLs yet</h2>
                    <p class="text-sm text-zinc-500 dark:text-zinc-400 mb-5">Your shortened URLs will appear here once you create them.</p>
                    <button
                        type="button"
                        onclick="document.getElementById('create-modal').showModal()"
                        class="inline-flex items-center gap-1.5 rounded-lg bg-zinc-900 dark:bg-zinc-100 px-4 py-2 text-sm font-medium text-white dark:text-zinc-900 hover:bg-zinc-700 dark:hover:bg-zinc-300 transition-colors cursor-pointer"
                    >
                        Create your first short URL
                    </button>
                @endif
            </div>
        @else
            <div class="bg-white dark:bg-zinc-800 rounded-xl border border-zinc-200 dark:border-zinc-700 overflow-hidden">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="border-b border-zinc-200 dark:border-zinc-700 text-left">
                            <th class="px-5 py-3 text-xs font-medium text-zinc-500 dark:text-zinc-400 uppercase tracking-wide">URL</th>
                            <th class="px-5 py-3 text-xs font-medium text-zinc-500 dark:text-zinc-400 uppercase tracking-wide hidden sm:table-cell">Original</th>
                            <th class="px-5 py-3 text-xs font-medium text-zinc-500 dark:text-zinc-400 uppercase tracking-wide text-right">Clicks</th>
                            <th class="px-5 py-3 text-xs font-medium text-zinc-500 dark:text-zinc-400 uppercase tracking-wide text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-zinc-100 dark:divide-zinc-700/60">
                        @foreach ($shortUrls as $url)
                            <tr class="hover:bg-zinc-50 dark:hover:bg-zinc-700/30 transition-colors group" wire:key="url-{{ $url->uuid }}">
                                {{-- Short URL cell --}}
                                <td class="px-5 py-4">
                                    @if ($url->title)
                                        <p class="font-medium text-zinc-900 dark:text-zinc-100 truncate max-w-[180px]" title="{{ $url->title }}">{{ $url->title }}</p>
                                    @endif
                                    <div class="flex items-center gap-1.5 mt-0.5">
                                        <a
                                            href="{{ $url->shortLink(1) }}"
                                            target="_blank"
                                            class="font-mono text-xs text-zinc-500 dark:text-zinc-400 hover:text-zinc-900 dark:hover:text-zinc-100 transition-colors"
                                        >/{{ $url->short_code }}</a>
                                        <button
                                            type="button"
                                            onclick="copyToClipboard('{{ $url->shortLink() }}', this)"
                                            class="transition-opacity p-0.5 rounded text-zinc-400 hover:text-zinc-600 dark:hover:text-zinc-200 cursor-pointer"
                                            title="Copy short URL"
                                        >
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z" />
                                            </svg>
                                        </button>
                                    </div>
                                </td>
                                {{-- Original URL cell --}}
                                <td class="px-5 py-4 hidden sm:table-cell">
                                    <a
                                        href="{{ $url->original_url }}"
                                        target="_blank"
                                        class="text-zinc-500 dark:text-zinc-400 hover:text-zinc-900 dark:hover:text-zinc-100 transition-colors truncate block max-w-xs"
                                        title="{{ $url->original_url }}"
                                    >{{ $url->original_url }}</a>
                                </td>
                                {{-- Clicks --}}
                                <td class="px-5 py-4 text-right tabular-nums text-zinc-700 dark:text-zinc-300 font-medium">
                                    {{ number_format($url->clicks) }}
                                </td>
                                {{-- Actions --}}
                                <td class="px-5 py-4">
                                    <div class="flex items-center justify-end gap-2">
                                        <button
                                            type="button"
                                            onclick="openEditDialog('{{ $url->uuid }}', {{ json_encode($url->title ?? '') }}, {{ json_encode($url->original_url) }}, {{ json_encode($url->short_code) }})"
                                            class="p-1.5 rounded-md text-zinc-400 hover:text-zinc-700 dark:hover:text-zinc-200 hover:bg-zinc-100 dark:hover:bg-zinc-700 transition-colors cursor-pointer"
                                            title="Edit"
                                        >
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                            </svg>
                                        </button>
                                        <button
                                            type="button"
                                            onclick="openQrDialog({{ json_encode(route('short-urls.qr', $url)) }}, {{ json_encode('/'.$url->short_code) }})"
                                            class="p-1.5 rounded-md text-zinc-400 hover:text-zinc-700 dark:hover:text-zinc-200 hover:bg-zinc-100 dark:hover:bg-zinc-700 transition-colors cursor-pointer"
                                            title="QR code"
                                        >
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z" />
                                            </svg>
                                        </button>
                                        <button
                                            type="button"
                                            onclick="openDeleteDialog('{{ $url->uuid }}', {{ json_encode($url->title ?: $url->short_code) }})"
                                            class="p-1.5 rounded-md text-zinc-400 hover:text-red-600 dark:hover:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/20 transition-colors cursor-pointer"
                                            title="Delete"
                                        >
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</div>
