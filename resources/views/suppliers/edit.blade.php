@extends('dashboard.layout')
@section('title', 'Editar Fornecedor')
@section('content')
<div class="w-full max-w-screen-xl mx-auto">
    <div class="flex justify-between items-center mb-8">
        <a href="{{ route('suppliers.index') }}" class="inline-flex items-center text-sm text-gray-600 hover:text-gray-900 transition">
            <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
            </svg>
            Voltar
        </a>
        <h2 class="text-xl font-semibold text-gray-800 flex items-center gap-2">
            <i class="fas fa-truck"></i> Editar Fornecedor
        </h2>
    </div>
    <form method="POST" action="{{ route('suppliers.update', $supplier->id) }}" class="bg-white p-6 rounded-lg shadow-md">
        @csrf
        @method('PUT')
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
            <div>
                <label class="block text-sm text-gray-700 font-medium mb-1">Tipo</label>
                <select name="type" class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-gray-800 bg-gray-50 text-sm" required>
                    <option value="">Selecione</option>
                    <option value="empresa" @if($supplier->type=='empresa') selected @endif>Empresa</option>
                    <option value="pessoa" @if($supplier->type=='pessoa') selected @endif>Pessoa Física</option>
                </select>
            </div>
            <div>
                <label class="block text-sm text-gray-700 font-medium mb-1">CNPJ</label>
                <input type="text" name="cnpj" placeholder="00.000.000/0000-00" class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-gray-800 bg-gray-50 text-sm" required value="{{ $supplier->cnpj }}">
            </div>
            <div class="sm:col-span-2 lg:col-span-3 xl:col-span-4">
                <label class="block text-sm text-gray-700 font-medium mb-1">Nome</label>
                <input type="text" name="name" placeholder="Nome do fornecedor" class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-gray-800 bg-gray-50 text-sm" required value="{{ $supplier->name }}">
            </div>
            <div>
                <label class="block text-sm text-gray-700 font-medium mb-1">Status</label>
                <select name="status" class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-gray-800 bg-gray-50 text-sm" required>
                    <option value="">Selecione</option>
                    <option value="ativo" @if($supplier->status=='ativo') selected @endif>Ativo</option>
                    <option value="inativo" @if($supplier->status=='inativo') selected @endif>Inativo</option>
                </select>
            </div>
        </div>
        <h2 class="text-lg font-semibold text-blue-800 mt-6 mb-2">Contato Principal</h2>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
            <div>
                <label class="block text-sm text-gray-700 font-medium mb-1">Nome</label>
                <input type="text" name="contact_name" placeholder="Nome do contato" class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-gray-800 bg-gray-50 text-sm" required value="{{ $supplier->contact_name }}">
            </div>
            <div>
                <label class="block text-sm text-gray-700 font-medium mb-1">E-mail</label>
                <input type="email" name="contact_email" placeholder="email@exemplo.com" class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-gray-800 bg-gray-50 text-sm" required value="{{ $supplier->contact_email }}">
            </div>
            <div>
                <label class="block text-sm text-gray-700 font-medium mb-1">Telefone</label>
                <input type="text" name="contact_phone" placeholder="(99) 99999-9999" class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-gray-800 bg-gray-50 text-sm" required value="{{ $supplier->contact_phone }}">
            </div>
            <div>
                <label class="block text-sm text-gray-700 font-medium mb-1">Site</label>
                <input type="text" name="contact_site" placeholder="www.exemplo.com" class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-gray-800 bg-gray-50 text-sm" value="{{ $supplier->contact_site }}">
            </div>
        </div>
        <div class="sm:col-span-2 lg:col-span-3 xl:col-span-4">
            <label class="block text-sm text-gray-700 font-medium mb-1">Descrição</label>
            <textarea name="description" rows="3" placeholder="Informações adicionais..." class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-gray-800 bg-gray-50 text-sm">{{ $supplier->description }}</textarea>
        </div>
        <h2 class="text-lg font-semibold text-blue-800 mt-6 mb-2">Endereço</h2>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
            <div>
                <label class="block text-sm text-gray-700 font-medium mb-1">CEP</label>
                <input type="text" name="cep" placeholder="00000-000" class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-gray-800 bg-gray-50 text-sm" required value="{{ $supplier->cep }}">
            </div>
            <div>
                <label class="block text-sm text-gray-700 font-medium mb-1">Endereço</label>
                <input type="text" name="address" placeholder="Rua, avenida..." class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-gray-800 bg-gray-50 text-sm" required value="{{ $supplier->address }}">
            </div>
            <div>
                <label class="block text-sm text-gray-700 font-medium mb-1">Número</label>
                <input type="text" name="number" placeholder="Número" class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-gray-800 bg-gray-50 text-sm" required value="{{ $supplier->number }}">
            </div>
            <div>
                <label class="block text-sm text-gray-700 font-medium mb-1">Complemento</label>
                <input type="text" name="complement" placeholder="Sala, apto, etc." class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-gray-800 bg-gray-50 text-sm" value="{{ $supplier->complement }}">
            </div>
            <div>
                <label class="block text-sm text-gray-700 font-medium mb-1">Bairro</label>
                <input type="text" name="neighborhood" placeholder="Bairro" class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-gray-800 bg-gray-50 text-sm" required value="{{ $supplier->neighborhood }}">
            </div>
            <div>
                <label class="block text-sm text-gray-700 font-medium mb-1">Estado</label>
                <input type="text" name="state" placeholder="Estado" class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-gray-800 bg-gray-50 text-sm" required value="{{ $supplier->state }}">
            </div>
            <div>
                <label class="block text-sm text-gray-700 font-medium mb-1">Cidade</label>
                <input type="text" name="city" placeholder="Cidade" class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-gray-800 bg-gray-50 text-sm" required value="{{ $supplier->city }}">
            </div>
            <div>
                <label class="block text-sm text-gray-700 font-medium mb-1">País</label>
                <input type="text" name="country" placeholder="Brasil" class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-gray-800 bg-gray-50 text-sm" required value="{{ $supplier->country }}">
            </div>
        </div>
        <div class="flex justify-end mt-8 gap-3">
            <a href="{{ route('suppliers.index') }}" class="px-4 py-2 rounded bg-gray-100 text-gray-700 hover:bg-gray-200 text-sm border">Cancelar</a>
            <button type="submit" class="px-6 py-2 rounded bg-gray-800 text-white hover:bg-gray-900 font-semibold text-sm shadow">Salvar</button>
        </div>
    </form>
</div>
@endsection
