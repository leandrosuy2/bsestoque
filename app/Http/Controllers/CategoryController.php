<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categories = \App\Models\Category::paginate(10);
        return view('categories.index', compact('categories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('categories.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'nullable|string|max:100|unique:categories,code',
            'description' => 'nullable|string',
        ]);
        \App\Models\Category::create($validated);
        return redirect()->route('categories.index')->with('success', 'Categoria cadastrada com sucesso!');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $category = \App\Models\Category::findOrFail($id);
        return view('categories.edit', compact('category'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $category = \App\Models\Category::findOrFail($id);
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'nullable|string|max:100|unique:categories,code,' . $category->id,
            'description' => 'nullable|string',
        ]);
        $category->update($validated);
        return redirect()->route('categories.index')->with('success', 'Categoria atualizada com sucesso!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $category = \App\Models\Category::findOrFail($id);

        if ($category->products()->count() > 0) {
            return redirect()->route('categories.index')
                ->with('error', 'Não é possível excluir a categoria porque existem produtos vinculados a ela.');
        }

        $category->delete();
        return redirect()->route('categories.index')->with('success', 'Categoria removida com sucesso!');
    }
}
