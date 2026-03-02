@extends('layouts.app')
@section('content')

<div class="row mb-2 mb-xl-3">
    <div class="col-auto d-none d-sm-block">
        <h3><strong>{{$user->name}}</strong></h3>
    </div>

    <div class="col-auto ms-auto text-end mt-n1">
        <a href="{{ route('users.index') }}" class="btn btn-primary">
             Voltar atrás
        </a>
    </div>
</div>

<div class="card">

<div class="card-body p-0">

<table class="table table-borderless table-sm mb-0">
  <thead style="background:#e2e8f0;color:#0f172a;border-bottom:2px solid #cbd5e1;">
    <tr>
      <th>Nome</th>
		<th>Email</th>
		<th>Tipo de utilizador</th>
		<th>Password</th>
    <th>Ações</th>
      
    </tr>
  </thead>
  <tbody>
  

 
      <tr>
        <td>{{ $user->name }}</td>
        <td>{{ $user->email}}</td>
        <td>{{ $user->role ? $user->role : '---'}}</td>
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


</div>

</div>


@endsection