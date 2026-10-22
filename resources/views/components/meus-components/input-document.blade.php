@props([
    'name',
    'label' => null,
    'id' => null,
    'model' => null,
    'col' => 'col-span-12 md:col-span-6'
])

{{-- 
    ⚡ Separando os atributos:
    Pegamos o 'x-model' que foi passado de fora (se existir) para aplicar na div certa.
--}}
@php
    $xModel = $attributes->get('x-model');
    // Removemos o x-model dos atributos que vão para o input para não dar conflito
    $inputAttributes = $attributes->except('x-model'); 
@endphp

<div class="{{ $col }}">
    @if($label)
        <label for="{{ $id ?? $name }}" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
            {{ $label }}
        </label>
    @endif

    <div 
        x-data="{
            rawVal: '{{ old($name, data_get($model, $name) ?? request($name)) }}',
            mask(value) {
                if (!value) return '';
                let v = value.replace(/\D/g, '');
                
                if (v.length <= 11) {
                    v = v.replace(/(\d{3})(\d)/, '$1.$2');
                    v = v.replace(/(\d{3})(\d)/, '$1.$2');
                    v = v.replace(/(\d{3})(\d{1,2})$/, '$1-$2');
                } else {
                    v = v.replace(/^(\d{2})(\d)/, '$1.$2');
                    v = v.replace(/^(\d{2})\.(\d{3})(\d)/, '$1.$2.$3');
                    v = v.replace(/\.(\d{3})(\d)/, '.$1/$2');
                    v = v.replace(/(\d{4})(\d)/, '$1-$2');
                }
                return v.substring(0, 18);
            }
        }"
        {{-- ⚡ Se existir x-model externo, aplica a sincronização aqui no wrapper --}}
        @if($xModel)
            x-modelable="rawVal"
            x-model="{{ $xModel }}"
        @endif
        x-init="
            rawVal = mask(rawVal);
            $watch('rawVal', value => {
                rawVal = mask(value);
            });
        "
    >
        <input
            type="text"
            name="{{ $name }}"
            id="{{ $id ?? $name }}"
            x-model="rawVal"
            @input="rawVal = mask($event.target.value); $dispatch('change-doc', rawVal)"
            @blur="$dispatch('blur-doc', rawVal)"
            {{ $inputAttributes->merge([
                'class' => 'w-full h-[42px] px-3 py-2 border rounded-md shadow-sm focus:outline-none focus:ring-2 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 placeholder-gray-400 ' . 
                ($errors->has($name) ? 'border-red-500 ring-red-500 focus:ring-red-500' : 'border-gray-300 dark:border-gray-700 focus:ring-gray-500'),
                'placeholder' => 'CPF ou CNPJ'
            ]) }}
        >
    </div>

    @error($name)
        <span class="text-xs text-red-500 mt-1 block font-medium" role="alert">
            {{ $message }}
        </span>
    @enderror
</div>