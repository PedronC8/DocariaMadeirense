@extends('layouts.app')

@section('title', 'Detalhes da Encomenda - A Docaria')

@section('content')
<div class="row mb-2 mb-xl-3">
    <div class="col-auto d-none d-sm-block">
        <h3><strong>Encomenda #{{ $order->id }}</strong></h3>
    </div>

    <div class="col-auto ms-auto text-end mt-n1">
        <a href="{{ route('orders.edit', $order) }}" class="btn btn-warning">
            <i class="align-middle" data-feather="edit"></i> Editar
        </a>
        <button onclick="window.print()" class="btn btn-secondary">
            <i class="align-middle" data-feather="printer"></i> Imprimir
        </button>
        <a href="{{ route('orders.index') }}" class="btn btn-info">
            <i class="align-middle" data-feather="arrow-left"></i> Voltar
        </a>
    </div>
</div>

<div class="row">
    <!-- Informações Principais -->
    <div class="col-lg-8">
        <!-- Card: Informações do Cliente -->
        <div class="card mb-3">
            <div class="card-header">
                <h5 class="card-title mb-0">Informações do Cliente</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <p class="mb-2"><strong>Nome:</strong> {{ $order->client->name }}</p>
                        <p class="mb-2"><strong>ID Faturação:</strong> {{ $order->client->formatted_id }}</p>
                        <p class="mb-2"><strong>NIF:</strong> {{ $order->client->nif ?? 'N/A' }}</p>
                    </div>
                    <div class="col-md-6">
                        <p class="mb-2"><strong>Contacto:</strong> {{ $order->client->contact ?? 'N/A' }}</p>
                        <p class="mb-2"><strong>Morada:</strong> {{ $order->client->address ?? 'N/A' }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Card: Datas -->
        <div class="card mb-3">
            <div class="card-header">
                <h5 class="card-title mb-0">Datas</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3">
                        <p class="mb-2 text-muted">Data Encomenda</p>
                        <p class="mb-0"><strong>{{ $order->order_date->format('d/m/Y') }}</strong></p>
                    </div>
                    <div class="col-md-3">
                        <p class="mb-2 text-muted">Pronto Em</p>
                        <p class="mb-0"><strong>{{ $order->ready_date->format('d/m/Y') }}</strong></p>
                    </div>
                    <div class="col-md-3">
                        <p class="mb-2 text-muted">Data Entrega</p>
                        <p class="mb-0"><strong>{{ $order->delivery_date->format('d/m/Y') }}</strong></p>
                    </div>
                    <div class="col-md-3">
                        <p class="mb-2 text-muted">Data Desejada</p>
                        <p class="mb-0"><strong>{{ $order->desired_date ? $order->desired_date->format('d/m/Y') : 'N/A' }}</strong></p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Card: Produtos -->
        <div class="card mb-3">
            <div class="card-header">
                <h5 class="card-title mb-0">Produtos da Encomenda</h5>
            </div>
            <div class="card-body">
                @if($order->invoice)
                    <!-- COM FATURA: Sem preços -->
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Produto</th>
                                    <th class="text-center">Quantidade</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($order->items as $item)
                                    <tr>
                                        <td><strong>{{ $item->product->name }}</strong></td>
                                        <td class="text-center">{{ $item->quantity }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="alert alert-info mb-0 mt-3">
                        <i class="align-middle" data-feather="info"></i>
                        <strong>Fatura Nº {{ $order->invoice }}</strong> - Valores detalhados omitidos
                    </div>
                @else
                    <!-- SEM FATURA: Com preços -->
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Produto</th>
                                    <th class="text-center">Quantidade</th>
                                    <th class="text-end">Preço Unit.</th>
                                    <th class="text-end">Subtotal</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($order->items as $item)
                                    <tr>
                                        <td><strong>{{ $item->product->name }}</strong></td>
                                        <td class="text-center">{{ $item->quantity }}</td>
                                        <td class="text-end">{{ number_format($item->product->price, 2, ',', '.') }}€</td>
                                        <td class="text-end"><strong>{{ number_format($item->subtotal, 2, ',', '.') }}€</strong></td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>
        @if($order->notes)
            <div class="card mb-3">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        Notas
                    </h5>
                </div>
                <div class="card-body">
                    <p class="mb-0">{{ $order->notes }}</p>
                </div>
            </div>
        @endif
    </div>

    <!-- Sidebar - Resumo e Status -->
    <div class="col-lg-4">
        <!-- Card: Resumo Financeiro -->
        <div class="card mb-3">
            <div class="card-header text-white" style="background-color: #222e3c;">
                <h5 class="card-title mb-0 text-white">Resumo Financeiro</h5>
            </div>
            <div class="card-body">
                @if($order->invoice)
                    <!-- COM FATURA: Valores omitidos -->
                    <div class="text-center py-3">
                        <i class="align-middle mb-2" data-feather="file-text" style="width: 48px; height: 48px;"></i>
                        <p class="text-muted mb-0">Valores omitidos</p>
                        <p class="text-muted small">Fatura Nº {{ $order->invoice }}</p>
                    </div>
                @else
                    <!-- SEM FATURA: Valores visíveis -->
                    <div class="d-flex justify-content-between mb-2">
                        <span>Subtotal:</span>
                        <strong>{{ number_format($order->subtotal, 2, ',', '.') }}€</strong>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span>IVA (22%):</span>
                        <strong>{{ number_format($order->iva, 2, ',', '.') }}€</strong>
                    </div>
                    <hr>
                    <div class="d-flex justify-content-between mb-0">
                        <h5 class="mb-0">Total:</h5>
                        <h5 class="mb-0 text-primary">{{ number_format($order->total, 2, ',', '.') }}€</h5>
                    </div>
                @endif
            </div>
        </div>

        <!-- Card: Status -->
        <div class="card mb-3">
            <div class="card-header">
                <h5 class="card-title mb-0">Controlo de Processo</h5>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <p class="mb-2 text-muted">Estado da Encomenda</p>
                    <h5>
                        <span class="badge bg-{{ $order->status_badge }}">
                            {{ $order->status_label }}
                        </span>
                    </h5>
                </div>

                <div class="mb-3">
                    <p class="mb-2 text-muted">Estado do Pagamento</p>
                    <h5>
                        <span class="badge bg-{{ $order->payment_status_badge }}">
                            {{ $order->payment_status_label }}
                        </span>
                    </h5>
                </div>

                @if($order->trabalhador)
                    <div class="mb-3">
                        <p class="mb-2 text-muted">Última Alteração Por</p>
                        <p class="mb-0">
                            <strong>{{ $order->trabalhador->name }}</strong>
                            <br>
                            <span class="badge bg-secondary">{{ ucfirst($order->trabalhador->role) }}</span>
                        </p>
                    </div>
                @endif

                @if($order->invoice)
                    <div>
                        <p class="mb-2 text-muted">Nº Fatura</p>
                        <p class="mb-0"><strong>{{ $order->invoice }}</strong></p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Card: Ações Rápidas -->
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Ações Rápidas</h5>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    @if($order->status == 'preparacao')
                        <!-- PREPARAÇÃO → Mostrar apenas botão Concluir -->
                        <form action="{{ route('orders.update', $order) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <input type="hidden" name="status" value="concluido">
                            @foreach($order->items as $item)
                                <input type="hidden" name="products[{{ $item->product_id }}][id]" value="{{ $item->product_id }}">
                                <input type="hidden" name="products[{{ $item->product_id }}][quantity]" value="{{ $item->quantity }}">
                            @endforeach
                            <input type="hidden" name="client_id" value="{{ $order->client_id }}">
                            <input type="hidden" name="order_date" value="{{ $order->order_date->format('Y-m-d') }}">
                            <input type="hidden" name="ready_date" value="{{ $order->ready_date->format('Y-m-d') }}">
                            <input type="hidden" name="delivery_date" value="{{ $order->delivery_date->format('Y-m-d') }}">
                            <input type="hidden" name="payment_status" value="{{ $order->payment_status }}">
                            <input type="hidden" name="desired_date" value="{{ optional($order->desired_date)->format('Y-m-d') }}">
                            <input type="hidden" name="invoice" value="{{ $order->invoice }}">
                            <input type="hidden" name="payment_method" value="">
                            <input type="hidden" name="trabalhador_id" value="{{ Auth::id() }}">
                            <input type="hidden" name="notes" value="{{ $order->notes }}">
                            <button type="submit" class="btn btn-success w-100 btn-lg">
                                <i class="align-middle" data-feather="check-circle"></i> Pronto para Entrega
                            </button>
                        </form>
                    @endif

                    @if($order->status == 'concluido')
                        <!-- CONCLUÍDO → Mostrar botões Entregue e Pago lado a lado -->
                        <div class="row g-2">
                            @if($order->status != 'entregue')
                                <div class="col-6">
                                    <form action="{{ route('orders.update', $order) }}" method="POST">
                                        @csrf
                                        @method('PUT')
                                        <input type="hidden" name="status" value="entregue">
                                        @foreach($order->items as $item)
                                            <input type="hidden" name="products[{{ $item->product_id }}][id]" value="{{ $item->product_id }}">
                                            <input type="hidden" name="products[{{ $item->product_id }}][quantity]" value="{{ $item->quantity }}">
                                        @endforeach
                                        <input type="hidden" name="client_id" value="{{ $order->client_id }}">
                                        <input type="hidden" name="order_date" value="{{ $order->order_date->format('Y-m-d') }}">
                                        <input type="hidden" name="ready_date" value="{{ $order->ready_date->format('Y-m-d') }}">
                                        <input type="hidden" name="delivery_date" value="{{ $order->delivery_date->format('Y-m-d') }}">
                                        <input type="hidden" name="payment_status" value="{{ $order->payment_status }}">
                                        <input type="hidden" name="desired_date" value="{{ optional($order->desired_date)->format('Y-m-d') }}">
                                        <input type="hidden" name="invoice" value="{{ $order->invoice }}">
                                        <input type="hidden" name="payment_method" value="">
                                        <input type="hidden" name="trabalhador_id" value="{{ Auth::id() }}">
                                        <input type="hidden" name="notes" value="{{ $order->notes }}">
                                        <button type="submit" class="btn btn-success w-100 btn-lg">
                                            <i class="align-middle" data-feather="truck"></i> Entregue
                                        </button>
                                    </form>
                                </div>
                            @endif

                            @if($order->payment_status != 'pago')
                                <div class="{{ $order->status == 'entregue' ? 'col-12' : 'col-6' }}">
                                    <form action="{{ route('orders.update', $order) }}" method="POST">
                                        @csrf
                                        @method('PUT')
                                        <input type="hidden" name="payment_status" value="pago">
                                        @foreach($order->items as $item)
                                            <input type="hidden" name="products[{{ $item->product_id }}][id]" value="{{ $item->product_id }}">
                                            <input type="hidden" name="products[{{ $item->product_id }}][quantity]" value="{{ $item->quantity }}">
                                        @endforeach
                                        <input type="hidden" name="client_id" value="{{ $order->client_id }}">
                                        <input type="hidden" name="order_date" value="{{ $order->order_date->format('Y-m-d') }}">
                                        <input type="hidden" name="ready_date" value="{{ $order->ready_date->format('Y-m-d') }}">
                                        <input type="hidden" name="delivery_date" value="{{ $order->delivery_date->format('Y-m-d') }}">
                                        <input type="hidden" name="status" value="{{ $order->status }}">
                                        <input type="hidden" name="desired_date" value="{{ optional($order->desired_date)->format('Y-m-d') }}">
                                        <input type="hidden" name="invoice" value="{{ $order->invoice }}">
                                        <input type="hidden" name="payment_method" value="">
                                        <input type="hidden" name="trabalhador_id" value="{{ Auth::id() }}">
                                        <input type="hidden" name="notes" value="{{ $order->notes }}">
                                        <button type="submit" class="btn btn-primary w-100 btn-lg">
                                            <i class="align-middle" data-feather="dollar-sign"></i> Pago
                                        </button>
                                    </form>
                                </div>
                            @endif
                        </div>
                    @endif

                    @if($order->status == 'entregue' && $order->payment_status != 'pago')
                        <!-- ENTREGUE mas NÃO PAGO → Mostrar apenas botão Pago -->
                        <form action="{{ route('orders.update', $order) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <input type="hidden" name="payment_status" value="pago">
                            @foreach($order->items as $item)
                                <input type="hidden" name="products[{{ $item->product_id }}][id]" value="{{ $item->product_id }}">
                                <input type="hidden" name="products[{{ $item->product_id }}][quantity]" value="{{ $item->quantity }}">
                            @endforeach
                            <input type="hidden" name="client_id" value="{{ $order->client_id }}">
                            <input type="hidden" name="order_date" value="{{ $order->order_date->format('Y-m-d') }}">
                            <input type="hidden" name="ready_date" value="{{ $order->ready_date->format('Y-m-d') }}">
                            <input type="hidden" name="delivery_date" value="{{ $order->delivery_date->format('Y-m-d') }}">
                            <input type="hidden" name="status" value="{{ $order->status }}">
                            <input type="hidden" name="desired_date" value="{{ optional($order->desired_date)->format('Y-m-d') }}">
                            <input type="hidden" name="invoice" value="{{ $order->invoice }}">
                            <input type="hidden" name="payment_method" value="">
                            <input type="hidden" name="trabalhador_id" value="{{ Auth::id() }}">
                            <input type="hidden" name="notes" value="{{ $order->notes }}">
                            <button type="submit" class="btn btn-primary w-100 btn-lg">
                                <i class="align-middle" data-feather="dollar-sign"></i> Marcar como Pago
                            </button>
                        </form>
                    @endif

                    <!-- Botões sempre presentes (Imprimir e Eliminar) -->
                    <div class="row g-2 mt-2">
                        <div class="col-6">
                            <button onclick="window.print()" class="btn btn-secondary w-100 btn-lg">
                                <i class="align-middle" data-feather="printer"></i> Imprimir
                            </button>
                        </div>
                        <div class="col-6">
                            <form action="{{ route('orders.destroy', $order) }}" 
                                  method="POST"
                                  onsubmit="return confirm('Tem a certeza que deseja eliminar esta encomenda?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger w-100 btn-lg">
                                    <i class="align-middle" data-feather="trash-2"></i> Eliminar
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
@media print {
    .sidebar,
    .navbar,
    .btn,
    .card-header {
        display: none !important;
    }
    
    .main {
        padding: 0 !important;
    }
    
    .card {
        border: none !important;
        box-shadow: none !important;
    }
}
</style>
@endpush
