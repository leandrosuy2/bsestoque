@extends('dashboard.layout')

@section('content')
<div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-8">
    <div class="flex items-center gap-3">
        <span class="bg-gray-800 p-2 rounded-full shadow">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M20 13V7a2 2 0 00-2-2H6a2 2 0 00-2 2v6m16 0v6a2 2 0 01-2 2H6a2 2 0 01-2-2v-6m16 0H4"/>
            </svg>
        </span>
        <h1 class="text-2xl font-bold text-gray-800">Produtos</h1>
    </div>
    <a href="{{ route('products.create') }}" class="inline-flex items-center gap-2 bg-gradient-to-r from-gray-800 to-gray-700 text-white px-5 py-2 rounded-xl hover:from-gray-900 hover:to-gray-800 transition font-semibold shadow-lg ring-1 ring-gray-900/10 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-800">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
        Novo Produto
    </a>
</div>

@if(session('success'))
    <div class="mb-4 px-4 py-3 bg-green-100 border border-green-200 text-green-800 rounded shadow-sm">
        {{ session('success') }}
    </div>
@endif

<div class="overflow-x-auto bg-white rounded-xl shadow-sm">
    <table class="min-w-full text-sm text-left">
        <thead class="bg-gray-100 border-b border-gray-200">
            <tr>
                <th class="px-4 py-3 font-semibold text-gray-600 uppercase">Nome</th>
                <th class="px-4 py-3 font-semibold text-gray-600 uppercase">Código</th>
                <th class="px-4 py-3 font-semibold text-gray-600 uppercase">Categoria</th>
                <th class="px-4 py-3 font-semibold text-gray-600 uppercase">Unidade</th>
                <th class="px-4 py-3 font-semibold text-gray-600 uppercase">Preço Custo</th>
                <th class="px-4 py-3 font-semibold text-gray-600 uppercase">Preço Venda</th>
                <th class="px-4 py-3 font-semibold text-gray-600 uppercase">Estoque Mínimo</th>
                <th class="px-4 py-3 text-right"></th>
            </tr>
        </thead>
        <tbody>
            @forelse($products as $product)
                <tr class="border-b last:border-0 hover:bg-gray-50 transition">
                    <td class="px-4 py-3 font-medium text-gray-900">{{ $product->name }}</td>
                    <td class="px-4 py-3 text-gray-600">{{ $product->internal_code }}</td>
                    <td class="px-4 py-3 text-gray-600">{{ $product->category->name ?? '-' }}</td>
                    <td class="px-4 py-3 text-gray-600">{{ $product->unit }}</td>
                    <td class="px-4 py-3 text-gray-600">R$ {{ number_format($product->cost_price, 2, ',', '.') }}</td>
                    <td class="px-4 py-3 text-gray-600">R$ {{ number_format($product->sale_price, 2, ',', '.') }}</td>
                    <td class="px-4 py-3">
                        <span class="inline-block px-2 py-1 rounded bg-gray-100 text-gray-700 text-xs font-medium">
                            {{ $product->min_stock }}
                        </span>
                    </td>
                    <td class="px-4 py-3 text-right">
                        <div class="flex justify-end gap-2">
                            <a href="{{ route('products.edit', $product) }}" class="inline-flex items-center gap-1 text-white bg-gradient-to-r from-blue-600 to-blue-500 hover:from-blue-700 hover:to-blue-600 font-semibold px-3 py-1.5 rounded-lg shadow focus:outline-none focus:ring-2 focus:ring-blue-500 transition">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.232 5.232l3.536 3.536M9 13l6-6m2 2l-6 6m-2 2h6"/>
                                </svg>
                                Editar
                            </a>
                            <form action="{{ route('products.destroy', $product) }}" method="POST" onsubmit="return confirm('Tem certeza que deseja remover este produto?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="inline-flex items-center gap-1 text-white bg-gradient-to-r from-red-600 to-red-500 hover:from-red-700 hover:to-red-600 font-semibold px-3 py-1.5 rounded-lg shadow focus:outline-none focus:ring-2 focus:ring-red-500 transition">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                                    </svg>
                                    Remover
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="8" class="px-4 py-8 text-center text-gray-400">Nenhum produto cadastrado.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

@if($products->hasPages())
    <div class="mt-6">
        {{ $products->links() }}
    </div>
@endif
@endsection
