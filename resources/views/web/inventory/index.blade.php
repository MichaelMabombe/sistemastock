@extends('layouts.app')
@section('title', 'Inventario Fisico')
@section('content')
<div class="panel">
    <h3>Novo inventario</h3>
    <form method="POST" action="{{ route('web.inventory.store') }}">
        @csrf
        <div class="row">
            <div>
                <label>Armazem</label>
                <select name="warehouse_id" required>
                    <option value="">Selecione</option>
                    @foreach($warehouses as $warehouse)<option value="{{ $warehouse->id }}">{{ $warehouse->name }}</option>@endforeach
                </select>
            </div>
            <div><label>Observacoes</label><input name="notes"></div>
        </div>
        <div id="items">
            <div class="row item-row">
                <div>
                    <label>Produto</label>
                    <select name="items[0][product_id]" required>
                        <option value="">Selecione</option>
                        @foreach($products as $product)<option value="{{ $product->id }}">{{ $product->name }}</option>@endforeach
                    </select>
                </div>
                <div>
                    <label>Quantidade fisica</label>
                    <input type="number" step="0.001" name="items[0][physical_quantity]" required>
                </div>
            </div>
        </div>
        <button class="btn btn-muted" type="button" onclick="addItem()">Adicionar item</button>
        <button class="btn btn-primary" type="submit">Criar inventario</button>
    </form>
</div>

<div class="panel">
    <table>
        <thead><tr><th>ID</th><th>Armazem</th><th>Status</th><th>Data</th><th>Acoes</th></tr></thead>
        <tbody>
        @foreach($inventoryCounts as $count)
            <tr>
                <td>#{{ $count->id }}</td>
                <td>{{ $count->warehouse->name ?? '-' }}</td>
                <td>{{ $count->status->value ?? $count->status }}</td>
                <td>{{ optional($count->counted_at)->format('d/m/Y H:i') }}</td>
                <td><a class="btn btn-muted" href="{{ route('web.inventory.show', $count) }}">Ver</a></td>
            </tr>
        @endforeach
        </tbody>
    </table>
    {{ $inventoryCounts->links() }}
</div>

<script>
let itemIndex = 1;
function addItem() {
    const container = document.getElementById('items');
    const products = @json($products->map(fn($p) => ['id' => $p->id, 'name' => $p->name])->values());
    const wrapper = document.createElement('div');
    wrapper.className = 'row item-row';
    const options = ['<option value="">Selecione</option>'].concat(products.map(p => `<option value="${p.id}">${p.name}</option>`)).join('');
    wrapper.innerHTML = `<div><label>Produto</label><select name="items[${itemIndex}][product_id]" required>${options}</select></div>
        <div><label>Quantidade fisica</label><input type="number" step="0.001" name="items[${itemIndex}][physical_quantity]" required></div>`;
    container.appendChild(wrapper);
    itemIndex++;
}
</script>
@endsection

