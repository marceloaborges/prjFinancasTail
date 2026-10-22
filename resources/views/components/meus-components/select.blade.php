@props([
    'name',
    'label' => null,
    'id' => null,
    'model' => null, // Objeto do banco (ex: $cliente)
    'selected' => null, // Para passar um valor fixo se necessário
    'col' => 'col-span-12',
])

@php
    // Define o valor que deve ficar selecionado automaticamente
    $valorAtivo = old($name, data_get($model, $name) ?? $selected ?? request($name));
@endphp

<div class="{{ $col }}">
    @if($label)
        <label for="{{ $id ?? $name }}" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
            {{ $label }}
        </label>
    @endif

    <select
        name="{{ $name }}"
        id="{{ $id ?? $name }}"
        {{ $attributes->merge(['class' => 'w-full h-[42px] px-3 py-2 border rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-gray-500 bg-white dark:bg-gray-800 text-gray-600 dark:text-gray-100 ' . ($errors->has($name) ? 'border-red-500 ring-red-500' : 'border-gray-300 dark:border-gray-700')]) }}
    >
        {{ $slot }}
    </select>

    @error($name)
        <span class="text-xs text-red-500 mt-1 block font-medium" role="alert">
            {{ $message }}
        </span>
    @enderror
</div>