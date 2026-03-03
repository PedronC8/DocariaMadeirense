@extends('layouts.app')
@section('content')

<div class="row mb-2 mb-xl-3">
    <div class="col-auto d-none d-sm-block">
        <h1><strong>Novo Utilizador</strong></h1>
    </div>

    <div class="col-auto ms-auto text-end mt-n1">
         <a href="{{ route('users.index') }}" class="btn btn-primary">Voltar </a>
    </div>
</div>


		   <!-- Formulário de adicionar cliente -->
<form method="POST" action="{{ route('users.store') }}" enctype="multipart/form-data">
    @csrf
    @method('POST')
      <div class="mb-3">
        <label for="name" class="form-label">Nome do utilizador</label>
        <input required name="name" type="text" class="form-control" id="username" >
        @error('username')
            <p class="text-danger">Erro no nome do utilizador</p>
        @enderror
    </div>

    <div class="mb-3">
        <label for="role" class="form-label">Tipo de utilizador</label>
        <input  name="role" type="text" class="form-control" id="email" >
        @error('email')
            <p class="text-danger">Erro no tipo de utilizador</p>
        @enderror
    </div>

	<div class="mb-3">
        <label for="email" class="form-label">Email do utilizador</label>
        <input  name="email" type="text" class="form-control" id="email" >
        @error('email')
            <p class="text-danger">Erro no email do utilizador</p>
        @enderror
    </div>

	<div class="mb-3">
        <label for="password" class="form-label">Password do utilizador</label>
        <input  name="password" type="password" class="form-control" id="password" >
        @error('password')
            <p class="text-danger">Erro na password do utilizador</p>
        @enderror
    </div>


    

    <button type="submit" class="btn btn-success">Adicionar Utilizador</button>
</form>


@endsection