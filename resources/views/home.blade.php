<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Dashboard &mdash; {{ config('app.name') }}</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />

    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/css/home.css', 'resources/js/app.js', 'resources/js/home.js'])
    @endif
</head>
<body class="bg-gray-50 dark:bg-zinc-900 text-zinc-900 dark:text-zinc-100 min-h-screen antialiased" style="font-family: 'Instrument Sans', ui-sans-serif, system-ui, sans-serif;">

    {{-- Nav --}}
    <header class="border-b border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-800">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 h-14 flex items-center justify-between">
            <a href="{{ url('/') }}" class="flex items-center gap-2 text-base font-semibold tracking-tight text-zinc-900 dark:text-zinc-100">
                @include('components/logo')
                {{ config('app.name') }}
            </a>
            <div class="flex items-center gap-4">
                <a href="{{ route('profile') }}" class="text-sm text-zinc-500 dark:text-zinc-400 hover:text-zinc-900 dark:hover:text-zinc-100 transition-colors">
                    {{ auth()->user()->name }}
                </a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="text-sm text-zinc-500 dark:text-zinc-400 hover:text-zinc-900 dark:hover:text-zinc-100 transition-colors cursor-pointer">
                        Sign out
                    </button>
                </form>
            </div>
        </div>
    </header>

    {{-- Content --}}
    <main class="max-w-5xl mx-auto px-4 sm:px-6 py-12">

        {{-- Page header --}}
        <div class="mb-8 flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-semibold text-zinc-900 dark:text-zinc-100">Dashboard</h1>
            </div>
            <button
                type="button"
                onclick="document.getElementById('create-modal').showModal()"
                class="inline-flex items-center gap-1.5 rounded-lg bg-zinc-900 dark:bg-zinc-100 px-4 py-2 text-sm font-medium text-white dark:text-zinc-900 hover:bg-zinc-700 dark:hover:bg-zinc-300 focus:outline-none focus:ring-2 focus:ring-zinc-900 dark:focus:ring-zinc-100 focus:ring-offset-2 transition-colors cursor-pointer"
            >
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                New short URL
            </button>
        </div>

        {{-- Flash: newly created URL --}}
        @if (session('status') === 'url-created' && session('created_short_link'))
            <div class="mb-6 rounded-xl border border-emerald-200 dark:border-emerald-800 bg-emerald-50 dark:bg-emerald-900/20 p-4">
                <div class="flex items-start gap-3">
                    <svg class="mt-0.5 w-5 h-5 shrink-0 text-emerald-600 dark:text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-medium text-emerald-800 dark:text-emerald-300">Short URL created!</p>
                        <div class="mt-2 flex items-center gap-2">
                            <a
                                id="new-link"
                                href="{{ session('created_short_link') }}"
                                target="_blank"
                                class="text-sm font-mono text-emerald-700 dark:text-emerald-400 hover:underline truncate"
                            >{{ session('created_short_link') }}</a>
                            <button
                                type="button"
                                onclick="copyToClipboard('{{ session('created_short_link') }}', this)"
                                class="shrink-0 inline-flex items-center gap-1 rounded-md border border-emerald-300 dark:border-emerald-700 bg-white dark:bg-zinc-800 px-2.5 py-1 text-xs font-medium text-emerald-700 dark:text-emerald-400 hover:bg-emerald-50 dark:hover:bg-emerald-900/30 transition-colors cursor-pointer"
                            >
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z" />
                                </svg>
                                Copy
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        {{-- Flash: updated --}}
        @if (session('status') === 'url-updated')
            <div class="mb-6 rounded-xl border border-emerald-200 dark:border-emerald-800 bg-emerald-50 dark:bg-emerald-900/20 px-4 py-3 text-sm text-emerald-700 dark:text-emerald-400">
                Short URL updated successfully.
            </div>
        @endif

        {{-- Flash: deleted --}}
        @if (session('status') === 'url-deleted')
            <div class="mb-6 rounded-xl border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-800 px-4 py-3 text-sm text-zinc-500 dark:text-zinc-400">
                Short URL deleted.
            </div>
        @endif

        {{-- URL list --}}
        @if ($shortUrls->isEmpty())
            <div class="bg-white dark:bg-zinc-800 rounded-xl border border-zinc-200 dark:border-zinc-700 p-10 text-center">
                <div class="mx-auto w-12 h-12 flex items-center justify-center rounded-full bg-zinc-100 dark:bg-zinc-700 mb-4">
                    <svg class="w-6 h-6 text-zinc-500 dark:text-zinc-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1" />
                    </svg>
                </div>
                <h2 class="text-base font-medium text-zinc-900 dark:text-zinc-100 mb-1">No short URLs yet</h2>
                <p class="text-sm text-zinc-500 dark:text-zinc-400 mb-5">Your shortened URLs will appear here once you create them.</p>
                <button
                    type="button"
                    onclick="document.getElementById('create-modal').showModal()"
                    class="inline-flex items-center gap-1.5 rounded-lg bg-zinc-900 dark:bg-zinc-100 px-4 py-2 text-sm font-medium text-white dark:text-zinc-900 hover:bg-zinc-700 dark:hover:bg-zinc-300 transition-colors cursor-pointer"
                >
                    Create your first short URL
                </button>
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
                            <tr class="hover:bg-zinc-50 dark:hover:bg-zinc-700/30 transition-colors group">
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
                                            onclick="openEditDialog({{ $url->id }}, {{ json_encode($url->title ?? '') }}, {{ json_encode($url->original_url) }}, {{ json_encode($url->short_code) }})"
                                            class="p-1.5 rounded-md text-zinc-400 hover:text-zinc-700 dark:hover:text-zinc-200 hover:bg-zinc-100 dark:hover:bg-zinc-700 transition-colors cursor-pointer"
                                            title="Edit"
                                        >
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                            </svg>
                                        </button>
                                        <button
                                            type="button"
                                            onclick="openDeleteDialog({{ $url->id }}, {{ json_encode($url->title ?: $url->short_code) }})"
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

    </main>

    @include('partials.dialogs-create')
    @include('partials.dialogs-edit')
    @include('partials.dialogs-delete')

    <script>
        // Re-open create dialog if there are validation errors
        @if ($errors->createUrl->any())
            document.addEventListener('DOMContentLoaded', () => {
                document.getElementById('create-modal').showModal();
            });
        @endif

        // Re-open edit dialog if there are validation errors
        @if ($errors->editUrl->any() && session('edit_id'))
            document.addEventListener('DOMContentLoaded', () => {
                document.getElementById('edit-form').action = '/short-urls/{{ session('edit_id') }}';
                document.getElementById('edit-title').value = {{ json_encode(old('title', '')) }};
                document.getElementById('edit-original-url').value = {{ json_encode(old('original_url', '')) }};
                document.getElementById('edit-short-code').value = {{ json_encode(old('short_code', '')) }};
                document.getElementById('edit-modal').showModal();
            });
        @endif
    </script>

</body>
</html>
