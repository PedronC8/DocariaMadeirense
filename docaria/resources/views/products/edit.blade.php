@extends('layouts.app')

@section('title', 'Editar Produto')

@section('breadcrumb')
    <h1 class="h3 mb-3"><strong>Editar Produto</strong></h1>
@endsection

@section('content')
<div class="card">
    <div class="card-body">
        <form action="{{ route('products.update', $product->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label class="form-label">Nome</label>
                <input type="text" name="name" class="form-control" value="{{ old('name', $product->name) }}" required>
            </div>

            <div class="mb-3">
                <label for="subcategory_id" class="form-label">Subcategoria</label>
                <select name="subcategory_id" id="subcategory_id" class="form-select" required>
                    <option value="">-- Selecionar --</option>
                    @foreach($subcategories as $subcategory)
                        <option value="{{ $subcategory->id }}" {{ old('subcategory_id', $product->subcategory_id) == $subcategory->id ? 'selected' : '' }}>
                            {{ $subcategory->category->name ?? '' }} -> {{ $subcategory->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label">Preco (€)</label>
                <input type="number" step="0.01" name="price" class="form-control" value="{{ old('price', $product->price) }}" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Ativo</label>
                <select name="active" class="form-select">
                    <option value="1" {{ old('active', $product->active) == 1 ? 'selected' : '' }}>Sim</option>
                    <option value="0" {{ old('active', $product->active) == 0 ? 'selected' : '' }}>Nao</option>
                </select>
            </div>

            <div class="mb-3">
                <label for="image" class="form-label">Imagem do Produto</label>
                <input type="file" name="image" id="image" class="form-control" accept=".jpg,.jpeg,.png,.webp">
                <small class="text-muted d-block">Se escolher nova imagem, substitui a atual.</small>
            </div>

            @if(!empty($product->image_path))
                <div class="mb-3">
                    <label class="form-label d-block">Imagem atual</label>
                    <img src="{{ $product->image_path }}" alt="{{ $product->name }}" style="max-width: 220px; max-height: 160px; object-fit: cover; border-radius: 6px;">
                </div>
            @endif

            <button type="submit" class="btn btn-success">Atualizar</button>
            <a href="{{ route('products.index') }}" class="btn btn-secondary">Cancelar</a>
        </form>
    </div>
</div>
@endsection
