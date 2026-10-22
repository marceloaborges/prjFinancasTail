@props([
    'tabs',
    'default' => null,
    'col' => 'col-span-12'
])

@php
    // Captura o parâmetro '?tab=' da URL atual
    $queryTab = request()->query('tab');
    
    // Se o parâmetro existir e bater com as chaves do array, ativa ele. Senão, pega a padrão.
    $abaInicial = (array_key_exists($queryTab, $tabs)) ? $queryTab : ($default ?? array_key_first($tabs));
@endphp

<div class="{{ $col }}" x-data="{ activeTab: '{{ $abaInicial }}' }">
    
    {{-- 📑 Navegação das Abas (Nav Links) --}}
    <div class="flex flex-wrap gap-5 border-stroke dark:border-strokedark">
        @foreach($tabs as $chave => $nome)
            <button 
                type="button"
                {{-- Altera o estado no Alpine E atualiza a URL sem dar reload na página --}}
                @click="activeTab = '{{ $chave }}'; window.history.replaceState(null, null, '?tab={{ $chave }}');"
                class="text-sm font-semibold transition-all relative cursor-pointer"
                :class="activeTab === '{{ $chave }}' ? 'text-blue-600 dark:text-blue-400' : 'text-gray-500 hover:text-black dark:hover:text-white'"
            >
                {{ $nome }}
                
                {{-- Linha indicadora de aba ativa animada --}}
                <div 
                    x-show="activeTab === '{{ $chave }}'" 
                    x-transition.duration.200ms
                    class="absolute bottom-0 left-0 right-0 h-[2px] bg-blue-600 dark:bg-blue-400 rounded-full"
                ></div>
            </button>
        @endforeach
    </div>

    {{-- 📦 Conteúdo Dinâmico das Abas --}}
    <div>
        {{ $slot }}
    </div>
</div>