@props([
    'name',
    'label',
    'route',
    'value' => '',
    'text' => '',
    'placeholder' => 'Digite o nome ou documento para buscar...',
    'required' => false,
    'col' => 'col-span-12',
])

<div class="{{ $col }}"
     x-data="{
        selectedValue: @js($value ?? ''),
        selectedText: @js($text ?? ''),
        search: @js($text ?? ''),
        selectedId: @js($value ?? ''),
        results: [],
        open: false,
        loading: false,

        async fetchResults() {
         if (this.search.length < 2) {
             this.results = [];
             this.open = false;
             return;
         }

         this.loading = true;
         this.open = true;

         try {
             const response = await fetch(`${'{{ $route }}'}?q=${encodeURIComponent(this.search)}`);
             this.results = await response.json();
         } catch (error) {
             console.error('Erro na busca:', error);
             this.results = [];
         } finally {
             this.loading = false;
         }
        },

        select(item) {
         this.selectedId = item.id;
         // Define o campo de busca com Nome - Documento ao selecionar
         this.search = item.display;
         this.open = false;
        },

        clear() {
         this.selectedId = '';
         this.search = '';
         this.results = [];
         this.open = false;
        }
    }">

    @if($label)
        <label for="{{ $name }}" class="block text-sm font-medium text-gray-700 mb-1">
            {{ $label }} {{ $required ? '*' : '' }}
        </label>
    @endif

    {{-- Input hidden enviado no formulário --}}
    <input type="hidden" name="{{ $name }}" :value="selectedId" {{ $required ? 'required' : '' }}>

    <div class="relative">
        <div class="flex rounded-md shadow-sm">
            <input type="text" 
                   class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm px-3 py-2 border" 
                   placeholder="{{ $placeholder }}"
                   x-model="search"
                   @input.debounce.300ms="fetchResults()"
                   @focus="if(search.length >= 2) open = true"
                   @click.outside="open = false">
            
            <template x-if="selectedId">
                <button type="button" 
                        @click="clear()" 
                        class="inline-flex items-center px-3 rounded-r-md border border-l-0 border-gray-300 bg-gray-50 text-gray-500 hover:text-gray-700 text-sm">
                    &#x2715;
                </button>
            </template>
        </div>

        {{-- Lista de Resultados --}}
        <div class="absolute z-50 w-full mt-1 bg-white rounded-md shadow-lg border border-gray-200 max-h-60 overflow-y-auto" 
             x-show="open && results.length > 0" 
             x-cloak 
             :class="{ 'hidden': !open || results.length === 0 }">
            <ul class="divide-y divide-gray-100">
                <template x-for="item in results" :key="item.id">
                    <li>
                        <button type="button" 
                                class="w-full text-left px-4 py-2 hover:bg-indigo-50 focus:bg-indigo-50 transition duration-150 ease-in-out"
                                @click="select(item)">
                            {{-- Exibe Nome - Documento na lista --}}
                            <div class="font-semibold text-gray-800 text-sm" x-text="item.display"></div>
                        </button>
                    </li>
                </template>
            </ul>
        </div>

        {{-- Feedback de Carregamento --}}
        <div class="absolute z-50 w-full mt-1 bg-white rounded-md shadow-lg border border-gray-200 p-3 text-sm text-gray-500" 
             x-show="open && loading" 
             x-cloak 
             :class="{ 'hidden': !open || !loading }">
            Buscando...
        </div>
    </div>
</div>