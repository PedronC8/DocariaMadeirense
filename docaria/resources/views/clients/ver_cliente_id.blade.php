@extends('layouts.app')

@section('title', 'Detalhes do Cliente - A Docaria')

@section('content')
<div class="row mb-2 mb-xl-3">
    <div class="col-auto d-none d-sm-block">
        <h3><strong style="color: #2f4f6c;">Detalhes do Cliente</strong></h3>
    </div>

    <div class="col-auto ms-auto text-end mt-n1">
        <a href="{{ route('clients.index') }}" class="btn btn-info">
            <i class="align-middle" data-feather="arrow-left"></i> Voltar
        </a>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">{{ $client->name }}</h5>
                <div class="d-flex gap-2">
                    <a href="{{ route('clients.edit', $client) }}" class="btn btn-sm btn-warning" title="Editar">
                        <i class="align-middle" data-feather="edit"></i>
                    </a>
                    <form action="{{ route('clients.destroy', $client) }}"
                          method="POST"
                          class="d-inline"
                          onsubmit="return confirm('Tem a certeza que deseja eliminar este cliente?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-danger" title="Eliminar">
                            <i class="align-middle" data-feather="trash-2"></i>
                        </button>
                    </form>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Nome</th>
                                <th>Telefone</th>
                                <th>NIF</th>
                                <th>IdFaturacao</th>
                                <th>Morada</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>{{ $client->id }}</td>
                                <td>{{ $client->name ?? '---' }}</td>
                                <td>{{ $client->contact ?? '---' }}</td>
                                <td>{{ $client->nif ?? '---' }}</td>
                                <td>{{ $client->idFaturacao ?? '---' }}</td>
                                <td>{{ $client->address ?? '---' }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
