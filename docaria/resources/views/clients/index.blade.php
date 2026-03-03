@extends('layouts.app')

@section('title', 'Clientes - A Docaria')

@section('content')

<div class="row mb-2 mb-xl-3">
    <div class="col-auto d-none d-sm-block">
        <h3><strong>Clientes</strong></h3>
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
            <div class="card-header">
                <h5 class="card-title mb-0">Filtrar Clientes</h5>
            </div>
            <div class="card-body">
                <form method="GET" action="{{ route('clients.index') }}">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label">Pesquisar</label>
                            <input type="text" 
                                   name="search" 
                                   class="form-control"
                                   placeholder="Nome, telefone, NIF..."
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
                                        <td><strong>{{ $client->name }}</strong></td>
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

                    <!-- Paginação igual às encomendas -->
                    <div class="d-flex justify-content-between align-items-center mt-3">
                        <div>
                            Mostrando {{ $clients->firstItem() }} a {{ $clients->lastItem() }} de {{ $clients->total() }} clientes
                        </div>
                        <div>
                            {{ $clients->links() }}
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