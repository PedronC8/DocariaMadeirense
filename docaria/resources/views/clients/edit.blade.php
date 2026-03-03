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

		   <!-- Formulário de editar cliente -->
<form method="POST" action="{{ route('clients.update',  $client->id) }}" enctype="multipart/form-data">
    @csrf
    @method('PUT')
      <div class="mb-3">
        <label for="name" class="form-label">Nome do cliente</label>
        <input required name="name" type="text" class="form-control" id="name" value="{{ $client->name }}">
        @error('username')
            <p class="text-danger">Erro no nome do cliente</p>
        @enderror
    </div>

	<div class="mb-3">
        <label for="nif" class="form-label">Nif do cliente</label>
        <input  name="nif" type="text" class="form-control" id="nif" value="{{ $client->nif }}">
        @error('nif')
            <p class="text-danger">Erro no nif do cliente</p>
        @enderror
    </div>

	<div class="mb-3">
        <label for="contact" class="form-label">Contacto do cliente</label>
        <input  name="contact" type="text" class="form-control" id="contact" value="{{ $client->contact }}">
        @error('contact')
            <p class="text-danger">Erro no contacto do cliente</p>
        @enderror
    </div>

	<div class="mb-3">
        <label for="address" class="form-label">Morada do cliente</label>
        <input  name="address" type="text" class="form-control" id="address" value="{{ $client->address }}">
        @error('address')
            <p class="text-danger">Erro na morada do cliente</p>
        @enderror
    </div>

    

    <button type="submit" class="btn btn-success">Editar Cliente</button>
</form>
		
@endsection