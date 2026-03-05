@extends('layouts.app')
@section('content')

<div class="row mb-2 mb-xl-3">
    <div class="col-auto d-none d-sm-block">
        <h3><strong>{{$user->name}}</strong></h3>
    </div>

    <div class="col-auto ms-auto text-end mt-n1">
        <a href="{{ route('users.index') }}" class="btn btn-primary">
             Voltar 
        </a>
    </div>
</div>

		   <!-- Formulário de editar cliente -->
<form method="POST" action="{{ route('users.update',  $user->id) }}" enctype="multipart/form-data">
    @csrf
    @method('PUT')
      <div class="mb-3">
        <label for="name" class="form-label">Nome do utilizador</label>
        <input required name="name" type="text" class="form-control" id="name" value="{{ $user->name }}">
        @error('name')
            <p class="text-danger">Erro no nome do utilizador</p>
        @enderror
    </div>

    <div class="mb-3">
        <label for="role" class="form-label">Tipo de utilizador</label>
        <select name="role" class="form-select" id="role" required>
            <option value="">Selecione...</option>
            <option value="admin" {{ old('role', $user->role) == 'admin' ? 'selected' : '' }}>Admin</option>
            <option value="vendedor" {{ old('role', $user->role) == 'vendedor' ? 'selected' : '' }}>Vendedor</option>
            <option value="trabalhador" {{ old('role', $user->role) == 'trabalhador' ? 'selected' : '' }}>Trabalhador</option>
        </select>
        @error('role')
            <p class="text-danger">Erro no tipo de utilizador</p>
        @enderror
    </div>

	<div class="mb-3">
        <label for="email" class="form-label">Email do utilizador</label>
        <input name="email" type="email" class="form-control" id="email" value="{{ old('email', $user->email) }}" required>
        @error('email')
            <p class="text-danger">Erro no email do utilizador</p>
        @enderror
    </div>

	<div class="mb-3">
        <label for="password" class="form-label">Password do utilizador</label>
        <input name="password" type="password" class="form-control" id="password" value="">
        <small class="text-muted">Deixe em branco para manter a password atual.</small>
        @error('password')
            <p class="text-danger">Erro na password do utilizador</p>
        @enderror
    </div>

	

    

    <button type="submit" class="btn btn-success">Editar Utilizador</button>
</form>
		
@endsection
