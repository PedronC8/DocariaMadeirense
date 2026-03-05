<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EstatisticasController extends Controller
{
    /**
     * Mostra a página de estatísticas com KPIs, gráficos e tabelas.
     */
    public function index(Request $request)
    {
        $period = $this->resolvePeriod($request->query('period', '30d'));
        [$startDate, $endDate] = $this->periodRange($period);
        $periodStart = $startDate->copy()->startOfDay()->toDateTimeString();
        $periodEnd = $endDate->copy()->endOfDay()->toDateTimeString();

        $ordersInPeriod = DB::table('orders')
            ->whereBetween('order_date', [$periodStart, $periodEnd]);

        // KPIs
        $kpis = [
            'total_orders' => (clone $ordersInPeriod)->count(),
            'total_paid' => (float) (clone $ordersInPeriod)->where('payment_status', 'pago')->sum('total'),
            'total_debt' => (float) (clone $ordersInPeriod)->whereIn('payment_status', ['nao_pago', 'parcial'])->sum('total'),
            'preparation_orders' => (clone $ordersInPeriod)->where('status', 'preparacao')->count(),
        ];
        $kpis['total_billed'] = $kpis['total_paid'] + $kpis['total_debt'];

        // Donut: encomendas por estado
        $ordersByStatusRaw = (clone $ordersInPeriod)
            ->select('status', DB::raw('COUNT(*) as total'))
            ->groupBy('status')
            ->pluck('total', 'status');

        $ordersByStatus = [
            'labels' => ['Em preparação', 'Concluído', 'Entregue'],
            'data' => [
                (int) ($ordersByStatusRaw['preparacao'] ?? 0),
                (int) ($ordersByStatusRaw['concluido'] ?? 0),
                (int) ($ordersByStatusRaw['entregue'] ?? 0),
            ],
        ];

        // Donut: pagamentos por estado
        $paymentsByStatusRaw = (clone $ordersInPeriod)
            ->select('payment_status', DB::raw('COUNT(*) as total'))
            ->groupBy('payment_status')
            ->pluck('total', 'payment_status');

        $paymentsByStatus = [
            'labels' => ['Pago', 'Parcial', 'Não pago'],
            'data' => [
                (int) ($paymentsByStatusRaw['pago'] ?? 0),
                (int) ($paymentsByStatusRaw['parcial'] ?? 0),
                (int) ($paymentsByStatusRaw['nao_pago'] ?? 0),
            ],
        ];

        // Line: encomendas por mês (últimos 12 meses)
        $lineStart = Carbon::now()->subMonths(11)->startOfMonth();
        $lineEnd = Carbon::now()->endOfMonth();
        $lineStartDateTime = $lineStart->copy()->startOfDay()->toDateTimeString();
        $lineEndDateTime = $lineEnd->copy()->endOfDay()->toDateTimeString();

        $ordersByMonthRaw = DB::table('orders')
            ->selectRaw('YEAR(order_date) as year_num, MONTH(order_date) as month_num, COUNT(*) as total')
            ->whereBetween('order_date', [$lineStartDateTime, $lineEndDateTime])
            ->groupBy('year_num', 'month_num')
            ->orderBy('year_num')
            ->orderBy('month_num')
            ->get();

        $ordersByMonthMap = $ordersByMonthRaw
            ->mapWithKeys(function ($row) {
                $month = str_pad((string) $row->month_num, 2, '0', STR_PAD_LEFT);
                return [$row->year_num . '-' . $month => (int) $row->total];
            });

        $ordersByMonthLabels = [];
        $ordersByMonthData = [];
        $monthsPeriod = CarbonPeriod::create($lineStart, '1 month', $lineEnd);

        foreach ($monthsPeriod as $month) {
            $key = $month->format('Y-m');
            $ordersByMonthLabels[] = ucfirst($month->locale('pt')->translatedFormat('M/Y'));
            $ordersByMonthData[] = (int) ($ordersByMonthMap[$key] ?? 0);
        }

        $ordersByMonth = [
            'labels' => $ordersByMonthLabels,
            'data' => $ordersByMonthData,
        ];


        // Line: faturacao por mes (ultimos 12 meses)
        $faturacaoByMonthRaw = DB::table('orders')
            ->selectRaw('YEAR(order_date) as year_num, MONTH(order_date) as month_num, SUM(total) as total')
            ->whereBetween('order_date', [$lineStartDateTime, $lineEndDateTime])
            ->groupBy('year_num', 'month_num')
            ->orderBy('year_num')
            ->orderBy('month_num')
            ->get();

        $faturacaoByMonthMap = $faturacaoByMonthRaw
            ->mapWithKeys(function ($row) {
                $month = str_pad((string) $row->month_num, 2, '0', STR_PAD_LEFT);
                return [$row->year_num . '-' . $month => (float) $row->total];
            });

        $faturacaoByMonthData = [];
        $monthsPeriodFaturacao = CarbonPeriod::create($lineStart, '1 month', $lineEnd);

        foreach ($monthsPeriodFaturacao as $month) {
            $key = $month->format('Y-m');
            $faturacaoByMonthData[] = (float) ($faturacaoByMonthMap[$key] ?? 0);
        }

        $faturacaoByMonth = [
            'labels' => $ordersByMonthLabels,
            'data' => $faturacaoByMonthData,
        ];
        // Bar horizontal: top 5 clientes por faturação
        $topClients = DB::table('orders as o')
            ->join('clients as c', 'c.id', '=', 'o.client_id')
            ->whereBetween('o.order_date', [$periodStart, $periodEnd])
            ->select('c.name', DB::raw('SUM(o.total) as total_faturado'))
            ->groupBy('c.id', 'c.name')
            ->orderByDesc('total_faturado')
            ->limit(5)
            ->get();

        $topClientsChart = [
            'labels' => $topClients->pluck('name')->values(),
            'data' => $topClients->pluck('total_faturado')->map(fn ($value) => (float) $value)->values(),
        ];

        // Lista completa: clientes por faturação (sem limite)
        $allClients = DB::table('orders as o')
            ->join('clients as c', 'c.id', '=', 'o.client_id')
            ->whereBetween('o.order_date', [$periodStart, $periodEnd])
            ->select('c.name', DB::raw('SUM(o.total) as total_faturado'))
            ->groupBy('c.id', 'c.name')
            ->orderByDesc('total_faturado')
            ->get();

        // Bar: produtos mais vendidos (top 5 por quantidade)
        $topProducts = DB::table('order_items as oi')
            ->join('orders as o', 'o.id', '=', 'oi.order_id')
            ->join('products as p', 'p.id', '=', 'oi.product_id')
            ->whereBetween('o.order_date', [$periodStart, $periodEnd])
            ->select('p.name', DB::raw('SUM(oi.quantity) as total_quantity'))
            ->groupBy('p.id', 'p.name')
            ->orderByDesc('total_quantity')
            ->limit(5)
            ->get();

        $topProductsChart = [
            'labels' => $topProducts->pluck('name')->values(),
            'data' => $topProducts->pluck('total_quantity')->map(fn ($value) => (int) $value)->values(),
        ];

        // Lista completa: produtos mais vendidos (sem limite)
        $allProducts = DB::table('order_items as oi')
            ->join('orders as o', 'o.id', '=', 'oi.order_id')
            ->join('products as p', 'p.id', '=', 'oi.product_id')
            ->whereBetween('o.order_date', [$periodStart, $periodEnd])
            ->select('p.name', DB::raw('SUM(oi.quantity) as total_quantity'))
            ->groupBy('p.id', 'p.name')
            ->orderByDesc('total_quantity')
            ->get();
        // Tabela: clientes com pagamentos em atraso (sem filtro temporal)
        $overdueClients = DB::table('orders as o')
            ->join('clients as c', 'c.id', '=', 'o.client_id')
            ->whereIn('o.payment_status', ['nao_pago', 'parcial'])
            ->select(
                'c.name',
                DB::raw('SUM(o.total) as total_debt'),
                DB::raw('COUNT(o.id) as pending_orders')
            )
            ->groupBy('c.id', 'c.name')
            ->orderByDesc('total_debt')
            ->limit(10)
            ->get();
        return view('statistics', [
            'period' => $period,
            'periodLabel' => $this->periodLabel($period),
            'kpis' => $kpis,
            'ordersByStatus' => $ordersByStatus,
            'paymentsByStatus' => $paymentsByStatus,
            'ordersByMonth' => $ordersByMonth,
            'faturacaoByMonth' => $faturacaoByMonth,
            'topClientsChart' => $topClientsChart,
            'topProductsChart' => $topProductsChart,
            'allClients' => $allClients,
            'allProducts' => $allProducts,
            'overdueClients' => $overdueClients,
        ]);
    }

    /**
     * Valida o período recebido por query string.
     */
    private function resolvePeriod(string $period): string
    {
        return in_array($period, ['7d', '30d', '12m'], true) ? $period : '30d';
    }

    /**
     * Converte período para intervalo de datas.
     */
    private function periodRange(string $period): array
    {
        $end = Carbon::now()->endOfDay();

        $start = match ($period) {
            '7d' => Carbon::now()->subDays(6)->startOfDay(),
            '12m' => Carbon::now()->subMonths(11)->startOfMonth(),
            default => Carbon::now()->subDays(29)->startOfDay(),
        };

        return [$start, $end];
    }

    /**
     * Label do período para mostrar no cabeçalho.
     */
    private function periodLabel(string $period): string
    {
        return match ($period) {
            '7d' => 'Últimos 7 dias',
            '12m' => 'Últimos 12 meses',
            default => 'Últimos 30 dias',
        };
    }
}

