@extends('layouts.app')

@section('title', 'Encomendas - A Docaria')

@push('styles')
<!-- Flatpickr CSS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/themes/light.css">

<style>
    .filters-container {
        max-height: 0;
        overflow: hidden;
        transition: max-height 0.3s ease-out;
    }
    .filters-container.show {
        max-height: 500px;
        transition: max-height 0.5s ease-in;
    }
    .filter-toggle-btn {
        cursor: pointer;
    }
    /* CustomizaÃ§Ã£o do Flatpickr */
    .flatpickr-input {
        background: white;
    }
    .flatpickr-calendar {
        font-family: 'Inter', sans-serif;
    }
    .orders-table thead th,
    .orders-table tbody td {
        padding-top: 0.85rem;
        padding-bottom: 0.85rem;
        vertical-align: middle;
    }
    .orders-actions {
        display: inline-flex;
        align-items: center;
        gap: 0.35rem;
    }
    .status-filter-select {
        min-width: 170px;
    }
</style>
@endpush

@section('content')
<div class="row mb-2 mb-xl-3">
    <div class="col-auto d-none d-sm-block">
        <h3><strong style="color: #2f4f6c;">Encomendas</strong></h3>
    </div>

    <div class="col-auto ms-auto text-end mt-n1">
        <a href="{{ route('orders.create') }}" class="btn btn-primary">
            <i class="align-middle" data-feather="plus"></i> Nova Encomenda
        </a>
    </div>
</div>

<!-- Card de Filtros -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center filter-toggle-btn" id="filterToggle">
                <h5 class="card-title mb-0">
                    <i class="align-middle" data-feather="filter"></i> Filtrar Encomendas
                </h5>
                <button type="button" class="btn btn-sm btn-outline-primary">
                    <i class="align-middle" data-feather="chevron-down" id="filterIcon"></i>
                    <span id="filterText">Mostrar Filtros</span>
                </button>
            </div>
            
            <!-- Filtros (Inicialmente escondidos) -->
            <div class="filters-container" id="filtersContainer">
                <div class="card-body">
                    <form method="GET" action="{{ route('orders.index') }}" id="filterForm">
                        <div class="row g-3">
                            <!-- Pesquisa -->
                            <div class="col-md-3">
                                <label class="form-label">Pesquisar</label>
                                <input type="text" name="search" class="form-control" 
                                       placeholder="Nº Fatura ou Cliente..." 
                                       value="{{ request('search') }}">
                            </div>

                            <!-- Status -->
                            <div class="col-md-3 col-lg-3">
                                <label class="form-label">Status</label>
                                <select name="status" class="form-select status-filter-select">
                                    <option value="">Todos</option>
                                    <option value="preparacao" {{ request('status') == 'preparacao' ? 'selected' : '' }}>Em Preparação</option>
                                    <option value="concluido" {{ request('status') == 'concluido' ? 'selected' : '' }}>Concluí­do</option>
                                    <option value="entregue" {{ request('status') == 'entregue' ? 'selected' : '' }}>Entregue</option>
                                </select>
                            </div>

                            <!-- Pagamento -->
                            <div class="col-md-2">
                                <label class="form-label">Pagamento</label>
                                <select name="payment_status" class="form-select">
                                    <option value="">Todos</option>
                                    <option value="nao_pago" {{ request('payment_status') == 'nao_pago' ? 'selected' : '' }}>Não Pago</option>
                                    <option value="parcial" {{ request('payment_status') == 'parcial' ? 'selected' : '' }}>Parcial</option>
                                    <option value="pago" {{ request('payment_status') == 'pago' ? 'selected' : '' }}>Pago</option>
                                </select>
                            </div>

                            <!-- Cliente por nome -->
                            <div class="col-md-3">
                                <label class="form-label">Cliente</label>
                                <input type="text" name="client_name" class="form-control" placeholder="Nome do cliente..." value="{{ request('client_name') }}">
                            </div>
<!-- Data Iní­cio com Flatpickr -->
                            <div class="col-md-3">
                                <label class="form-label">Data Iní­cio</label>
                                <input type="text" 
                                       name="date_from" 
                                       id="dateFrom" 
                                       class="form-control datepicker" 
                                       placeholder="dd/mm/aaaa"
                                       value="{{ request('date_from') ? \Carbon\Carbon::parse(request('date_from'))->format('d/m/Y') : '' }}"
                                       autocomplete="off">
                                <input type="hidden" name="date_from_hidden" id="dateFromHidden" value="{{ request('date_from') }}">
                            </div>

                            <!-- Data Fim com Flatpickr -->
                            <div class="col-md-3">
                                <label class="form-label">Data Fim</label>
                                <input type="text" 
                                       name="date_to" 
                                       id="dateTo" 
                                       class="form-control datepicker" 
                                       placeholder="dd/mm/aaaa"
                                       value="{{ request('date_to') ? \Carbon\Carbon::parse(request('date_to'))->format('d/m/Y') : '' }}"
                                       autocomplete="off">
                                <input type="hidden" name="date_to_hidden" id="dateToHidden" value="{{ request('date_to') }}">
                            </div>
                            <!-- Botões -->
                            <div class="col-12 d-flex align-items-end justify-content-end">
                                <button type="submit" class="btn btn-primary me-2">
                                    <i class="align-middle" data-feather="filter"></i> Filtrar
                                </button>
                                <a href="{{ route('orders.index') }}" class="btn btn-secondary">
                                    <i class="align-middle" data-feather="x"></i> Limpar
                                </a>
                            </div>

                            <!-- Info sobre filtro ativo -->
                            @if(request()->hasAny(['search', 'status', 'payment_status', 'client_name', 'date_from', 'date_to']) && request('status') != 'preparacao')
                                <div class="col-12">
                                    <div class="alert alert-info mb-0 d-flex justify-content-between align-items-center">
                                        <span>
                                            <i class="align-middle" data-feather="info"></i>
                                            Filtros ativos. 
                                            <a href="{{ route('orders.index') }}" class="alert-link">Clique aqui</a> 
                                            para ver apenas encomendas em preparação.
                                        </span>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Tabela de Encomendas -->
