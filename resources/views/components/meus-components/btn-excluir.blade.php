@props([
    'id',               // ID único do registro
    'action',           // Rota do destroy (ex: route('...'))
    'description',      // Texto dinâmico (ex: "o cargo 'Diretor'")
    'disabled' => false // Estado de desabilitado
])

<div x-data="{ open: false }" @open-modal-delete-{{ $id }}.window="if (!{{ $disabled ? 'true' : 'false' }}) open = true" {{ $attributes->merge(['class' => 'inline-block']) }}>
    
    {{-- Gatilho: Botão Customizado com Texto e Ícone --}}
    <button type="button" 
            @disabled($disabled)
            @click="if (!{{ $disabled ? 'true' : 'false' }}) open = true" 
            class="flex h-[42px] w-full sm:w-auto items-center justify-center gap-2 rounded px-4 py-2 text-sm font-medium transition shadow-sm
                {{ $disabled 
                    ? 'bg-gray-300 dark:bg-gray-700 text-gray-500 dark:text-gray-400 cursor-not-allowed opacity-60' 
                    : 'bg-red-600 text-white hover:bg-red-700 active:scale-[0.98] cursor-pointer' }}">
        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
            <polyline points="3 6 5 6 21 6"></polyline>
            <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
            <line x1="10" y1="11" x2="10" y2="17"></line>
            <line x1="14" y1="11" x2="14" y2="17"></line>
        </svg>
        Excluir
    </button>

    {{-- Modal de Confirmação Fixo --}}
    <div x-show="open" class="fixed inset-0 z-999 flex items-center justify-center overflow-y-auto bg-black/50 p-4" style="display: none;" x-transition>
        <div @click.away="open = false" class="w-full max-w-md transform rounded bg-white p-6 shadow-xl dark:bg-boxdark text-left dark:border-gray-800 dark:bg-white/[0.03] sm:px-6 transition-all">
            
            <div class="flex items-center justify-between border-b border-stroke pb-3 dark:border-strokedark mb-4">
                <h3 class="text-lg font-semibold text-black dark:text-white">Confirmar Exclusão</h3>
            </div>

            <form action="{{ $action }}" method="POST">
                @csrf
                @method('DELETE')

                <div class="flex items-start gap-3 p-2">
                    <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-red-700 text-red-600 dark:bg-red-500/10 mt-0.5">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500 dark:text-gray-400">
                            Tem certeza que deseja excluir {!! $description !!} ? 
                            <br/>Esta ação não poderá ser desfeita.
                        </p>
                    </div>
                </div>

                <div class="flex justify-end gap-2 border-t border-stroke pt-3 dark:border-strokedark mt-5">
                    <button type="button" @click="open = false" class="px-4 py-2 border border-gray-300 dark:border-gray-700 text-gray-700 dark:text-gray-300 text-sm font-medium rounded hover:bg-gray-50 dark:hover:bg-gray-800">
                        Cancelar
                    </button>
                    <button type="submit" class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white text-sm font-medium rounded">
                        Sim, Excluir
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>