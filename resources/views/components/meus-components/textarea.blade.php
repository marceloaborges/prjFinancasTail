@props([
    'name',
    'label' => null,
    'placeholder' => '',
    'rows' => 4,                  // Altura padrão (4 linhas)
    'col' => 'col-span-12',       // Padrão ocupa a linha toda, mas aceita col-span-6, etc.
    'value' => null,              // Conteúdo para edição
    'required' => false
])

<div class="{{ $col }} flex flex-col gap-1.5">
    @if($label)
        <label for="{{ $name }}" class="text-sm font-medium text-gray-700 dark:text-gray-300">
            {{ $label }} @if($required)<span class="text-red-500">*</span>@endif
        </label>
    @endif

    {{-- 
        CRUCIAL: O conteúdo do textarea deve ficar colado entre as tags de abertura e fechamento.
        Qualquer quebra de linha ou espaço em branco que o PHP renderizar ali dentro virará espaço real no campo!
    --}}
    <textarea
        name="{{ $name }}"
        id="{{ $name }}"
        rows="{{ $rows }}"
        placeholder="{{ $placeholder }}"
        {{ $required ? 'required' : '' }}
        
        {{-- ⚡ Força o uppercase visual no Tailwind --}}
        {{ $attributes->merge([
            'class' => 'w-full rounded border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-black outline-none transition focus:border-blue-600 active:border-blue-600 disabled:cursor-default disabled:bg-whiter dark:border-form-strokedark dark:bg-form-input dark:text-white dark:focus:border-blue-500 uppercase'
        ]) }}

        {{-- ⚡ AlpineJS: Força uppercase na digitação em tempo real --}}
        @input="$el.value = $el.value.toUpperCase();"
    >@if(!$attributes->has('x-model')){{ old($name, $value) }}@endif</textarea>

    {{-- Exibição de erros de validação do Laravel --}}
    @error($name)
        <span class="text-xs font-medium text-red-500 mt-1" role="alert">
            {{ $message }}
        </span>
    @enderror
</div>