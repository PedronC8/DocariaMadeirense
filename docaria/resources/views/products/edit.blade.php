@extends('layouts.app')

@section('title', 'Editar Produto')

@section('breadcrumb')
    <h1 class="h3 mb-3"><strong>Editar Produto</strong></h1>
@endsection

@section('content')

<div class="card">
    <div class="card-body">

        <form action="{{ route('products.update', $product->id) }}" method="POST">
            @csrf
            @method('PUT')

            <!-- Nome -->
            <div class="mb-3">
                <label class="form-label">Nome</label>
                <input type="text"
                       name="name"
                       class="form-control"
                       value="{{ old('name', $product->name) }}"
                       required>
            </div>

            <!-- Label -->
            <div class="mb-3">
                <label for="subcategory_id" class="form-label">Subcategoria</label>
                <select name= "subcategory_id" id="subcategory_id" class="form-select" required >
                    <option value="">-- Selecionar --</option>
                    @foreach($subcategories as $subcategory)
                    <option value="{{ $subcategory->id }}"
                    {{ old('subcategory_id', $product->subcategory_id) == $subcategory->id ? 'selected' : ''}}>
                {{ $subcategory->category->name ?? ''}} → {{$subcategory->name}}
</option>
@endforeach
</select>
</div>
                
                

            <!-- Preço -->
            <div class="mb-3">
                <label class="form-label">Preço (€)</label>
                <input type="number"
                       step="0.01"
                       name="price"
                       class="form-control"
                       value="{{ old('price', $product->price) }}"
                       required>
            </div>


            <!-- Estado -->
            <div class="mb-3">
                <label class="form-label">Ativo</label>
                <select name="active" class="form-select">
                    <option value="1"
                        {{ old('active', $product->active) == 1 ? 'selected' : '' }}>
                        Sim
                    </option>
                    <option value="0"
                        {{ old('active', $product->active) == 0 ? 'selected' : '' }}>
                        Não
                    </option>
                </select>
            </div>

            <!-- Botões para atualizar e cancelar  -->
            <button type="submit" class="btn btn-success">Atualizar</button>
            <a href="{{ route('products.index') }}" class="btn btn-secondary">Cancelar</a>

        </form>

    </div>
</div>

@endsection