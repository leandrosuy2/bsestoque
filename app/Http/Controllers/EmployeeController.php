<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use Illuminate\Http\Request;

class EmployeeController extends Controller
{
    public function index()
    {
        $employees = Employee::orderBy('name')->paginate(15);
        return view('employees.index', compact('employees'));
    }

    public function create()
    {
        return view('employees.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'cpf' => 'required|string|max:14|unique:employees,cpf',
            'email' => 'required|email|max:255|unique:employees,email',
            'phone' => 'nullable|string|max:20',
            'role' => 'required|string|max:100',
            'admission_date' => 'required|date',
            'username' => 'required|string|max:100|unique:employees,username',
            'password' => 'required|string|min:6|confirmed',
            'permission_level' => 'required|in:administrador,operador,consulta',
            'active' => 'boolean',
        ]);
        $validated['password'] = bcrypt($validated['password']);
        Employee::create($validated);
        return redirect()->route('employees.index')->with('success', 'Funcionário cadastrado com sucesso!');
    }
}
