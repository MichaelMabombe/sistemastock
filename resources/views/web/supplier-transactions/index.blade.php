@extends('layouts.app')
@section('title', 'Financeiro de Fornecedores')
@section('content')
<div class="panel">
    <h3>Registrar divida/pagamento</h3>
    <form method="POST" action="{{ route('web.supplier-transactions.store') }}">
        @csrf
        <div class="row">
            <div>
                <label>Fornecedor</label>
                <select name="supplier_id" required>
                    <option value="">Selecione</option>
                    @foreach($suppliers as $supplier)<option value="{{ $supplier->id }}">{{ $supplier->name }}</option>@endforeach
                </select>
            </div>
            <div><label>Tipo</label><select name="type">@foreach($types as $type)<option value="{{ $type }}">{{ $type }}</option>@endforeach</select></div>
            <div><label>Valor</label><input type="number" step="0.01" name="amount" required></div>
        </div>
        <div class="row">
            <div><label>Referencia</label><input name="reference"></div>
            <div><label>Vencimento</label><input type="date" name="due_date"></div>
            <div><label>Pago em</label><input type="date" name="paid_at"></div>
        </div>
        <label>Notas</label><input name="notes">
        <button class="btn btn-primary" type="submit">Registrar</button>
    </form>
</div>

<div class="panel">
    <table>
        <thead><tr><th>Data</th><th>Fornecedor</th><th>Tipo</th><th>Valor</th><th>Referencia</th><th>Vencimento</th><th>Pago</th></tr></thead>
        <tbody>
        @foreach($transactions as $tx)
            <tr>
                <td>{{ $tx->created_at->format('d/m/Y H:i') }}</td>
                <td>{{ $tx->supplier->name ?? '-' }}</td>
                <td>{{ $tx->type->value ?? $tx->type }}</td>
                <td>{{ number_format($tx->amount, 2, ',', '.') }}</td>
                <td>{{ $tx->reference }}</td>
                <td>{{ optional($tx->due_date)->format('d/m/Y') }}</td>
                <td>{{ optional($tx->paid_at)->format('d/m/Y') }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
    {{ $transactions->links() }}
</div>
@endsection

