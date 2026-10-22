@props([
    'name',
    'label',
    'model' => null,
    'checked' => false,
    'modelAlpine' => null,
    'col' => 'col-span-12',
    'disabled' => false
])

@php
    $isChecked = old($name, $model?->$name ?? $checked);
    $alpineVariable = $modelAlpine ?? $name;
@endphp

<div class="{{ $col }} flex flex-col justify-end pb-1">
    <label class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
        {{ $label }}
    </label>

    <div @if(!$modelAlpine) x-data="{ {{ $alpineVariable }}: {{ $isChecked ? 'true' : 'false' }} }" @endif class="flex items-center">
        
        {{-- Hidden Input para envio no form POST --}}
        <input type="hidden" name="{{ $name }}" :value="{{ $alpineVariable }} ? '1' : '0'">

        {{-- Botão do Toggle --}}
        <button 
            type="button"
            @disabled($disabled)
            @click.prevent="if ({{ $disabled ? 'true' : 'false' }}) return; {{ $alpineVariable }} = !{{ $alpineVariable }}; $dispatch('change')"
            :class="{{ $alpineVariable }} ? 'bg-emerald-500 dark:bg-emerald-600' : 'bg-rose-500 dark:bg-rose-600'"
            {{ $attributes->merge([
                'class' => 'relative inline-flex h-6 w-11 flex-shrink-0 rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 ' . 
                ($disabled ? 'opacity-50 cursor-not-allowed' : 'cursor-pointer')
            ]) }}
        >
            <span 
                aria-hidden="true" 
                :class="{{ $alpineVariable }} ? 'translate-x-5' : 'translate-x-0'"
                class="pointer-events-none inline-block h-5 w-5 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out"
            ></span>
        </button>

        {{-- Texto Sim / Não --}}
        <span class="ml-3 text-xs font-semibold uppercase tracking-wider transition-colors"
              :class="{{ $alpineVariable }} ? 'text-emerald-600 dark:text-emerald-400' : 'text-rose-600 dark:text-rose-400'">
            <span x-text="{{ $alpineVariable }} ? 'Sim' : 'Não'"></span>
        </span>
    </div>
    
    @error($name)
        <span class="text-xs text-danger mt-1" role="alert">{{ $message }}</span>
    @enderror
</div>