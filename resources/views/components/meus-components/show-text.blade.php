@props([
    'value' => '',
    'class' => 'text-sm text-body'
])

@php
    $content = $slot->isEmpty() ? $value : $slot;
    $content = is_string($content) || $content instanceof \Illuminate\Support\HtmlString ? trim((string)$content) : $content;
@endphp

{{-- Usar inline-block e removemos qualquer m-0/p-0 extra --}}
<div {{ $attributes->merge(['class' => "$class inline-block align-middle whitespace-pre-line"]) }}>{{ $content }}</div>