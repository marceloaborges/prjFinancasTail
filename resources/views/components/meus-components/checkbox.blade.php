@props([
    'name',
    'label',
    'id' => null,
    'model' => null, // Objeto do banco (ex: $cliente)
    'checked' => false, // Fallback manual se necessário
    'col' => 'col-span-12 md:col-span-6 lg:col-span-4 xl:col-span-3'
])

@php
    // Define se o checkbox deve iniciar marcado com base na ordem de prioridade
    $isInputChecked = old($name) 
        ? true 
        : (isset($model) ? data_get($model, $name) == 1 : ($checked || request($name) == '1'));
@endphp

<div class="{{ $col }} flex flex-col justify-center h-[42px] pt-5">
    <div class="flex items-center">
        <div class="flex items-center h-5">
            <input 
                type="checkbox" 
                name="{{ $name }}" 
                id="{{ $id ?? $name }}" 
                value="1"
                {{ $isInputChecked ? 'checked' : '' }}
                {{ $attributes->merge(['class' => 'h-4 w-4 rounded text-gray-600 focus:ring-gray-500 bg-white dark:bg-gray-800 transition ' . ($errors->has($name) ? 'border-red-500 ring-red-500' : 'border-gray-300 dark:border-gray-700')]) }}
            >
        </div>
        <div class="ml-3 text-sm">
            <label for="{{ $id ?? $name }}" class="font-medium text-gray-700 dark:text-gray-300 select-none cursor-pointer">
                {{ $label }}
            </label>
        </div>
    </div>

    {{-- Exibe erro de validação específico para o Checkbox --}}
    @error($name)
        <span class="text-xs text-red-500 mt-1 block font-medium" role="alert">
            {{ $message }}
        </span>
    @enderror
</div>