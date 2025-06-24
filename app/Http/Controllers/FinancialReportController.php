<?php

namespace App\Http\Controllers;

use App\Models\Payable;
use App\Models\Receivable;
use Illuminate\Http\Request;
use Carbon\Carbon;

class FinancialReportController extends Controller
{
    public function index()
    {
        return view('financial_reports.index');
    }

    public function dashboard()
    {
        $mesAtual = Carbon::now()->month;
        $anoAtual = Carbon::now()->year;

        // Contas a Pagar
        $totalPagarPendente = Payable::where('status', 'pendente')->sum('valor');
        $totalPagarPago = Payable::where('status', 'pago')->sum('valor');
        $totalPagarAtrasado = Payable::where('status', 'atrasado')->sum('valor');
        $pagarMesAtual = Payable::whereMonth('data_vencimento', $mesAtual)
            ->whereYear('data_vencimento', $anoAtual)
            ->sum('valor');

        // Contas a Receber
        $totalReceberPendente = Receivable::where('status', 'pendente')->sum('valor');
        $totalReceberRecebido = Receivable::where('status', 'recebido')->sum('valor');
        $totalReceberAtrasado = Receivable::where('status', 'atrasado')->sum('valor');
        $receberMesAtual = Receivable::whereMonth('data_vencimento', $mesAtual)
            ->whereYear('data_vencimento', $anoAtual)
            ->sum('valor');

        // Vencimentos próximos (próximos 7 dias)
        $vencimentosProximosPagar = Payable::where('status', 'pendente')
            ->whereBetween('data_vencimento', [now(), now()->addDays(7)])
            ->orderBy('data_vencimento')
            ->get();

        $vencimentosProximosReceber = Receivable::where('status', 'pendente')
            ->whereBetween('data_vencimento', [now(), now()->addDays(7)])
            ->orderBy('data_vencimento')
            ->get();

        return view('financial_reports.dashboard', compact(
            'totalPagarPendente',
            'totalPagarPago',
            'totalPagarAtrasado',
            'pagarMesAtual',
            'totalReceberPendente',
            'totalReceberRecebido',
            'totalReceberAtrasado',
            'receberMesAtual',
            'vencimentosProximosPagar',
            'vencimentosProximosReceber'
        ));
    }

    public function fluxoCaixa(Request $request)
    {
        $dataInicio = $request->input('data_inicio', now()->startOfMonth());
        $dataFim = $request->input('data_fim', now()->endOfMonth());

        // Contas a Pagar no período
        $pagarPeriodo = Payable::whereBetween('data_vencimento', [$dataInicio, $dataFim])
            ->selectRaw('DATE(data_vencimento) as data, SUM(valor) as total')
            ->groupBy('data')
            ->orderBy('data')
            ->get();

        // Contas a Receber no período
        $receberPeriodo = Receivable::whereBetween('data_vencimento', [$dataInicio, $dataFim])
            ->selectRaw('DATE(data_vencimento) as data, SUM(valor) as total')
            ->groupBy('data')
            ->orderBy('data')
            ->get();

        return view('financial_reports.fluxo_caixa', compact(
            'pagarPeriodo',
            'receberPeriodo',
            'dataInicio',
            'dataFim'
        ));
    }

    public function categorias(Request $request)
    {
        $dataInicio = $request->input('data_inicio', now()->startOfMonth());
        $dataFim = $request->input('data_fim', now()->endOfMonth());

        // Top categorias a pagar
        $topCategoriasPagar = Payable::whereBetween('data_vencimento', [$dataInicio, $dataFim])
            ->selectRaw('categoria, SUM(valor) as total, COUNT(*) as quantidade')
            ->groupBy('categoria')
            ->orderByDesc('total')
            ->limit(10)
            ->get();

        // Top categorias a receber
        $topCategoriasReceber = Receivable::whereBetween('data_vencimento', [$dataInicio, $dataFim])
            ->selectRaw('categoria, SUM(valor) as total, COUNT(*) as quantidade')
            ->groupBy('categoria')
            ->orderByDesc('total')
            ->limit(10)
            ->get();

        return view('financial_reports.categorias', compact(
            'topCategoriasPagar',
            'topCategoriasReceber',
            'dataInicio',
            'dataFim'
        ));
    }

    public function pessoas(Request $request)
    {
        $dataInicio = $request->input('data_inicio', now()->startOfMonth());
        $dataFim = $request->input('data_fim', now()->endOfMonth());

        // Top pessoas a pagar
        $topPessoasPagar = Payable::whereBetween('data_vencimento', [$dataInicio, $dataFim])
            ->selectRaw('pessoa, SUM(valor) as total, COUNT(*) as quantidade')
            ->groupBy('pessoa')
            ->orderByDesc('total')
            ->limit(15)
            ->get();

        // Top pessoas a receber
        $topPessoasReceber = Receivable::whereBetween('data_vencimento', [$dataInicio, $dataFim])
            ->selectRaw('pessoa, SUM(valor) as total, COUNT(*) as quantidade')
            ->groupBy('pessoa')
            ->orderByDesc('total')
            ->limit(15)
            ->get();

        return view('financial_reports.pessoas', compact(
            'topPessoasPagar',
            'topPessoasReceber',
            'dataInicio',
            'dataFim'
        ));
    }
}
