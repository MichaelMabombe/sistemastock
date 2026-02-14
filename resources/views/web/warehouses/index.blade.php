@extends('layouts.app')
@section('title', 'Armazens')
@section('content')
<div class="panel"><a class="btn btn-primary" href="{{ route('web.warehouses.create') }}">Novo armazem</a></div>
<div class="panel">
    <table>
        <thead><tr><th>Nome</th><th>Codigo</th><th>Cidade</th><th>Ativo</th><th>Acoes</th></tr></thead>
        <tbody>
        @foreach($warehouses as $warehouse)
            <tr>
                <td>{{ $warehouse->name }}</td>
                <td>{{ $warehouse->code }}</td>
                <td>{{ $warehouse->city }}</td>
                <td>{{ $warehouse->is_active ? 'Sim' : 'Nao' }}</td>
                <td>
                    <a class="btn btn-muted" href="{{ route('web.warehouses.edit', $warehouse) }}">Editar</a>
                    <form class="inline" method="POST" action="{{ route('web.warehouses.destroy', $warehouse) }}">
                        @csrf @method('DELETE')
                        <button class="btn btn-danger">Apagar</button>
                    </form>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
    {{ $warehouses->links() }}
</div>
@endsection

