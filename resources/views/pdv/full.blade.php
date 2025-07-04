<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>PDV - Ponto de Venda</title>
  @vite(['resources/css/app.css', 'resources/js/app.js'])
  <script src="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free/js/all.min.js" defer></script>
</head>
<body class="bg-gradient-to-br from-blue-900 to-blue-800 min-h-screen flex items-center justify-center text-gray-800 font-sans">
  <div class="w-full max-w-7xl bg-white rounded-3xl shadow-2xl p-8 flex flex-col md:flex-row gap-8 min-h-[90vh]">

    <!-- Carrinho -->
    <div class="flex-1 flex flex-col">
      <div class="flex items-center justify-between mb-6">
        <h1 class="text-3xl font-bold text-blue-900">PDV - Frente de Loja</h1>
      </div>

      <!-- Botões de Ações com Atalhos -->
      <div class="flex gap-2 mb-6">
        <!-- Botão Nova Venda (sempre ativo) -->
        <form action="{{ route('pdv.start') }}" method="POST" class="flex-1">
          @csrf
          <button type="submit" class="w-full bg-green-600 hover:bg-green-700 text-white px-4 py-3 rounded-md font-semibold text-base shadow flex items-center justify-center gap-2">
            <i class="fas fa-plus"></i> Nova Venda
            <span class="text-xs bg-white text-green-700 font-bold px-2 py-0.5 rounded ml-2">F1</span>
          </button>
        </form>
        <!-- Botão Avulso -->
        <button type="button" id="btnAvulso" class="bg-pink-600 hover:bg-pink-700 text-white px-4 py-2 rounded-lg font-semibold shadow flex items-center gap-2">
          <i class="fas fa-bolt"></i> Avulso
        </button>
        <!-- Consultar Preço -->
        <button id="btnConsultarPreco" class="bg-yellow-500 hover:bg-yellow-700 text-white px-4 py-2 rounded-lg font-semibold shadow flex items-center gap-2 mr-2">
          <i class="fas fa-search-dollar"></i> Consultar Preço (F2)
        </button>

        <!-- Cancelar Venda -->
        <button id="btnCancelarVenda" class="bg-red-600 hover:bg-red-800 text-white px-4 py-2 rounded-lg font-semibold shadow flex items-center gap-2">
          <i class="fas fa-times-circle"></i> Cancelar Venda (F3)
          </button>
      </div>


      <!-- Campo de Busca de Produto com Ícones -->
