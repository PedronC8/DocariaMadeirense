@extends('layouts.app')

@section('title', 'Encomendas - A Docaria')

@section('content')
<div class="row mb-2 mb-xl-3">
    <div class="col-auto d-none d-sm-block">
        <h3><strong>Encomendas</strong></h3>
    </div>

    <div class="col-auto ms-auto text-end mt-n1">
        <a href="{{ route('orders.create') }}" class="btn btn-primary">
            <i class="align-middle" data-feather="plus"></i> Nova Encomenda
        </a>
    </div>
</div>

<!-- Filtros -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Filtrar Encomendas</h5>
            </div>
            <div class="card-body">
                <form method="GET" action="{{ route('orders.index') }}">
                    <div class="row g-3">
                        <div class="col-md-3">
                            <label class="form-label">Pesquisar</label>
                            <input type="text" name="search" class="form-control" 
                                   placeholder="Nº Fatura ou Cliente..." 
                                   value="{{ request('search') }}">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Status</label>
                            <select name="status" class="form-select">
                                <option value="">Todos</option>
                                <option value="preparacao" {{ request('status') == 'preparacao' ? 'selected' : '' }}>Em Preparação</option>
                                <option value="concluido" {{ request('status') == 'concluido' ? 'selected' : '' }}>Concluído</option>
                                <option value="entregue" {{ request('status') == 'entregue' ? 'selected' : '' }}>Entregue</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Pagamento</label>
                            <select name="payment_status" class="form-select">
                                <option value="">Todos</option>
                                <option value="nao_pago" {{ request('payment_status') == 'nao_pago' ? 'selected' : '' }}>Não Pago</option>
                                <option value="parcial" {{ request('payment_status') == 'parcial' ? 'selected' : '' }}>Parcial</option>
                                <option value="pago" {{ request('payment_status') == 'pago' ? 'selected' : '' }}>Pago</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Cliente</label>
                            <select name="client_id" class="form-select">
                                <option value="">Todos</option>
                                @foreach($clients as $client)
                                    <option value="{{ $client->id }}" {{ request('client_id') == $client->id ? 'selected' : '' }}>
                                        {{ $client->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2 d-flex align-items-end">
                            <button type="submit" class="btn btn-primary me-2">
                                <i class="align-middle" data-feather="filter"></i> Filtrar
                            </button>
                            <a href="{{ route('orders.index') }}" class="btn btn-secondary">
                                <i class="align-middle" data-feather="x"></i> Limpar
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Tabela de Encomendas -->
<div class="row mt-3">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Lista de Encomendas</h5>
            </div>
            <div class="card-body">
                @if($orders->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Fatura</th>
                                    <th>Cliente</th>
                                    <th>Data Encomenda</th>
                                    <th>Data Entrega</th>
                                    <th>Total</th>
                                    <th>Status</th>
                                    <th>Pagamento</th>
                                    <th class="text-end">Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($orders as $order)
                                    <tr>
                                        <td><strong>#{{ $order->id }}</strong></td>
                                        <td>{{ $order->invoice ?? 'N/A' }}</td>
                                        <td>{{ $order->client->name }}</td>
                                        <td>{{ $order->order_date->format('d/m/Y') }}</td>
                                        <td>{{ $order->delivery_date->format('d/m/Y') }}</td>
                                        <td><strong>{{ number_format($order->total, 2, ',', '.') }}€</strong></td>
                                        <td>
                                            <span class="badge bg-{{ $order->status_badge }}">
                                                {{ $order->status_label }}
                                            </span>
                                        </td>
                                        <td>
                                            <span class="badge bg-{{ $order->payment_status_badge }}">
                                                {{ $order->payment_status_label }}
                                            </span>
                                        </td>
                                        <td class="text-end">
                                            <a href="{{ route('orders.show', $order) }}" 
                                               class="btn btn-sm btn-info" 
                                               title="Ver Detalhes">
                                                <i class="align-middle" data-feather="eye"></i>
                                            </a>
                                            <a href="{{ route('orders.edit', $order) }}" 
                                               class="btn btn-sm btn-warning" 
                                               title="Editar">
                                                <i class="align-middle" data-feather="edit"></i>
                                            </a>
                                            <form action="{{ route('orders.destroy', $order) }}" 
                                                  method="POST" 
                                                  class="d-inline"
                                                  onsubmit="return confirm('Tem a certeza que deseja eliminar esta encomenda?');">
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

                    <!-- Paginação -->
                    <div class="d-flex justify-content-between align-items-center mt-3">
                        <div>
                            Mostrando {{ $orders->firstItem() }} a {{ $orders->lastItem() }} de {{ $orders->total() }} encomendas
                        </div>
                        <div>
                            {{ $orders->links() }}
                        </div>
                    </div>
                @else
                    <div class="alert alert-info mb-0">
                        <i class="align-middle" data-feather="info"></i>
                        Nenhuma encomenda encontrada.
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
