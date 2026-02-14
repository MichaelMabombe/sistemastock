@extends('layouts.app')
@section('title', 'Produtos')
@section('content')
<div class="panel"><a class="btn btn-primary" href="{{ route('web.products.create') }}">Novo produto</a></div>
<div class="panel">
    <table>
        <thead><tr><th>Nome</th><th>Codigo</th><th>Categoria</th><th>Stock</th><th>Compra</th><th>Venda</th><th>Acoes</th></tr></thead>
        <tbody>
        @foreach($products as $product)
            <tr>
                <td>{{ $product->name }}</td>
                <td>{{ $product->internal_code }}</td>
                <td>{{ $product->category->name ?? '-' }}</td>
                <td>{{ number_format($product->stock_current, 3, ',', '.') }}</td>
                <td>{{ number_format($product->purchase_price, 2, ',', '.') }}</td>
                <td>{{ number_format($product->sale_price, 2, ',', '.') }}</td>
                <td>
                    <a class="btn btn-muted" href="{{ route('web.products.edit', $product) }}">Editar</a>
                    <form class="inline" method="POST" action="{{ route('web.products.destroy', $product) }}">
                        @csrf @method('DELETE')
                        <button class="btn btn-danger">Apagar</button>
                    </form>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
    {{ $products->links() }}
</div>
@endsection

