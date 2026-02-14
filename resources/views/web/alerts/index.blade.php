@extends('layouts.app')
@section('title', 'Alertas')
@section('content')
<div class="panel">
    <h3>Novo alerta</h3>
    <form method="POST" action="{{ route('web.alerts.store') }}">
        @csrf
        <div class="row">
            <div>
                <label>Produto</label>
                <select name="product_id"><option value="">Opcional</option>@foreach($products as $product)<option value="{{ $product->id }}">{{ $product->name }}</option>@endforeach</select>
            </div>
            <div>
                <label>Armazem</label>
                <select name="warehouse_id"><option value="">Opcional</option>@foreach($warehouses as $warehouse)<option value="{{ $warehouse->id }}">{{ $warehouse->name }}</option>@endforeach</select>
            </div>
            <div><label>Tipo</label><select name="type">@foreach($types as $type)<option value="{{ $type }}">{{ $type }}</option>@endforeach</select></div>
            <div><label>Severidade (1-5)</label><input type="number" name="severity" min="1" max="5" required value="3"></div>
        </div>
        <label>Mensagem</label><input name="message" required>
        <button class="btn btn-primary" type="submit">Criar alerta</button>
    </form>
</div>

<div class="panel">
    <table>
        <thead><tr><th>Data</th><th>Tipo</th><th>Mensagem</th><th>Produto</th><th>Status</th><th>Acoes</th></tr></thead>
        <tbody>
        @foreach($alerts as $alert)
            <tr>
                <td>{{ $alert->created_at->format('d/m/Y H:i') }}</td>
                <td>{{ $alert->type->value ?? $alert->type }}</td>
                <td>{{ $alert->message }}</td>
                <td>{{ $alert->product->name ?? '-' }}</td>
                <td>{{ $alert->resolved_at ? 'Resolvido' : 'Aberto' }}</td>
                <td>
                    @if(!$alert->resolved_at)
                        <form class="inline" method="POST" action="{{ route('web.alerts.resolve', $alert) }}">@csrf<button class="btn btn-primary">Resolver</button></form>
                    @endif
                    <form class="inline" method="POST" action="{{ route('web.alerts.destroy', $alert) }}">@csrf @method('DELETE')<button class="btn btn-danger">Apagar</button></form>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
    {{ $alerts->links() }}
</div>
@endsection

