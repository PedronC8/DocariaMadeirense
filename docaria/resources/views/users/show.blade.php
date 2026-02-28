@extends('layouts.app')
@section('content')

<h1>Detalhes do cliente</h1>

<table class="table">
  <thead>
    <tr>
      <th>Nome</th>
		<th>Email</th>
		<th>Data de Criação</th>
		<th>Password</th>
    <th>Ações</th>
      
    </tr>
  </thead>
  <tbody>
  

 
      <tr>
        <td>{{ $user->name }}</td>
        <td>{{ $user->email}}</td>
        <td>{{ $user->created_at }}</td>
        <td>{{ $user->password }}</td>
        
        
        <td>
          <a href="{{ route('users.edit', $user->id) }}" class="btn btn-warning ">Editar</a>
          <form action="{{ route('users.destroy', $user->id) }}" method="POST" class="d-inline">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-danger">Remover</button>
          </form>
        </td>
        
      </tr>
  
  </tbody>
</table>
@endsection