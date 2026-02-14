@extends('layouts.app')
@section('title', 'Relatorios')
@section('content')
<div class="panel">
    <form method="GET" action="{{ route('web.reports.index') }}">
        <div class="row">
            <div><label>Data inicio</label><input type="date" name="start_date" value="{{ $start->format('Y-m-d') }}"></div>
            <div><label>Data fim</label><input type="date" name="end_date" value="{{ $end->format('Y-m-d') }}"></div>
        </div>
        <button class="btn btn-primary" type="submit">Filtrar</button>
    </form>
</div>

<div class="panel">
    <div class="grid">
        <div class="kpi">Entradas<strong>{{ number_format($entries, 3, ',', '.') }}</strong></div>
        <div class="kpi">Saidas<strong>{{ number_format($exits, 3, ',', '.') }}</strong></div>
        <div class="kpi">Valor estimado<strong>{{ number_format($estimatedValue, 2, ',', '.') }} MZN</strong></div>
        <div class="kpi">Margem estimada<strong>{{ number_format($estimatedMargin, 2, ',', '.') }} MZN</strong></div>
    </div>
</div>

<div class="panel">
    <h3>Produtos mais vendidos</h3>
    <table>
        <thead><tr><th>Produto</th><th>Codigo</th><th>Total vendido</th></tr></thead>
        <tbody>
        @forelse($topProducts as $top)
            <tr>
                <td>{{ $top->product->name ?? '-' }}</td>
                <td>{{ $top->product->internal_code ?? '-' }}</td>
                <td>{{ number_format($top->total_sold, 3, ',', '.') }}</td>
            </tr>
        @empty
            <tr><td colspan="3">Sem vendas no periodo.</td></tr>
        @endforelse
        </tbody>
    </table>
</div>
@endsection

