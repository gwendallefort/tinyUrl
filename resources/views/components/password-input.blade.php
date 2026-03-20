@props([
    'id'                => 'password',
    'name'              => 'password',
    'autocomplete'      => 'current-password',
    'placeholder'       => '••••••••',
    'hasError'          => false,
    'inputClass'        => 'px-3.5 py-2.5 outline-none transition-colors',
    'normalBorderClass' => 'border-zinc-300 dark:border-zinc-600 focus:border-zinc-500 dark:focus:border-zinc-400',
    'errorBorderClass'  => 'border-red-400 dark:border-red-600 focus:border-red-400 dark:focus:border-red-500',
])

<div class="relative">
    <input
        id="{{ $id }}"
        type="password"
        name="{{ $name }}"
        autocomplete="{{ $autocomplete }}"
        placeholder="{{ $placeholder }}"
        {{ $attributes->merge(['required' => true]) }}
        class="w-full pr-10 text-sm text-zinc-900 dark:text-zinc-100 placeholder-zinc-400 dark:placeholder-zinc-500 bg-white dark:bg-zinc-900 border rounded-lg {{ $inputClass }} {{ $hasError ? $errorBorderClass : $normalBorderClass }}"
    >
    <button
        type="button"
        onclick="togglePassword('{{ $id }}', this)"
        tabindex="-1"
        aria-label="Toggle password visibility"
        class="absolute inset-y-0 right-0 flex items-center px-3 text-zinc-400 hover:text-zinc-600 dark:hover:text-zinc-200 transition-colors"
    >
        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 icon-eye" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 icon-eye-off hidden" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17.94 17.94A10.07 10.07 0 0112 20c-7 0-11-8-11-8a18.45 18.45 0 015.06-5.94M9.9 4.24A9.12 9.12 0 0112 4c7 0 11 8 11 8a18.5 18.5 0 01-2.16 3.19m-6.72-1.07a3 3 0 11-4.24-4.24"/><line x1="1" y1="1" x2="23" y2="23"/></svg>
    </button>
</div>

@once
    @push('scripts')
    <script>
        function togglePassword(inputId, btn) {
            const input = document.getElementById(inputId);
            const isHidden = input.type === 'password';
            input.type = isHidden ? 'text' : 'password';
            btn.querySelector('.icon-eye').classList.toggle('hidden', isHidden);
            btn.querySelector('.icon-eye-off').classList.toggle('hidden', !isHidden);
        }
    </script>
    @endpush
@endonce
