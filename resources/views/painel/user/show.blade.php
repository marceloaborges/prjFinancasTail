@extends('layouts.app')

@section('content')
    <div class="space-y-6">
        
        {{-- ⚡ CABEÇALHO DO HUB DO USUÁRIO --}}
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between mb-4">
            <div>
                <h2 class="text-xl font-bold text-black dark:text-white sm:text-2xl">
                    Perfil de: {{ $usuario->name }}
                </h2>
                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">
                    Visualizando detalhes e permissões da conta.
                </p>
            </div>

            <div class="flex items-center gap-3">
                {{-- Botão Voltar à Lista --}}
                <x-meus-components.btn-voltar :href="route('painel.users.index')" />
                
                {{-- Botão Editar Dados --}}
                @can('editar_usuarios')
                    <x-meus-components.btn-editar :href="route('painel.users.edit', $usuario->id)" />
                @endcan

                {{-- Botão Excluir Registro --}}
                @can('excluir_usuarios')
                    <x-meus-components.btn-excluir 
                        :id="$usuario->id" 
                        :action="route('painel.users.destroy', $usuario->id)" 
                        :description="'o usuário <strong>' . $usuario->nome . '</strong>'" 
                    />
                @endcan
            </div>
        </div>

        {{-- ⚡ GRID DE INFORMAÇÕES --}}
        <div class="grid grid-cols-12 gap-6">
            
            {{-- Card Principal: Dados Cadastrais --}}
            <div class="col-span-12 lg:col-span-8 p-6 bg-white dark:bg-boxdark rounded-sm border border-stroke dark:border-strokedark shadow-default">
                <h3 class="text-base font-semibold text-gray-700 dark:text-gray-300 border-b border-stroke dark:border-strokedark pb-2 mb-4">
                    Informações da Conta
                </h3>

                <dl class="grid grid-cols-1 sm:grid-cols-2 gap-x-6 gap-y-4">
                    <div>
                        <dt class="text-xs font-semibold uppercase tracking-wider text-gray-400">Nome Completo</dt>
                        <dd class="text-sm font-medium text-black dark:text-white mt-1">{{ $usuario->name }}</dd>
                    </div>

                    <div>
                        <dt class="text-xs font-semibold uppercase tracking-wider text-gray-400">Endereço de E-mail</dt>
                        <dd class="text-sm font-medium text-black dark:text-white mt-1">{{ $usuario->email }}</dd>
                    </div>
                </dl>
            </div>

            {{-- Card Lateral: Status de Segurança --}}
            <div class="col-span-12 lg:col-span-4 p-6 bg-white dark:bg-boxdark rounded-sm border border-stroke dark:border-strokedark shadow-default">
                <h3 class="text-base font-semibold text-gray-700 dark:text-gray-300 border-b border-stroke dark:border-strokedark pb-2 mb-4">
                    Status de Segurança
                </h3>

                <div class="space-y-4">
                    {{-- Status Ativo --}}
                    <div class="flex items-center justify-between p-3 rounded-lg bg-gray-50 dark:bg-meta-4">
                        <span class="text-sm font-medium text-gray-600 dark:text-gray-400">Conta Ativa</span>
                        <span class="inline-flex rounded-full px-2.5 py-1 text-xs font-semibold {{ $usuario->active ? 'bg-success/10 text-success' : 'bg-danger/10 text-danger' }}">
                            {{ $usuario->active ? 'Sim' : 'Não' }}
                        </span>
                    </div>

                    {{-- Status Acesso Geral --}}
                    <div class="flex items-center justify-between p-3 rounded-lg bg-gray-50 dark:bg-meta-4">
                        <span class="text-sm font-medium text-gray-600 dark:text-gray-400">Permissão de Login</span>
                        <span class="inline-flex rounded-full px-2.5 py-1 text-xs font-semibold {{ $usuario->access ? 'bg-success/10 text-success' : 'bg-danger/10 text-danger' }}">
                            {{ $usuario->access ? 'Liberado' : 'Bloqueado' }}
                        </span>
                    </div>

                    {{-- Status 2FA --}}
                    <div class="flex items-center justify-between p-3 rounded-lg bg-gray-50 dark:bg-meta-4">
                        <span class="text-sm font-medium text-gray-600 dark:text-gray-400">Exigência de 2FA</span>
                        <span class="inline-flex rounded-full px-2.5 py-1 text-xs font-semibold {{ $usuario->two_factor_required ? 'bg-warning/10 text-warning' : 'bg-gray-400/10 text-gray-400' }}">
                            {{ $usuario->two_factor_required ? 'Obrigatório' : 'Opcional' }}
                        </span>
                    </div>
                </div>
            </div>

        </div>
    </div>
@endsection