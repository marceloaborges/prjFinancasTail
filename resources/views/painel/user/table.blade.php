{{-- 2. CABEÇALHO DA TABELA --}}
<x-slot:thead>
	<x-meus-components.th>Name</x-meus-components.th>
	<x-meus-components.th>Email</x-meus-components.th>
	<x-meus-components.th>Role</x-meus-components.th>
</x-slot:thead>

{{-- 3. CORPO DA TABELA --}}
<x-slot:body>
    @foreach ($users as $user) {{-- Ajustado para bater com a sua variável do controller ($usuarios) --}}
        <tr 
            x-data
            @click="if (!['BUTTON', 'A', 'INPUT', 'I'].includes($event.target.tagName)) window.location.href = '{{ route('painel.users.show', $user->id) }}'"
            class="hover:bg-gray-50 dark:hover:bg-gray-800/50 transition cursor-pointer"
        >
            <x-meus-components.td class="font-medium text-black dark:text-white">
                {{ $user->name }}
            </x-meus-components.td>
            
            <x-meus-components.td>
                {{ $user->email }}
            </x-meus-components.td>
            
            {{--💡 CORREÇÃO: Estava imprimindo o name de novo, alterado para a descrição da função/cargo --}}
            <x-meus-components.td>
                {{ $user->funcao->descricao ?? 'Não Atribuída' }}
            </x-meus-components.td>
        </tr>
    @endforeach
</x-slot:body>

{{-- 4. SUA PAGINAÇÃO PERSONALIZADA --}}
<x-slot:pagination>
    {{ $users->links('vendor.pagination.customized-pagination') }}
</x-slot:pagination>