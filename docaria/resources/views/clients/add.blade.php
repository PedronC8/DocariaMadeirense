@extends('layouts.app')
@section('content')

<div class="row mb-2 mb-xl-3">
    <div class="col-auto d-none d-sm-block">
        <h1><strong>Novo Cliente</strong></h1>
    </div>

    <div class="col-auto ms-auto text-end mt-n1">
         <a href="{{ route('clients.index') }}" class="btn btn-primary">Voltar </a>
    </div>
</div>



		   <!-- Formulário de adicionar cliente -->
<form method="POST" action="{{ route('clients.store') }}" enctype="multipart/form-data">
    @csrf
    @method('POST')
      <div class="mb-3">
        <label for="name" class="form-label">Nome do cliente</label>
        <input required name="name" type="text" class="form-control" id="name" value="{{ old('name') }}">
        @error('name')
            <p class="text-danger">Erro no nome do cliente</p>
        @enderror
    </div>

    <div class="mb-3">
        <label for="idFaturacao" class="form-label">ID Faturação</label>
        <input name="idFaturacao" type="text" class="form-control" id="idFaturacao" value="{{ old('idFaturacao') }}">
        @error('idFaturacao')
            <p class="text-danger">Erro no ID Faturação</p>
        @enderror
    </div>

	<div class="mb-3">
        <label for="nif" class="form-label">Nif do cliente</label>
        <input  name="nif" type="text" class="form-control" id="nif" value="{{ old('nif') }}">
        @error('nif')
            <p class="text-danger">Erro no nif do cliente</p>
        @enderror
    </div>

	<div class="mb-3">
        <label for="contact" class="form-label">Contacto do cliente</label>
        <input  name="contact" type="text" class="form-control" id="contact" value="{{ old('contact') }}">
        @error('contact')
            <p class="text-danger">Erro no contacto do cliente</p>
        @enderror
    </div>

	<div class="mb-3">
        <label for="address" class="form-label">Morada do cliente</label>
        <input  name="address" type="text" class="form-control" id="address" value="{{ old('address') }}">
        @error('address')
            <p class="text-danger">Erro na morada do cliente</p>
        @enderror
    </div>

    

    <button type="submit" class="btn btn-success">Adicionar Cliente</button>
</form>


@endsection
