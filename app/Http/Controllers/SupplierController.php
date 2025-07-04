<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use Illuminate\Http\Request;

class SupplierController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'type' => 'required|in:empresa,pessoa',
            'cnpj' => 'required|string|max:20',
            'name' => 'required|string',
            'status' => 'required|in:ativo,inativo',
            'contact_name' => 'required|string',
            'contact_email' => 'required|email',
            'contact_phone' => 'required|string',
            'contact_site' => 'nullable|string',
            'description' => 'nullable|string',
            'cep' => 'required|string|max:12',
            'address' => 'required|string',
            'number' => 'required|string|max:20',
            'complement' => 'nullable|string',
            'neighborhood' => 'required|string',
            'state' => 'required|string|max:40',
            'city' => 'required|string|max:60',
            'country' => 'required|string|max:40',
        ]);
        Supplier::create($validated);
        return redirect('/suppliers')->with('success', 'Fornecedor cadastrado com sucesso!');
    }

    public function index()
    {
        $suppliers = \App\Models\Supplier::orderByDesc('id')->paginate(15);
        return view('suppliers.index', compact('suppliers'));
    }

    public function edit($id)
    {
        $supplier = \App\Models\Supplier::findOrFail($id);
        return view('suppliers.edit', compact('supplier'));
    }

    public function update(Request $request, $id)
    {
        $supplier = \App\Models\Supplier::findOrFail($id);
        $validated = $request->validate([
            'type' => 'required|in:empresa,pessoa',
            'cnpj' => 'required|string|max:20',
            'name' => 'required|string',
            'status' => 'required|in:ativo,inativo',
            'contact_name' => 'required|string',
            'contact_email' => 'required|email',
            'contact_phone' => 'required|string',
            'contact_site' => 'nullable|string',
            'description' => 'nullable|string',
            'cep' => 'required|string|max:12',
            'address' => 'required|string',
            'number' => 'required|string|max:20',
            'complement' => 'nullable|string',
            'neighborhood' => 'required|string',
            'state' => 'required|string|max:40',
            'city' => 'required|string|max:60',
            'country' => 'required|string|max:40',
        ]);
        $supplier->update($validated);
        return redirect('/suppliers')->with('success', 'Fornecedor atualizado com sucesso!');
    }

    public function destroy($id)
    {
        $supplier = \App\Models\Supplier::findOrFail($id);
        $supplier->delete();
        return redirect('/suppliers')->with('success', 'Fornecedor excluído com sucesso!');
    }

    public function create()
    {
        return view('suppliers.create');
    }
}
