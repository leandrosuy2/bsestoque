@extends('dashboard.layout')

@section('content')
<div class="w-full max-w-screen-xl mx-auto">
    <div class="flex justify-between items-center mb-8">
        <a href="{{ route('employees.index') }}" class="inline-flex items-center text-sm text-gray-600 hover:text-gray-900 transition">
            <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
            </svg>
            Voltar
        </a>
        <h2 class="text-xl font-semibold text-gray-800 flex items-center gap-2">
            <svg class="w-6 h-6 text-gray-800" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
            </svg>
            Novo Funcionário
        </h2>
    </div>

    <form method="POST" action="{{ route('employees.store') }}" class="bg-white p-6 rounded-lg shadow-md">
        @csrf
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
            <div>
                <label class="block text-sm text-gray-700 font-medium mb-1">Nome completo</label>
                <input type="text" name="name" placeholder="Nome completo" class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-gray-800 bg-gray-50 text-sm" required value="{{ old('name') }}">
            </div>
            <div>
                <label class="block text-sm text-gray-700 font-medium mb-1">CPF</label>
                <input type="text" name="cpf" maxlength="14" placeholder="000.000.000-00" class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-gray-800 bg-gray-50 text-sm" required value="{{ old('cpf') }}">
            </div>
            <div>
                <label class="block text-sm text-gray-700 font-medium mb-1">E-mail</label>
                <input type="email" name="email" placeholder="email@exemplo.com" class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-gray-800 bg-gray-50 text-sm" required value="{{ old('email') }}">
            </div>
            <div>
                <label class="block text-sm text-gray-700 font-medium mb-1">Telefone</label>
                <input type="text" name="phone" placeholder="(99) 99999-9999" class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-gray-800 bg-gray-50 text-sm" value="{{ old('phone') }}">
            </div>
            <div>
                <label class="block text-sm text-gray-700 font-medium mb-1">Cargo/Função</label>
                <input type="text" name="role" placeholder="Cargo ou função" class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-gray-800 bg-gray-50 text-sm" required value="{{ old('role') }}">
            </div>
            <div>
                <label class="block text-sm text-gray-700 font-medium mb-1">Data de admissão</label>
                <input type="date" name="admission_date" class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-gray-800 bg-gray-50 text-sm" required value="{{ old('admission_date', date('Y-m-d')) }}">
            </div>
            <div>
                <label class="block text-sm text-gray-700 font-medium mb-1">Usuário</label>
                <input type="text" name="username" placeholder="Usuário de acesso" class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-gray-800 bg-gray-50 text-sm" required value="{{ old('username') }}">
            </div>
            <div>
                <label class="block text-sm text-gray-700 font-medium mb-1">Senha</label>
                <input type="password" name="password" placeholder="Senha de acesso" class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-gray-800 bg-gray-50 text-sm" required>
            </div>
            <div>
                <label class="block text-sm text-gray-700 font-medium mb-1">Confirme a senha</label>
                <input type="password" name="password_confirmation" placeholder="Confirme a senha" class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-gray-800 bg-gray-50 text-sm" required>
            </div>
            <div>
                <label class="block text-sm text-gray-700 font-medium mb-1">Permissão</label>
                <select name="permission_level" class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-gray-800 bg-gray-50 text-sm" required>
                    <option value="">Selecione</option>
                    <option value="administrador" @selected(old('permission_level') == 'administrador')>Administrador</option>
                    <option value="operador" @selected(old('permission_level') == 'operador')>Operador</option>
                    <option value="consulta" @selected(old('permission_level') == 'consulta')>Consulta</option>
                </select>
            </div>
            <div>
                <label class="block text-sm text-gray-700 font-medium mb-1">Ativo</label>
                <select name="active" class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-gray-800 bg-gray-50 text-sm" required>
                    <option value="1" @selected(old('active', '1') == '1')>Sim</option>
                    <option value="0" @selected(old('active') == '0')>Não</option>
                </select>
            </div>
        </div>
        <div class="flex justify-end mt-8 gap-3">
            <a href="{{ route('employees.index') }}" class="px-4 py-2 rounded bg-gray-100 text-gray-700 hover:bg-gray-200 text-sm border">Cancelar</a>
            <button type="submit" class="px-6 py-2 rounded bg-gray-800 text-white hover:bg-gray-900 font-semibold text-sm shadow">Salvar</button>
        </div>
    </form>
</div>
@endsection
