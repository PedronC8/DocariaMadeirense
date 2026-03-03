@extends('layouts.app')

@section('title', 'Gestão de Produtos')

@section('breadcrumb')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="h3 mb-0"><strong>Produtos</strong></h1>
        <a href="{{ route('products.create') }}" class="btn btn-primary">
            <i data-feather="plus"></i> Novo Produto
        </a>
    </div>
@endsection

@section('content')

<div class="card">
    <div class="card-body">

        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th>ID</th>
                        <th>Nome</th>
                        <th>Categoria</th>
                        <th>Subcategoria</th>
                        <th>Preço</th>
                        <th>Estado</th>
                        <th class="text-end">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($products as $product)
                        <tr>
                            <td>{{ $product->id }}</td>
                            <td>{{ $product->name }}</td>
                            <td>{{ $product->subcategory->category->name ?? '-' }}</td>
                            <td>{{ $product->subcategory->name ?? 'Sem subcategoria' }}</td>
                            <td>{{ number_format($product->price, 2) }} €</td>
                            <td>
                                @if($product->active)
                                    <span class="badge bg-success">Ativo</span>
                                @else
                                    <span class="badge bg-secondary">Inativo</span>
                                @endif
                            </td>
                            <td class="text-end">
                                <a href="{{ route('products.edit', $product->id) }}" 
                                   class="btn btn-sm btn-warning">
                                    Editar
                                </a>

                               {{-- <form action="{{ route('products.destroy', $product->id) }}" 
                                      method="POST" 
                                      class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" 
                                            class="btn btn-sm btn-danger"
                                            onclick="return confirm('Tem a certeza que deseja eliminar este produto?')">
                                        Eliminar
                                    </button>
                                </form>--}}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center">
                                Nenhum produto encontrado.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            <div class="mt-3">
    {{ $products->links() }}
</div>
        </div>

    </div>
</div>

@endsection