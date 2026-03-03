@extends('layouts.app')

@section('title', 'Novo Produto')

@section('breadcrumb')
    <h1 class="h3 mb-3"><strong>Novo Produto</strong></h1>
@endsection

@section('content')

<div class="card">
    <div class="card-body">

        <form action="{{ route('products.store') }}" method="POST">
            @csrf

            <div class="mb-3">
                <label class="form-label">Nome</label>
                <input type="text" name="name" id="name" class="form-control" value="{{ old('name') }}"required>
            </div>
  <!-- Dropdown de Categoria -->
            <div class="mb-3">
                <label for="subcategory_id" class="form-label">Categoria/Subcategoria</label>
                <select name="subcategory_id" id="subcategory_id" class="form-select" required>
                <option value="">-- Selecionar --</option>
                @foreach($subcategories as $subcategory)
                <option value="{{ $subcategory->id }}"
                {{ old('subcategory_id') == $subcategory->id ? 'selected' : ''}}>
                {{ $subcategory->category->name ?? ''}} → {{$subcategory->name}}
</option>
@endforeach
</select>
</div>
            <div class="mb-3">
                <label for="price" class="form-label">Preço (€)</label>
                <input type="number" step="0.01" name="price" id="price" class="form-control" value="{{ old('price') }}" required>
            </div>
            <!-- Dropdown de subcategoria
            <div class="mb-3">
                <label class="form-label">Subcategoria</label>
                <select name="subcategory_id" class="form-select" required>
                    <option value="">Escolha uma subcategoria</option>
                    @foreach($subcategories as $subcategory)
                        <option value="{{ $subcategory->id }}">
                            {{ $subcategory->name }}
                        </option>
                    @endforeach
                </select>
            </div> -->

            <div class="mb-3">
                <label class="form-label">Ativo</label>
                <select name="active" class="form-select">
                    <option value="1">Sim</option>
                    <option value="0">Não</option>
                </select>
            </div>

            <button type="submit" class="btn btn-success">
                Guardar
            </button>

            <a href="{{ route('products.index') }}" class="btn btn-secondary">
                Cancelar
            </a>
        </form>

    </div>
</div>

@endsection