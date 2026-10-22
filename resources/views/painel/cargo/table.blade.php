{{-- CABEÇALHO DA TABELA --}}
<x-slot:thead>
	<x-meus-components.th class="w-8">Id</x-meus-components.th>
	<x-meus-components.th>Descrição</x-meus-components.th>
    <x-meus-components.th class="w-12">Situação</x-meus-components.th>
    <x-meus-components.th class="w-24 text-center">...</x-meus-components.th>
</x-slot:thead>

{{-- CORPO DA TABELA --}}
<x-slot:body>
    @foreach ($cargos as $cargo)
        <tr>
        	<x-meus-components.td class="w-8">{{ $cargo->id }}</x-meus-components.td>
        	<x-meus-components.td>{{ $cargo->descricao }}</x-meus-components.td>
            <x-meus-components.td class="w-12">
                @if($cargo->ativa)
                    <x-meus-components.badge color="green">Ativo</x-meus-components.badge>
                @else
                    <x-meus-components.badge color="red">Inativo</x-meus-components.badge>
                @endif
            </x-meus-components.td>
            {{-- CÉLULA DE AÇÕES - SUPER COMPACTA E DINÂMICA! --}}
            <x-meus-components.td class="w-24 text-center">
                <div class="flex items-center justify-center gap-2">                            
                    
                    <button 
                        type="button" 
                        {{-- Dispara o evento 'carregar-edicao-cargo' enviando os dados limpos do objeto --}}
                        @click="$dispatch('carregar-edicao-cargo', { 
                            id: {{ $cargo->id }}, 
                            descricao: '{{ addslashes($cargo->descricao) }}', 
                            ativa: {{ $cargo->ativa == 1 ? 'true' : 'false' }} {{-- FORÇA BOOLEANO PURO --}}
                        })"
                        class="text-gray-500 hover:text-blue-600 transition p-1 cursor-pointer"
                        title="Editar Cargo"
                    >
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M12 20h9"></path>
                            <path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z"></path>
                        </svg>
                    </button>

                    {{-- COMPONENTE NOVO: EXCLUIR DINÂMICO --}}
                    <x-meus-components.icon-excluir 
                        :id="$cargo->id" 
                        :action="route('painel.cargos.destroy', $cargo->id)"
                        description="o registro <br/> <strong class='text-black dark:text-white'>&ldquo;{{ $cargo->descricao }}&rdquo;</strong>"
                    />

                </div>
            </x-meus-components.td>

        </tr>
    @endforeach
</x-slot:body>

{{-- SUA PAGINAÇÃO PERSONALIZADA --}}
<x-slot:pagination>
    {{ $cargos->links('vendor.pagination.customized-pagination') }}
</x-slot:pagination>