<div class="row mt-3">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    Lista de Encomendas
                    @if(request('status') == 'preparacao' && !request()->hasAny(['search', 'payment_status', 'client_name', 'date_from', 'date_to']))
                        <span class="badge bg-warning">Em Preparação</span>
                    @endif
                </h5>
            </div>
            <div class="card-body">
                @if($orders->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover orders-table">
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
                                        <td class="client-cell"
                                            data-bs-toggle="tooltip"
                                            data-bs-placement="top"
                                            title="{{ $order->client->name }}">

                                            {{ \Illuminate\Support\Str::limit($order->client->name, 25) }}
                                        </td>
                                        <td>{{ $order->order_date->format('d/m/Y') }}</td>
                                        <td>{{ $order->delivery_date ? $order->delivery_date->format('d/m/Y') : 'N/A' }}</td>
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
                                            <div class="orders-actions">
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
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- PaginaÃ§Ã£o -->
                    <div class="d-flex justify-content-between align-items-center mt-3">
                        <div>
                            Mostrando de {{ $orders->firstItem() }} a {{ $orders->lastItem() }} de {{ $orders->total() }} encomendas
                        </div>
                        <div>
                            {{ $orders->appends(request()->query())->links('pagination::bootstrap-5') }}
                        </div>
                    </div>
                @else
                    <div class="alert alert-info mb-0">
                        <i class="align-middle" data-feather="info"></i>
                        Nenhuma encomenda encontrada. Tente ajustar os filtros.
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- Flatpickr JS -->
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/pt.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // ========================================
    // 1. TOGGLE DOS FILTROS
    // ========================================
    const filterToggle = document.getElementById('filterToggle');
    const filtersContainer = document.getElementById('filtersContainer');
    const filterIcon = document.getElementById('filterIcon');
    const filterText = document.getElementById('filterText');

    const hasActiveFilters = {{ 
        request()->hasAny(['search', 'payment_status', 'client_name', 'date_from', 'date_to']) || 
        (request('status') && request('status') != 'preparacao') ? 'true' : 'false' 
    }};

    if (hasActiveFilters) {
        filtersContainer.classList.add('show');
        filterText.textContent = 'Esconder Filtros';
        filterIcon.setAttribute('data-feather', 'chevron-up');
        if (typeof feather !== 'undefined') {
            feather.replace();
        }
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
        
        if (typeof feather !== 'undefined') {
            feather.replace();
        }
    });

    // ========================================
    // 3. INICIALIZAR FLATPICKR (DATAS EM PORTUGUÃŠS)
    // ========================================
    
    // Data InÃ­cio
    const dateFromPicker = flatpickr("#dateFrom", {
        dateFormat: "d/m/Y",      // Formato visual: dd/mm/yyyy
        altInput: false,
        allowInput: true,
        locale: "pt",              // PortuguÃªs
        onChange: function(selectedDates, dateStr, instance) {
            if (selectedDates.length > 0) {
                // Converter para yyyy-mm-dd para o backend
                const year = selectedDates[0].getFullYear();
                const month = String(selectedDates[0].getMonth() + 1).padStart(2, '0');
                const day = String(selectedDates[0].getDate()).padStart(2, '0');
                document.getElementById('dateFromHidden').value = `${year}-${month}-${day}`;
            } else {
                document.getElementById('dateFromHidden').value = '';
            }
        }
    });

    // Data Fim
    const dateToPicker = flatpickr("#dateTo", {
        dateFormat: "d/m/Y",
        altInput: false,
        allowInput: true,
        locale: "pt",
        onChange: function(selectedDates, dateStr, instance) {
            if (selectedDates.length > 0) {
                const year = selectedDates[0].getFullYear();
                const month = String(selectedDates[0].getMonth() + 1).padStart(2, '0');
                const day = String(selectedDates[0].getDate()).padStart(2, '0');
                document.getElementById('dateToHidden').value = `${year}-${month}-${day}`;
            } else {
                document.getElementById('dateToHidden').value = '';
            }
        }
    });

    // ========================================
    // 4. SUBMETER FORM COM VALORES CORRETOS
    // ========================================
    document.getElementById('filterForm').addEventListener('submit', function(e) {
        // Substituir valores dos campos visÃ­veis pelos hidden
        const dateFromHidden = document.getElementById('dateFromHidden').value;
        const dateToHidden = document.getElementById('dateToHidden').value;
        
        if (dateFromHidden) {
            document.getElementById('dateFrom').name = '';
            document.getElementById('dateFromHidden').name = 'date_from';
        }
        
        if (dateToHidden) {
            document.getElementById('dateTo').name = '';
            document.getElementById('dateToHidden').name = 'date_to';
        }
    });

    // ========================================
    // 5. INICIALIZAR FEATHER ICONS
    // ========================================
    if (typeof feather !== 'undefined') {
        feather.replace();
    }
});
</script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('  [data-bs-toggle="tooltip"]'))
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl)
        })
    });
</script>
@endpush










