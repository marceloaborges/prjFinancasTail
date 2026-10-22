@extends('layouts.app')

@section('content')

    <x-meus-components.index 
        title="Usuários" 
        id="usuarios_list" 
        heightClosed="280px" 
        heightOpened="362px"
    >

    <x-slot:acoes>
        <x-meus-components.btn-link 
            :href="route('painel.users.create')" 
            label="Novo" 
        />
    </x-slot:acoes>


    @include('painel.user.search')

    @include('painel.user.table')

    </x-meus-components.index>

@endsection