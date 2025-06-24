<?php

namespace App\Http\Controllers;

use App\Models\StockMovement;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;

class StockMovementController extends Controller
{
    public function index()
    {
        $movements = StockMovement::with(['product', 'user'])->orderByDesc('date')->paginate(15);
        return view('stock_movements.index', compact('movements'));
    }

    public function create()
    {
        $products = Product::all();
        $users = User::all();
        return view('stock_movements.create', compact('products', 'users'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'user_id' => 'required|exists:users,id',
            'type' => 'required|in:entrada,saida',
            'movement_reason' => 'required|in:compra,devolucao,ajuste,venda,perda',
            'quantity' => 'required|integer|min:1',
            'date' => 'required|date',
            'notes' => 'nullable|string',
        ]);
        StockMovement::create($validated);
        return redirect()->route('stock_movements.index')->with('success', 'Movimentação registrada com sucesso!');
    }

    public function destroy($id)
    {
        $movement = \App\Models\StockMovement::findOrFail($id);
        $movement->delete();
        return redirect()->route('stock_movements.index')->with('success', 'Movimentação removida com sucesso!');
    }
}
