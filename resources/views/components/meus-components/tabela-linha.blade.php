@props([
    'cols' => 12, {{-- Quantidade de colunas para o colspan --}}
])

<tbody x-data="{ aberto: false }" class="border-b border-stroke dark:border-strokedark last:border-b-0">
    {{-- LINHA PRINCIPAL --}}
    <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/50 transition-colors">
        
        {{-- COLUNA 1: O Botão com o Ícone (Injetado automaticamente pelo componente) --}}
        <td class="p-3 text-center w-10">
            <button 
                type="button" 
                @click="aberto = !aberto" 
                class="p-1.5 rounded hover:bg-gray-200 dark:hover:bg-gray-700 text-gray-500 hover:text-gray-800 dark:hover:text-gray-200 transition cursor-pointer inline-flex items-center justify-center"
                title="Expandir/Recolher"
            >
                <svg 
                    xmlns="http://www.w3.org/2000/svg" 
                    viewBox="0 0 24 24" 
                    fill="none" 
                    stroke="currentColor" 
                    stroke-width="2" 
                    stroke-linecap="round" 
                    stroke-linejoin="round"
                    class="w-4 h-4 transition-transform duration-200 shrink-0"
                    :class="{ 'rotate-90 text-primary': aberto }"
                >
                    <polyline points="9 18 15 12 9 6"></polyline>
                </svg>
            </button>
        </td>

        {{-- DEMAIS COLUNAS: Aqui entram as 7 <td>s que você declarou na sua view --}}
        {{ $slot }}
    </tr>

    {{-- LINHA DE DETALHES (COLLAPSE) --}}
    <tr x-show="aberto" x-transition.opacity.duration.200ms class="bg-gray-50/70 dark:bg-slate-900/60">
        <td colspan="{{ $cols }}" class="p-4 border-t border-stroke dark:border-strokedark">
            {{ $detalhes }}
        </td>
    </tr>
</tbody>