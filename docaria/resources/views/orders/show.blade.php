@extends('layouts.app')

@section('title', 'Detalhes da Encomenda - A Docaria')

@section('content')
<div class="row mb-2 mb-xl-3">
    <div class="col-auto d-none d-sm-block">
        <h3><strong>Encomenda {{ $order->id }}</strong></h3>
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
                    <div class="col-md-3 print-date-main">
                        <p class="mb-2 text-muted">Data Encomenda</p>
                        <p class="mb-0"><strong>{{ $order->order_date->format('d/m/Y') }}</strong></p>
                    </div>
                    <div class="col-md-3 print-date-main">
                        <p class="mb-2 text-muted text-nowrap">Data Entrega Desejada</p>
                        <p class="mb-0"><strong>{{ $order->desired_date ? $order->desired_date->format('d/m/Y') : 'N/A' }}</strong></p>
                    </div>
                    <div class="col-md-3 print-hide-date-extra ps-md-5">
                        <p class="mb-2 text-muted">Pronto Em</p>
                        <p class="mb-0"><strong>{{ in_array($order->status, ['concluido', 'entregue']) && $order->ready_date ? $order->ready_date->format('d/m/Y') : 'N/A' }}</strong></p>
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col-md-3 print-hide-date-extra">
                        <p class="mb-2 text-muted">Data Entrega</p>
                        <p class="mb-0"><strong>{{ $order->status === 'entregue' && $order->delivery_date ? $order->delivery_date->format('d/m/Y') : 'N/A' }}</strong></p>
                    </div>
                    <div class="col-md-3 print-hide-date-extra">
                        <p class="mb-2 text-muted">Pago Em</p>
                        <p class="mb-0"><strong>{{ $order->payment_date ? $order->payment_date->format('d/m/Y') : 'N/A' }}</strong></p>
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
                <div class="table-responsive">
                    <table class="table table-sm">
                        <thead>
                            <tr>
                                <th>Produto</th>
                                <th class="text-center">Quantidade</th>
                                @if(!$order->invoice)
                                    <th class="text-end">Preço Unit.</th>
                                    <th class="text-end">Subtotal</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($order->items as $item)
                                <tr>
                                    <td>
                                        <form action="{{ route('orders.items.check', ['order' => $order, 'item' => $item]) }}" method="POST" class="d-flex align-items-center gap-2 mb-0">
                                            @csrf
                                            @method('PATCH')
                                            <input type="hidden" name="is_checked" value="0">
                                            <input
                                                type="checkbox"
                                                name="is_checked"
                                                value="1"
                                                class="form-check-input mt-0 order-item-checkbox"
                                                onchange="this.form.submit()"
                                                {{ $item->is_checked ? 'checked' : '' }}
                                                aria-label="Marcar produto {{ $item->product->name }} como concluido">
                                            <span class="order-item-name {{ $item->is_checked ? 'order-item-checked' : '' }}">
                                                {{ $item->product->name }}
                                            </span>
                                        </form>
                                    </td>
                                    <td class="text-center">{{ $item->quantity }}</td>
                                    @if(!$order->invoice)
                                        <td class="text-end">{{ number_format($item->product->price, 2, ',', '.') }}€</td>
                                        <td class="text-end"><strong>{{ number_format($item->subtotal, 2, ',', '.') }}€</strong></td>
                                    @endif
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
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
            <div class="card-header text-white" style="background-color: #2f4f6c;">
                <h5 class="card-title mb-0 text-white">Resumo Financeiro</h5>
            </div>
            <div class="card-body">
                <div class="d-flex justify-content-between mb-2">
                    <span>Subtotal:</span>
                    <strong>{{ number_format($order->subtotal, 2, ',', '.') }}€</strong>
                </div>
                <div class="d-flex justify-content-between mb-2">
                    <span>IVA:</span>
                    <strong>{{ number_format($order->invoice ? $order->iva : 0, 2, ',', '.') }}€</strong>
                </div>
                <hr>
                <div class="d-flex justify-content-between mb-0">
                    <h5 class="mb-0">Total:</h5>
                    <h5 class="mb-0 text-primary">{{ number_format($order->invoice ? $order->total : $order->subtotal, 2, ',', '.') }}€</h5>
                </div>
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
                            <strong>{{ $order->trabalhador->name }} ({{ strtolower($order->trabalhador->role) }})</strong>
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
                        <!-- PREPARAÇÂO ao Mostrar apenas botão Concluir -->
                        <form action="{{ route('orders.quick-update', $order) }}" method="POST">
                            @csrf
                            <input type="hidden" name="status" value="concluido">
                            @foreach($order->items as $item)
                                <input type="hidden" name="products[{{ $item->product_id }}][id]" value="{{ $item->product_id }}">
                                <input type="hidden" name="products[{{ $item->product_id }}][quantity]" value="{{ $item->quantity }}">
                            @endforeach
                            <input type="hidden" name="client_id" value="{{ $order->client_id }}">
                            <input type="hidden" name="order_date" value="{{ $order->order_date->format('Y-m-d') }}">
                            <input type="hidden" name="ready_date" value="{{ optional($order->ready_date)->format('Y-m-d') }}">
                            <input type="hidden" name="delivery_date" value="{{ optional($order->delivery_date)->format('Y-m-d') }}">
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
                        <!-- CONCLUÍDO ao Mostrar botões Entregue e Pago lado a lado -->
                        <div class="row g-2">
                            @if($order->status != 'entregue')
                                <div class="col-6">
                                    <form action="{{ route('orders.quick-update', $order) }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="status" value="entregue">
                                        @foreach($order->items as $item)
                                            <input type="hidden" name="products[{{ $item->product_id }}][id]" value="{{ $item->product_id }}">
                                            <input type="hidden" name="products[{{ $item->product_id }}][quantity]" value="{{ $item->quantity }}">
                                        @endforeach
                                        <input type="hidden" name="client_id" value="{{ $order->client_id }}">
                                        <input type="hidden" name="order_date" value="{{ $order->order_date->format('Y-m-d') }}">
                                        <input type="hidden" name="ready_date" value="{{ optional($order->ready_date)->format('Y-m-d') }}">
                                        <input type="hidden" name="delivery_date" value="{{ optional($order->delivery_date)->format('Y-m-d') }}">
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
                                    <form action="{{ route('orders.quick-update', $order) }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="payment_status" value="pago">
                                        @foreach($order->items as $item)
                                            <input type="hidden" name="products[{{ $item->product_id }}][id]" value="{{ $item->product_id }}">
                                            <input type="hidden" name="products[{{ $item->product_id }}][quantity]" value="{{ $item->quantity }}">
                                        @endforeach
                                        <input type="hidden" name="client_id" value="{{ $order->client_id }}">
                                        <input type="hidden" name="order_date" value="{{ $order->order_date->format('Y-m-d') }}">
                                        <input type="hidden" name="ready_date" value="{{ optional($order->ready_date)->format('Y-m-d') }}">
                                        <input type="hidden" name="delivery_date" value="{{ optional($order->delivery_date)->format('Y-m-d') }}">
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
                        <!-- ENTREGUE mas NÃƒO PAGO ao Mostrar apenas botÃ£o Pago -->
                        <form action="{{ route('orders.quick-update', $order) }}" method="POST">
                            @csrf
                            <input type="hidden" name="payment_status" value="pago">
                            @foreach($order->items as $item)
                                <input type="hidden" name="products[{{ $item->product_id }}][id]" value="{{ $item->product_id }}">
                                <input type="hidden" name="products[{{ $item->product_id }}][quantity]" value="{{ $item->quantity }}">
                            @endforeach
                            <input type="hidden" name="client_id" value="{{ $order->client_id }}">
                            <input type="hidden" name="order_date" value="{{ $order->order_date->format('Y-m-d') }}">
                            <input type="hidden" name="ready_date" value="{{ optional($order->ready_date)->format('Y-m-d') }}">
                            <input type="hidden" name="delivery_date" value="{{ optional($order->delivery_date)->format('Y-m-d') }}">
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

                    <!-- BotÃƒÂµes sempre presentes (Imprimir e Eliminar) -->
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
.order-item-name {
    transition: color 0.2s ease;
}

