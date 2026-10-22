@props([
    'card' => true,
    'striped' => false,
])

<div @class([
    'w-full overflow-hidden text-sm',
    'border border-stroke dark:border-strokedark bg-white dark:bg-boxdark shadow-default mt-2' => $card,
])>
    <div class="max-w-full overflow-x-auto">
        <table class="w-full table-auto text-left border-collapse">
            {{-- Cabeçalho da Tabela --}}
            <thead class="sticky top-0 z-10 bg-gray-50 dark:bg-gray-900 border-b border-gray-200 dark:border-gray-700">
                <tr>
                    {{ $thead }}
                </tr>
            </thead>

            {{-- Corpo da Tabela --}}
            <tbody class="divide-y divide-stroke dark:divide-strokedark text-gray-700 dark:text-gray-300">
                {{ $slot }}
            </tbody>
        </table>
    </div>
</div>