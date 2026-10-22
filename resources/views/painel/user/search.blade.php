{{-- 1. CAMPOS DO FORMULÁRIO SEARCH --}}
<x-slot:filtros>
    <div class="col-span-12 md:col-span-6 lg:col-span-4 xl:col-span-3">
        <label class="block text-sm font-medium text-gray-700 mb-1">Nome</label>
        <input type="text" name="nome" value="{{ request('nome') }}" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-gray-500 focus:border-gray-500" placeholder="Digite seu nome">
    </div>
    
    {{-- Botão de Lupa Nativo --}}
    <button type="submit" class="col-span-auto h-[42px] aspect-square flex items-center justify-center bg-gray-400 text-white rounded-md hover:bg-gray-500 transition">
        <svg xmlns="http://w3.org" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
        </svg>
    </button>
</x-slot:filtros>