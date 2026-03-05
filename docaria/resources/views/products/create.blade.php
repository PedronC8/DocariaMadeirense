@extends('layouts.app')

@section('title', 'Novo Produto')

@section('breadcrumb')
    <h1 class="h3 mb-3"><strong>Novo Produto</strong></h1>
@endsection

@section('content')
<div class="card">
    <div class="card-body">
        <form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="mb-3">
                <label class="form-label">Nome</label>
                <input type="text" name="name" id="name" class="form-control" value="{{ old('name') }}" required>
            </div>

            <div class="mb-3">
                <label for="subcategory_id" class="form-label">Categoria/Subcategoria</label>
                <select name="subcategory_id" id="subcategory_id" class="form-select" required>
                    <option value="">-- Selecionar --</option>
                    @foreach($subcategories as $subcategory)
                        <option value="{{ $subcategory->id }}" {{ old('subcategory_id') == $subcategory->id ? 'selected' : '' }}>
                            {{ $subcategory->category->name ?? '' }} -> {{ $subcategory->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="mb-3">
                <label for="price" class="form-label">Preco (EUR)</label>
                <input type="number" step="0.01" name="price" id="price" class="form-control" value="{{ old('price') }}" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Ativo</label>
                <select name="active" class="form-select">
                    <option value="1" {{ old('active', '1') == '1' ? 'selected' : '' }}>Sim</option>
                    <option value="0" {{ old('active') == '0' ? 'selected' : '' }}>Nao</option>
                </select>
            </div>

            <div class="mb-3">
                <label for="image" class="form-label">Imagem do Produto</label>
                <input type="file" name="image" id="image" class="form-control" accept=".jpg,.jpeg,.png,.webp">
                <small class="text-muted">Formatos permitidos: JPG, PNG, WEBP (max 4MB)</small>
            </div>

            <button type="submit" class="btn btn-success">Guardar</button>
            <a href="{{ route('products.index') }}" class="btn btn-secondary">Cancelar</a>
        </form>
    </div>
</div>
@endsection
