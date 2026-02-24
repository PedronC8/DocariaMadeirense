
@extends('layouts.app')
@section('content')



<body>

<h1 class="fw-bold">Clientes</h1>



<div class="d-flex justify-content-between align-items-center mt-4 mb-4">

    <div class="d-flex align-items-center gap-2">
        <input type="text" class="form-control" style="width:250px;" placeholder="Pesquisar por nome, telefone...">
        
        <select class="form-select" style="width:180px;" aria-label="Default select example">
			 <option selected>Vend: Todos</option>
  <option value="1">One</option>
  <option value="2">Two</option>
  <option value="3">Three</option>
		</select>
        
        <select class="form-select" style="width:180px;">
			<option selected>Data: Hoje</option>
  <option value="1">One</option>
  <option value="2">Two</option>
  <option value="3">Three</option>
		</select>
    </div>

    <div>
        <a href="{{ route('clients.create') }}" class="btn btn-primary"><i class="align-middle me-2 " data-feather="plus"></i>Novo Cliente</a>
    </div>

</div>






<table class="table" >
	<thead style="background-color:  #f5f7fb;">
		<th>Nome</th>
		<th>Telefone</th>
		<th>Última Encomenda</th>
		<th>Total Encomendas</th>
		<th>Ações</th>

	</thead>
		
		@foreach($clients as $client)
		<tr>
			<td>{{ $client->name }}</td>
			<td>{{ $client->nif }}</td>
			<td>{{ $client->contact }}</td>
			<td>{{ $client->address }}</td>
			<td>
				<a href="{{ route('clients.show', $client->id) }}" class="btn btn-primary">Ver Detalhes</a>
			</td>
		</tr>
		@endforeach
	</table>

	<!-- Gera os links de paginação (Tailwind por padrão) -->
{{ $clients->links() }}
	
</body>
	



@endsection