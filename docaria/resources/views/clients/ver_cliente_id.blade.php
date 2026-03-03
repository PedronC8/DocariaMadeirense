@extends('layouts.app')
@section('content')

<div class="row mb-2 mb-xl-3">
    <div class="col-auto d-none d-sm-block">
        <h3><strong>{{$client->name}}</strong></h3>
    </div>

    <div class="col-auto ms-auto text-end mt-n1">
        <a href="{{ route('clients.index') }}" class="btn btn-primary">
             Voltar 
        </a>
    </div>
</div>

<div class="card">
  <div class="card-body p-0">
    <table class="table table-borderless table-sm mb-0 " >
  <thead style="background:#e2e8f0;color:#0f172a;border-bottom:2px solid #cbd5e1;">
    <tr>
      <th>Nome</th>
		<th>Telefone</th>
		<th>Nif</th>
		<th>Morada</th>
    <th>Ações</th>
      
    </tr>
  </thead>
  <tbody>
  

 
      <tr >
        <td>{{ $client->name }}</td>
        <td>{{ $client->nif ? $client->nif : '---' }}</td>
        <td>{{ $client->contact ? $client->contact : '---' }}</td>
        <td>{{ $client->address ? $client->address : '---' }}</td>
        
        
        <td>
          <a href="{{ route('clients.edit', $client->id) }}" class="btn btn-warning "><i class="align-middle" data-feather="edit"></i></a>
          <form action="{{ route('clients.destroy', $client->id) }}" method="POST" class="d-inline">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-danger"><i class="align-middle" data-feather="trash-2"></i></button>
          </form>
        </td>
        
      </tr>
  
  </tbody>


  </div>

</div>


</table>
@endsection