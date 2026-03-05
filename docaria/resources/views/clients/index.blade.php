@extends('layouts.app')

@section('title', 'Clientes - A Docaria')

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
</style>
@endpush

@section('content')

<div class="row mb-2 mb-xl-3">
    <div class="col-auto d-none d-sm-block">
        <h3><strong style="color: #2f4f6c;">Clientes</strong></h3>
    </div>

    <div class="col-auto ms-auto text-end mt-n1">
        <a href="{{ route('clients.create') }}" class="btn btn-primary">
            <i class="align-middle" data-feather="plus"></i> Novo Cliente
        </a>
    </div>
</div>

<!-- Filtro -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center filter-toggle-btn" id="filterToggle">
                <h5 class="card-title mb-0">
                    <i class="align-middle" data-feather="filter"></i> Filtrar Clientes
                </h5>
                <button type="button" class="btn btn-sm btn-outline-primary">
                    <i class="align-middle" data-feather="chevron-down" id="filterIcon"></i>
                    <span id="filterText">Mostrar Filtros</span>
                </button>
            </div>
            <div class="filters-container" id="filtersContainer">
                <div class="card-body">
                    <form method="GET" action="{{ route('clients.index') }}">
                        <div class="row g-3">
                            <div class="col-md-4">
                                <label class="form-label">Pesquisar por nome</label>
                                <input type="text" 
                                       name="search" 
                                       class="form-control"
                                       placeholder="Nome do cliente..."
                                       value="{{ request('search') }}">
                            </div>

                            <div class="col-md-8 d-flex align-items-end justify-content-end">
                                <button type="submit" class="btn btn-primary me-2">
                                    <i class="align-middle" data-feather="filter"></i> Filtrar
                                </button>

                                <a href="{{ route('clients.index') }}" class="btn btn-secondary">
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

<!-- Lista de Clientes -->
<div class="row mt-3">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Lista de Clientes</h5>
            </div>
            <div class="card-body">

                @if($clients->count() > 0)

                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Nome</th>
                                    <th>Telefone</th>
                                    <th>NIF</th>
                                    <th>Morada</th>
                                    <th class="text-end">Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($clients as $client)
                                    <tr>
                                        <td>{{ $client->id }}</td>
                                        <td>{{ $client->name }}</td>
                                        <td>{{ $client->contact ?? '---' }}</td>
                                        <td>{{ $client->nif ?? '---' }}</td>
                                        <td>
                                            {{ $client->address 
                                                ? Str::limit($client->address, 20) 
                                                : '---' }}
                                        </td>
                                        <td class="text-end">

                                            <!-- Ver Detalhes -->
                                            <a href="{{ route('clients.show', $client) }}" 
                                               class="btn btn-sm btn-info"
                                               title="Ver Detalhes">
                                                <i class="align-middle" data-feather="eye"></i>
                                            </a>

                                            <!-- Editar -->
                                            <a href="{{ route('clients.edit', $client) }}" 
                                               class="btn btn-sm btn-warning"
                                               title="Editar">
                                                <i class="align-middle" data-feather="edit"></i>
                                            </a>

                                            <!-- Remover -->
                                            <form action="{{ route('clients.destroy', $client) }}" 
                                                  method="POST"
                                                  class="d-inline"
                                                  onsubmit="return confirm('Tem a certeza que deseja eliminar este cliente?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" 
                                                        class="btn btn-sm btn-danger"
                                                        title="Eliminar">
                                                    <i class="align-middle" data-feather="trash-2"></i>
                                                </button>
                                            </form>

                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- PaginaÃ§Ã£o igual Ã s encomendas -->
                    <div class="d-flex justify-content-between align-items-center mt-3">
                        <div>
                            Mostrando {{ $clients->firstItem() }} a {{ $clients->lastItem() }} de {{ $clients->total() }} clientes
                        </div>
                        <div>
                            {{ $clients->appends(request()->query())->links() }}
                        </div>
                    </div>

                @else
                    <div class="alert alert-info mb-0">
                        <i class="align-middle" data-feather="info"></i>
                        Nenhum cliente encontrado.
                    </div>
                @endif

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
