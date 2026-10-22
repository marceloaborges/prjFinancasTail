@extends('layouts.app')

@section('content')
<div class="p-6 bg-white dark:bg-boxdark rounded-sm border border-stroke dark:border-gray-800 dark:bg-white/[0.03] sm:px-6">
    <form action="{{ route('painel.users.store') }}" method="POST">
        @csrf

        {{-- Inclui o formulário limpo e componentizado --}}
        @include('painel.user.form')

        <div class="flex items-center justify-end gap-3 mt-6 pt-4 border-t border-stroke dark:border-strokedark">
            <x-meus-components.btn-cancelar :href="route('painel.users.index')" />
            <x-meus-components.btn-salvar label="Salvar Usuário" />
        </div>
    </form>
</div>
@endsection