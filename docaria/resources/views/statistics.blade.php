@extends('layouts.app')

@section('title', 'Estatisticas - A Docaria')

@section('content')
<div class="row">
    <div class="col-12 d-flex flex-wrap justify-content-between align-items-center gap-2">
        <h1 class="h3 mb-0"><strong style="color: #2f4f6c;">Estatisticas</strong></h1>

        <div class="btn-group" role="group" aria-label="Filtro de periodo">
            <a href="{{ route('statistics', ['period' => '7d']) }}" class="btn btn-sm {{ $period === '7d' ? 'btn-primary' : 'btn-outline-primary' }}">7 dias</a>
            <a href="{{ route('statistics', ['period' => '30d']) }}" class="btn btn-sm {{ $period === '30d' ? 'btn-primary' : 'btn-outline-primary' }}">30 dias</a>
            <a href="{{ route('statistics', ['period' => '12m']) }}" class="btn btn-sm {{ $period === '12m' ? 'btn-primary' : 'btn-outline-primary' }}">12 meses</a>
        </div>
    </div>
</div>

<div class="row mt-2">
    <div class="col-12">
        <p class="text-muted mb-3">Periodo selecionado: <strong>{{ $periodLabel }}</strong></p>
    </div>
</div>

<!-- Row 1: KPI cards -->
<div class="row">
    <div class="col-xl-4 col-md-6">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title mb-2">Total de encomendas</h5>
                <h3 class="mb-0" style="color: #222e3c;">{{ $kpis['total_orders'] }}</h3>
            </div>
        </div>
    </div>

    <div class="col-xl-4 col-md-6">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title mb-2">Total faturado (pago)</h5>
                <h3 class="mb-0" style="color: #222e3c;">{{ number_format($kpis['total_paid'], 2, ',', '.') }} EUR</h3>
            </div>
        </div>
    </div>

    <div class="col-xl-4 col-md-6">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title mb-2">Valor total em divida</h5>
                <h3 class="mb-0" style="color: #222e3c;">{{ number_format($kpis['total_debt'], 2, ',', '.') }} EUR</h3>
            </div>
        </div>
    </div>

</div>
<!-- Row 2: Line chart - faturacao por mes -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Faturacao por Mes</h5>
            </div>
            <div class="card-body">
                <canvas id="chartFaturacaoMonth" height="100"></canvas>
            </div>
        </div>
    </div>
</div>
<!-- Row 3: Donut charts -->
<div class="row">
    <div class="col-lg-6">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Estado de Encomendas</h5>
            </div>
            <div class="card-body">
                <canvas id="chartOrdersStatus" height="140"></canvas>
            </div>
        </div>
    </div>

    <div class="col-lg-6">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Estado de Pagamentos</h5>
            </div>
            <div class="card-body">
                <canvas id="chartPaymentsStatus" height="140"></canvas>
            </div>
        </div>
    </div>
</div>

<!-- Row 4: Line chart -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Encomendas por Mês</h5>
            </div>
            <div class="card-body">
                <canvas id="chartOrdersMonth" height="100"></canvas>
            </div>
        </div>
    </div>
</div>

<!-- Row 5: Two bar charts -->
<div class="row">
    <div class="col-lg-6">
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">Top clientes</h5>
                    <button type="button" class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#allClientsModal">
                        Ver todos
                    </button>
                </div>
            </div>
            <div class="card-body">
                <canvas id="chartTopClients" height="140"></canvas>
            </div>
        </div>
    </div>
    <div class="col-lg-6">
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">Top Produtos</h5>
                    <button type="button" class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#allProductsModal">
                        Ver todos
                    </button>
                </div>
            </div>
            <div class="card-body">
                <canvas id="chartTopProducts" height="140"></canvas>
            </div>
        </div>
    </div></div>

