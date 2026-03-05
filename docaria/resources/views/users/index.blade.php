@extends('layouts.app')

@section('title', 'Utilizadores - A Docaria')

@section('content')

<div class="row mb-2 mb-xl-3">
    <div class="col-auto d-none d-sm-block">
        <h3><strong style="color: #2f4f6c;">Gestão de Utilizadores</strong></h3>
    </div>

    <div class="col-auto ms-auto text-end mt-n1">
        <a href="{{ route('users.create') }}" class="btn btn-primary">
            <i class="align-middle" data-feather="plus"></i> Novo Utilizador
        </a>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card">

            <div class="card-header">
                <h5 class="card-title mb-0">Lista de Utilizadores</h5>
            </div>

            <div class="card-body">

                @if($users->count() > 0)

                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Nome</th>
                                    <th>Email</th>
                                    <th>Tipo de utilizador</th>
                                    <th class="text-end">Ações</th>
                                </tr>
                            </thead>
                            <tbody>

                                @foreach($users as $user)
                                    <tr>
                                        <td><strong>{{ $user->name }}</strong></td>
                                        <td>{{ $user->email }}</td>
                                        <td>{{ $user->role ?? '---' }}</td>

                                        <td class="text-end">
                                            <a href="{{ route('users.edit', $user) }}"
                                               class="btn btn-sm btn-warning"
                                               title="Editar">
                                                <i class="align-middle" data-feather="edit"></i>
                                            </a>

                                            <form action="{{ route('users.destroy', $user) }}"
                                                  method="POST"
                                                  class="d-inline"
                                                  onsubmit="return confirm('Tem a certeza que deseja eliminar este utilizador?');">
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

                    <div class="d-flex justify-content-between align-items-center mt-3">
                        <div>
                            Mostrando {{ $users->firstItem() }} a {{ $users->lastItem() }} de {{ $users->total() }} utilizadores
                        </div>
                        <div>
                            {{ $users->links() }}
                        </div>
                    </div>

                @else
                    <div class="alert alert-info mb-0">
                        <i class="align-middle" data-feather="info"></i>
                        Nenhum utilizador encontrado.
                    </div>
                @endif

            </div>
        </div>
    </div>
</div>

@endsection
