@extends('layouts.app')
@section('title', 'Fornecedores')
@section('content')
<div class="panel"><a class="btn btn-primary" href="{{ route('web.suppliers.create') }}">Novo fornecedor</a></div>
<div class="panel">
    <table>
        <thead><tr><th>Nome</th><th>NUIT</th><th>Telefone</th><th>Email</th><th>Historico</th><th>Acoes</th></tr></thead>
        <tbody>
        @foreach($suppliers as $supplier)
            <tr>
                <td>{{ $supplier->name }}</td>
                <td>{{ $supplier->nuit }}</td>
                <td>{{ $supplier->phone }}</td>
                <td>{{ $supplier->email }}</td>
                <td>{{ $supplier->transactions_count }}</td>
                <td>
                    <a class="btn btn-muted" href="{{ route('web.suppliers.edit', $supplier) }}">Editar</a>
                    <form class="inline" method="POST" action="{{ route('web.suppliers.destroy', $supplier) }}">
                        @csrf @method('DELETE')
                        <button class="btn btn-danger">Apagar</button>
                    </form>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
    {{ $suppliers->links() }}
</div>
@endsection

