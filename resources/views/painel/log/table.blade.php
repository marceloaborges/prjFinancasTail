{{-- CABEÇALHO DA TABELA --}}
<x-slot:thead>
    <x-meus-components.th class="w-10"></x-meus-components.th> {{-- Coluna reservada para o ícone de seta --}}
    <x-meus-components.th class="w-24">DATA</x-meus-components.th>
    <x-meus-components.th>AÇÃO</x-meus-components.th>
    <x-meus-components.th>MÉTODO</x-meus-components.th>
    <x-meus-components.th>DESCRICAO</x-meus-components.th>
    <x-meus-components.th>TABELA</x-meus-components.th>
    <x-meus-components.th>TIPO</x-meus-components.th>
    <x-meus-components.th>RESPONSÁVEL</x-meus-components.th>
</x-slot:thead>

{{-- CORPO DA TABELA --}}
<x-slot:body>
    @foreach ($logs as $log)
        {{-- ⚡ Cada linha gerencia seu próprio estado de abertura de forma isolada --}}
        <tbody x-data="{ aberto: false }" class="border-b border-stroke dark:border-strokedark last:border-none">
            
            {{-- LINHA PRINCIPAL --}}
            <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/50 transition">
                
                {{-- Botão com Ícone que rotaciona quando aberto --}}
                <x-meus-components.td class="w-10 text-center">
                    <button 
                        type="button" 
                        @click="aberto = !aberto" 
                        class="p-1 rounded hover:bg-gray-200 dark:hover:bg-gray-700 text-gray-500 transition cursor-pointer"
                    >
                        {{-- Seta que vira 90 graus se 'aberto' for true --}}
                        <svg 
                            xmlns="http://www.w3.org/2000/svg" 
                            width="16" height="16" 
                            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                            class="transition-transform duration-200"
                            :class="aberto ? 'rotate-90' : ''"
                        >
                            <polyline points="9 18 15 12 9 6"></polyline>
                        </svg>
                    </button>
                </x-meus-components.td>

                <x-meus-components.td class="w-24 whitespace-nowrap">
                    {{ \Carbon\Carbon::parse($log->created_at)->format('d-m-Y H:i:s') }}
                </x-meus-components.td>
                <x-meus-components.td>{{ $log->acao }}</x-meus-components.td>
                <x-meus-components.td>{{ $log->metodo }}</x-meus-components.td>
                <x-meus-components.td>{{ $log->descricao }}</x-meus-components.td>
                <x-meus-components.td>{{ $log->tabela }}</x-meus-components.td>
                <x-meus-components.td>{{ $log->tipo->descricao ?? '--' }}</x-meus-components.td>
                <x-meus-components.td>{{ $log->user->name ?? '--' }}</x-meus-components.td>
            </tr>

            {{-- LINHA DO COLLAPSE (PAYLOAD) --}}
            {{-- Só aparece se 'aberto' for true. O 'colspan="8"' garante que ela ocupe toda a largura da tabela --}}
            <tr x-show="aberto" x-transition>
                <td colspan="8" class="p-4 bg-gray-50 dark:bg-slate-900 border-t border-stroke dark:border-strokedark">
                    <div class="text-left">
                        <span class="text-xs font-semibold text-gray-500 uppercase tracking-wider block mb-2">
                            📦 Payload dos Dados Alterados:
                        </span>
                        
                        {{-- Mostra o JSON formatado de um jeito bonito e legível --}}
                        <pre class="p-3 bg-white dark:bg-boxdark rounded-lg text-xs font-mono text-green-600 dark:text-green-400 overflow-x-auto max-h-60 shadow-inner"><code>{{ json_encode($log->payload ?? [], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</code></pre>
                    </div>
                </td>
            </tr>

        </tbody>
    @endforeach
</x-slot:body>

{{-- SUA PAGINAÇÃO PERSONALIZADA --}}
<x-slot:pagination>
    {{ $logs->links('vendor.pagination.customized-pagination') }}
</x-slot:pagination>