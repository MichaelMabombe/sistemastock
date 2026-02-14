@extends('layouts.app')
@section('title', 'Transferencias')
@section('content')
<div class="panel">
    <h3>Nova transferencia</h3>
    <form method="POST" action="{{ route('web.transfers.store') }}">
        @csrf
        <div class="row">
            <div>
                <label>Origem</label>
                <select name="origin_warehouse_id" required>
                    <option value="">Selecione</option>
                    @foreach($warehouses as $warehouse)<option value="{{ $warehouse->id }}">{{ $warehouse->name }}</option>@endforeach
                </select>
            </div>
            <div>
                <label>Destino</label>
                <select name="destination_warehouse_id" required>
                    <option value="">Selecione</option>
                    @foreach($warehouses as $warehouse)<option value="{{ $warehouse->id }}">{{ $warehouse->name }}</option>@endforeach
                </select>
            </div>
            <div>
                <label>Produto</label>
                <select name="product_id" required>
                    <option value="">Selecione</option>
                    @foreach($products as $product)<option value="{{ $product->id }}">{{ $product->name }}</option>@endforeach
                </select>
            </div>
            <div><label>Quantidade</label><input type="number" step="0.001" name="quantity" required></div>
        </div>
        <label>Observacoes</label><input name="notes">
        <button class="btn btn-primary" type="submit">Criar transferencia</button>
    </form>
</div>

<div class="panel">
    <table>
        <thead><tr><th>Data</th><th>Produto</th><th>Origem</th><th>Destino</th><th>Qtd</th><th>Status</th><th>Acoes</th></tr></thead>
        <tbody>
        @foreach($transfers as $transfer)
            <tr>
                <td>{{ optional($transfer->requested_at)->format('d/m/Y H:i') }}</td>
                <td>{{ $transfer->product->name ?? '-' }}</td>
                <td>{{ $transfer->originWarehouse->name ?? '-' }}</td>
                <td>{{ $transfer->destinationWarehouse->name ?? '-' }}</td>
                <td>{{ number_format($transfer->quantity, 3, ',', '.') }}</td>
                <td>{{ $transfer->status->value ?? $transfer->status }}</td>
                <td>
                    @if(($transfer->status->value ?? $transfer->status) === 'pending')
                        <form class="inline" method="POST" action="{{ route('web.transfers.confirm', $transfer) }}">@csrf<button class="btn btn-primary">Confirmar</button></form>
                        <form class="inline" method="POST" action="{{ route('web.transfers.cancel', $transfer) }}">@csrf<button class="btn btn-danger">Cancelar</button></form>
                    @endif
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
    {{ $transfers->links() }}
</div>
@endsection

