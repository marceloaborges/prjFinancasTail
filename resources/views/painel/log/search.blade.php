{{-- 1. CAMPOS DO FORMULÁRIO SEARCH --}}
<x-slot:filtros>

    {{-- Multiselect para Responsáveis (Usuários) --}}
        <x-meus-components.multi-select 
            name="user_ids"
            label="Responsáveis"
            placeholder="Todos os usuários..."
            :options="$usuarios->map(fn($u) => ['value' => $u->id, 'label' => $u->name])->toArray()"
            col="col-span-12 md:col-span-5 lg:col-span-3"
        />

        {{-- Multiselect para Tipos de Log --}}
        <x-meus-components.multi-select 
            name="log_tipo_ids"
            label="Tipos de Log"
            placeholder="Todos os tipos..."
            :options="$tipos->map(fn($t) => ['value' => $t->id, 'label' => $t->descricao])->toArray()"
            col="col-span-12 md:col-span-5 lg:col-span-3"
        />

        {{-- 🔍 Input Texto para Tabela --}}
        <div class="col-span-12 md:col-span-4 lg:col-span-2">
            <label for="tabela" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Tabela</label>
            <input 
                type="text" 
                name="tabela" 
                id="tabela"
                value="{{ request('tabela') }}"
                placeholder="Ex: clientes..."
                class="w-full h-[42px] px-3 py-1.5 border border-gray-300 dark:border-gray-700 rounded-md bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-500"
            >
        </div>

        {{-- 🔍 Input Texto para Descrição --}}
        <div class="col-span-12 md:col-span-8 lg:col-span-2">
            <label for="descricao" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Descrição</label>
            <input 
                type="text" 
                name="descricao" 
                id="descricao"
                value="{{ request('descricao') }}"
                placeholder="Pesquisar no texto do log..."
                class="w-full h-[42px] px-3 py-1.5 border border-gray-300 dark:border-gray-700 rounded-md bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-500"
            >
        </div>

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

        @if(request()->anyFilled(['log_tipo_ids', 'user_ids', 'tabela', 'descricao']))
            <a
                href="{{ route('painel.logs.index') }}" 
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