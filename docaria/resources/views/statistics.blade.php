@extends('layouts.app')

@section('title', 'Estatisticas - A Docaria')

@section('content')
<div class="row">
    <div class="col-12 d-flex flex-wrap justify-content-between align-items-center gap-2">
        <h1 class="h3 mb-0"><strong>Estatisticas</strong></h1>

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
    <div class="col-xl-3 col-md-6">
        <div class="card">
            <div class="card-body">
                <h6 class="text-muted mb-1">Total de encomendas</h6>
                <h3 class="mb-0">{{ $kpis['total_orders'] }}</h3>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6">
        <div class="card">
            <div class="card-body">
                <h6 class="text-muted mb-1">Total faturado (pago)</h6>
                <h3 class="mb-0">{{ number_format($kpis['total_paid'], 2, ',', '.') }} EUR</h3>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6">
        <div class="card">
            <div class="card-body">
                <h6 class="text-muted mb-1">Valor total em divida</h6>
                <h3 class="mb-0">{{ number_format($kpis['total_debt'], 2, ',', '.') }} EUR</h3>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6">
        <div class="card">
            <div class="card-body">
                <h6 class="text-muted mb-1">Encomendas em preparacao</h6>
                <h3 class="mb-0">{{ $kpis['preparation_orders'] }}</h3>
            </div>
        </div>
    </div>
</div>

<!-- Row 2: Donut charts -->
<div class="row">
    <div class="col-lg-6">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Encomendas por estado</h5>
            </div>
            <div class="card-body">
                <canvas id="chartOrdersStatus" height="140"></canvas>
            </div>
        </div>
    </div>

    <div class="col-lg-6">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Pagamentos por estado</h5>
            </div>
            <div class="card-body">
                <canvas id="chartPaymentsStatus" height="140"></canvas>
            </div>
        </div>
    </div>
</div>

<!-- Row 3: Line chart -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Encomendas por mes (ultimos 12 meses)</h5>
            </div>
            <div class="card-body">
                <canvas id="chartOrdersMonth" height="100"></canvas>
            </div>
        </div>
    </div>
</div>

<!-- Row 4: Two bar charts -->
<div class="row">
    <div class="col-lg-6">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Top 5 clientes por faturacao</h5>
            </div>
            <div class="card-body">
                <canvas id="chartTopClients" height="140"></canvas>
            </div>
        </div>
    </div>
    <div class="col-lg-6">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Produtos mais vendidos (top 5)</h5>
            </div>
            <div class="card-body">
                <canvas id="chartTopProducts" height="140"></canvas>
            </div>
        </div>
    </div></div>

<!-- Row 5: Tables -->
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
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const ordersByStatus = @json($ordersByStatus);
    const paymentsByStatus = @json($paymentsByStatus);
    const ordersByMonth = @json($ordersByMonth);
    const topClientsChart = @json($topClientsChart);
    const topProductsChart = @json($topProductsChart);

    const defaultLegend = {
        position: 'bottom'
    };

    new Chart(document.getElementById('chartOrdersStatus'), {
        type: 'doughnut',
        data: {
            labels: ordersByStatus.labels,
            datasets: [{
                data: ordersByStatus.data,
                backgroundColor: ['#f59e0b', '#0ea5e9', '#22c55e']
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
                backgroundColor: ['#22c55e', '#f59e0b', '#ef4444']
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
                borderColor: '#3b82f6',
                backgroundColor: 'rgba(59,130,246,0.15)',
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
                backgroundColor: '#14b8a6'
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
                backgroundColor: '#f97316'
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
