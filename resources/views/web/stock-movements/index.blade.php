@extends('layouts.app')
@section('title', 'Movimentos de Stock')
@section('content')
<div class="panel">
    <h3>Novo movimento</h3>
    <form method="POST" action="{{ route('web.stock-movements.store') }}">
        @csrf
        <div class="row">
            <div>
                <label>Produto</label>
                <select name="product_id" required>
                    <option value="">Selecione</option>
                    @foreach($products as $product)<option value="{{ $product->id }}">{{ $product->name }}</option>@endforeach
                </select>
            </div>
            <div>
                <label>Armazem</label>
                <select name="warehouse_id" required>
                    <option value="">Selecione</option>
                    @foreach($warehouses as $warehouse)<option value="{{ $warehouse->id }}">{{ $warehouse->name }}</option>@endforeach
                </select>
            </div>
            <div>
                <label>Tipo</label>
                <select name="type" required>
                    @foreach($types as $type)<option value="{{ $type }}">{{ $type }}</option>@endforeach
                </select>
            </div>
            <div><label>Quantidade</label><input type="number" step="0.001" name="quantity" required></div>
            <div><label>Preco unitario</label><input type="number" step="0.01" name="unit_price"></div>
            <div>
                <label>Direcao (ajuste)</label>
                <select name="direction"><option value="in">Entrada</option><option value="out">Saida</option></select>
            </div>
        </div>
        <label>Observacoes</label><input name="notes">
        <button class="btn btn-primary" type="submit">Registar</button>
    </form>
</div>

<div class="panel">
    <table>
        <thead><tr><th>Data</th><th>Produto</th><th>Armazem</th><th>Tipo</th><th>Qtd</th><th>Total</th></tr></thead>
        <tbody>
        @foreach($movements as $movement)
            <tr>
                <td>{{ optional($movement->performed_at)->format('d/m/Y H:i') }}</td>
                <td>{{ $movement->product->name ?? '-' }}</td>
                <td>{{ $movement->warehouse->name ?? '-' }}</td>
                <td>{{ $movement->type->value ?? $movement->type }}</td>
                <td>{{ number_format($movement->quantity, 3, ',', '.') }}</td>
                <td>{{ $movement->total_value ? number_format($movement->total_value, 2, ',', '.') : '-' }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
    {{ $movements->links() }}
</div>
@endsection

