@extends('layouts.app')

@section('content')
<div class="p-6 bg-white dark:bg-boxdark rounded-sm border border-stroke dark:border-strokedark shadow-default">
    <form action="{{ route('painel.users.update', $usuario->id) }}" method="POST">
        @csrf
        @method('PUT')

        {{-- Inclui o mesmo formulário compartilhando o objeto $usuario --}}
        @include('painel.user.form')

        <div class="flex items-center justify-end gap-3 mt-6 pt-4 border-t border-stroke dark:border-strokedark">
            <x-meus-components.btn-cancelar :href="route('painel.users.show', $usuario->id)" />
            <x-meus-components.btn-atualizar label="Atualizar Dados" />
        </div>
    </form>
</div>
@endsection