@props([
    'href'
])

<a href="{{ $href }}" 
   {{ $attributes->merge(['class' => 'group flex h-[42px] items-center justify-center gap-2 rounded border border-stroke dark:border-stroke-dark px-4 py-2 text-sm font-medium text-black dark:text-white hover:bg-gray-50 dark:hover:bg-gray-800 transition']) }}>
    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" class="text-gray-400 group-hover:-translate-x-0.5 transition-transform">
        <line x1="19" y1="12" x2="5" y2="12"></line>
        <polyline points="12 19 5 12 12 5"></polyline>
    </svg>
    Voltar
</a>