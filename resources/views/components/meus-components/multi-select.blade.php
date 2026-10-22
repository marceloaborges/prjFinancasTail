@props([
    'name',
    'label' => null,
    'id' => null,
    'options' => [], 
    'model' => null,       
    'relation' => null,    
    'selected' => [],      
    'placeholder' => 'Selecione as opções...',
    'col' => 'col-span-12 md:col-span-6 lg:col-span-4 xl:col-span-3'
])

@php
    $valoresPreDefinidos = old($name);

    if (!$valoresPreDefinidos && isset($model)) {
        if ($relation && method_exists($model, $relation)) {
            $valoresPreDefinidos = $model->$relation->pluck('id')->toArray();
        } else {
            $valoresPreDefinidos = data_get($model, $name, []);
        }
    }

    if (!$valoresPreDefinidos) {
        $valoresPreDefinidos = request($name, $selected);
    }

    // Garante array limpo e converte todos os valores para String no PHP para evitar quebra no JS
    $valoresFinais = is_array($valoresPreDefinidos) 
        ? array_map('strval', array_values($valoresPreDefinidos)) 
        : ($valoresPreDefinidos ? [strval($valoresPreDefinidos)] : []);
@endphp

{{-- ⚡ DEIXAR O SCRIPT NO TOPO GARANTE QUE O ALPINE ENCONTRE A FUNÇÃO A TEMPO --}}
<script>
    if (typeof multiSelect !== 'function') {
        window.multiSelect = function(config) {
            return {
                options: config.options || [],
                selected: [],
                open: false,
                search: '',
                init() {
                    // Carrega os valores já selecionados convertendo para string de forma segura
                    this.selected = config.selectedValues ? Object.values(config.selectedValues).map(v => String(v)) : [];
                },
                get filteredOptions() {
                    return this.options.filter(option => 
                        option.label.toLowerCase().includes(this.search.toLowerCase())
                    );
                },
                toggleOption(value) {
                    value = String(value);
                    if (this.isSelected(value)) {
                        this.removeOption(value);
                    } else {
                        this.selected.push(value);
                    }
                },
                removeOption(value) {
                    value = String(value);
                    this.selected = this.selected.filter(v => v !== value);
                },
                isSelected(value) {
                    return this.selected.includes(String(value));
                },
                getLabel(value) {
                    const opt = this.options.find(o => String(o.value) === String(value));
                    return opt ? opt.label : value;
                }
            }
        }
    }
</script>

{{-- HTML DO COMPONENTE --}}
<div class="{{ $col }}" 
     x-data="multiSelect({ 
        options: {{ json_encode($options) }}, 
        selectedValues: {{ json_encode($valoresFinais) }} 
     })">
    
    <template x-for="value in selected" :key="value">
        <input type="hidden" name="{{ $name }}[]" :value="value">
    </template>

    @if($label)
        <label for="{{ $id ?? $name }}" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
            {{ $label }}
        </label>
    @endif

    <div class="relative z-20">
        <div @click="open = !open" 
             {{ $attributes->merge(['class' => 'w-full min-h-[42px] px-2 py-1.5 border rounded-md shadow-sm bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 flex flex-wrap items-center gap-1.5 cursor-pointer focus-within:ring-2 focus-within:ring-gray-500 ' . ($errors->has($name) ? 'border-red-500 ring-red-500' : 'border-gray-300 dark:border-gray-700')]) }}>
            
            <span x-show="selected.length === 0" class="text-gray-400 text-sm pl-1">{{ $placeholder }}</span>

            <template x-for="(value, index) in selected" :key="value">
                <div class="flex items-center gap-1 text-sm bg-gray-100 dark:bg-gray-800 text-gray-800 dark:text-gray-100 px-2 py-0.5 rounded-md border border-gray-200 dark:border-gray-600">
                    <span x-text="getLabel(value)"></span>
                    <button type="button" @click.stop="removeOption(value)" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-100 focus:outline-none">
                        <svg class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            </template>
        </div>

        <div x-show="open" 
             @click.away="open = false" 
             class="absolute left-0 mt-1 w-full max-h-60 overflow-y-auto rounded-md bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 shadow-lg z-50 p-1 custom-scrollbar" style="display: none;">
            
            <input type="text" 
                   x-model="search" 
                   @click.stop 
                   placeholder="Pesquisar..." 
                   class="w-full mb-1 px-3 py-1.5 text-sm border border-gray-200 dark:border-gray-700 rounded bg-gray-50 dark:bg-gray-800 dark:text-gray-100 focus:outline-none focus:ring-1 focus:ring-gray-500">

            <template x-for="option in filteredOptions" :key="option.value">
                <div @click="toggleOption(option.value)" 
                     class="flex items-center justify-between px-3 py-2 text-sm rounded cursor-pointer transition"
                     :class="isSelected(option.value) ? 'bg-gray-100 dark:bg-gray-700 dark:text-gray-100 font-semibold' : 'hover:bg-gray-50 dark:hover:bg-gray-700/50 dark:text-gray-100'">
                    <span x-text="option.label"></span>
                    <svg x-show="isSelected(option.value)" class="h-4 w-4 text-gray-600 dark:text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                </div>
            </template>

            <div x-show="filteredOptions.length === 0" class="text-gray-400 text-sm p-3 text-center">
                Nenhum resultado encontrado.
            </div>
        </div>
    </div>

    @error($name)
        <span class="text-xs text-red-500 mt-1 block font-medium" role="alert">
            {{ $message }}
        </span>
    @enderror
</div>