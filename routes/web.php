<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\StockMovementController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\PayableController;
use App\Http\Controllers\ReceivableController;
use App\Http\Controllers\FinancialReportController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\TimeClockController;
use App\Http\Controllers\PayrollController;
use App\Http\Controllers\VacationController;
use App\Http\Controllers\LeaveController;
use App\Http\Controllers\BenefitController;
use App\Http\Controllers\PayslipController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/login', function () {
    return view('login');
})->name('login');

Route::post('/login', function (Request $request) {
    $credentials = $request->only('email', 'password');
    if (Auth::attempt($credentials)) {
        $request->session()->regenerate();
        return redirect('/');
    }
    return back()->with('error', 'E-mail ou senha inválidos.');
});

Route::post('/logout', function (Request $request) {
    Auth::logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();
    return redirect('/login');
})->name('logout');

Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

Route::resource('products', ProductController::class);
Route::resource('categories', CategoryController::class);
Route::resource('stock_movements', StockMovementController::class);
Route::resource('employees', EmployeeController::class);

// Rotas do módulo financeiro
Route::resource('payables', PayableController::class);
Route::resource('receivables', ReceivableController::class);

// Rotas específicas para marcar como pago/recebido
Route::patch('payables/{payable}/marcar-pago', [PayableController::class, 'marcarComoPago'])->name('payables.marcar-pago');
Route::patch('receivables/{receivable}/marcar-recebido', [ReceivableController::class, 'marcarComoRecebido'])->name('receivables.marcar-recebido');

// Relatórios financeiros
Route::prefix('financial-reports')->group(function () {
    Route::get('/', [FinancialReportController::class, 'index'])->name('financial-reports.index');
    Route::get('dashboard', [FinancialReportController::class, 'dashboard'])->name('financial-reports.dashboard');
    Route::get('fluxo-caixa', [FinancialReportController::class, 'fluxoCaixa'])->name('financial-reports.fluxo-caixa');
    Route::get('categorias', [FinancialReportController::class, 'categorias'])->name('financial-reports.categorias');
    Route::get('pessoas', [FinancialReportController::class, 'pessoas'])->name('financial-reports.pessoas');
});

Route::prefix('reports')->group(function () {
    Route::get('estoque-atual', [ReportController::class, 'estoqueAtual'])->name('reports.estoque_atual');
    Route::get('historico-movimentacoes', [ReportController::class, 'historicoMovimentacoes'])->name('reports.historico_movimentacoes');
    Route::get('alerta-estoque', [ReportController::class, 'alertaEstoque'])->name('reports.alerta_estoque');
    Route::get('produtos-mais-movimentados', [ReportController::class, 'produtosMaisMovimentados'])->name('reports.produtos_mais_movimentados');
});

Route::get('reports', [ReportController::class, 'index'])->name('reports.index');

// Rotas do módulo RH / Departamento Pessoal
Route::resource('timeclocks', TimeClockController::class);
Route::resource('payrolls', PayrollController::class);
Route::resource('vacations', VacationController::class);
Route::resource('leaves', LeaveController::class);
Route::resource('benefits', BenefitController::class);
Route::resource('payslips', PayslipController::class);
