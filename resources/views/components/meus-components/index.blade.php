@props([
    'title' => 'Listagem',
    'id' => 'default',
    'heightClosed' => '300px',
    'heightOpened' => '400px',
    'filtros' => null, 
    'acoes' => null,
    'pagination' => null,
    'card' => true
])

<div class="grid grid-cols-1">
    {{-- Container do Card --}}
    <div @class([
            'overflow-hidden custom-scrollbar',
            'pt-4 rounded-2xl border border-gray-200 bg-white px-3 dark:border-gray-800 dark:bg-white/[0.03] sm:px-6' => $card,
            'pt-0 bg-transparent' => !$card
        ])>
        
        {{-- Cabeçalho Superior com Título e Botão --}}
        <div class="flex justify-between gap-2 mb-2 sm:items-center">
            @if($title)
                <div>
                    <h3 class="text-md font-semibold text-gray-800 dark:text-white/90">{{ $title }}</h3>
                </div>
            @endif

            <div class="relative flex items-center gap-2 ml-auto">
                @if(isset($acoes))
                    {{ $acoes }}
                @endif

                @if(isset($filtros) && trim($filtros) !== '')
                    <button id="btn-filtros-{{ $id }}" class="inline-flex items-center h-[42px] gap-2 rounded border border-gray-300 bg-white px-4 py-2 text-theme-sm font-medium text-gray-700 shadow-theme-xs hover:bg-gray-50 hover:text-gray-800 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:hover:bg-white/[0.03] dark:hover:text-gray-200 cursor-pointer">
                        <svg class="stroke-current fill-white dark:fill-gray-800" width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M2.29004 5.90393H17.7067" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M17.7075 14.0961H2.29085" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M12.0826 3.33331C13.5024 3.33331 14.6534 4.48431 14.6534 5.90414C14.6534 7.32398 13.5024 8.47498 12.0826 8.47498C10.6627 8.47498 9.51172 7.32398 9.51172 5.90415C9.51172 4.48432 10.6627 3.33331 12.0826 3.33331Z" stroke-width="1.5"/>
                            <path d="M7.91745 11.525C6.49762 11.525 5.34662 12.676 5.34662 14.0959C5.34661 15.5157 6.49762 16.6667 7.91745 16.6667C9.33728 16.6667 10.4883 15.5157 10.4883 14.0959C10.4883 12.676 9.33728 11.525 7.91745 11.525Z" stroke-width="1.5"/>
                        </svg>
                        Filtros
                    </button>
                @endif
            </div>
        </div> {{-- 🛠️ Matamos o </div> fantasma e fechamos a div do cabeçalho corretamente aqui! --}}

        {{-- Formulário de Filtros Dinâmicos --}}
        @if(isset($filtros) && trim($filtros) !== '')
            <form id="form-filtros-{{ $id }}" {{ $attributes->merge(['method' => 'GET'])->only(['action', 'method']) }} class="hidden grid grid-cols-12 gap-2 items-end mb-4">
                @if(strtoupper($attributes->get('method', 'GET')) !== 'GET')
                    @csrf
                @endif
                {{ $filtros }}
            </form>
        @endif

        {{-- Container da Tabela com Rolagem --}}
        <div class="max-w-full overflow-x-auto custom-scrollbar">
            {{-- 
               ⚡ Aplicamos border-separate para consertar a renderização das bordas 
               com elementos colados (sticky) e isolamos o container com a altura do JS.
            --}}
            <div id="container-tabela-{{ $id }}" class="overflow-y-auto border-separate" style="border-spacing: 0;">
                <table class="min-w-full border-collapse">
                    {{-- 
                       ⚡ Para o cabeçalho fixar na rolagem, o <thead> ganha 'sticky top-0 z-10' 
                       e uma cor sólida para impedir que o conteúdo do body passe visível por trás.
                    --}}
                    <thead class="sticky top-0 z-10 bg-gray-50 dark:bg-gray-900 border-b border-gray-200 dark:border-gray-700">
                        <tr>
                            {{ $thead }}
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                        {{ $body }}
                    </tbody>
                </table>
            </div>

            {{-- Área de Paginação Injetada --}}
            @if(isset($pagination))
                <div class="py-4 border-t border-gray-200 dark:border-white/[0.05]">
                    {{ $pagination }}
                </div>
            @endif
        </div>

    </div>
</div>

<script>
    (function() {
        const id = "{{ $id }}";
        const hClosed = "calc(100vh - {{ $heightClosed }})";
        const hOpened = "calc(100vh - {{ $heightOpened }})";

        const btn = document.getElementById('btn-filtros-' + id);
        const form = document.getElementById('form-filtros-' + id);
        const container = document.getElementById('container-tabela-' + id);

        if (!form) {
            if (container) {
                container.style.height = hClosed;
            }
            return; 
        }

        function ajustar(escondido) {
            container.style.height = escondido ? hClosed : hOpened;
        }

        const estado = localStorage.getItem('filtro_aberto_' + id);

        if (estado === 'false') {
            form.classList.add('hidden');
            ajustar(true);
        } else {
            form.classList.remove('hidden');
            ajustar(false);
        }

        if (btn) {
            btn.addEventListener('click', function() {
                form.classList.toggle('hidden');
                const hidden = form.classList.contains('hidden');
                localStorage.setItem('filtro_aberto_' + id, !hidden);
                ajustar(hidden);
            });
        }
    })();
</script>