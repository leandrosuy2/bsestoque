@extends('dashboard.layout')
@section('content')
<h1>Comprovante de Venda PDV #{{ $sale->id }}</h1>
<p><strong>Operador:</strong> {{ $sale->user->name ?? '-' }}</p>
<p><strong>Data:</strong> {{ $sale->sold_at }}</p>
<h3>Itens</h3>
<table class="table table-bordered">
    <thead>
        <tr>
            <th>Produto</th>
            <th>Quantidade</th>
            <th>Valor Unitário</th>
            <th>Total</th>
        </tr>
    </thead>
    <tbody>
        @foreach($sale->items as $item)
        <tr>
            <td>{{ $item->product->name }}</td>
            <td>{{ $item->quantity }}</td>
            <td>R$ {{ number_format($item->unit_price, 2, ',', '.') }}</td>
            <td>R$ {{ number_format($item->total_price, 2, ',', '.') }}</td>
        </tr>
        @endforeach
    </tbody>
</table>
<h3>Pagamentos</h3>
<table class="table table-bordered">
    <thead>
        <tr>
            <th>Tipo</th>
            <th>Valor</th>
        </tr>
    </thead>
    <tbody>
        @foreach($sale->payments as $pay)
        <tr>
            <td>{{ ucfirst($pay->payment_type) }}</td>
            <td>R$ {{ number_format($pay->amount, 2, ',', '.') }}</td>
        </tr>
        @endforeach
    </tbody>
</table>
<h3>Totais</h3>
<p><strong>Total:</strong> R$ {{ number_format($sale->total, 2, ',', '.') }}</p>
<p><strong>Desconto:</strong> R$ {{ number_format($sale->discount, 2, ',', '.') }}</p>
<p><strong>Total Final:</strong> R$ {{ number_format($sale->final_total, 2, ',', '.') }}</p>
<a href="{{ route('pdv.index') }}" class="btn btn-secondary">Nova Venda</a>
<a href="{{ route('pdv.history') }}" class="btn btn-info">Histórico de Vendas</a>
@endsection
