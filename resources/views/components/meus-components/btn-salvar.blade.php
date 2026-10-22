@props([
    'label' => 'Salvar' // Texto padrão, pode sobrescrever para 'Salvar Usuário', 'Salvar Cargo', etc.
])

<button type="submit" 
        class="flex h-[42px] items-center justify-center gap-2 rounded bg-blue-600 px-4 py-2 font-medium text-white hover:bg-blue-700 active:scale-98 transition shadow-sm cursor-pointer">
    {{-- Ícone de disquete/salvamento limpo --}}
    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
        <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"></path>
        <polyline points="17 21 17 13 7 13 7 21"></polyline>
        <polyline points="7 3 7 8 15 8"></polyline>
    </svg>
    {{ $label }}
</button>