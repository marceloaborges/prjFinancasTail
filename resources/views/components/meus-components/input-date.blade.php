@props([
    'name',
    'label' => null,
    'id' => null,
    'model' => null, // Objeto do banco
    'value' => null,
    'col' => 'col-span-12 md:col-span-6 lg:col-span-4 xl:col-span-3'
])

@php
    // Obtém o valor do fallback (Old -> Model -> Value -> Request)
    $rawVal = old($name, data_get($model, $name) ?? $value ?? request($name));

    // Formata com segurança para Y-m-d exigido pelo input[type="date"]
    $formattedVal = $rawVal ? \Carbon\Carbon::parse($rawVal)->format('Y-m-d') : '';
@endphp

<div class="{{ $col }}">
    @if($label)
        <label for="{{ $id ?? $name }}" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
            {{ $label }}
        </label>
    @endif

    <input 
        type="date" 
        name="{{ $name }}" 
        id="{{ $id ?? $name }}" 
        value="{{ $formattedVal }}"
        onclick="this.showPicker && this.showPicker()"
        {{ $attributes->merge([
            'class' => 'w-full h-[42px] px-3 py-2 border rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-gray-500 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 transition cursor-pointer ' . 
            ($errors->has($name) ? 'border-red-500 ring-red-500' : 'border-gray-300 dark:border-gray-700')
        ]) }}
    >

    @error($name)
        <span class="text-xs text-red-500 mt-1 block font-medium" role="alert">
            {{ $message }}
        </span>
    @enderror
</div>