<form onsubmit="return false;" class="flex gap-3 mb-4 items-center">
    <div class="relative w-full">
      <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-blue-600">
        <i class="fas fa-search"></i>
      </span>
      <select name="product_id" class="pl-10 pr-4 py-3 rounded-lg w-full border border-blue-300 shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500" required>
        <option value="">Selecione um produto...</option>
        @foreach($products as $product)
          <option value="{{ $product->id }}" data-preco="{{ $product->sale_price }}">{{ $product->name }} @if($product->code) ({{ $product->code }}) @endif</option>
        @endforeach
      </select>
    </div>
    <div class="relative w-28">
      <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-blue-600">
        <i class="fas fa-box"></i>
      </span>
      <input type="number" name="quantity" value="1" min="1" class="pl-10 pr-2 py-3 rounded-lg w-full border border-blue-300 shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Qtd" required>
    </div>
    <button id="btnAdicionar" class="bg-blue-700 hover:bg-blue-900 text-white px-5 py-3 rounded-lg font-semibold shadow flex items-center gap-2">
      <i class="fas fa-plus-circle"></i> Adicionar
    </button>
  </form>


      <!-- Tabela de Itens no Carrinho -->
      <div class="flex-1 overflow-y-auto rounded-lg border border-blue-200 bg-gray-50">
        <table class="min-w-full text-sm">
          <thead class="bg-blue-100 text-blue-900 sticky top-0">
            <tr>
              <th class="p-3 text-left">Produto</th>
              <th class="p-3 text-center">Qtd</th>
              <th class="p-3 text-right">Unitário</th>
              <th class="p-3 text-right">Total</th>
              <th class="p-3 text-center">Ação</th>
            </tr>
          </thead>
          <tbody id="carrinho-tbody">
            <!-- Itens do carrinho renderizados via JS -->
          </tbody>
        </table>
      </div>
    </div>

    <!-- Resumo da Venda -->
    <div class="w-full md:w-[420px] bg-gradient-to-br from-blue-50 to-blue-100 rounded-2xl p-6 shadow-xl flex flex-col justify-between">
        <div>
          <!-- Título -->
          <h2 class="text-2xl font-bold text-blue-900 mb-6 flex items-center gap-2">
            <i class="fas fa-cash-register"></i> Resumo da Venda
          </h2>

          <!-- Logo na sidebar -->
          <div class="flex justify-center mb-6">
            <img src="/imagens/logo.png" alt="Logo" class="h-16 hidden md:block">
            <img src="/imagens/logo_fechado.png" alt="Logo Mobile" class="h-12 md:hidden">
          </div>

          <!-- Totais -->
          <div class="space-y-3 text-base">
            <div class="flex justify-between items-center border-b pb-1">
              <span class="text-gray-700 font-semibold flex items-center gap-2"><i class="fas fa-shopping-cart"></i> Subtotal:</span>
              <span id="subtotal" class="text-gray-800 font-bold">R$ 0,00</span>
            </div>
            <div class="flex justify-between items-center border-b pb-1">
              <span class="text-gray-700 font-semibold flex items-center gap-2"><i class="fas fa-tag"></i> Desconto:</span>
              <span class="text-yellow-600 font-bold">R$ 0,00</span>
            </div>
            <div class="flex justify-between items-center bg-green-100 px-3 py-2 rounded-md shadow-inner">
              <span class="text-green-800 font-bold flex items-center gap-2"><i class="fas fa-hand-holding-usd"></i> Total Final:</span>
              <span id="totalfinal" class="text-green-800 text-xl font-bold">R$ 0,00</span>
            </div>
          </div>
        </div>

        <!-- Botões de Finalização -->
        @if($sale && $sale->items->count())
        <div class="flex flex-col md:flex-row gap-3 mt-8">
          <form action="{{ route('pdv.finalizeWithInvoice') }}" method="POST" class="flex-1">
            @csrf
            <button type="submit" class="w-full bg-green-700 hover:bg-green-800 text-white py-3 rounded-lg text-base font-bold flex items-center justify-center gap-2 transition">
              <i class="fas fa-file-invoice"></i> Finalizar c/ Nota <span class="text-xs bg-white text-green-800 font-bold px-2 py-0.5 rounded">F4</span>
            </button>
          </form>
          <form action="{{ route('pdv.finalizeWithoutInvoice') }}" method="POST" class="flex-1">
            @csrf
            <button type="submit" class="w-full bg-blue-700 hover:bg-blue-900 text-white py-3 rounded-lg text-base font-bold flex items-center justify-center gap-2 transition">
              <i class="fas fa-receipt"></i> Finalizar s/ Nota <span class="text-xs bg-white text-blue-800 font-bold px-2 py-0.5 rounded">F5</span>
            </button>
          </form>
        </div>
        @endif
        <!-- Botão Finalizar Venda no final do resumo -->
        <div class="mt-8 flex justify-center">
          <button id="btnFinalizarVenda" class="w-full bg-green-700 hover:bg-green-900 text-white py-3 rounded-lg font-semibold text-base shadow flex items-center justify-center gap-2 max-w-xs">
            <i class="fas fa-cash-register"></i> Finalizar Venda
          </button>
        </div>
      </div>

  </div>

  <!-- Modal Consulta de Preço -->
  <dialog id="modalConsultaPreco" class="z-50 p-0 w-full max-w-lg m-auto bg-transparent transition-all duration-300">
    <div class="fixed inset-0 flex items-center justify-center z-40">
      <div class="absolute inset-0 bg-black/40 backdrop-blur-sm transition-opacity duration-300"></div>
      <form method="dialog" class="relative bg-white rounded-2xl p-8 w-full max-w-lg mx-auto z-50 shadow-2xl animate-fade-in" onsubmit="return false;">
        <button type="button" onclick="modalConsultaPreco.close()" class="absolute top-4 right-4 text-gray-400 hover:text-red-600 text-3xl transition"><i class="fas fa-times-circle"></i></button>
        <div class="flex flex-col items-center mb-6">
          <div class="bg-blue-100 text-blue-700 rounded-full p-4 mb-2 shadow"><i class="fas fa-barcode fa-2x"></i></div>
          <h2 class="text-2xl font-bold text-blue-900 flex items-center gap-2"><i class="fas fa-search"></i> Consulta de Preço</h2>
          <p class="text-gray-500 text-sm mt-2">Digite o nome ou código do produto para consultar o preço.</p>
        </div>
        <div class="relative mb-4">
          <span class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-blue-600">
            <i class="fas fa-search"></i>
          </span>
          <input type="text" id="consultaPrecoInput" class="form-input w-full pl-12 pr-4 py-3 rounded-lg border border-blue-300 text-lg focus:ring-2 focus:ring-blue-400" placeholder="Ex: Notebook, NB001..." autocomplete="off" autofocus>
        </div>
        <div id="consultaPrecoResultados" class="space-y-2 max-h-64 overflow-y-auto">
          <!-- Resultados AJAX aqui -->
        </div>
      </form>
    </div>
  </dialog>
  <script>
  const input = document.getElementById('consultaPrecoInput');
  const resultados = document.getElementById('consultaPrecoResultados');
  if(input) {
    input.addEventListener('input', function() {
      const termo = this.value.trim();
      resultados.innerHTML = '';
      if(termo.length < 2) return;
      fetch('/pdv/consulta-preco', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({ termo })
      })
      .then(res => res.json())
      .then(data => {
        if(data.length === 0) {
          resultados.innerHTML = '<div class="text-gray-400 text-center py-4">Nenhum produto encontrado</div>';
        } else {
          resultados.innerHTML = data.map(prod => `
            <div class="flex items-center justify-between bg-blue-50 rounded-lg px-4 py-3 shadow hover:bg-blue-100 transition mb-1">
              <div>
                <div class="font-bold text-blue-900 text-lg">${prod.name}</div>
                <div class="text-xs text-gray-500">Cód: ${prod.internal_code ?? prod.id}</div>
              </div>
              <div class="text-2xl font-bold text-green-700">R$ ${parseFloat(prod.sale_price).toFixed(2).replace('.', ',')}</div>
            </div>
          `).join('');
        }
      });
    });
  }
  </script>
  <script>
  // Carrinho local
  let carrinho = [];

  function renderCarrinho() {
      const tbody = document.querySelector('#carrinho-tbody');
      tbody.innerHTML = '';
      if (carrinho.length === 0) {
          tbody.innerHTML = '<tr><td colspan="5" class="text-center text-gray-400">Nenhum item no carrinho</td></tr>';
      } else {
          carrinho.forEach((item, idx) => {
              tbody.innerHTML += `<tr class="border-t hover:bg-white">
                  <td class="p-3">${item.nome}</td>
                  <td class="p-3 text-center">${item.qtd}</td>
                  <td class="p-3 text-right">R$ ${item.unitario.toFixed(2).replace('.', ',')}</td>
                  <td class="p-3 text-right">R$ ${(item.unitario * item.qtd).toFixed(2).replace('.', ',')}</td>
                  <td class="p-3 text-center">
                      <button onclick="removerItem(${idx})" class="text-white bg-red-500 hover:bg-red-700 px-3 py-1 rounded-full shadow inline-flex items-center gap-1">
                          Remover
                      </button>
                  </td>
              </tr>`;
          });
      }
      atualizarResumo();
  }

  function adicionarItem() {
      const select = document.querySelector('select[name="product_id"]');
      const inputQtd = document.querySelector('input[name="quantity"]');
      if (!select.value || !inputQtd.value) return;
      const nome = select.options[select.selectedIndex].text;
      const unitario = parseFloat(select.options[select.selectedIndex].getAttribute('data-preco'));
      carrinho.push({
          nome,
          qtd: parseInt(inputQtd.value),
          unitario
      });
      renderCarrinho();
      inputQtd.value = 1;
  }

  function removerItem(idx) {
      carrinho.splice(idx, 1);
      renderCarrinho();
  }

  function atualizarResumo() {
      let subtotal = 0;
      carrinho.forEach(item => subtotal += item.unitario * item.qtd);
      document.getElementById('subtotal').innerText = 'R$ ' + subtotal.toFixed(2).replace('.', ',');
      document.getElementById('totalfinal').innerText = 'R$ ' + subtotal.toFixed(2).replace('.', ',');
  }

  document.addEventListener('DOMContentLoaded', function() {
      renderCarrinho();
      document.getElementById('btnAdicionar').addEventListener('click', function(e) {
          e.preventDefault();
          adicionarItem();
      });
      document.getElementById('btnConsultarPreco').addEventListener('click', function() {
          document.getElementById('modalConsultaPreco').showModal();
      });
      document.getElementById('btnCancelarVenda').addEventListener('click', function() {
          if (confirm('Tem certeza que deseja cancelar a venda?')) {
              carrinho = [];
              renderCarrinho();
              atualizarResumo();
          }
      });
      if (document.getElementById('btnEmitirNF')) {
        document.getElementById('btnEmitirNF').onclick = function() {
          gerarPDF('nf');
          carrinho = [];
          renderCarrinho();
          atualizarResumo();
          document.getElementById('modalFinalizarVenda').close();
        };
      }
      if (document.getElementById('btnCupomSimples')) {
        document.getElementById('btnCupomSimples').onclick = function() {
          gerarPDF('cupom');
          carrinho = [];
          renderCarrinho();
          atualizarResumo();
          document.getElementById('modalFinalizarVenda').close();
        };
      }
      document.getElementById('btnAvulso').addEventListener('click', function() {
        document.getElementById('modalAvulso').showModal();
      });
  });
  </script>
  <style>
  @keyframes fade-in {
    from { opacity: 0; transform: scale(0.95); }
    to { opacity: 1; transform: scale(1); }
  }
  .animate-fade-in { animation: fade-in 0.25s cubic-bezier(.4,0,.2,1) both; }
  </style>
  <script>
  document.getElementById('btnFinalizarVenda').addEventListener('click', function() {
    document.getElementById('modalFinalizarVenda').showModal();
  });
  </script>
  <!-- Adicionar jsPDF -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
  <script>
  // Função para gerar PDF do cupom/nota
  function gerarPDF(tipo) {
    const doc = new window.jspdf.jsPDF({ unit: 'mm', format: 'a4' });
    let y = 20;
    if (tipo === 'nf') {
      // Cabeçalho NF
      doc.setFontSize(16);
      doc.text('NOTA FISCAL ELETRÔNICA', 105, y, { align: 'center' });
      y += 10;
      doc.setFontSize(11);
      doc.text('Loja Exemplo Ltda. - CNPJ: 12.345.678/0001-99', 105, y, { align: 'center' });
      y += 7;
      doc.text('Endereço: Rua das Vendas, 123 - Centro - Cidade/UF', 105, y, { align: 'center' });
      y += 7;
      doc.text('Data: ' + new Date().toLocaleString(), 105, y, { align: 'center' });
      y += 10;
      doc.setLineWidth(0.5);
      doc.line(15, y, 195, y);
      y += 5;
      doc.setFontSize(12);
      doc.text('Itens:', 15, y);
      y += 7;
      doc.setFont('helvetica', 'bold');
      doc.text('Produto', 18, y);
      doc.text('Qtd', 90, y);
      doc.text('Unit.', 110, y);
      doc.text('Total', 140, y);
      doc.setFont('helvetica', 'normal');
      y += 6;
      let subtotal = 0;
      carrinho.forEach((item, idx) => {
        doc.text(item.nome, 18, y);
        doc.text(String(item.qtd), 92, y, { align: 'right' });
        doc.text('R$ ' + item.unitario.toFixed(2).replace('.', ','), 120, y, { align: 'right' });
        doc.text('R$ ' + (item.unitario * item.qtd).toFixed(2).replace('.', ','), 150, y, { align: 'right' });
        y += 6;
        subtotal += item.unitario * item.qtd;
      });
      y += 4;
      doc.setLineWidth(0.2);
      doc.line(15, y, 195, y);
      y += 7;
      doc.setFontSize(13);
      doc.setFont('helvetica', 'bold');
      doc.text('TOTAL:', 110, y);
      doc.text('R$ ' + subtotal.toFixed(2).replace('.', ','), 150, y, { align: 'right' });
      y += 12;
      doc.setFontSize(11);
      doc.setFont('helvetica', 'normal');
      doc.text('Documento sem valor fiscal real. Exemplo de PDV.', 105, y, { align: 'center' });
    } else {
      // Cupom simples
      doc.setFontSize(15);
      doc.text('RECIBO DE COMPRA', 105, y, { align: 'center' });
      y += 10;
      doc.setFontSize(11);
      doc.text('Loja Exemplo Ltda.', 105, y, { align: 'center' });
      y += 7;
      doc.text('CNPJ: 12.345.678/0001-99', 105, y, { align: 'center' });
      y += 7;
      doc.text('Data: ' + new Date().toLocaleString(), 105, y, { align: 'center' });
      y += 10;
      doc.setLineWidth(0.5);
      doc.line(30, y, 180, y);
      y += 5;
      doc.setFontSize(12);
      doc.text('Itens:', 32, y);
      y += 7;
      let subtotal = 0;
      carrinho.forEach((item, idx) => {
        doc.text(`${item.nome} x${item.qtd} - R$ ${(item.unitario * item.qtd).toFixed(2).replace('.', ',')}`, 32, y);
        y += 7;
        subtotal += item.unitario * item.qtd;
      });
      y += 4;
      doc.setLineWidth(0.2);
      doc.line(30, y, 180, y);
      y += 7;
      doc.setFontSize(13);
      doc.setFont('helvetica', 'bold');
      doc.text('TOTAL:', 120, y);
      doc.text('R$ ' + subtotal.toFixed(2).replace('.', ','), 180, y, { align: 'right' });
      y += 12;
      doc.setFontSize(11);
      doc.setFont('helvetica', 'normal');
      doc.text('Obrigado pela preferência!', 105, y, { align: 'center' });
    }
    doc.save(tipo === 'nf' ? 'nota-fiscal.pdf' : 'cupom-simples.pdf');
  }
  </script>
  <!-- Modal Finalizar Venda -->
  <dialog id="modalFinalizarVenda" class="z-50 p-0 w-full max-w-md m-auto bg-transparent transition-all duration-300">
    <div class="fixed inset-0 flex items-center justify-center z-40">
      <div class="absolute inset-0 bg-black/40 backdrop-blur-sm transition-opacity duration-300"></div>
      <form method="dialog" class="relative bg-white rounded-2xl p-8 w-full max-w-md mx-auto z-50 shadow-2xl animate-fade-in flex flex-col items-center gap-6" onsubmit="return false;">
        <button type="button" onclick="modalFinalizarVenda.close()" class="absolute top-4 right-4 text-gray-400 hover:text-red-600 text-3xl transition"><i class="fas fa-times-circle"></i></button>
        <h2 class="text-2xl font-bold text-blue-900 flex items-center gap-2 mb-2"><i class="fas fa-cash-register"></i> Finalizar Venda</h2>
        <p class="text-gray-600 text-center">Como deseja finalizar a venda?</p>
        <div class="flex gap-4 w-full">
          <button type="button" id="btnEmitirNF" class="flex-1 bg-green-700 hover:bg-green-900 text-white py-3 rounded-lg font-semibold text-base shadow flex items-center justify-center gap-2">
            <i class="fas fa-file-invoice"></i> Emitir NF
          </button>
          <button type="button" id="btnCupomSimples" class="flex-1 bg-blue-700 hover:bg-blue-900 text-white py-3 rounded-lg font-semibold text-base shadow flex items-center justify-center gap-2">
            <i class="fas fa-receipt"></i> Cupom Simples
          </button>
        </div>
      </form>
    </div>
  </dialog>
  <!-- Modal Pagamento -->
  <dialog id="modalPagamento" class="z-50 p-0 w-full max-w-md m-auto bg-transparent transition-all duration-300">
    <div class="fixed inset-0 flex items-center justify-center z-40">
      <div class="absolute inset-0 bg-black/40 backdrop-blur-sm transition-opacity duration-300"></div>
      <form method="dialog" class="relative bg-white rounded-2xl p-8 w-full max-w-md mx-auto z-50 shadow-2xl animate-fade-in flex flex-col items-center gap-6" onsubmit="return false;">
        <button type="button" onclick="modalPagamento.close()" class="absolute top-4 right-4 text-gray-400 hover:text-red-600 text-3xl transition"><i class="fas fa-times-circle"></i></button>
        <h2 class="text-2xl font-bold text-blue-900 flex items-center gap-2 mb-2"><i class="fas fa-credit-card"></i> Pagamento</h2>
        <p class="text-gray-600 text-center">Informe o valor pago em cada forma de pagamento:</p>
        <div class="w-full flex flex-col gap-3">
          <div class="flex items-center gap-2">
            <span class="w-24 text-right">Dinheiro:</span>
            <input type="number" min="0" step="0.01" id="pgDinheiro" class="form-input flex-1 px-4 py-2 rounded border border-blue-300" placeholder="0,00">
          </div>
          <div class="flex items-center gap-2">
            <span class="w-24 text-right">Pix:</span>
            <input type="number" min="0" step="0.01" id="pgPix" class="form-input flex-1 px-4 py-2 rounded border border-blue-300" placeholder="0,00">
          </div>
          <div class="flex items-center gap-2">
            <span class="w-24 text-right">Cartão:</span>
            <input type="number" min="0" step="0.01" id="pgCartao" class="form-input flex-1 px-4 py-2 rounded border border-blue-300" placeholder="0,00">
          </div>
        </div>
        <button type="button" id="btnConfirmarPagamento" class="w-full bg-green-700 hover:bg-green-900 text-white py-3 rounded-lg font-semibold text-base shadow flex items-center justify-center gap-2 mt-4">
          <i class="fas fa-check-circle"></i> Confirmar Pagamento
        </button>
      </form>
    </div>
  </dialog>
  <script>
  document.addEventListener('DOMContentLoaded', function() {
    // ... outros eventos ...
    document.getElementById('btnFinalizarVenda').addEventListener('click', function() {
      document.getElementById('modalFinalizarVenda').showModal();
    });
    document.getElementById('btnEmitirNF').onclick = function() {
      document.getElementById('modalFinalizarVenda').close();
      document.getElementById('modalPagamento').showModal();
      window.tipoFinalizacao = 'nf';
    };
    document.getElementById('btnCupomSimples').onclick = function() {
      document.getElementById('modalFinalizarVenda').close();
      document.getElementById('modalPagamento').showModal();
      window.tipoFinalizacao = 'cupom';
    };
    document.getElementById('btnConfirmarPagamento').onclick = async function() {
      const dinheiro = parseFloat(document.getElementById('pgDinheiro').value) || 0;
      const pix = parseFloat(document.getElementById('pgPix').value) || 0;
      const cartao = parseFloat(document.getElementById('pgCartao').value) || 0;
      const total = dinheiro + pix + cartao;
      let subtotal = 0;
      carrinho.forEach(item => subtotal += item.unitario * item.qtd);
      if (total < subtotal) {
        alert('O valor total pago é menor que o total da venda!');
        return;
      }
      // Corrigir envio do ID real do produto
      const select = document.querySelector('select[name="product_id"]');
      const itens = carrinho.map(item => {
        let id = '';
        for (let opt of select.options) {
          if (opt.text.trim() === item.nome.trim()) {
            id = opt.value;
            break;
          }
        }
        return {
          id,
          nome: item.nome,
          qtd: item.qtd,
          unitario: item.unitario
        };
      });
      const pagamentos = [
        { tipo: 'dinheiro', valor: dinheiro },
        { tipo: 'pix', valor: pix },
        { tipo: 'cartao', valor: cartao }
      ];
      try {
        const resp = await fetch('/pdv/finalizar', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '{{ csrf_token() }}'
          },
          body: JSON.stringify({ itens, pagamentos })
        });
        const data = await resp.json();
        if (data.success) {
          document.getElementById('modalPagamento').close();
          gerarPDF(window.tipoFinalizacao);
          carrinho = [];
          renderCarrinho();
          atualizarResumo();
        } else {
          alert(data.error || 'Erro ao registrar venda!');
        }
      } catch (e) {
        alert('Erro ao registrar venda!');
      }
    };
    document.getElementById('btnSalvarAvulso').addEventListener('click', function() {
      const nome = document.getElementById('avulsoNome').value.trim();
      const unitario = parseFloat(document.getElementById('avulsoValor').value);
      const qtd = parseInt(document.getElementById('avulsoQtd').value);
      if (!nome || isNaN(unitario) || unitario <= 0 || isNaN(qtd) || qtd < 1) {
        alert('Preencha todos os campos corretamente!');
        return;
      }
      carrinho.push({ nome, qtd, unitario });
      renderCarrinho();
      atualizarResumo();
      document.getElementById('modalAvulso').close();
      document.getElementById('avulsoNome').value = '';
      document.getElementById('avulsoValor').value = '';
      document.getElementById('avulsoQtd').value = 1;
    });
  });
  </script>
  @if(!$register)
    <div class="fixed inset-0 bg-black/60 z-50 flex items-center justify-center">
      <div class="bg-white rounded-2xl shadow-2xl p-10 text-center max-w-md mx-auto">
        <h2 class="text-2xl font-bold text-red-700 mb-4 flex items-center justify-center gap-2"><i class="fas fa-lock"></i> Caixa Fechado</h2>
        <p class="text-gray-700 mb-4">Você precisa abrir um caixa para operar o PDV.</p>
        <a href="{{ route('caixa.index') }}" class="bg-blue-700 hover:bg-blue-900 text-white px-6 py-3 rounded-lg font-semibold shadow inline-block">Ir para Frente de Caixa</a>
      </div>
    </div>
    <script>
      document.addEventListener('DOMContentLoaded', function() {
        // Desabilita todos os botões do PDV
        document.querySelectorAll('button, input, select').forEach(el => {
          el.disabled = true;
        });
      });
    </script>
  @endif
  <!-- Modal Avulso -->
  <dialog id="modalAvulso" class="z-50 p-0 w-full max-w-sm m-auto bg-transparent transition-all duration-300">
    <div class="fixed inset-0 flex items-center justify-center z-40">
      <div class="absolute inset-0 bg-black/40 backdrop-blur-sm transition-opacity duration-300"></div>
      <form method="dialog" class="relative bg-white rounded-2xl p-8 w-full max-w-sm mx-auto z-50 shadow-2xl animate-fade-in flex flex-col gap-4" onsubmit="return false;">
        <button type="button" onclick="modalAvulso.close()" class="absolute top-4 right-4 text-gray-400 hover:text-red-600 text-3xl transition"><i class="fas fa-times-circle"></i></button>
        <h2 class="text-xl font-bold text-pink-700 flex items-center gap-2 mb-2"><i class="fas fa-bolt"></i> Produto Avulso</h2>
        <input type="text" id="avulsoNome" class="form-input px-4 py-2 rounded border border-pink-300" placeholder="Nome do produto" required autofocus>
        <input type="number" id="avulsoValor" class="form-input px-4 py-2 rounded border border-pink-300" placeholder="Valor (R$)" min="0.01" step="0.01" required>
        <input type="number" id="avulsoQtd" class="form-input px-4 py-2 rounded border border-pink-300" placeholder="Quantidade" min="1" value="1" required>
        <button type="button" id="btnSalvarAvulso" class="bg-pink-600 hover:bg-pink-700 text-white py-2 rounded-lg font-semibold shadow flex items-center justify-center gap-2 mt-2">
          <i class="fas fa-plus-circle"></i> Adicionar ao Carrinho
        </button>
      </form>
    </div>
  </dialog>
</body>
</html>
