@props([
    'label' => 'Atualizar' // Texto padrão
])

<button 
    type="submit" 
    {{ $attributes->merge([
        'class' => 'flex h-[42px] items-center justify-center gap-2 rounded bg-emerald-600 px-4 py-2 font-medium text-white hover:bg-emerald-700 active:scale-98 transition shadow-sm cursor-pointer'
    ]) }}
>
    {{-- Ícone de rotação/sincronismo (Refresh) --}}
    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
        <path d="M21.5 2v6h-6M21.34 15.57a10 10 0 1 1-.57-8.38l5.67-5.67"></path>
    </svg>
    {{ $label }}
</button>