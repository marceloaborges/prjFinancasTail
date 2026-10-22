@props([
    'href',             // A rota de destino (ex: route('painel.user.create'))
    'label' => 'Novo'   // Texto do botão
])

<a href="{{ $href }}" 
   class="inline-flex items-center h-[42px] rounded bg-blue-600 px-4 py-2 text-theme-sm font-medium text-white shadow-theme-xs hover:bg-blue-700 transition cursor-pointer whitespace-nowrap">
    {{-- Mesmo ícone de mais (+) fixo para manter a identidade visual do sistema --}}
    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
        <line x1="12" y1="5" x2="12" y2="19"></line>
        <line x1="5" y1="12" x2="19" y2="12"></line>
    </svg>
    {{ $label }}
</a>