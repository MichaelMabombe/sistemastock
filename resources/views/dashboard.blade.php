@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="panel">
    <div class="grid">
        <div class="kpi">Entradas (mes)<strong>{{ number_format($entries, 3, ',', '.') }}</strong></div>
        <div class="kpi">Saidas (mes)<strong>{{ number_format($exits, 3, ',', '.') }}</strong></div>
        <div class="kpi">Valor stock<strong>{{ number_format($stockValue, 2, ',', '.') }} MZN</strong></div>
        <div class="kpi">Transferencias pendentes<strong>{{ $pendingTransfers }}</strong></div>
        <div class="kpi">Alertas abertos<strong>{{ $openAlerts }}</strong></div>
    </div>
</div>

<div class="panel">
    <h3>Ultimos movimentos</h3>
    <table>
        <thead><tr><th>Data</th><th>Produto</th><th>Armazem</th><th>Tipo</th><th>Quantidade</th></tr></thead>
        <tbody>
        @forelse($latestMovements as $m)
            <tr>
                <td>{{ optional($m->performed_at)->format('d/m/Y H:i') }}</td>
                <td>{{ $m->product->name ?? '-' }}</td>
                <td>{{ $m->warehouse->name ?? '-' }}</td>
                <td>{{ $m->type->value ?? $m->type }}</td>
                <td>{{ number_format($m->quantity, 3, ',', '.') }}</td>
            </tr>
        @empty
            <tr><td colspan="5">Sem movimentos.</td></tr>
        @endforelse
        </tbody>
    </table>
</div>
@endsection

