<?php

namespace App\Http\Controllers;

use App\Models\CashRegister;
use App\Models\Sale;
use App\Models\SaleItem;
use App\Models\SalePayment;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PDVController extends Controller
{
    public function index(Request $request)
    {
        // Busca produtos para autocomplete e consulta de preço
        $products = Product::all();
        // Busca vendas abertas do usuário (carrinho em andamento)
        $sale = Sale::where('user_id', Auth::id())
            ->where('status', 'in_progress')
            ->with(['items.product', 'payments'])
            ->latest()->first();
        // Busca caixa aberto do usuário
        $register = \App\Models\CashRegister::where('user_id', Auth::id())->where('status', 'open')->latest()->first();
        return view('pdv.full', compact('products', 'sale', 'register'));
    }

    public function startSale(Request $request)
    {
        $register = CashRegister::where('user_id', Auth::id())->where('status', 'open')->latest()->first();
        if (!$register) {
            return back()->with('error', 'Abra um caixa antes de iniciar uma venda.');
        }
        $sale = Sale::create([
            'cash_register_id' => $register->id,
            'user_id' => Auth::id(),
            'total' => 0,
            'discount' => 0,
            'final_total' => 0,
            'status' => 'in_progress',
            'sold_at' => null,
        ]);
        return redirect()->route('pdv.full');
    }

    public function addItem(Request $request)
    {
        Log::info('PDV addItem chamado', [
            'user_id' => Auth::id(),
            'product_id' => $request->input('product_id'),
            'quantity' => $request->input('quantity'),
            'all' => $request->all(),
            'method' => $request->method(),
            'url' => $request->url(),
        ]);
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
        ]);
        $sale = Sale::where('user_id', Auth::id())->where('status', 'in_progress')->latest()->firstOrFail();
        $product = Product::findOrFail($request->product_id);
        $totalPrice = $product->price * $request->quantity;
        SaleItem::create([
            'sale_id' => $sale->id,
            'product_id' => $product->id,
            'quantity' => $request->quantity,
            'unit_price' => $product->price,
            'total_price' => $totalPrice,
        ]);
        $sale->total += $totalPrice;
        $sale->final_total = $sale->total - $sale->discount;
        $sale->save();
        return redirect('/pdv/full');
    }

    public function removeItem($itemId)
    {
        $item = SaleItem::findOrFail($itemId);
        $sale = $item->sale;
        $sale->total -= $item->total_price;
        $sale->final_total = $sale->total - $sale->discount;
        $sale->save();
        $item->delete();
        return redirect()->route('pdv.index');
    }

    public function applyDiscount(Request $request)
    {
        $request->validate([
            'discount' => 'required|numeric|min:0',
        ]);
        $sale = Sale::where('user_id', Auth::id())->where('status', 'in_progress')->latest()->firstOrFail();
        $sale->discount = $request->discount;
        $sale->final_total = $sale->total - $sale->discount;
        $sale->save();
        return redirect()->route('pdv.index');
    }

    public function addPayment(Request $request)
    {
        $request->validate([
            'payment_type' => 'required|string',
            'amount' => 'required|numeric|min:0.01',
        ]);
        $sale = Sale::where('user_id', Auth::id())->where('status', 'in_progress')->latest()->firstOrFail();
        SalePayment::create([
            'sale_id' => $sale->id,
            'payment_type' => $request->payment_type,
            'amount' => $request->amount,
        ]);
        return redirect()->route('pdv.index');
    }

    public function finalizeWithInvoice(Request $request)
    {
        $sale = Sale::where('user_id', Auth::id())->where('status', 'in_progress')->with('items.product')->latest()->firstOrFail();
        \DB::transaction(function () use ($sale) {
            foreach ($sale->items as $item) {
                $product = $item->product;
                $product->stock -= $item->quantity;
                $product->save();
            }
            $sale->status = 'completed';
            $sale->sold_at = now();
            $sale->save();
            $register = $sale->cashRegister;
            $register->movements()->create([
                'user_id' => $sale->user_id,
                'type' => 'sale',
                'amount' => $sale->final_total,
                'description' => 'Venda PDV (NF) #' . $sale->id,
            ]);
        });
        // Aqui você pode acionar a geração de nota fiscal eletrônica
        return redirect()->route('pdv.receipt', $sale->id);
    }

    public function finalizeWithoutInvoice(Request $request)
    {
        $sale = Sale::where('user_id', Auth::id())->where('status', 'in_progress')->with('items.product')->latest()->firstOrFail();
        \DB::transaction(function () use ($sale) {
            foreach ($sale->items as $item) {
                $product = $item->product;
                $product->stock -= $item->quantity;
                $product->save();
            }
            $sale->status = 'completed';
            $sale->sold_at = now();
            $sale->save();
            $register = $sale->cashRegister;
            $register->movements()->create([
                'user_id' => $sale->user_id,
                'type' => 'sale',
                'amount' => $sale->final_total,
                'description' => 'Venda PDV (Sem NF) #' . $sale->id,
            ]);
        });
        return redirect()->route('pdv.receipt', $sale->id);
    }

    public function cancelSale(Request $request)
    {
        $sale = Sale::where('user_id', Auth::id())->where('status', 'in_progress')->latest()->first();
        if ($sale) {
            $sale->delete();
        }
        return redirect()->route('pdv.full');
    }

    public function receipt($saleId)
    {
        $sale = Sale::with(['items.product', 'payments', 'user'])->findOrFail($saleId);
        return view('pdv.receipt', compact('sale'));
    }

    public function history()
    {
        $sales = Sale::with('user')->orderByDesc('sold_at')->paginate(20);
        return view('pdv.history', compact('sales'));
    }

    public function priceLookup(Request $request)
    {
        $request->validate(['termo' => 'required|string|min:2']);
        $termo = $request->input('termo');
        $produtos = \App\Models\Product::where('name', 'like', "%{$termo}%")
            ->orWhere('internal_code', 'like', "%{$termo}%")
            ->limit(15)
            ->get(['id', 'name', 'internal_code', 'sale_price']);
        return response()->json($produtos);
    }

    public function finalize(Request $request)
    {
        $request->validate([
            'itens' => 'required|array|min:1',
            'pagamentos' => 'required|array|min:1',
        ]);
        $userId = Auth::id();
        $register = CashRegister::where('user_id', $userId)->where('status', 'open')->latest()->first();
        if (!$register) {
            return response()->json(['error' => 'Caixa não aberto.'], 400);
        }
        $total = 0;
        foreach ($request->itens as $item) {
            $total += $item['unitario'] * $item['qtd'];
        }
        $sale = Sale::create([
            'cash_register_id' => $register->id,
            'user_id' => $userId,
            'total' => $total,
            'discount' => 0,
            'final_total' => $total,
            'status' => 'completed',
            'sold_at' => now(),
        ]);
        foreach ($request->itens as $item) {
            $productId = $item['id'] ?? null;
            if (empty($productId)) {
                // Produto avulso: criar categoria se não existir
                $companyId = $register->company_id ?? null;
                $category = \App\Models\Category::firstOrCreate(
                    [
                        'name' => 'Avulso',
                        'company_id' => $companyId,
                    ],
                    [
                        'description' => 'Produtos avulsos criados no PDV',
                        'code' => 'AVULSO',
                    ]
                );
                // Gerar código interno único
                $internalCode = 'AVU-' . strtoupper(uniqid());
                $product = \App\Models\Product::create([
                    'name' => $item['nome'] ?? 'Avulso',
                    'internal_code' => $internalCode,
                    'description' => 'Produto avulso criado no PDV',
                    'category_id' => $category->id,
                    'unit' => 'un',
                    'cost_price' => $item['unitario'],
                    'sale_price' => $item['unitario'],
                    'min_stock' => 0,
                    'company_id' => $companyId,
                ]);
                $productId = $product->id;
            }
            \App\Models\SaleItem::create([
                'sale_id' => $sale->id,
                'product_id' => $productId,
                'quantity' => $item['qtd'],
                'unit_price' => $item['unitario'],
                'total_price' => $item['unitario'] * $item['qtd'],
            ]);
        }
        foreach ($request->pagamentos as $pg) {
            if ($pg['valor'] > 0) {
                SalePayment::create([
                    'sale_id' => $sale->id,
                    'payment_type' => $pg['tipo'],
                    'amount' => $pg['valor'],
                ]);
                $register->movements()->create([
                    'cash_register_id' => $register->id,
                    'user_id' => $userId,
                    'type' => 'sale',
                    'amount' => $pg['valor'],
                    'description' => 'Venda PDV (' . ucfirst($pg['tipo']) . ') #' . $sale->id,
                ]);
            }
        }
        return response()->json(['success' => true, 'sale_id' => $sale->id]);
    }
}
