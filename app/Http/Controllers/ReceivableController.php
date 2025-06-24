<?php

namespace App\Http\Controllers;

use App\Models\Receivable;
use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReceivableController extends Controller
{
    public function index(Request $request)
    {
        $query = Receivable::with('criador');

        // Filtros
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('pessoa')) {
            $query->where('pessoa', 'like', '%' . $request->pessoa . '%');
        }
        if ($request->filled('categoria')) {
            $query->where('categoria', $request->categoria);
        }
        if ($request->filled('date_start')) {
            $query->where('data_vencimento', '>=', $request->date_start);
        }
        if ($request->filled('date_end')) {
            $query->where('data_vencimento', '<=', $request->date_end);
        }

        $receivables = $query->orderBy('data_vencimento')->paginate(15);

        // Estatísticas
        $totalPendente = Receivable::where('status', 'pendente')->sum('valor');
        $totalRecebido = Receivable::where('status', 'recebido')->sum('valor');
        $totalAtrasado = Receivable::where('status', 'atrasado')->sum('valor');

        return view('receivables.index', compact('receivables', 'totalPendente', 'totalRecebido', 'totalAtrasado', 'request'));
    }

    public function create()
    {
        $employees = Employee::where('active', true)->get();
        return view('receivables.create', compact('employees'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'descricao' => 'required|string|max:255',
            'pessoa' => 'required|string|max:255',
            'categoria' => 'required|string|max:100',
            'valor' => 'required|numeric|min:0.01',
            'data_vencimento' => 'required|date',
            'forma_recebimento' => 'required|string|max:100',
            'observacoes' => 'nullable|string',
            'comprovante' => 'nullable|string|max:255',
        ]);

        $validated['criado_por'] = Auth::id();
        $validated['status'] = 'pendente';

        Receivable::create($validated);

        return redirect()->route('receivables.index')->with('success', 'Conta a receber registrada com sucesso!');
    }

    public function show(Receivable $receivable)
    {
        return view('receivables.show', compact('receivable'));
    }

    public function edit(Receivable $receivable)
    {
        $employees = Employee::where('active', true)->get();
        return view('receivables.edit', compact('receivable', 'employees'));
    }

    public function update(Request $request, Receivable $receivable)
    {
        $validated = $request->validate([
            'descricao' => 'required|string|max:255',
            'pessoa' => 'required|string|max:255',
            'categoria' => 'required|string|max:100',
            'valor' => 'required|numeric|min:0.01',
            'data_vencimento' => 'required|date',
            'data_recebimento' => 'nullable|date',
            'status' => 'required|in:pendente,recebido,atrasado',
            'forma_recebimento' => 'required|string|max:100',
            'observacoes' => 'nullable|string',
            'comprovante' => 'nullable|string|max:255',
        ]);

        $receivable->update($validated);

        return redirect()->route('receivables.index')->with('success', 'Conta a receber atualizada com sucesso!');
    }

    public function destroy(Receivable $receivable)
    {
        $receivable->delete();
        return redirect()->route('receivables.index')->with('success', 'Conta a receber removida com sucesso!');
    }

    public function marcarComoRecebido(Receivable $receivable)
    {
        $receivable->update([
            'status' => 'recebido',
            'data_recebimento' => now()->toDateString()
        ]);

        return redirect()->route('receivables.index')->with('success', 'Conta marcada como recebida!');
    }
}
