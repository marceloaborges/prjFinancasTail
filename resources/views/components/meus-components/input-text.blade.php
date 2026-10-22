@props([
    'name',
    'label' => null,
    'id' => null,
    'type' => 'text',     
    'model' => null, // Passamos o objeto do banco aqui (ex: $cliente)
    'col' => 'col-span-12',
])

<div class="{{ $col }}">
    @if($label)
        <label for="{{ $id ?? $name }}" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
            {{ $label }}
        </label>
    @endif

    <input
        type="{{ $type }}"
        name="{{ $name }}"
        id="{{ $id ?? $name }}"
        
        {{-- ⚡ SÓ renderiza o value se o desenvolvedor NÃO passar um x-model na chamada do componente --}}
        @if(!$attributes->has('x-model'))
            value="{{ old($name, data_get($model, $name) ?? request($name)) }}"
        @endif

        {{-- ⚡ Merge limpo e dinâmico das classes Tailwind --}}
        {{ $attributes->merge([
            'class' => 'w-full h-[42px] px-3 py-2 border rounded-md shadow-sm focus:outline-none focus:ring-2 text-gray-900 dark:text-gray-100 placeholder-gray-400 ' . 
            ($attributes->has('readonly') || $attributes->has('disabled')
                ? 'bg-gray-100 dark:bg-gray-700 cursor-not-allowed text-gray-600 dark:text-gray-300 font-semibold' 
                : 'bg-white dark:bg-gray-800') . ' ' .
            ($errors->has($name) ? 'border-red-500 ring-red-500 focus:ring-red-500' : 'border-gray-300 dark:border-gray-700 focus:ring-gray-500'), 
            
            // Ativa condicionalmente as classes de texto
            'lowercase' => $type === 'email',
            'uppercase' => ($type !== 'password' && $type !== 'email') 
        ]) }}

        {{-- ⚡ AlpineJS: Comportamento de digitação inteligente --}}
        @if($type === 'email')
            @input="$el.value = $el.value.toLowerCase().trim();"
        @elseif($type !== 'password')
            @input="$el.value = $el.value.toUpperCase();"
        @endif
    >

    {{-- Validação global automatizada por componente --}}
    @error($name)
        <span class="text-xs text-red-500 mt-1 block font-medium" role="alert">
            {{ $message }}
        </span>
    @enderror
</div>