<x-slot:acoes>
    <x-meus-components.btn-novo 
        id="create-cargo" 
        title="Cadastrar Novo Cargo" 
        label="Novo"
        :action="route('painel.cargos.store')"
        maxWidth="xl"
    >
        @csrf
        <input type="hidden" name="validationReturn" value="create">

        {{-- 
           ⚡ INICIALIZAÇÃO SEGURA DO ESCREVENTE DO ALPINE PARA O CADASTRO.
           Dizemos ao Alpine: "Para este formulário, use variáveis limpas!"
           Isso blinda o input contra o lixo do escopo global.
        --}}
        <div 
            x-data="{ 
                descricao: '{{ old('validationReturn') === 'create' ? old('descricao', '') : '' }}', 
                ativa: {{ old('validationReturn') === 'create' ? (old('ativa') ? 'true' : 'false') : 'true' }}
            }"
            @close-modal-create-cargo.window="
                descricao = ''; 
                ativa = true;
                // 🧹 Remove fisicamente os alertas de erro do Laravel de dentro deste bloco
                $el.querySelectorAll('[role=\'alert\']').forEach(el => el.remove());
            "
        >
            
            {{-- ⚡ INJEÇÃO DIRETA DOS CAMPOS UNIFICADOS --}}
            @include('painel.cargo.form')
            
        </div>
        
    </x-meus-components.btn-novo>
</x-slot:acoes>