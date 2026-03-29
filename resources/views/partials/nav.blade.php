<header class="border-b border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-800">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 h-14 flex items-center justify-between">
        <a href="{{ url('/') }}" class="site-logo-a flex items-center gap-2 text-base font-semibold tracking-tight text-zinc-900 dark:text-zinc-100">
            @include('components/logo', ['pathClass' => 'site-logo-path'])
            {{ config('app.name') }}
        </a>
        <div class="flex items-center gap-4">

            @if(request()->routeIs('dashboard'))
                <span class="text-sm font-medium text-zinc-900 dark:text-zinc-100">Dashboard</span>
            @else
                <a href="{{ route('dashboard') }}" class="text-sm text-zinc-500 dark:text-zinc-400 hover:text-zinc-900 dark:hover:text-zinc-100 transition-colors">
                    Dashboard
                </a>
            @endif

            <span class="text-zinc-300 dark:text-zinc-600">|</span>

            @if(request()->routeIs('profile'))
                <span class="text-sm font-medium text-zinc-900 dark:text-zinc-100">Profile</span>
            @else
                <a href="{{ route('profile') }}" class="text-sm text-zinc-500 dark:text-zinc-400 hover:text-zinc-900 dark:hover:text-zinc-100 transition-colors">
                    Profile
                </a>
            @endif
        </div>
    </div>
</header>
