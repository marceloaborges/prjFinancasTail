@props([
    'id',               // ID único para o modal (ex: 'create-cargo')
    'title',            // Título que vai aparecer no topo do modal
    'label' => 'Novo',  // Texto do botão (ex: 'Novo Cargo', 'Novo Cliente')
    'action',           // A rota do POST (ex: route('painel.cargos.store'))
    'maxWidth' => 'md', // Trocado de 'max-w-md' para apenas 'md'
    'disabled' => false // Estado de desabilitado
])

<x-meus-components.modal :id="$id" :title="$title" :maxWidth="$maxWidth">
    <x-slot:trigger>
        <button 
            type="button" 
            @disabled($disabled)
            @click="if ({{ $disabled ? 'true' : 'false' }}) $event.stopImmediatePropagation()"
            class="inline-flex h-[42px] items-center gap-2 rounded px-4 py-2 text-theme-sm font-medium shadow-theme-xs transition whitespace-nowrap
                {{ $disabled 
                    ? 'bg-gray-300 dark:bg-gray-700 text-gray-500 dark:text-gray-400 cursor-not-allowed opacity-60' 
                    : 'bg-blue-600 text-white hover:bg-blue-700 cursor-pointer' }}"
        >
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <line x1="12" y1="5" x2="12" y2="19"></line>
                <line x1="5" y1="12" x2="19" y2="12"></line>
            </svg>
            {{ $label }}
        </button>
    </x-slot:trigger>

    {{-- O formulário fica envelopado aqui dentro de forma fixa --}}
    <form 
        id="form-{{ $id }}"
        action="{{ $action }}" 
        method="POST" 
        class="space-y-4"
        x-effect="if (!open) { 
            $el.reset(); 
            document.querySelectorAll('#form-{{ $id }} [role=\'alert\']').forEach(el => el.remove());
        }"
    >
        
        {{-- Injeta dinamicamente os inputs que você mandar da View --}}
        {{ $slot }}

        {{-- Rodapé com os botões de salvar/cancelar --}}
        <div class="flex justify-end gap-2 border-t border-stroke pt-3 dark:border-strokedark mt-4">
            <x-meus-components.btn-cancelar @click="open = false" />
            <x-meus-components.btn-salvar label="Salvar" />
        </div>
    </form>
</x-meus-components.modal>