@props([
    'href'
])

<a href="{{ $href }}" 
   {{ $attributes->merge(['class' => 'inline-flex h-[42px] items-center gap-2 rounded bg-amber-500 px-4 py-2 text-theme-sm font-medium text-white shadow-theme-xs hover:bg-amber-600 transition cursor-pointer whitespace-nowrap']) }}>
    {{-- Ícone de lápis (Editar) --}}
    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
        <path d="M12 20h9"></path>
        <path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z"></path>
    </svg>
    Editar
</a>