@extends('layouts.app')
@section('content')

	<x-meus-components.index 
	    title="Logs" 
	    id="logs" 
	    heightClosed="280px" 
	    heightOpened="362px"
	    action="{{ route('painel.logs.index') }}" 
	    method="GET"
	>
		{{-- Search --}}
		@include('painel.log.search')

		@include('painel.log.table')  

	</x-meus-components.index>

@endsection