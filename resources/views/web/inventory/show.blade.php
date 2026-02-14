@extends('layouts.app')
@section('title', 'Inventario #'.$inventoryCount->id)
@section('content')
<div class="panel">
    <div><strong>Armazem:</strong> {{ $inventoryCount->warehouse->name ?? '-' }}</div>
    <div><strong>Status:</strong> {{ $inventoryCount->status->value ?? $inventoryCount->status }}</div>
    <div><strong>Data:</strong> {{ optional($inventoryCount->counted_at)->format('d/m/Y H:i') }}</div>
    <div><strong>Notas:</strong> {{ $inventoryCount->notes }}</div>
    @if(($inventoryCount->status->value ?? $inventoryCount->status) === 'open')
    <form method="POST" action="{{ route('web.inventory.close', $inventoryCount) }}" style="margin-top:10px">
        @csrf
        <button class="btn btn-primary">Fechar inventario e ajustar stock</button>
    </form>
    @endif
</div>

<div class="panel">
    <table>
        <thead><tr><th>Produto</th><th>Sistema</th><th>Fisico</th><th>Diferenca</th><th>Ajustado</th></tr></thead>
        <tbody>
        @foreach($inventoryCount->items as $item)
            <tr>
                <td>{{ $item->product->name ?? '-' }}</td>
                <td>{{ number_format($item->system_quantity, 3, ',', '.') }}</td>
                <td>{{ number_format($item->physical_quantity, 3, ',', '.') }}</td>
                <td>{{ number_format($item->difference_quantity, 3, ',', '.') }}</td>
                <td>{{ $item->adjusted ? 'Sim' : 'Nao' }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
@endsection

