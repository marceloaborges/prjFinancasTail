{{-- resources/views/painel/cargos/edit-modal.blade.php --}}

<div 
    x-data="{ 
        open: {{ old('validationReturn') === 'edit' ? 'true' : 'false' }}, 
        id: '{{ old('id', '') }}', 
        descricao: '{{ old('descricao', '') }}', 
        ativa: {{ old('ativa') || old('validationReturn') !== 'edit' ? 'true' : 'false' }}
    }"
    @carregar-edicao-cargo.window="
        // 🧹 Limpa os erros velhos antes de carregar o novo item
        $el.querySelectorAll('[role=\'alert\']').forEach(el => el.remove());

        id = $event.detail.id; 
        descricao = $event.detail.descricao; 
        ativa = $event.detail.ativa; 
        open = true;
    "
    x-show="open" 
    class="fixed inset-0 z-999 flex items-center justify-center overflow-y-auto bg-black/50 p-4" 
    style="display: none;" 
    x-transition
>
    <div @click.away="open = false" class="w-full max-w-xl transform rounded-lg bg-white p-6 dark:border-gray-800 dark:bg-white/[0.03] sm:px-6 transition-all">
        
        <div class="flex items-center justify-between border-b border-stroke pb-3 dark:border-strokedark mb-4">
            <h3 class="text-lg font-semibold text-black dark:text-white">Editar Cargo</h3>
        </div>

        <form :action="'/painel/cargos/' + id" method="POST" class="space-y-4">
            @csrf
            @method('PUT')
            <input type="hidden" name="id" x-model="id">
            <input type="hidden" name="validationReturn" value="edit">

            {{-- ⚡ INJEÇÃO DOS MESMOS CAMPOS UNIFICADOS --}}
            @include('painel.cargo.form')

            <div class="flex items-center justify-end gap-3 mt-6 pt-4 border-t border-stroke dark:border-strokedark">
                <x-meus-components.btn-cancelar :href="route('painel.cargos.index')" />
                <x-meus-components.btn-atualizar label="Atualizar Dados" />
            </div>
        </form>
    </div>
</div>