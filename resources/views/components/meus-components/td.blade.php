@props([
    'align' => 'left',
    'preserveLines' => false
])

@php
    $alignmentClasses = [
        'left' => 'text-left',
        'center' => 'text-center',
        'right' => 'text-right',
    ];
    
    $lineClass = $preserveLines ? 'whitespace-pre-line' : '';
@endphp

{{-- Adicionada a classe 'align-middle' --}}
<td {{ $attributes->merge(['class' => "px-4 py-3.5 align-middle text-sm text-gray-500 dark:text-gray-400 {$alignmentClasses[$align]} {$lineClass}"]) }}>
    {{ $slot }}
</td>