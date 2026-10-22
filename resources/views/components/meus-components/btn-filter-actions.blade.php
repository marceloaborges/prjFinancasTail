@props([
    'route', // Rota para onde o usuário volta ao limpar (ex: 'painel.vendas.index')
    'params' => [] // Array opcional de campos específicos para monitorar, se não quiser monitorar tudo
])

@php
    // Se você passou campos específicos, checa se algum deles está preenchido.
    // Se não passou nada, ele checa automaticamente QUALQUER parâmetro da request (exceto o 'page' da paginação).
    $hasFilters = !empty($params) 
        ? request()->anyFilled($params) 
        : collect(request()->query())->except(['page'])->isNotEmpty();
@endphp

<x-meus-components.btn-search />

@if($hasFilters)
    <a
        href="{{ route($route, ['clear' => 1]) }}" 
        class="h-[42px] aspect-square flex items-center justify-center border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-500 dark:text-gray-400 rounded-md hover:bg-gray-100 dark:hover:bg-gray-700 transition cursor-pointer shadow-sm"
        title="Limpar Filtros"
    >
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
        </svg>
    </a>
@endif