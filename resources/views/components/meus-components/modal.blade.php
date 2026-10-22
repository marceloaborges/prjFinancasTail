@props([
    'id', // ID único para o modal (ex: 'create-cliente')
    'title' => '', // Titulo do modal
    'maxWidth' => 'md', // Prefixo do tamanho: sm, md, lg, xl, 2xl, etc.
    'align' => 'center', // 'center' ou 'top' / 'start'
    'titleClass' => '',
])

@php
    $shouldOpen = ($errors->any() || isset($clientePreSelecionado)) ? 'true' : 'false';

    $maxWidthClass = [
        'sm' => 'max-w-sm',
        'md' => 'max-w-md',
        'lg' => 'max-w-lg',
        'xl' => 'max-w-xl',
        '2xl' => 'max-w-2xl',
        '3xl' => 'max-w-3xl',
        '4xl' => 'max-w-4xl',
        '5xl' => 'max-w-5xl',
        '7xl' => 'max-w-7xl',
    ][$maxWidth] ?? 'max-w-md';

    $alignmentClass = match($align) {
        'top', 'start' => 'items-start',
        default => 'items-center',
    };
@endphp

<div
    x-data="{ open: false }"
    x-init="
        @if(old('validationReturn') === $id)
            open = true;
        @endif
    "
    @open-modal-{{ $id }}.window="open = true"
    @close-modal-{{ $id }}.window="open = false"
    class="inline-block"
>
    @if(isset($trigger))
        <div @click="open = true">
            {{ $trigger }}
        </div>
    @endif

    <template x-teleport="body">
        <div 
            x-show="open" 
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            class="fixed inset-0 z-999999 flex {{ $alignmentClass }} justify-center overflow-y-auto bg-black/70 p-4 backdrop-blur-sm"
            style="display: none;"
        >
            {{-- Card do Modal: Fundo escuro real e borda destacada --}}
            <div 
                @click.away="open = false"
                x-show="open"
                x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 scale-95"
                x-transition:enter-end="opacity-100 scale-100"
                x-transition:leave="transition ease-in duration-200"
                x-transition:leave-start="opacity-100 scale-100"
                x-transition:leave-end="opacity-0 scale-95"
                class="w-full {{ $maxWidthClass }} my-8 transform rounded-xl bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-700/80 p-6 shadow-2xl transition-all flex flex-col max-h-[calc(100vh-4rem)] text-gray-900 dark:text-gray-100"
            >
                {{-- Cabeçalho --}}
                <div class="flex items-center justify-between border-b border-gray-200 dark:border-gray-800 pb-3 mb-4 shrink-0">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white {{ $titleClass }}">
                        {{ $title }}
                    </h3>
                    <button 
                        type="button" 
                        @click="open = false" 
                        class="text-gray-400 hover:text-gray-600 dark:hover:text-white focus:outline-none cursor-pointer p-1 rounded hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors"
                    >
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                {{-- Conteúdo do Modal --}}
                <div class="text-sm overflow-y-auto custom-scrollbar flex-1 pr-1 text-gray-900 dark:text-gray-100">
                    {{ $slot }}
                </div>
            </div>
        </div>
    </template>
</div>