<!-- Row 6: Tables -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Clientes com pagamentos em atraso</h5>
            </div>
            <div class="card-body table-responsive">
                <table class="table table-striped table-hover mb-0">
                    <thead>
                        <tr>
                            <th>Nome cliente</th>
                            <th>Total em divida</th>
                            <th>No encomendas pendentes</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($overdueClients as $client)
                            <tr>
                                <td>{{ $client->name }}</td>
                                <td>{{ number_format((float) $client->total_debt, 2, ',', '.') }} EUR</td>
                                <td>{{ $client->pending_orders }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="text-center text-muted">Sem dados.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Modal: Todos os clientes -->
<div class="modal fade" id="allClientsModal" tabindex="-1" aria-labelledby="allClientsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="allClientsModalLabel">Todos os clientes por faturacao</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
            </div>
            <div class="modal-body">
                <div class="table-responsive">
                    <table class="table table-striped table-hover mb-0">
                        <thead>
                            <tr>
                                <th>Cliente</th>
                                <th class="text-end">Total faturado</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($allClients as $client)
                                <tr>
                                    <td>{{ $client->name }}</td>
                                    <td class="text-end">{{ number_format((float) $client->total_faturado, 2, ',', '.') }} EUR</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="2" class="text-center text-muted">Sem dados.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal: Todos os produtos -->
<div class="modal fade" id="allProductsModal" tabindex="-1" aria-labelledby="allProductsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="allProductsModalLabel">Todos os produtos por quantidade vendida</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
            </div>
            <div class="modal-body">
                <div class="table-responsive">
                    <table class="table table-striped table-hover mb-0">
                        <thead>
                            <tr>
                                <th>Produto</th>
                                <th class="text-end">Quantidade vendida</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($allProducts as $product)
                                <tr>
                                    <td>{{ $product->name }}</td>
                                    <td class="text-end">{{ (int) $product->total_quantity }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="2" class="text-center text-muted">Sem dados.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const ordersByStatus = @json($ordersByStatus);
    const paymentsByStatus = @json($paymentsByStatus);
    const ordersByMonth = @json($ordersByMonth);
    const faturacaoByMonth = @json($faturacaoByMonth);
    const topClientsChart = @json($topClientsChart);
    const topProductsChart = @json($topProductsChart);

    const defaultLegend = {
        position: 'bottom'
    };


    new Chart(document.getElementById('chartFaturacaoMonth'), {
        type: 'line',
        data: {
            labels: faturacaoByMonth.labels,
            datasets: [{
                label: 'Faturacao (EUR)',
                data: faturacaoByMonth.data,
                borderColor: '#577c88',
                backgroundColor: 'rgba(87,124,136,0.15)',
                fill: true,
                tension: 0.3
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: { beginAtZero: true }
            }
        }
    });
    new Chart(document.getElementById('chartOrdersStatus'), {
        type: 'doughnut',
        data: {
            labels: ordersByStatus.labels,
            datasets: [{
                data: ordersByStatus.data,
                backgroundColor: ['#577c88', '#2e4357', '#c7d9e5']
            }]
        },
        options: {
            responsive: true,
            plugins: { legend: defaultLegend }
        }
    });

    new Chart(document.getElementById('chartPaymentsStatus'), {
        type: 'doughnut',
        data: {
            labels: paymentsByStatus.labels,
            datasets: [{
                data: paymentsByStatus.data,
                backgroundColor: ['#2e4357', '#577c88', '#c7d9e5']
            }]
        },
        options: {
            responsive: true,
            plugins: { legend: defaultLegend }
        }
    });

    new Chart(document.getElementById('chartOrdersMonth'), {
        type: 'line',
        data: {
            labels: ordersByMonth.labels,
            datasets: [{
                label: 'Encomendas',
                data: ordersByMonth.data,
                borderColor: '#2e4357',
                backgroundColor: 'rgba(46,67,87,0.15)',
                fill: true,
                tension: 0.3
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: { beginAtZero: true, ticks: { precision: 0 } }
            }
        }
    });
    new Chart(document.getElementById('chartTopClients'), {
        type: 'bar',
        data: {
            labels: topClientsChart.labels,
            datasets: [{
                label: 'Total faturado (EUR)',
                data: topClientsChart.data,
                backgroundColor: '#577c88'
            }]
        },
        options: {
            indexAxis: 'y',
            responsive: true,
            scales: {
                x: { beginAtZero: true }
            }
        }
    });

    new Chart(document.getElementById('chartTopProducts'), {
        type: 'bar',
        data: {
            labels: topProductsChart.labels,
            datasets: [{
                label: 'Quantidade vendida',
                data: topProductsChart.data,
                backgroundColor: '#c7d9e5'
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: { beginAtZero: true, ticks: { precision: 0 } }
            }
        }
    });</script>
@endpush




