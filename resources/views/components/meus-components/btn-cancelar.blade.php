@props([
    'href' => null,         // Opcional agora! Se enviado, vira link.
    'label' => 'Cancelar'
])

@php
    // Classes CSS idênticas compartilhadas para manter o padrão visual indestrutível
    $classes = "group flex h-[42px] items-center justify-center gap-2 rounded border border-gray-300 dark:border-gray-700 px-4 py-2 font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-800 transition shadow-sm cursor-pointer whitespace-nowrap";
@endphp

@if($href)
    {{-- Comportamento de Link (Para telas Create/Edit normais) --}}
    <a href="{{ $href }}" class="{{ $classes }}">
        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" class="text-gray-400 group-hover:text-gray-600 dark:group-hover:text-gray-200 transition">
            <line x1="18" y1="6" x2="6" y2="18"></line>
            <line x1="6" y1="6" x2="18" y2="18"></line>
        </svg>
        {{ $label }}
    </a>
@else
    {{-- Comportamento de Botão (Perfeito para fechar Modais com Alpine) --}}
    <button type="button" {{ $attributes->merge(['class' => $classes]) }}>
        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" class="text-gray-400 group-hover:text-gray-600 dark:group-hover:text-gray-200 transition">
            <line x1="18" y1="6" x2="6" y2="18"></line>
            <line x1="6" y1="6" x2="18" y2="18"></line>
        </svg>
        {{ $label }}
    </button>
@endif