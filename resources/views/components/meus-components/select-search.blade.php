@props([
    'name',
    'label' => null,
    'id' => null,
    'options' => [],
    'placeholder' => 'Selecione...',
    'selected' => null,
    'model' => null,
    'col' => 'col-span-12',
    'alpineModel' => null,
    'alpineOptions' => null,
])

@php
    $id = $id ?? $name;
    $valorInicial = old($name, data_get($model, $name) ?? $selected ?? request($name));
@endphp

<div class="{{ $col }}" 
    x-data="{
        open: false,
        search: '',
        value: '{{ $valorInicial }}',
        label: '{{ $placeholder }}',

        get items() {
            @if($alpineOptions)
                let list = {};
                const sourceData = typeof {{ $alpineOptions }} !== 'undefined' ? {{ $alpineOptions }} : [];
                if (Array.isArray(sourceData)) {
                    sourceData.forEach(item => { 
                        list[item.id] = item.descricao || item.nome; 
                    });
                }
                return list;
            @else
                return {{ json_encode($options) }};
            @endif
        },

        init() {
            @if($alpineModel)
                this.value = {{ $alpineModel }} || '{{ $valorInicial }}';
                this.$watch('{{ $alpineModel }}', val => {
                    this.value = val;
                    this.updateLabel();
                });
            @endif

            @if($alpineOptions)
                this.$watch('{{ $alpineOptions }}', () => {
                    this.updateLabel();
                });
            @endif

            this.updateLabel();
        },

        updateLabel() {
            if (this.value && this.items[this.value]) {
                this.label = this.items[this.value];
            } else {
                this.label = '{{ $placeholder }}';
            }
        },

        toggleOpen() {
            this.open = !this.open;
            if (this.open) {
                this.$nextTick(() => {
                    this.$refs.searchInput.focus();
                });
            }
        },

        get filteredOptions() {
            if (this.search === '') return this.items;
            let filtered = {};
            for (let [key, text] of Object.entries(this.items)) {
                if (String(text).toLowerCase().includes(this.search.toLowerCase())) {
                    filtered[key] = text;
                }
            }
            return filtered;
        },

        selectOption(key, text) {
            this.value = key;
            this.label = text;
            this.open = false;
            this.search = '';

            @if($alpineModel)
                {{ $alpineModel }} = key;
            @endif
        }
    }"
    x-modelable="value" {{-- 👈 Adicione esta linha --}}
    x-effect="updateLabel()" {{-- 👈 Adicione para forçar a atualização da label quando o valor mudar --}}
    @click.outside="open = false"
>
    @if($label)
        <label for="{{ $id }}" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
            {{ $label }}
        </label>
    @endif

    <input type="hidden" name="{{ $name }}" :value="value">

    <div class="relative">
        {{-- Botão Principal --}}
        <button
            type="button"
            @click="toggleOpen()"
            class="w-full h-[42px] px-3 py-2 text-left border rounded-md shadow-sm flex items-center justify-between bg-white dark:bg-gray-800 border-gray-300 dark:border-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-500"
        >
            <span class="block truncate !text-gray-900 dark:!text-white font-normal" x-text="label"></span>
            
            <svg class="h-4 w-4 text-gray-400 dark:text-gray-300 transition-transform duration-200" :class="{ 'rotate-180': open }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
            </svg>
        </button>

        {{-- Dropdown + Input Pesquisa --}}
        <div
            x-show="open"
            x-cloak
            x-transition:enter="transition ease-out duration-100"
            x-transition:enter-start="transform opacity-0 scale-95"
            x-transition:enter-end="transform opacity-100 scale-100"
            class="absolute z-50 mt-1 w-full bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-md shadow-xl max-h-60 overflow-hidden flex flex-col"
        >
            {{-- Input de Busca --}}
            <div class="p-2 border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900">
                <input
                    x-ref="searchInput"
                    type="text"
                    x-model="search"
                    class="w-full px-3 py-2 bg-white dark:bg-gray-950 !text-gray-900 dark:!text-white placeholder-gray-400 dark:placeholder-gray-500 border border-gray-300 dark:border-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-500 rounded-md text-sm"
                    placeholder="Buscar..."
                    @keydown.escape="open = false"
                />
            </div>

            {{-- Opções --}}
            <ul class="overflow-y-auto max-h-48 py-1 text-sm">
                <template x-for="(text, key) in filteredOptions" :key="key">
                    <li 
                        @click="selectOption(key, text)" 
                        class="px-3 py-2 cursor-pointer hover:bg-gray-100 dark:hover:bg-gray-700/70 !text-gray-900 dark:!text-white flex items-center justify-between"
                    >
                        <span x-text="text"></span>
                        <span x-show="value == key" class="text-emerald-500 dark:text-emerald-400 font-bold">✓</span>
                    </li>
                </template>

                <li x-show="Object.keys(filteredOptions).length === 0" class="px-3 py-2 text-xs text-gray-400 dark:text-gray-500 text-center">
                    Nenhum resultado encontrado.
                </li>
            </ul>
        </div>
    </div>

    @error($name)
        <span class="text-xs text-red-500 mt-1 block font-medium" role="alert">
            {{ $message }}
        </span>
    @enderror
</div>