.order-item-checked {
    color: #6c757d;
    text-decoration: line-through;
}

@media print {
    .sidebar,
    .navbar,
    .btn,
    .card-header {
        display: none !important;
    }

    .order-item-checkbox {
        display: none !important;
    }

    .main {
        padding: 0 !important;
    }

    .card {
        border: none !important;
        box-shadow: none !important;
        margin-bottom: 1rem !important;
    }

    .row {
        display: block !important;
    }

    .col-lg-8,
    .col-lg-4 {
        width: 100% !important;
        max-width: 100% !important;
        flex: 0 0 100% !important;
    }

    /* Topo em 2 colunas: Cliente (esq) + Datas (dir) */
    .col-lg-8 > .card:nth-of-type(1),
    .col-lg-8 > .card:nth-of-type(2) {
        display: inline-block !important;
        width: 49% !important;
        vertical-align: top;
    }

    .col-lg-8 > .card:nth-of-type(1) {
        margin-right: 1% !important;
    }

    /* Em baixo: Produtos em largura total */
    .col-lg-8 > .card:nth-of-type(3) {
        display: block !important;
        width: 100% !important;
        clear: both;
    }

    /* No print, no card de datas mostrar apenas Encomenda + Desejada */
    .print-hide-date-extra {
        display: none !important;
    }

    .print-date-main {
        width: 50% !important;
        max-width: 50% !important;
        flex: 0 0 50% !important;
    }

    /* Esconder extras para manter impressÃ£o limpa */
    .col-lg-8 > .card:nth-of-type(n+4) {
        display: none !important;
    }

    /* Sidebar: mostrar Resumo Financeiro no fim */
    .col-lg-4 > .card:first-child {
        display: block !important;
        width: 100% !important;
    }

    .col-lg-4 > .card:not(:first-child) {
        display: none !important;
    }
}
</style>
@endpush


