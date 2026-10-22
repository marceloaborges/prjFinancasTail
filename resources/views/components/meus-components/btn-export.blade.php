{{-- resources/views/components/meus-components/btn-export.blade.php --}}
@props([
    'href',
    'label' => 'Exportar',
])

<a
    href="{{ $href }}"
    {{ $attributes->merge([
        'class' => 'inline-flex h-[42px] items-center gap-2 rounded-lg bg-green-600 px-4 py-2 text-theme-sm font-medium text-white shadow-theme-xs hover:bg-green-700 transition cursor-pointer whitespace-nowrap'
    ]) }}
>
    {{-- Ícone Excel / Download --}}
    <svg xmlns="http://www.w3.org/2000/svg"
         width="20"
         height="20"
         viewBox="0 0 24 24"
         fill="none"
         stroke="currentColor"
         stroke-width="2"
         stroke-linecap="round"
         stroke-linejoin="round">
        <path d="M12 3v12"/>
        <path d="M8 11l4 4 4-4"/>
        <path d="M4 21h16"/>
    </svg>

    {{ $label }}
</a>