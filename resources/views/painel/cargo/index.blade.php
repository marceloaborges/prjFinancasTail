@extends('layouts.app')

@section('content')

    <x-meus-components.index 
        title="Cargos" 
        id="cargos" 
        heightClosed="235px" 
        heightOpened="320px"
        action="{{ route('painel.cargos.index') }}" 
        method="GET"
    >

        {{-- Create --}}
        @include('painel.cargo.create')

        {{-- Search --}}
        @include('painel.cargo.search')

        @include('painel.cargo.table')        

    </x-meus-components.index>

    @include('painel.cargo.edit-modal')

@endsection