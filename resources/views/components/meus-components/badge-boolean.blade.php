@props([
    'value' => false,
    'type' => 'danger' // 'danger' (Vermelho se Não), 'neutral' (Cinza se Não), 'inverted' (Alerta se Sim)
])

@php
    $classes = match($type) {
        'neutral' => $value 
            ? 'bg-success/10 text-success' 
            : 'bg-gray-400/10 text-gray-400',

        'inverted' => $value 
            ? 'bg-warning/10 text-warning' 
            : 'bg-success/10 text-success',

        default => $value 
            ? 'bg-success/10 text-success' 
            : 'bg-danger/10 text-danger',
    };
@endphp

<span class="inline-flex rounded px-2.5 py-0.5 text-xs font-semibold {{ $classes }}">
    {{ $value ? 'Sim' : 'Não' }}
</span>