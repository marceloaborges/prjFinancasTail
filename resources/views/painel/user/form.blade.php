<div class="grid grid-cols-12 gap-6" 
     x-data="{ 
        liberarAcesso: {{ old('access', $usuario->access ?? 0) == 1 ? 'true' : 'false' }} 
     }">
    
    {{-- Seção: Informações Básicas --}}
    <div class="col-span-12">
        <h4 class="text-base font-semibold text-gray-700 dark:text-gray-300 border-b border-stroke dark:border-strokedark pb-2">
            Informações Básicas
        </h4>
    </div>

    {{-- Nome --}}
    <x-meus-components.input-text 
        name="name"
        label="Nome Completo"
        placeholder="Digite o nome completo"
        :model="$usuario ?? null"
        required
        col="col-span-12 md:col-span-6"
    />

    {{-- E-mail --}}
    <x-meus-components.input-text 
        type="email"
        name="email"
        label="E-mail"
        placeholder="exemplo@email.com"
        :model="$usuario ?? null"
        required
        col="col-span-12 md:col-span-6"
    />

    {{-- Seção: Atribuições e Hierarquia --}}
    <div class="col-span-12 mt-4">
        <h4 class="text-base font-semibold text-gray-700 dark:text-gray-300 border-b border-stroke dark:border-strokedark pb-2">
            Atribuições e Hierarquia
        </h4>
    </div>

    {{-- Seção: Configurações de Acesso e Segurança --}}
    <div class="col-span-12 mt-4">
        <h4 class="text-base font-semibold text-gray-700 dark:text-gray-300 border-b border-stroke dark:border-strokedark pb-2">
            Segurança e Status
        </h4>
    </div>

    {{-- ⚡ TOGGLES ALINHADOS NA MESMA LINHA --}}

    {{-- Toggle: Exigir Segundo Fator (2FA) --}}
    <x-meus-components.toggle 
        name="two_factor_required"
        label="Exigir Autenticação de 2 Fatores"
        :model="$usuario ?? null"
        :checked="true"
        col="col-span-12 sm:col-span-4"
    />
    
    {{-- Toggle: Usuário Ativo --}}
    <x-meus-components.toggle 
        name="active"
        label="Usuário Ativo"
        :model="$usuario ?? null"
        :checked="false"
        col="col-span-12 sm:col-span-4"
    />

    {{-- Toggle: Liberar Acesso Geral --}}
    <x-meus-components.toggle 
        name="access"
        label="Liberar Acesso"
        :model="$usuario ?? null"
        :checked="false"
        modelAlpine="liberarAcesso"
        col="col-span-12 sm:col-span-4"
    />
    

    {{-- Bloco Condicional das Senhas com Alpine.js ⚡ --}}
    {{-- Exibe sempre no cadastro se 'liberarAcesso' for true. Na edição, obedece estritamente ao toggle dinâmico --}}
    <div class="col-span-12 grid grid-cols-12 gap-6 mt-4" 
         x-show="liberarAcesso" 
         x-transition.duration.300ms>
        
        <div class="col-span-12">
            <h4 class="text-sm font-semibold text-gray-600 dark:text-gray-400 border-b border-stroke dark:border-strokedark pb-1">
                Definição de Credenciais
            </h4>
        </div>

        {{-- Senha Real --}}
        <x-meus-components.input-text 
            type="password"
            name="password"
            label="Senha {{ isset($usuario) ? '(Deixe em branco para não alterar)' : '' }}"
            placeholder="••••••••"
            col="col-span-12 md:col-span-6"
        />

        {{-- Confirmação da Senha --}}
        <x-meus-components.input-text 
            type="password"
            name="password_confirmation"
            label="Confirmar Senha"
            placeholder="••••••••"
            col="col-span-12 md:col-span-6"
        />
    </div>

</div>