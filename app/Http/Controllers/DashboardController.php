<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use App\Models\StockMovement;
use App\Models\Payable;
use App\Models\Receivable;
use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        // Estatísticas gerais
        $totalProducts = Product::count();
        $totalCategories = Category::count();
        $totalEmployees = Employee::count();

        // Estatísticas de estoque
        $productsWithLowStock = Product::where('min_stock', '>', 0)->count();
        $totalStockValue = Product::sum(DB::raw('cost_price * min_stock'));

        // Estatísticas financeiras
        $totalPayable = Payable::where('status', 'pendente')->sum('valor');
        $totalReceivable = Receivable::where('status', 'pendente')->sum('valor');
        $totalPaid = Payable::where('status', 'pago')->sum('valor');
        $totalReceived = Receivable::where('status', 'recebido')->sum('valor');

        // Movimentações recentes
        $recentMovements = StockMovement::with('product')
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        // Produtos mais movimentados (últimos 30 dias)
        $topProducts = StockMovement::select('product_id', DB::raw('SUM(quantity) as total_movimentado'))
            ->with('product')
            ->where('created_at', '>=', Carbon::now()->subDays(30))
            ->groupBy('product_id')
            ->orderBy('total_movimentado', 'desc')
            ->limit(5)
            ->get();

        // Gráfico de movimentações por mês (últimos 6 meses)
        $movementsByMonth = StockMovement::select(
                DB::raw('MONTH(created_at) as month'),
                DB::raw('YEAR(created_at) as year'),
                DB::raw('COUNT(*) as total')
            )
            ->where('created_at', '>=', Carbon::now()->subMonths(6))
            ->groupBy('year', 'month')
            ->orderBy('year')
            ->orderBy('month')
            ->get();

        // Gráfico de contas a pagar por status
        $payablesByStatus = Payable::select('status', DB::raw('COUNT(*) as total'), DB::raw('SUM(valor) as valor_total'))
            ->groupBy('status')
            ->get();

        // Gráfico de contas a receber por status
        $receivablesByStatus = Receivable::select('status', DB::raw('COUNT(*) as total'), DB::raw('SUM(valor) as valor_total'))
            ->groupBy('status')
            ->get();

        // Fluxo de caixa (últimos 30 dias)
        $cashFlow = $this->getCashFlow();
        if (empty($cashFlow['days'])) $cashFlow['days'] = [];
        if (empty($cashFlow['payables'])) $cashFlow['payables'] = [];
        if (empty($cashFlow['receivables'])) $cashFlow['receivables'] = [];

        if ($movementsByMonth->isEmpty()) {
            $movementsByMonth = collect([['month' => now()->month, 'year' => now()->year, 'total' => 0]]);
        }
        if ($payablesByStatus->isEmpty()) {
            $payablesByStatus = collect([['status' => 'Nenhum', 'total' => 0, 'valor_total' => 0]]);
        }
        if ($receivablesByStatus->isEmpty()) {
            $receivablesByStatus = collect([['status' => 'Nenhum', 'total' => 0, 'valor_total' => 0]]);
        }

        // Produtos com estoque baixo (usando min_stock como estoque atual)
        $lowStockProducts = Product::where('min_stock', '>', 0)
            ->where('min_stock', '<=', 5) // Considerando estoque baixo se <= 5
            ->get();

        // Contas vencendo nos próximos 7 dias
        $upcomingPayables = Payable::where('status', 'pendente')
            ->whereBetween('data_vencimento', [Carbon::now(), Carbon::now()->addDays(7)])
            ->orderBy('data_vencimento')
            ->limit(5)
            ->get();

        $upcomingReceivables = Receivable::where('status', 'pendente')
            ->whereBetween('data_vencimento', [Carbon::now(), Carbon::now()->addDays(7)])
            ->orderBy('data_vencimento')
            ->limit(5)
            ->get();

        // Categorias mais utilizadas
        $topCategories = Category::withCount('products')
            ->orderBy('products_count', 'desc')
            ->limit(5)
            ->get();

        return view('dashboard.index', compact(
            'totalProducts',
            'totalCategories',
            'totalEmployees',
            'productsWithLowStock',
            'totalStockValue',
            'totalPayable',
            'totalReceivable',
            'totalPaid',
            'totalReceived',
            'recentMovements',
            'topProducts',
            'movementsByMonth',
            'payablesByStatus',
            'receivablesByStatus',
            'cashFlow',
            'lowStockProducts',
            'upcomingPayables',
            'upcomingReceivables',
            'topCategories'
        ));
    }

    private function getCashFlow()
    {
        $days = [];
        $payables = [];
        $receivables = [];

        for ($i = 29; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i);
            $days[] = $date->format('d/m');

            $payables[] = Payable::where('status', 'pago')
                ->whereDate('data_pagamento', $date)
                ->sum('valor');

            $receivables[] = Receivable::where('status', 'recebido')
                ->whereDate('data_recebimento', $date)
                ->sum('valor');
        }

        return [
            'days' => $days,
            'payables' => $payables,
            'receivables' => $receivables
        ];
    }
}
