@extends('layouts.app')
@section('content')

<h1>Detalhes do cliente</h1>

<table class="table">
  <thead>
    <tr>
      <th>Nome</th>
		<th>Telefone</th>
		<th>Última Encomenda</th>
		<th>Total Encomendas</th>
    <th>Ações</th>
      
    </tr>
  </thead>
  <tbody>
  

 
      <tr>
        <td>{{ $client->name }}</td>
        <td>{{ $client->contact }}</td>
        <td>{{ $client->last_order }}</td>
        <td>{{ $client->total_orders }}</td>
        
        
        <td>
          <a href="{{ route('clients.edit', $client->id) }}" class="btn btn-warning ">Editar</a>
          <form action="{{ route('clients.destroy', $client->id) }}" method="POST" class="d-inline">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-danger">Remover</button>
          </form>
        </td>
        
      </tr>
  
  </tbody>
</table>
@endsection