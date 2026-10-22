<!DOCTYPE html>
<html lang="pt-BR">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Autenticação de Dois Fatores (2FA) | {{ config('app.name') }}</title>
        {{-- Injeta o Tailwind/Vite do seu ecossistema --}}
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="bg-gray-100 dark:bg-boxdark-2 min-h-screen flex items-center justify-center p-4 transition-colors duration-200">

        <div class="w-full max-w-md bg-white dark:bg-boxdark rounded-sm border border-stroke dark:border-strokedark shadow-default p-6 sm:p-8">
            
            {{-- Logotipo ou Ícone de Segurança --}}
            <div class="flex flex-col items-center text-center mb-6">
                <div class="flex h-12 w-12 items-center justify-center rounded-full bg-blue-100 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 mb-3">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                        <rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect>
                        <path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
                    </svg>
                </div>
                <h2 class="text-xl font-bold text-black dark:text-white sm:text-2xl">
                    Verificação de Segurança
                </h2>
                <p class="text-sm font-medium text-gray-500 dark:text-gray-400 mt-1">
                    A autenticação de dois fatores (2FA) está ativa na sua conta.
                </p>
            </div>

            {{-- ⚡ CASO 1: PRIMEIRO ACESSO (Exibe o QR Code para o usuário escanear) --}}
            @if($qrCodeUrl)
                <div class="mb-6 p-4 bg-gray-50 dark:bg-meta-4 rounded border border-stroke dark:border-strokedark text-center">
                    <p class="text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider mb-3">
                        1. Escaneie o QR Code abaixo
                    </p>
                    
                    {{-- Renderização limpa do QR Code via API de SVG --}}
                    <div class="inline-block p-2 bg-white rounded shadow-sm mb-3">
                        {{-- Injeta a URL encodada perfeitamente para a API de SVG externo --}}
                        <img src="https://api.qrserver.com/v1/create-qr-code/?size=180x180&data={{ urlencode($qrCodeUrl) }}&format=svg" 
                             alt="QR Code 2FA" 
                             class="w-[180px] h-[180px]">
                    </div>

                    <p class="text-xs text-gray-500 dark:text-gray-400 px-2 leading-relaxed">
                        Abra o seu app de autenticação (Google Authenticator, Authy ou similar) e aponte a câmera do celular.
                    </p>
                    
                    {{-- Código Texto de Backup se a câmera falhar --}}
                    <div class="mt-3 pt-2 border-t border-stroke dark:border-strokedark">
                        <span class="text-[10px] text-gray-400 uppercase font-bold block">Chave manual se necessário:</span>
                        <code class="text-xs font-mono font-bold text-blue-600 dark:text-blue-400 break-all select-all">{{ $secretKey }}</code>
                    </div>
                </div>
            @endif

            {{-- FORMULÁRIO DE VALIDAÇÃO DO TOKEN --}}
            <form action="{{ route('painel.2fa.verificar') }}" method="POST" x-data="{ code: '' }">
                @csrf

                <div class="mb-5">
                    <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                        {{ $qrCodeUrl ? '2. Digite o código de 6 dígitos gerado:' : 'Digite o código de 6 dígitos gerado no seu celular:' }}
                    </label>
                    
                    <div class="relative">
                        <input 
                            type="text" 
                            name="code" 
                            maxlength="6"
                            x-model="code"
                            placeholder="000000" 
                            autocomplete="one-time-code"
                            inputmode="numeric"
                            pattern="[0-9]*"
                            class="w-full text-center tracking-[0.5em] text-xl font-bold font-mono rounded border-[1.5px] border-stroke bg-transparent py-3 px-5 text-black outline-none transition focus:border-blue-600 active:border-blue-600 dark:border-form-strokedark dark:bg-form-input dark:text-white dark:focus:border-blue-500 placeholder:tracking-normal placeholder:font-sans placeholder:text-gray-400"
                            required
                            autofocus
                        />
                    </div>

                    @error('code')
                        <span class="text-xs text-danger mt-1.5 block font-medium" role="alert">
                            {{ $message }}
                        </span>
                    @enderror
                </div>

                {{-- Botões de Ação Padronizados --}}
                <div class="flex flex-col gap-3">
                    {{-- Injeta o nosso botão de salvar modificado para agir como confirmação --}}
                    <x-meus-components.btn-salvar label="Confirmar Código e Acessar" class="w-full justify-center" />
                    
                    {{-- Botão de escape seguro: Desloga o usuário se ele quiser desistir --}}
                    <button 
                        type="button"
                        onclick="document.getElementById('logout-form').submit();"
                        class="text-xs font-medium text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-white transition text-center underline cursor-pointer mt-1"
                    >
                        Sair da conta / Voltar ao Login
                    </button>
                </div>
            </form>

            {{-- Formulário Oculto de Logout para o botão de escape --}}
            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">
                @csrf
            </form>

        </div>

    </body>
</html>