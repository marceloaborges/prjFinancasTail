@props([
    'color' => 'gray', // Cor padrão (gray, green, red, blue, yellow, etc.)
    'dot' => false,      // Exibir o ponto por padrão (defina como false para ocultar)
])

@php
    // Mapeamento das classes do container (Fundo, Texto e Anel)
    $colors = [
        'green'  => 'bg-green-50 text-green-700 ring-green-600/20 dark:bg-green-500/10 dark:text-green-400 dark:ring-green-500/20',
        'red'    => 'bg-red-50 text-red-700 ring-red-600/20 dark:bg-red-500/10 dark:text-red-400 dark:ring-red-500/20',
        'blue'   => 'bg-blue-50 text-blue-700 ring-blue-600/20 dark:bg-blue-500/10 dark:text-blue-400 dark:ring-blue-500/20',
        'yellow' => 'bg-yellow-50 text-yellow-800 ring-yellow-600/20 dark:bg-yellow-400/10 dark:text-yellow-500 dark:ring-yellow-400/20',
        'gray'   => 'bg-gray-50 text-gray-600 ring-gray-500/10 dark:bg-gray-400/10 dark:text-gray-400 dark:ring-gray-400/20',
    ];

    // Mapeamento da cor da bolinha interna
    $dotColors = [
        'green'  => 'bg-green-600 dark:bg-green-400',
        'red'    => 'bg-red-600 dark:bg-red-400',
        'blue'   => 'bg-blue-600 dark:bg-blue-400',
        'yellow' => 'bg-yellow-600 dark:bg-yellow-400',
        'gray'   => 'bg-gray-600 dark:bg-gray-400',
    ];

    // Obtém as classes com base na cor passada ou usa 'gray' como fallback
    $badgeClasses = $colors[$color] ?? $colors['gray'];
    $dotClass     = $dotColors[$color] ?? $dotColors['gray'];
@endphp

<span {{ $attributes->merge(['class' => "inline-flex items-center gap-1.5 rounded-full px-2.5 py-0.5 text-xs font-medium ring-1 ring-inset " . $badgeClasses]) }}>
    {{-- Exibe a bolinha apenas se a prop $dot for verdadeira --}}
    @if($dot)
        <span class="h-1.5 w-1.5 rounded-full {{ $dotClass }}"></span>
    @endif
    
    {{ $slot }}
</span>