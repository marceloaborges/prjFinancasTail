{{-- 1. CAMPOS DO FORMULÁRIO SEARCH --}}
<x-slot:filtros>

    <x-meus-components.input-text
        name="descricao" 
        label="Pesquisar por Descrição" 
        placeholder="Digite o cargo..."
        col="col-span-12 md:col-span-5 lg:col-span-3"
    />

    <x-meus-components.select 
        name="ativa" 
        label="Situação"
        col="col-span-12 md:col-span-5 lg:col-span-3"
    >
        {{-- Injetando as options dentro do $slot do seu componente --}}
        <option value="">Todos</option>
        <option value="1" {{ request('ativa') === '1' ? 'selected' : '' }}>Somente Ativos</option>
        <option value="0" {{ request('ativa') === '0' ? 'selected' : '' }}>Somente Inativos</option>
    </x-meus-components.select>

    <div class="col-span-12 md:col-span-2 lg:col-span-2 flex items-center gap-2 self-end">
    
        {{-- Botão de Lupa Nativo --}}
        <button 
            type="submit" 
            class="h-[42px] aspect-square flex items-center justify-center bg-gray-400 dark:bg-gray-700 text-white rounded-md hover:bg-gray-500 dark:hover:bg-gray-600 transition justify-self-start self-end mb-0"
            title="Filtrar"
            aria-label="Filtrar resultados"
        >
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
            </svg>
        </button>

        @if(request()->anyFilled(['descricao', 'ativa']))
            <a
                href="{{ route('painel.cargos.index') }}" 
                class="h-[42px] aspect-square flex items-center justify-center border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-500 dark:text-gray-400 rounded-md hover:bg-gray-50 dark:hover:bg-gray-700 transition justify-self-start self-end mb-0"
                title="Limpar Filtros"
                aria-label="Limpar filtros aplicados"
            >
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </a>
        @endif
    </div>

</x-slot:filtros>