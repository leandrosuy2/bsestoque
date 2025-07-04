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
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\Admin\CompanyController;
use App\Http\Controllers\Api\CnpjController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\CashRegisterController;
use App\Http\Controllers\CashMovementController;
use App\Http\Controllers\PDVController;
use App\Http\Controllers\SupplierController;

Route::get('/', function () {
    if (Auth::check()) {
        return redirect('/dashboard');
    }
    return redirect('/login');
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

Route::middleware(['auth', 'company.access'])->group(function () {
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

    Route::get('/payment/notice', [PaymentController::class, 'notice'])->name('payment.notice');

    // Rotas para gerenciamento de usuários e papéis
    Route::resource('roles', RoleController::class);
    Route::patch('roles/{role}/toggle-status', [RoleController::class, 'toggleStatus'])->name('roles.toggle-status');

    Route::resource('users', UserController::class);
    Route::patch('users/{user}/toggle-status', [UserController::class, 'toggleStatus'])->name('users.toggle-status');
});

// Rotas públicas para cadastro de empresa
Route::get('/cadastro-empresa', function () {
    Log::info('Acessou a rota /cadastro-empresa');
    return app(\App\Http\Controllers\Admin\CompanyController::class)->publicCreate(request());
});
Route::post('/cadastro-empresa', [CompanyController::class, 'publicStore'])->name('companies.public.store');

Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('companies', [CompanyController::class, 'index'])->name('companies.index');
    Route::get('companies/{company}/edit', [CompanyController::class, 'edit'])->name('companies.edit');
    Route::put('companies/{company}', [CompanyController::class, 'update'])->name('companies.update');
    Route::post('companies/{company}/toggle-active', [CompanyController::class, 'toggleActive'])->name('companies.toggleActive');
    Route::post('companies/{company}/liberar-pagamento', [CompanyController::class, 'liberarPagamento'])->name('companies.liberarPagamento');
    Route::post('companies/{company}/renovar-trial', [CompanyController::class, 'renovarTrial'])->name('companies.renovarTrial');
    Route::get('companies/create', [CompanyController::class, 'create'])->name('companies.create');
    Route::post('companies', [CompanyController::class, 'store'])->name('companies.store');
});

// Rota da API para buscar dados do CNPJ
Route::get('/api/cnpj/{cnpj}', [CnpjController::class, 'search']);

// Rotas do Caixa
Route::prefix('caixa')->middleware(['auth'])->group(function () {
    Route::get('/', [\App\Http\Controllers\CashRegisterController::class, 'index'])->name('caixa.index');
    Route::post('/abrir', [\App\Http\Controllers\CashRegisterController::class, 'open'])->name('caixa.open');
    Route::get('/{id}', [\App\Http\Controllers\CashRegisterController::class, 'show'])->name('caixa.show');
    Route::post('/{id}/fechar', [\App\Http\Controllers\CashRegisterController::class, 'close'])->name('caixa.close');
    Route::get('/{id}/relatorio', [\App\Http\Controllers\CashRegisterController::class, 'report'])->name('caixa.report');
    // Movimentações
    Route::get('/{id}/movimentacoes', [\App\Http\Controllers\CashMovementController::class, 'index'])->name('caixa.movements');
    Route::post('/{id}/movimentacoes', [\App\Http\Controllers\CashMovementController::class, 'store'])->name('caixa.movements.store');
});

// Rotas do PDV
Route::prefix('pdv')->middleware(['auth'])->group(function () {
    Route::get('/', [\App\Http\Controllers\PDVController::class, 'index'])->name('pdv.index');
    Route::get('/full', [\App\Http\Controllers\PDVController::class, 'index'])->name('pdv.full');
    Route::post('/iniciar', [\App\Http\Controllers\PDVController::class, 'startSale'])->name('pdv.start');
    Route::post('/item', [\App\Http\Controllers\PDVController::class, 'addItem'])->name('pdv.addItem');
    Route::delete('/item/{itemId}', [\App\Http\Controllers\PDVController::class, 'removeItem'])->name('pdv.removeItem');
    Route::post('/desconto', [\App\Http\Controllers\PDVController::class, 'applyDiscount'])->name('pdv.discount');
    Route::post('/pagamento', [\App\Http\Controllers\PDVController::class, 'addPayment'])->name('pdv.addPayment');
    Route::post('/finalizar', [\App\Http\Controllers\PDVController::class, 'finalize'])->name('pdv.finalize');
    Route::get('/venda/{id}/comprovante', [\App\Http\Controllers\PDVController::class, 'receipt'])->name('pdv.receipt');
    Route::get('/historico', [\App\Http\Controllers\PDVController::class, 'history'])->name('pdv.history');
    Route::post('/consulta-preco', [\App\Http\Controllers\PDVController::class, 'priceLookup'])->name('pdv.priceLookup');
});

Route::post('/pdv/finalizar-nf', [\App\Http\Controllers\PDVController::class, 'finalizeWithInvoice'])->middleware(['auth'])->name('pdv.finalizeWithInvoice');
Route::post('/pdv/finalizar-sem-nf', [\App\Http\Controllers\PDVController::class, 'finalizeWithoutInvoice'])->middleware(['auth'])->name('pdv.finalizeWithoutInvoice');
Route::post('/pdv/cancelar', [\App\Http\Controllers\PDVController::class, 'cancelSale'])->middleware(['auth'])->name('pdv.cancelSale');

Route::post('/suppliers', [\App\Http\Controllers\SupplierController::class, 'store'])->name('suppliers.store');
Route::get('/suppliers', [\App\Http\Controllers\SupplierController::class, 'index'])->name('suppliers.index');
Route::get('/suppliers/{id}/edit', [\App\Http\Controllers\SupplierController::class, 'edit'])->name('suppliers.edit');
Route::put('/suppliers/{id}', [\App\Http\Controllers\SupplierController::class, 'update'])->name('suppliers.update');
Route::delete('/suppliers/{id}', [\App\Http\Controllers\SupplierController::class, 'destroy'])->name('suppliers.destroy');
Route::get('/suppliers/create', [\App\Http\Controllers\SupplierController::class, 'create'])->name('suppliers.create');
