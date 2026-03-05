@extends('layouts.app')

@section('title', 'Gestao de Produtos')

@push('styles')
<style>
    .filters-container {
        max-height: 0;
        overflow: hidden;
        transition: max-height 0.3s ease-out;
    }
    .filters-container.show {
        max-height: 260px;
        transition: max-height 0.5s ease-in;
    }
    .filter-toggle-btn {
        cursor: pointer;
    }
    .products-table thead th,
    .products-table tbody td {
        padding-top: 0.85rem;
        padding-bottom: 0.85rem;
        vertical-align: middle;
    }
</style>
@endpush

@section('breadcrumb')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="h3 mb-0"><strong style="color: #2f4f6c;">Produtos</strong></h1>
        <a href="{{ route('products.create') }}" class="btn btn-primary">
            <i data-feather="plus"></i> Novo Produto
        </a>
    </div>
@endsection

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center filter-toggle-btn" id="filterToggle">
                <h5 class="card-title mb-0">
                    <i class="align-middle" data-feather="filter"></i> Filtrar Produtos
                </h5>
                <button type="button" class="btn btn-sm btn-outline-primary">
                    <i class="align-middle" data-feather="chevron-down" id="filterIcon"></i>
                    <span id="filterText">Mostrar Filtros</span>
                </button>
            </div>
            <div class="filters-container" id="filtersContainer">
                <div class="card-body">
                    <form method="GET" action="{{ route('products.index') }}">
                        <div class="row g-3">
                            <div class="col-md-4">
                                <label class="form-label">Pesquisar por nome</label>
                                <input type="text" name="search" class="form-control" placeholder="Nome do produto..." value="{{ request('search') }}">
                            </div>
                            <div class="col-md-8 d-flex align-items-end justify-content-end">
                                <button type="submit" class="btn btn-primary me-2">
                                    <i class="align-middle" data-feather="filter"></i> Filtrar
                                </button>
                                <a href="{{ route('products.index') }}" class="btn btn-secondary">
                                    <i class="align-middle" data-feather="x"></i> Limpar
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row mt-3">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Lista de Produtos</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover products-table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th></th>
                                <th>Nome</th>
                                <th>Categoria</th>
                                <th>Subcategoria</th>
                                <th>Preco</th>
                                <th>Estado</th>
                                <th class="text-end">Acoes</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($products as $product)
                                <tr>
                                    <td>{{ $product->id }}</td>
                                    <td>
                                        @if(!empty($product->image_path))
                                            <img src="{{ $product->image_path }}" alt="{{ $product->name }}" style="width: 46px; height: 46px; object-fit: cover; border-radius: 6px;">
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
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
                                        <a href="{{ route('products.edit', $product->id) }}" class="btn btn-sm btn-warning">
                                            Editar
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center">
                                        Nenhum produto encontrado.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>

                    <div class="d-flex justify-content-between align-items-center mt-3">
                        <div>
                            Mostrando de {{ $products->firstItem() }} a {{ $products->lastItem() }} de {{ $products->total() }} produtos
                        </div>
                        <div>
                            {{ $products->appends(request()->query())->links('pagination::bootstrap-5') }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const filterToggle = document.getElementById('filterToggle');
    const filtersContainer = document.getElementById('filtersContainer');
    const filterIcon = document.getElementById('filterIcon');
    const filterText = document.getElementById('filterText');

    const hasActiveFilters = {{ request()->filled('search') ? 'true' : 'false' }};

    if (hasActiveFilters) {
        filtersContainer.classList.add('show');
        filterText.textContent = 'Esconder Filtros';
        filterIcon.setAttribute('data-feather', 'chevron-up');
        if (typeof feather !== 'undefined') feather.replace();
    }

    filterToggle.addEventListener('click', function() {
        filtersContainer.classList.toggle('show');

        if (filtersContainer.classList.contains('show')) {
            filterText.textContent = 'Esconder Filtros';
            filterIcon.setAttribute('data-feather', 'chevron-up');
        } else {
            filterText.textContent = 'Mostrar Filtros';
            filterIcon.setAttribute('data-feather', 'chevron-down');
        }

        if (typeof feather !== 'undefined') feather.replace();
    });
});
</script>
@endpush
