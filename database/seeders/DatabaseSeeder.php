<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Category;
use App\Models\Product;
use App\Models\StockMovement;
use App\Models\Payable;
use App\Models\Receivable;
use App\Models\Employee;
use App\Models\TimeClock;
use App\Models\Payroll;
use App\Models\Vacation;
use App\Models\Leave;
use App\Models\Benefit;
use App\Models\Payslip;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Usuários
        User::factory()->create([
            'name' => 'Administrador',
            'email' => 'admin@empresa.com',
            'password' => bcrypt('admin123'),
            'role' => 'admin',
        ]);

        User::factory()->create([
            'name' => 'Estoquista',
            'email' => 'estoquista@empresa.com',
            'password' => bcrypt('estoque123'),
            'role' => 'estoquista',
        ]);

        // Funcionários
        Employee::create([
            'name' => 'João Silva',
            'cpf' => '123.456.789-00',
            'email' => 'joao@empresa.com',
            'phone' => '(11) 99999-9999',
            'role' => 'Gerente',
            'admission_date' => Carbon::now()->subYear(),
            'username' => 'joao.silva',
            'password' => bcrypt('123456'),
            'permission_level' => 'administrador',
        ]);

        Employee::create([
            'name' => 'Maria Santos',
            'cpf' => '987.654.321-00',
            'email' => 'maria@empresa.com',
            'phone' => '(11) 88888-8888',
            'role' => 'Estoquista',
            'admission_date' => Carbon::now()->subMonths(6),
            'username' => 'maria.santos',
            'password' => bcrypt('123456'),
            'permission_level' => 'operador',
        ]);

        // Categorias
        $categorias = [
            ['name' => 'Eletrônicos', 'code' => 'ELET', 'description' => 'Produtos eletrônicos'],
            ['name' => 'Informática', 'code' => 'INFO', 'description' => 'Produtos de informática'],
            ['name' => 'Escritório', 'code' => 'ESCR', 'description' => 'Material de escritório'],
            ['name' => 'Limpeza', 'code' => 'LIMP', 'description' => 'Produtos de limpeza'],
            ['name' => 'Alimentação', 'code' => 'ALIM', 'description' => 'Produtos alimentícios'],
        ];

        foreach ($categorias as $cat) {
            Category::create($cat);
        }

        // Produtos
        $produtos = [
            ['name' => 'Notebook Dell', 'internal_code' => 'NB001', 'category_id' => 2, 'unit' => 'un', 'cost_price' => 2500.00, 'sale_price' => 3200.00, 'min_stock' => 3],
            ['name' => 'Mouse Wireless', 'internal_code' => 'MS001', 'category_id' => 2, 'unit' => 'un', 'cost_price' => 25.00, 'sale_price' => 45.00, 'min_stock' => 15],
            ['name' => 'Teclado Mecânico', 'internal_code' => 'TC001', 'category_id' => 2, 'unit' => 'un', 'cost_price' => 120.00, 'sale_price' => 180.00, 'min_stock' => 8],
            ['name' => 'Papel A4', 'internal_code' => 'PP001', 'category_id' => 3, 'unit' => 'pacote', 'cost_price' => 15.00, 'sale_price' => 25.00, 'min_stock' => 20],
            ['name' => 'Caneta Bic', 'internal_code' => 'CN001', 'category_id' => 3, 'unit' => 'un', 'cost_price' => 1.50, 'sale_price' => 3.00, 'min_stock' => 50],
            ['name' => 'Detergente', 'internal_code' => 'DT001', 'category_id' => 4, 'unit' => 'l', 'cost_price' => 8.00, 'sale_price' => 12.00, 'min_stock' => 10],
            ['name' => 'Café em Pó', 'internal_code' => 'CF001', 'category_id' => 5, 'unit' => 'kg', 'cost_price' => 12.00, 'sale_price' => 18.00, 'min_stock' => 5],
        ];

        foreach ($produtos as $prod) {
            Product::create($prod);
        }

        // Movimentações de estoque
        $movimentacoes = [
            ['product_id' => 1, 'user_id' => 1, 'type' => 'entrada', 'quantity' => 5, 'date' => Carbon::now()->subDays(5), 'notes' => 'Compra inicial'],
            ['product_id' => 1, 'user_id' => 1, 'type' => 'saida', 'quantity' => 2, 'date' => Carbon::now()->subDays(3), 'notes' => 'Venda'],
            ['product_id' => 2, 'user_id' => 1, 'type' => 'entrada', 'quantity' => 20, 'date' => Carbon::now()->subDays(10), 'notes' => 'Compra'],
            ['product_id' => 2, 'user_id' => 1, 'type' => 'saida', 'quantity' => 5, 'date' => Carbon::now()->subDays(2), 'notes' => 'Venda'],
            ['product_id' => 3, 'user_id' => 1, 'type' => 'entrada', 'quantity' => 10, 'date' => Carbon::now()->subDays(7), 'notes' => 'Compra'],
            ['product_id' => 4, 'user_id' => 1, 'type' => 'entrada', 'quantity' => 30, 'date' => Carbon::now()->subDays(15), 'notes' => 'Compra'],
            ['product_id' => 4, 'user_id' => 1, 'type' => 'saida', 'quantity' => 10, 'date' => Carbon::now()->subDays(1), 'notes' => 'Uso interno'],
        ];

        foreach ($movimentacoes as $mov) {
            StockMovement::create($mov);
        }

        // Contas a pagar
        $contasPagar = [
            ['descricao' => 'Fornecedor Eletrônicos Ltda', 'pessoa' => 'Fornecedor Eletrônicos', 'categoria' => 'Fornecedores', 'valor' => 5000.00, 'data_vencimento' => Carbon::now()->addDays(5), 'status' => 'pendente', 'forma_pagamento' => 'PIX', 'criado_por' => 1],
            ['descricao' => 'Aluguel Escritório', 'pessoa' => 'Imobiliária Central', 'categoria' => 'Despesas Fixas', 'valor' => 2500.00, 'data_vencimento' => Carbon::now()->addDays(3), 'status' => 'pendente', 'forma_pagamento' => 'Transferência', 'criado_por' => 1],
            ['descricao' => 'Energia Elétrica', 'pessoa' => 'Companhia Energética', 'categoria' => 'Serviços Públicos', 'valor' => 800.00, 'data_vencimento' => Carbon::now()->subDays(2), 'status' => 'pago', 'data_pagamento' => Carbon::now()->subDays(1), 'forma_pagamento' => 'Boleto', 'criado_por' => 1],
            ['descricao' => 'Internet', 'pessoa' => 'Provedor Net', 'categoria' => 'Serviços Públicos', 'valor' => 150.00, 'data_vencimento' => Carbon::now()->addDays(10), 'status' => 'pendente', 'forma_pagamento' => 'Cartão', 'criado_por' => 1],
        ];

        foreach ($contasPagar as $conta) {
            Payable::create($conta);
        }

        // Contas a receber
        $contasReceber = [
            ['descricao' => 'Venda Cliente A', 'pessoa' => 'Cliente A Ltda', 'categoria' => 'Vendas', 'valor' => 3200.00, 'data_vencimento' => Carbon::now()->addDays(7), 'status' => 'pendente', 'forma_recebimento' => 'PIX', 'criado_por' => 1],
            ['descricao' => 'Venda Cliente B', 'pessoa' => 'Cliente B S/A', 'categoria' => 'Vendas', 'valor' => 1800.00, 'data_vencimento' => Carbon::now()->subDays(1), 'status' => 'recebido', 'data_recebimento' => Carbon::now(), 'forma_recebimento' => 'Transferência', 'criado_por' => 1],
            ['descricao' => 'Serviço Consultoria', 'pessoa' => 'Empresa C', 'categoria' => 'Serviços', 'valor' => 2500.00, 'data_vencimento' => Carbon::now()->addDays(15), 'status' => 'pendente', 'forma_recebimento' => 'Boleto', 'criado_por' => 1],
            ['descricao' => 'Venda Cliente D', 'pessoa' => 'Cliente D', 'categoria' => 'Vendas', 'valor' => 900.00, 'data_vencimento' => Carbon::now()->addDays(2), 'status' => 'pendente', 'forma_recebimento' => 'PIX', 'criado_por' => 1],
        ];

        foreach ($contasReceber as $conta) {
            Receivable::create($conta);
        }

        // TimeClocks (ponto)
        TimeClock::create([
            'employee_id' => 1,
            'data' => now()->subDays(1)->toDateString(),
            'hora_entrada' => '08:00',
            'hora_intervalo_inicio' => '12:00',
            'hora_intervalo_fim' => '13:00',
            'hora_saida' => '17:00',
            'observacao' => 'Dia normal',
        ]);
        TimeClock::create([
            'employee_id' => 2,
            'data' => now()->subDays(1)->toDateString(),
            'hora_entrada' => '09:00',
            'hora_intervalo_inicio' => '12:30',
            'hora_intervalo_fim' => '13:30',
            'hora_saida' => '18:00',
            'observacao' => 'Chegou atrasado',
        ]);

        // Payrolls (folha de pagamento)
        $payroll1 = Payroll::create([
            'employee_id' => 1,
            'competencia' => now()->format('m/Y'),
            'salario_base' => 5000,
            'descontos' => 500,
            'adicionais' => 200,
            'total_liquido' => 4700,
            'status' => 'pago',
            'data_pagamento' => now()->toDateString(),
            'observacao' => 'Folha do mês',
        ]);
        $payroll2 = Payroll::create([
            'employee_id' => 2,
            'competencia' => now()->format('m/Y'),
            'salario_base' => 3000,
            'descontos' => 200,
            'adicionais' => 100,
            'total_liquido' => 2900,
            'status' => 'pendente',
            'observacao' => 'Folha do mês',
        ]);

        // Vacations (férias)
        Vacation::create([
            'employee_id' => 1,
            'data_inicio' => now()->addDays(10)->toDateString(),
            'data_fim' => now()->addDays(30)->toDateString(),
            'dias' => 20,
            'status' => 'aprovada',
            'data_solicitacao' => now()->subDays(5)->toDateString(),
            'data_aprovacao' => now()->toDateString(),
            'observacao' => 'Férias programadas',
        ]);

        // Leaves (licenças)
        Leave::create([
            'employee_id' => 2,
            'tipo' => 'Licença médica',
            'data_inicio' => now()->subDays(3)->toDateString(),
            'data_fim' => now()->addDays(2)->toDateString(),
            'dias' => 5,
            'status' => 'aprovada',
            'observacao' => 'Atestado médico',
        ]);

        // Benefits (benefícios)
        Benefit::create([
            'employee_id' => 1,
            'tipo' => 'Vale Transporte',
            'valor' => 200,
            'status' => 'ativo',
            'data_inicio' => now()->subMonths(6)->toDateString(),
            'observacao' => 'Benefício mensal',
        ]);
        Benefit::create([
            'employee_id' => 2,
            'tipo' => 'Plano de Saúde',
            'valor' => 400,
            'status' => 'ativo',
            'data_inicio' => now()->subMonths(3)->toDateString(),
            'observacao' => 'Plano familiar',
        ]);

        // Payslips (holerites)
        Payslip::create([
            'employee_id' => 1,
            'payroll_id' => $payroll1->id,
            'competencia' => now()->format('m/Y'),
            'arquivo' => 'holerites/joao_silva_' . now()->format('m_Y') . '.pdf',
            'data_geracao' => now()->toDateString(),
            'observacao' => 'Holerite digital',
        ]);
    }
}
